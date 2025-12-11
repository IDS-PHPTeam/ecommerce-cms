@extends('layouts.app')

@section('title', __('cms.add_product'))

@section('content')
<div class="card pb-90 mb-0">
    <h2 class="section-heading-lg mb-6">{{ __('cms.add_new_product') }}</h2>

    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" id="productForm">
        @csrf

        <!-- Tabs Navigation -->
        <div class="tabs-nav">
            <button type="button" class="tab-btn active" data-tab="general">{{ __('cms.general_information') }}</button>
            <button type="button" class="tab-btn" data-tab="media">{{ __('cms.media') }}</button>
            <button type="button" class="tab-btn {{ old('product_type', 'simple') == 'simple' ? 'd-block' : 'd-none' }}" data-tab="simple-fields" id="simpleFieldsTabBtn">{{ __('cms.stock_pricing') }}</button>
            <button type="button" class="tab-btn {{ old('product_type') == 'variable' ? 'd-block' : 'd-none' }}" data-tab="attributes" id="attributesTab">{{ __('cms.attributes') }}</button>
            <button type="button" class="tab-btn {{ old('product_type') == 'variable' ? 'd-block' : 'd-none' }}" data-tab="variations" id="variationsTab">{{ __('cms.variations') }}</button>
        </div>

        <!-- Tab 1: General Information -->
        <div id="generalTab" class="tab-content active">
            <div class="form-group">
                <label class="form-label">{{ __('cms.name') }} <span class="required-asterisk">*</span></label>
                <div class="grid grid-2 gap-4">
                    <div>
                        <input type="text" id="name_en" name="name_en" value="{{ old('name_en') }}" class="form-input" placeholder="{{ __('cms.product_name_english') }}">
                        @error('name_en')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <input type="text" id="name_ar" name="name_ar" value="{{ old('name_ar') }}" class="form-input" placeholder="{{ __('cms.product_name_arabic') }}" dir="rtl">
                        @error('name_ar')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <input type="hidden" id="name" name="name" value="{{ old('name') }}">
                @error('name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('cms.description') }}</label>
                <div class="grid grid-2 gap-4">
                    <div>
                        <textarea id="description_en" name="description_en" rows="4" class="form-input" placeholder="{{ __('cms.product_description_english') }}">{{ old('description_en') }}</textarea>
                        @error('description_en')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <textarea id="description_ar" name="description_ar" rows="4" class="form-input" placeholder="{{ __('cms.product_description_arabic') }}" dir="rtl">{{ old('description_ar') }}</textarea>
                        @error('description_ar')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <input type="hidden" id="description" name="description" value="{{ old('description') }}">
                @error('description')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="grid grid-auto-250 gap-4">
                <div class="form-group">
                    <label for="product_type" class="form-label">{{ __('cms.product_type') }} <span class="required-asterisk">*</span></label>
                    <select id="product_type" name="product_type" required class="form-input">
                        <option value="simple" {{ old('product_type', 'simple') == 'simple' ? 'selected' : '' }}>{{ __('cms.simple') }}</option>
                        <option value="variable" {{ old('product_type') == 'variable' ? 'selected' : '' }}>{{ __('cms.variable') }}</option>
                    </select>
                    @error('product_type')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status" class="form-label">{{ __('cms.status') }} <span class="required-asterisk">*</span></label>
                    <select id="status" name="status" required class="form-input">
                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>{{ __('cms.active') }}</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>{{ __('cms.inactive') }}</option>
                    </select>
                    @error('status')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Categories (Multi-select) -->
            <div class="form-group mt-4">
                <label for="categories" class="form-label">{{ __('cms.categories') }}</label>
                <div class="custom-multiselect relative">
                    <div class="multiselect-trigger multiselect-trigger-full" id="categoriesTrigger">
                        <div class="multiselect-selected">
                            <span class="placeholder-text text-quaternary">{{ __('cms.select_categories') }}</span>
                            <div class="selected-categories-tags d-none flex-wrap gap-2">
                                <!-- Selected category tags will be added here -->
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" class="clear-all-categories-btn d-none bg-none border-0 text-red cursor-pointer p-1 text-sm" title="{{ __('cms.clear_all') }}">{{ __('cms.clear_all') }}</button>
                            <svg class="multiselect-arrow text-tertiary transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    <div class="multiselect-dropdown multiselect-dropdown-full" id="categoriesDropdown">
                        @foreach($categories as $category)
                            <label class="multiselect-option multiselect-option-item">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="category-checkbox checkbox-custom" data-category-name="{{ $category->name }}" {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                                <span class="text-secondary user-select-none">{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <small class="text-tertiary text-sm mt-1 d-block">{{ __('cms.select_one_or_more_categories') }}</small>
                @error('categories')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Tab 2: Media -->
        <div id="mediaTab" class="tab-content d-none">
            <!-- Featured Image -->
            <div class="form-group">
                <label for="featured_image" class="form-label">{{ __('cms.featured_image') }}</label>
                <button type="button" id="chooseFileBtn" class="btn btn-primary" data-media-url="{{ route('media.json') }}">{{ __('cms.choose_file') }}</button>
                <input type="file" id="featured_image" name="featured_image" accept="image/*" class="d-none">
                <input type="hidden" id="selected_media_path" name="selected_media_path" value="">
                @error('featured_image')
                    <span class="form-error">{{ $message }}</span>
                @enderror
                <div id="imagePreview" class="mt-4 d-none">
                    <div class="relative d-inline-block">
                        <img id="previewImg" src="" alt="Preview" class="preview-image-large">
                        <button type="button" id="removeImageBtn" class="remove-image-btn absolute top-2 right-2 bg-red-500 text-white border-none rounded-full w-8 h-8 cursor-pointer flex items-center justify-center shadow-md z-10" title="Remove image">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Gallery -->
            <div class="form-group">
                <label class="form-label">{{ __('cms.gallery') }} ({{ __('cms.images') }} + {{ __('cms.videos') }})</label>
                <button type="button" id="addGalleryItemBtn" class="btn btn-primary">{{ __('cms.add_to_gallery') }}</button>
                <div id="galleryContainer" class="mt-4 grid grid-auto-150">
                    <!-- Gallery items will be added here dynamically -->
                </div>
                @error('gallery')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Tab 3: Stock & Pricing (Simple Products Only) -->
        <div id="simpleFieldsTab" class="tab-content d-none">
            <h3 class="text-xl font-semibold mb-4 text-secondary">{{ __('cms.pricing') }}</h3>
            <div class="grid grid-auto-200 gap-4">
                <div class="form-group">
                    <label for="price" class="form-label">{{ __('cms.price') }} <span class="text-red-500">*</span></label>
                    <input type="number" id="price" name="price" value="{{ old('price') }}" step="0.01" min="0" class="form-input" placeholder="0.00">
                    @error('price')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="sale_price" class="form-label">{{ __('cms.sale_price') }}</label>
                    <input type="number" id="sale_price" name="sale_price" value="{{ old('sale_price') }}" step="0.01" min="0" class="form-input" placeholder="0.00">
                    @error('sale_price')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <h3 class="text-xl font-semibold my-6 text-secondary">{{ __('cms.stock_management') }}</h3>
            <div class="form-group">
                <label for="track_stock" class="form-label">{{ __('cms.track_stock') }}</label>
                <select id="track_stock" name="track_stock" class="form-input">
                    <option value="1" {{ old('track_stock', '1') == '1' ? 'selected' : '' }}>{{ __('cms.yes') }}</option>
                    <option value="0" {{ old('track_stock') == '0' ? 'selected' : '' }}>{{ __('cms.no') }}</option>
                </select>
            </div>

            <div id="stockQuantityField" class="{{ old('track_stock', '1') == '1' ? '' : 'd-none' }}">
                <div class="form-group">
                    <label for="stock_quantity" class="form-label">{{ __('cms.stock_quantity') }}</label>
                    <input type="number" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity') }}" min="0" class="form-input" placeholder="0">
                    @error('stock_quantity')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div id="stockStatusField" class="{{ old('track_stock', '1') == '0' ? '' : 'd-none' }}">
                <div class="form-group">
                    <label for="stock_status" class="form-label">{{ __('cms.stock_status') }}</label>
                    <select id="stock_status" name="stock_status" class="form-input">
                        <option value="in_stock" {{ old('stock_status', 'in_stock') == 'in_stock' ? 'selected' : '' }}>{{ __('cms.in_stock') }}</option>
                        <option value="out_of_stock" {{ old('stock_status') == 'out_of_stock' ? 'selected' : '' }}>{{ __('cms.out_of_stock') }}</option>
                        <option value="on_backorder" {{ old('stock_status') == 'on_backorder' ? 'selected' : '' }}>{{ __('cms.on_backorder') }}</option>
                    </select>
                    @error('stock_status')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Tab 4: Attributes (Variable Products Only) -->
        <div id="attributesTab" class="tab-content {{ old('product_type') == 'variable' ? '' : 'd-none' }}">
            <div class="form-group">
                <label class="form-label">{{ __('cms.select_attributes_for_variants') }}</label>
                <p class="text-tertiary text-sm mb-4">{{ __('cms.select_attributes_for_variants_description') }}</p>
                <div class="grid grid-auto-200 gap-4">
                    @foreach($attributes as $attribute)
                        <label class="variant-attribute-label flex items-center p-3 border border-gray-300 rounded-md cursor-pointer transition-all">
                            <input type="checkbox" class="variant-attribute-checkbox checkbox-custom-sm" value="{{ $attribute->id }}" data-attribute-name="{{ $attribute->name }}" data-attribute-values="{{ json_encode($attribute->values->pluck('value', 'id')->toArray()) }}">
                            <div class="flex-1">
                                <div class="font-semibold text-secondary">{{ $attribute->name }}</div>
                                <div class="text-sm text-tertiary">{{ $attribute->values->count() }} {{ __('cms.values') }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>
                @if($attributes->isEmpty())
                    <p class="text-tertiary text-sm mt-2">{{ __('cms.no_attributes_available') }}</p>
                @endif
            </div>
        </div>

        <!-- Tab 5: Variations (Variable Products Only) -->
        <div id="variationsTab" class="tab-content {{ old('product_type') == 'variable' ? '' : 'd-none' }}">
            <div id="variantsContainer">
                <div id="variantsList">
                    <!-- Variants will be added here -->
                </div>
                <button type="button" id="addVariantBtn" class="btn btn-primary mt-4">{{ __('cms.add_variant') }}</button>
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

        <div class="flex gap-4 mt-6">
            <button type="submit" class="btn btn-primary">{{ __('cms.save') }}</button>
            <a href="{{ route('products.index') }}" class="btn bg-gray-600 text-white">{{ __('cms.cancel') }}</a>
        </div>
    </form>
</div>

<!-- Image Selection Modal -->
<div id="imageSelectionModal" class="modal-overlay d-none">
    <div class="modal-content modal-content-large">
        <div class="modal-header">
            <h3 class="text-xl font-bold text-secondary">{{ __('cms.choose_media') }}</h3>
            <button type="button" class="modal-close" id="closeImageSelectionModal">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <div class="flex border-b border-gray-200">
            <button type="button" class="image-tab-btn active flex-1 p-4 bg-none border-none border-b-2 border-primary-blue text-primary-blue font-semibold cursor-pointer" data-tab="media">{{ __('cms.select_from_media') }}</button>
            <button type="button" class="image-tab-btn flex-1 p-4 bg-none border-none border-b-2 border-b-transparent text-tertiary font-semibold cursor-pointer" data-tab="upload">{{ __('cms.select_from_pc') }}</button>
        </div>

        <div class="modal-body modal-body-large">
            <div id="modalMediaTab" class="tab-content active">
                <div id="mediaGrid" class="grid grid-auto-150">
                    <div class="text-center p-8 text-tertiary grid-col-full">Loading images...</div>
                </div>
            </div>

            <div id="modalUploadTab" class="tab-content d-none">
                <div class="text-center p-8">
                    <input type="file" id="modalFileInput" accept="image/*,video/*" class="form-input max-w-400 mx-auto">
                    <p class="text-tertiary text-sm mt-4">{{ __('cms.select_file_from_computer') }}</p>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn bg-gray-600 text-white" id="cancelImageSelection">{{ __('cms.cancel') }}</button>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/product-form.js') }}"></script>
<script src="{{ asset('js/product-price-validation.js') }}"></script>
<script src="{{ asset('js/media-selector.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sync bilingual name and description fields
    const nameEnInput = document.getElementById('name_en');
    const nameArInput = document.getElementById('name_ar');
    const nameHiddenInput = document.getElementById('name');
    const descriptionEnInput = document.getElementById('description_en');
    const descriptionArInput = document.getElementById('description_ar');
    const descriptionHiddenInput = document.getElementById('description');
    
    function updateNameField() {
        const currentLocale = document.documentElement.lang || 'en';
        if (currentLocale === 'ar' && nameArInput.value) {
            nameHiddenInput.value = nameArInput.value;
        } else if (nameEnInput.value) {
            nameHiddenInput.value = nameEnInput.value;
        } else if (nameArInput.value) {
            nameHiddenInput.value = nameArInput.value;
        }
    }
    
    function updateDescriptionField() {
        const currentLocale = document.documentElement.lang || 'en';
        if (currentLocale === 'ar' && descriptionArInput.value) {
            descriptionHiddenInput.value = descriptionArInput.value;
        } else if (descriptionEnInput.value) {
            descriptionHiddenInput.value = descriptionEnInput.value;
        } else if (descriptionArInput.value) {
            descriptionHiddenInput.value = descriptionArInput.value;
        }
    }
    
    if (nameEnInput) nameEnInput.addEventListener('input', updateNameField);
    if (nameArInput) nameArInput.addEventListener('input', updateNameField);
    if (descriptionEnInput) descriptionEnInput.addEventListener('input', updateDescriptionField);
    if (descriptionArInput) descriptionArInput.addEventListener('input', updateDescriptionField);
    
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
        btn.addEventListener('click', function(e) {
            e.preventDefault();
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
            
            // Update content - hide all tab contents first
            document.querySelectorAll('.tab-content').forEach(content => {
                if (content.id && (content.id.includes('Tab') || content.id === 'simpleFieldsTab' || content.id === 'generalTab' || content.id === 'mediaTab' || content.id === 'attributesTab' || content.id === 'variationsTab')) {
                    content.classList.remove('active');
                    content.style.display = 'none';
                    content.style.setProperty('display', 'none', 'important');
                }
            });
            
            // Show target content
            const targetContent = document.getElementById(targetTabId);
            if (targetContent) {
                targetContent.classList.add('active');
                targetContent.style.display = 'block';
                targetContent.style.setProperty('display', 'block', 'important');
                targetContent.style.visibility = 'visible';
                targetContent.style.opacity = '1';
            } else {
                console.error('Tab content not found:', targetTabId, 'Available IDs:', Array.from(document.querySelectorAll('[id]')).map(el => el.id));
            }
        });
    });

    // Product type toggle - show/hide tabs
    const productTypeSelect = document.getElementById('product_type');
    const simpleFieldsTabBtn = document.getElementById('simpleFieldsTabBtn');
    const attributesTabBtn = document.getElementById('attributesTab');
    const variationsTabBtn = document.getElementById('variationsTab');
    const simpleFieldsTab = document.getElementById('simpleFieldsTab');
    const attributesTab = document.getElementById('attributesTab');
    const variationsTab = document.getElementById('variationsTab');
    
    if (productTypeSelect) {
        productTypeSelect.addEventListener('change', function() {
            if (this.value === 'simple') {
                // Show simple fields tab button, hide variable tabs
                simpleFieldsTabBtn.style.display = 'block';
                attributesTabBtn.style.display = 'none';
                variationsTabBtn.style.display = 'none';
                
                // Hide variable tab contents
                attributesTab.style.display = 'none';
                attributesTab.classList.remove('active');
                variationsTab.style.display = 'none';
                variationsTab.classList.remove('active');
                
                // If currently on variable tabs, switch to general tab
                if (attributesTab.classList.contains('active') || variationsTab.classList.contains('active')) {
                    // Switch to general tab instead of automatically showing stock & pricing
                    document.querySelector('[data-tab="general"]').click();
                }
            } else if (this.value === 'variable') {
                // Hide simple fields tab, show variable tabs
                simpleFieldsTabBtn.style.display = 'none';
                attributesTabBtn.style.display = 'block';
                variationsTabBtn.style.display = 'block';
                
                // Hide simple fields tab content
                simpleFieldsTab.style.display = 'none';
                simpleFieldsTab.classList.remove('active');
                
                // If currently on simple fields tab, switch to attributes
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
        
        mediaGrid.innerHTML = '<div class="text-center p-8 text-tertiary grid-col-full">Loading images...</div>';
        
        fetch('{{ route("media.json") }}')
            .then(response => response.json())
            .then(data => {
                mediaGrid.innerHTML = '';
                if (data.length === 0) {
                    mediaGrid.innerHTML = '<div class="text-center p-8 text-tertiary grid-col-full">No media files found</div>';
                    return;
                }
                
                data.forEach(media => {
                    const mediaItem = document.createElement('div');
                    mediaItem.className = 'media-select-item';
                    mediaItem.dataset.mediaPath = media.path;
                    mediaItem.dataset.mediaType = media.type;
                    mediaItem.dataset.mediaUrl = media.url;
                    
                    if (media.type === 'video') {
                        mediaItem.innerHTML = `
                            <video src="${media.url}"></video>
                        `;
                    } else {
                        mediaItem.innerHTML = `
                            <img src="${media.url}" alt="${media.name}">
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
                mediaGrid.innerHTML = '<div class="text-center p-8 text-red-500 grid-col-full">Error loading media files</div>';
            });
    }

    // Add item to gallery
    function addToGallery(mediaPath, mediaType, mediaUrl) {
        const galleryItem = document.createElement('div');
        galleryItem.className = 'gallery-item';
        
        galleryItem.innerHTML = `
            <input type="hidden" name="gallery[${galleryIndex}][media_path]" value="${mediaPath}">
            <input type="hidden" name="gallery[${galleryIndex}][media_type]" value="${mediaType}">
            ${mediaType === 'video' ? `
                <div class="gallery-item-video-wrapper">
                    <video src="${mediaUrl}"></video>
                    <button type="button" class="remove-gallery-item-btn">×</button>
                </div>
            ` : `
                <div class="gallery-item-image-wrapper">
                    <img src="${mediaUrl}" alt="Gallery item">
                    <button type="button" class="remove-gallery-item-btn">×</button>
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
                <h5 style="font-size: 1rem; font-weight: 600; color: #1f2937; margin: 0;">{{ __('cms.variant') }} ${variantIndex + 1}</h5>
                <button type="button" class="remove-variant-btn" style="background-color: #ef4444; color: white; border: none; padding: 0.25rem 0.75rem; border-radius: 0.25rem; cursor: pointer; font-size: 0.875rem;">{{ __('cms.remove') }}</button>
            </div>
            
            <!-- Attributes Repeater -->
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label class="form-label">{{ __('cms.attributes') }}</label>
                <div class="attributes-repeater" style="margin-top: 0.5rem;">
                    <!-- Attribute rows will be added here -->
                </div>
                <button type="button" class="add-attribute-row-btn btn" style="margin-top: 0.5rem; background-color: #6b7280; color: white; font-size: 0.875rem;">{{ __('cms.add_attribute') }}</button>
            </div>
            
            <div class="form-group" style="margin-bottom: 1rem;">
                <label class="form-label">{{ __('cms.description') }}</label>
                <textarea name="variants[${variantIndex}][description]" class="form-input" rows="2" placeholder="{{ __('cms.variant_description_optional') }}"></textarea>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                <div class="form-group">
                    <label class="form-label">{{ __('cms.price') }} <span style="color: #ef4444;">*</span></label>
                    <input type="number" name="variants[${variantIndex}][price]" step="0.01" min="0" required class="form-input" placeholder="0.00">
                </div>
                
                <div class="form-group">
                    <label class="form-label">{{ __('cms.sale_price') }}</label>
                    <input type="number" name="variants[${variantIndex}][sale_price]" step="0.01" min="0" class="form-input" placeholder="0.00">
                </div>
            </div>
            
            <div class="form-group" style="margin-bottom: 1rem;">
                <label class="form-label">{{ __('cms.track_stock') }}</label>
                <select name="variants[${variantIndex}][track_stock]" class="form-input track-stock-select">
                    <option value="1">{{ __('cms.yes') }}</option>
                    <option value="0">{{ __('cms.no') }}</option>
                </select>
            </div>
            
            <div class="stock-quantity-field" style="display: block; margin-bottom: 1rem;">
                <div class="form-group">
                    <label class="form-label">{{ __('cms.stock_quantity') }}</label>
                    <input type="number" name="variants[${variantIndex}][stock_quantity]" min="0" class="form-input" placeholder="0">
                </div>
            </div>
            
            <div class="stock-status-field" style="display: none; margin-bottom: 1rem;">
                <div class="form-group">
                    <label class="form-label">{{ __('cms.stock_status') }}</label>
                    <select name="variants[${variantIndex}][stock_status]" class="form-input">
                        <option value="in_stock">{{ __('cms.in_stock') }}</option>
                        <option value="out_of_stock">{{ __('cms.out_of_stock') }}</option>
                        <option value="on_backorder">{{ __('cms.on_backorder') }}</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">{{ __('cms.variant_image_optional') }}</label>
                <input type="text" name="variants[${variantIndex}][image]" class="form-input" placeholder="{{ __('cms.image_path_or_url') }}">
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
                    <option value="">{{ __('cms.select_attribute') }}</option>
                    ${Object.entries(attributesData).map(([id, attr]) => 
                        `<option value="${id}" data-attribute-name="${attr.name}">${attr.name}</option>`
                    ).join('')}
                </select>
            </div>
            <div class="form-group" style="flex: 1;">
                <label class="form-label">Value</label>
                <select class="form-input attribute-value-select" name="variants[${variantIdx}][attributes][${attrRowIndex}][value_id]" style="width: 100%;" disabled>
                    <option value="">{{ __('cms.select_attribute_first') }}</option>
                </select>
            </div>
            <button type="button" class="remove-attribute-row-btn" style="background-color: #ef4444; color: white; border: none; padding: 0.5rem 1rem; border-radius: 0.25rem; cursor: pointer; height: fit-content; margin-bottom: 0.5rem;">{{ __('cms.remove') }}</button>
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
            valueSelect.innerHTML = '<option value="">{{ __('cms.select_value') }}</option>';
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
