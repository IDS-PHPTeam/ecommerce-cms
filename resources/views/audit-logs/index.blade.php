@extends('layouts.app')

@section('title', 'Audit Logs')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.875rem; font-weight: 700;">Audit Logs</h2>
        <div style="display: flex; gap: 0.5rem;">
            <a href="{{ route('audit-logs.export', request()->query()) }}" class="btn btn-primary" style="display: flex; align-items: center; gap: 0.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export CSV
            </a>
            <button type="button" onclick="deleteSelected()" id="delete-selected-btn" class="btn" style="background-color: #ef4444; color: white; display: none;">
                Delete Selected
            </button>
            <button type="button" onclick="deleteAllFiltered()" class="btn" style="background-color: #dc2626; color: white;">
                Delete All Filtered
            </button>
        </div>
    </div>

    @if(session('success'))
        <div style="background-color: #d1fae5; color: #065f46; padding: 0.75rem 1rem; border-radius: 0.375rem; margin-bottom: 1.5rem; border: 1px solid #6ee7b7;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="card" style="margin-bottom: 1.5rem; padding: 1rem;">
        <form method="GET" action="{{ route('audit-logs.index') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div>
                <label for="search" class="form-label" style="font-size: 0.875rem; margin-bottom: 0.25rem;">Search</label>
                <input 
                    type="text" 
                    id="search" 
                    name="search" 
                    value="{{ request('search') }}" 
                    class="form-input"
                    placeholder="Search description..."
                >
            </div>
            <div>
                <label for="action" class="form-label" style="font-size: 0.875rem; margin-bottom: 0.25rem;">Action</label>
                <select id="action" name="action" class="form-input">
                    <option value="">All Actions</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>{{ ucfirst($action) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="user_id" class="form-label" style="font-size: 0.875rem; margin-bottom: 0.25rem;">User</label>
                <select id="user_id" name="user_id" class="form-input">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->first_name && $user->last_name ? $user->first_name . ' ' . $user->last_name : $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="model_type" class="form-label" style="font-size: 0.875rem; margin-bottom: 0.25rem;">Model Type</label>
                <select id="model_type" name="model_type" class="form-input">
                    <option value="">All Models</option>
                    @foreach($modelTypes as $modelType)
                        <option value="{{ $modelType }}" {{ request('model_type') == $modelType ? 'selected' : '' }}>
                            {{ class_basename($modelType) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="date_from" class="form-label" style="font-size: 0.875rem; margin-bottom: 0.25rem;">Date From</label>
                <input 
                    type="date" 
                    id="date_from" 
                    name="date_from" 
                    value="{{ request('date_from') }}" 
                    class="form-input"
                >
            </div>
            <div>
                <label for="date_to" class="form-label" style="font-size: 0.875rem; margin-bottom: 0.25rem;">Date To</label>
                <input 
                    type="date" 
                    id="date_to" 
                    name="date_to" 
                    value="{{ request('date_to') }}" 
                    class="form-input"
                >
            </div>
            <div style="display: flex; align-items: end; gap: 0.5rem;">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('audit-logs.index') }}" class="btn" style="background-color: #6b7280; color: white;">Reset</a>
            </div>
        </form>
    </div>

    <!-- Audit Logs Table -->
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f3f4f6; border-bottom: 2px solid #e5e7eb;">
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600; width: 40px;">
                        <input type="checkbox" id="select-all" onchange="toggleSelectAll(this)">
                    </th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Date & Time</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">User</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Action</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Model</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Description</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">IP Address</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($auditLogs as $log)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 0.75rem; text-align: center;">
                        <input type="checkbox" class="log-checkbox" value="{{ $log->id }}" onchange="updateDeleteButton()">
                    </td>
                    <td style="padding: 0.75rem; font-size: 0.875rem;">
                        {{ $log->created_at->format('M d, Y') }}<br>
                        <span style="color: #6b7280;">{{ $log->created_at->format('h:i A') }}</span>
                    </td>
                    <td style="padding: 0.75rem;">
                        @if($log->user)
                            {{ $log->user->first_name && $log->user->last_name ? $log->user->first_name . ' ' . $log->user->last_name : $log->user->name }}
                            <br>
                            <span style="color: #6b7280; font-size: 0.875rem;">{{ $log->user->email }}</span>
                        @else
                            <span style="color: #9ca3af;">System</span>
                        @endif
                    </td>
                    <td style="padding: 0.75rem;">
                        <span style="padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 500; 
                            @if($log->action == 'created') background-color: #d1fae5; color: #065f46;
                            @elseif($log->action == 'updated') background-color: #dbeafe; color: #1e40af;
                            @elseif($log->action == 'deleted') background-color: #fee2e2; color: #991b1b;
                            @else background-color: #f3f4f6; color: #374151;
                            @endif">
                            {{ ucfirst($log->action) }}
                        </span>
                    </td>
                    <td style="padding: 0.75rem;">
                        @if($log->model_type)
                            <span style="font-family: monospace; font-size: 0.875rem; color: #6b7280;">
                                {{ class_basename($log->model_type) }}
                            </span>
                            @if($log->model_id)
                                <br>
                                <span style="color: #9ca3af; font-size: 0.75rem;">ID: {{ $log->model_id }}</span>
                            @endif
                        @else
                            <span style="color: #9ca3af;">N/A</span>
                        @endif
                    </td>
                    <td style="padding: 0.75rem; max-width: 300px;">
                        <div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ $log->description ?? 'N/A' }}
                        </div>
                    </td>
                    <td style="padding: 0.75rem; font-family: monospace; font-size: 0.875rem; color: #6b7280;">
                        {{ $log->ip_address ?? 'N/A' }}
                    </td>
                    <td style="padding: 0.75rem;">
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('audit-logs.show', $log) }}" class="action-btn action-btn-edit" title="View Details">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            <form action="{{ route('audit-logs.destroy', $log) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this audit log?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn action-btn-delete" title="Delete">
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
                    <td colspan="8" style="padding: 2rem; text-align: center; color: #6b7280;">No audit logs found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="margin-top: 1.5rem;">
        {{ $auditLogs->links() }}
    </div>
</div>

<!-- Bulk Delete Form -->
<form id="bulk-delete-form" action="{{ route('audit-logs.destroyMultiple') }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Delete All Filtered Form -->
<form id="delete-all-form" action="{{ route('audit-logs.destroyAll') }}" method="POST" style="display: none;">
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
        deleteBtn.textContent = `Delete Selected (${checked.length})`;
    } else {
        deleteBtn.style.display = 'none';
    }
}

function deleteSelected() {
    const checked = document.querySelectorAll('.log-checkbox:checked');
    if (checked.length === 0) {
        alert('Please select at least one audit log to delete.');
        return;
    }
    
    if (!confirm(`Are you sure you want to delete ${checked.length} audit log(s)?`)) {
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
        alert('No audit logs to delete.');
        return;
    }
    
    if (!confirm(`Are you sure you want to delete all ${count} filtered audit log(s)? This action cannot be undone.`)) {
        return;
    }
    
    document.getElementById('delete-all-form').submit();
}
</script>
@endsection

