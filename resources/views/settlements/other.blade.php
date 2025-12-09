@extends('settlements.index')

@section('settlements-content')
<!-- Sub-tabs for Other section -->
<div style="border-bottom: 1px solid #e5e7eb; margin-bottom: 1.5rem;">
    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
        <a href="{{ route('settlements.discrepancy-reports') }}" 
           class="sub-tab-link {{ request()->routeIs('settlements.discrepancy-reports') ? 'active' : '' }}"
           style="padding: 0.5rem 1rem; text-decoration: none; color: #6b7280; border-bottom: 2px solid transparent; transition: all 0.2s; font-weight: 500;">
            Discrepancy Reports
        </a>
        <a href="{{ route('settlements.payout-summary') }}" 
           class="sub-tab-link {{ request()->routeIs('settlements.payout-summary') ? 'active' : '' }}"
           style="padding: 0.5rem 1rem; text-decoration: none; color: #6b7280; border-bottom: 2px solid transparent; transition: all 0.2s; font-weight: 500;">
            Payout Summary Generator
        </a>
        <a href="{{ route('settlements.commission-calculator') }}" 
           class="sub-tab-link {{ request()->routeIs('settlements.commission-calculator') ? 'active' : '' }}"
           style="padding: 0.5rem 1rem; text-decoration: none; color: #6b7280; border-bottom: 2px solid transparent; transition: all 0.2s; font-weight: 500;">
            Commission Calculator
        </a>
    </div>
</div>

@yield('other-content')
@endsection




