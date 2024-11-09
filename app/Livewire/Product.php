<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Products;
use App\Models\ProductImage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Category as Categories;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;
use PDF;

class Product extends Component
{
    use WithPagination,WithFileUploads;
    protected $perPage = 10;
    public $products=[],$category,$images,$product_name,$price,$product_id,$product_images;
    public $updateProduct = false;
    public $addProduct = false;
    public $selected = '';

    public $selectedProducts = []; // Array to hold selected checkboxes
    public $selectAll = false; // Boolean to track the header checkbox state
    
    public $viewProduct = false; // Boolean to track view modal state
    public $product = []; // To store the product being viewed
    
    protected $listeners = ['updateSelected' => 'updateSelected','productAdded' => 'refreshProductTable'];

    public function updatedSelectedProducts($value)
    {
        // Check if all products are selected
        if (count($this->selectedProducts) === Products::count()) {
            $this->selectAll = true;
        } else {
            $this->selectAll = false;
        }
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            // Select all products by adding all product ids to selected
            $this->selectedProducts = $this->products->pluck('id')->toArray();
        } else {
            // Unselect all
            $this->selectedProducts = [];
        }
    }

    public function updateSelected($data)
    {
        $this->selected = $data;
    }

    public function render()
    {
        $this->dispatch('post-created');
        
        // Fetch categories for dropdown selection
        $this->category = Categories::all();

        return view('livewire.product', [
            'products_data' => Products::with('product_images')->paginate($this->perPage)
        ]);
    }

    public function addProductData(){
        $this->category = Categories::all();
        $this->addProduct = true;
        $this->updateProduict = false;
    }

    public function productStore(){
        
        $this->validate([
            'product_name' => 'required',
            'price' => 'required',
            'images.*' => 'image',
        ]);

        $product = Products::create([
            'product_name' => $this->product_name,
            'price' => $this->price,
            'category_id' => (int)$this->selected,
            'user_id' => Auth::user()->id
        ]);

        if($this->images){
            foreach ($this->images as $key => $image) {
                $name = time() . $image->getClientOriginalName();
                $image->storeAs('/public/product_image/',$name);
                $customer_images = ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $name
                ]);
            }
        }

        $this->resetInputFields();
        $this->addProduct = false;
        session()->flash('success', 'Product created successfully!');
        
        // Dispatch the refresh event
        $this->dispatch('refreshProducts')->self();
    }

    private function resetInputFields(){
        $this->product_name = '';
        $this->price = '';
        $this->selected = '';
        $this->images = null;
    }

    public function refreshProductTable()
    {
        $this->resetPage(); // Reset pagination
    }

    public function editProduct($id){
        $products = Products::with('product_images')->where('id',$id)->first();
        $this->product_images = $products->product_images;
        $this->product_id = $id;
        $this->product_name = $products->product_name;
        $this->price = $products->price;
        $this->selected = $products->category_id;
        $this->updateProduct = !$this->updateProduct;
    }

    public function viewProductData($id){
        $this->product = Products::with('category', 'product_images')->findOrFail($id);
        $this->dispatch('showViewProductModal');
    }

    public function updateProductData(){
        $this->validate([
            'product_name' => 'required',
            'price' => 'required',
            'images.*' => 'required',
        ]);
        
        $product = Products::where('id',$this->product_id)->first();
        $product->update([
            'product_name' => $this->product_name,
            'price' => $this->price,
            'category_id' => $this->selected,
        ]);

        if($this->images){
            ProductImage::where('product_id',$this->product_id)->delete();
            foreach ($this->images as $key => $image) {
                $name = time() . $image->getClientOriginalName();
                $image->storeAs('/public/product_image/',$name);
                $product_images = ProductImage::create([
                    'product_id' => $this->product_id,
                    'image' => $name
                ]);
            }
        }

        session()->flash('message', 'Product Updated successfully!');
        $this->updateProduct = !$this->updateProduct;
        $this->resetInputFields();
    }

    public function deleteProduct($id){
        ProductImage::where('product_id',$id)->delete();
        Products::where('id',$id)->delete();
        session()->flash('message', 'Product Deleted Successfully.');
    } 

    public function deleteSelectedProducts(){
        Products::whereIn('id', $this->selectedProducts)->delete();
        ProductImage::whereIn('product_id', $this->selectedProducts)->delete();
        $this->selectAll = false;
        session()->flash('message', 'Selected Products Deleted Successfully.');
    }

    public function export($format)
    {
        // Handle PDF export
        if ($format === 'pdf') {
            $products = Products::with('category')->get();
            $pdf = PDF::loadView('exports.products', ['products' => $products]);
            return response()->streamDownload(function() use ($pdf) {
                echo $pdf->output();
            }, 'products.pdf');
        }
        
        return Excel::download(new ProductsExport(), 'products.' . $format);
    }

    public function refreshData()
    {
        $this->render();
    }

    public function loadProducts()
    {
        $this->products = Products::with('product_images')->paginate($this->perPage);
    }
}
