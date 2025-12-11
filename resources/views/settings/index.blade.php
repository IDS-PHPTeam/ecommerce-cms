@extends('layouts.app')

@section('title', __('cms.settings'))

@section('content')
<div class="card">
    <h2 class="section-header">{{ __('cms.settings') }}</h2>

    <!-- Tabs Navigation -->
    <div class="settings-tabs border-bottom-2 mb-6">
        <button class="settings-tab active" data-tab="general" onclick="switchTab('general')">
            {{ __('cms.general') }}
        </button>
        @if(($settings['multi_currency'] ?? '0') === '1')
        <button class="settings-tab" data-tab="currency" onclick="switchTab('currency')">
            {{ __('cms.multi_currency') }}
        </button>
        @endif
        <button class="settings-tab" data-tab="zones" onclick="switchTab('zones')">
            {{ __('cms.zones_areas_management') }}
        </button>
    </div>

    <!-- General Settings Tab -->
    <div id="general-tab" class="settings-tab-content active">
        <form method="POST" action="{{ route('settings.update') }}">
            @csrf
            @method('PUT')

            <!-- Grid Layout for Better Organization -->
            <div class="grid grid-auto-400 gap-4 mb-8">
                
                <!-- Left Column: Basic Settings -->
                <div class="flex flex-column gap-6">
                    <h3 class="settings-section-header">{{ __('cms.basic_settings') }}</h3>
                    
                    <div class="form-group">
                        <label for="timezone" class="form-label">{{ __('cms.timezone') }}</label>
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
                        <label class="form-label">{{ __('cms.multilingual') }}</label>
                        <div class="flex-center gap-3 mt-2">
                            <label class="radio-label">
                                <input type="radio" name="multilingual" value="1" {{ old('multilingual', $settings['multilingual']) == '1' ? 'checked' : '' }} required>
                                <span>{{ __('cms.yes') }}</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="multilingual" value="0" {{ old('multilingual', $settings['multilingual']) == '0' ? 'checked' : '' }} required>
                                <span>{{ __('cms.no') }}</span>
                            </label>
                        </div>
                        @error('multilingual')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="default_language" class="form-label">{{ __('cms.default_language') }}</label>
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
                        <label for="theme_mode" class="form-label">{{ __('cms.theme_mode') }}</label>
                        <div class="flex-center gap-3 mt-2">
                            <label class="radio-label">
                                <input type="radio" name="theme_mode" value="light" {{ old('theme_mode', $settings['theme_mode'] ?? 'light') == 'light' ? 'checked' : '' }} required>
                                <span>{{ __('cms.light_mode') }}</span>
                            </label>
                            <label class="radio-label">
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
                <div class="flex flex-column gap-6">
                    <h3 class="settings-section-header">{{ __('cms.additional_settings') }}</h3>
                    
                    <div class="form-group">
                        <label class="form-label">{{ __('cms.multi_currency') }}</label>
                        <div class="flex-center gap-3 mt-2">
                            <label class="radio-label">
                                <input type="radio" name="multi_currency" value="1" id="multi_currency_yes" {{ old('multi_currency', $settings['multi_currency'] ?? '0') == '1' ? 'checked' : '' }} required onchange="toggleDefaultCurrencySelector()">
                                <span>{{ __('cms.yes') }}</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="multi_currency" value="0" id="multi_currency_no" {{ old('multi_currency', $settings['multi_currency'] ?? '0') == '0' ? 'checked' : '' }} required onchange="toggleDefaultCurrencySelector()">
                                <span>{{ __('cms.no') }}</span>
                            </label>
                        </div>
                        @error('multi_currency')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group" id="default_currency_selector" style="display: {{ old('multi_currency', $settings['multi_currency'] ?? '0') == '0' ? 'block' : 'none' }};">
                        <label for="default_currency_id" class="form-label">{{ __('cms.default_currency') }} <span class="required-asterisk">*</span></label>
                        <select id="default_currency_id" name="default_currency_id" class="form-input">
                            <option value="">{{ __('cms.select_default_currency') }}</option>
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->id }}" {{ old('default_currency_id', $defaultCurrencyId ?? ($defaultCurrency ? $defaultCurrency->id : null)) == $currency->id ? 'selected' : '' }}>
                                    {{ $currency->code }} - {{ $currency->name }} ({{ $currency->symbol }})
                                </option>
                            @endforeach
                        </select>
                        @error('default_currency_id')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ __('cms.notify_by') }}</label>
                        <div class="flex-center gap-4 mt-2">
                            <label class="radio-label">
                                <input type="checkbox" name="notify_by_email" value="1" {{ old('notify_by_email', $settings['notify_by_email'] ?? '1') == '1' ? 'checked' : '' }}>
                                <span>{{ __('cms.email') }}</span>
                            </label>
                            <label class="radio-label">
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

            <!-- Action Buttons -->
            <div class="flex gap-4 mt-8 pt-6 border-top-2">
                <button type="submit" class="btn btn-primary">{{ __('cms.save') }}</button>
                <a href="{{ route('dashboard') }}" class="btn btn-gray">{{ __('cms.cancel') }}</a>
            </div>
        </form>
    </div>

    <!-- Multi-Currency Settings Tab -->
    <div id="currency-tab" class="settings-tab-content d-none">
        <div class="flex-between mb-6">
            <h3 class="section-subheader">{{ __('cms.multi_currency_settings') }}</h3>
            <button type="button" class="btn btn-primary" onclick="openAddCurrencyModal()">{{ __('cms.add_currency') }}</button>
        </div>

    <!-- Currency List -->
    <div class="mb-8">
        <h3 class="text-lg font-semibold mb-4">{{ __('cms.currencies') }}</h3>
        <div class="overflow-x-auto">
            <table class="w-full table-border-collapse">
                <thead>
                    <tr class="table-header-row">
                        <th class="table-cell font-semibold">{{ __('cms.code') }}</th>
                        <th class="table-cell font-semibold">{{ __('cms.name') }}</th>
                        <th class="table-cell font-semibold">{{ __('cms.symbol') }}</th>
                        <th class="table-cell font-semibold text-center">{{ __('cms.default') }}</th>
                        <th class="table-cell font-semibold text-center">{{ __('cms.active') }}</th>
                        <th class="table-cell font-semibold text-right">{{ __('cms.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($currencies as $currency)
                        <tr class="border-bottom">
                            <td class="table-cell"><strong>{{ $currency->code }}</strong></td>
                            <td class="table-cell">{{ $currency->name }}</td>
                            <td class="table-cell">{{ $currency->symbol }}</td>
                            <td class="table-cell text-center">
                                @if($currency->is_default)
                                    <span class="status-default">✓ {{ __('cms.default') }}</span>
                                @else
                                    <span class="status-dash">-</span>
                                @endif
                            </td>
                            <td class="table-cell text-center">
                                @if($currency->is_active)
                                    <span class="status-active">{{ __('cms.active') }}</span>
                                @else
                                    <span class="status-inactive">{{ __('cms.inactive') }}</span>
                                @endif
                            </td>
                            <td class="table-cell text-right">
                                <button type="button" class="btn btn-primary btn-small mr-2" onclick="openEditCurrencyModal({{ $currency->id }}, '{{ addslashes($currency->code) }}', '{{ addslashes($currency->name) }}', '{{ addslashes($currency->symbol) }}', {{ $currency->is_default ? 'true' : 'false' }}, {{ $currency->is_active ? 'true' : 'false' }})">{{ __('cms.edit') }}</button>
                                @if(!$currency->is_default && $currencies->count() > 1)
                                    <form method="POST" action="{{ route('settings.currencies.delete', $currency) }}" class="inline-form" onsubmit="return confirm('{{ __('cms.confirm_delete_currency') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-small bg-red-500 text-white">{{ __('cms.delete') }}</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-gray">{{ __('cms.no_currencies_found') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Exchange Rates Matrix -->
    @if($currencies->count() > 1)
        <div>
            <h3 class="section-heading mb-4">{{ __('cms.exchange_rates') }}</h3>
            <p class="text-tertiary text-sm mb-4">{{ __('cms.exchange_rates_description') }}</p>
            
            <form method="POST" action="{{ route('settings.exchange-rates.update') }}" id="exchangeRatesForm">
                @csrf
                @method('PUT')
                <div class="overflow-x-auto">
                    <table class="w-full table-border-collapse table-min-width-600">
                        <thead>
                            <tr class="table-header-row">
                                <th class="table-cell-header">{{ __('cms.from_to') }}</th>
                                @foreach($currencies as $toCurrency)
                                    <th class="table-cell-header text-center">{{ $toCurrency->code }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($currencies as $fromCurrency)
                                <tr class="table-row-border">
                                    <td class="table-cell-padding font-semibold">{{ $fromCurrency->code }}</td>
                                    @foreach($currencies as $toCurrency)
                                        <td class="table-cell-small">
                                            @if($fromCurrency->id == $toCurrency->id)
                                                <span class="text-quaternary">-</span>
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
                                                    class="exchange-rate-input rounded border border-gray-300 text-center exchange-rate-input-width"
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
                <div class="flex gap-4 mt-6">
                    <button type="submit" class="btn btn-primary">{{ __('cms.save_exchange_rates') }}</button>
                </div>
            </form>
        </div>
    @endif
    </div>

    <!-- Zones / Areas Management Tab -->
    <div id="zones-tab" class="settings-tab-content d-none">
        <div class="flex-between mb-6">
            <button type="button" class="btn btn-primary" onclick="openAddShippingZoneModal()">{{ __('cms.add_shipping_zone') }}</button>
        </div>

        <!-- Shipping Zones List -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold mb-4">{{ __('cms.shipping_zones') }}</h3>
            @if($defaultCurrency)
                <p class="text-tertiary text-sm mb-4">{{ __('cms.shipping_charges_in_currency', ['currency' => $defaultCurrency->code . ' (' . $defaultCurrency->symbol . ')']) }}</p>
            @endif
            <div class="overflow-x-auto">
                <table class="w-full table-border-collapse">
                    <thead>
                        <tr class="table-header-row">
                            <th class="table-cell font-semibold">{{ __('cms.country') }}</th>
                            <th class="table-cell font-semibold text-right">{{ __('cms.shipping_charge') }}</th>
                            <th class="table-cell font-semibold text-right">{{ __('cms.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shippingZones as $zone)
                            <tr class="border-bottom">
                                <td class="table-cell">
                                    @php
                                        $currentLocale = app()->getLocale();
                                        $countryName = $currentLocale === 'ar' ? ($zone->country->country_name_ar ?? $zone->country_code) : ($zone->country->country_name_en ?? $zone->country_code);
                                    @endphp
                                    <strong>{{ $countryName }}</strong>
                                    <span class="text-tertiary text-sm ml-2">({{ $zone->country_code }})</span>
                                </td>
                                <td class="table-cell text-right">
                                    @if($defaultCurrency)
                                        <strong>{{ number_format($zone->shipping_charge, 2) }} {{ $defaultCurrency->symbol }}</strong>
                                    @else
                                        <strong>{{ number_format($zone->shipping_charge, 2) }}</strong>
                                    @endif
                                </td>
                                <td class="table-cell text-right">
                                    <button type="button" class="btn btn-primary btn-small mr-2" onclick="openEditShippingZoneModal({{ $zone->id }}, '{{ addslashes($zone->country_code) }}', {{ $zone->shipping_charge }})">{{ __('cms.edit') }}</button>
                                    <form method="POST" action="{{ route('settings.shipping-zones.delete', $zone) }}" class="inline-form" onsubmit="return confirm('{{ __('cms.confirm_delete_shipping_zone') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-small bg-red-500 text-white">{{ __('cms.delete') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-6 text-center text-gray">{{ __('cms.no_shipping_zones_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Currency Modal -->
<div id="addCurrencyModal" class="modal-overlay modal-overlay-hidden">
    <div class="modal-content modal-content-max-width">
        <div class="modal-header">
            <h3 class="text-xl font-bold text-primary">{{ __('cms.add_currency') }}</h3>
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
                    <label for="currency_code" class="form-label">{{ __('cms.currency_code') }}</label>
                    <input type="text" id="currency_code" name="code" required maxlength="3" class="form-input uppercase" placeholder="USD">
                    @error('code')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="currency_name" class="form-label">{{ __('cms.currency_name') }}</label>
                    <input type="text" id="currency_name" name="name" required class="form-input" placeholder="{{ __('cms.currency_name_placeholder') }}">
                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="currency_symbol" class="form-label">{{ __('cms.symbol') }}</label>
                    <input type="text" id="currency_symbol" name="symbol" required maxlength="10" class="form-input" placeholder="$">
                    @error('symbol')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="radio-label">
                        <input type="checkbox" name="is_default" value="1">
                        <span>{{ __('cms.set_as_default_currency') }}</span>
                    </label>
                </div>
                <div class="form-group">
                    <label class="radio-label">
                        <input type="checkbox" name="is_active" value="1" checked>
                        <span>{{ __('cms.active') }}</span>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gray-600 text-white" onclick="closeAddCurrencyModal()">{{ __('cms.cancel') }}</button>
                <button type="submit" class="btn btn-primary">{{ __('cms.add_currency') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Add Shipping Zone Modal -->
<div id="addShippingZoneModal" class="modal-overlay modal-overlay-hidden">
    <div class="modal-content modal-content-max-width">
        <div class="modal-header">
            <h3 class="text-xl font-bold text-primary">{{ __('cms.add_shipping_zone') }}</h3>
            <button type="button" class="modal-close" onclick="closeAddShippingZoneModal()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('settings.shipping-zones.store') }}" id="addShippingZoneForm">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="shipping_zone_country" class="form-label">{{ __('cms.country') }} <span class="required-asterisk">*</span></label>
                    <select id="shipping_zone_country" name="country_code" required class="form-input">
                        <option value="">{{ __('cms.select_country') }}</option>
                        @php
                            $currentLocale = app()->getLocale();
                            $usedCountryCodes = $shippingZones->pluck('country_code')->toArray();
                        @endphp
                        @foreach($countries as $country)
                            @if(!in_array($country->country_code, $usedCountryCodes))
                                <option value="{{ $country->country_code }}">{{ $currentLocale === 'ar' ? $country->country_name_ar : $country->country_name_en }} ({{ $country->country_code }})</option>
                            @endif
                        @endforeach
                    </select>
                    @error('country_code')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="shipping_zone_charge" class="form-label">{{ __('cms.shipping_charge') }} <span class="required-asterisk">*</span></label>
                    <div class="flex items-center gap-2">
                        <input type="number" id="shipping_zone_charge" name="shipping_charge" required step="0.01" min="0" class="form-input" placeholder="0.00">
                        @if($defaultCurrency)
                            <span class="text-secondary">{{ $defaultCurrency->code }} ({{ $defaultCurrency->symbol }})</span>
                        @endif
                    </div>
                    @error('shipping_charge')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gray-600 text-white" onclick="closeAddShippingZoneModal()">{{ __('cms.cancel') }}</button>
                <button type="submit" class="btn btn-primary">{{ __('cms.add_shipping_zone') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Shipping Zone Modal -->
<div id="editShippingZoneModal" class="modal-overlay modal-overlay-hidden">
    <div class="modal-content modal-content-max-width">
        <div class="modal-header">
            <h3 class="text-xl font-bold text-primary">{{ __('cms.edit_shipping_zone') }}</h3>
            <button type="button" class="modal-close" onclick="closeEditShippingZoneModal()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form method="POST" id="editShippingZoneForm" action="">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label for="edit_shipping_zone_country" class="form-label">{{ __('cms.country') }} <span class="required-asterisk">*</span></label>
                    <select id="edit_shipping_zone_country" name="country_code" required class="form-input">
                        <option value="">{{ __('cms.select_country') }}</option>
                        @php
                            $currentLocale = app()->getLocale();
                        @endphp
                        @foreach($countries as $country)
                            <option value="{{ $country->country_code }}">{{ $currentLocale === 'ar' ? $country->country_name_ar : $country->country_name_en }} ({{ $country->country_code }})</option>
                        @endforeach
                    </select>
                    @error('country_code')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="edit_shipping_zone_charge" class="form-label">{{ __('cms.shipping_charge') }} <span class="required-asterisk">*</span></label>
                    <div class="flex items-center gap-2">
                        <input type="number" id="edit_shipping_zone_charge" name="shipping_charge" required step="0.01" min="0" class="form-input" placeholder="0.00">
                        @if($defaultCurrency)
                            <span class="text-secondary">{{ $defaultCurrency->code }} ({{ $defaultCurrency->symbol }})</span>
                        @endif
                    </div>
                    @error('shipping_charge')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gray-600 text-white" onclick="closeEditShippingZoneModal()">{{ __('cms.cancel') }}</button>
                <button type="submit" class="btn btn-primary">{{ __('cms.update_shipping_zone') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Currency Modal -->
<div id="editCurrencyModal" class="modal-overlay modal-overlay-hidden">
    <div class="modal-content modal-content-max-width">
        <div class="modal-header">
            <h3 class="text-xl font-bold text-primary">{{ __('cms.edit_currency') }}</h3>
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
                    <label for="edit_currency_code" class="form-label">{{ __('cms.currency_code') }}</label>
                    <input type="text" id="edit_currency_code" name="code" required maxlength="3" class="form-input uppercase" placeholder="USD">
                    @error('code')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="edit_currency_name" class="form-label">{{ __('cms.currency_name') }}</label>
                    <input type="text" id="edit_currency_name" name="name" required class="form-input" placeholder="{{ __('cms.currency_name_placeholder') }}">
                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="edit_currency_symbol" class="form-label">{{ __('cms.symbol') }}</label>
                    <input type="text" id="edit_currency_symbol" name="symbol" required maxlength="10" class="form-input" placeholder="$">
                    @error('symbol')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="radio-label">
                        <input type="checkbox" id="edit_is_default" name="is_default" value="1">
                        <span>{{ __('cms.set_as_default_currency') }}</span>
                    </label>
                </div>
                <div class="form-group">
                    <label class="radio-label">
                        <input type="checkbox" id="edit_is_active" name="is_active" value="1">
                        <span>{{ __('cms.active') }}</span>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gray-600 text-white" onclick="closeEditCurrencyModal()">{{ __('cms.cancel') }}</button>
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

    .settings-tab-content.d-none {
        display: none !important;
    }

    .settings-tab-content.active {
        display: block !important;
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
            content.classList.add('d-none');
        });

        // Remove active class from all tabs
        document.querySelectorAll('.settings-tab').forEach(tab => {
            tab.classList.remove('active');
        });

        // Show selected tab content
        const selectedContent = document.getElementById(tabName + '-tab');
        if (selectedContent) {
            selectedContent.classList.remove('d-none');
            selectedContent.classList.add('active');
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

    // Toggle default currency selector based on multi-currency setting
    function toggleDefaultCurrencySelector() {
        const multiCurrencyNo = document.getElementById('multi_currency_no');
        const defaultCurrencySelector = document.getElementById('default_currency_selector');
        const defaultCurrencySelect = document.getElementById('default_currency_id');
        
        if (multiCurrencyNo && defaultCurrencySelector) {
            if (multiCurrencyNo.checked) {
                defaultCurrencySelector.style.display = 'block';
                if (defaultCurrencySelect) {
                    defaultCurrencySelect.setAttribute('required', 'required');
                }
            } else {
                defaultCurrencySelector.style.display = 'none';
                if (defaultCurrencySelect) {
                    defaultCurrencySelect.removeAttribute('required');
                }
            }
        }
    }

    // Check URL hash on page load
    document.addEventListener('DOMContentLoaded', function() {
        const hash = window.location.hash.substring(1);
        if (hash === 'currency' || hash === 'general' || hash === 'zones') {
            switchTab(hash);
        }
        
        // Initialize default currency selector visibility
        toggleDefaultCurrencySelector();
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

    function openAddShippingZoneModal() {
        document.getElementById('addShippingZoneModal').style.display = 'flex';
        document.getElementById('addShippingZoneForm').reset();
    }

    function closeAddShippingZoneModal() {
        document.getElementById('addShippingZoneModal').style.display = 'none';
    }

    function openEditShippingZoneModal(id, countryCode, shippingCharge) {
        document.getElementById('editShippingZoneModal').style.display = 'flex';
        document.getElementById('editShippingZoneForm').action = '{{ route("settings.shipping-zones.update", ":id") }}'.replace(':id', id);
        document.getElementById('edit_shipping_zone_country').value = countryCode;
        document.getElementById('edit_shipping_zone_charge').value = shippingCharge;
    }

    function closeEditShippingZoneModal() {
        document.getElementById('editShippingZoneModal').style.display = 'none';
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
        const addCurrencyModal = document.getElementById('addCurrencyModal');
        const editCurrencyModal = document.getElementById('editCurrencyModal');
        const addShippingZoneModal = document.getElementById('addShippingZoneModal');
        const editShippingZoneModal = document.getElementById('editShippingZoneModal');
        if (event.target == addCurrencyModal) {
            closeAddCurrencyModal();
        }
        if (event.target == editCurrencyModal) {
            closeEditCurrencyModal();
        }
        if (event.target == addShippingZoneModal) {
            closeAddShippingZoneModal();
        }
        if (event.target == editShippingZoneModal) {
            closeEditShippingZoneModal();
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

