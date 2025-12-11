@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="card">
    <h2 class="section-heading-lg mb-6">Edit Profile</h2>

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PUT')

        <div class="grid grid-auto-200 gap-4">
            <div class="form-group">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required class="form-input" placeholder="First name">
                @error('first_name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required class="form-input" placeholder="Last name">
                @error('last_name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="form-input" placeholder="email@example.com">
            @error('email')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="role" class="form-label">Role</label>
            <select id="role" name="role" required class="form-input">
                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="manager" {{ old('role', $user->role) == 'manager' ? 'selected' : '' }}>Manager</option>
                <option value="editor" {{ old('role', $user->role) == 'editor' ? 'selected' : '' }}>Editor</option>
            </select>
            @error('role')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="mt-8 pt-6 border-t border-gray-200">
            <h3 class="section-heading mb-4">Change Password</h3>
            <p class="text-tertiary text-sm mb-4">Leave blank if you don't want to change the password.</p>

            <div class="form-group">
                <label for="password" class="form-label">New Password</label>
                <input type="password" id="password" name="password" class="form-input" placeholder="Enter new password">
                @error('password')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="Confirm new password">
            </div>
        </div>

        <div class="flex gap-4 mt-6">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('dashboard') }}" class="btn bg-gray-600 text-white">Cancel</a>
        </div>
    </form>
</div>
@endsection





