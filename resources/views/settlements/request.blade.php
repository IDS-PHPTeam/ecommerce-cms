@extends('settlements.index')

@section('settlements-content')
<!-- Filters -->
<div class="card" style="margin-bottom: 1.5rem; padding: 1rem;">
    <form method="GET" action="{{ route('settlements.request') }}">
        <div style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: end;">
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
                <a href="{{ route('settlements.request') }}" class="btn" style="background-color: #6b7280; color: white; margin-left: 0.5rem;">Reset</a>
            </div>
        </div>
    </form>
</div>

<!-- Request Info -->
<div class="card" style="margin-bottom: 1.5rem; padding: 1rem; background-color: #eff6ff; border-left: 4px solid #3b82f6;">
    <div style="display: flex; align-items: center; gap: 0.5rem;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20" style="color: #3b82f6;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <strong>Request for all pending orders with current date time: {{ now()->format('Y-m-d H:i:s') }}</strong>
    </div>
</div>

<!-- Export Buttons -->
<div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-bottom: 1rem;">
    <a href="{{ route('settlements.export-request', ['format' => 'excel']) . '?' . http_build_query(request()->all()) }}" 
       class="btn" 
       style="background-color: #059669; color: white;">
        Export Excel
    </a>
    <a href="{{ route('settlements.export-request', ['format' => 'pdf']) . '?' . http_build_query(request()->all()) }}" 
       class="btn" 
       style="background-color: #dc2626; color: white;">
        Export PDF
    </a>
</div>

<!-- Driver Order Lists -->
@forelse($driverOrders as $driverId => $data)
<div class="card" style="margin-bottom: 1.5rem;">
    <div style="padding: 1rem; background-color: #f3f4f6; border-bottom: 1px solid #e5e7eb;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 1.25rem; font-weight: 600;">
                Driver: {{ $data['driver'] ? ($data['driver']->first_name && $data['driver']->last_name ? $data['driver']->first_name . ' ' . $data['driver']->last_name : $data['driver']->name) : 'N/A' }}
            </h3>
            <form method="POST" action="{{ route('settlements.store-request') }}" style="display: inline;">
                @csrf
                <input type="hidden" name="driver_id" value="{{ $driverId }}">
                <input type="hidden" name="value" value="{{ $data['settlement_value'] }}">
                <button type="submit" class="btn btn-primary">Create Settlement Request</button>
            </form>
        </div>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Order Number</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Commission Value</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Pending Money</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['orders'] as $orderData)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 0.75rem;">#{{ $orderData['order_number'] }}</td>
                    <td style="padding: 0.75rem;">${{ number_format($orderData['commission_value'], 2) }}</td>
                    <td style="padding: 0.75rem;">${{ number_format($orderData['pending_money'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background-color: #f9fafb; border-top: 2px solid #e5e7eb; font-weight: 600;">
                    <td style="padding: 0.75rem;">Total Commission</td>
                    <td style="padding: 0.75rem;">${{ number_format($data['total_commission'], 2) }}</td>
                    <td style="padding: 0.75rem;">${{ number_format($data['total_pending'], 2) }}</td>
                </tr>
                <tr style="background-color: #eff6ff; font-weight: 700;">
                    <td style="padding: 0.75rem;" colspan="2">Settlement Value (rounded)</td>
                    <td style="padding: 0.75rem; color: {{ $data['settlement_value'] >= 0 ? '#059669' : '#dc2626' }}">
                        ${{ number_format($data['settlement_value'], 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@empty
<div class="card" style="padding: 2rem; text-align: center; color: #6b7280;">
    No pending orders found for settlement.
</div>
@endforelse
@endsection




