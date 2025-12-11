@extends('settlements.other')

@section('other-content')
<!-- Filters -->
<div class="card mb-6 p-4">
    <form method="GET" action="{{ route('settlements.payout-summary') }}">
        <div class="flex gap-4 flex-wrap items-end">
            <div class="flex-1-min-200">
                <label for="date_from" class="form-label">From Date</label>
                <input 
                    type="date" 
                    id="date_from" 
                    name="date_from" 
                    value="{{ $dateFrom }}" 
                    class="form-input"
                >
            </div>
            <div class="flex-1-min-200">
                <label for="date_to" class="form-label">To Date</label>
                <input 
                    type="date" 
                    id="date_to" 
                    name="date_to" 
                    value="{{ $dateTo }}" 
                    class="form-input"
                >
            </div>
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
                <a href="{{ route('settlements.payout-summary') }}" class="btn bg-gray-600 text-white ml-2">Reset</a>
            </div>
        </div>
    </form>
</div>

<!-- Payout Summary Table -->
<div class="card">
    <div class="card-section-header">
        <h3 class="card-section-title">Payout Summary</h3>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full" style="border-collapse: collapse;">
            <thead>
                <tr class="table-header-row">
                    <th class="table-cell-header">Driver</th>
                    <th class="table-cell-header">Total Settlements</th>
                    <th class="table-cell-header">Total Value</th>
                    <th class="table-cell-header">Requested</th>
                    <th class="table-cell-header">Paid</th>
                </tr>
            </thead>
            <tbody>
                @forelse($summary as $driverId => $data)
                <tr class="table-row-border">
                    <td class="table-cell-padding">
                        {{ $data['driver']->first_name && $data['driver']->last_name ? $data['driver']->first_name . ' ' . $data['driver']->last_name : $data['driver']->name }}
                    </td>
                    <td class="table-cell-padding">{{ $data['total_settlements'] }}</td>
                    <td class="table-cell-padding font-semibold">${{ number_format($data['total_value'], 2) }}</td>
                    <td class="table-cell-padding text-yellow-500">${{ number_format($data['requested'], 2) }}</td>
                    <td class="table-cell-padding stat-value-green">${{ number_format($data['paid'], 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-tertiary">No payout data found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const activeSubTab = document.querySelector('.sub-tab-link.active');
        if (activeSubTab) {
            activeSubTab.style.color = '#099ecb';
            activeSubTab.style.borderBottomColor = '#099ecb';
        }
    });
</script>
@endpush
@endsection




