@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.875rem; font-weight: 700;">Dashboard</h2>
    </div>

    <!-- Filters -->
    <div class="card" style="margin-bottom: 1.5rem; padding: 1rem;">
        <form method="GET" action="{{ route('dashboard') }}">
            <div style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: end;">
                <div style="flex: 1; min-width: 200px;">
                    <label for="date_from" class="form-label">From Date</label>
                    <input 
                        type="date" 
                        id="date_from" 
                        name="date_from" 
                        value="{{ $dateFrom }}" 
                        class="form-input"
                    >
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <label for="date_to" class="form-label">To Date</label>
                    <input 
                        type="date" 
                        id="date_to" 
                        name="date_to" 
                        value="{{ $dateTo }}" 
                        class="form-input"
                    >
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <label for="client" class="form-label">Client</label>
                    <input 
                        type="text" 
                        id="client" 
                        name="client" 
                        value="{{ request('client') }}" 
                        class="form-input"
                        placeholder="Search by client..."
                    >
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <label for="driver" class="form-label">Driver</label>
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
                    <a href="{{ route('dashboard') }}" class="btn" style="background-color: #6b7280; color: white; margin-left: 0.5rem;">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <!-- KPIs -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
        <div class="card" style="padding: 1.5rem;">
            <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.5rem;">Total Orders</div>
            <div style="font-size: 2rem; font-weight: 700; color: #1f2937;">{{ number_format($totalOrders) }}</div>
        </div>
        <div class="card" style="padding: 1.5rem;">
            <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.5rem;">Successful</div>
            <div style="font-size: 2rem; font-weight: 700; color: #059669;">{{ number_format($successfulOrders) }}</div>
        </div>
        <div class="card" style="padding: 1.5rem;">
            <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.5rem;">Canceled</div>
            <div style="font-size: 2rem; font-weight: 700; color: #dc2626;">{{ number_format($canceledOrders) }}</div>
        </div>
        <div class="card" style="padding: 1.5rem;">
            <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.5rem;">Failed</div>
            <div style="font-size: 2rem; font-weight: 700; color: #dc2626;">{{ number_format($failedOrders) }}</div>
        </div>
        <div class="card" style="padding: 1.5rem;">
            <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.5rem;">Total Value</div>
            <div style="font-size: 2rem; font-weight: 700; color: #1f2937;">${{ number_format($totalValue, 2) }}</div>
        </div>
        <div class="card" style="padding: 1.5rem;">
            <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.5rem;">Total Commission</div>
            <div style="font-size: 2rem; font-weight: 700; color: #1f2937;">${{ number_format($totalCommission, 2) }}</div>
        </div>
        <div class="card" style="padding: 1.5rem;">
            <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.5rem;">Current Orders - Assigned</div>
            <div style="font-size: 2rem; font-weight: 700; color: #2563eb;">{{ number_format($assignedOrders) }}</div>
        </div>
        <div class="card" style="padding: 1.5rem;">
            <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.5rem;">Current Orders - Not Assigned</div>
            <div style="font-size: 2rem; font-weight: 700; color: #f59e0b;">{{ number_format($notAssignedOrders) }}</div>
        </div>
        <div class="card" style="padding: 1.5rem;">
            <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.5rem;">Active Drivers / Total Drivers</div>
            <div style="font-size: 2rem; font-weight: 700; color: #1f2937;">{{ number_format($activeDrivers) }} / {{ number_format($totalDrivers) }}</div>
        </div>
        <div class="card" style="padding: 1.5rem;">
            <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.5rem;">Average Delivery Time</div>
            <div style="font-size: 2rem; font-weight: 700; color: #1f2937;">{{ $averageDeliveryTime }} min</div>
        </div>
        <div class="card" style="padding: 1.5rem;">
            <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.5rem;">Failed Delivery Rate</div>
            <div style="font-size: 2rem; font-weight: 700; color: #dc2626;">{{ number_format($failedDeliveryRate, 2) }}%</div>
        </div>
    </div>

    <!-- Map Section -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb;">
            <h3 style="font-size: 1.25rem; font-weight: 600;">Live Map</h3>
        </div>
        <div style="padding: 1rem;">
            <div id="map" style="width: 100%; height: 500px; background-color: #f3f4f6; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; color: #6b7280;">
                <div style="text-align: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="48" height="48" style="margin: 0 auto 1rem; color: #9ca3af;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                    <p>Map integration required</p>
                    <p style="font-size: 0.875rem; margin-top: 0.5rem;">Driver locations: {{ $mapDrivers->count() }}</p>
                    <p style="font-size: 0.875rem;">Order locations: {{ $mapOrders->count() }}</p>
                </div>
            </div>
            
            <!-- Map Legend -->
            <div style="display: flex; gap: 2rem; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e5e7eb;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div style="width: 16px; height: 16px; background-color: #3b82f6; border-radius: 50%;"></div>
                    <span style="font-size: 0.875rem;">Driver Locations ({{ $mapDrivers->count() }})</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div style="width: 16px; height: 16px; background-color: #10b981; border-radius: 50%;"></div>
                    <span style="font-size: 0.875rem;">Assigned Orders ({{ $mapOrders->where('status', 'assigned')->count() }})</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div style="width: 16px; height: 16px; background-color: #f59e0b; border-radius: 50%;"></div>
                    <span style="font-size: 0.875rem;">Unassigned Orders ({{ $mapOrders->where('status', 'pending')->count() }})</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div style="width: 16px; height: 16px; background-color: #ef4444; border-radius: 50%;"></div>
                    <span style="font-size: 0.875rem;">Failed/Delays ({{ $mapOrders->where('status', 'failed')->count() }})</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

