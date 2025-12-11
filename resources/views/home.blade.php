@extends('layouts.app')

@section('title', __('cms.dashboard'))

@section('content')
<div class="card">
    <h2 class="section-heading-lg mb-4">{{ __('cms.dashboard') }}</h2>
    <p class="text-tertiary mb-6">{{ __('cms.dashboard') }}</p>
    
    <div class="dashboard-grid-metrics mt-8">
        <div class="card metric-card metric-card-purple">
            <h3 class="metric-label">{{ __('cms.admins') }}</h3>
            <p class="metric-value">{{ \App\Models\User::count() }}</p>
        </div>
        
        <div class="card metric-card metric-card-pink">
            <h3 class="metric-label">{{ __('cms.products') }}</h3>
            <p class="metric-value">{{ $productsCount ?? 0 }}</p>
        </div>
        
        <div class="card metric-card metric-card-blue">
            <h3 class="metric-label">{{ __('cms.orders') }}</h3>
            <p class="metric-value">0</p>
        </div>
    </div>
</div>
@endsection

