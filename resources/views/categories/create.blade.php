@extends('layouts.app')

@section('title', __('cms.add_category'))

@section('content')
<div class="card">
    <h2 class="section-heading-lg mb-6">{{ __('cms.add_new_category') }}</h2>

    <form method="POST" action="{{ route('categories.store') }}" id="categoryForm">
        @csrf

        <div class="form-group">
            <label class="form-label">{{ __('cms.name') }}</label>
            <div class="grid grid-2 gap-4">
                <div>
                    <input type="text" id="name_en" name="name_en" value="{{ old('name_en') }}" class="form-input" placeholder="{{ __('cms.category_name_english') }}">
                    @error('name_en')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <input type="text" id="name_ar" name="name_ar" value="{{ old('name_ar') }}" class="form-input" placeholder="{{ __('cms.category_name_arabic') }}" dir="rtl">
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
            <div class="grid grid-2 gap-4">
                <div>
                    <textarea id="description_en" name="description_en" rows="4" class="form-input" placeholder="{{ __('cms.category_description_english') }}">{{ old('description_en') }}</textarea>
                    @error('description_en')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <textarea id="description_ar" name="description_ar" rows="4" class="form-input" placeholder="{{ __('cms.category_description_arabic') }}" dir="rtl">{{ old('description_ar') }}</textarea>
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
            <label for="status" class="form-label">{{ __('cms.status') }}</label>
            <select id="status" name="status" required class="form-input">
                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>{{ __('cms.active') }}</option>
                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>{{ __('cms.inactive') }}</option>
            </select>
            @error('status')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex gap-4 mt-6">
            <button type="submit" class="btn btn-primary">{{ __('cms.save') }}</button>
            <a href="{{ route('categories.index') }}" class="btn bg-gray-600 text-white">{{ __('cms.cancel') }}</a>
        </div>
    </form>
</div>

@push('scripts')
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
});
</script>
@endpush
@endsection

