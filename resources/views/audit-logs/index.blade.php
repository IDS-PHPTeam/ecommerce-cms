@extends('layouts.app')

@section('title', __('cms.audit_logs'))

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h2 class="section-heading-lg">{{ __('cms.audit_logs') }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('audit-logs.export', request()->query()) }}" class="btn btn-primary flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                {{ __('cms.export_csv') }}
            </a>
            <button type="button" onclick="deleteSelected()" id="delete-selected-btn" class="btn bg-red-500 text-white d-none">
                {{ __('cms.delete_selected') }}
            </button>
            <button type="button" onclick="deleteAllFiltered()" class="btn bg-red-600 text-white">
                {{ __('cms.delete_all_filtered') }}
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="card mb-6 p-4">
        <form method="GET" action="{{ route('audit-logs.index') }}" class="grid grid-auto-200 gap-4">
            <div>
                <label for="search" class="form-label text-sm mb-1">{{ __('cms.search') }}</label>
                <input 
                    type="text" 
                    id="search" 
                    name="search" 
                    value="{{ request('search') }}" 
                    class="form-input"
                    placeholder="{{ __('cms.search_description') }}"
                >
            </div>
            <div>
                <label for="action" class="form-label text-sm mb-1">{{ __('cms.action') }}</label>
                <select id="action" name="action" class="form-input">
                    <option value="">{{ __('cms.all_actions') }}</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>{{ __('cms.action_' . $action) ?: ucfirst($action) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="user_id" class="form-label text-sm mb-1">{{ __('cms.user') }}</label>
                <select id="user_id" name="user_id" class="form-input">
                    <option value="">{{ __('cms.all_users') }}</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->first_name && $user->last_name ? $user->first_name . ' ' . $user->last_name : $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="model_type" class="form-label text-sm mb-1">{{ __('cms.model_type') }}</label>
                <select id="model_type" name="model_type" class="form-input">
                    <option value="">{{ __('cms.all_models') }}</option>
                    @foreach($modelTypes as $modelType)
                        <option value="{{ $modelType }}" {{ request('model_type') == $modelType ? 'selected' : '' }}>
                            {{ __('cms.model_' . strtolower(class_basename($modelType))) ?: class_basename($modelType) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="date_from" class="form-label text-sm mb-1">{{ __('cms.from_date') }}</label>
                <input 
                    type="date" 
                    id="date_from" 
                    name="date_from" 
                    value="{{ request('date_from') }}" 
                    class="form-input"
                >
            </div>
            <div>
                <label for="date_to" class="form-label text-sm mb-1">{{ __('cms.to_date') }}</label>
                <input 
                    type="date" 
                    id="date_to" 
                    name="date_to" 
                    value="{{ request('date_to') }}" 
                    class="form-input"
                >
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn btn-primary">{{ __('cms.filter') }}</button>
                <a href="{{ route('audit-logs.index') }}" class="btn bg-gray-600 text-white">{{ __('cms.reset') }}</a>
            </div>
        </form>
    </div>

    <!-- Audit Logs Table -->
    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
                <tr class="table-header-row">
                    <th class="table-cell-header w-40px">
                        <input type="checkbox" id="select-all" onchange="toggleSelectAll(this)">
                    </th>
                    <th class="table-cell-header">{{ __('cms.date_time') }}</th>
                    <th class="table-cell-header">{{ __('cms.user') }}</th>
                    <th class="table-cell-header">{{ __('cms.action') }}</th>
                    <th class="table-cell-header">{{ __('cms.model') }}</th>
                    <th class="table-cell-header">{{ __('cms.description') }}</th>
                    <th class="table-cell-header">{{ __('cms.ip_address') }}</th>
                    <th class="table-cell-header">{{ __('cms.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($auditLogs as $log)
                <tr class="table-row-border">
                    <td class="table-cell-padding text-center">
                        <input type="checkbox" class="log-checkbox" value="{{ $log->id }}" onchange="updateDeleteButton()">
                    </td>
                    <td class="table-cell-padding text-sm">
                        {{ $log->created_at->format('M d, Y') }}<br>
                        <span class="text-tertiary">{{ $log->created_at->format('h:i A') }}</span>
                    </td>
                    <td class="table-cell-padding">
                        @if($log->user)
                            {{ $log->user->first_name && $log->user->last_name ? $log->user->first_name . ' ' . $log->user->last_name : $log->user->name }}
                            <br>
                            <span class="text-tertiary text-sm">{{ $log->user->email }}</span>
                        @else
                            <span class="text-quaternary">{{ __('cms.system') }}</span>
                        @endif
                    </td>
                    <td class="table-cell-padding">
                        @php
                            $actionBadgeClass = $log->action == 'created' ? 'badge-green' : 
                                               ($log->action == 'updated' ? 'badge-blue' : 
                                               ($log->action == 'deleted' ? 'badge-red' : 'badge-gray'));
                        @endphp
                        <span class="badge {{ $actionBadgeClass }}">
                            {{ __('cms.action_' . $log->action) ?: ucfirst($log->action) }}
                        </span>
                    </td>
                    <td class="table-cell-padding">
                        @if($log->model_type)
                            <span class="font-mono text-sm text-tertiary">
                                {{ __('cms.model_' . strtolower(class_basename($log->model_type))) ?: class_basename($log->model_type) }}
                            </span>
                            @if($log->model_id)
                                <br>
                                <span class="text-quaternary text-xs">{{ __('cms.id') }}: {{ $log->model_id }}</span>
                            @endif
                        @else
                            <span class="text-quaternary">{{ __('cms.na') }}</span>
                        @endif
                    </td>
                    <td class="table-cell-padding max-w-300">
                        <div class="text-ellipsis">
                            {{ $log->description ?? __('cms.na') }}
                        </div>
                    </td>
                    <td class="table-cell-padding font-mono text-sm text-tertiary">
                        {{ $log->ip_address ?? __('cms.na') }}
                    </td>
                    <td class="table-cell-padding">
                        <div class="flex gap-2">
                            <a href="{{ route('audit-logs.show', $log) }}" class="action-btn action-btn-edit" title="{{ __('cms.view_details') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            <form action="{{ route('audit-logs.destroy', $log) }}" method="POST" class="d-inline" data-confirm="{{ __('cms.confirm_delete_audit_log') }}">
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
                    <td colspan="8" class="p-8 text-center text-tertiary">{{ __('cms.no_audit_logs_found') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($auditLogs->hasPages())
    <div class="mt-6 flex justify-center">
        {{ $auditLogs->links() }}
    </div>
    @endif
</div>

<!-- Bulk Delete Form -->
<form id="bulk-delete-form" action="{{ route('audit-logs.destroyMultiple') }}" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>

<!-- Delete All Filtered Form -->
<form id="delete-all-form" action="{{ route('audit-logs.destroyAll') }}" method="POST" class="d-none">
    @csrf
    @method('DELETE')
    @if(request('action'))
        <input type="hidden" name="action" value="{{ request('action') }}">
    @endif
    @if(request('user_id'))
        <input type="hidden" name="user_id" value="{{ request('user_id') }}">
    @endif
    @if(request('model_type'))
        <input type="hidden" name="model_type" value="{{ request('model_type') }}">
    @endif
    @if(request('date_from'))
        <input type="hidden" name="date_from" value="{{ request('date_from') }}">
    @endif
    @if(request('date_to'))
        <input type="hidden" name="date_to" value="{{ request('date_to') }}">
    @endif
    @if(request('search'))
        <input type="hidden" name="search" value="{{ request('search') }}">
    @endif
</form>

<script>
function toggleSelectAll(checkbox) {
    const checkboxes = document.querySelectorAll('.log-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = checkbox.checked;
    });
    updateDeleteButton();
}

function updateDeleteButton() {
    const checked = document.querySelectorAll('.log-checkbox:checked');
    const deleteBtn = document.getElementById('delete-selected-btn');
    
    if (checked.length > 0) {
        deleteBtn.style.display = 'block';
        deleteBtn.textContent = '{{ __('cms.delete_selected_count') }}'.replace(':count', checked.length);
    } else {
        deleteBtn.style.display = 'none';
    }
}

function deleteSelected() {
    const checked = document.querySelectorAll('.log-checkbox:checked');
    if (checked.length === 0) {
        alert('{{ __('cms.please_select_audit_log') }}');
        return;
    }
    
    const confirmMessage = '{{ __('cms.confirm_delete_audit_logs') }}'.replace(':count', checked.length);
    if (!confirm(confirmMessage)) {
        return;
    }
    
    const ids = Array.from(checked).map(cb => cb.value);
    const form = document.getElementById('bulk-delete-form');
    
    // Add IDs as hidden inputs
    ids.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ids[]';
        input.value = id;
        form.appendChild(input);
    });
    
    form.submit();
}

function deleteAllFiltered() {
    const count = {{ $auditLogs->total() }};
    if (count === 0) {
        alert('{{ __('cms.no_audit_logs_to_delete') }}');
        return;
    }
    
    const confirmMessage = '{{ __('cms.confirm_delete_all_filtered_audit_logs') }}'.replace(':count', count);
    if (!confirm(confirmMessage)) {
        return;
    }
    
    document.getElementById('delete-all-form').submit();
}
</script>
@endsection

