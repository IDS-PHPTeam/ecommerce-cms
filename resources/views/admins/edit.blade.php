@extends('layouts.app')

@section('title', 'Edit Admin')

@section('content')
<div class="card">
    <h2 style="margin-bottom: 1.5rem; font-size: 1.875rem; font-weight: 700;">Edit Admin</h2>

    <form method="POST" action="{{ route('admins.update', $admin) }}">
        @csrf
        @method('PUT')

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div class="form-group">
                <label for="first_name" class="form-label">First Name <span style="color: #ef4444;">*</span></label>
                <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $admin->first_name) }}" required class="form-input" placeholder="First name">
                @error('first_name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="last_name" class="form-label">Last Name <span style="color: #ef4444;">*</span></label>
                <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $admin->last_name) }}" required class="form-input" placeholder="Last name">
                @error('last_name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email Address <span style="color: #ef4444;">*</span></label>
            <input type="email" id="email" name="email" value="{{ old('email', $admin->email) }}" required class="form-input" placeholder="email@example.com">
            @error('email')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
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

        <div class="form-group">
            <label for="role" class="form-label">Role <span style="color: #ef4444;">*</span></label>
            <select id="role" name="role" required class="form-input">
                <option value="admin" {{ old('role', $admin->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="manager" {{ old('role', $admin->role) == 'manager' ? 'selected' : '' }}>Manager</option>
                <option value="editor" {{ old('role', $admin->role) == 'editor' ? 'selected' : '' }}>Editor</option>
            </select>
            @error('role')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('admins.index') }}" class="btn" style="background-color: #6b7280; color: white;">Cancel</a>
        </div>
    </form>
</div>
@endsection

