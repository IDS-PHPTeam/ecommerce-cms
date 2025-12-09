@extends('settlements.other')

@section('other-content')
<div class="card">
    <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb;">
        <h3 style="font-size: 1.25rem; font-weight: 600;">Discrepancy Reports</h3>
        <p style="color: #6b7280; margin-top: 0.5rem;">Find discrepancies between expected and actual settlements</p>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f3f4f6; border-bottom: 2px solid #e5e7eb;">
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Driver</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Expected Commission</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Paid Settlements</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Difference</th>
                </tr>
            </thead>
            <tbody>
                @forelse($discrepancies as $discrepancy)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 0.75rem;">
                        {{ $discrepancy['driver']->first_name && $discrepancy['driver']->last_name ? $discrepancy['driver']->first_name . ' ' . $discrepancy['driver']->last_name : $discrepancy['driver']->name }}
                    </td>
                    <td style="padding: 0.75rem;">${{ number_format($discrepancy['expected'], 2) }}</td>
                    <td style="padding: 0.75rem;">${{ number_format($discrepancy['paid'], 2) }}</td>
                    <td style="padding: 0.75rem; font-weight: 600; color: {{ $discrepancy['difference'] >= 0 ? '#059669' : '#dc2626' }}">
                        ${{ number_format($discrepancy['difference'], 2) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding: 2rem; text-align: center; color: #6b7280;">No discrepancies found.</td>
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




