@extends('layouts.app')

@section('title', 'Driver Details')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.875rem; font-weight: 700;">Driver Details</h2>
        <a href="{{ route('drivers.index') }}" class="btn" style="background-color: #6b7280; color: white;">Back to List</a>
    </div>

    <!-- Driver Info -->
    <div class="card" style="margin-bottom: 1.5rem; padding: 1.5rem;">
        <div style="display: flex; gap: 2rem; align-items: start;">
            @if($driver->profile_image)
            <div style="flex-shrink: 0;">
                <img src="{{ asset('storage/' . $driver->profile_image) }}" alt="Driver Profile" style="width: 150px; height: 150px; border-radius: 50%; border: 3px solid var(--primary-blue); object-fit: cover;">
            </div>
            @else
            <div style="flex-shrink: 0; width: 150px; height: 150px; border-radius: 50%; background-color: #e5e7eb; display: flex; align-items: center; justify-content: center; border: 3px solid var(--primary-blue);">
                <span style="font-size: 3rem; color: #9ca3af;">{{ strtoupper(substr($driver->first_name ?? $driver->name, 0, 1)) }}</span>
            </div>
            @endif
            <div style="flex: 1;">
                <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem;">Driver Information</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                    <div>
                        <label class="form-label">ID</label>
                        <p style="margin-top: 0.25rem;">{{ $driver->id }}</p>
                    </div>
                    <div>
                        <label class="form-label">Name</label>
                        <p style="margin-top: 0.25rem;">{{ $driver->first_name && $driver->last_name ? $driver->first_name . ' ' . $driver->last_name : $driver->name }}</p>
                    </div>
                    <div>
                        <label class="form-label">Email</label>
                        <p style="margin-top: 0.25rem;">{{ $driver->email }}</p>
                    </div>
                    <div>
                        <label class="form-label">Status</label>
                        <p style="margin-top: 0.25rem;">
                            <span style="padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 500; {{ $driver->driver_status == 'active' ? 'background-color: #d1fae5; color: #065f46;' : 'background-color: #fee2e2; color: #991b1b;' }}">
                                {{ ucfirst($driver->driver_status ?? 'N/A') }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="form-label">Load Capacity</label>
                        <p style="margin-top: 0.25rem;">{{ $driver->load_capacity ?? 'N/A' }} kg</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="card" style="margin-bottom: 1.5rem; padding: 1.5rem;">
        <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem;">Driver Performance</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div style="text-align: center; padding: 1rem; background-color: #f3f4f6; border-radius: 0.5rem;">
                <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.5rem;">Total Orders</p>
                <p style="font-size: 1.5rem; font-weight: 700; color: #1f2937;">{{ $totalOrders }}</p>
            </div>
            <div style="text-align: center; padding: 1rem; background-color: #d1fae5; border-radius: 0.5rem;">
                <p style="font-size: 0.875rem; color: #065f46; margin-bottom: 0.5rem;">Completed</p>
                <p style="font-size: 1.5rem; font-weight: 700; color: #065f46;">{{ $completedOrders }}</p>
            </div>
            <div style="text-align: center; padding: 1rem; background-color: #fee2e2; border-radius: 0.5rem;">
                <p style="font-size: 0.875rem; color: #991b1b; margin-bottom: 0.5rem;">Failed</p>
                <p style="font-size: 1.5rem; font-weight: 700; color: #991b1b;">{{ $failedOrders }}</p>
            </div>
            <div style="text-align: center; padding: 1rem; background-color: #dbeafe; border-radius: 0.5rem;">
                <p style="font-size: 0.875rem; color: #1e40af; margin-bottom: 0.5rem;">Success Rate</p>
                <p style="font-size: 1.5rem; font-weight: 700; color: #1e40af;">{{ $successRate }}%</p>
            </div>
            <div style="text-align: center; padding: 1rem; background-color: #fef3c7; border-radius: 0.5rem;">
                <p style="font-size: 0.875rem; color: #92400e; margin-bottom: 0.5rem;">Average Rating</p>
                <p style="font-size: 1.5rem; font-weight: 700; color: #92400e;">★ {{ number_format($averageRating, 1) }}</p>
            </div>
        </div>
    </div>

    <!-- Order History -->
    <div class="card" style="margin-bottom: 1.5rem; padding: 1.5rem;">
        <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem;">Order History</h3>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f3f4f6; border-bottom: 2px solid #e5e7eb;">
                        <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Order ID</th>
                        <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Customer</th>
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
                        <td colspan="6" style="padding: 2rem; text-align: center; color: #6b7280;">No orders found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top: 1rem;">
            {{ $orders->links() }}
        </div>
    </div>

    <!-- Feedback & Ratings -->
    @if($feedback->count() > 0)
    <div class="card" style="padding: 1.5rem;">
        <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem;">Feedback & Ratings</h3>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            @foreach($feedback as $item)
            <div style="padding: 1rem; background-color: #f9fafb; border-radius: 0.5rem; border-left: 4px solid var(--primary-blue);">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                    <div>
                        <p style="font-weight: 600; color: #1f2937;">Order #{{ $item['order_id'] }} - {{ $item['customer'] }}</p>
                        <p style="font-size: 0.875rem; color: #6b7280;">{{ \Carbon\Carbon::parse($item['date'])->format('M d, Y') }}</p>
                    </div>
                    @if($item['rating'])
                    <span style="color: #f59e0b; font-weight: 600;">★ {{ $item['rating'] }}/5</span>
                    @endif
                </div>
                @if($item['feedback'])
                <p style="color: #374151; margin-top: 0.5rem;">{{ $item['feedback'] }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection


