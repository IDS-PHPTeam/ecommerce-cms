@extends('layouts.app')

@section('title', 'Add Attribute')

@section('content')
<div class="card">
    <h2 style="margin-bottom: 1.5rem; font-size: 1.875rem; font-weight: 700;">Add New Attribute</h2>

    <form method="POST" action="{{ route('attributes.store') }}">
        @csrf

        <div class="form-group">
            <label for="name" class="form-label">Name <span style="color: #ef4444;">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required class="form-input" placeholder="Attribute name">
            @error('name')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" rows="3" class="form-input" placeholder="Attribute description">{{ old('description') }}</textarea>
            @error('description')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Values</label>
            <div id="valuesContainer" style="margin-bottom: 0.5rem;">
                <div class="value-item" style="display: flex; gap: 0.5rem; margin-bottom: 0.5rem; align-items: center; padding: 0.5rem; background-color: #f9fafb; border-radius: 0.375rem; cursor: move;">
                    <span class="drag-handle" style="cursor: grab; color: #6b7280; display: flex; align-items: center; padding: 0.25rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                        </svg>
                    </span>
                    <input type="text" name="values[]" class="form-input" placeholder="Enter value" style="flex: 1;">
                    <button type="button" class="remove-value-btn" style="background-color: #ef4444; color: white; border: none; padding: 0.5rem 1rem; border-radius: 0.25rem; cursor: pointer; display: none;">Remove</button>
                </div>
            </div>
            <button type="button" id="addValueBtn" class="btn" style="margin-top: 0.5rem; background-color: #6b7280; color: white;">+ Add Value</button>
            <small style="color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem; display: block;">Add possible values for this attribute. Drag and drop to reorder.</small>
            @error('values')
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

        <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('attributes.index') }}" class="btn" style="background-color: #6b7280; color: white;">Cancel</a>
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
        const valueItem = document.createElement('div');
        valueItem.className = 'value-item';
        valueItem.style.cssText = 'display: flex; gap: 0.5rem; margin-bottom: 0.5rem; align-items: center; padding: 0.5rem; background-color: #f9fafb; border-radius: 0.375rem; cursor: move;';
        valueItem.innerHTML = `
            <span class="drag-handle" style="cursor: grab; color: #6b7280; display: flex; align-items: center; padding: 0.25rem;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                </svg>
            </span>
            <input type="text" name="values[]" class="form-input" placeholder="Enter value" style="flex: 1;">
            <button type="button" class="remove-value-btn" style="background-color: #ef4444; color: white; border: none; padding: 0.5rem 1rem; border-radius: 0.25rem; cursor: pointer;">Remove</button>
        `;
        valuesContainer.appendChild(valueItem);

        // Show remove button on first item if there are multiple items
        if (valuesContainer.children.length > 1) {
            valuesContainer.querySelectorAll('.remove-value-btn').forEach(btn => {
                btn.style.display = 'block';
            });
        }

        // Attach remove event
        valueItem.querySelector('.remove-value-btn').addEventListener('click', function() {
            valueItem.remove();
            // Hide remove buttons if only one item remains
            if (valuesContainer.children.length === 1) {
                valuesContainer.querySelector('.remove-value-btn').style.display = 'none';
            }
        });
    });

    // Handle remove buttons
    document.querySelectorAll('.remove-value-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            this.closest('.value-item').remove();
            if (valuesContainer.children.length === 1) {
                valuesContainer.querySelector('.remove-value-btn').style.display = 'none';
            }
        });
    });
});
</script>
<style>
.sortable-ghost {
    opacity: 0.4;
    background-color: #e5e7eb;
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

