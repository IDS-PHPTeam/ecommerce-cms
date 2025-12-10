@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div style="max-width: 1400px; margin: 0 auto;">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h2 style="font-size: 2rem; font-weight: 700; color: #1f2937; margin-bottom: 0.25rem;">Dashboard</h2>
            <p style="color: #6b7280; font-size: 0.875rem;">Overview of your operations and performance</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="card card-padded">
        <form method="GET" action="{{ route('dashboard') }}">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
                <div>
                    <label for="date_from" class="form-label" style="display: block; margin-bottom: 0.5rem; font-size: 0.875rem; font-weight: 500;">From Date</label>
                    <input 
                        type="date" 
                        id="date_from" 
                        name="date_from" 
                        value="{{ $dateFrom }}" 
                        class="form-input"
                        style="width: 100%;"
                    >
                </div>
                <div>
                    <label for="date_to" class="form-label" style="display: block; margin-bottom: 0.5rem; font-size: 0.875rem; font-weight: 500;">To Date</label>
                    <input 
                        type="date" 
                        id="date_to" 
                        name="date_to" 
                        value="{{ $dateTo }}" 
                        class="form-input"
                        style="width: 100%;"
                    >
                </div>
                <div>
                    <label for="client" class="form-label" style="display: block; margin-bottom: 0.5rem; font-size: 0.875rem; font-weight: 500;">Client</label>
                    <input 
                        type="text" 
                        id="client" 
                        name="client" 
                        value="{{ request('client') }}" 
                        class="form-input"
                        placeholder="Search by client..."
                        style="width: 100%;"
                    >
                </div>
                <div>
                    <label for="driver" class="form-label" style="display: block; margin-bottom: 0.5rem; font-size: 0.875rem; font-weight: 500;">Driver</label>
                    <select id="driver" name="driver" class="form-input" style="width: 100%;">
                        <option value="">All Drivers</option>
                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}" {{ request('driver') == $driver->id ? 'selected' : '' }}>
                                {{ $driver->first_name && $driver->last_name ? $driver->first_name . ' ' . $driver->last_name : $driver->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">Filter</button>
                    <a href="{{ route('dashboard') }}" class="btn" style="background-color: #6b7280; color: white; padding: 0.5rem 1rem; border-radius: 0.375rem; text-decoration: none; display: inline-block;">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Main Metrics - Key KPIs -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <!-- Total Orders -->
        <div class="card" style="padding: 2rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
            <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Total Orders</div>
            <div style="font-size: 3rem; font-weight: 700; margin-bottom: 0.5rem;">{{ number_format($totalOrders) }}</div>
            <div style="font-size: 0.875rem; opacity: 0.8;">Period: {{ $dateFrom }} to {{ $dateTo }}</div>
        </div>

        <!-- Total Value -->
        <div class="card" style="padding: 2rem; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; border: none;">
            <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Total Value</div>
            <div style="font-size: 3rem; font-weight: 700; margin-bottom: 0.5rem;">${{ number_format($totalValue, 2) }}</div>
            <div style="font-size: 0.875rem; opacity: 0.8;">Commission: ${{ number_format($totalCommission, 2) }}</div>
        </div>

        <!-- Success Rate -->
        <div class="card" style="padding: 2rem; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none;">
            <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Success Rate</div>
            <div style="font-size: 3rem; font-weight: 700; margin-bottom: 0.5rem;">
                @if($totalOrders > 0)
                    {{ number_format(($successfulOrders / $totalOrders) * 100, 1) }}%
                @else
                    0%
                @endif
            </div>
            <div style="font-size: 0.875rem; opacity: 0.8;">{{ number_format($successfulOrders) }} successful orders</div>
        </div>
    </div>

    <!-- Detailed Sections -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
        <!-- Orders Overview -->
        <div class="card card-no-padding">
            <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                <h3 style="font-size: 1.25rem; font-weight: 600; color: #1f2937;">Orders Overview</h3>
            </div>
            <div style="padding: 1.5rem;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div>
                        <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.5rem;">Successful</div>
                        <div style="font-size: 2rem; font-weight: 700; color: #059669;">{{ number_format($successfulOrders) }}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.5rem;">Canceled</div>
                        <div style="font-size: 2rem; font-weight: 700; color: #dc2626;">{{ number_format($canceledOrders) }}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.5rem;">Failed</div>
                        <div style="font-size: 2rem; font-weight: 700; color: #dc2626;">{{ number_format($failedOrders) }}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.5rem;">Failed Rate</div>
                        <div style="font-size: 2rem; font-weight: 700; color: #dc2626;">{{ number_format($failedDeliveryRate, 1) }}%</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Operations Status -->
        <div class="card card-no-padding">
            <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                <h3 style="font-size: 1.25rem; font-weight: 600; color: #1f2937;">Operations Status</h3>
            </div>
            <div style="padding: 1.5rem;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div>
                        <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.5rem;">Assigned Orders</div>
                        <div style="font-size: 2rem; font-weight: 700; color: #2563eb;">{{ number_format($assignedOrders) }}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.5rem;">Unassigned Orders</div>
                        <div style="font-size: 2rem; font-weight: 700; color: #f59e0b;">{{ number_format($notAssignedOrders) }}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.5rem;">Active Drivers</div>
                        <div style="font-size: 2rem; font-weight: 700; color: #10b981;">{{ number_format($activeDrivers) }}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.5rem;">Total Drivers</div>
                        <div style="font-size: 2rem; font-weight: 700; color: #1f2937;">{{ number_format($totalDrivers) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="card card-no-padding" style="margin-bottom: 2rem;">
        <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: #1f2937;">Performance Metrics</h3>
        </div>
        <div style="padding: 1.5rem;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem;">
                <div>
                    <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.5rem;">Average Delivery Time</div>
                    <div style="font-size: 1.75rem; font-weight: 700; color: #1f2937;">{{ $averageDeliveryTime }} min</div>
                </div>
                <div>
                    <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.5rem;">Total Commission</div>
                    <div style="font-size: 1.75rem; font-weight: 700; color: #1f2937;">${{ number_format($totalCommission, 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Section -->
    <div class="card card-no-padding">
        <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: #1f2937;">Live Map</h3>
        </div>
        <div style="padding: 1.5rem;">
            <div id="map" style="width: 100%; height: 500px; background-color: #f3f4f6; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; color: #6b7280;">
                <div style="text-align: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="48" height="48" style="margin: 0 auto 1rem; color: #9ca3af;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                    <p style="font-weight: 500; margin-bottom: 0.5rem;">Map integration required</p>
                    <p style="font-size: 0.875rem; margin-top: 0.5rem; color: #9ca3af;">Driver locations: {{ $mapDrivers->count() }}</p>
                    <p style="font-size: 0.875rem; color: #9ca3af;">Order locations: {{ $mapOrders->count() }}</p>
                </div>
            </div>
            
            <!-- Map Legend -->
            <div style="display: flex; gap: 2rem; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div style="width: 16px; height: 16px; background-color: #3b82f6; border-radius: 50%;"></div>
                    <span style="font-size: 0.875rem; color: #374151;">Driver Locations ({{ $mapDrivers->count() }})</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div style="width: 16px; height: 16px; background-color: #10b981; border-radius: 50%;"></div>
                    <span style="font-size: 0.875rem; color: #374151;">Assigned Orders ({{ $mapOrders->where('status', 'assigned')->count() }})</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div style="width: 16px; height: 16px; background-color: #f59e0b; border-radius: 50%;"></div>
                    <span style="font-size: 0.875rem; color: #374151;">Unassigned Orders ({{ $mapOrders->where('status', 'pending')->count() }})</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div style="width: 16px; height: 16px; background-color: #ef4444; border-radius: 50%;"></div>
                    <span style="font-size: 0.875rem; color: #374151;">Failed/Delays ({{ $mapOrders->where('status', 'failed')->count() }})</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

