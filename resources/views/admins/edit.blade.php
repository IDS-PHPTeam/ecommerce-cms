@extends('layouts.app')

@section('title', __('cms.edit_admin'))

@section('content')
<div class="card">
    <h2 class="section-heading-lg mb-6">{{ __('cms.edit_admin') }}</h2>

    <form method="POST" action="{{ route('admins.update', $admin) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-auto-200 gap-4">
            <div class="form-group">
                <label for="first_name" class="form-label">{{ __('cms.first_name') }}</label>
                <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $admin->first_name) }}" required class="form-input" placeholder="{{ __('cms.first_name') }}">
                @error('first_name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="last_name" class="form-label">{{ __('cms.last_name') }}</label>
                <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $admin->last_name) }}" required class="form-input" placeholder="{{ __('cms.last_name') }}">
                @error('last_name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="email" class="form-label">{{ __('cms.email_address') }}</label>
            <input type="email" id="email" name="email" value="{{ old('email', $admin->email) }}" required class="form-input" placeholder="{{ __('cms.email_placeholder') }}">
            @error('email')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="grid grid-auto-200 gap-4">
            <div class="form-group">
                <label for="password" class="form-label">{{ __('cms.password') }}</label>
                <input type="password" id="password" name="password" class="form-input" placeholder="{{ __('cms.leave_blank_to_keep_password') }}">
                @error('password')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">{{ __('cms.confirm_password') }}</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="{{ __('cms.confirm_password') }}">
            </div>
        </div>

        <div class="form-group">
            <label for="role" class="form-label">{{ __('cms.role') }}</label>
            <select id="role" name="role" required class="form-input">
                <option value="admin" {{ old('role', $admin->role) == 'admin' ? 'selected' : '' }}>{{ __('cms.admin') }}</option>
                <option value="manager" {{ old('role', $admin->role) == 'manager' ? 'selected' : '' }}>{{ __('cms.manager') }}</option>
                <option value="editor" {{ old('role', $admin->role) == 'editor' ? 'selected' : '' }}>{{ __('cms.editor') }}</option>
            </select>
            @error('role')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex gap-4 mt-6">
            <button type="submit" class="btn btn-primary">{{ __('cms.save') }}</button>
            <a href="{{ route('admins.index') }}" class="btn bg-gray-600 text-white">{{ __('cms.cancel') }}</a>
        </div>
    </form>
</div>
@endsection

