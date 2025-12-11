@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-1400 m-auto">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="section-heading-xl mb-1">Dashboard</h2>
            <p class="text-tertiary text-sm">Overview of your operations and performance</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="card card-padded">
        <form method="GET" action="{{ route('dashboard') }}">
            <div class="grid grid-auto-250 gap-4 items-end">
                <div>
                    <label for="date_from" class="form-label input-label-small">From Date</label>
                    <input 
                        type="date" 
                        id="date_from" 
                        name="date_from" 
                        value="{{ $dateFrom }}" 
                        class="form-input w-full"
                    >
                </div>
                <div>
                    <label for="date_to" class="form-label input-label-small">To Date</label>
                    <input 
                        type="date" 
                        id="date_to" 
                        name="date_to" 
                        value="{{ $dateTo }}" 
                        class="form-input w-full"
                    >
                </div>
                <div>
                    <label for="client" class="form-label input-label-small">Client</label>
                    <input 
                        type="text" 
                        id="client" 
                        name="client" 
                        value="{{ request('client') }}" 
                        class="form-input w-full"
                        placeholder="Search by client..."
                    >
                </div>
                <div>
                    <label for="driver" class="form-label input-label-small">Driver</label>
                    <select id="driver" name="driver" class="form-input w-full">
                        <option value="">All Drivers</option>
                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}" {{ request('driver') == $driver->id ? 'selected' : '' }}>
                                {{ $driver->first_name && $driver->last_name ? $driver->first_name . ' ' . $driver->last_name : $driver->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary flex-1">Filter</button>
                    <a href="{{ route('dashboard') }}" class="btn bg-gray-600 text-white px-4 py-2 rounded text-base no-underline d-inline-block">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Main Metrics - Key KPIs -->
    <div class="dashboard-grid-metrics">
        <!-- Total Orders -->
        <div class="card metric-card metric-card-purple">
            <div class="metric-label">Total Orders</div>
            <div class="metric-value">{{ number_format($totalOrders) }}</div>
            <div class="metric-subtext">Period: {{ $dateFrom }} to {{ $dateTo }}</div>
        </div>

        <!-- Total Value -->
        <div class="card metric-card metric-card-blue">
            <div class="metric-label">Total Value</div>
            <div class="metric-value">${{ number_format($totalValue, 2) }}</div>
            <div class="metric-subtext">Commission: ${{ number_format($totalCommission, 2) }}</div>
        </div>

        <!-- Success Rate -->
        <div class="card metric-card metric-card-green">
            <div class="metric-label">Success Rate</div>
            <div class="metric-value">
                @if($totalOrders > 0)
                    {{ number_format(($successfulOrders / $totalOrders) * 100, 1) }}%
                @else
                    0%
                @endif
            </div>
            <div class="metric-subtext">{{ number_format($successfulOrders) }} successful orders</div>
        </div>
    </div>

    <!-- Detailed Sections -->
    <div class="dashboard-grid-2">
        <!-- Orders Overview -->
        <div class="card card-no-padding">
            <div class="card-section-header">
                <h3 class="card-section-title">Orders Overview</h3>
            </div>
            <div class="card-section-body">
                <div class="dashboard-grid-inner">
                    <div>
                        <div class="stat-label">Successful</div>
                        <div class="stat-value-lg stat-value-green">{{ number_format($successfulOrders) }}</div>
                    </div>
                    <div>
                        <div class="stat-label">Canceled</div>
                        <div class="stat-value-lg stat-value-red">{{ number_format($canceledOrders) }}</div>
                    </div>
                    <div>
                        <div class="stat-label">Failed</div>
                        <div class="stat-value-lg stat-value-red">{{ number_format($failedOrders) }}</div>
                    </div>
                    <div>
                        <div class="stat-label">Failed Rate</div>
                        <div class="stat-value-lg stat-value-red">{{ number_format($failedDeliveryRate, 1) }}%</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Operations Status -->
        <div class="card card-no-padding">
            <div class="card-section-header">
                <h3 class="card-section-title">Operations Status</h3>
            </div>
            <div class="card-section-body">
                <div class="dashboard-grid-inner">
                    <div>
                        <div class="stat-label">Assigned Orders</div>
                        <div class="stat-value-lg text-blue-600">{{ number_format($assignedOrders) }}</div>
                    </div>
                    <div>
                        <div class="stat-label">Unassigned Orders</div>
                        <div class="stat-value-lg stat-value-yellow">{{ number_format($notAssignedOrders) }}</div>
                    </div>
                    <div>
                        <div class="stat-label">Active Drivers</div>
                        <div class="stat-value-lg stat-value-emerald">{{ number_format($activeDrivers) }}</div>
                    </div>
                    <div>
                        <div class="stat-label">Total Drivers</div>
                        <div class="stat-value-lg text-primary">{{ number_format($totalDrivers) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="card card-no-padding mb-8">
        <div class="card-section-header">
            <h3 class="card-section-title">Performance Metrics</h3>
        </div>
        <div class="card-section-body">
            <div class="grid grid-auto-250 gap-8">
                <div>
                    <div class="stat-label">Average Delivery Time</div>
                    <div class="stat-value-xl">{{ $averageDeliveryTime }} min</div>
                </div>
                <div>
                    <div class="stat-label">Total Commission</div>
                    <div class="stat-value-xl">${{ number_format($totalCommission, 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Section -->
    <div class="card card-no-padding">
        <div class="card-section-header">
            <h3 class="card-section-title">Live Map</h3>
        </div>
        <div class="card-section-body">
            <div id="map" class="w-full bg-gray-100 rounded-md flex items-center justify-center text-tertiary h-500">
                <div class="text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="48" height="48" class="m-auto mb-4 text-quaternary">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                    <p class="font-medium mb-2">Map integration required</p>
                    <p class="text-sm mt-2 text-quaternary">Driver locations: {{ $mapDrivers->count() }}</p>
                    <p class="text-sm text-quaternary">Order locations: {{ $mapOrders->count() }}</p>
                </div>
            </div>
            
            <!-- Map Legend -->
            <div class="flex gap-8 mt-6 pt-6 border-t border-gray-200 flex-wrap">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-full bg-blue-500"></div>
                    <span class="text-sm text-secondary">Driver Locations ({{ $mapDrivers->count() }})</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-full bg-green-500"></div>
                    <span class="text-sm text-secondary">Assigned Orders ({{ $mapOrders->where('status', 'assigned')->count() }})</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-full bg-yellow-500"></div>
                    <span class="text-sm text-secondary">Unassigned Orders ({{ $mapOrders->where('status', 'pending')->count() }})</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-full bg-red-500"></div>
                    <span class="text-sm text-secondary">Failed/Delays ({{ $mapOrders->where('status', 'failed')->count() }})</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

