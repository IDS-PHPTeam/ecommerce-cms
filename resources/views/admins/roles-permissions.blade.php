@extends('layouts.app')

@section('title', __('cms.roles_and_permissions'))

@section('content')
<div class="card">
    <h2 class="section-heading-lg mb-6">{{ __('cms.roles_and_permissions') }}</h2>

    @if(session('success'))
        <div class="alert-success mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tabs -->
    <div class="border-b-2 border-gray-200 mb-8">
        <div class="flex gap-4">
            <button onclick="showTab('roles')" id="roles-tab" class="tab-button active p-3 bg-none border-0 border-b-3 border-primary-blue text-primary-blue font-semibold cursor-pointer text-base">
                {{ __('cms.roles') }}
            </button>
            <button onclick="showTab('permissions')" id="permissions-tab" class="tab-button p-3 bg-none border-0 border-b-3 border-b-transparent text-tertiary font-semibold cursor-pointer text-base">
                {{ __('cms.permissions') }}
            </button>
            <button onclick="showTab('assign')" id="assign-tab" class="tab-button p-3 bg-none border-0 border-b-3 border-b-transparent text-tertiary font-semibold cursor-pointer text-base">
                {{ __('cms.assign_permissions') }}
            </button>
        </div>
    </div>

    <!-- Roles Tab -->
    <div id="roles-content" class="tab-content active">
        <div class="flex justify-between items-center mb-6">
            <h3 class="section-heading">{{ __('cms.roles') }}</h3>
            <button onclick="showRoleForm()" class="btn btn-primary">{{ __('cms.add_new') }}</button>
        </div>

        <!-- Add/Edit Role Form -->
        <div id="role-form-container" class="form-container d-none mb-8 p-6 rounded-md">
            <form id="role-form" method="POST" action="{{ route('roles-permissions.storeRole') }}">
                @csrf
                <input type="hidden" name="_method" id="role-method" value="POST">
                <input type="hidden" name="role_id" id="role-id">
                
                <div class="grid grid-auto-200 gap-4 mb-4">
                    <div class="form-group">
                        <label for="role-name" class="form-label">{{ __('cms.role_name') }} <span class="text-red-500">*</span></label>
                        <input type="text" id="role-name" name="name" required class="form-input" placeholder="{{ __('cms.role_name_placeholder') }}">
                    </div>
                    <div class="form-group">
                        <label for="role-slug" class="form-label">{{ __('cms.slug') }} <span class="text-red-500">*</span></label>
                        <input type="text" id="role-slug" name="slug" required class="form-input" placeholder="{{ __('cms.slug_placeholder') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="role-description" class="form-label">{{ __('cms.description') }}</label>
                    <textarea id="role-description" name="description" rows="3" class="form-input" placeholder="{{ __('cms.role_description_placeholder') }}"></textarea>
                </div>
                <div class="flex gap-4">
                    <button type="submit" class="btn btn-primary">{{ __('cms.save') }}</button>
                    <button type="button" onclick="hideRoleForm()" class="btn bg-gray-600 text-white">{{ __('cms.cancel') }}</button>
                </div>
            </form>
        </div>

        <!-- Roles Table -->
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="table-header-row">
                        <th class="table-cell-header">{{ __('cms.name') }}</th>
                        <th class="table-cell-header">{{ __('cms.slug') }}</th>
                        <th class="table-cell-header">{{ __('cms.description') }}</th>
                        <th class="table-cell-header">{{ __('cms.permissions') }}</th>
                        <th class="table-cell-header">{{ __('cms.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                    <tr class="table-row-border">
                        <td class="table-cell-padding font-medium">{{ $role->name }}</td>
                        <td class="table-cell-padding text-tertiary font-mono">{{ $role->slug }}</td>
                        <td class="table-cell-padding text-tertiary">{{ $role->description ?? 'N/A' }}</td>
                        <td class="table-cell-padding">
                            <span class="badge badge-blue">
                                {{ __('cms.permissions_count', ['count' => $role->permissions->count()]) }}
                            </span>
                        </td>
                        <td class="table-cell-padding">
                            <div class="flex gap-2">
                                <button onclick="editRole({{ $role->id }}, '{{ addslashes($role->name) }}', '{{ addslashes($role->slug) }}', '{{ addslashes($role->description ?? '') }}')" class="action-btn action-btn-edit" title="{{ __('cms.edit') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <form action="{{ route('roles-permissions.destroyRole', $role) }}" method="POST" class="d-inline" data-confirm="{{ __('cms.confirm_delete_role') }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn action-btn-delete" title="{{ __('cms.delete') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-tertiary">{{ __('cms.no_roles_found') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Permissions Tab -->
    <div id="permissions-content" class="tab-content d-none">
        <div class="flex justify-between items-center mb-6">
            <h3 class="section-heading">{{ __('cms.permissions') }}</h3>
            <button onclick="showPermissionForm()" class="btn btn-primary">{{ __('cms.add_new') }}</button>
        </div>

        <!-- Add/Edit Permission Form -->
        <div id="permission-form-container" class="form-container d-none mb-8 p-6 rounded-md">
            <form id="permission-form" method="POST" action="{{ route('roles-permissions.storePermission') }}">
                @csrf
                <input type="hidden" name="_method" id="permission-method" value="POST">
                <input type="hidden" name="permission_id" id="permission-id">
                
                <div class="grid grid-auto-200 gap-4 mb-4">
                    <div class="form-group">
                        <label for="permission-name" class="form-label">{{ __('cms.permission_name') }} <span class="text-red-500">*</span></label>
                        <input type="text" id="permission-name" name="name" required class="form-input" placeholder="{{ __('cms.permission_name_placeholder') }}">
                    </div>
                    <div class="form-group">
                        <label for="permission-slug" class="form-label">{{ __('cms.slug') }} <span class="text-red-500">*</span></label>
                        <input type="text" id="permission-slug" name="slug" required class="form-input" placeholder="{{ __('cms.slug_placeholder') }}">
                    </div>
                    <div class="form-group">
                        <label for="permission-group" class="form-label">{{ __('cms.group') }}</label>
                        <input type="text" id="permission-group" name="group" class="form-input" placeholder="{{ __('cms.group_placeholder') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="permission-description" class="form-label">{{ __('cms.description') }}</label>
                    <textarea id="permission-description" name="description" rows="3" class="form-input" placeholder="{{ __('cms.permission_description_placeholder') }}"></textarea>
                </div>
                <div class="flex gap-4">
                    <button type="submit" class="btn btn-primary">{{ __('cms.save') }}</button>
                    <button type="button" onclick="hidePermissionForm()" class="btn bg-gray-600 text-white">{{ __('cms.cancel') }}</button>
                </div>
            </form>
        </div>

        <!-- Permissions by Group -->
        @foreach($permissions as $group => $groupPermissions)
        <div class="mb-8">
            <h4 class="text-xl font-semibold mb-4 text-secondary">{{ $group ?? __('cms.other') }}</h4>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="table-header-row">
                            <th class="table-cell-header">{{ __('cms.name') }}</th>
                            <th class="table-cell-header">{{ __('cms.slug') }}</th>
                            <th class="table-cell-header">{{ __('cms.description') }}</th>
                            <th class="table-cell-header">{{ __('cms.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($groupPermissions as $permission)
                        <tr class="table-row-border">
                            <td class="table-cell-padding font-medium">{{ __('cms.permission_' . str_replace(['.', '-'], ['_', '_'], $permission->slug)) ?: $permission->name }}</td>
                            <td class="table-cell-padding text-tertiary font-mono">{{ $permission->slug }}</td>
                            <td class="table-cell-padding text-tertiary">{{ $permission->description ?? 'N/A' }}</td>
                            <td class="table-cell-padding">
                                <div class="flex gap-2">
                                    <button onclick="editPermission({{ $permission->id }}, '{{ $permission->name }}', '{{ $permission->slug }}', '{{ $permission->description ?? '' }}', '{{ $permission->group ?? '' }}')" class="action-btn action-btn-edit" title="{{ __('cms.edit') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <form action="{{ route('roles-permissions.destroyPermission', $permission) }}" method="POST" class="d-inline" data-confirm="{{ __('cms.confirm_delete_permission') }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn action-btn-delete" title="{{ __('cms.delete') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Assign Permissions Tab -->
    <div id="assign-content" class="tab-content d-none">
        <h3 class="section-heading mb-6">{{ __('cms.assign_permissions_to_roles') }}</h3>
        
        @foreach($roles as $role)
        <div class="role-permissions-card mb-8 p-6 rounded-md">
            <form method="POST" action="{{ route('roles-permissions.updateRolePermissions', $role) }}">
                @csrf
                @method('PUT')
                
                <div class="flex justify-between items-center mb-4">
                    <h4 class="role-name-heading text-lg font-semibold">{{ $role->name }}</h4>
                    <div class="flex gap-2 items-center">
                        <button type="button" onclick="toggleAllPermissions({{ $role->id }})" class="btn bg-gray-600 text-white px-4 py-2 text-sm" id="toggle-btn-{{ $role->id }}">
                            {{ __('cms.toggle_all') }}
                        </button>
                        <button type="submit" class="btn btn-primary px-4 py-2 text-sm">{{ __('cms.save') }}</button>
                    </div>
                </div>
                
                <div class="grid grid-auto-250 gap-3" id="permissions-container-{{ $role->id }}">
                    @foreach($permissions->flatten() as $permission)
                    <label class="permission-label flex items-center gap-2 p-2 rounded cursor-pointer">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" {{ $role->permissions->contains($permission->id) ? 'checked' : '' }} class="permission-checkbox-{{ $role->id }}">
                        <span class="text-sm">{{ __('cms.permission_' . str_replace(['.', '-'], ['_', '_'], $permission->slug)) ?: $permission->name }}</span>
                    </label>
                    @endforeach
                </div>
            </form>
        </div>
        @endforeach
    </div>
</div>

<script>
function showTab(tab) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('d-none');
        content.classList.remove('active');
    });
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('active');
        btn.classList.add('border-b-transparent', 'text-tertiary');
        btn.classList.remove('border-primary-blue', 'text-primary-blue');
    });
    
    // Show selected tab
    const tabContent = document.getElementById(tab + '-content');
    tabContent.classList.remove('d-none');
    tabContent.classList.add('active');
    const tabBtn = document.getElementById(tab + '-tab');
    tabBtn.classList.add('active', 'border-primary-blue', 'text-primary-blue');
    tabBtn.classList.remove('border-b-transparent', 'text-tertiary');
}

function showRoleForm() {
    document.getElementById('role-form-container').classList.remove('d-none');
    document.getElementById('role-form').action = '{{ route("roles-permissions.storeRole") }}';
    document.getElementById('role-method').value = 'POST';
    document.getElementById('role-id').value = '';
    document.getElementById('role-name').value = '';
    document.getElementById('role-slug').value = '';
    document.getElementById('role-description').value = '';
}

function hideRoleForm() {
    document.getElementById('role-form-container').classList.add('d-none');
}

function editRole(id, name, slug, description) {
    document.getElementById('role-form-container').classList.remove('d-none');
    document.getElementById('role-form').action = '{{ route("roles-permissions.updateRole", ":id") }}'.replace(':id', id);
    document.getElementById('role-method').value = 'PUT';
    document.getElementById('role-id').value = id;
    document.getElementById('role-name').value = name || '';
    document.getElementById('role-slug').value = slug || '';
    document.getElementById('role-description').value = description || '';
}

function showPermissionForm() {
    document.getElementById('permission-form-container').classList.remove('d-none');
    document.getElementById('permission-form').action = '{{ route("roles-permissions.storePermission") }}';
    document.getElementById('permission-method').value = 'POST';
    document.getElementById('permission-id').value = '';
    document.getElementById('permission-name').value = '';
    document.getElementById('permission-slug').value = '';
    document.getElementById('permission-group').value = '';
    document.getElementById('permission-description').value = '';
}

function hidePermissionForm() {
    document.getElementById('permission-form-container').classList.add('d-none');
}

function editPermission(id, name, slug, description, group) {
    document.getElementById('permission-form-container').classList.remove('d-none');
    document.getElementById('permission-form').action = '{{ route("roles-permissions.updatePermission", ":id") }}'.replace(':id', id);
    document.getElementById('permission-method').value = 'PUT';
    document.getElementById('permission-id').value = id;
    document.getElementById('permission-name').value = name;
    document.getElementById('permission-slug').value = slug;
    document.getElementById('permission-group').value = group;
    document.getElementById('permission-description').value = description;
}

function toggleAllPermissions(roleId) {
    const container = document.getElementById('permissions-container-' + roleId);
    const checkboxes = container.querySelectorAll('.permission-checkbox-' + roleId);
    const toggleBtn = document.getElementById('toggle-btn-' + roleId);
    
    if (checkboxes.length === 0) return;
    
    // Check if all are checked
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    
    // Determine new state: if all checked, uncheck all; otherwise check all
    const newState = !allChecked;
    
    // Toggle all checkboxes
    checkboxes.forEach(checkbox => {
        checkbox.checked = newState;
    });
    
    // Update button text based on new state
    updateToggleButtonText(roleId);
}

function updateToggleButtonText(roleId) {
    const container = document.getElementById('permissions-container-' + roleId);
    const checkboxes = container.querySelectorAll('.permission-checkbox-' + roleId);
    const toggleBtn = document.getElementById('toggle-btn-' + roleId);
    
    if (checkboxes.length === 0) return;
    
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    toggleBtn.textContent = allChecked ? '{{ __('cms.deselect_all') }}' : '{{ __('cms.select_all') }}';
}

// Initialize button text on page load and add event listeners
document.addEventListener('DOMContentLoaded', function() {
    @foreach($roles as $role)
    updateToggleButtonText({{ $role->id }});
    
    // Add event listeners to checkboxes to update button text
    const checkboxes{{ $role->id }} = document.querySelectorAll('.permission-checkbox-{{ $role->id }}');
    checkboxes{{ $role->id }}.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateToggleButtonText({{ $role->id }});
        });
    });
    @endforeach
});
</script>
@endsection

