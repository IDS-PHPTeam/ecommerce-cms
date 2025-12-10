@extends('layouts.app')

@section('title', __('cms.settlements_finance'))

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.875rem; font-weight: 700;">{{ __('cms.settlements_finance') }}</h2>
    </div>

    <!-- Tabs -->
    <div style="border-bottom: 2px solid #e5e7eb; margin-bottom: 1.5rem;">
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="{{ route('settlements.history') }}" 
               class="tab-link {{ request()->routeIs('settlements.history') ? 'active' : '' }}"
               style="padding: 0.75rem 1.5rem; text-decoration: none; color: #6b7280; border-bottom: 2px solid transparent; transition: all 0.2s; font-weight: 500;">
                {{ __('cms.settlement_history') }}
            </a>
            <a href="{{ route('settlements.request') }}" 
               class="tab-link {{ request()->routeIs('settlements.request') ? 'active' : '' }}"
               style="padding: 0.75rem 1.5rem; text-decoration: none; color: #6b7280; border-bottom: 2px solid transparent; transition: all 0.2s; font-weight: 500;">
                {{ __('cms.settlement_request') }}
            </a>
            <a href="{{ route('settlements.discrepancy-reports') }}" 
               class="tab-link {{ request()->routeIs('settlements.discrepancy-reports') || request()->routeIs('settlements.payout-summary') || request()->routeIs('settlements.commission-calculator') ? 'active' : '' }}"
               style="padding: 0.75rem 1.5rem; text-decoration: none; color: #6b7280; border-bottom: 2px solid transparent; transition: all 0.2s; font-weight: 500;">
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

