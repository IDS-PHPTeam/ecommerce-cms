@extends('layouts.app')

@section('title', 'Add Product')

@section('content')
<div class="card">
    <h2 style="margin-bottom: 1.5rem; font-size: 1.875rem; font-weight: 700;">Add New Product</h2>

    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="name" class="form-label">Name <span style="color: #ef4444;">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required class="form-input" placeholder="Product name">
            @error('name')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" rows="4" class="form-input" placeholder="Product description">{{ old('description') }}</textarea>
            @error('description')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="featured_image" class="form-label">Featured Image</label>
            <button type="button" id="chooseFileBtn" class="btn btn-primary" data-media-url="{{ route('media.json') }}">Choose File</button>
            <input type="file" id="featured_image" name="featured_image" accept="image/*" style="display: none;">
            <input type="hidden" id="selected_media_path" name="selected_media_path" value="">
            @error('featured_image')
                <span class="form-error">{{ $message }}</span>
            @enderror
            <div id="imagePreview" style="margin-top: 1rem; display: none;">
                <div style="position: relative; display: inline-block;">
                    <img id="previewImg" src="" alt="Preview" style="max-width: 300px; max-height: 300px; border-radius: 0.5rem; border: 1px solid #e5e7eb; object-fit: cover; display: block;">
                    <button type="button" id="removeImageBtn" style="position: absolute; top: 0.5rem; right: 0.5rem; background-color: #ef4444; color: white; border: none; border-radius: 50%; width: 32px; height: 32px; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.2); z-index: 10;" title="Remove image">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Image Selection Modal with Tabs -->
        <div id="imageSelectionModal" class="modal-overlay" style="display: none;">
            <div class="modal-content" style="max-width: 90vw; max-height: 90vh; padding: 0;">
                <div class="modal-header">
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: #1f2937;">Choose Image</h3>
                    <button type="button" class="modal-close" id="closeImageSelectionModal">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <!-- Tabs -->
                <div style="display: flex; border-bottom: 1px solid #e5e7eb;">
                    <button type="button" class="image-tab-btn active" data-tab="media" style="flex: 1; padding: 1rem; background: none; border: none; border-bottom: 2px solid var(--primary-blue); color: var(--primary-blue); font-weight: 600; cursor: pointer;">Select from Media</button>
                    <button type="button" class="image-tab-btn" data-tab="upload" style="flex: 1; padding: 1rem; background: none; border: none; border-bottom: 2px solid transparent; color: #6b7280; font-weight: 600; cursor: pointer;">Select from PC</button>
                </div>

                <!-- Tab Content -->
                <div class="modal-body" style="max-height: calc(90vh - 180px); overflow-y: auto; padding: 1.5rem;">
                    <!-- Media Tab -->
                    <div id="mediaTab" class="tab-content" style="display: block;">
                        <div id="mediaGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem;">
                            <div style="text-align: center; padding: 2rem; color: #6b7280; grid-column: 1 / -1;">Loading images...</div>
                        </div>
                    </div>

                    <!-- Upload Tab -->
                    <div id="uploadTab" class="tab-content" style="display: none;">
                        <div style="text-align: center; padding: 2rem;">
                            <input type="file" id="modalFileInput" accept="image/*" class="form-input" style="max-width: 400px; margin: 0 auto;">
                            <p style="color: #6b7280; font-size: 0.875rem; margin-top: 1rem;">Select an image file from your computer</p>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn" id="cancelImageSelection" style="background-color: #6b7280; color: white;">Cancel</button>
                </div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div class="form-group">
                <label for="price" class="form-label">Price <span style="color: #ef4444;">*</span></label>
                <input type="number" id="price" name="price" value="{{ old('price') }}" step="0.01" min="0" required class="form-input" placeholder="0.00">
                @error('price')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="sale_price" class="form-label">Sale Price</label>
                <input type="number" id="sale_price" name="sale_price" value="{{ old('sale_price') }}" step="0.01" min="0" class="form-input" placeholder="0.00">
                @error('sale_price')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div class="form-group">
                <label for="category" class="form-label">Category</label>
                <select id="category" name="category" class="form-input">
                    <option value="">Select a category (optional)</option>
                    @foreach($categories as $categoryName)
                        <option value="{{ $categoryName }}" {{ old('category') == $categoryName ? 'selected' : '' }}>{{ $categoryName }}</option>
                    @endforeach
                </select>
                @error('category')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="in_stock" class="form-label">Stock <span style="color: #ef4444;">*</span></label>
                <select id="in_stock" name="in_stock" required class="form-input">
                    <option value="1" {{ old('in_stock', '1') == '1' ? 'selected' : '' }}>In Stock</option>
                    <option value="0" {{ old('in_stock') == '0' ? 'selected' : '' }}>Out of Stock</option>
                </select>
                @error('in_stock')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="status" class="form-label">Status <span style="color: #ef4444;">*</span></label>
            <select id="status" name="status" required class="form-input">
                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('status')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('products.index') }}" class="btn" style="background-color: #6b7280; color: white;">Cancel</a>
        </div>
    </form>
</div>

@push('scripts')
<script src="{{ asset('js/product-form.js') }}"></script>
<script src="{{ asset('js/product-price-validation.js') }}"></script>
<script src="{{ asset('js/media-selector.js') }}"></script>
@endpush
@endsection

