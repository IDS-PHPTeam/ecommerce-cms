@extends('layouts.app')

@section('title', 'Customer Details')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.875rem; font-weight: 700;">Customer Details</h2>
        <a href="{{ route('customers.index') }}" class="btn" style="background-color: #6b7280; color: white;">Back to List</a>
    </div>

    <!-- Customer Info -->
    <div class="card" style="margin-bottom: 1.5rem; padding: 1.5rem;">
        <div style="display: flex; gap: 2rem; align-items: start;">
            @if($customer->profile_image)
                <div>
                    <img src="{{ asset('storage/' . $customer->profile_image) }}" alt="Profile" style="width: 150px; height: 150px; border-radius: 50%; border: 3px solid var(--primary-blue); object-fit: cover;">
                </div>
            @else
                <div style="width: 150px; height: 150px; border-radius: 50%; border: 3px solid #e5e7eb; background-color: #f3f4f6; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="60" height="60" style="color: #9ca3af;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            @endif
            <div style="flex: 1;">
                <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem;">Customer Information</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                    <div>
                        <label class="form-label">ID</label>
                        <p style="margin-top: 0.25rem; font-weight: 600;">#{{ $customer->id }}</p>
                    </div>
                    <div>
                        <label class="form-label">Name</label>
                        <p style="margin-top: 0.25rem;">{{ $customer->first_name && $customer->last_name ? $customer->first_name . ' ' . $customer->last_name : $customer->name }}</p>
                    </div>
                    <div>
                        <label class="form-label">Email</label>
                        <p style="margin-top: 0.25rem;">{{ $customer->email }}</p>
                    </div>
                    <div>
                        <label class="form-label">Mobile</label>
                        <p style="margin-top: 0.25rem;">{{ $customer->phone ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="form-label">Account Status</label>
                        <p style="margin-top: 0.25rem;">
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
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Addresses -->
    @if(count($addresses) > 0)
    <div class="card" style="margin-bottom: 1.5rem; padding: 1.5rem;">
        <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem;">Addresses</h3>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            @foreach($addresses as $index => $address)
            <div style="padding: 1rem; background-color: #f9fafb; border-radius: 0.5rem; border-left: 4px solid var(--primary-blue);">
                <p style="font-weight: 600; color: #1f2937; margin-bottom: 0.5rem;">Address {{ $index + 1 }}</p>
                @if(is_array($address))
                    <p style="color: #374151;">{{ implode(', ', array_filter($address)) }}</p>
                @else
                    <p style="color: #374151;">{{ $address }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Order History -->
    <div class="card" style="margin-bottom: 1.5rem; padding: 1.5rem;">
        <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem;">Order History</h3>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr class="table-header-row">
                        <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Order ID</th>
                        <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Location</th>
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
                        <td style="padding: 0.75rem; max-width: 200px; word-break: break-word;">{{ Str::limit($order->location, 50) }}</td>
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
                        <p style="font-weight: 600; color: #1f2937;">Order #{{ $item['order_id'] }}</p>
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


