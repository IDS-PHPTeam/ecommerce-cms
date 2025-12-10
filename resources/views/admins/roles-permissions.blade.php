@extends('layouts.app')

@section('title', __('cms.roles_and_permissions'))

@section('content')
<div class="card">
    <h2 style="margin-bottom: 1.5rem; font-size: 1.875rem; font-weight: 700;">{{ __('cms.roles_and_permissions') }}</h2>

    @if(session('success'))
        <div style="background-color: #d1fae5; color: #065f46; padding: 0.75rem 1rem; border-radius: 0.375rem; margin-bottom: 1.5rem; border: 1px solid #6ee7b7;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tabs -->
    <div style="border-bottom: 2px solid #e5e7eb; margin-bottom: 2rem;">
        <div style="display: flex; gap: 1rem;">
            <button onclick="showTab('roles')" id="roles-tab" class="tab-button active" style="padding: 0.75rem 1.5rem; background: none; border: none; border-bottom: 3px solid var(--primary-blue); color: var(--primary-blue); font-weight: 600; cursor: pointer; font-size: 1rem;">
                {{ __('cms.roles') }}
            </button>
            <button onclick="showTab('permissions')" id="permissions-tab" class="tab-button" style="padding: 0.75rem 1.5rem; background: none; border: none; border-bottom: 3px solid transparent; color: #6b7280; font-weight: 600; cursor: pointer; font-size: 1rem;">
                {{ __('cms.permissions') }}
            </button>
            <button onclick="showTab('assign')" id="assign-tab" class="tab-button" style="padding: 0.75rem 1.5rem; background: none; border: none; border-bottom: 3px solid transparent; color: #6b7280; font-weight: 600; cursor: pointer; font-size: 1rem;">
                {{ __('cms.assign_permissions') }}
            </button>
        </div>
    </div>

    <!-- Roles Tab -->
    <div id="roles-content" class="tab-content" style="display: block;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.5rem; font-weight: 600;">{{ __('cms.roles') }}</h3>
            <button onclick="showRoleForm()" class="btn btn-primary">{{ __('cms.add_new') }}</button>
        </div>

        <!-- Add/Edit Role Form -->
        <div id="role-form-container" class="form-container" style="display: none; margin-bottom: 2rem; padding: 1.5rem; border-radius: 0.5rem;">
            <form id="role-form" method="POST" action="{{ route('roles-permissions.storeRole') }}">
                @csrf
                <input type="hidden" name="_method" id="role-method" value="POST">
                <input type="hidden" name="role_id" id="role-id">
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                    <div class="form-group">
                        <label for="role-name" class="form-label">{{ __('cms.role_name') }} <span style="color: #ef4444;">*</span></label>
                        <input type="text" id="role-name" name="name" required class="form-input" placeholder="{{ __('cms.role_name_placeholder') }}">
                    </div>
                    <div class="form-group">
                        <label for="role-slug" class="form-label">{{ __('cms.slug') }} <span style="color: #ef4444;">*</span></label>
                        <input type="text" id="role-slug" name="slug" required class="form-input" placeholder="{{ __('cms.slug_placeholder') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="role-description" class="form-label">{{ __('cms.description') }}</label>
                    <textarea id="role-description" name="description" rows="3" class="form-input" placeholder="{{ __('cms.role_description_placeholder') }}"></textarea>
                </div>
                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">{{ __('cms.save') }}</button>
                    <button type="button" onclick="hideRoleForm()" class="btn" style="background-color: #6b7280; color: white;">{{ __('cms.cancel') }}</button>
                </div>
            </form>
        </div>

        <!-- Roles Table -->
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr class="table-header-row">
                        <th style="padding: 0.75rem; text-align: left; font-weight: 600;">{{ __('cms.name') }}</th>
                        <th style="padding: 0.75rem; text-align: left; font-weight: 600;">{{ __('cms.slug') }}</th>
                        <th style="padding: 0.75rem; text-align: left; font-weight: 600;">{{ __('cms.description') }}</th>
                        <th style="padding: 0.75rem; text-align: left; font-weight: 600;">{{ __('cms.permissions') }}</th>
                        <th style="padding: 0.75rem; text-align: left; font-weight: 600;">{{ __('cms.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 0.75rem; font-weight: 500;">{{ $role->name }}</td>
                        <td style="padding: 0.75rem; color: #6b7280; font-family: monospace;">{{ $role->slug }}</td>
                        <td style="padding: 0.75rem; color: #6b7280;">{{ $role->description ?? 'N/A' }}</td>
                        <td style="padding: 0.75rem;">
                            <span style="padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.875rem; background-color: #dbeafe; color: #1e40af;">
                                {{ __('cms.permissions_count', ['count' => $role->permissions->count()]) }}
                            </span>
                        </td>
                        <td style="padding: 0.75rem;">
                            <div style="display: flex; gap: 0.5rem;">
                                <button onclick="editRole({{ $role->id }}, '{{ addslashes($role->name) }}', '{{ addslashes($role->slug) }}', '{{ addslashes($role->description ?? '') }}')" class="action-btn action-btn-edit" title="{{ __('cms.edit') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <form action="{{ route('roles-permissions.destroyRole', $role) }}" method="POST" style="display: inline;" onsubmit="return confirm('{{ __('cms.confirm_delete_role') }}');">
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
                        <td colspan="5" style="padding: 2rem; text-align: center; color: #6b7280;">{{ __('cms.no_roles_found') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Permissions Tab -->
    <div id="permissions-content" class="tab-content" style="display: none;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.5rem; font-weight: 600;">{{ __('cms.permissions') }}</h3>
            <button onclick="showPermissionForm()" class="btn btn-primary">{{ __('cms.add_new') }}</button>
        </div>

        <!-- Add/Edit Permission Form -->
        <div id="permission-form-container" class="form-container" style="display: none; margin-bottom: 2rem; padding: 1.5rem; border-radius: 0.5rem;">
            <form id="permission-form" method="POST" action="{{ route('roles-permissions.storePermission') }}">
                @csrf
                <input type="hidden" name="_method" id="permission-method" value="POST">
                <input type="hidden" name="permission_id" id="permission-id">
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                    <div class="form-group">
                        <label for="permission-name" class="form-label">{{ __('cms.permission_name') }} <span style="color: #ef4444;">*</span></label>
                        <input type="text" id="permission-name" name="name" required class="form-input" placeholder="{{ __('cms.permission_name_placeholder') }}">
                    </div>
                    <div class="form-group">
                        <label for="permission-slug" class="form-label">{{ __('cms.slug') }} <span style="color: #ef4444;">*</span></label>
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
                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">{{ __('cms.save') }}</button>
                    <button type="button" onclick="hidePermissionForm()" class="btn" style="background-color: #6b7280; color: white;">{{ __('cms.cancel') }}</button>
                </div>
            </form>
        </div>

        <!-- Permissions by Group -->
        @foreach($permissions as $group => $groupPermissions)
        <div style="margin-bottom: 2rem;">
            <h4 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem; color: #374151;">{{ $group ?? __('cms.other') }}</h4>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr class="table-header-row">
                            <th style="padding: 0.75rem; text-align: left; font-weight: 600;">{{ __('cms.name') }}</th>
                            <th style="padding: 0.75rem; text-align: left; font-weight: 600;">{{ __('cms.slug') }}</th>
                            <th style="padding: 0.75rem; text-align: left; font-weight: 600;">{{ __('cms.description') }}</th>
                            <th style="padding: 0.75rem; text-align: left; font-weight: 600;">{{ __('cms.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($groupPermissions as $permission)
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 0.75rem; font-weight: 500;">{{ __('cms.permission_' . str_replace(['.', '-'], ['_', '_'], $permission->slug)) ?: $permission->name }}</td>
                            <td style="padding: 0.75rem; color: #6b7280; font-family: monospace;">{{ $permission->slug }}</td>
                            <td style="padding: 0.75rem; color: #6b7280;">{{ $permission->description ?? 'N/A' }}</td>
                            <td style="padding: 0.75rem;">
                                <div style="display: flex; gap: 0.5rem;">
                                    <button onclick="editPermission({{ $permission->id }}, '{{ $permission->name }}', '{{ $permission->slug }}', '{{ $permission->description ?? '' }}', '{{ $permission->group ?? '' }}')" class="action-btn action-btn-edit" title="{{ __('cms.edit') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <form action="{{ route('roles-permissions.destroyPermission', $permission) }}" method="POST" style="display: inline;" onsubmit="return confirm('{{ __('cms.confirm_delete_permission') }}');">
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
    <div id="assign-content" class="tab-content" style="display: none;">
        <h3 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 1.5rem;">{{ __('cms.assign_permissions_to_roles') }}</h3>
        
        @foreach($roles as $role)
        <div class="role-permissions-card" style="margin-bottom: 2rem; padding: 1.5rem; border-radius: 0.5rem;">
            <form method="POST" action="{{ route('roles-permissions.updateRolePermissions', $role) }}">
                @csrf
                @method('PUT')
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h4 class="role-name-heading" style="font-size: 1.125rem; font-weight: 600;">{{ $role->name }}</h4>
                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                        <button type="button" onclick="toggleAllPermissions({{ $role->id }})" class="btn" style="background-color: #6b7280; color: white; padding: 0.5rem 1rem; font-size: 0.875rem;" id="toggle-btn-{{ $role->id }}">
                            {{ __('cms.toggle_all') }}
                        </button>
                        <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">{{ __('cms.save') }}</button>
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 0.75rem;" id="permissions-container-{{ $role->id }}">
                    @foreach($permissions->flatten() as $permission)
                    <label class="permission-label" style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem; border-radius: 0.25rem; cursor: pointer;">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" {{ $role->permissions->contains($permission->id) ? 'checked' : '' }} class="permission-checkbox-{{ $role->id }}">
                        <span style="font-size: 0.875rem;">{{ __('cms.permission_' . str_replace(['.', '-'], ['_', '_'], $permission->slug)) ?: $permission->name }}</span>
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
        content.style.display = 'none';
    });
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.style.borderBottomColor = 'transparent';
        btn.style.color = '#6b7280';
    });
    
    // Show selected tab
    document.getElementById(tab + '-content').style.display = 'block';
    const tabBtn = document.getElementById(tab + '-tab');
    tabBtn.style.borderBottomColor = 'var(--primary-blue)';
    tabBtn.style.color = 'var(--primary-blue)';
}

function showRoleForm() {
    document.getElementById('role-form-container').style.display = 'block';
    document.getElementById('role-form').action = '{{ route("roles-permissions.storeRole") }}';
    document.getElementById('role-method').value = 'POST';
    document.getElementById('role-id').value = '';
    document.getElementById('role-name').value = '';
    document.getElementById('role-slug').value = '';
    document.getElementById('role-description').value = '';
}

function hideRoleForm() {
    document.getElementById('role-form-container').style.display = 'none';
}

function editRole(id, name, slug, description) {
    document.getElementById('role-form-container').style.display = 'block';
    document.getElementById('role-form').action = '{{ route("roles-permissions.updateRole", ":id") }}'.replace(':id', id);
    document.getElementById('role-method').value = 'PUT';
    document.getElementById('role-id').value = id;
    document.getElementById('role-name').value = name || '';
    document.getElementById('role-slug').value = slug || '';
    document.getElementById('role-description').value = description || '';
}

function showPermissionForm() {
    document.getElementById('permission-form-container').style.display = 'block';
    document.getElementById('permission-form').action = '{{ route("roles-permissions.storePermission") }}';
    document.getElementById('permission-method').value = 'POST';
    document.getElementById('permission-id').value = '';
    document.getElementById('permission-name').value = '';
    document.getElementById('permission-slug').value = '';
    document.getElementById('permission-group').value = '';
    document.getElementById('permission-description').value = '';
}

function hidePermissionForm() {
    document.getElementById('permission-form-container').style.display = 'none';
}

function editPermission(id, name, slug, description, group) {
    document.getElementById('permission-form-container').style.display = 'block';
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

