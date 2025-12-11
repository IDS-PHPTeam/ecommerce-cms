@extends('layouts.app')

@section('title', __('cms.audit_log_details'))

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h2 class="section-heading-lg">{{ __('cms.audit_log_details') }}</h2>
        <a href="{{ route('audit-logs.index') }}" class="btn bg-gray-600 text-white">{{ __('cms.back_to_logs') }}</a>
    </div>

    <div class="grid grid-auto-300 gap-6">
        <!-- Basic Information -->
        <div class="p-6 bg-gray-50 rounded-md">
            <h3 class="text-xl font-semibold mb-4 text-secondary">{{ __('cms.basic_information') }}</h3>
            <div class="flex-col gap-3">
                <div>
                    <span class="text-sm text-tertiary font-medium">{{ __('cms.date_time') }}:</span>
                    <div class="mt-1 font-medium">
                        {{ $auditLog->created_at->format('F d, Y h:i A') }}
                    </div>
                </div>
                <div>
                    <span class="text-sm text-tertiary font-medium">{{ __('cms.user') }}:</span>
                    <div class="mt-1">
                        @if($auditLog->user)
                            {{ $auditLog->user->first_name && $auditLog->user->last_name ? $auditLog->user->first_name . ' ' . $auditLog->user->last_name : $auditLog->user->name }}
                            <br>
                            <span class="text-tertiary text-sm">{{ $auditLog->user->email }}</span>
                        @else
                            <span class="text-quaternary">{{ __('cms.system') }}</span>
                        @endif
                    </div>
                </div>
                <div>
                    <span class="text-sm text-tertiary font-medium">{{ __('cms.action') }}:</span>
                    <div class="mt-1">
                        @php
                            $actionBadgeClass = $auditLog->action == 'created' ? 'badge-green' : 
                                               ($auditLog->action == 'updated' ? 'badge-blue' : 
                                               ($auditLog->action == 'deleted' ? 'badge-red' : 'badge-gray'));
                        @endphp
                        <span class="badge {{ $actionBadgeClass }}">
                            {{ __('cms.action_' . $auditLog->action) ?: ucfirst($auditLog->action) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Model Information -->
        <div class="p-6 bg-gray-50 rounded-md">
            <h3 class="text-xl font-semibold mb-4 text-secondary">{{ __('cms.model_information') }}</h3>
            <div class="flex-col gap-3">
                <div>
                    <span class="text-sm text-tertiary font-medium">{{ __('cms.model_type') }}:</span>
                    <div class="mt-1 font-mono font-medium">
                        {{ $auditLog->model_type ? (__('cms.model_' . strtolower(class_basename($auditLog->model_type))) ?: class_basename($auditLog->model_type)) : __('cms.na') }}
                    </div>
                </div>
                <div>
                    <span class="text-sm text-tertiary font-medium">{{ __('cms.model_id') }}:</span>
                    <div class="mt-1 font-medium">
                        {{ $auditLog->model_id ?? __('cms.na') }}
                    </div>
                </div>
                <div>
                    <span class="text-sm text-tertiary font-medium">{{ __('cms.description') }}:</span>
                    <div class="mt-1 font-medium">
                        {{ $auditLog->description ?? __('cms.na') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Request Information -->
        <div class="p-6 bg-gray-50 rounded-md">
            <h3 class="text-xl font-semibold mb-4 text-secondary">{{ __('cms.request_information') }}</h3>
            <div class="flex-col gap-3">
                <div>
                    <span class="text-sm text-tertiary font-medium">{{ __('cms.ip_address') }}:</span>
                    <div class="mt-1 font-mono font-medium">
                        {{ $auditLog->ip_address ?? __('cms.na') }}
                    </div>
                </div>
                <div>
                    <span class="text-sm text-tertiary font-medium">{{ __('cms.user_agent') }}:</span>
                    <div class="mt-1 text-sm word-break-all">
                        {{ $auditLog->user_agent ?? __('cms.na') }}
                    </div>
                </div>
                <div>
                    <span class="text-sm text-tertiary font-medium">{{ __('cms.url') }}:</span>
                    <div class="mt-1 text-sm word-break-all text-primary-blue">
                        {{ $auditLog->url ?? __('cms.na') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Changes -->
    @if($auditLog->old_values || $auditLog->new_values)
    <div class="mt-8">
        <h3 class="section-heading mb-4">{{ __('cms.changes') }}</h3>
        <div class="grid grid-auto-400 gap-6">
            @if($auditLog->old_values)
            <div class="p-6 bg-red-50 border border-red-200 rounded-md">
                <h4 class="text-base font-semibold mb-4 text-red-700">{{ __('cms.old_values') }}</h4>
                <pre class="bg-white p-4 rounded text-sm overflow-x-auto leading-normal">{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT) }}</pre>
            </div>
            @endif

            @if($auditLog->new_values)
            <div class="p-6 bg-green-50 border border-green-200 rounded-md">
                <h4 class="text-base font-semibold mb-4 text-green-700">{{ __('cms.new_values') }}</h4>
                <pre class="bg-white p-4 rounded text-sm overflow-x-auto leading-normal">{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT) }}</pre>
            </div>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection




