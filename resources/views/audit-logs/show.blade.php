@extends('layouts.app')

@section('title', 'Audit Log Details')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.875rem; font-weight: 700;">Audit Log Details</h2>
        <a href="{{ route('audit-logs.index') }}" class="btn" style="background-color: #6b7280; color: white;">Back to Logs</a>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
        <!-- Basic Information -->
        <div style="padding: 1.5rem; background-color: #f9fafb; border-radius: 0.5rem;">
            <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem; color: #374151;">Basic Information</h3>
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                <div>
                    <span style="font-size: 0.875rem; color: #6b7280; font-weight: 500;">Date & Time:</span>
                    <div style="margin-top: 0.25rem; font-weight: 500;">
                        {{ $auditLog->created_at->format('F d, Y h:i A') }}
                    </div>
                </div>
                <div>
                    <span style="font-size: 0.875rem; color: #6b7280; font-weight: 500;">User:</span>
                    <div style="margin-top: 0.25rem;">
                        @if($auditLog->user)
                            {{ $auditLog->user->first_name && $auditLog->user->last_name ? $auditLog->user->first_name . ' ' . $auditLog->user->last_name : $auditLog->user->name }}
                            <br>
                            <span style="color: #6b7280; font-size: 0.875rem;">{{ $auditLog->user->email }}</span>
                        @else
                            <span style="color: #9ca3af;">System</span>
                        @endif
                    </div>
                </div>
                <div>
                    <span style="font-size: 0.875rem; color: #6b7280; font-weight: 500;">Action:</span>
                    <div style="margin-top: 0.25rem;">
                        <span style="padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 500; 
                            @if($auditLog->action == 'created') background-color: #d1fae5; color: #065f46;
                            @elseif($auditLog->action == 'updated') background-color: #dbeafe; color: #1e40af;
                            @elseif($auditLog->action == 'deleted') background-color: #fee2e2; color: #991b1b;
                            @else background-color: #f3f4f6; color: #374151;
                            @endif">
                            {{ ucfirst($auditLog->action) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Model Information -->
        <div style="padding: 1.5rem; background-color: #f9fafb; border-radius: 0.5rem;">
            <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem; color: #374151;">Model Information</h3>
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                <div>
                    <span style="font-size: 0.875rem; color: #6b7280; font-weight: 500;">Model Type:</span>
                    <div style="margin-top: 0.25rem; font-family: monospace; font-weight: 500;">
                        {{ $auditLog->model_type ? class_basename($auditLog->model_type) : 'N/A' }}
                    </div>
                </div>
                <div>
                    <span style="font-size: 0.875rem; color: #6b7280; font-weight: 500;">Model ID:</span>
                    <div style="margin-top: 0.25rem; font-weight: 500;">
                        {{ $auditLog->model_id ?? 'N/A' }}
                    </div>
                </div>
                <div>
                    <span style="font-size: 0.875rem; color: #6b7280; font-weight: 500;">Description:</span>
                    <div style="margin-top: 0.25rem; font-weight: 500;">
                        {{ $auditLog->description ?? 'N/A' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Request Information -->
        <div style="padding: 1.5rem; background-color: #f9fafb; border-radius: 0.5rem;">
            <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem; color: #374151;">Request Information</h3>
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                <div>
                    <span style="font-size: 0.875rem; color: #6b7280; font-weight: 500;">IP Address:</span>
                    <div style="margin-top: 0.25rem; font-family: monospace; font-weight: 500;">
                        {{ $auditLog->ip_address ?? 'N/A' }}
                    </div>
                </div>
                <div>
                    <span style="font-size: 0.875rem; color: #6b7280; font-weight: 500;">User Agent:</span>
                    <div style="margin-top: 0.25rem; font-size: 0.875rem; word-break: break-all;">
                        {{ $auditLog->user_agent ?? 'N/A' }}
                    </div>
                </div>
                <div>
                    <span style="font-size: 0.875rem; color: #6b7280; font-weight: 500;">URL:</span>
                    <div style="margin-top: 0.25rem; font-size: 0.875rem; word-break: break-all; color: var(--primary-blue);">
                        {{ $auditLog->url ?? 'N/A' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Changes -->
    @if($auditLog->old_values || $auditLog->new_values)
    <div style="margin-top: 2rem;">
        <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem;">Changes</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem;">
            @if($auditLog->old_values)
            <div style="padding: 1.5rem; background-color: #fef2f2; border: 1px solid #fecaca; border-radius: 0.5rem;">
                <h4 style="font-size: 1rem; font-weight: 600; margin-bottom: 1rem; color: #991b1b;">Old Values</h4>
                <pre style="background-color: white; padding: 1rem; border-radius: 0.375rem; overflow-x: auto; font-size: 0.875rem; line-height: 1.5;">{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT) }}</pre>
            </div>
            @endif

            @if($auditLog->new_values)
            <div style="padding: 1.5rem; background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 0.5rem;">
                <h4 style="font-size: 1rem; font-weight: 600; margin-bottom: 1rem; color: #166534;">New Values</h4>
                <pre style="background-color: white; padding: 1rem; border-radius: 0.375rem; overflow-x: auto; font-size: 0.875rem; line-height: 1.5;">{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT) }}</pre>
            </div>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection




