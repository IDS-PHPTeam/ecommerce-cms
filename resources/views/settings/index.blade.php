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

            <!-- Grid Layout for Better Organization -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
                
                <!-- Left Column: Basic Settings -->
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem; padding-bottom: 0.75rem; border-bottom: 2px solid #e5e7eb;">{{ __('cms.basic_settings') }}</h3>
                    
                    <div class="form-group">
                        <label for="timezone" class="form-label">{{ __('cms.timezone') }} <span style="color: #ef4444;">*</span></label>
                        <select id="timezone" name="timezone" required class="form-input">
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
                        <select id="default_language" name="default_language" required class="form-input">
                            <option value="">{{ __('cms.default_language') }}</option>
                            <option value="en" {{ old('default_language', $settings['default_language']) == 'en' ? 'selected' : '' }}>{{ __('cms.english') }}</option>
                            <option value="ar" {{ old('default_language', $settings['default_language']) == 'ar' ? 'selected' : '' }}>{{ __('cms.arabic') }}</option>
                        </select>
                        @error('default_language')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="theme_mode" class="form-label">{{ __('cms.theme_mode') }} <span style="color: #ef4444;">*</span></label>
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-top: 0.5rem;">
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="radio" name="theme_mode" value="light" {{ old('theme_mode', $settings['theme_mode'] ?? 'light') == 'light' ? 'checked' : '' }} required>
                                <span>{{ __('cms.light_mode') }}</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="radio" name="theme_mode" value="dark" {{ old('theme_mode', $settings['theme_mode'] ?? 'light') == 'dark' ? 'checked' : '' }} required>
                                <span>{{ __('cms.dark_mode') }}</span>
                            </label>
                        </div>
                        @error('theme_mode')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Right Column: Additional Settings -->
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem; padding-bottom: 0.75rem; border-bottom: 2px solid #e5e7eb;">{{ __('cms.additional_settings') }}</h3>
                    
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

                    <div class="form-group">
                        <label class="form-label">{{ __('cms.notify_by') }}</label>
                        <div style="display: flex; align-items: center; gap: 1.5rem; margin-top: 0.5rem;">
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="checkbox" name="notify_by_email" value="1" {{ old('notify_by_email', $settings['notify_by_email'] ?? '1') == '1' ? 'checked' : '' }}>
                                <span>{{ __('cms.email') }}</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="checkbox" name="notify_by_push" value="1" {{ old('notify_by_push', $settings['notify_by_push'] ?? '0') == '1' ? 'checked' : '' }}>
                                <span>{{ __('cms.push') }}</span>
                            </label>
                        </div>
                        @error('notify_by_email')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                        @error('notify_by_push')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Full Width: Delivery Countries -->
            <div class="form-group" style="margin-bottom: 2rem;">
                <label for="delivery_countries" class="form-label">{{ __('cms.delivery_shipping_to_countries') }}</label>
                <div class="custom-multiselect" style="position: relative; max-width: 500px;">
                    <div class="multiselect-trigger" id="countriesTrigger" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: white; cursor: pointer; display: flex; justify-content: space-between; align-items: center; min-height: 42px;">
                        <div class="multiselect-selected" style="color: #374151; flex: 1; display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center;">
                            <span class="placeholder-text" style="color: #9ca3af;">{{ __('cms.select_countries') }}</span>
                            <div class="selected-countries-tags" style="display: none; flex-wrap: wrap; gap: 0.5rem;">
                                <!-- Selected country tags will be added here -->
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <button type="button" class="clear-all-countries-btn" style="display: none; background: none; border: none; color: #ef4444; cursor: pointer; padding: 0.25rem; font-size: 0.875rem;" title="{{ __('cms.clear_all') }}">{{ __('cms.clear_all') }}</button>
                            <svg class="multiselect-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20" style="color: #6b7280; transition: transform 0.2s;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    <div class="multiselect-dropdown" id="countriesDropdown" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #d1d5db; border-radius: 0.375rem; margin-top: 0.25rem; max-height: 200px; overflow-y: auto; z-index: 1000; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);">
                        @php
                            $currentLocale = app()->getLocale();
                            $selectedCountries = old('delivery_countries', $settings['delivery_countries'] ?? ['LB']);
                            if (is_string($selectedCountries)) {
                                // Handle legacy single country or JSON string
                                $decoded = json_decode($selectedCountries, true);
                                $selectedCountries = is_array($decoded) ? $decoded : [$selectedCountries];
                            }
                        @endphp
                        @foreach($countries as $country)
                            <label class="multiselect-option" style="display: flex; align-items: center; padding: 0.75rem; cursor: pointer; border-bottom: 1px solid #f3f4f6; transition: background-color 0.15s;">
                                <input type="checkbox" name="delivery_countries[]" value="{{ $country->country_code }}" class="country-checkbox" data-country-name="{{ $currentLocale === 'ar' ? $country->country_name_ar : $country->country_name_en }}" {{ in_array($country->country_code, $selectedCountries) ? 'checked' : '' }} style="margin-right: 0.75rem; width: 1rem; height: 1rem; cursor: pointer;">
                                <span style="color: #374151; user-select: none;">{{ $currentLocale === 'ar' ? $country->country_name_ar : $country->country_name_en }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <small style="color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ __('cms.select_one_or_more_countries') }}</small>
                @error('delivery_countries')
                    <span class="form-error">{{ $message }}</span>
                @enderror
                @error('delivery_countries.*')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; gap: 1rem; margin-top: 2rem; padding-top: 1.5rem; border-top: 2px solid #e5e7eb;">
                <button type="submit" class="btn btn-primary">{{ __('cms.save') }}</button>
                <a href="{{ route('dashboard') }}" class="btn" style="background-color: #6b7280; color: white;">{{ __('cms.cancel') }}</a>
            </div>
        </form>
    </div>

    <!-- Multi-Currency Settings Tab -->
    <div id="currency-tab" class="settings-tab-content" style="display: none;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.5rem; font-weight: 600;">{{ __('cms.multi_currency_settings') }}</h3>
            <button type="button" class="btn btn-primary" onclick="openAddCurrencyModal()">{{ __('cms.add_currency') }}</button>
        </div>

    <!-- Currency List -->
    <div style="margin-bottom: 2rem;">
        <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem;">{{ __('cms.currencies') }}</h3>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr class="table-header-row">
                        <th style="padding: 0.75rem; text-align: left; font-weight: 600;">{{ __('cms.code') }}</th>
                        <th style="padding: 0.75rem; text-align: left; font-weight: 600;">{{ __('cms.name') }}</th>
                        <th style="padding: 0.75rem; text-align: left; font-weight: 600;">{{ __('cms.symbol') }}</th>
                        <th style="padding: 0.75rem; text-align: center; font-weight: 600;">{{ __('cms.default') }}</th>
                        <th style="padding: 0.75rem; text-align: center; font-weight: 600;">{{ __('cms.active') }}</th>
                        <th style="padding: 0.75rem; text-align: right; font-weight: 600;">{{ __('cms.actions') }}</th>
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
                                    <span style="color: #10b981; font-weight: 600;">✓ {{ __('cms.default') }}</span>
                                @else
                                    <span style="color: #6b7280;">-</span>
                                @endif
                            </td>
                            <td style="padding: 0.75rem; text-align: center;">
                                @if($currency->is_active)
                                    <span style="color: #10b981;">{{ __('cms.active') }}</span>
                                @else
                                    <span style="color: #ef4444;">{{ __('cms.inactive') }}</span>
                                @endif
                            </td>
                            <td style="padding: 0.75rem; text-align: right;">
                                <button type="button" class="btn" style="padding: 0.375rem 0.75rem; font-size: 0.875rem; background-color: #099ecb; color: white; margin-right: 0.5rem;" onclick="openEditCurrencyModal({{ $currency->id }}, '{{ addslashes($currency->code) }}', '{{ addslashes($currency->name) }}', '{{ addslashes($currency->symbol) }}', {{ $currency->is_default ? 'true' : 'false' }}, {{ $currency->is_active ? 'true' : 'false' }})">{{ __('cms.edit') }}</button>
                                @if(!$currency->is_default && $currencies->count() > 1)
                                    <form method="POST" action="{{ route('settings.currencies.delete', $currency) }}" style="display: inline;" onsubmit="return confirm('{{ __('cms.confirm_delete_currency') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn" style="padding: 0.375rem 0.75rem; font-size: 0.875rem; background-color: #ef4444; color: white;">{{ __('cms.delete') }}</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 2rem; text-align: center; color: #6b7280;">{{ __('cms.no_currencies_found') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Exchange Rates Matrix -->
    @if($currencies->count() > 1)
        <div>
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem;">{{ __('cms.exchange_rates') }}</h3>
            <p style="color: #6b7280; margin-bottom: 1rem; font-size: 0.875rem;">{{ __('cms.exchange_rates_description') }}</p>
            
            <form method="POST" action="{{ route('settings.exchange-rates.update') }}" id="exchangeRatesForm">
                @csrf
                @method('PUT')
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; min-width: 600px;">
                        <thead>
                            <tr class="table-header-row">
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">{{ __('cms.from_to') }}</th>
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
                    <button type="submit" class="btn btn-primary">{{ __('cms.save_exchange_rates') }}</button>
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
            <h3 style="font-size: 1.25rem; font-weight: 700; color: #1f2937;">{{ __('cms.add_currency') }}</h3>
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
                    <label for="currency_code" class="form-label">{{ __('cms.currency_code') }} <span style="color: #ef4444;">*</span></label>
                    <input type="text" id="currency_code" name="code" required maxlength="3" class="form-input" placeholder="USD" style="text-transform: uppercase;">
                    @error('code')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="currency_name" class="form-label">{{ __('cms.currency_name') }} <span style="color: #ef4444;">*</span></label>
                    <input type="text" id="currency_name" name="name" required class="form-input" placeholder="{{ __('cms.currency_name_placeholder') }}">
                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="currency_symbol" class="form-label">{{ __('cms.symbol') }} <span style="color: #ef4444;">*</span></label>
                    <input type="text" id="currency_symbol" name="symbol" required maxlength="10" class="form-input" placeholder="$">
                    @error('symbol')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="checkbox" name="is_default" value="1">
                        <span>{{ __('cms.set_as_default_currency') }}</span>
                    </label>
                </div>
                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="checkbox" name="is_active" value="1" checked>
                        <span>{{ __('cms.active') }}</span>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" style="background-color: #6b7280; color: white;" onclick="closeAddCurrencyModal()">{{ __('cms.cancel') }}</button>
                <button type="submit" class="btn btn-primary">{{ __('cms.add_currency') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Currency Modal -->
<div id="editCurrencyModal" class="modal-overlay" style="display: none;">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header">
            <h3 style="font-size: 1.25rem; font-weight: 700; color: #1f2937;">{{ __('cms.edit_currency') }}</h3>
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
                    <label for="edit_currency_code" class="form-label">{{ __('cms.currency_code') }} <span style="color: #ef4444;">*</span></label>
                    <input type="text" id="edit_currency_code" name="code" required maxlength="3" class="form-input" placeholder="USD" style="text-transform: uppercase;">
                    @error('code')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="edit_currency_name" class="form-label">{{ __('cms.currency_name') }} <span style="color: #ef4444;">*</span></label>
                    <input type="text" id="edit_currency_name" name="name" required class="form-input" placeholder="{{ __('cms.currency_name_placeholder') }}">
                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="edit_currency_symbol" class="form-label">{{ __('cms.symbol') }} <span style="color: #ef4444;">*</span></label>
                    <input type="text" id="edit_currency_symbol" name="symbol" required maxlength="10" class="form-input" placeholder="$">
                    @error('symbol')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="checkbox" id="edit_is_default" name="is_default" value="1">
                        <span>{{ __('cms.set_as_default_currency') }}</span>
                    </label>
                </div>
                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="checkbox" id="edit_is_active" name="is_active" value="1">
                        <span>{{ __('cms.active') }}</span>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" style="background-color: #6b7280; color: white;" onclick="closeEditCurrencyModal()">{{ __('cms.cancel') }}</button>
                <button type="submit" class="btn btn-primary">{{ __('cms.update_currency') }}</button>
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

    .multiselect-trigger:hover {
        border-color: #099ecb;
    }

    .multiselect-trigger:focus-within {
        outline: 2px solid #099ecb;
        outline-offset: 2px;
    }

    .multiselect-option:hover {
        background-color: #f3f4f6;
    }

    .multiselect-option input[type="checkbox"]:checked + span {
        font-weight: 600;
        color: #099ecb;
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

        // Countries multi-select with tags
        const countriesTrigger = document.getElementById('countriesTrigger');
        const countriesDropdown = document.getElementById('countriesDropdown');
        const countryCheckboxes = document.querySelectorAll('.country-checkbox');
        const placeholderText = countriesTrigger ? countriesTrigger.querySelector('.placeholder-text') : null;
        const selectedTagsContainer = countriesTrigger ? countriesTrigger.querySelector('.selected-countries-tags') : null;
        const clearAllBtn = countriesTrigger ? countriesTrigger.querySelector('.clear-all-countries-btn') : null;
        const multiselectArrow = countriesTrigger ? countriesTrigger.querySelector('.multiselect-arrow') : null;

        if (countriesTrigger && countriesDropdown) {
            // Toggle dropdown
            countriesTrigger.addEventListener('click', function(e) {
                // Don't toggle if clicking on tags or clear button
                if (e.target.closest('.country-tag') || e.target.closest('.clear-all-countries-btn')) {
                    return;
                }
                e.stopPropagation();
                const isOpen = countriesDropdown.style.display === 'block';
                countriesDropdown.style.display = isOpen ? 'none' : 'block';
                if (multiselectArrow) {
                    multiselectArrow.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
                }
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!countriesTrigger.contains(e.target) && !countriesDropdown.contains(e.target)) {
                    countriesDropdown.style.display = 'none';
                    if (multiselectArrow) {
                        multiselectArrow.style.transform = 'rotate(0deg)';
                    }
                }
            });

            // Update selected countries display
            function updateSelectedCountries() {
                const checked = Array.from(countryCheckboxes).filter(cb => cb.checked);
                
                // Clear existing tags
                if (selectedTagsContainer) {
                    selectedTagsContainer.innerHTML = '';
                }
                
                if (checked.length > 0) {
                    if (placeholderText) placeholderText.style.display = 'none';
                    if (selectedTagsContainer) selectedTagsContainer.style.display = 'flex';
                    if (clearAllBtn) clearAllBtn.style.display = 'block';
                    
                    // Create tags for each selected country
                    checked.forEach(checkbox => {
                        const countryCode = checkbox.value;
                        const countryName = checkbox.getAttribute('data-country-name');
                        
                        const tag = document.createElement('div');
                        tag.className = 'country-tag';
                        tag.style.cssText = 'display: inline-flex; align-items: center; gap: 0.5rem; background-color: var(--primary-blue); color: white; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 500;';
                        tag.innerHTML = `
                            <span>${countryName}</span>
                            <button type="button" class="remove-country-tag" data-country-code="${countryCode}" style="background: none; border: none; color: white; cursor: pointer; padding: 0; display: flex; align-items: center; font-size: 1rem; line-height: 1;" title="Remove">×</button>
                        `;
                        
                        // Handle remove tag button
                        tag.querySelector('.remove-country-tag').addEventListener('click', function(e) {
                            e.stopPropagation();
                            const code = this.getAttribute('data-country-code');
                            const checkbox = document.querySelector(`.country-checkbox[value="${code}"]`);
                            if (checkbox) {
                                checkbox.checked = false;
                                updateSelectedCountries();
                            }
                        });
                        
                        if (selectedTagsContainer) {
                            selectedTagsContainer.appendChild(tag);
                        }
                    });
                } else {
                    if (placeholderText) placeholderText.style.display = 'inline';
                    if (selectedTagsContainer) selectedTagsContainer.style.display = 'none';
                    if (clearAllBtn) clearAllBtn.style.display = 'none';
                }
            }

            // Clear all countries
            if (clearAllBtn) {
                clearAllBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    countryCheckboxes.forEach(checkbox => {
                        checkbox.checked = false;
                    });
                    updateSelectedCountries();
                });
            }

            // Handle checkbox changes
            countryCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedCountries);
            });

            // Prevent dropdown from closing when clicking inside
            countriesDropdown.addEventListener('click', function(e) {
                e.stopPropagation();
            });

            // Initialize selected countries display
            updateSelectedCountries();

            // Add hover effect
            document.querySelectorAll('#countriesDropdown .multiselect-option').forEach(option => {
                option.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = '#f3f4f6';
                });
                option.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = 'white';
                });
            });
        }
    });
</script>
@endpush
@endsection

