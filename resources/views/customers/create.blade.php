@extends('layouts.app')

@section('title', 'Add Customer')

@section('content')
<div class="card">
    <h2 class="section-heading-lg mb-6">Add New Customer</h2>

    <form method="POST" action="{{ route('customers.store') }}" enctype="multipart/form-data" id="customerForm">
        @csrf

        <!-- Tabs Navigation -->
        <div class="tabs-nav">
            <button type="button" class="tab-btn active" data-tab="general">General Information</button>
            <button type="button" class="tab-btn" data-tab="addresses">Addresses</button>
        </div>

        <!-- Tab 1: General Information -->
        <div id="generalTab" class="tab-content active">
        <div class="grid grid-auto-200 gap-4">
            <div class="form-group">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required class="form-input" placeholder="First name">
                @error('first_name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required class="form-input" placeholder="Last name">
                @error('last_name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required class="form-input" placeholder="email@example.com">
            @error('email')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="grid grid-auto-200 gap-4">
            <div class="form-group">
                <label for="phone" class="form-label">Mobile</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="form-input" placeholder="Mobile number">
                @error('phone')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="account_status" class="form-label">Account Status</label>
                <select id="account_status" name="account_status" required class="form-input">
                    <option value="active_not_verified" {{ old('account_status', 'active_not_verified') == 'active_not_verified' ? 'selected' : '' }}>Active (Not Verified)</option>
                    <option value="active_verified" {{ old('account_status') == 'active_verified' ? 'selected' : '' }}>Active (Verified)</option>
                    <option value="deactivated" {{ old('account_status') == 'deactivated' ? 'selected' : '' }}>Deactivated</option>
                    <option value="suspended" {{ old('account_status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
                @error('account_status')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="profile_image" class="form-label">Profile Image</label>
            <button type="button" id="chooseFileBtn" class="btn btn-primary" data-media-url="{{ route('media.json') }}">Choose File</button>
            <input type="file" id="profile_image" name="profile_image" accept="image/*" class="d-none">
            <input type="hidden" id="selected_media_path" name="selected_media_path" value="">
            @error('profile_image')
                <span class="form-error">{{ $message }}</span>
            @enderror
            <div id="imagePreview" class="mt-4 d-none">
                <div class="relative d-inline-block">
                    <img id="previewImg" src="" alt="Preview" class="rounded-full border-2 border-gray-200 d-block" style="max-width: 200px; max-height: 200px; object-fit: cover;">
                    <button type="button" id="removeImageBtn" class="absolute bg-red-500 text-white border-0 rounded-full cursor-pointer flex items-center justify-center" style="top: 0.5rem; right: 0.5rem; width: 32px; height: 32px; box-shadow: 0 2px 4px rgba(0,0,0,0.2); z-index: 10;" title="Remove image">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-auto-200 gap-4">
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" required class="form-input" placeholder="Enter password">
                @error('password')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required class="form-input" placeholder="Confirm password">
            </div>
        </div>

        <!-- Image Selection Modal -->
        <div id="imageSelectionModal" class="modal-overlay modal-overlay-hidden">
            <div class="modal-content" style="max-width: 90vw; max-height: 90vh; padding: 0;">
                <div class="modal-header">
                    <h3 class="text-xl font-bold text-primary">Choose Image</h3>
                    <button type="button" class="modal-close" id="closeImageSelectionModal">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <!-- Tabs -->
                <div class="flex border-b border-gray-200">
                    <button type="button" class="image-tab-btn active flex-1 p-4 bg-none border-0 border-b-2 border-b-blue text-blue font-semibold cursor-pointer" data-tab="media">Select from Media</button>
                    <button type="button" class="image-tab-btn flex-1 p-4 bg-none border-0 border-b-2 border-b-transparent text-tertiary font-semibold cursor-pointer" data-tab="upload">Select from PC</button>
                </div>

                <!-- Tab Content -->
                <div class="modal-body overflow-y-auto p-6" style="max-height: calc(90vh - 180px);">
                    <!-- Media Tab -->
                    <div id="mediaTab" class="tab-content active">
                        <div id="mediaGrid" class="grid grid-auto-150 gap-4">
                            <div class="text-center p-8 text-tertiary" style="grid-column: 1 / -1;">Loading images...</div>
                        </div>
                    </div>

                    <!-- Upload Tab -->
                    <div id="uploadTab" class="tab-content">
                        <div class="text-center p-8">
                            <input type="file" id="modalFileInput" accept="image/*" class="form-input input-file-center">
                            <p class="text-tertiary text-sm mt-4">Select an image file from your computer</p>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn bg-gray-600 text-white" id="cancelImageSelection">Cancel</button>
                </div>
            </div>
        </div>

        </div>
        <!-- End of General Information Tab -->

        <!-- Tab 2: Addresses -->
        <div id="addressesTab" class="tab-content">
            <div class="form-group">
                <label class="form-label">Addresses</label>
                <div id="addressesContainer" class="mb-2">
                    <div class="address-item border border-gray-200 rounded-md p-4 mb-2 bg-gray-50" data-address-index="0">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-base font-semibold text-primary">Address 1</h4>
                            <button type="button" class="remove-address-btn bg-red-500 text-white border-0 px-4 py-2 rounded cursor-pointer text-sm d-none">Remove</button>
                        </div>
                        <div class="grid grid-auto-200 gap-4">
                            <div class="form-group">
                                <label class="form-label">Label (e.g., Home, Work)</label>
                                <input type="text" name="addresses[0][label]" value="{{ old('addresses.0.label', '') }}" class="form-input" placeholder="Home">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Street Address</label>
                                <input type="text" name="addresses[0][street]" value="{{ old('addresses.0.street', '') }}" class="form-input" placeholder="123 Main St">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Street Address 2 (Optional)</label>
                                <input type="text" name="addresses[0][street2]" value="{{ old('addresses.0.street2', '') }}" class="form-input" placeholder="Apt, Suite, etc.">
                            </div>
                            <div class="form-group">
                                <label class="form-label">City</label>
                                <input type="text" name="addresses[0][city]" value="{{ old('addresses.0.city', '') }}" class="form-input" placeholder="City">
                            </div>
                            <div class="form-group">
                                <label class="form-label">State/Province</label>
                                <input type="text" name="addresses[0][state]" value="{{ old('addresses.0.state', '') }}" class="form-input" placeholder="State">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Postal Code</label>
                                <input type="text" name="addresses[0][postal_code]" value="{{ old('addresses.0.postal_code', '') }}" class="form-input" placeholder="12345">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Country</label>
                                <select name="addresses[0][country]" class="form-input">
                                    <option value="">Select Country</option>
                                    @php
                                        $currentLocale = app()->getLocale();
                                        $selectedCountry = old('addresses.0.country', '');
                                    @endphp
                                    @foreach($deliveryCountries as $country)
                                        <option value="{{ $country->country_code }}" {{ $selectedCountry == $country->country_code ? 'selected' : '' }}>
                                            {{ $currentLocale === 'ar' ? $country->country_name_ar : $country->country_name_en }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" id="addAddressBtn" class="btn mt-2 bg-gray-600 text-white">Add Address</button>
                <small class="text-tertiary text-sm mt-1 d-block">Add one or more addresses for this customer</small>
            </div>
        </div>
        <!-- End of Addresses Tab -->

        <div class="flex gap-4 mt-6">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('customers.index') }}" class="btn bg-gray-600 text-white">Cancel</a>
        </div>
    </form>
</div>

@push('scripts')
<script src="{{ asset('js/media-selector.js') }}"></script>
<script>
    // Delivery countries data for JavaScript
    @php
        $currentLocale = app()->getLocale();
    @endphp
    const deliveryCountries = @json($deliveryCountries->map(function($country) use ($currentLocale) {
        return [
            'code' => $country->country_code,
            'name' => $currentLocale === 'ar' ? $country->country_name_ar : $country->country_name_en
        ];
    }));
    
    // Tab switching functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Tab navigation
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const tabName = this.getAttribute('data-tab');
                
                // Update tab buttons
                document.querySelectorAll('.tab-btn').forEach(b => {
                    b.classList.remove('active');
                    b.style.borderBottomColor = 'transparent';
                    b.style.color = '#6b7280';
                });
                this.classList.add('active');
                this.style.borderBottomColor = 'var(--primary-blue)';
                this.style.color = 'var(--primary-blue)';
                
                // Update tab content
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.remove('active');
                    content.style.display = 'none';
                });
                document.getElementById(tabName + 'Tab').classList.add('active');
                document.getElementById(tabName + 'Tab').style.display = 'block';
            });
        });

        // Address management
        const addressesContainer = document.getElementById('addressesContainer');
        const addAddressBtn = document.getElementById('addAddressBtn');
        let addressIndex = addressesContainer.querySelectorAll('.address-item').length;

        addAddressBtn.addEventListener('click', function() {
            const addressItem = document.createElement('div');
            addressItem.className = 'address-item';
            addressItem.setAttribute('data-address-index', addressIndex);
            addressItem.style.cssText = 'border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1rem; margin-bottom: 0.5rem; background-color: #f9fafb;';
            
            addressItem.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h4 style="font-size: 1rem; font-weight: 600; color: #1f2937;">Address ${addressIndex + 1}</h4>
                    <button type="button" class="remove-address-btn" style="background-color: #ef4444; color: white; border: none; padding: 0.5rem 1rem; border-radius: 0.25rem; cursor: pointer; font-size: 0.875rem;">Remove</button>
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Label (e.g., Home, Work)</label>
                        <input type="text" name="addresses[${addressIndex}][label]" class="form-input" placeholder="Home">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Street Address</label>
                        <input type="text" name="addresses[${addressIndex}][street]" class="form-input" placeholder="123 Main St">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Street Address 2 (Optional)</label>
                        <input type="text" name="addresses[${addressIndex}][street2]" class="form-input" placeholder="Apt, Suite, etc.">
                    </div>
                    <div class="form-group">
                        <label class="form-label">City</label>
                        <input type="text" name="addresses[${addressIndex}][city]" class="form-input" placeholder="City">
                    </div>
                    <div class="form-group">
                        <label class="form-label">State/Province</label>
                        <input type="text" name="addresses[${addressIndex}][state]" class="form-input" placeholder="State">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Postal Code</label>
                        <input type="text" name="addresses[${addressIndex}][postal_code]" class="form-input" placeholder="12345">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Country</label>
                        <select name="addresses[${addressIndex}][country]" class="form-input">
                            <option value="">Select Country</option>
                            ${deliveryCountries.map(country => `<option value="${country.code}">${country.name}</option>`).join('')}
                        </select>
                    </div>
                </div>
            `;
            
            addressesContainer.appendChild(addressItem);
            addressIndex++;
            
            // Update remove buttons visibility
            updateRemoveButtons();
            
            // Attach remove event
            addressItem.querySelector('.remove-address-btn').addEventListener('click', function() {
                addressItem.remove();
                updateAddressNumbers();
                updateRemoveButtons();
            });
        });

        // Remove address buttons
        document.querySelectorAll('.remove-address-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                this.closest('.address-item').remove();
                updateAddressNumbers();
                updateRemoveButtons();
            });
        });

        function updateAddressNumbers() {
            const addressItems = addressesContainer.querySelectorAll('.address-item');
            addressItems.forEach((item, index) => {
                const title = item.querySelector('h4');
                if (title) {
                    title.textContent = `Address ${index + 1}`;
                }
                // Update input and select names
                item.querySelectorAll('input, select').forEach(input => {
                    const name = input.getAttribute('name');
                    if (name && name.startsWith('addresses[')) {
                        // Extract field name (e.g., 'label', 'street', etc.)
                        const match = name.match(/addresses\[\d+\]\[(.+)\]/);
                        if (match && match[1]) {
                            input.setAttribute('name', `addresses[${index}][${match[1]}]`);
                        }
                    }
                });
            });
        }

        function updateRemoveButtons() {
            const addressItems = addressesContainer.querySelectorAll('.address-item');
            addressItems.forEach(item => {
                const removeBtn = item.querySelector('.remove-address-btn');
                if (removeBtn) {
                    removeBtn.style.display = addressItems.length > 1 ? 'block' : 'none';
                }
            });
        }

        // Initialize remove buttons visibility
        updateRemoveButtons();

        // Initialize media selector for profile image
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

