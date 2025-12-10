@extends('layouts.app')

@section('title', __('cms.add_attribute'))

@section('content')
<div class="card">
    <h2 style="margin-bottom: 1.5rem; font-size: 1.875rem; font-weight: 700;">{{ __('cms.add_new_attribute') }}</h2>

    <form method="POST" action="{{ route('attributes.store') }}" id="attributeForm">
        @csrf

        <div class="form-group">
            <label class="form-label">{{ __('cms.name') }} <span style="color: #ef4444;">*</span></label>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label for="name_en" style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem; display: block;">{{ __('cms.english') }}</label>
                    <input type="text" id="name_en" name="name_en" value="{{ old('name_en') }}" class="form-input" placeholder="{{ __('cms.attribute_name_english') }}">
                    @error('name_en')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="name_ar" style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem; display: block;">{{ __('cms.arabic') }}</label>
                    <input type="text" id="name_ar" name="name_ar" value="{{ old('name_ar') }}" class="form-input" placeholder="{{ __('cms.attribute_name_arabic') }}" dir="rtl">
                    @error('name_ar')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <input type="hidden" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">{{ __('cms.description') }}</label>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label for="description_en" style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem; display: block;">{{ __('cms.english') }}</label>
                    <textarea id="description_en" name="description_en" rows="3" class="form-input" placeholder="{{ __('cms.attribute_description_english') }}">{{ old('description_en') }}</textarea>
                    @error('description_en')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="description_ar" style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem; display: block;">{{ __('cms.arabic') }}</label>
                    <textarea id="description_ar" name="description_ar" rows="3" class="form-input" placeholder="{{ __('cms.attribute_description_arabic') }}" dir="rtl">{{ old('description_ar') }}</textarea>
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

        <div class="form-group">
            <label class="form-label">{{ __('cms.values') }}</label>
            <div id="valuesContainer" style="margin-bottom: 0.5rem;">
                <div class="value-item">
                    <div style="display: flex; gap: 0.5rem; align-items: center; margin-bottom: 0.5rem;">
                        <span class="drag-handle" style="cursor: grab; color: #6b7280; display: flex; align-items: center; padding: 0.25rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                            </svg>
                        </span>
                        <span class="value-number-label" style="font-size: 0.875rem; color: #6b7280; font-weight: 500;">{{ __('cms.value') }} 1</span>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <label style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem; display: block;">{{ __('cms.english') }}</label>
                            <input type="text" name="values_en[]" class="form-input" placeholder="{{ __('cms.enter_value_english') }}">
                        </div>
                        <div>
                            <label style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem; display: block;">{{ __('cms.arabic') }}</label>
                            <input type="text" name="values_ar[]" class="form-input" placeholder="{{ __('cms.enter_value_arabic') }}" dir="rtl">
                        </div>
                    </div>
                    <input type="hidden" name="values[]" class="value-hidden-input">
                    <button type="button" class="remove-value-btn" style="background-color: #ef4444; color: white; border: none; padding: 0.5rem 1rem; border-radius: 0.25rem; cursor: pointer; margin-top: 0.5rem; display: none;">{{ __('cms.remove') }}</button>
                </div>
            </div>
            <button type="button" id="addValueBtn" class="btn" style="margin-top: 0.5rem; background-color: #6b7280; color: white;">{{ __('cms.add_value') }}</button>
            <small style="color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ __('cms.add_attribute_values_description') }}</small>
            @error('values')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="status" class="form-label">{{ __('cms.status') }} <span style="color: #ef4444;">*</span></label>
            <select id="status" name="status" required class="form-input">
                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>{{ __('cms.active') }}</option>
                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>{{ __('cms.inactive') }}</option>
            </select>
            @error('status')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
            <button type="submit" class="btn btn-primary">{{ __('cms.save') }}</button>
            <a href="{{ route('attributes.index') }}" class="btn" style="background-color: #6b7280; color: white;">{{ __('cms.cancel') }}</a>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const addValueBtn = document.getElementById('addValueBtn');
    const valuesContainer = document.getElementById('valuesContainer');

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
            <div style="display: flex; gap: 0.5rem; align-items: center; margin-bottom: 0.5rem;">
                <span class="drag-handle" style="cursor: grab; color: #6b7280; display: flex; align-items: center; padding: 0.25rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                    </svg>
                </span>
                <span class="value-number-label" style="font-size: 0.875rem; color: #6b7280; font-weight: 500;">{{ __('cms.value') }} ${valueIndex}</span>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem; display: block;">{{ __('cms.english') }}</label>
                    <input type="text" name="values_en[]" class="form-input value-en-input" placeholder="{{ __('cms.enter_value_english') }}">
                </div>
                <div>
                    <label style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem; display: block;">{{ __('cms.arabic') }}</label>
                    <input type="text" name="values_ar[]" class="form-input value-ar-input" placeholder="{{ __('cms.enter_value_arabic') }}" dir="rtl">
                </div>
            </div>
            <input type="hidden" name="values[]" class="value-hidden-input">
            <button type="button" class="remove-value-btn" style="background-color: #ef4444; color: white; border: none; padding: 0.5rem 1rem; border-radius: 0.25rem; cursor: pointer; margin-top: 0.5rem; display: none;">{{ __('cms.remove') }}</button>
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

        // Show remove button on first item if there are multiple items
        if (valuesContainer.children.length > 1) {
            valuesContainer.querySelectorAll('.remove-value-btn').forEach(btn => {
                btn.style.display = 'block';
            });
        }

        // Attach remove event
        valueItem.querySelector('.remove-value-btn').addEventListener('click', function() {
            valueItem.remove();
            // Update value numbers after removal
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
            
            enInput.addEventListener('input', updateHiddenValue);
            arInput.addEventListener('input', updateHiddenValue);
        }
    });
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

