@extends('layouts.app')

@section('title', 'Edit Driver')

@section('content')
<div class="card">
    <h2 class="section-heading-lg mb-6">Edit Driver</h2>

    <form method="POST" action="{{ route('drivers.update', $driver) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-auto-200 gap-4">
            <div class="form-group">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $driver->first_name) }}" required class="form-input" placeholder="First name">
                @error('first_name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $driver->last_name) }}" required class="form-input" placeholder="Last name">
                @error('last_name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email', $driver->email) }}" required class="form-input" placeholder="email@example.com">
            @error('email')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="grid grid-auto-200 gap-4">
            <div class="form-group">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $driver->phone) }}" class="form-input" placeholder="Phone number">
                @error('phone')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="driver_status" class="form-label">Driver Status</label>
                <select id="driver_status" name="driver_status" required class="form-input">
                    <option value="active" {{ old('driver_status', $driver->driver_status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('driver_status', $driver->driver_status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('driver_status')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="load_capacity" class="form-label">Load Capacity (kg)</label>
            <input type="number" id="load_capacity" name="load_capacity" value="{{ old('load_capacity', $driver->load_capacity) }}" min="0" step="0.01" class="form-input" placeholder="Load capacity">
            @error('load_capacity')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="grid grid-auto-200 gap-4">
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-input" placeholder="Leave blank to keep current password">
                @error('password')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="Confirm password">
            </div>
        </div>

        <div class="flex gap-4 mt-6">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('drivers.index') }}" class="btn bg-gray-600 text-white">Cancel</a>
        </div>
    </form>
</div>
@endsection




