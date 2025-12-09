@extends('settlements.other')

@section('other-content')
<!-- Filters -->
<div class="card" style="margin-bottom: 1.5rem; padding: 1rem;">
    <form method="GET" action="{{ route('settlements.payout-summary') }}">
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
                <a href="{{ route('settlements.payout-summary') }}" class="btn" style="background-color: #6b7280; color: white; margin-left: 0.5rem;">Reset</a>
            </div>
        </div>
    </form>
</div>

<!-- Payout Summary Table -->
<div class="card">
    <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb;">
        <h3 style="font-size: 1.25rem; font-weight: 600;">Payout Summary</h3>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f3f4f6; border-bottom: 2px solid #e5e7eb;">
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Driver</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Total Settlements</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Total Value</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Requested</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Paid</th>
                </tr>
            </thead>
            <tbody>
                @forelse($summary as $driverId => $data)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 0.75rem;">
                        {{ $data['driver']->first_name && $data['driver']->last_name ? $data['driver']->first_name . ' ' . $data['driver']->last_name : $data['driver']->name }}
                    </td>
                    <td style="padding: 0.75rem;">{{ $data['total_settlements'] }}</td>
                    <td style="padding: 0.75rem; font-weight: 600;">${{ number_format($data['total_value'], 2) }}</td>
                    <td style="padding: 0.75rem; color: #f59e0b;">${{ number_format($data['requested'], 2) }}</td>
                    <td style="padding: 0.75rem; color: #059669;">${{ number_format($data['paid'], 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 2rem; text-align: center; color: #6b7280;">No payout data found.</td>
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




