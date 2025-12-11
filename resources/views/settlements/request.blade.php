@extends('settlements.index')

@section('settlements-content')
<!-- Filters -->
<div class="card mb-6 p-4">
    <form method="GET" action="{{ route('settlements.request') }}">
        <div class="flex gap-4 flex-wrap items-end">
            <div class="flex-1-min-200">
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
                <a href="{{ route('settlements.request') }}" class="btn bg-gray-600 text-white ml-2">Reset</a>
            </div>
        </div>
    </form>
</div>

<!-- Request Info -->
<div class="card mb-6 p-4" style="background-color: #eff6ff; border-left: 4px solid #3b82f6;">
    <div class="flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20" style="color: #3b82f6;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <strong>Request for all pending orders with current date time: {{ now()->format('Y-m-d H:i:s') }}</strong>
    </div>
</div>

<!-- Export Buttons -->
<div class="flex justify-end gap-2 mb-4">
    <a href="{{ route('settlements.export-request', ['format' => 'excel']) . '?' . http_build_query(request()->all()) }}" 
       class="btn text-white" style="background-color: #059669;">
        Export Excel
    </a>
    <a href="{{ route('settlements.export-request', ['format' => 'pdf']) . '?' . http_build_query(request()->all()) }}" 
       class="btn bg-red-500 text-white">
        Export PDF
    </a>
</div>

<!-- Driver Order Lists -->
@forelse($driverOrders as $driverId => $data)
<div class="card mb-6">
    <div class="p-4 bg-gray-100 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h3 class="section-heading mb-0">
                Driver: {{ $data['driver'] ? ($data['driver']->first_name && $data['driver']->last_name ? $data['driver']->first_name . ' ' . $data['driver']->last_name : $data['driver']->name) : 'N/A' }}
            </h3>
            <form method="POST" action="{{ route('settlements.store-request') }}" class="d-inline">
                @csrf
                <input type="hidden" name="driver_id" value="{{ $driverId }}">
                <input type="hidden" name="value" value="{{ $data['settlement_value'] }}">
                <button type="submit" class="btn btn-primary">Create Settlement Request</button>
            </form>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full" style="border-collapse: collapse;">
            <thead>
                <tr class="table-header-row">
                    <th class="table-cell-header">Order Number</th>
                    <th class="table-cell-header">Commission Value</th>
                    <th class="table-cell-header">Pending Money</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['orders'] as $orderData)
                <tr class="table-row-border">
                    <td class="table-cell-padding">#{{ $orderData['order_number'] }}</td>
                    <td class="table-cell-padding">${{ number_format($orderData['commission_value'], 2) }}</td>
                    <td class="table-cell-padding">${{ number_format($orderData['pending_money'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-gray-50 border-t-2 border-gray-200 font-semibold">
                    <td class="table-cell-padding">Total Commission</td>
                    <td class="table-cell-padding">${{ number_format($data['total_commission'], 2) }}</td>
                    <td class="table-cell-padding">${{ number_format($data['total_pending'], 2) }}</td>
                </tr>
                <tr class="font-bold" style="background-color: #eff6ff;">
                    <td class="table-cell-padding" colspan="2">Settlement Value (rounded)</td>
                    <td class="table-cell-padding" style="color: {{ $data['settlement_value'] >= 0 ? '#059669' : '#dc2626' }}">
                        ${{ number_format($data['settlement_value'], 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@empty
<div class="card card-padding-lg text-center text-tertiary">
    No pending orders found for settlement.
</div>
@endforelse
@endsection




