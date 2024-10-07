<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Products;
use App\Models\ProductImage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Category as Categories;
use Illuminate\Support\Facades\Auth;

class Product extends Component
{
    use WithFileUploads,WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $products,$category,$images,$product_name,$price,$product_id;
    public $updateProduct = false;
    public $addProduct = false;
    public $selected = '';
    
    protected $listeners = ['updateSelected' => 'updateSelected'];

    public function updateSelected($data)
    {
        $this->selected = $data;
    }

    public function render()
    {
        $this->dispatch('post-created');
        $this->category = Categories::all();
        $this->products = Products::with('product_images')->get();
        return view('livewire.product');
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

        session()->flash('message', 'Customer created successfully!');
        $this->addProduct = !$this->addProduct;
        $this->resetInputFields();
    }

    private function resetInputFields(){
        $this->product_name = '';
        $this->price = '';
        $this->selected = '';
        $this->images = '';
    }

    public function editProduct($id){
        $products = Products::with('product_images')->where('id',$id)->first();
        $this->product_id = $id;
        $this->product_name = $products->product_name;
        $this->price = $products->price;
        $this->selected = $products->category_id;
        $this->updateProduct = !$this->updateProduct;
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
}
