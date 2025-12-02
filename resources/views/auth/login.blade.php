@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="login-container">
    <div class="login-card">
        <div class="login-logo-container">
            <img src="{{ asset('images/logo.png') }}" alt="تنور العصر" class="login-logo">
        </div>
        
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus
                    class="form-input"
                    placeholder="admin@admin.com"
                >
                @error('email')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required
                    class="form-input"
                    placeholder="Enter your password"
                >
                @error('password')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-checkbox-group">
                <input 
                    type="checkbox" 
                    id="remember" 
                    name="remember"
                    class="form-checkbox"
                >
                <label for="remember" class="form-checkbox-label">Remember me</label>
            </div>

            <button type="submit" class="btn btn-primary btn-full-width">
                Login
            </button>
        </form>
    </div>
</div>
@endsection

