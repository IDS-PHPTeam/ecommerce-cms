@extends('layouts.app')

@section('title', __('cms.admins'))

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h2 class="section-heading-lg">{{ __('cms.all_admins') }}</h2>
        <a href="{{ route('admins.create') }}" class="btn btn-primary">{{ __('cms.add_new') }}</a>
    </div>

    @if(session('success'))
        <div class="alert-success mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Admins Table -->
    <div class="overflow-x-auto">
        <table class="w-full" style="border-collapse: collapse;">
            <thead>
                <tr class="table-header-row">
                    <th class="table-cell-header">{{ __('cms.name') }}</th>
                    <th class="table-cell-header">{{ __('cms.email') }}</th>
                    <th class="table-cell-header">{{ __('cms.role') }}</th>
                    <th class="table-cell-header">{{ __('cms.created') }}</th>
                    <th class="table-cell-header">{{ __('cms.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $admin)
                <tr class="table-row-border">
                    <td class="table-cell-padding">
                        {{ $admin->first_name && $admin->last_name ? $admin->first_name . ' ' . $admin->last_name : $admin->name }}
                    </td>
                    <td class="table-cell-padding">{{ $admin->email }}</td>
                    <td class="table-cell-padding">
                        <span class="badge badge-blue">
                            {{ ucfirst($admin->role) }}
                        </span>
                    </td>
                    <td class="table-cell-padding">{{ $admin->created_at->format('M d, Y') }}</td>
                    <td class="table-cell-padding">
                        <div class="flex gap-2">
                            <a href="{{ route('admins.edit', $admin) }}" class="action-btn action-btn-edit" title="{{ __('cms.edit') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form action="{{ route('admins.destroy', $admin) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('cms.confirm_delete_admin') }}');">
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
                    <td colspan="5" class="p-8 text-center text-tertiary">{{ __('cms.no_admins_found') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $admins->links() }}
    </div>
</div>
@endsection

