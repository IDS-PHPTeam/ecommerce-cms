@extends('layouts.app')

@section('title', __('cms.edit_attribute'))

@section('content')
<div class="card">
    <h2 class="section-heading-lg mb-6">{{ __('cms.edit_attribute') }}</h2>

    <form method="POST" action="{{ route('attributes.update', $attribute) }}" id="attributeForm">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label">{{ __('cms.name') }} <span class="text-red-500">*</span></label>
            <div class="grid grid-2 gap-4">
                <div>
                    <input type="text" id="name_en" name="name_en" value="{{ old('name_en', $attribute->name_en ?: $attribute->name) }}" class="form-input" placeholder="{{ __('cms.attribute_name_english') }}">
                    @error('name_en')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <input type="text" id="name_ar" name="name_ar" value="{{ old('name_ar', $attribute->name_ar ?: $attribute->name) }}" class="form-input" placeholder="{{ __('cms.attribute_name_arabic') }}" dir="rtl">
                    @error('name_ar')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <input type="hidden" id="name" name="name" value="{{ old('name', $attribute->name) }}" required>
            @error('name')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">{{ __('cms.description') }}</label>
            <div class="grid grid-2 gap-4">
                <div>
                    <textarea id="description_en" name="description_en" rows="3" class="form-input" placeholder="{{ __('cms.attribute_description_english') }}">{{ old('description_en', $attribute->description_en ?: $attribute->description) }}</textarea>
                    @error('description_en')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <textarea id="description_ar" name="description_ar" rows="3" class="form-input" placeholder="{{ __('cms.attribute_description_arabic') }}" dir="rtl">{{ old('description_ar', $attribute->description_ar ?: $attribute->description) }}</textarea>
                    @error('description_ar')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <input type="hidden" id="description" name="description" value="{{ old('description', $attribute->description) }}">
            @error('description')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">{{ __('cms.values') }}</label>
            <div id="valuesContainer" class="mb-2">
                @foreach($attribute->values->sortBy('sort_order') as $index => $value)
                    <div class="value-item" data-value-id="{{ $value->id }}">
                        <div class="flex gap-2 items-center mb-2">
                            <span class="drag-handle cursor-grab text-tertiary flex items-center p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                                </svg>
                            </span>
                            <span class="value-number-label text-sm text-tertiary font-medium">{{ __('cms.value') }} {{ $index + 1 }}</span>
                        </div>
                        <input type="hidden" name="value_ids[]" value="{{ $value->id }}">
                        <div class="grid grid-2 gap-4 mb-2">
                            <div>
                                <input type="text" name="values_en[]" value="{{ old("values_en.{$index}", $value->value_en ?: $value->value) }}" class="form-input value-en-input" placeholder="{{ __('cms.enter_value_english') }}">
                            </div>
                            <div>
                                <input type="text" name="values_ar[]" value="{{ old("values_ar.{$index}", $value->value_ar ?: $value->value) }}" class="form-input value-ar-input" placeholder="{{ __('cms.enter_value_arabic') }}" dir="rtl">
                            </div>
                        </div>
                        <input type="hidden" name="values[]" class="value-hidden-input" value="{{ old("values.{$index}", $value->value) }}">
                        <button type="button" class="remove-value-btn btn btn-danger-sm {{ $attribute->values->count() == 1 ? 'd-none' : '' }}">{{ __('cms.remove') }}</button>
                    </div>
                @endforeach
                @if($attribute->values->isEmpty())
                    <div class="value-item">
                        <div class="flex gap-2 items-center mb-2">
                            <span class="drag-handle cursor-grab text-tertiary flex items-center p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                                </svg>
                            </span>
                            <span class="value-number-label text-sm text-tertiary font-medium">{{ __('cms.value') }} 1</span>
                        </div>
                        <input type="hidden" name="value_ids[]" value="">
                        <div class="grid grid-2 gap-4 mb-2">
                            <div>
                                <input type="text" name="values_en[]" class="form-input value-en-input" placeholder="{{ __('cms.enter_value_english') }}">
                            </div>
                            <div>
                                <input type="text" name="values_ar[]" class="form-input value-ar-input" placeholder="{{ __('cms.enter_value_arabic') }}" dir="rtl">
                            </div>
                        </div>
                        <input type="hidden" name="values[]" class="value-hidden-input">
                        <button type="button" class="remove-value-btn btn btn-danger-sm d-none">{{ __('cms.remove') }}</button>
                    </div>
                @endif
            </div>
            <button type="button" id="addValueBtn" class="btn bg-gray-600 text-white mt-2">{{ __('cms.add_value') }}</button>
            <small class="text-tertiary text-sm mt-1 d-block">{{ __('cms.add_attribute_values_description') }}</small>
            @error('values')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="status" class="form-label">{{ __('cms.status') }} <span class="text-red-500">*</span></label>
            <select id="status" name="status" required class="form-input">
                <option value="active" {{ old('status', $attribute->status) == 'active' ? 'selected' : '' }}>{{ __('cms.active') }}</option>
                <option value="inactive" {{ old('status', $attribute->status) == 'inactive' ? 'selected' : '' }}>{{ __('cms.inactive') }}</option>
            </select>
            @error('status')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex gap-4 mt-6">
            <button type="submit" class="btn btn-primary">{{ __('cms.save') }}</button>
            <a href="{{ route('attributes.index') }}" class="btn bg-gray-600 text-white">{{ __('cms.cancel') }}</a>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const addValueBtn = document.getElementById('addValueBtn');
    const valuesContainer = document.getElementById('valuesContainer');
    let valueIndex = {{ $attribute->values->count() }};

    // Initialize SortableJS
    const sortable = Sortable.create(valuesContainer, {
        handle: '.drag-handle',
        animation: 150,
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        dragClass: 'sortable-drag',
        onEnd: function(evt) {
            // Update cursor after drag
            document.querySelectorAll('.drag-handle').forEach(handle => {
                handle.style.cursor = 'grab';
            });
        },
        onStart: function(evt) {
            // Change cursor during drag
            document.querySelectorAll('.drag-handle').forEach(handle => {
                handle.style.cursor = 'grabbing';
            });
        }
    });

    addValueBtn.addEventListener('click', function() {
        const valueIndex = valuesContainer.children.length + 1;
        const valueItem = document.createElement('div');
        valueItem.className = 'value-item';
        // valueItem styles are now handled by CSS class
        valueItem.innerHTML = `
            <div class="flex gap-2 items-center mb-2">
                <span class="drag-handle cursor-grab text-tertiary flex items-center p-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                    </svg>
                </span>
                <span class="value-number-label text-sm text-tertiary font-medium">{{ __('cms.value') }} ${valueIndex}</span>
            </div>
            <input type="hidden" name="value_ids[]" value="">
            <div class="grid grid-2 gap-4 mb-2">
                <div>
                    <input type="text" name="values_en[]" class="form-input value-en-input" placeholder="{{ __('cms.enter_value_english') }}">
                </div>
                <div>
                    <input type="text" name="values_ar[]" class="form-input value-ar-input" placeholder="{{ __('cms.enter_value_arabic') }}" dir="rtl">
                </div>
            </div>
            <input type="hidden" name="values[]" class="value-hidden-input">
            <button type="button" class="remove-value-btn btn btn-danger-sm d-none">{{ __('cms.remove') }}</button>
        `;
        valuesContainer.appendChild(valueItem);

        // Sync bilingual fields to hidden input
        const enInput = valueItem.querySelector('.value-en-input');
        const arInput = valueItem.querySelector('.value-ar-input');
        const hiddenInput = valueItem.querySelector('.value-hidden-input');
        
        function updateHiddenValue() {
            const currentLocale = document.documentElement.lang || 'en';
            if (currentLocale === 'ar' && arInput.value) {
                hiddenInput.value = arInput.value;
            } else if (enInput.value) {
                hiddenInput.value = enInput.value;
            } else if (arInput.value) {
                hiddenInput.value = arInput.value;
            }
        }
        
        enInput.addEventListener('input', updateHiddenValue);
        arInput.addEventListener('input', updateHiddenValue);

        // Show remove button on all items if there are multiple items
        if (valuesContainer.children.length > 1) {
            valuesContainer.querySelectorAll('.remove-value-btn').forEach(btn => {
                btn.style.display = 'block';
            });
        }

        // Attach remove event
        valueItem.querySelector('.remove-value-btn').addEventListener('click', function() {
            valueItem.remove();
            updateValueNumbers();
            // Hide remove buttons if only one item remains
            if (valuesContainer.children.length === 1) {
                valuesContainer.querySelector('.remove-value-btn').style.display = 'none';
            }
        });
    });
    
    // Function to update value numbers
    function updateValueNumbers() {
        const valueItems = valuesContainer.querySelectorAll('.value-item');
        valueItems.forEach((item, index) => {
            const valueLabel = item.querySelector('.value-number-label');
            if (valueLabel) {
                // Extract the base text (either "Value" or translated equivalent)
                const baseText = valueLabel.textContent.split(/\d+/)[0].trim();
                valueLabel.textContent = baseText + ' ' + (index + 1);
            }
        });
    }

    // Handle remove buttons
    document.querySelectorAll('.remove-value-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            this.closest('.value-item').remove();
            updateValueNumbers();
            if (valuesContainer.children.length === 1) {
                valuesContainer.querySelector('.remove-value-btn').style.display = 'none';
            }
        });
    });
    
    // Sync bilingual fields for initial value items
    document.querySelectorAll('.value-item').forEach(item => {
        const enInput = item.querySelector('input[name="values_en[]"]');
        const arInput = item.querySelector('input[name="values_ar[]"]');
        const hiddenInput = item.querySelector('.value-hidden-input');
        
        if (enInput && arInput && hiddenInput) {
            function updateHiddenValue() {
                const currentLocale = document.documentElement.lang || 'en';
                if (currentLocale === 'ar' && arInput.value) {
                    hiddenInput.value = arInput.value;
                } else if (enInput.value) {
                    hiddenInput.value = enInput.value;
                } else if (arInput.value) {
                    hiddenInput.value = arInput.value;
                }
            }
            
            // Initialize hidden value on page load
            updateHiddenValue();
            
            enInput.addEventListener('input', updateHiddenValue);
            arInput.addEventListener('input', updateHiddenValue);
        }
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
.sortable-ghost {
    opacity: 0.4;
    background-color: #e5e7eb;
}

[data-theme="dark"] .sortable-ghost {
    background-color: var(--bg-tertiary);
}
.sortable-chosen {
    cursor: grabbing !important;
}
.sortable-drag {
    opacity: 0.8;
}
</style>
@endpush
@endsection

