@extends('settlements.other')

@section('other-content')
<div class="card">
    <div class="card-section-header">
        <h3 class="card-section-title">Discrepancy Reports</h3>
        <p class="text-tertiary mt-2">Find discrepancies between expected and actual settlements</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full" style="border-collapse: collapse;">
            <thead>
                <tr class="table-header-row">
                    <th class="table-cell-header">Driver</th>
                    <th class="table-cell-header">Expected Commission</th>
                    <th class="table-cell-header">Paid Settlements</th>
                    <th class="table-cell-header">Difference</th>
                </tr>
            </thead>
            <tbody>
                @forelse($discrepancies as $discrepancy)
                <tr class="table-row-border">
                    <td class="table-cell-padding">
                        {{ $discrepancy['driver']->first_name && $discrepancy['driver']->last_name ? $discrepancy['driver']->first_name . ' ' . $discrepancy['driver']->last_name : $discrepancy['driver']->name }}
                    </td>
                    <td class="table-cell-padding">${{ number_format($discrepancy['expected'], 2) }}</td>
                    <td class="table-cell-padding">${{ number_format($discrepancy['paid'], 2) }}</td>
                    <td class="table-cell-padding font-semibold" style="color: {{ $discrepancy['difference'] >= 0 ? '#059669' : '#dc2626' }}">
                        ${{ number_format($discrepancy['difference'], 2) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-8 text-center text-tertiary">No discrepancies found.</td>
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




