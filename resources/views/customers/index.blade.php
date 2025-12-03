@extends('layouts.app')

@section('title', 'Customers')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.875rem; font-weight: 700;">All Customers</h2>
        <a href="{{ route('customers.create') }}" class="btn btn-primary">+ Add New</a>
    </div>

    <!-- Filters -->
    <div class="card" style="margin-bottom: 1.5rem; padding: 1rem;">
        <form method="GET" action="{{ route('customers.index') }}" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: end;">
            <div style="flex: 1; min-width: 200px;">
                <input 
                    type="text" 
                    id="search" 
                    name="search" 
                    value="{{ request('search') }}" 
                    class="form-input"
                    placeholder="Search by name, email, or phone..."
                >
            </div>
            <div style="flex: 1; min-width: 200px;">
                <select id="status" name="status" class="form-input">
                    <option value="">All Status</option>
                    <option value="active_not_verified" {{ request('status') == 'active_not_verified' ? 'selected' : '' }}>Active (Not Verified)</option>
                    <option value="active_verified" {{ request('status') == 'active_verified' ? 'selected' : '' }}>Active (Verified)</option>
                    <option value="deactivated" {{ request('status') == 'deactivated' ? 'selected' : '' }}>Deactivated</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('customers.index') }}" class="btn" style="background-color: #6b7280; color: white; margin-left: 0.5rem;">Reset</a>
            </div>
        </form>
    </div>

    <!-- Customers Table -->
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f3f4f6; border-bottom: 2px solid #e5e7eb;">
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Name</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Email</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Phone</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Orders</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Account Status</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 0.75rem;">
                        {{ $customer->first_name && $customer->last_name ? $customer->first_name . ' ' . $customer->last_name : $customer->name }}
                    </td>
                    <td style="padding: 0.75rem;">{{ $customer->email }}</td>
                    <td style="padding: 0.75rem;">{{ $customer->phone ?? 'N/A' }}</td>
                    <td style="padding: 0.75rem;">{{ $customer->orders_count ?? 0 }}</td>
                    <td style="padding: 0.75rem;">
                        @php
                            $statusColors = [
                                'active_not_verified' => 'background-color: #fef3c7; color: #92400e;',
                                'active_verified' => 'background-color: #d1fae5; color: #065f46;',
                                'deactivated' => 'background-color: #fee2e2; color: #991b1b;',
                                'suspended' => 'background-color: #fee2e2; color: #991b1b;',
                            ];
                            $statusLabels = [
                                'active_not_verified' => 'Active (Not Verified)',
                                'active_verified' => 'Active (Verified)',
                                'deactivated' => 'Deactivated',
                                'suspended' => 'Suspended',
                            ];
                            $status = $customer->account_status ?? 'active_not_verified';
                        @endphp
                        <span style="padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 500; {{ $statusColors[$status] ?? $statusColors['active_not_verified'] }}">
                            {{ $statusLabels[$status] ?? ucfirst($status) }}
                        </span>
                    </td>
                    <td style="padding: 0.75rem;">
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('customers.show', $customer) }}" class="action-btn action-btn-edit" title="View">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            <a href="{{ route('customers.edit', $customer) }}" class="action-btn action-btn-edit" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form action="{{ route('customers.destroy', $customer) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this customer?');">
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
                    <td colspan="6" style="padding: 2rem; text-align: center; color: #6b7280;">No customers found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="margin-top: 1.5rem;">
        {{ $customers->links() }}
    </div>
</div>
@endsection


