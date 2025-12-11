@extends('layouts.app')

@section('title', __('cms.edit_product'))

@section('content')
<div class="card pb-90 mb-0">
    <h2 class="section-heading-lg mb-6">{{ __('cms.edit_product') }}</h2>

    <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data" id="productForm">
        @csrf
        @method('PUT')

        <!-- Tabs Navigation -->
        <div class="tabs-nav">
            <button type="button" class="tab-btn active" data-tab="general">{{ __('cms.general_information') }}</button>
            <button type="button" class="tab-btn" data-tab="media">{{ __('cms.media') }}</button>
            <button type="button" class="tab-btn {{ old('product_type', $product->product_type) == 'simple' ? 'd-block' : 'd-none' }}" data-tab="simple-fields" id="simpleFieldsTabBtn">{{ __('cms.stock_pricing') }}</button>
            <button type="button" class="tab-btn {{ old('product_type', $product->product_type) == 'variable' ? 'd-block' : 'd-none' }}" data-tab="attributes" id="attributesTab">{{ __('cms.attributes') }}</button>
            <button type="button" class="tab-btn {{ old('product_type', $product->product_type) == 'variable' ? 'd-block' : 'd-none' }}" data-tab="variations" id="variationsTab">{{ __('cms.variations') }}</button>
        </div>

        <!-- Tab 1: General Information -->
        <div id="generalTab" class="tab-content active">
            <div class="form-group">
                <label class="form-label">{{ __('cms.name') }} <span class="required-asterisk">*</span></label>
                <div class="grid grid-2 gap-4">
                    <div>
                        <input type="text" id="name_en" name="name_en" value="{{ old('name_en', $product->name_en ?: $product->name) }}" class="form-input" placeholder="{{ __('cms.product_name_english') }}">
                        @error('name_en')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <input type="text" id="name_ar" name="name_ar" value="{{ old('name_ar', $product->name_ar ?: $product->name) }}" class="form-input" placeholder="{{ __('cms.product_name_arabic') }}" dir="rtl">
                        @error('name_ar')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <input type="hidden" id="name" name="name" value="{{ old('name', $product->name) }}">
                @error('name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('cms.description') }}</label>
                <div class="grid grid-2 gap-4">
                    <div>
                        <textarea id="description_en" name="description_en" rows="4" class="form-input" placeholder="{{ __('cms.product_description_english') }}">{{ old('description_en', $product->description_en ?: $product->description) }}</textarea>
                        @error('description_en')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <textarea id="description_ar" name="description_ar" rows="4" class="form-input" placeholder="{{ __('cms.product_description_arabic') }}" dir="rtl">{{ old('description_ar', $product->description_ar ?: $product->description) }}</textarea>
                        @error('description_ar')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <input type="hidden" id="description" name="description" value="{{ old('description', $product->description) }}">
                @error('description')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="grid grid-auto-250 gap-4">
                <div class="form-group">
                    <label for="product_type" class="form-label">Product Type <span class="required-asterisk">*</span></label>
                    <select id="product_type" name="product_type" required class="form-input">
                        <option value="simple" {{ old('product_type', $product->product_type) == 'simple' ? 'selected' : '' }}>Simple</option>
                        <option value="variable" {{ old('product_type', $product->product_type) == 'variable' ? 'selected' : '' }}>Variable</option>
                    </select>
                    @error('product_type')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status" class="form-label">Status <span class="required-asterisk">*</span></label>
                    <select id="status" name="status" required class="form-input">
                        <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Categories (Multi-select) -->
            <div class="form-group mt-4">
                <label for="categories" class="form-label">Categories</label>
                <div class="custom-multiselect relative">
                    <div class="multiselect-trigger multiselect-trigger-full" id="categoriesTrigger">
                        <div class="multiselect-selected">
                            <span class="placeholder-text text-quaternary">Select categories</span>
                            <div class="selected-categories-tags d-none flex-wrap gap-2">
                                <!-- Selected category tags will be added here -->
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" class="clear-all-categories-btn d-none bg-none border-0 text-red cursor-pointer p-1 text-sm" title="Clear all">Clear All</button>
                            <svg class="multiselect-arrow text-tertiary" style="transition: transform 0.2s;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    <div class="multiselect-dropdown multiselect-dropdown-full" id="categoriesDropdown">
                        @foreach($categories as $category)
                            <label class="multiselect-option multiselect-option-item">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="category-checkbox" data-category-name="{{ $category->name }}" {{ in_array($category->id, old('categories', $product->categories->pluck('id')->toArray())) ? 'checked' : '' }} style="margin-right: 0.75rem; width: 1rem; height: 1rem; cursor: pointer;">
                                <span class="text-secondary user-select-none">{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <small class="text-tertiary text-sm mt-1 d-block">Select one or more categories</small>
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
            @if($product->featured_image)
                <div id="currentImageContainer" style="margin-bottom: 0.5rem; position: relative; display: inline-block;">
                    <div style="position: relative; display: inline-block;">
                        <img id="currentImage" src="{{ asset('storage/' . $product->featured_image) }}" alt="{{ $product->name }}" style="width: 150px; height: 150px; object-fit: cover; border-radius: 0.25rem; border: 1px solid #e5e7eb; display: block;">
                        <button type="button" id="removeCurrentImageBtn" style="position: absolute; top: 0.5rem; right: 0.5rem; background-color: #ef4444; color: white; border: none; border-radius: 50%; width: 32px; height: 32px; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.2); z-index: 10;" title="Delete current image">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                    <p style="color: #6b7280; font-size: 0.875rem; margin-top: 0.5rem;">Current image</p>
                    <input type="hidden" id="delete_current_image" name="delete_current_image" value="0">
                </div>
            @endif
            <button type="button" id="chooseFileBtn" class="btn btn-primary" data-media-url="{{ route('media.json') }}">Choose File</button>
            <input type="file" id="featured_image" name="featured_image" accept="image/*" style="display: none;">
            <input type="hidden" id="selected_media_path" name="selected_media_path" value="">
            @error('featured_image')
                <span class="form-error">{{ $message }}</span>
            @enderror
            <div id="imagePreview" style="margin-top: 1rem; display: none; position: relative;">
                <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">New image preview:</p>
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
                @foreach($product->gallery as $index => $galleryItem)
                    <div class="gallery-item" data-gallery-id="{{ $galleryItem->id }}" style="position: relative; border: 1px solid #e5e7eb; border-radius: 0.5rem; overflow: hidden; background: white;">
                        <input type="hidden" name="gallery[{{ $index }}][id]" value="{{ $galleryItem->id }}">
                        <input type="hidden" name="gallery[{{ $index }}][media_path]" value="{{ $galleryItem->media_path }}">
                        <input type="hidden" name="gallery[{{ $index }}][media_type]" value="{{ $galleryItem->media_type }}">
                        @if($galleryItem->media_type === 'video')
                            <div style="position: relative; padding-top: 100%; background: #000;">
                                <video src="{{ asset('storage/' . $galleryItem->media_path) }}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain;"></video>
                                <button type="button" class="remove-gallery-item-btn" style="position: absolute; top: 0.5rem; right: 0.5rem; background-color: #ef4444; color: white; border: none; border-radius: 50%; width: 32px; height: 32px; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.2); z-index: 10;" title="Remove from gallery">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        @else
                            <div style="position: relative; padding-top: 100%; background: #f3f4f6;">
                                <img src="{{ asset('storage/' . $galleryItem->media_path) }}" alt="Gallery item" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;">
                                <button type="button" class="remove-gallery-item-btn" data-gallery-id="{{ $galleryItem->id }}" style="position: absolute; top: 0.5rem; right: 0.5rem; background-color: #ef4444; color: white; border: none; border-radius: 50%; width: 32px; height: 32px; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.2); z-index: 10;" title="Remove from gallery">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            @error('gallery')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>
        </div>

        <!-- Tab 3: Stock & Pricing (Simple Products Only) -->
        <div id="simpleFieldsTab" class="tab-content" style="display: none;">
            <h3 class="section-heading mb-4">Pricing</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div class="form-group">
                    <label for="price" class="form-label">Price <span style="color: #ef4444;">*</span></label>
                    <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" step="0.01" min="0" class="form-input" placeholder="0.00">
                    @error('price')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="sale_price" class="form-label">Sale Price</label>
                    <input type="number" id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" step="0.01" min="0" class="form-input" placeholder="0.00">
                    @error('sale_price')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <h3 class="section-heading" style="margin: 1.5rem 0 1rem 0;">Stock Management</h3>
            <div class="form-group">
                <label for="track_stock" class="form-label">Track Stock</label>
                <select id="track_stock" name="track_stock" class="form-input">
                    <option value="1" {{ old('track_stock', $product->track_stock ? '1' : '0') == '1' ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ old('track_stock', $product->track_stock ? '1' : '0') == '0' ? 'selected' : '' }}>No</option>
                </select>
            </div>

            <div id="stockQuantityField" style="display: {{ old('track_stock', $product->track_stock ? '1' : '0') == '1' ? 'block' : 'none' }};">
                <div class="form-group">
                    <label for="stock_quantity" class="form-label">Stock Quantity</label>
                    <input type="number" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" min="0" class="form-input" placeholder="0">
                    @error('stock_quantity')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div id="stockStatusField" style="display: {{ old('track_stock', $product->track_stock ? '1' : '0') == '0' ? 'block' : 'none' }};">
                <div class="form-group">
                    <label for="stock_status" class="form-label">Stock Status</label>
                    <select id="stock_status" name="stock_status" class="form-input">
                        <option value="in_stock" {{ old('stock_status', $product->stock_status) == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                        <option value="out_of_stock" {{ old('stock_status', $product->stock_status) == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                        <option value="on_backorder" {{ old('stock_status', $product->stock_status) == 'on_backorder' ? 'selected' : '' }}>On Backorder</option>
                    </select>
                    @error('stock_status')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Tab 4: Attributes (Variable Products Only) -->
        <div id="attributesTabContent" class="tab-content" style="display: {{ old('product_type', $product->product_type) == 'variable' ? 'block' : 'none' }};">
            <div class="form-group">
                <label class="form-label">{{ __('cms.select_attributes_for_variants') }}</label>
                <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 1rem;">{{ __('cms.select_attributes_for_variants_description') }}</p>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem;">
                    @foreach($attributes as $attribute)
                        @php
                            // Check if this attribute is used in existing variants
                            $isUsed = false;
                            if ($product->variants->isNotEmpty()) {
                                foreach ($product->variants as $variant) {
                                    if ($variant->attributes->where('attribute_name', $attribute->name)->isNotEmpty()) {
                                        $isUsed = true;
                                        break;
                                    }
                                }
                            }
                        @endphp
                        <label style="display: flex; align-items: center; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; cursor: pointer; transition: all 0.2s; {{ $isUsed ? 'border-color: var(--primary-blue); background-color: #f0f9ff;' : '' }}">
                            <input type="checkbox" class="variant-attribute-checkbox" value="{{ $attribute->id }}" data-attribute-name="{{ $attribute->name }}" data-attribute-values="{{ json_encode($attribute->values->pluck('value', 'id')->toArray()) }}" {{ $isUsed ? 'checked' : '' }} style="margin-right: 0.5rem; width: 1rem; height: 1rem; cursor: pointer;">
                            <div style="flex: 1;">
                                <div style="font-weight: 600; color: #374151;">{{ $attribute->name }}</div>
                                <div style="font-size: 0.875rem; color: #6b7280;">{{ $attribute->values->count() }} {{ __('cms.values') }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>
                @if($attributes->isEmpty())
                    <p style="color: #6b7280; font-size: 0.875rem; margin-top: 0.5rem;">{{ __('cms.no_attributes_available') }}</p>
                @endif
            </div>
        </div>

        <!-- Tab 5: Variations (Variable Products Only) -->
        <div id="variationsTabContent" class="tab-content" style="display: {{ old('product_type', $product->product_type) == 'variable' ? 'block' : 'none' }};">
            <div id="variantsList">
                @foreach($product->variants as $index => $variant)
                    <div class="variant-form" data-variant-id="{{ $variant->id }}" style="border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1.5rem; margin-bottom: 1rem; background-color: #f9fafb;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding-bottom: 0.75rem; border-bottom: 2px solid var(--primary-blue);">
                            <h5 style="font-size: 1rem; font-weight: 600; color: #1f2937; margin: 0;">Variant {{ $index + 1 }}</h5>
                            <button type="button" class="remove-variant-btn" style="background-color: #ef4444; color: white; border: none; padding: 0.25rem 0.75rem; border-radius: 0.25rem; cursor: pointer; font-size: 0.875rem;">Remove</button>
                        </div>
                        
                        <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant->id }}">
                        
                        <!-- Attributes Repeater -->
                        <div class="form-group" style="margin-bottom: 1.5rem;">
                            <label class="form-label">Attributes</label>
                            <div class="attributes-repeater" style="margin-top: 0.5rem;">
                                @foreach($variant->attributes as $attrIndex => $attribute)
                                    @php
                                        $attr = $attributes->firstWhere('name', $attribute->attribute_name);
                                        $attrValue = $attr ? $attr->values->firstWhere('value', $attribute->attribute_value) : null;
                                    @endphp
                                    <div class="attribute-row" style="display: flex; gap: 0.5rem; align-items: end; margin-bottom: 0.5rem;">
                                        <div class="form-group" style="flex: 1;">
                                            <label class="form-label">Attribute</label>
                                            <select class="form-input attribute-select" name="variants[{{ $index }}][attributes][{{ $attrIndex }}][attribute_id]" style="width: 100%;">
                                                <option value="">Select Attribute</option>
                                                @foreach($attributes as $attr)
                                                    <option value="{{ $attr->id }}" data-attribute-name="{{ $attr->name }}" {{ $attr->id == ($attr ? $attr->id : null) ? 'selected' : '' }}>{{ $attr->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group" style="flex: 1;">
                                            <label class="form-label">Value</label>
                                            <select class="form-input attribute-value-select" name="variants[{{ $index }}][attributes][{{ $attrIndex }}][value_id]" style="width: 100%;">
                                                <option value="">Select Value</option>
                                                @if($attr)
                                                    @foreach($attr->values as $val)
                                                        <option value="{{ $val->id }}" {{ $attrValue && $val->id == $attrValue->id ? 'selected' : '' }}>{{ $val->value }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <button type="button" class="remove-attribute-row-btn" style="background-color: #ef4444; color: white; border: none; padding: 0.5rem 1rem; border-radius: 0.25rem; cursor: pointer; height: fit-content; margin-bottom: 0.5rem;">Remove</button>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="add-attribute-row-btn btn" style="margin-top: 0.5rem; background-color: #6b7280; color: white; font-size: 0.875rem;" data-variant-index="{{ $index }}">+ Add Attribute</button>
                        </div>
                        
                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label class="form-label">Description</label>
                            <textarea name="variants[{{ $index }}][description]" rows="2" class="form-input" placeholder="Variant description (optional)">{{ old("variants.{$index}.description", $variant->description) }}</textarea>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                            <div class="form-group">
                                <label class="form-label">Price <span style="color: #ef4444;">*</span></label>
                                <input type="number" name="variants[{{ $index }}][price]" step="0.01" min="0" required class="form-input" value="{{ old("variants.{$index}.price", $variant->price) }}" placeholder="0.00">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Sale Price</label>
                                <input type="number" name="variants[{{ $index }}][sale_price]" step="0.01" min="0" class="form-input" value="{{ old("variants.{$index}.sale_price", $variant->sale_price) }}" placeholder="0.00">
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label class="form-label">Track Stock</label>
                            <select name="variants[{{ $index }}][track_stock]" class="form-input track-stock-select">
                                <option value="1" {{ old("variants.{$index}.track_stock", $variant->track_stock ? '1' : '0') == '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ old("variants.{$index}.track_stock", $variant->track_stock ? '1' : '0') == '0' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>

                        <div class="stock-quantity-field" style="display: {{ old("variants.{$index}.track_stock", $variant->track_stock ? '1' : '0') == '1' ? 'block' : 'none' }}; margin-bottom: 1rem;">
                            <div class="form-group">
                                <label class="form-label">Stock Quantity</label>
                                <input type="number" name="variants[{{ $index }}][stock_quantity]" min="0" class="form-input" value="{{ old("variants.{$index}.stock_quantity", $variant->stock_quantity) }}" placeholder="0">
                            </div>
                        </div>

                        <div class="stock-status-field" style="display: {{ old("variants.{$index}.track_stock", $variant->track_stock ? '1' : '0') == '0' ? 'block' : 'none' }}; margin-bottom: 1rem;">
                            <div class="form-group">
                                <label class="form-label">Stock Status</label>
                                <select name="variants[{{ $index }}][stock_status]" class="form-input">
                                    <option value="in_stock" {{ old("variants.{$index}.stock_status", $variant->stock_status) == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                    <option value="out_of_stock" {{ old("variants.{$index}.stock_status", $variant->stock_status) == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                                    <option value="on_backorder" {{ old("variants.{$index}.stock_status", $variant->stock_status) == 'on_backorder' ? 'selected' : '' }}>On Backorder</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Variant Image (Optional)</label>
                            @if($variant->image)
                                <div style="margin-bottom: 0.5rem;">
                                    <img src="{{ asset('storage/' . $variant->image) }}" alt="Variant image" style="max-width: 150px; max-height: 150px; border-radius: 0.25rem; border: 1px solid #e5e7eb;">
                                </div>
                            @endif
                            <input type="text" name="variants[{{ $index }}][image]" class="form-input" placeholder="Image path or URL" value="{{ old("variants.{$index}.image", $variant->image) }}">
                        </div>
                    </div>
                @endforeach
            </div>
            <button type="button" id="addVariantBtn" class="btn btn-primary" style="margin-top: 1rem;">{{ __('cms.add_variant') }}</button>
            
            @error('variants')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <!-- Variable Product Fields (Legacy - kept for backward compatibility) -->
        <div id="variableProductFields" style="display: none;">
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
    </form>
</div>

<!-- Save/Cancel buttons - Fixed at bottom of viewport, always visible -->
<div id="productFormButtons" class="product-form-buttons">
    <button type="submit" form="productForm" class="btn btn-primary" style="min-width: 120px; padding: 0.75rem 1.5rem; font-size: 1rem; font-weight: 600; cursor: pointer; background-color: #099ecb; color: white; border: none; border-radius: 0.375rem;">{{ __('cms.save') }}</button>
    <a href="{{ route('products.index') }}" class="btn" style="background-color: #6b7280; color: white; min-width: 120px; padding: 0.75rem 1.5rem; text-decoration: none; display: inline-block; text-align: center; font-size: 1rem; font-weight: 600; border-radius: 0.375rem; cursor: pointer;">{{ __('cms.cancel') }}</a>
</div>

<script>
    // Ensure buttons are always fixed at bottom of viewport
    (function() {
        const buttonsContainer = document.getElementById('productFormButtons');
        if (!buttonsContainer) return;
        
        // Force fixed positioning
        buttonsContainer.style.position = 'fixed';
        buttonsContainer.style.bottom = '0';
        buttonsContainer.style.zIndex = '9999';
        
        function updateButtonPosition() {
            const sidebar = document.getElementById('sidebar');
            if (sidebar && buttonsContainer) {
                const sidebarWidth = sidebar.offsetWidth || 250;
                const computedStyle = window.getComputedStyle(sidebar);
                const isVisible = computedStyle.display !== 'none' && computedStyle.visibility !== 'hidden';
                
                if (isVisible) {
                    buttonsContainer.style.left = sidebarWidth + 'px';
                } else {
                    buttonsContainer.style.left = '0';
                }
            }
        }
        
        // Initial update
        updateButtonPosition();
        
        // Update on various events
        window.addEventListener('resize', updateButtonPosition);
        window.addEventListener('load', updateButtonPosition);
        
        // Watch for sidebar changes
        const sidebar = document.getElementById('sidebar');
        if (sidebar) {
            const observer = new MutationObserver(function() {
                updateButtonPosition();
            });
            observer.observe(sidebar, { 
                attributes: true, 
                attributeFilter: ['class', 'style'],
                childList: false,
                subtree: false
            });
        }
        
        // Fallback: check periodically
        setInterval(updateButtonPosition, 1000);
    })();
</script>

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
                    <input type="file" id="modalFileInput" accept="image/*,video/*" multiple class="form-input" style="max-width: 400px; margin: 0 auto;">
                    <p style="color: #6b7280; font-size: 0.875rem; margin-top: 1rem;">Select one or more image or video files from your computer</p>
                </div>
            </div>
        </div>

        <div class="modal-footer" style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.5rem; border-top: 1px solid #e5e7eb;">
            <div id="selectedCount" style="color: #6b7280; font-size: 0.875rem; font-weight: 500;">
                <span id="selectedCountText">0 selected</span>
            </div>
            <div style="display: flex; gap: 0.75rem;">
                <button type="button" class="btn" id="cancelImageSelection" style="background-color: #6b7280; color: white;">Cancel</button>
                <button type="button" class="btn" id="addSelectedToGallery" style="background-color: var(--primary-blue); color: white; display: none;">Add Selected (<span id="addSelectedCount">0</span>)</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/product-form.js') }}"></script>
<script src="{{ asset('js/product-price-validation.js') }}"></script>
<script src="{{ asset('js/media-selector.js') }}"></script>
<script>
// Initialize existing variants and gallery for edit page
document.addEventListener('DOMContentLoaded', function() {
    // Debug: Check if tab content exists
    const simpleFieldsTabContent = document.getElementById('simpleFieldsTab');
    console.log('simpleFieldsTab element:', simpleFieldsTabContent);
    if (simpleFieldsTabContent) {
        console.log('simpleFieldsTab innerHTML length:', simpleFieldsTabContent.innerHTML.length);
        console.log('simpleFieldsTab children:', simpleFieldsTabContent.children.length);
    }
    
    // Tab switching functionality
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    // Map data-tab values to actual tab IDs
    const tabIdMap = {
        'general': 'generalTab',
        'media': 'mediaTab',
        'simple-fields': 'simpleFieldsTab',
        'attributes': 'attributesTabContent',
        'variations': 'variationsTabContent'
    };
    
    // Function to switch tabs
    function switchTab(targetTabId) {
        // Hide all tab contents
        const mainTabContents = ['generalTab', 'mediaTab', 'simpleFieldsTab', 'attributesTabContent', 'variationsTabContent'];
        mainTabContents.forEach(tabId => {
            const tabContent = document.getElementById(tabId);
            if (tabContent) {
                tabContent.style.display = 'none';
                tabContent.classList.remove('active');
            }
        });
        
        // Show target tab content
        const targetContent = document.getElementById(targetTabId);
        if (targetContent) {
            // Force show with multiple methods
            targetContent.style.removeProperty('display');
            targetContent.style.removeProperty('visibility');
            targetContent.style.display = 'block';
            targetContent.style.visibility = 'visible';
            targetContent.style.opacity = '1';
            targetContent.style.height = 'auto';
            targetContent.classList.add('active');
            
            // Double check it's visible
            setTimeout(() => {
                if (targetContent.style.display !== 'block') {
                    targetContent.style.setProperty('display', 'block', 'important');
                }
                console.log('Tab switched to:', targetTabId, 'Display:', targetContent.style.display, 'Visible:', targetContent.offsetHeight > 0);
            }, 10);
        } else {
            console.error('Tab content not found:', targetTabId);
        }
    }
    
    tabButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
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
            
            // Switch tab content
            switchTab(targetTabId);
        });
    });

    // Product type toggle - show/hide tabs
    const productTypeSelect = document.getElementById('product_type');
    const simpleFieldsTabBtn = document.getElementById('simpleFieldsTabBtn');
    const attributesTabBtn = document.getElementById('attributesTab');
    const variationsTabBtn = document.getElementById('variationsTab');
    const simpleFieldsTab = document.getElementById('simpleFieldsTab');
    const attributesTabContent = document.getElementById('attributesTabContent');
    const variationsTabContent = document.getElementById('variationsTabContent');
    
    if (productTypeSelect) {
        productTypeSelect.addEventListener('change', function() {
            if (this.value === 'simple') {
                // Show simple fields tab button, hide variable tabs
                simpleFieldsTabBtn.style.display = 'block';
                attributesTabBtn.style.display = 'none';
                variationsTabBtn.style.display = 'none';
                
                // Hide variable tab contents
                attributesTabContent.style.display = 'none';
                attributesTabContent.classList.remove('active');
                variationsTabContent.style.display = 'none';
                variationsTabContent.classList.remove('active');
                
                // If currently on variable tabs, switch to general tab
                if (attributesTabContent.classList.contains('active') || variationsTabContent.classList.contains('active')) {
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
    // Gallery functionality
    const addGalleryItemBtn = document.getElementById('addGalleryItemBtn');
    const galleryContainer = document.getElementById('galleryContainer');
    const imageSelectionModal = document.getElementById('imageSelectionModal');
    const closeImageSelectionModal = document.getElementById('closeImageSelectionModal');
    const cancelImageSelection = document.getElementById('cancelImageSelection');
    const mediaGrid = document.getElementById('mediaGrid');
    const modalFileInput = document.getElementById('modalFileInput');
    let galleryIndex = {{ $product->gallery ? $product->gallery->count() : 0 }};
    let currentSelectionMode = 'gallery'; // 'featured' or 'gallery'
    window.currentSelectionMode = currentSelectionMode; // Make it globally accessible for media-selector.js
    let selectedMediaItems = []; // Array to track selected media items
    let itemsToRemoveFromGallery = []; // Array to track items to remove from gallery

    // Store original gallery items when modal opens
    let originalGalleryItems = []; // Array of {id, path, type, url}
    
    // Open gallery selection modal
    if (addGalleryItemBtn) {
        addGalleryItemBtn.addEventListener('click', function() {
            currentSelectionMode = 'gallery';
            window.currentSelectionMode = 'gallery';
            // Get current gallery items to mark them as selected
            const currentGalleryPaths = getCurrentGalleryPaths();
            
            // Store original gallery items with their IDs
            originalGalleryItems = [];
            const galleryItems = galleryContainer.querySelectorAll('.gallery-item');
            galleryItems.forEach(item => {
                const pathInput = item.querySelector('input[name*="[media_path]"]');
                const idInput = item.querySelector('input[name*="[id]"]');
                const typeInput = item.querySelector('input[name*="[media_type]"]');
                const img = item.querySelector('img');
                const video = item.querySelector('video');
                if (pathInput) {
                    originalGalleryItems.push({
                        id: idInput ? idInput.value : null,
                        path: pathInput.value,
                        type: typeInput ? typeInput.value : 'image',
                        url: img ? img.src : (video ? video.src : '{{ asset("storage") }}/' + pathInput.value)
                    });
                }
            });
            
            selectedMediaItems = []; // Reset selection array, will be populated by loadMediaGrid
            itemsToRemoveFromGallery = []; // Reset removal list
            updateSelectedCount();
            imageSelectionModal.style.display = 'flex';
            loadMediaGrid(currentGalleryPaths);
        });
    }

    // Close modal
    if (closeImageSelectionModal) {
        closeImageSelectionModal.addEventListener('click', function() {
            selectedMediaItems = []; // Reset selection
            updateSelectedCount();
            imageSelectionModal.style.display = 'none';
        });
    }

    if (cancelImageSelection) {
        cancelImageSelection.addEventListener('click', function() {
            selectedMediaItems = []; // Reset selection
            updateSelectedCount();
            imageSelectionModal.style.display = 'none';
        });
    }
    
    // Get current gallery items paths
    function getCurrentGalleryPaths() {
        const galleryItems = galleryContainer.querySelectorAll('.gallery-item');
        const paths = [];
        galleryItems.forEach(item => {
            const pathInput = item.querySelector('input[name*="[media_path]"]');
            if (pathInput) {
                paths.push(pathInput.value);
            }
        });
        return paths;
    }
    
    // Add selected items to gallery
    const addSelectedToGalleryBtn = document.getElementById('addSelectedToGallery');
    if (addSelectedToGalleryBtn) {
        addSelectedToGalleryBtn.addEventListener('click', function() {
            selectedMediaItems.forEach(item => {
                addToGallery(item.path, item.type, item.url);
            });
            selectedMediaItems = []; // Reset selection
            updateSelectedCount();
            // Close modal after adding
            imageSelectionModal.style.display = 'none';
        });
    }
    
    // Remove item from gallery
    function removeFromGallery(mediaPath) {
        const galleryItems = galleryContainer.querySelectorAll('.gallery-item');
        galleryItems.forEach(item => {
            const pathInput = item.querySelector('input[name*="[media_path]"]');
            if (pathInput && pathInput.value === mediaPath) {
                // Check if it has an ID (existing item from database)
                const idInput = item.querySelector('input[name*="[id]"]');
                if (idInput && idInput.value) {
                    // Add to removal list
                    if (!itemsToRemoveFromGallery.includes(idInput.value)) {
                        itemsToRemoveFromGallery.push(idInput.value);
                    }
                    // Add hidden input for deletion
                    let deleteInput = document.querySelector(`input[name="delete_gallery[]"][value="${idInput.value}"]`);
                    if (!deleteInput) {
                        deleteInput = document.createElement('input');
                        deleteInput.type = 'hidden';
                        deleteInput.name = 'delete_gallery[]';
                        deleteInput.value = idInput.value;
                        document.getElementById('productForm').appendChild(deleteInput);
                    }
                }
                item.remove();
            }
        });
    }
    
    // Toggle media selection
    function toggleMediaSelection(mediaItem, media) {
        const currentGalleryPaths = getCurrentGalleryPaths();
        const isInGallery = currentGalleryPaths.includes(media.path);
        const index = selectedMediaItems.findIndex(item => item.path === media.path);
        const isSelected = mediaItem.classList.contains('selected');
        
        if (isInGallery && isSelected) {
            // Item is already in gallery and selected - remove it
            removeFromGallery(media.path);
            
            // Remove from selectedMediaItems array as well
            const selectedIndex = selectedMediaItems.findIndex(item => item.path === media.path);
            if (selectedIndex > -1) {
                selectedMediaItems.splice(selectedIndex, 1);
            }
            
            // Remove visual selection
            mediaItem.classList.remove('selected');
            mediaItem.style.borderColor = 'transparent';
            mediaItem.style.borderWidth = '2px';
            mediaItem.style.opacity = '1';
            mediaItem.title = '';
            
            // Remove overlay
            const overlay = mediaItem.querySelector('.selection-overlay');
            if (overlay) {
                overlay.remove();
            }
        } else if (index > -1) {
            // Remove from selection (not in gallery yet)
            selectedMediaItems.splice(index, 1);
            mediaItem.classList.remove('selected');
            mediaItem.style.borderColor = 'transparent';
            mediaItem.style.borderWidth = '2px';
            
            // Remove overlay
            const overlay = mediaItem.querySelector('.selection-overlay');
            if (overlay) {
                overlay.remove();
            }
        } else {
            // Add to selection (either new item or re-adding removed item)
            const currentGalleryPaths = getCurrentGalleryPaths();
            const isCurrentlyInGallery = currentGalleryPaths.includes(media.path);
            
            // Check if it was originally in gallery (has an ID in originalGalleryItems)
            const originalItem = originalGalleryItems.find(item => item.path === media.path);
            
            if (originalItem && originalItem.id && !isCurrentlyInGallery) {
                // It was in gallery but was removed - re-add it to gallery with original ID
                addToGalleryWithId(media.path, media.type, media.url, originalItem.id);
                
                // Remove from delete list
                const removeIndex = itemsToRemoveFromGallery.indexOf(originalItem.id);
                if (removeIndex > -1) {
                    itemsToRemoveFromGallery.splice(removeIndex, 1);
                }
                // Remove delete input
                const deleteInput = document.querySelector(`input[name="delete_gallery[]"][value="${originalItem.id}"]`);
                if (deleteInput) {
                    deleteInput.remove();
                }
            } else if (!originalItem && !isCurrentlyInGallery) {
                // New item - add to selectedMediaItems but don't add to gallery yet
                // It will be added when user clicks "Add Selected"
                selectedMediaItems.push({
                    path: media.path,
                    type: media.type,
                    url: media.url
                });
            } else if (isCurrentlyInGallery) {
                // Already in gallery, don't add to selectedMediaItems
                // Just update visual state
            }
            
            // Update visual selection state
            mediaItem.classList.add('selected');
            mediaItem.style.borderColor = 'var(--primary-blue)';
            mediaItem.style.borderWidth = '3px';
            mediaItem.style.opacity = '1';
            mediaItem.title = '';
            
            // Add overlay
            const overlay = document.createElement('div');
            overlay.className = 'selection-overlay';
            overlay.style.cssText = 'position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(9, 158, 203, 0.3); display: flex; align-items: center; justify-content: center; z-index: 5;';
            overlay.innerHTML = '<div style="background: var(--primary-blue); color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold;">✓</div>';
            mediaItem.appendChild(overlay);
        }
        
        updateSelectedCount();
    }
    
    // Update selected count display
    function updateSelectedCount() {
        const selectedCountText = document.getElementById('selectedCountText');
        const addSelectedCount = document.getElementById('addSelectedCount');
        const addSelectedBtn = document.getElementById('addSelectedToGallery');
        
        // Count items currently in gallery (visible in the gallery container)
        const currentGalleryPaths = getCurrentGalleryPaths();
        const totalCount = currentGalleryPaths.length;
        
        // Count only new selections (items in selectedMediaItems that are not yet in gallery)
        // These are items the user selected but haven't been added yet
        const newSelections = selectedMediaItems.filter(item => !currentGalleryPaths.includes(item.path)).length;
        
        if (selectedCountText) {
            selectedCountText.textContent = totalCount + (totalCount === 1 ? ' item selected' : ' items selected');
        }
        if (addSelectedCount) {
            addSelectedCount.textContent = newSelections;
        }
        if (addSelectedBtn) {
            if (newSelections > 0) {
                addSelectedBtn.style.display = 'block';
                addSelectedBtn.style.visibility = 'visible';
            } else {
                addSelectedBtn.style.display = 'none';
                addSelectedBtn.style.visibility = 'hidden';
            }
        }
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
    function loadMediaGrid(existingGalleryPaths = []) {
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
                
                // Reset selectedMediaItems - only track new selections, not items already in gallery
                selectedMediaItems = [];
                updateSelectedCount();
                
                data.forEach(media => {
                    const mediaItem = document.createElement('div');
                    mediaItem.style.cssText = 'position: relative; padding-top: 100%; background: #f3f4f6; border-radius: 0.5rem; overflow: hidden; cursor: pointer; border: 2px solid transparent; transition: all 0.2s;';
                    mediaItem.className = 'media-select-item';
                    mediaItem.dataset.mediaPath = media.path;
                    mediaItem.dataset.mediaType = media.type;
                    mediaItem.dataset.mediaUrl = media.url;
                    
                    // Check if already in gallery (should be selected)
                    const isSelected = existingGalleryPaths.includes(media.path);
                    
                    if (media.type === 'video') {
                        mediaItem.innerHTML = `
                            <video src="${media.url}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;"></video>
                            ${isSelected ? '<div class="selection-overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(9, 158, 203, 0.3); display: flex; align-items: center; justify-content: center; z-index: 5;"><div style="background: var(--primary-blue); color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold;">✓</div></div>' : ''}
                        `;
                    } else {
                        mediaItem.innerHTML = `
                            <img src="${media.url}" alt="${media.name}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;">
                            ${isSelected ? '<div class="selection-overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(9, 158, 203, 0.3); display: flex; align-items: center; justify-content: center; z-index: 5;"><div style="background: var(--primary-blue); color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold;">✓</div></div>' : ''}
                        `;
                    }
                    
                    if (isSelected) {
                        mediaItem.style.borderColor = 'var(--primary-blue)';
                        mediaItem.style.borderWidth = '3px';
                        mediaItem.classList.add('selected');
                        // Add a visual indicator that it's already in gallery
                        mediaItem.style.opacity = '0.7';
                        mediaItem.title = 'Already in gallery';
                    }
                    
                    mediaItem.addEventListener('click', function() {
                        if (currentSelectionMode === 'gallery') {
                            toggleMediaSelection(mediaItem, media);
                        }
                    });
                    
                    mediaItem.addEventListener('mouseenter', function() {
                        if (!this.classList.contains('selected')) {
                            this.style.borderColor = 'var(--primary-blue)';
                            this.style.borderWidth = '2px';
                            this.style.transform = 'scale(1.05)';
                        } else if (existingGalleryPaths.includes(media.path)) {
                            // Show it can be unselected
                            this.style.cursor = 'pointer';
                            this.style.opacity = '0.8';
                        }
                    });
                    
                    mediaItem.addEventListener('mouseleave', function() {
                        if (!this.classList.contains('selected')) {
                            this.style.borderColor = 'transparent';
                            this.style.borderWidth = '2px';
                            this.style.transform = 'scale(1)';
                        } else if (existingGalleryPaths.includes(media.path)) {
                            this.style.opacity = '0.7';
                        }
                    });
                    
                    mediaGrid.appendChild(mediaItem);
                });
            })
            .catch(error => {
                console.error('Error loading media:', error);
                mediaGrid.innerHTML = '<div style="text-align: center; padding: 2rem; color: #ef4444; grid-column: 1 / -1;">Error loading media files</div>';
            });
    }

    // Add item to gallery with ID (for restoring removed items)
    function addToGalleryWithId(mediaPath, mediaType, mediaUrl, itemId) {
        const galleryItem = document.createElement('div');
        galleryItem.className = 'gallery-item';
        galleryItem.style.cssText = 'position: relative; border: 1px solid #e5e7eb; border-radius: 0.5rem; overflow: hidden; background: white;';
        
        galleryItem.innerHTML = `
            <input type="hidden" name="gallery[${galleryIndex}][id]" value="${itemId}">
            <input type="hidden" name="gallery[${galleryIndex}][media_path]" value="${mediaPath}">
            <input type="hidden" name="gallery[${galleryIndex}][media_type]" value="${mediaType}">
            ${mediaType === 'video' ? `
                <div style="position: relative; padding-top: 100%; background: #000;">
                    <video src="${mediaUrl}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain;"></video>
                    <button type="button" class="remove-gallery-item-btn" style="position: absolute; top: 0.5rem; right: 0.5rem; background-color: #ef4444; color: white; border: none; border-radius: 50%; width: 32px; height: 32px; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.2); z-index: 10;" title="Remove from gallery">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            ` : `
                <div style="position: relative; padding-top: 100%; background: #f3f4f6;">
                    <img src="${mediaUrl}" alt="Gallery item" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;">
                    <button type="button" class="remove-gallery-item-btn" style="position: absolute; top: 0.5rem; right: 0.5rem; background-color: #ef4444; color: white; border: none; border-radius: 50%; width: 32px; height: 32px; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.2); z-index: 10;" title="Remove from gallery">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
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
    
    // Add item to gallery
    function addToGallery(mediaPath, mediaType, mediaUrl) {
        // Check if already in gallery to prevent duplicates
        const currentGalleryPaths = getCurrentGalleryPaths();
        if (currentGalleryPaths.includes(mediaPath)) {
            return; // Already in gallery, don't add again
        }
        
        const galleryItem = document.createElement('div');
        galleryItem.className = 'gallery-item';
        galleryItem.style.cssText = 'position: relative; border: 1px solid #e5e7eb; border-radius: 0.5rem; overflow: hidden; background: white;';
        
        galleryItem.innerHTML = `
            <input type="hidden" name="gallery[${galleryIndex}][media_path]" value="${mediaPath}">
            <input type="hidden" name="gallery[${galleryIndex}][media_type]" value="${mediaType}">
            ${mediaType === 'video' ? `
                <div style="position: relative; padding-top: 100%; background: #000;">
                    <video src="${mediaUrl}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain;"></video>
                    <button type="button" class="remove-gallery-item-btn" style="position: absolute; top: 0.5rem; right: 0.5rem; background-color: #ef4444; color: white; border: none; border-radius: 50%; width: 32px; height: 32px; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.2); z-index: 10;" title="Remove from gallery">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            ` : `
                <div style="position: relative; padding-top: 100%; background: #f3f4f6;">
                    <img src="${mediaUrl}" alt="Gallery item" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;">
                    <button type="button" class="remove-gallery-item-btn" style="position: absolute; top: 0.5rem; right: 0.5rem; background-color: #ef4444; color: white; border: none; border-radius: 50%; width: 32px; height: 32px; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.2); z-index: 10;" title="Remove from gallery">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
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
            const files = Array.from(e.target.files);
            if (files.length === 0) return;
            
            // Upload all files
            const uploadPromises = files.map(file => {
                const formData = new FormData();
                formData.append('file', file);
                
                return fetch('{{ route("media.store") }}', {
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
                            // Add to selection instead of directly to gallery
                            const mediaItem = {
                                path: data.path,
                                type: mediaType,
                                url: mediaUrl
                            };
                            
                            // Check if not already selected
                            if (!selectedMediaItems.some(item => item.path === data.path)) {
                                selectedMediaItems.push(mediaItem);
                            }
                        }
                        
                        return { success: true, path: data.path, type: mediaType, url: mediaUrl };
                    }
                    return { success: false };
                })
                .catch(error => {
                    console.error('Error uploading file:', error);
                    return { success: false, error: error.message };
                });
            });
            
            // Wait for all uploads to complete
            Promise.all(uploadPromises).then(results => {
                const successCount = results.filter(r => r.success).length;
                if (successCount > 0) {
                    updateSelectedCount();
                    loadMediaGrid(); // Reload to show new files
                    
                    // Switch to media tab to show uploaded files
                    const mediaTabBtn = document.querySelector('.image-tab-btn[data-tab="media"]');
                    if (mediaTabBtn) {
                        mediaTabBtn.click();
                    }
                }
                
                if (successCount < files.length) {
                    alert(`Successfully uploaded ${successCount} of ${files.length} files.`);
                }
                
                modalFileInput.value = '';
            });
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
            
            // If switching to media tab and in gallery mode, reload the media grid
            if (targetTab === 'media' && currentSelectionMode === 'gallery') {
                const currentGalleryPaths = getCurrentGalleryPaths();
                loadMediaGrid(currentGalleryPaths);
            }
        });
    });

    // Handle gallery item removal
    document.querySelectorAll('.remove-gallery-item-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const galleryId = this.getAttribute('data-gallery-id');
            if (galleryId) {
                // Add to delete list
                let deleteInput = document.querySelector('input[name="delete_gallery[]"]');
                if (!deleteInput) {
                    deleteInput = document.createElement('input');
                    deleteInput.type = 'hidden';
                    deleteInput.name = 'delete_gallery[]';
                    deleteInput.value = galleryId;
                    document.getElementById('productForm').appendChild(deleteInput);
                } else {
                    // Create new input for each deleted item
                    const newInput = document.createElement('input');
                    newInput.type = 'hidden';
                    newInput.name = 'delete_gallery[]';
                    newInput.value = galleryId;
                    document.getElementById('productForm').appendChild(newInput);
                }
            }
            this.closest('.gallery-item').remove();
        });
    });

    // Handle variant removal
    document.querySelectorAll('.remove-variant-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const variantId = this.getAttribute('data-variant-id');
            if (variantId) {
                // Add to delete list
                let deleteInput = document.querySelector('input[name="delete_variants[]"]');
                if (!deleteInput) {
                    deleteInput = document.createElement('input');
                    deleteInput.type = 'hidden';
                    deleteInput.name = 'delete_variants[]';
                    deleteInput.value = variantId;
                    document.getElementById('productForm').appendChild(deleteInput);
                } else {
                    const newInput = document.createElement('input');
                    newInput.type = 'hidden';
                    newInput.name = 'delete_variants[]';
                    newInput.value = variantId;
                    document.getElementById('productForm').appendChild(newInput);
                }
            }
            this.closest('.variant-item').remove();
        });
    });

    // Initialize variant index for new variants
    const existingVariants = document.querySelectorAll('.variant-item');
    if (existingVariants.length > 0) {
        window.variantIndex = existingVariants.length;
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
</style>
<script>
// Variant Repeater Logic for Edit Page
document.addEventListener('DOMContentLoaded', function() {
    const addVariantBtn = document.getElementById('addVariantBtn');
    const variantsList = document.getElementById('variantsList');
    let variantIndex = {{ $product->variants ? $product->variants->count() : 0 }};
    
    // Load attributes data
    const attributesDataElement = document.getElementById('attributesData');
    if (!attributesDataElement) return;
    
    const attributesData = JSON.parse(attributesDataElement.textContent);

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

    // Initialize existing variant attribute dropdowns
    document.querySelectorAll('.attribute-select').forEach(select => {
        select.addEventListener('change', function() {
            const valueSelect = this.closest('.attribute-row').querySelector('.attribute-value-select');
            const selectedAttrId = this.value;
            const selectedOption = this.options[this.selectedIndex];
            const attributeName = selectedOption.getAttribute('data-attribute-name');
            
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
            }
        });
    });

    // Handle track stock toggle for existing variants
    document.querySelectorAll('.track-stock-select').forEach(select => {
        select.addEventListener('change', function() {
            const variantDiv = this.closest('.variant-form');
            const stockQuantityField = variantDiv.querySelector('.stock-quantity-field');
            const stockStatusField = variantDiv.querySelector('.stock-status-field');
            
            if (this.value === '1') {
                stockQuantityField.style.display = 'block';
                stockStatusField.style.display = 'none';
            } else {
                stockQuantityField.style.display = 'none';
                stockStatusField.style.display = 'block';
            }
        });
    });

    // Handle remove variant button for existing variants
    document.querySelectorAll('.remove-variant-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const variantDiv = this.closest('.variant-form');
            const variantId = variantDiv.getAttribute('data-variant-id');
            
            if (variantId) {
                // Add to delete list
                let deleteInput = document.querySelector('input[name="delete_variants[]"]');
                if (!deleteInput) {
                    deleteInput = document.createElement('input');
                    deleteInput.type = 'hidden';
                    deleteInput.name = 'delete_variants[]';
                    deleteInput.value = variantId;
                    document.getElementById('productForm').appendChild(deleteInput);
                } else {
                    const newInput = document.createElement('input');
                    newInput.type = 'hidden';
                    newInput.name = 'delete_variants[]';
                    newInput.value = variantId;
                    document.getElementById('productForm').appendChild(newInput);
                }
            }
            variantDiv.remove();
        });
    });

    // Handle add attribute row for existing variants
    document.querySelectorAll('.add-attribute-row-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const variantIndex = this.getAttribute('data-variant-index');
            const attributesRepeater = this.previousElementSibling;
            addAttributeRow(attributesRepeater, variantIndex);
        });
    });

    // Handle remove attribute row
    document.querySelectorAll('.remove-attribute-row-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            this.closest('.attribute-row').remove();
        });
    });

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
.tab-content {
    min-height: 300px;
}
</style>
<script>
// Reindex gallery items before form submission to ensure proper array indexing
function reindexGalleryItems() {
    const galleryItems = document.querySelectorAll('#galleryContainer .gallery-item');
    console.log('Reindexing gallery items, count:', galleryItems.length);
    
    let validIndex = 0;
    galleryItems.forEach((item, index) => {
        // Skip items that are marked for removal or don't have media_path
        const pathInput = item.querySelector('input[name*="[media_path]"]');
        if (!pathInput || !pathInput.value || pathInput.value.trim() === '') {
            console.log(`Skipping item at index ${index} - no valid media_path`);
            return; // Skip this item
        }
        
        // Update all input names to use sequential index starting from 0
        const idInput = item.querySelector('input[name*="[id]"]');
        const typeInput = item.querySelector('input[name*="[media_type]"]');
        
        // Only include ID if it exists and has a value (existing items)
        if (idInput && idInput.value && idInput.value.trim() !== '') {
            idInput.name = `gallery[${validIndex}][id]`;
            console.log(`Updated id input: ${idInput.name} = ${idInput.value}`);
        } else if (idInput) {
            // Remove ID input if it's empty (new items shouldn't have ID)
            idInput.remove();
            console.log(`Removed empty id input for new item at index ${validIndex}`);
        }
        
        if (pathInput) {
            pathInput.name = `gallery[${validIndex}][media_path]`;
            console.log(`Updated path input: ${pathInput.name} = ${pathInput.value}`);
        }
        if (typeInput) {
            typeInput.name = `gallery[${validIndex}][media_type]`;
            console.log(`Updated type input: ${typeInput.name} = ${typeInput.value}`);
        }
        
        validIndex++;
    });
    
    console.log(`Reindexing complete. Valid items: ${validIndex}`);
    return true; // Allow form submission
}

// Add form submit event listener to reindex gallery items
document.addEventListener('DOMContentLoaded', function() {
    const productForm = document.getElementById('productForm');
    if (productForm) {
        productForm.addEventListener('submit', function(e) {
            console.log('Form submitting, reindexing gallery items...');
            const beforeCount = document.querySelectorAll('#galleryContainer .gallery-item').length;
            console.log('Gallery items before reindex:', beforeCount);
            reindexGalleryItems();
            const afterCount = document.querySelectorAll('#galleryContainer .gallery-item').length;
            console.log('Gallery items after reindex:', afterCount);
            
            // Verify all inputs have correct names
            const allInputs = document.querySelectorAll('#galleryContainer input[name*="gallery"]');
            console.log('Total gallery inputs:', allInputs.length);
            allInputs.forEach((input, idx) => {
                console.log(`Input ${idx}: name="${input.name}", value="${input.value}"`);
            });
        });
    }
});
</script>
@endpush
@endsection
