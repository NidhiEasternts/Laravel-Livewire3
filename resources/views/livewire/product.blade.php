<div>
    <div class="col-md-8 mb-2">
        @if(session()->has('success'))
            <div class="alert alert-success" role="alert">
                {{ session()->get('success') }}
            </div>
        @endif
        @if(session()->has('error'))
            <div class="alert alert-danger" role="alert">
                {{ session()->get('error') }}
            </div>
        @endif
        @if($updateProduct)
            @include('livewire.product.update')
        {{-- @elseif($addProduct) --}}
        @else
            @include('livewire.product.create')
        @endif
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                @if(!$addProduct)
                    {{-- <button wire:click="addProductData()" class="btn btn-primary btn-sm float-right">Add New Product</button> --}}
                @endif
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Images</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($products) > 0)
                                @foreach ($products as $product)
                                    <tr>
                                        <td>
                                            {{$product->product_name}}
                                        </td>
                                        <td>
                                            {{$product->category->name}}
                                        </td>
                                        <td>
                                            @foreach($product->product_images as $image)
                                                <img src="{{ Storage::url('product_image/' . $image->image) }}" alt="">
                                            @endforeach
                                        </td>
                                        <td>
                                            <button wire:click="editProduct({{$product->id}})" class="btn btn-primary btn-sm">Edit</button>
                                            <button wire:click="deleteProduct({{$product->id}})" wire:confirm="Are you sure you want to delete this post?" class="btn btn-danger btn-sm">Delete</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3" align="center">
                                        No Categories Found.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        document.addEventListener("livewire:load", function() {
            initializeSelect2();
        });
        function initializeSelect2() {
            // Check if the select2 element exists
            let select2Element = $('#select2');
            if (select2Element.length > 0) {
                select2Element.select2();
                
                // Listen to change event and update Livewire component state
                select2Element.on('change', function (e) {
                    var data = select2Element.select2("val");
                $wire.set('selected', data);
                });
            }
        }

        // Initialize Select2 when the page loads
        

    </script>
@endpush
