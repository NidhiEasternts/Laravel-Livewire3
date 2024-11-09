<div>
    <div class="col-md-8 mb-2">
        @if (session()->has('success'))
            <div class="alert alert-success" role="alert">
                {{ session()->get('success') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger" role="alert">
                {{ session()->get('error') }}
            </div>
        @endif
        @if ($updateProduct)
            @include('livewire.product.update')
        @elseif($addProduct)
            @include('livewire.product.create')
        @endif
    </div>
    <div class="col-md-12">
        <div class="card">

            <div class="modal fade" id="viewProductModal" tabindex="-1" aria-labelledby="viewProductModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewProductModalLabel">View Product Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @if ($product)
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Product Name:</strong> {{ $product->product_name }}</p>
                                        <p><strong>Category:</strong> {{ $product->category->name }}</p>
                                        <p><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Product Images:</h6>
                                        <div class="row">
                                            @foreach ($product->product_images as $image)
                                                <div class="col-md-4 mb-2">
                                                    <img src="{{ asset('storage/product_image/' . $image->image) }}"
                                                        alt="Product Image" class="img-fluid rounded">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @if (!$addProduct)
                    <button wire:click="addProductData()" class="btn btn-primary btn-sm float-right">Add New
                        Product</button>
                @endif
                <button wire:click="deleteSelectedProducts" class="btn btn-danger btn-sm" style="float: right;">
                    <i class="fas fa-trash-alt"></i>
                </button>
                <div class="dropdown" style="float: right; margin-right: 5px;">
                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="fas fa-download"></i> Export
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="javascript:void(0)" wire:click="export('xlsx')"><i
                                    class="fas fa-file-excel me-2"></i>Excel</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0)" wire:click="export('csv')"><i
                                    class="fas fa-file-csv me-2"></i>CSV</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0)" wire:click="export('pdf')"><i
                                    class="fas fa-file-pdf me-2"></i>PDF</a></li>
                    </ul>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th><input type="checkbox" wire:model.live="selectAll"></th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Images</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($products_data->count() > 0)
                                @foreach ($products_data as $product)
                                    <tr>
                                        <td>
                                            <input type="checkbox" wire:model.live="selectedProducts"
                                                value="{{ $product->id }}">
                                        </td>
                                        <td>
                                            {{ $product->product_name }}
                                        </td>
                                        <td>
                                            {{ $product->category->name }}
                                        </td>
                                        <td>
                                            @foreach ($product->product_images as $image)
                                                <img src="{{ asset('storage/product_image/' . $image->image) }}" alt="Product Image" width="50">
                                            @endforeach
                                        </td>
                                        <td>
                                            ${{ $product->price }}
                                        </td>
                                        <td>
                                            <button wire:click="viewProductData({{ $product->id }})"
                                                class="btn btn-info btn-sm">
                                                View
                                            </button>
                                            <button wire:click="editProduct({{ $product->id }})"
                                                class="btn btn-primary btn-sm">Edit</button>
                                            <button wire:click="deleteProduct({{ $product->id }})"
                                                wire:confirm="Are you sure you want to delete this post?"
                                                class="btn btn-danger btn-sm">Delete</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" align="center">No Products Found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>

                    <div class="pagination-links">
                        {{ $products_data->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            initializeSelect2();

            // Listen for the product-added event
            Livewire.on('product-added', () => {
                // Close modal if you're using one
                if (typeof bootstrap !== 'undefined') {
                    const modals = document.querySelectorAll('.modal.show');
                    modals.forEach(modal => {
                        const modalInstance = bootstrap.Modal.getInstance(modal);
                        if (modalInstance) {
                            modalInstance.hide();
                        }
                    });
                }
                
                // Refresh the component
                @this.$refresh();
            });

            Livewire.on('refreshProducts', () => {
                // Refresh the data
                @this.$refresh();
                
                // Close any open modals
                const modals = document.querySelectorAll('.modal.show');
                modals.forEach(modal => {
                    const modalInstance = bootstrap.Modal.getInstance(modal);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                });
            });
        });

        Livewire.on('post-created', (event) => {
            setTimeout(function() {
                initializeSelect2();
            }, 500)
        });

        Livewire.on('showViewProductModal', () => {
            const viewProductModal = new bootstrap.Modal(document.getElementById('viewProductModal'));
            viewProductModal.show();
        });

        function initializeSelect2() {
            // Check if the select2 element exists
            let select2Element = $('#select2');
            if (select2Element.length > 0) {
                select2Element.select2();

                // Listen to change event and update Livewire component state
                select2Element.on('change', function(e) {
                    var data = select2Element.select2("val");
                    Livewire.dispatch('updateSelected', {
                        data: data
                    });
                });
            }
        }
        // Initialize Select2 when the page loads
    </script>
@endpush
