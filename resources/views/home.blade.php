@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="card">
    <h2 style="margin-bottom: 1rem; font-size: 1.875rem; font-weight: 700;">Dashboard</h2>
    <p style="color: #6b7280; margin-bottom: 1.5rem;">Welcome to the admin dashboard!</p>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
        <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem;">Users</h3>
            <p style="font-size: 2rem; font-weight: 700;">{{ \App\Models\User::count() }}</p>
        </div>
        
        <div class="card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
            <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem;">Products</h3>
            <p style="font-size: 2rem; font-weight: 700;">{{ $productsCount ?? 0 }}</p>
        </div>
        
        <div class="card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
            <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem;">Orders</h3>
            <p style="font-size: 2rem; font-weight: 700;">0</p>
        </div>
    </div>
</div>
@endsection

