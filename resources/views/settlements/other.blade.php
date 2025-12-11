@extends('settlements.index')

@section('settlements-content')
<!-- Sub-tabs for Other section -->
<div class="border-b border-gray-200 mb-6">
    <div class="flex gap-2 flex-wrap">
        <a href="{{ route('settlements.discrepancy-reports') }}" 
           class="sub-tab-link no-underline text-tertiary border-b-2 border-b-transparent font-medium p-2 transition-all {{ request()->routeIs('settlements.discrepancy-reports') ? 'active' : '' }}">
            Discrepancy Reports
        </a>
        <a href="{{ route('settlements.payout-summary') }}" 
           class="sub-tab-link no-underline text-tertiary border-b-2 border-b-transparent font-medium p-2 transition-all {{ request()->routeIs('settlements.payout-summary') ? 'active' : '' }}">
            Payout Summary Generator
        </a>
        <a href="{{ route('settlements.commission-calculator') }}" 
           class="sub-tab-link no-underline text-tertiary border-b-2 border-b-transparent font-medium p-2 transition-all {{ request()->routeIs('settlements.commission-calculator') ? 'active' : '' }}">
            Commission Calculator
        </a>
    </div>
</div>

@yield('other-content')
@endsection




