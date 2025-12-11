@extends('settlements.index')

@section('settlements-content')
<!-- Filters -->
<div class="card mb-6 p-4">
    <form method="GET" action="{{ route('dashboard') }}">
        <div class="flex gap-4 flex-wrap items-end">
            <div class="flex-1-min-200">
                <label for="date_from" class="form-label">{{ __('cms.from_date') }}</label>
                <input 
                    type="date" 
                    id="date_from" 
                    name="date_from" 
                    value="{{ $dateFrom }}" 
                    class="form-input"
                >
            </div>
            <div class="flex-1-min-200">
                <label for="date_to" class="form-label">{{ __('cms.to_date') }}</label>
                <input 
                    type="date" 
                    id="date_to" 
                    name="date_to" 
                    value="{{ $dateTo }}" 
                    class="form-input"
                >
            </div>
            <div class="flex-1-min-200">
                <label for="client" class="form-label">{{ __('cms.client') }}</label>
                <input 
                    type="text" 
                    id="client" 
                    name="client" 
                    value="{{ request('client') }}" 
                    class="form-input"
                    placeholder="{{ __('cms.search_by_client') }}"
                >
            </div>
            <div class="flex-1-min-200">
                <label for="driver" class="form-label">{{ __('cms.driver') }}</label>
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
                <a href="{{ route('dashboard') }}" class="btn bg-gray-600 text-white ml-2">{{ __('cms.reset') }}</a>
            </div>
        </div>
    </form>
</div>

<!-- KPIs -->
<div class="dashboard-grid-metrics mb-6">
    <div class="card p-6">
        <div class="stat-label">{{ __('cms.total_orders') }}</div>
        <div class="stat-value-lg text-primary">{{ number_format($totalOrders) }}</div>
    </div>
    <div class="card p-6">
        <div class="stat-label">{{ __('cms.successful') }}</div>
        <div class="stat-value-lg stat-value-green">{{ number_format($successfulOrders) }}</div>
    </div>
    <div class="card p-6">
        <div class="stat-label">{{ __('cms.canceled') }}</div>
        <div class="stat-value-lg stat-value-red">{{ number_format($canceledOrders) }}</div>
    </div>
    <div class="card p-6">
        <div class="stat-label">{{ __('cms.failed') }}</div>
        <div class="stat-value-lg stat-value-red">{{ number_format($failedOrders) }}</div>
    </div>
    <div class="card p-6">
        <div class="stat-label">{{ __('cms.total_value') }}</div>
        <div class="stat-value-lg text-primary">${{ number_format($totalValue, 2) }}</div>
    </div>
    <div class="card p-6">
        <div class="stat-label">{{ __('cms.total_commission') }}</div>
        <div class="stat-value-lg text-primary">${{ number_format($totalCommission, 2) }}</div>
    </div>
    <div class="card p-6">
        <div class="stat-label">{{ __('cms.current_orders_assigned') }}</div>
        <div class="stat-value-lg stat-value-blue">{{ number_format($assignedOrders) }}</div>
    </div>
    <div class="card p-6">
        <div class="stat-label">{{ __('cms.current_orders_not_assigned') }}</div>
        <div class="stat-value-lg stat-value-yellow">{{ number_format($notAssignedOrders) }}</div>
    </div>
    <div class="card p-6">
        <div class="stat-label">{{ __('cms.active_drivers_total_drivers') }}</div>
        <div class="stat-value-lg text-primary">{{ number_format($activeDrivers) }} / {{ number_format($totalDrivers) }}</div>
    </div>
    <div class="card p-6">
        <div class="stat-label">{{ __('cms.average_delivery_time') }}</div>
        <div class="stat-value-lg text-primary">{{ $averageDeliveryTime }} {{ __('cms.min') }}</div>
    </div>
    <div class="card p-6">
        <div class="stat-label">{{ __('cms.failed_delivery_rate') }}</div>
        <div class="stat-value-lg stat-value-red">{{ number_format($failedDeliveryRate, 2) }}%</div>
    </div>
</div>

<!-- Map Section -->
<div class="card mb-6">
    <div class="card-section-header">
        <h3 class="card-section-title">{{ __('cms.live_map') }}</h3>
    </div>
    <div class="card-section-body">
        <div id="map" class="map-container">
            <div class="text-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="48" height="48" class="mx-auto mb-4 text-gray-400">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                </svg>
                <p>{{ __('cms.map_integration_required') }}</p>
                <p class="text-sm mt-2">{{ __('cms.driver_locations') }}: {{ $mapDrivers->count() }}</p>
                <p class="text-sm">{{ __('cms.order_locations') }}: {{ $mapOrders->count() }}</p>
            </div>
        </div>
        
        <!-- Map Legend -->
        <div class="flex gap-8 mt-4 pt-4 border-t border-gray-200">
            <div class="flex-center-y gap-2">
                <div class="w-4 h-4 bg-blue-500 rounded-full"></div>
                <span class="text-sm">{{ __('cms.driver_locations') }} ({{ $mapDrivers->count() }})</span>
            </div>
            <div class="flex-center-y gap-2">
                <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                <span class="text-sm">{{ __('cms.assigned_orders') }} ({{ $mapOrders->where('status', 'assigned')->count() }})</span>
            </div>
            <div class="flex-center-y gap-2">
                <div class="w-4 h-4 bg-yellow-500 rounded-full"></div>
                <span class="text-sm">{{ __('cms.unassigned_orders') }} ({{ $mapOrders->where('status', 'pending')->count() }})</span>
            </div>
            <div class="flex-center-y gap-2">
                <div class="w-4 h-4 bg-red-500 rounded-full"></div>
                <span class="text-sm">{{ __('cms.failed_delays') }} ({{ $mapOrders->where('status', 'failed')->count() }})</span>
            </div>
        </div>
    </div>
</div>
@endsection

