@extends('layouts.app')

@section('title', 'Orders')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.875rem; font-weight: 700;">Orders</h2>
    </div>

    <!-- Filters -->
    <div class="card" style="margin-bottom: 1.5rem; padding: 1rem;">
        <form method="GET" action="{{ route('orders.index') }}">
            <!-- First Row: Date Fields -->
            <div style="display: flex; gap: 1rem; align-items: end; margin-bottom: 1rem;">
                <div style="flex: 1; min-width: 200px;">
                    <label for="date_from" class="form-label">Date From</label>
                    <input 
                        type="date" 
                        id="date_from" 
                        name="date_from" 
                        value="{{ request('date_from') }}" 
                        class="form-input"
                    >
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <label for="date_to" class="form-label">Date To</label>
                    <input 
                        type="date" 
                        id="date_to" 
                        name="date_to" 
                        value="{{ request('date_to') }}" 
                        class="form-input"
                    >
                </div>
            </div>
            
            <!-- Second Row: Status, Location, Customer, Driver, Buttons -->
            <div style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: end;">
                <div style="flex: 1; min-width: 200px;">
                    <select id="status" name="status" class="form-input">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <input 
                        type="text" 
                        id="location" 
                        name="location" 
                        value="{{ request('location') }}" 
                        class="form-input"
                        placeholder="Search by location..."
                    >
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <input 
                        type="text" 
                        id="customer" 
                        name="customer" 
                        value="{{ request('customer') }}" 
                        class="form-input"
                        placeholder="Search by customer..."
                    >
                </div>
                <div style="flex: 0 0 auto; min-width: 200px; max-width: 250px;">
                    <select id="driver" name="driver" class="form-input">
                        <option value="">All Drivers</option>
                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}" {{ request('driver') == $driver->id ? 'selected' : '' }}>
                                {{ $driver->first_name && $driver->last_name ? $driver->first_name . ' ' . $driver->last_name : $driver->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('orders.index') }}" class="btn" style="background-color: #6b7280; color: white; margin-left: 0.5rem;">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f3f4f6; border-bottom: 2px solid #e5e7eb;">
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">ID</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Customer</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Location</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Driver</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Total</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Status</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Date</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 0.75rem;">{{ $order->id }}</td>
                    <td style="padding: 0.75rem;">{{ $order->customer_name }}</td>
                    <td style="padding: 0.75rem; max-width: 200px; word-break: break-word;">{{ Str::limit($order->location, 50) }}</td>
                    <td style="padding: 0.75rem;">
                        @if($order->driver)
                            {{ $order->driver->first_name && $order->driver->last_name ? $order->driver->first_name . ' ' . $order->driver->last_name : $order->driver->name }}
                        @else
                            <span style="color: #9ca3af;">Not Assigned</span>
                        @endif
                    </td>
                    <td style="padding: 0.75rem;">${{ number_format($order->total, 2) }}</td>
                    <td style="padding: 0.75rem;">
                        <span style="padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 500; 
                            {{ $order->status == 'completed' ? 'background-color: #d1fae5; color: #065f46;' : 
                               ($order->status == 'failed' ? 'background-color: #fee2e2; color: #991b1b;' : 
                               ($order->status == 'assigned' ? 'background-color: #dbeafe; color: #1e40af;' : 
                               'background-color: #fef3c7; color: #92400e;')) }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td style="padding: 0.75rem;">{{ $order->order_date->format('M d, Y') }}</td>
                    <td style="padding: 0.75rem;">
                        <a href="{{ route('orders.show', $order) }}" class="action-btn action-btn-edit" title="View">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="padding: 2rem; text-align: center; color: #6b7280;">No orders found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="margin-top: 1.5rem;">
        {{ $orders->links() }}
    </div>
</div>
@endsection

