@extends('layouts.app')

@section('title', 'Edit Customer')

@section('content')
<div class="card">
    <h2 style="margin-bottom: 1.5rem; font-size: 1.875rem; font-weight: 700;">Edit Customer</h2>

    <form method="POST" action="{{ route('customers.update', $customer) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div class="form-group">
                <label for="first_name" class="form-label">First Name <span style="color: #ef4444;">*</span></label>
                <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $customer->first_name) }}" required class="form-input" placeholder="First name">
                @error('first_name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="last_name" class="form-label">Last Name <span style="color: #ef4444;">*</span></label>
                <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $customer->last_name) }}" required class="form-input" placeholder="Last name">
                @error('last_name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email Address <span style="color: #ef4444;">*</span></label>
            <input type="email" id="email" name="email" value="{{ old('email', $customer->email) }}" required class="form-input" placeholder="email@example.com">
            @error('email')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div class="form-group">
                <label for="phone" class="form-label">Mobile</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $customer->phone) }}" class="form-input" placeholder="Mobile number">
                @error('phone')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="account_status" class="form-label">Account Status <span style="color: #ef4444;">*</span></label>
                <select id="account_status" name="account_status" required class="form-input">
                    <option value="active_not_verified" {{ old('account_status', $customer->account_status ?? 'active_not_verified') == 'active_not_verified' ? 'selected' : '' }}>Active (Not Verified)</option>
                    <option value="active_verified" {{ old('account_status', $customer->account_status) == 'active_verified' ? 'selected' : '' }}>Active (Verified)</option>
                    <option value="deactivated" {{ old('account_status', $customer->account_status) == 'deactivated' ? 'selected' : '' }}>Deactivated</option>
                    <option value="suspended" {{ old('account_status', $customer->account_status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
                @error('account_status')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="profile_image" class="form-label">Profile Image</label>
            @if($customer->profile_image)
                <div style="margin-bottom: 1rem;">
                    <img src="{{ asset('storage/' . $customer->profile_image) }}" alt="Profile" style="max-width: 150px; max-height: 150px; border-radius: 50%; border: 2px solid #e5e7eb; object-fit: cover; display: block;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.5rem; cursor: pointer;">
                        <input type="checkbox" name="delete_current_image" value="1">
                        <span style="color: #ef4444; font-size: 0.875rem;">Delete current image</span>
                    </label>
                </div>
            @endif
            <button type="button" id="chooseFileBtn" class="btn btn-primary" data-media-url="{{ route('media.json') }}">Choose File</button>
            <input type="file" id="profile_image" name="profile_image" accept="image/*" style="display: none;">
            <input type="hidden" id="selected_media_path" name="selected_media_path" value="">
            @error('profile_image')
                <span class="form-error">{{ $message }}</span>
            @enderror
            <div id="imagePreview" style="margin-top: 1rem; display: none;">
                <div style="position: relative; display: inline-block;">
                    <img id="previewImg" src="" alt="Preview" style="max-width: 200px; max-height: 200px; border-radius: 50%; border: 2px solid #e5e7eb; object-fit: cover; display: block;">
                    <button type="button" id="removeImageBtn" style="position: absolute; top: 0.5rem; right: 0.5rem; background-color: #ef4444; color: white; border: none; border-radius: 50%; width: 32px; height: 32px; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.2); z-index: 10;" title="Remove image">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>
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

        <!-- Image Selection Modal -->
        <div id="imageSelectionModal" class="modal-overlay" style="display: none;">
            <div class="modal-content" style="max-width: 90vw; max-height: 90vh; padding: 0;">
                <div class="modal-header">
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: #1f2937;">Choose Image</h3>
                    <button type="button" class="modal-close" id="closeImageSelectionModal">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <!-- Tabs -->
                <div style="display: flex; border-bottom: 1px solid #e5e7eb;">
                    <button type="button" class="image-tab-btn active" data-tab="media" style="flex: 1; padding: 1rem; background: none; border: none; border-bottom: 2px solid var(--primary-blue); color: var(--primary-blue); font-weight: 600; cursor: pointer;">Select from Media</button>
                    <button type="button" class="image-tab-btn" data-tab="upload" style="flex: 1; padding: 1rem; background: none; border: none; border-bottom: 2px solid transparent; color: #6b7280; font-weight: 600; cursor: pointer;">Select from PC</button>
                </div>

                <!-- Tab Content -->
                <div class="modal-body" style="max-height: calc(90vh - 180px); overflow-y: auto; padding: 1.5rem;">
                    <!-- Media Tab -->
                    <div id="mediaTab" class="tab-content" style="display: block;">
                        <div id="mediaGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem;">
                            <div style="text-align: center; padding: 2rem; color: #6b7280; grid-column: 1 / -1;">Loading images...</div>
                        </div>
                    </div>

                    <!-- Upload Tab -->
                    <div id="uploadTab" class="tab-content" style="display: none;">
                        <div style="text-align: center; padding: 2rem;">
                            <input type="file" id="modalFileInput" accept="image/*" class="form-input" style="max-width: 400px; margin: 0 auto;">
                            <p style="color: #6b7280; font-size: 0.875rem; margin-top: 1rem;">Select an image file from your computer</p>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn" id="cancelImageSelection" style="background-color: #6b7280; color: white;">Cancel</button>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('customers.index') }}" class="btn" style="background-color: #6b7280; color: white;">Cancel</a>
        </div>
    </form>
</div>

@push('scripts')
<script src="{{ asset('js/media-selector.js') }}"></script>
<script>
    // Initialize media selector for profile image
    document.addEventListener('DOMContentLoaded', function() {
        const chooseFileBtn = document.getElementById('chooseFileBtn');
        const profileImageInput = document.getElementById('profile_image');
        const selectedMediaPath = document.getElementById('selected_media_path');
        const imagePreview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        const removeImageBtn = document.getElementById('removeImageBtn');
        const imageSelectionModal = document.getElementById('imageSelectionModal');
        const closeImageSelectionModal = document.getElementById('closeImageSelectionModal');
        const cancelImageSelection = document.getElementById('cancelImageSelection');
        const mediaUrl = chooseFileBtn.getAttribute('data-media-url');

        // Open file input or modal
        chooseFileBtn.addEventListener('click', function() {
            imageSelectionModal.style.display = 'flex';
            loadMediaLibrary();
        });

        // Close modal
        closeImageSelectionModal.addEventListener('click', function() {
            imageSelectionModal.style.display = 'none';
        });

        cancelImageSelection.addEventListener('click', function() {
            imageSelectionModal.style.display = 'none';
        });

        // Tab switching
        document.querySelectorAll('.image-tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const tab = this.getAttribute('data-tab');
                document.querySelectorAll('.image-tab-btn').forEach(b => {
                    b.style.borderBottomColor = 'transparent';
                    b.style.color = '#6b7280';
                });
                this.style.borderBottomColor = 'var(--primary-blue)';
                this.style.color = 'var(--primary-blue)';

                document.querySelectorAll('.tab-content').forEach(content => {
                    content.style.display = 'none';
                });
                document.getElementById(tab + 'Tab').style.display = 'block';
            });
        });

        // Load media library
        function loadMediaLibrary() {
            fetch(mediaUrl)
                .then(response => response.json())
                .then(data => {
                    const mediaGrid = document.getElementById('mediaGrid');
                    mediaGrid.innerHTML = '';
                    
                    if (data.length === 0) {
                        mediaGrid.innerHTML = '<div style="text-align: center; padding: 2rem; color: #6b7280; grid-column: 1 / -1;">No images found.</div>';
                        return;
                    }

                    data.forEach(media => {
                        const div = document.createElement('div');
                        div.style.cursor = 'pointer';
                        div.style.border = '2px solid transparent';
                        div.style.borderRadius = '0.5rem';
                        div.style.overflow = 'hidden';
                        div.style.transition = 'all 0.2s';
                        
                        div.innerHTML = `<img src="${media.url}" alt="${media.name}" style="width: 100%; height: 150px; object-fit: cover; display: block;">`;
                        
                        div.addEventListener('click', function() {
                            selectedMediaPath.value = media.path;
                            previewImg.src = media.url;
                            imagePreview.style.display = 'block';
                            imageSelectionModal.style.display = 'none';
                        });

                        div.addEventListener('mouseenter', function() {
                            this.style.borderColor = 'var(--primary-blue)';
                        });

                        div.addEventListener('mouseleave', function() {
                            this.style.borderColor = 'transparent';
                        });

                        mediaGrid.appendChild(div);
                    });
                })
                .catch(error => {
                    console.error('Error loading media:', error);
                });
        }

        // Handle file input from modal
        document.getElementById('modalFileInput').addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const file = e.target.files[0];
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.style.display = 'block';
                    profileImageInput.files = file;
                    imageSelectionModal.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });

        // Remove image
        removeImageBtn.addEventListener('click', function() {
            previewImg.src = '';
            imagePreview.style.display = 'none';
            profileImageInput.value = '';
            selectedMediaPath.value = '';
        });
    });
</script>
@endpush
@endsection

