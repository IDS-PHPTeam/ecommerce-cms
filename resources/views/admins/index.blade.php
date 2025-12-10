@extends('layouts.app')

@section('title', __('cms.admins'))

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.875rem; font-weight: 700;">{{ __('cms.all_admins') }}</h2>
        <a href="{{ route('admins.create') }}" class="btn btn-primary">{{ __('cms.add_new') }}</a>
    </div>

    @if(session('success'))
        <div style="background-color: #d1fae5; color: #065f46; padding: 0.75rem 1rem; border-radius: 0.375rem; margin-bottom: 1.5rem; border: 1px solid #6ee7b7;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Admins Table -->
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr class="table-header-row">
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">{{ __('cms.name') }}</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">{{ __('cms.email') }}</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">{{ __('cms.role') }}</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">{{ __('cms.created') }}</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">{{ __('cms.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $admin)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 0.75rem;">
                        {{ $admin->first_name && $admin->last_name ? $admin->first_name . ' ' . $admin->last_name : $admin->name }}
                    </td>
                    <td style="padding: 0.75rem;">{{ $admin->email }}</td>
                    <td style="padding: 0.75rem;">
                        <span style="padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 500; background-color: #dbeafe; color: #1e40af;">
                            {{ ucfirst($admin->role) }}
                        </span>
                    </td>
                    <td style="padding: 0.75rem;">{{ $admin->created_at->format('M d, Y') }}</td>
                    <td style="padding: 0.75rem;">
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('admins.edit', $admin) }}" class="action-btn action-btn-edit" title="{{ __('cms.edit') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form action="{{ route('admins.destroy', $admin) }}" method="POST" style="display: inline;" onsubmit="return confirm('{{ __('cms.confirm_delete_admin') }}');">
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
                    <td colspan="5" style="padding: 2rem; text-align: center; color: #6b7280;">{{ __('cms.no_admins_found') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="margin-top: 1.5rem;">
        {{ $admins->links() }}
    </div>
</div>
@endsection

