@extends('layouts.app')

@section('title', __('cms.settings'))

@section('content')
<div class="card">
    <h2 style="margin-bottom: 1.5rem; font-size: 1.875rem; font-weight: 700;">{{ __('cms.settings') }}</h2>

    <!-- Tabs Navigation -->
    <div class="settings-tabs" style="border-bottom: 2px solid #e5e7eb; margin-bottom: 1.5rem;">
        <button class="settings-tab active" data-tab="general" onclick="switchTab('general')">
            {{ __('cms.general') }}
        </button>
        @if(($settings['multi_currency'] ?? '0') === '1')
        <button class="settings-tab" data-tab="currency" onclick="switchTab('currency')">
            {{ __('cms.multi_currency') }}
        </button>
        @endif
    </div>

    <!-- General Settings Tab -->
    <div id="general-tab" class="settings-tab-content active">
        <form method="POST" action="{{ route('settings.update') }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="timezone" class="form-label">{{ __('cms.timezone') }} <span style="color: #ef4444;">*</span></label>
                <select id="timezone" name="timezone" required class="form-input" style="width: 350px;">
                    <option value="">{{ __('cms.select_timezone') }}</option>
                    <option value="UTC" {{ old('timezone', $settings['timezone']) == 'UTC' ? 'selected' : '' }}>UTC</option>
                    <option value="America/New_York" {{ old('timezone', $settings['timezone']) == 'America/New_York' ? 'selected' : '' }}>America/New_York (EST)</option>
                    <option value="America/Chicago" {{ old('timezone', $settings['timezone']) == 'America/Chicago' ? 'selected' : '' }}>America/Chicago (CST)</option>
                    <option value="America/Denver" {{ old('timezone', $settings['timezone']) == 'America/Denver' ? 'selected' : '' }}>America/Denver (MST)</option>
                    <option value="America/Los_Angeles" {{ old('timezone', $settings['timezone']) == 'America/Los_Angeles' ? 'selected' : '' }}>America/Los_Angeles (PST)</option>
                    <option value="Europe/London" {{ old('timezone', $settings['timezone']) == 'Europe/London' ? 'selected' : '' }}>Europe/London (GMT)</option>
                    <option value="Europe/Paris" {{ old('timezone', $settings['timezone']) == 'Europe/Paris' ? 'selected' : '' }}>Europe/Paris (CET)</option>
                    <option value="Asia/Dubai" {{ old('timezone', $settings['timezone']) == 'Asia/Dubai' ? 'selected' : '' }}>Asia/Dubai (GST)</option>
                    <option value="Asia/Beirut" {{ old('timezone', $settings['timezone']) == 'Asia/Beirut' ? 'selected' : '' }}>Asia/Beirut (EET)</option>
                    <option value="Asia/Tokyo" {{ old('timezone', $settings['timezone']) == 'Asia/Tokyo' ? 'selected' : '' }}>Asia/Tokyo (JST)</option>
                    <option value="Asia/Shanghai" {{ old('timezone', $settings['timezone']) == 'Asia/Shanghai' ? 'selected' : '' }}>Asia/Shanghai (CST)</option>
                    <option value="Asia/Kolkata" {{ old('timezone', $settings['timezone']) == 'Asia/Kolkata' ? 'selected' : '' }}>Asia/Kolkata (IST)</option>
                    <option value="Australia/Sydney" {{ old('timezone', $settings['timezone']) == 'Australia/Sydney' ? 'selected' : '' }}>Australia/Sydney (AEDT)</option>
                </select>
                @error('timezone')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('cms.multilingual') }} <span style="color: #ef4444;">*</span></label>
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-top: 0.5rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="radio" name="multilingual" value="1" {{ old('multilingual', $settings['multilingual']) == '1' ? 'checked' : '' }} required>
                        <span>{{ __('cms.yes') }}</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="radio" name="multilingual" value="0" {{ old('multilingual', $settings['multilingual']) == '0' ? 'checked' : '' }} required>
                        <span>{{ __('cms.no') }}</span>
                    </label>
                </div>
                @error('multilingual')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="default_language" class="form-label">{{ __('cms.default_language') }} <span style="color: #ef4444;">*</span></label>
                <select id="default_language" name="default_language" required class="form-input" style="width: 350px;">
                    <option value="">{{ __('cms.default_language') }}</option>
                    <option value="en" {{ old('default_language', $settings['default_language']) == 'en' ? 'selected' : '' }}>{{ __('cms.english') }}</option>
                    <option value="ar" {{ old('default_language', $settings['default_language']) == 'ar' ? 'selected' : '' }}>{{ __('cms.arabic') }}</option>
                </select>
                @error('default_language')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('cms.multi_currency') }} <span style="color: #ef4444;">*</span></label>
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-top: 0.5rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="radio" name="multi_currency" value="1" {{ old('multi_currency', $settings['multi_currency'] ?? '0') == '1' ? 'checked' : '' }} required>
                        <span>{{ __('cms.yes') }}</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="radio" name="multi_currency" value="0" {{ old('multi_currency', $settings['multi_currency'] ?? '0') == '0' ? 'checked' : '' }} required>
                        <span>{{ __('cms.no') }}</span>
                    </label>
                </div>
                @error('multi_currency')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">{{ __('cms.save') }}</button>
                <a href="{{ route('dashboard') }}" class="btn" style="background-color: #6b7280; color: white;">{{ __('cms.cancel') }}</a>
            </div>
        </form>
    </div>

    <!-- Multi-Currency Settings Tab -->
    <div id="currency-tab" class="settings-tab-content" style="display: none;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.5rem; font-weight: 600;">Multi-Currency Settings</h3>
            <button type="button" class="btn btn-primary" onclick="openAddCurrencyModal()">Add Currency</button>
        </div>

    <!-- Currency List -->
    <div style="margin-bottom: 2rem;">
        <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem;">Currencies</h3>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f3f4f6; border-bottom: 2px solid #e5e7eb;">
                        <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Code</th>
                        <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Name</th>
                        <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Symbol</th>
                        <th style="padding: 0.75rem; text-align: center; font-weight: 600;">Default</th>
                        <th style="padding: 0.75rem; text-align: center; font-weight: 600;">Active</th>
                        <th style="padding: 0.75rem; text-align: right; font-weight: 600;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($currencies as $currency)
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 0.75rem;"><strong>{{ $currency->code }}</strong></td>
                            <td style="padding: 0.75rem;">{{ $currency->name }}</td>
                            <td style="padding: 0.75rem;">{{ $currency->symbol }}</td>
                            <td style="padding: 0.75rem; text-align: center;">
                                @if($currency->is_default)
                                    <span style="color: #10b981; font-weight: 600;">✓ Default</span>
                                @else
                                    <span style="color: #6b7280;">-</span>
                                @endif
                            </td>
                            <td style="padding: 0.75rem; text-align: center;">
                                @if($currency->is_active)
                                    <span style="color: #10b981;">Active</span>
                                @else
                                    <span style="color: #ef4444;">Inactive</span>
                                @endif
                            </td>
                            <td style="padding: 0.75rem; text-align: right;">
                                <button type="button" class="btn" style="padding: 0.375rem 0.75rem; font-size: 0.875rem; background-color: #099ecb; color: white; margin-right: 0.5rem;" onclick="openEditCurrencyModal({{ $currency->id }}, '{{ addslashes($currency->code) }}', '{{ addslashes($currency->name) }}', '{{ addslashes($currency->symbol) }}', {{ $currency->is_default ? 'true' : 'false' }}, {{ $currency->is_active ? 'true' : 'false' }})">Edit</button>
                                @if(!$currency->is_default && $currencies->count() > 1)
                                    <form method="POST" action="{{ route('settings.currencies.delete', $currency) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this currency?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn" style="padding: 0.375rem 0.75rem; font-size: 0.875rem; background-color: #ef4444; color: white;">Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 2rem; text-align: center; color: #6b7280;">No currencies found. Add your first currency.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Exchange Rates Matrix -->
    @if($currencies->count() > 1)
        <div>
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem;">Exchange Rates</h3>
            <p style="color: #6b7280; margin-bottom: 1rem; font-size: 0.875rem;">Set exchange rates between currencies. Rate represents how many units of the target currency equal 1 unit of the source currency.</p>
            
            <form method="POST" action="{{ route('settings.exchange-rates.update') }}" id="exchangeRatesForm">
                @csrf
                @method('PUT')
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; min-width: 600px;">
                        <thead>
                            <tr style="background-color: #f3f4f6; border-bottom: 2px solid #e5e7eb;">
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">From \ To</th>
                                @foreach($currencies as $toCurrency)
                                    <th style="padding: 0.75rem; text-align: center; font-weight: 600;">{{ $toCurrency->code }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($currencies as $fromCurrency)
                                <tr style="border-bottom: 1px solid #e5e7eb;">
                                    <td style="padding: 0.75rem; font-weight: 600;">{{ $fromCurrency->code }}</td>
                                    @foreach($currencies as $toCurrency)
                                        <td style="padding: 0.5rem; text-align: center;">
                                            @if($fromCurrency->id == $toCurrency->id)
                                                <span style="color: #9ca3af;">-</span>
                                            @else
                                                @php
                                                    // Determine step and decimal places based on target currency
                                                    $isUSD = strtoupper($toCurrency->code) === 'USD';
                                                    $isLBP = strtoupper($toCurrency->code) === 'LBP';
                                                    
                                                    if ($isUSD) {
                                                        $step = '0.01';
                                                        $decimals = 2;
                                                        $placeholder = '0.00';
                                                    } elseif ($isLBP) {
                                                        $step = '1';
                                                        $decimals = 0;
                                                        $placeholder = '0';
                                                    } else {
                                                        $step = '0.000001';
                                                        $decimals = 6;
                                                        $placeholder = '0.000000';
                                                    }
                                                    
                                                    // Format the value based on decimal places
                                                    $rateValue = isset($rateMatrix[$fromCurrency->id][$toCurrency->id]) ? $rateMatrix[$fromCurrency->id][$toCurrency->id] : '';
                                                    if ($rateValue !== '' && $rateValue !== null) {
                                                        $rateValue = number_format((float)$rateValue, $decimals, '.', '');
                                                    }
                                                @endphp
                                                <input 
                                                    type="number" 
                                                    name="rates[{{ $fromCurrency->id }}][{{ $toCurrency->id }}]" 
                                                    value="{{ $rateValue }}"
                                                    step="{{ $step }}"
                                                    min="0"
                                                    style="width: 100px; padding: 0.375rem; border: 1px solid #d1d5db; border-radius: 0.375rem; text-align: center;"
                                                    placeholder="{{ $placeholder }}"
                                                    data-currency-code="{{ $toCurrency->code }}"
                                                    class="exchange-rate-input"
                                                >
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary">Save Exchange Rates</button>
                </div>
            </form>
        </div>
    @endif
    </div>
</div>

<!-- Add Currency Modal -->
<div id="addCurrencyModal" class="modal-overlay" style="display: none;">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header">
            <h3 style="font-size: 1.25rem; font-weight: 700; color: #1f2937;">Add Currency</h3>
            <button type="button" class="modal-close" onclick="closeAddCurrencyModal()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('settings.currencies.store') }}" id="addCurrencyForm">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="currency_code" class="form-label">Currency Code <span style="color: #ef4444;">*</span></label>
                    <input type="text" id="currency_code" name="code" required maxlength="3" class="form-input" placeholder="USD" style="text-transform: uppercase;">
                    @error('code')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="currency_name" class="form-label">Currency Name <span style="color: #ef4444;">*</span></label>
                    <input type="text" id="currency_name" name="name" required class="form-input" placeholder="United States Dollar">
                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="currency_symbol" class="form-label">Symbol <span style="color: #ef4444;">*</span></label>
                    <input type="text" id="currency_symbol" name="symbol" required maxlength="10" class="form-input" placeholder="$">
                    @error('symbol')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="checkbox" name="is_default" value="1">
                        <span>Set as default currency</span>
                    </label>
                </div>
                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="checkbox" name="is_active" value="1" checked>
                        <span>Active</span>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" style="background-color: #6b7280; color: white;" onclick="closeAddCurrencyModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Currency</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Currency Modal -->
<div id="editCurrencyModal" class="modal-overlay" style="display: none;">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header">
            <h3 style="font-size: 1.25rem; font-weight: 700; color: #1f2937;">Edit Currency</h3>
            <button type="button" class="modal-close" onclick="closeEditCurrencyModal()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form method="POST" id="editCurrencyForm" action="">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label for="edit_currency_code" class="form-label">Currency Code <span style="color: #ef4444;">*</span></label>
                    <input type="text" id="edit_currency_code" name="code" required maxlength="3" class="form-input" placeholder="USD" style="text-transform: uppercase;">
                    @error('code')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="edit_currency_name" class="form-label">Currency Name <span style="color: #ef4444;">*</span></label>
                    <input type="text" id="edit_currency_name" name="name" required class="form-input" placeholder="United States Dollar">
                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="edit_currency_symbol" class="form-label">Symbol <span style="color: #ef4444;">*</span></label>
                    <input type="text" id="edit_currency_symbol" name="symbol" required maxlength="10" class="form-input" placeholder="$">
                    @error('symbol')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="checkbox" id="edit_is_default" name="is_default" value="1">
                        <span>Set as default currency</span>
                    </label>
                </div>
                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="checkbox" id="edit_is_active" name="is_active" value="1">
                        <span>Active</span>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" style="background-color: #6b7280; color: white;" onclick="closeEditCurrencyModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Currency</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<style>
    .settings-tabs {
        display: flex;
        gap: 0;
        margin-bottom: 1.5rem;
    }

    .settings-tab {
        padding: 0.75rem 1.5rem;
        background: none;
        border: none;
        border-bottom: 3px solid transparent;
        cursor: pointer;
        font-size: 1rem;
        font-weight: 500;
        color: #6b7280;
        transition: all 0.2s;
        position: relative;
        bottom: -2px;
    }

    .settings-tab:hover {
        color: #099ecb;
        background-color: #f9fafb;
    }

    .settings-tab.active {
        color: #099ecb;
        border-bottom-color: #099ecb;
        font-weight: 600;
    }

    .settings-tab-content {
        display: none;
    }

    .settings-tab-content.active {
        display: block;
    }
</style>
<script>
    function switchTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.settings-tab-content').forEach(content => {
            content.classList.remove('active');
            content.style.display = 'none';
        });

        // Remove active class from all tabs
        document.querySelectorAll('.settings-tab').forEach(tab => {
            tab.classList.remove('active');
        });

        // Show selected tab content
        const selectedContent = document.getElementById(tabName + '-tab');
        if (selectedContent) {
            selectedContent.classList.add('active');
            selectedContent.style.display = 'block';
        }

        // Add active class to selected tab
        const selectedTab = document.querySelector(`.settings-tab[data-tab="${tabName}"]`);
        if (selectedTab) {
            selectedTab.classList.add('active');
        }

        // Update URL hash without scrolling
        if (history.pushState) {
            history.pushState(null, null, '#' + tabName);
        }
    }

    // Check URL hash on page load
    document.addEventListener('DOMContentLoaded', function() {
        const hash = window.location.hash.substring(1);
        if (hash === 'currency' || hash === 'general') {
            switchTab(hash);
        }
    });

    function openAddCurrencyModal() {
        document.getElementById('addCurrencyModal').style.display = 'flex';
        document.getElementById('addCurrencyForm').reset();
    }

    function closeAddCurrencyModal() {
        document.getElementById('addCurrencyModal').style.display = 'none';
    }

    function openEditCurrencyModal(id, code, name, symbol, isDefault, isActive) {
        document.getElementById('editCurrencyModal').style.display = 'flex';
        document.getElementById('editCurrencyForm').action = '{{ route("settings.currencies.update", ":id") }}'.replace(':id', id);
        document.getElementById('edit_currency_code').value = code;
        document.getElementById('edit_currency_name').value = name;
        document.getElementById('edit_currency_symbol').value = symbol;
        document.getElementById('edit_is_default').checked = isDefault;
        document.getElementById('edit_is_active').checked = isActive;
    }

    function closeEditCurrencyModal() {
        document.getElementById('editCurrencyModal').style.display = 'none';
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
        const addModal = document.getElementById('addCurrencyModal');
        const editModal = document.getElementById('editCurrencyModal');
        if (event.target == addModal) {
            closeAddCurrencyModal();
        }
        if (event.target == editModal) {
            closeEditCurrencyModal();
        }
    }

    // Auto-uppercase currency code
    document.addEventListener('DOMContentLoaded', function() {
        const codeInputs = document.querySelectorAll('#currency_code, #edit_currency_code');
        codeInputs.forEach(input => {
            input.addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });
        });

        // Format exchange rate inputs based on currency
        const exchangeRateInputs = document.querySelectorAll('.exchange-rate-input');
        exchangeRateInputs.forEach(input => {
            const currencyCode = input.getAttribute('data-currency-code');
            const isUSD = currencyCode === 'USD';
            const isLBP = currencyCode === 'LBP';
            
            input.addEventListener('blur', function() {
                if (this.value !== '' && this.value !== null) {
                    let formattedValue = parseFloat(this.value);
                    if (!isNaN(formattedValue)) {
                        if (isUSD) {
                            formattedValue = parseFloat(formattedValue.toFixed(2));
                        } else if (isLBP) {
                            formattedValue = Math.round(formattedValue);
                        }
                        this.value = formattedValue;
                    }
                }
            });
        });
    });
</script>
@endpush
@endsection

