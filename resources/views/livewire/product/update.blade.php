<form>
    <div class="form-group mb-3">
        <label for="productName">Product Name:</label>
        <input type="text" class="form-control @error('product_name') is-invalid @enderror" id="productName" placeholder="Enter Product Name" wire:model="product_name">
        @error('product_name') <span class="text-danger">{{ $message }}</span>@enderror
    </div>
    <div class="form-group mb-3">
        <label for="category">Category:</label>
        <select class="form-control" id="select2" name="category_id" wire:model="selected">
        <option value="">Select Category</option>
        @foreach($category as $value)
            <option value="{{$value->id}}" {{$selected == $value->id ? 'selected' : ''}}>{{$value->name}}</option>
        @endforeach
        </select>
        @error('category_id') <span class="text-danger">{{ $message }}</span>@enderror
    </div>
   
    <div class="form-group mb-3">
        <label for="price">Product Price:</label>
        <input type="text" class="form-control @error('price') is-invalid @enderror" id="price" placeholder="Enter Price" wire:model="price">
        @error('price') <span class="text-danger">{{ $message }}</span>@enderror
    </div>
    <div class="form-group mb-3">
        <label for="images">Product Images:</label>
        <input type="file" class="form-control @error('images') is-invalid @enderror" id="images" wire:model="images" multiple>
        @error('images.*') <span class="text-danger">{{ $message }}</span>@enderror
        @if(count($product_images) > 0)
        @foreach($product_images as $image)
            <img src="{{ Storage::url('product_image/' . $image->image) }}" alt="">
        @endforeach
        @endif
    </div>

    <div class="d-grid gap-2">
        <button wire:click.prevent="updateProductData()" class="btn btn-success btn-block">Save</button>
        <button wire:click.prevent="cancel()" class="btn btn-danger">Cancel</button>
    </div>
</form>