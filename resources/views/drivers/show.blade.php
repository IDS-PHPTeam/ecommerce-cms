@extends('layouts.app')

@section('title', __('cms.driver_details'))

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h2 class="section-heading-lg">{{ __('cms.driver_details') }}</h2>
        <a href="{{ route('drivers.index') }}" class="btn bg-gray-600 text-white">{{ __('cms.back_to_list') }}</a>
    </div>

    <!-- Driver Info -->
    <div class="card mb-6 p-6">
        <div class="flex gap-8 items-start">
            @if($driver->profile_image)
            <div class="flex-shrink-0">
                <img src="{{ asset('storage/' . $driver->profile_image) }}" alt="Driver Profile" class="w-150 h-150 rounded-full border-3 border-primary-blue object-cover">
            </div>
            @else
            <div class="flex-shrink-0 w-150 h-150 rounded-full bg-gray-200 flex-center border-3 border-primary-blue">
                <span class="text-5xl text-gray-400">{{ strtoupper(substr($driver->first_name ?? $driver->name, 0, 1)) }}</span>
            </div>
            @endif
            <div class="flex-1">
                <h3 class="section-heading mb-4">{{ __('cms.driver_information') }}</h3>
                <div class="grid grid-auto-250 gap-4">
                    <div>
                        <label class="form-label">{{ __('cms.id') }}</label>
                        <p class="mt-1">{{ $driver->id }}</p>
                    </div>
                    <div>
                        <label class="form-label">{{ __('cms.name') }}</label>
                        <p class="mt-1">{{ $driver->first_name && $driver->last_name ? $driver->first_name . ' ' . $driver->last_name : $driver->name }}</p>
                    </div>
                    <div>
                        <label class="form-label">{{ __('cms.email') }}</label>
                        <p class="mt-1">{{ $driver->email }}</p>
                    </div>
                    <div>
                        <label class="form-label">{{ __('cms.status') }}</label>
                        <p class="mt-1">
                            <span class="badge {{ $driver->driver_status == 'active' ? 'badge-green' : 'badge-red' }}">
                                {{ ucfirst($driver->driver_status ?? __('cms.na')) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="form-label">{{ __('cms.load_capacity') }}</label>
                        <p class="mt-1">{{ $driver->load_capacity ?? __('cms.na') }} kg</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="card mb-6 p-6">
        <h3 class="section-heading mb-4">{{ __('cms.driver_performance') }}</h3>
        <div class="grid grid-auto-200 gap-4">
            <div class="text-center p-4 bg-gray-100 rounded-md">
                <p class="stat-label">{{ __('cms.total_orders') }}</p>
                <p class="stat-value-lg text-primary">{{ $totalOrders }}</p>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-md">
                <p class="stat-label text-green-700">{{ __('cms.completed') }}</p>
                <p class="stat-value-lg stat-value-green">{{ $completedOrders }}</p>
            </div>
            <div class="text-center p-4 bg-red-50 rounded-md">
                <p class="stat-label text-red-700">{{ __('cms.failed') }}</p>
                <p class="stat-value-lg stat-value-red">{{ $failedOrders }}</p>
            </div>
            <div class="text-center p-4 bg-blue-50 rounded-md">
                <p class="stat-label text-blue-700">{{ __('cms.success_rate') }}</p>
                <p class="stat-value-lg stat-value-blue">{{ $successRate }}%</p>
            </div>
            <div class="text-center p-4 bg-yellow-50 rounded-md">
                <p class="stat-label text-yellow-700">{{ __('cms.average_rating') }}</p>
                <p class="stat-value-lg stat-value-yellow">★ {{ number_format($averageRating, 1) }}</p>
            </div>
        </div>
    </div>

    <!-- Order History -->
    <div class="card mb-6 p-6">
        <h3 class="section-heading mb-4">{{ __('cms.order_history') }}</h3>
        <div class="overflow-x-auto">
            <table class="w-full" style="border-collapse: collapse;">
                <thead>
                    <tr class="table-header-row">
                        <th class="table-cell-header">{{ __('cms.order_id') }}</th>
                        <th class="table-cell-header">{{ __('cms.customer') }}</th>
                        <th class="table-cell-header">{{ __('cms.total') }}</th>
                        <th class="table-cell-header">{{ __('cms.status') }}</th>
                        <th class="table-cell-header">{{ __('cms.order_date') }}</th>
                        <th class="table-cell-header">{{ __('cms.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr class="table-row-border">
                        <td class="table-cell-padding">{{ $order->id }}</td>
                        <td class="table-cell-padding">{{ $order->customer_name }}</td>
                        <td class="table-cell-padding">${{ number_format($order->total, 2) }}</td>
                        <td class="table-cell-padding">
                            @php
                                $statusBadgeClass = $order->status == 'completed' ? 'badge-green' : 
                                                   ($order->status == 'failed' ? 'badge-red' : 
                                                   ($order->status == 'assigned' ? 'badge-blue' : 'badge-yellow'));
                                $statusTranslations = [
                                    'pending' => __('cms.pending'),
                                    'assigned' => __('cms.assigned'),
                                    'failed' => __('cms.failed'),
                                    'completed' => __('cms.completed'),
                                ];
                            @endphp
                            <span class="badge {{ $statusBadgeClass }}">
                                {{ $statusTranslations[$order->status] ?? ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="table-cell-padding">{{ $order->order_date->format('M d, Y') }}</td>
                        <td class="table-cell-padding">
                            <a href="{{ route('orders.show', $order) }}" class="action-btn action-btn-edit" title="{{ __('cms.view') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-tertiary">{{ __('cms.no_orders_found') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>

    <!-- Feedback & Ratings -->
    @if($feedback->count() > 0)
    <div class="card p-6">
        <h3 class="section-heading mb-4">{{ __('cms.feedback_ratings') }}</h3>
        <div class="flex-col gap-4">
            @foreach($feedback as $item)
            <div class="p-4 bg-gray-50 rounded-md border-l-4 border-primary-blue">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <p class="font-semibold text-primary">{{ __('cms.order') }} #{{ $item['order_id'] }} - {{ $item['customer'] }}</p>
                        <p class="text-sm text-tertiary">{{ \Carbon\Carbon::parse($item['date'])->format('M d, Y') }}</p>
                    </div>
                    @if($item['rating'])
                    <span class="text-yellow-500 font-semibold">★ {{ $item['rating'] }}/5</span>
                    @endif
                </div>
                @if($item['feedback'])
                <p class="text-gray-700 mt-2">{{ $item['feedback'] }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection


