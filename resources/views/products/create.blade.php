@extends('layouts.app')

@section('title', 'Add Product')

@section('content')
<div class="card">
    <h2 style="margin-bottom: 1.5rem; font-size: 1.875rem; font-weight: 700;">Add New Product</h2>

    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" id="productForm">
        @csrf

        <!-- Tabs Navigation -->
        <div class="tabs-nav" style="display: flex; border-bottom: 2px solid #e5e7eb; margin-bottom: 1.5rem; overflow-x: auto;">
            <button type="button" class="tab-btn active" data-tab="general" style="padding: 0.75rem 1.5rem; background: none; border: none; border-bottom: 2px solid var(--primary-blue); color: var(--primary-blue); font-weight: 600; cursor: pointer; font-size: 0.9375rem; white-space: nowrap;">General Information</button>
            <button type="button" class="tab-btn" data-tab="media" style="padding: 0.75rem 1.5rem; background: none; border: none; border-bottom: 2px solid transparent; color: #6b7280; font-weight: 600; cursor: pointer; font-size: 0.9375rem; white-space: nowrap;">Media</button>
            <button type="button" class="tab-btn" data-tab="simple-fields" id="simpleFieldsTab" style="padding: 0.75rem 1.5rem; background: none; border: none; border-bottom: 2px solid transparent; color: #6b7280; font-weight: 600; cursor: pointer; font-size: 0.9375rem; white-space: nowrap; display: {{ old('product_type', 'simple') == 'simple' ? 'block' : 'none' }};">Stock & Pricing</button>
            <button type="button" class="tab-btn" data-tab="attributes" id="attributesTab" style="padding: 0.75rem 1.5rem; background: none; border: none; border-bottom: 2px solid transparent; color: #6b7280; font-weight: 600; cursor: pointer; font-size: 0.9375rem; white-space: nowrap; display: {{ old('product_type') == 'variable' ? 'block' : 'none' }};">Attributes</button>
            <button type="button" class="tab-btn" data-tab="variations" id="variationsTab" style="padding: 0.75rem 1.5rem; background: none; border: none; border-bottom: 2px solid transparent; color: #6b7280; font-weight: 600; cursor: pointer; font-size: 0.9375rem; white-space: nowrap; display: {{ old('product_type') == 'variable' ? 'block' : 'none' }};">Variations</button>
        </div>

        <!-- Tab 1: General Information -->
        <div id="generalTab" class="tab-content active" style="display: block;">
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

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div class="form-group">
                    <label for="product_type" class="form-label">Product Type <span style="color: #ef4444;">*</span></label>
                    <select id="product_type" name="product_type" required class="form-input">
                        <option value="simple" {{ old('product_type', 'simple') == 'simple' ? 'selected' : '' }}>Simple</option>
                        <option value="variable" {{ old('product_type') == 'variable' ? 'selected' : '' }}>Variable</option>
                    </select>
                    @error('product_type')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
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
            </div>

            <!-- Categories (Multi-select) -->
            <div class="form-group" style="margin-top: 1rem;">
                <label for="categories" class="form-label">Categories</label>
                <div class="custom-multiselect" style="position: relative;">
                    <div class="multiselect-trigger" id="categoriesTrigger" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: white; cursor: pointer; display: flex; justify-content: space-between; align-items: center; min-height: 42px;">
                        <div class="multiselect-selected" style="color: #374151; flex: 1; display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center;">
                            <span class="placeholder-text" style="color: #9ca3af;">Select categories</span>
                            <div class="selected-categories-tags" style="display: none; flex-wrap: wrap; gap: 0.5rem;">
                                <!-- Selected category tags will be added here -->
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <button type="button" class="clear-all-categories-btn" style="display: none; background: none; border: none; color: #ef4444; cursor: pointer; padding: 0.25rem; font-size: 0.875rem;" title="Clear all">Clear All</button>
                            <svg class="multiselect-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20" style="color: #6b7280; transition: transform 0.2s;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    <div class="multiselect-dropdown" id="categoriesDropdown" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #d1d5db; border-radius: 0.375rem; margin-top: 0.25rem; max-height: 200px; overflow-y: auto; z-index: 1000; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);">
                        @foreach($categories as $category)
                            <label class="multiselect-option" style="display: flex; align-items: center; padding: 0.75rem; cursor: pointer; border-bottom: 1px solid #f3f4f6; transition: background-color 0.15s;">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="category-checkbox" data-category-name="{{ $category->name }}" {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }} style="margin-right: 0.75rem; width: 1rem; height: 1rem; cursor: pointer;">
                                <span style="color: #374151; user-select: none;">{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <small style="color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem; display: block;">Select one or more categories</small>
                @error('categories')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Tab 2: Media -->
        <div id="mediaTab" class="tab-content" style="display: none;">
            <!-- Featured Image -->
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

            <!-- Gallery -->
            <div class="form-group">
                <label class="form-label">Gallery (Images + Videos)</label>
                <button type="button" id="addGalleryItemBtn" class="btn btn-primary">Add to Gallery</button>
                <div id="galleryContainer" style="margin-top: 1rem; display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem;">
                    <!-- Gallery items will be added here dynamically -->
                </div>
                @error('gallery')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Tab 3: Stock & Pricing (Simple Products Only) -->
        <div id="simpleFieldsTab" class="tab-content" style="display: {{ old('product_type', 'simple') == 'simple' ? 'block' : 'none' }};">
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem; color: #1f2937;">Pricing</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div class="form-group">
                    <label for="price" class="form-label">Price <span style="color: #ef4444;">*</span></label>
                    <input type="number" id="price" name="price" value="{{ old('price') }}" step="0.01" min="0" class="form-input" placeholder="0.00">
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

            <h3 style="font-size: 1.25rem; font-weight: 600; margin: 1.5rem 0 1rem 0; color: #1f2937;">Stock Management</h3>
            <div class="form-group">
                <label for="track_stock" class="form-label">Track Stock</label>
                <select id="track_stock" name="track_stock" class="form-input">
                    <option value="1" {{ old('track_stock', '1') == '1' ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ old('track_stock') == '0' ? 'selected' : '' }}>No</option>
                </select>
            </div>

            <div id="stockQuantityField" style="display: {{ old('track_stock', '1') == '1' ? 'block' : 'none' }};">
                <div class="form-group">
                    <label for="stock_quantity" class="form-label">Stock Quantity</label>
                    <input type="number" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity') }}" min="0" class="form-input" placeholder="0">
                    @error('stock_quantity')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div id="stockStatusField" style="display: {{ old('track_stock', '1') == '0' ? 'block' : 'none' }};">
                <div class="form-group">
                    <label for="stock_status" class="form-label">Stock Status</label>
                    <select id="stock_status" name="stock_status" class="form-input">
                        <option value="in_stock" {{ old('stock_status', 'in_stock') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                        <option value="out_of_stock" {{ old('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                        <option value="on_backorder" {{ old('stock_status') == 'on_backorder' ? 'selected' : '' }}>On Backorder</option>
                    </select>
                    @error('stock_status')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Tab 4: Attributes (Variable Products Only) -->
        <div id="attributesTab" class="tab-content" style="display: {{ old('product_type') == 'variable' ? 'block' : 'none' }};">
            <div class="form-group">
                <label class="form-label">Select Attributes for Variants</label>
                <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 1rem;">Select which attributes will be available when creating variants (e.g., Size, Color, Type)</p>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem;">
                    @foreach($attributes as $attribute)
                        <label style="display: flex; align-items: center; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; cursor: pointer; transition: all 0.2s;">
                            <input type="checkbox" class="variant-attribute-checkbox" value="{{ $attribute->id }}" data-attribute-name="{{ $attribute->name }}" data-attribute-values="{{ json_encode($attribute->values->pluck('value', 'id')->toArray()) }}" style="margin-right: 0.5rem; width: 1rem; height: 1rem; cursor: pointer;">
                            <div style="flex: 1;">
                                <div style="font-weight: 600; color: #374151;">{{ $attribute->name }}</div>
                                <div style="font-size: 0.875rem; color: #6b7280;">{{ $attribute->values->count() }} values</div>
                            </div>
                        </label>
                    @endforeach
                </div>
                @if($attributes->isEmpty())
                    <p style="color: #6b7280; font-size: 0.875rem; margin-top: 0.5rem;">No attributes available. Please create attributes first.</p>
                @endif
            </div>
        </div>

        <!-- Tab 5: Variations (Variable Products Only) -->
        <div id="variationsTab" class="tab-content" style="display: {{ old('product_type') == 'variable' ? 'block' : 'none' }};">
            <div id="variantsContainer">
                <div id="variantsList">
                    <!-- Variants will be added here -->
                </div>
                <button type="button" id="addVariantBtn" class="btn btn-primary" style="margin-top: 1rem;">+ Add Variant</button>
            </div>
            
            @error('variants')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>
        
        <!-- Store attributes data for JavaScript -->
        <script type="application/json" id="attributesData">
            @json($attributes->mapWithKeys(function($attr) {
                return [$attr->id => [
                    'name' => $attr->name,
                    'values' => $attr->values->mapWithKeys(function($val) {
                        return [$val->id => $val->value];
                    })
                ]];
            }))
        </script>

        <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('products.index') }}" class="btn" style="background-color: #6b7280; color: white;">Cancel</a>
        </div>
    </form>
</div>

<!-- Image Selection Modal -->
<div id="imageSelectionModal" class="modal-overlay" style="display: none;">
    <div class="modal-content" style="max-width: 90vw; max-height: 90vh; padding: 0;">
        <div class="modal-header">
            <h3 style="font-size: 1.25rem; font-weight: 700; color: #1f2937;">Choose Media</h3>
            <button type="button" class="modal-close" id="closeImageSelectionModal">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <div style="display: flex; border-bottom: 1px solid #e5e7eb;">
            <button type="button" class="image-tab-btn active" data-tab="media" style="flex: 1; padding: 1rem; background: none; border: none; border-bottom: 2px solid var(--primary-blue); color: var(--primary-blue); font-weight: 600; cursor: pointer;">Select from Media</button>
            <button type="button" class="image-tab-btn" data-tab="upload" style="flex: 1; padding: 1rem; background: none; border: none; border-bottom: 2px solid transparent; color: #6b7280; font-weight: 600; cursor: pointer;">Select from PC</button>
        </div>

        <div class="modal-body" style="max-height: calc(90vh - 180px); overflow-y: auto; padding: 1.5rem;">
            <div id="modalMediaTab" class="tab-content" style="display: block;">
                <div id="mediaGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem;">
                    <div style="text-align: center; padding: 2rem; color: #6b7280; grid-column: 1 / -1;">Loading images...</div>
                </div>
            </div>

            <div id="modalUploadTab" class="tab-content" style="display: none;">
                <div style="text-align: center; padding: 2rem;">
                    <input type="file" id="modalFileInput" accept="image/*,video/*" class="form-input" style="max-width: 400px; margin: 0 auto;">
                    <p style="color: #6b7280; font-size: 0.875rem; margin-top: 1rem;">Select an image or video file from your computer</p>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn" id="cancelImageSelection" style="background-color: #6b7280; color: white;">Cancel</button>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/product-form.js') }}"></script>
<script src="{{ asset('js/product-price-validation.js') }}"></script>
<script src="{{ asset('js/media-selector.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching functionality
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    // Map data-tab values to actual tab IDs
    const tabIdMap = {
        'general': 'generalTab',
        'media': 'mediaTab',
        'simple-fields': 'simpleFieldsTab',
        'attributes': 'attributesTab',
        'variations': 'variationsTab'
    };
    
    tabButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            const targetTabId = tabIdMap[targetTab] || (targetTab + 'Tab');
            
            // Update buttons
            tabButtons.forEach(b => {
                b.classList.remove('active');
                b.style.borderBottomColor = 'transparent';
                b.style.color = '#6b7280';
            });
            this.classList.add('active');
            this.style.borderBottomColor = 'var(--primary-blue)';
            this.style.color = 'var(--primary-blue)';
            
            // Update content
            tabContents.forEach(content => {
                content.classList.remove('active');
                content.style.display = 'none';
            });
            const targetContent = document.getElementById(targetTabId);
            if (targetContent) {
                targetContent.classList.add('active');
                targetContent.style.display = 'block';
            }
        });
    });

    // Product type toggle - show/hide tabs
    const productTypeSelect = document.getElementById('product_type');
    const simpleFieldsTabBtn = document.getElementById('simpleFieldsTab');
    const attributesTabBtn = document.getElementById('attributesTab');
    const variationsTabBtn = document.getElementById('variationsTab');
    const simpleFieldsTab = document.getElementById('simpleFieldsTab');
    const attributesTab = document.getElementById('attributesTab');
    const variationsTab = document.getElementById('variationsTab');
    
    if (productTypeSelect) {
        productTypeSelect.addEventListener('change', function() {
            if (this.value === 'simple') {
                // Show simple fields tab, hide variable tabs
                simpleFieldsTabBtn.style.display = 'block';
                attributesTabBtn.style.display = 'none';
                variationsTabBtn.style.display = 'none';
                
                // If currently on variable tabs, switch to simple
                if (attributesTab.classList.contains('active') || variationsTab.classList.contains('active')) {
                    simpleFieldsTabBtn.click();
                }
            } else if (this.value === 'variable') {
                // Hide simple fields tab, show variable tabs
                simpleFieldsTabBtn.style.display = 'none';
                attributesTabBtn.style.display = 'block';
                variationsTabBtn.style.display = 'block';
                
                // If currently on simple tab, switch to attributes
                if (simpleFieldsTab.classList.contains('active')) {
                    attributesTabBtn.click();
                }
            }
        });
    }
    
    // Categories multi-select with tags
    const categoriesTrigger = document.getElementById('categoriesTrigger');
    const categoriesDropdown = document.getElementById('categoriesDropdown');
    const categoryCheckboxes = document.querySelectorAll('.category-checkbox');
    const placeholderText = categoriesTrigger.querySelector('.placeholder-text');
    const selectedTagsContainer = categoriesTrigger.querySelector('.selected-categories-tags');
    const clearAllBtn = categoriesTrigger.querySelector('.clear-all-categories-btn');
    const multiselectArrow = categoriesTrigger.querySelector('.multiselect-arrow');

    // Toggle dropdown
    categoriesTrigger.addEventListener('click', function(e) {
        // Don't toggle if clicking on tags or clear button
        if (e.target.closest('.category-tag') || e.target.closest('.clear-all-categories-btn')) {
            return;
        }
        e.stopPropagation();
        const isOpen = categoriesDropdown.style.display === 'block';
        categoriesDropdown.style.display = isOpen ? 'none' : 'block';
        multiselectArrow.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!categoriesTrigger.contains(e.target) && !categoriesDropdown.contains(e.target)) {
            categoriesDropdown.style.display = 'none';
            multiselectArrow.style.transform = 'rotate(0deg)';
        }
    });

    // Update selected categories display
    function updateSelectedCategories() {
        const checked = Array.from(categoryCheckboxes).filter(cb => cb.checked);
        
        // Clear existing tags
        selectedTagsContainer.innerHTML = '';
        
        if (checked.length > 0) {
            placeholderText.style.display = 'none';
            selectedTagsContainer.style.display = 'flex';
            clearAllBtn.style.display = 'block';
            
            // Create tags for each selected category
            checked.forEach(checkbox => {
                const categoryId = checkbox.value;
                const categoryName = checkbox.getAttribute('data-category-name');
                
                const tag = document.createElement('div');
                tag.className = 'category-tag';
                tag.style.cssText = 'display: inline-flex; align-items: center; gap: 0.5rem; background-color: var(--primary-blue); color: white; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 500;';
                tag.innerHTML = `
                    <span>${categoryName}</span>
                    <button type="button" class="remove-category-tag" data-category-id="${categoryId}" style="background: none; border: none; color: white; cursor: pointer; padding: 0; display: flex; align-items: center; font-size: 1rem; line-height: 1;" title="Remove">×</button>
                `;
                
                // Handle remove tag button
                tag.querySelector('.remove-category-tag').addEventListener('click', function(e) {
                    e.stopPropagation();
                    const catId = this.getAttribute('data-category-id');
                    const checkbox = document.querySelector(`.category-checkbox[value="${catId}"]`);
                    if (checkbox) {
                        checkbox.checked = false;
                        updateSelectedCategories();
                    }
                });
                
                selectedTagsContainer.appendChild(tag);
            });
        } else {
            placeholderText.style.display = 'inline';
            selectedTagsContainer.style.display = 'none';
            clearAllBtn.style.display = 'none';
        }
    }

    // Clear all categories
    clearAllBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        categoryCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        updateSelectedCategories();
    });

    // Handle checkbox changes
    categoryCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCategories);
    });

    // Prevent dropdown from closing when clicking inside
    categoriesDropdown.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    // Initialize selected categories display
    updateSelectedCategories();

    // Add hover effect
    document.querySelectorAll('.multiselect-option').forEach(option => {
        option.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f3f4f6';
        });
        option.addEventListener('mouseleave', function() {
            this.style.backgroundColor = 'white';
        });
    });

    // Track stock toggle for simple products
    const trackStockSelect = document.getElementById('track_stock');
    const stockQuantityField = document.getElementById('stockQuantityField');
    const stockStatusField = document.getElementById('stockStatusField');
    
    if (trackStockSelect) {
        trackStockSelect.addEventListener('change', function() {
            if (this.value === '1') {
                stockQuantityField.style.display = 'block';
                stockStatusField.style.display = 'none';
            } else {
                stockQuantityField.style.display = 'none';
                stockStatusField.style.display = 'block';
            }
        });
    }

    // Gallery functionality
    const addGalleryItemBtn = document.getElementById('addGalleryItemBtn');
    const galleryContainer = document.getElementById('galleryContainer');
    const imageSelectionModal = document.getElementById('imageSelectionModal');
    const closeImageSelectionModal = document.getElementById('closeImageSelectionModal');
    const cancelImageSelection = document.getElementById('cancelImageSelection');
    const mediaGrid = document.getElementById('mediaGrid');
    const modalFileInput = document.getElementById('modalFileInput');
    let galleryIndex = 0;
    let currentSelectionMode = 'gallery'; // 'featured' or 'gallery'

    // Open gallery selection modal
    if (addGalleryItemBtn) {
        addGalleryItemBtn.addEventListener('click', function() {
            currentSelectionMode = 'gallery';
            imageSelectionModal.style.display = 'flex';
            loadMediaGrid();
        });
    }

    // Close modal
    if (closeImageSelectionModal) {
        closeImageSelectionModal.addEventListener('click', function() {
            imageSelectionModal.style.display = 'none';
        });
    }

    if (cancelImageSelection) {
        cancelImageSelection.addEventListener('click', function() {
            imageSelectionModal.style.display = 'none';
        });
    }

    // Close modal when clicking outside
    if (imageSelectionModal) {
        imageSelectionModal.addEventListener('click', function(e) {
            if (e.target === imageSelectionModal) {
                imageSelectionModal.style.display = 'none';
            }
        });
    }

    // Load media grid
    function loadMediaGrid() {
        if (!mediaGrid) return;
        
        mediaGrid.innerHTML = '<div style="text-align: center; padding: 2rem; color: #6b7280; grid-column: 1 / -1;">Loading images...</div>';
        
        fetch('{{ route("media.json") }}')
            .then(response => response.json())
            .then(data => {
                mediaGrid.innerHTML = '';
                if (data.length === 0) {
                    mediaGrid.innerHTML = '<div style="text-align: center; padding: 2rem; color: #6b7280; grid-column: 1 / -1;">No media files found</div>';
                    return;
                }
                
                data.forEach(media => {
                    const mediaItem = document.createElement('div');
                    mediaItem.style.cssText = 'position: relative; padding-top: 100%; background: #f3f4f6; border-radius: 0.5rem; overflow: hidden; cursor: pointer; border: 2px solid transparent; transition: all 0.2s;';
                    mediaItem.className = 'media-select-item';
                    mediaItem.dataset.mediaPath = media.path;
                    mediaItem.dataset.mediaType = media.type;
                    mediaItem.dataset.mediaUrl = media.url;
                    
                    if (media.type === 'video') {
                        mediaItem.innerHTML = `
                            <video src="${media.url}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;"></video>
                        `;
                    } else {
                        mediaItem.innerHTML = `
                            <img src="${media.url}" alt="${media.name}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;">
                        `;
                    }
                    
                    mediaItem.addEventListener('click', function() {
                        if (currentSelectionMode === 'gallery') {
                            addToGallery(media.path, media.type, media.url);
                        }
                        imageSelectionModal.style.display = 'none';
                    });
                    
                    mediaItem.addEventListener('mouseenter', function() {
                        this.style.borderColor = 'var(--primary-blue)';
                        this.style.transform = 'scale(1.05)';
                    });
                    
                    mediaItem.addEventListener('mouseleave', function() {
                        this.style.borderColor = 'transparent';
                        this.style.transform = 'scale(1)';
                    });
                    
                    mediaGrid.appendChild(mediaItem);
                });
            })
            .catch(error => {
                console.error('Error loading media:', error);
                mediaGrid.innerHTML = '<div style="text-align: center; padding: 2rem; color: #ef4444; grid-column: 1 / -1;">Error loading media files</div>';
            });
    }

    // Add item to gallery
    function addToGallery(mediaPath, mediaType, mediaUrl) {
        const galleryItem = document.createElement('div');
        galleryItem.className = 'gallery-item';
        galleryItem.style.cssText = 'position: relative; border: 1px solid #e5e7eb; border-radius: 0.5rem; overflow: hidden; background: white;';
        
        galleryItem.innerHTML = `
            <input type="hidden" name="gallery[${galleryIndex}][media_path]" value="${mediaPath}">
            <input type="hidden" name="gallery[${galleryIndex}][media_type]" value="${mediaType}">
            ${mediaType === 'video' ? `
                <div style="position: relative; padding-top: 100%; background: #000;">
                    <video src="${mediaUrl}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain;"></video>
                    <button type="button" class="remove-gallery-item-btn" style="position: absolute; top: 0.5rem; right: 0.5rem; background-color: #ef4444; color: white; border: none; border-radius: 50%; width: 32px; height: 32px; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.2); z-index: 10;">×</button>
                </div>
            ` : `
                <div style="position: relative; padding-top: 100%; background: #f3f4f6;">
                    <img src="${mediaUrl}" alt="Gallery item" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;">
                    <button type="button" class="remove-gallery-item-btn" style="position: absolute; top: 0.5rem; right: 0.5rem; background-color: #ef4444; color: white; border: none; border-radius: 50%; width: 32px; height: 32px; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.2); z-index: 10;">×</button>
                </div>
            `}
        `;
        
        galleryContainer.appendChild(galleryItem);
        
        // Handle remove button
        galleryItem.querySelector('.remove-gallery-item-btn').addEventListener('click', function() {
            galleryItem.remove();
        });
        
        galleryIndex++;
    }

    // Handle file upload in modal
    if (modalFileInput) {
        modalFileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const formData = new FormData();
                formData.append('file', file);
                
                fetch('{{ route("media.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.path) {
                        const mediaType = file.type.startsWith('video/') ? 'video' : 'image';
                        const mediaUrl = data.url || '{{ asset("storage") }}/' + data.path;
                        
                        if (currentSelectionMode === 'gallery') {
                            addToGallery(data.path, mediaType, mediaUrl);
                        }
                        
                        imageSelectionModal.style.display = 'none';
                        modalFileInput.value = '';
                        loadMediaGrid(); // Reload to show new file
                    }
                })
                .catch(error => {
                    console.error('Error uploading file:', error);
                    alert('Error uploading file. Please try again.');
                });
            }
        });
    }

    // Handle modal tab switching
    const imageTabButtons = document.querySelectorAll('.image-tab-btn');
    const modalTabContents = document.querySelectorAll('#imageSelectionModal .tab-content');
    
    imageTabButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            imageTabButtons.forEach(b => {
                b.classList.remove('active');
                b.style.borderBottomColor = 'transparent';
                b.style.color = '#6b7280';
            });
            this.classList.add('active');
            this.style.borderBottomColor = 'var(--primary-blue)';
            this.style.color = 'var(--primary-blue)';
            
            modalTabContents.forEach(content => {
                content.style.display = 'none';
            });
            const targetContent = document.getElementById('modal' + targetTab.charAt(0).toUpperCase() + targetTab.slice(1) + 'Tab');
            if (targetContent) {
                targetContent.style.display = 'block';
            }
        });
    });
});

// Variant Repeater Logic
document.addEventListener('DOMContentLoaded', function() {
    const addVariantBtn = document.getElementById('addVariantBtn');
    const variantsList = document.getElementById('variantsList');
    let variantIndex = 0;
    
    // Load attributes data
    const attributesDataElement = document.getElementById('attributesData');
    if (!attributesDataElement) return;
    
    const attributesData = JSON.parse(attributesDataElement.textContent);

    // Add variant button
    if (addVariantBtn) {
        addVariantBtn.addEventListener('click', function() {
            createVariantForm();
        });
    }

    // Create variant form with attribute repeater
    function createVariantForm() {
        const variantDiv = document.createElement('div');
        variantDiv.className = 'variant-form';
        variantDiv.style.cssText = 'border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1.5rem; margin-bottom: 1rem; background-color: #f9fafb;';
        variantDiv.dataset.variantIndex = variantIndex;
        
        variantDiv.innerHTML = `
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding-bottom: 0.75rem; border-bottom: 2px solid var(--primary-blue);">
                <h5 style="font-size: 1rem; font-weight: 600; color: #1f2937; margin: 0;">Variant ${variantIndex + 1}</h5>
                <button type="button" class="remove-variant-btn" style="background-color: #ef4444; color: white; border: none; padding: 0.25rem 0.75rem; border-radius: 0.25rem; cursor: pointer; font-size: 0.875rem;">Remove</button>
            </div>
            
            <!-- Attributes Repeater -->
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label class="form-label">Attributes</label>
                <div class="attributes-repeater" style="margin-top: 0.5rem;">
                    <!-- Attribute rows will be added here -->
                </div>
                <button type="button" class="add-attribute-row-btn btn" style="margin-top: 0.5rem; background-color: #6b7280; color: white; font-size: 0.875rem;">+ Add Attribute</button>
            </div>
            
            <div class="form-group" style="margin-bottom: 1rem;">
                <label class="form-label">Description</label>
                <textarea name="variants[${variantIndex}][description]" class="form-input" rows="2" placeholder="Variant description (optional)"></textarea>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                <div class="form-group">
                    <label class="form-label">Price <span style="color: #ef4444;">*</span></label>
                    <input type="number" name="variants[${variantIndex}][price]" step="0.01" min="0" required class="form-input" placeholder="0.00">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Sale Price</label>
                    <input type="number" name="variants[${variantIndex}][sale_price]" step="0.01" min="0" class="form-input" placeholder="0.00">
                </div>
            </div>
            
            <div class="form-group" style="margin-bottom: 1rem;">
                <label class="form-label">Track Stock</label>
                <select name="variants[${variantIndex}][track_stock]" class="form-input track-stock-select">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            
            <div class="stock-quantity-field" style="display: block; margin-bottom: 1rem;">
                <div class="form-group">
                    <label class="form-label">Stock Quantity</label>
                    <input type="number" name="variants[${variantIndex}][stock_quantity]" min="0" class="form-input" placeholder="0">
                </div>
            </div>
            
            <div class="stock-status-field" style="display: none; margin-bottom: 1rem;">
                <div class="form-group">
                    <label class="form-label">Stock Status</label>
                    <select name="variants[${variantIndex}][stock_status]" class="form-input">
                        <option value="in_stock">In Stock</option>
                        <option value="out_of_stock">Out of Stock</option>
                        <option value="on_backorder">On Backorder</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Variant Image (Optional)</label>
                <input type="text" name="variants[${variantIndex}][image]" class="form-input" placeholder="Image path or URL">
            </div>
        `;
        
        variantsList.appendChild(variantDiv);
        
        // Initialize attribute repeater for this variant
        const attributesRepeater = variantDiv.querySelector('.attributes-repeater');
        const addAttributeBtn = variantDiv.querySelector('.add-attribute-row-btn');
        
        // Add first attribute row
        addAttributeRow(attributesRepeater, variantIndex);
        
        // Handle add attribute button
        addAttributeBtn.addEventListener('click', function() {
            addAttributeRow(attributesRepeater, variantIndex);
        });
        
        // Handle track stock toggle
        const trackStockSelect = variantDiv.querySelector('.track-stock-select');
        const stockQuantityField = variantDiv.querySelector('.stock-quantity-field');
        const stockStatusField = variantDiv.querySelector('.stock-status-field');
        
        trackStockSelect.addEventListener('change', function() {
            if (this.value === '1') {
                stockQuantityField.style.display = 'block';
                stockStatusField.style.display = 'none';
            } else {
                stockQuantityField.style.display = 'none';
                stockStatusField.style.display = 'block';
            }
        });
        
        // Handle remove variant button
        variantDiv.querySelector('.remove-variant-btn').addEventListener('click', function() {
            variantDiv.remove();
        });
        
        variantIndex++;
    }

    // Add attribute row (attribute dropdown + value dropdown)
    function addAttributeRow(container, variantIdx) {
        const attrRowIndex = container.children.length;
        const attrRow = document.createElement('div');
        attrRow.className = 'attribute-row';
        attrRow.style.cssText = 'display: flex; gap: 0.5rem; align-items: end; margin-bottom: 0.5rem;';
        
        attrRow.innerHTML = `
            <div class="form-group" style="flex: 1;">
                <label class="form-label">Attribute</label>
                <select class="form-input attribute-select" name="variants[${variantIdx}][attributes][${attrRowIndex}][attribute_id]" style="width: 100%;">
                    <option value="">Select Attribute</option>
                    ${Object.entries(attributesData).map(([id, attr]) => 
                        `<option value="${id}" data-attribute-name="${attr.name}">${attr.name}</option>`
                    ).join('')}
                </select>
            </div>
            <div class="form-group" style="flex: 1;">
                <label class="form-label">Value</label>
                <select class="form-input attribute-value-select" name="variants[${variantIdx}][attributes][${attrRowIndex}][value_id]" style="width: 100%;" disabled>
                    <option value="">Select Attribute First</option>
                </select>
            </div>
            <button type="button" class="remove-attribute-row-btn" style="background-color: #ef4444; color: white; border: none; padding: 0.5rem 1rem; border-radius: 0.25rem; cursor: pointer; height: fit-content; margin-bottom: 0.5rem;">Remove</button>
        `;
        
        container.appendChild(attrRow);
        
        const attributeSelect = attrRow.querySelector('.attribute-select');
        const valueSelect = attrRow.querySelector('.attribute-value-select');
        
        // Handle attribute selection - populate values
        attributeSelect.addEventListener('change', function() {
            const selectedAttrId = this.value;
            const selectedOption = this.options[this.selectedIndex];
            const attributeName = selectedOption.getAttribute('data-attribute-name');
            
            // Clear and populate value dropdown
            valueSelect.innerHTML = '<option value="">Select Value</option>';
            valueSelect.disabled = !selectedAttrId;
            
            if (selectedAttrId && attributesData[selectedAttrId]) {
                const values = attributesData[selectedAttrId].values;
                Object.entries(values).forEach(([valueId, valueName]) => {
                    const option = document.createElement('option');
                    option.value = valueId;
                    option.textContent = valueName;
                    valueSelect.appendChild(option);
                });
                
                // Store attribute name in hidden field
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = `variants[${variantIdx}][attributes][${attrRowIndex}][attribute_name]`;
                hiddenInput.value = attributeName;
                attrRow.appendChild(hiddenInput);
            }
        });
        
        // Handle remove attribute row
        attrRow.querySelector('.remove-attribute-row-btn').addEventListener('click', function() {
            attrRow.remove();
        });
    }
});
</script>
<style>
.multiselect-trigger:hover {
    border-color: var(--primary-blue);
}
.multiselect-trigger:focus-within {
    border-color: var(--primary-blue);
    outline: none;
    box-shadow: 0 0 0 3px rgba(9, 158, 203, 0.1);
}
.multiselect-option:hover {
    background-color: #f3f4f6;
}
.multiselect-option input[type="checkbox"]:checked + span {
    color: var(--primary-blue);
    font-weight: 500;
}
.tab-content {
    min-height: 300px;
}
</style>
@endpush
@endsection
