@extends('layouts.app')

@section('title', __('cms.settlements_finance'))

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h2 class="section-heading-lg">{{ __('cms.settlements_finance') }}</h2>
    </div>

    <!-- Tabs -->
    <div class="border-b-2 border-gray-200 mb-6">
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('settlements.history') }}" 
               class="tab-link no-underline text-tertiary border-b-2 border-b-transparent font-medium {{ request()->routeIs('settlements.history') ? 'active' : '' }}"
               style="padding: 0.75rem 1.5rem; transition: all 0.2s;">
                {{ __('cms.settlement_history') }}
            </a>
            <a href="{{ route('settlements.request') }}" 
               class="tab-link no-underline text-tertiary border-b-2 border-b-transparent font-medium {{ request()->routeIs('settlements.request') ? 'active' : '' }}"
               style="padding: 0.75rem 1.5rem; transition: all 0.2s;">
                {{ __('cms.settlement_request') }}
            </a>
            <a href="{{ route('settlements.discrepancy-reports') }}" 
               class="tab-link no-underline text-tertiary border-b-2 border-b-transparent font-medium {{ request()->routeIs('settlements.discrepancy-reports') || request()->routeIs('settlements.payout-summary') || request()->routeIs('settlements.commission-calculator') ? 'active' : '' }}"
               style="padding: 0.75rem 1.5rem; transition: all 0.2s;">
                {{ __('cms.other') }}
            </a>
        </div>
    </div>

    @yield('settlements-content')
</div>

@push('scripts')
<script>
    // Add active tab styling
    document.addEventListener('DOMContentLoaded', function() {
        const activeTab = document.querySelector('.tab-link.active');
        if (activeTab) {
            activeTab.style.color = '#099ecb';
            activeTab.style.borderBottomColor = '#099ecb';
        }
    });
</script>
@endpush
@endsection

