@extends('layouts.app')

@section('title', 'Add Driver')

@section('content')
<div class="card">
    <h2 style="margin-bottom: 1.5rem; font-size: 1.875rem; font-weight: 700;">Add New Driver</h2>

    <form method="POST" action="{{ route('drivers.store') }}">
        @csrf

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div class="form-group">
                <label for="first_name" class="form-label">First Name <span style="color: #ef4444;">*</span></label>
                <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required class="form-input" placeholder="First name">
                @error('first_name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="last_name" class="form-label">Last Name <span style="color: #ef4444;">*</span></label>
                <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required class="form-input" placeholder="Last name">
                @error('last_name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email Address <span style="color: #ef4444;">*</span></label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required class="form-input" placeholder="email@example.com">
            @error('email')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div class="form-group">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="form-input" placeholder="Phone number">
                @error('phone')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="driver_status" class="form-label">Driver Status <span style="color: #ef4444;">*</span></label>
                <select id="driver_status" name="driver_status" required class="form-input">
                    <option value="active" {{ old('driver_status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('driver_status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('driver_status')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="load_capacity" class="form-label">Load Capacity (kg)</label>
            <input type="number" id="load_capacity" name="load_capacity" value="{{ old('load_capacity') }}" min="0" step="0.01" class="form-input" placeholder="Load capacity">
            @error('load_capacity')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div class="form-group">
                <label for="password" class="form-label">Password <span style="color: #ef4444;">*</span></label>
                <input type="password" id="password" name="password" required class="form-input" placeholder="Enter password">
                @error('password')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirm Password <span style="color: #ef4444;">*</span></label>
                <input type="password" id="password_confirmation" name="password_confirmation" required class="form-input" placeholder="Confirm password">
            </div>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('drivers.index') }}" class="btn" style="background-color: #6b7280; color: white;">Cancel</a>
        </div>
    </form>
</div>
@endsection

