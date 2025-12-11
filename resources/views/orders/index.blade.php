@extends('layouts.app')

@section('title', __('cms.orders'))

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h2 class="section-heading-lg">{{ __('cms.orders') }}</h2>
    </div>

    <!-- Filters -->
    <div class="card mb-6 p-4">
        <form method="GET" action="{{ route('orders.index') }}">
            <!-- First Row: Date Fields -->
            <div class="flex gap-4 items-end mb-4">
                <div class="flex-1-min-200">
                    <label for="date_from" class="form-label">{{ __('cms.date_from') }}</label>
                    <input 
                        type="date" 
                        id="date_from" 
                        name="date_from" 
                        value="{{ request('date_from') }}" 
                        class="form-input"
                    >
                </div>
                <div class="flex-1-min-200">
                    <label for="date_to" class="form-label">{{ __('cms.date_to') }}</label>
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
            <div class="flex gap-4 flex-wrap items-end">
                <div class="flex-1-min-200">
                    <select id="status" name="status" class="form-input">
                        <option value="">{{ __('cms.all_status') }}</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('cms.pending') }}</option>
                        <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>{{ __('cms.assigned') }}</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>{{ __('cms.failed') }}</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('cms.completed') }}</option>
                    </select>
                </div>
                <div class="flex-1-min-200">
                    <input 
                        type="text" 
                        id="location" 
                        name="location" 
                        value="{{ request('location') }}" 
                        class="form-input"
                        placeholder="{{ __('cms.search_by_location') }}"
                    >
                </div>
                <div class="flex-1-min-200">
                    <input 
                        type="text" 
                        id="customer" 
                        name="customer" 
                        value="{{ request('customer') }}" 
                        class="form-input"
                        placeholder="{{ __('cms.search_by_customer') }}"
                    >
                </div>
                <div class="flex-auto-min-200-max-250">
                    <select id="driver" name="driver" class="form-input">
                        <option value="">{{ __('cms.all_drivers') }}</option>
                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}" {{ request('driver') == $driver->id ? 'selected' : '' }}>
                                {{ $driver->first_name && $driver->last_name ? $driver->first_name . ' ' . $driver->last_name : $driver->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">{{ __('cms.filter') }}</button>
                    <a href="{{ route('orders.index') }}" class="btn bg-gray-600 text-white ml-2">{{ __('cms.reset') }}</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="overflow-x-auto">
        <table class="w-full" style="border-collapse: collapse;">
            <thead>
                <tr class="table-header-row">
                    <th class="table-cell-header">{{ __('cms.id') }}</th>
                    <th class="table-cell-header">{{ __('cms.customer') }}</th>
                    <th class="table-cell-header">{{ __('cms.location') }}</th>
                    <th class="table-cell-header">{{ __('cms.driver') }}</th>
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
                    <td class="table-cell-padding max-w-200 word-break">{{ Str::limit($order->location, 50) }}</td>
                    <td class="table-cell-padding">
                        @if($order->driver)
                            {{ $order->driver->first_name && $order->driver->last_name ? $order->driver->first_name . ' ' . $order->driver->last_name : $order->driver->name }}
                        @else
                            <span class="text-gray-400">{{ __('cms.not_assigned') }}</span>
                        @endif
                    </td>
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
                    <td colspan="8" class="p-8 text-center text-tertiary">{{ __('cms.no_orders_found') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $orders->links() }}
    </div>
</div>
@endsection

