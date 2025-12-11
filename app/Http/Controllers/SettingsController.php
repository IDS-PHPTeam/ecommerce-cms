<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Currency;
use App\Models\CurrencyExchangeRate;
use App\Models\Country;
use App\Models\ShippingZone;
use App\Traits\LogsAudit;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    use LogsAudit;
    /**
     * Show the settings page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $settings = [
            'timezone' => Setting::get('timezone', 'UTC'),
            'multilingual' => Setting::get('multilingual', '0'),
            'default_language' => Setting::get('default_language', 'en'),
            'multi_currency' => Setting::get('multi_currency', '0'),
            'notify_by_email' => Setting::get('notify_by_email', '1'),
            'notify_by_push' => Setting::get('notify_by_push', '0'),
            'theme_mode' => Setting::get('theme_mode', 'light'),
        ];

        $currencies = Currency::orderBy('is_default', 'desc')
            ->orderBy('code')
            ->get();
        
        $exchangeRates = CurrencyExchangeRate::with(['fromCurrency', 'toCurrency'])->get();
        
        // Build a matrix of exchange rates for easier display
        $rateMatrix = [];
        foreach ($exchangeRates as $rate) {
            $rateMatrix[$rate->from_currency_id][$rate->to_currency_id] = $rate->rate;
        }

        $countries = Country::orderBy('country_name_en')->get();
        
        $shippingZones = ShippingZone::with('country')
            ->orderBy('country_code')
            ->get();
        
        $defaultCurrency = Currency::getDefault();
        
        // Get default currency ID from settings if multi-currency is disabled
        $defaultCurrencyId = null;
        if (($settings['multi_currency'] ?? '0') === '0' && $defaultCurrency) {
            $defaultCurrencyId = $defaultCurrency->id;
        }

        return view('settings.index', compact('settings', 'currencies', 'rateMatrix', 'countries', 'shippingZones', 'defaultCurrency', 'defaultCurrencyId'));
    }

    /**
     * Update the settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'timezone' => 'required|string|max:255',
            'multilingual' => 'required|in:0,1',
            'default_language' => 'required|in:en,ar',
            'multi_currency' => 'required|in:0,1',
            'default_currency_id' => 'required_if:multi_currency,0|nullable|exists:currencies,id',
            'notify_by_email' => 'nullable|in:0,1',
            'notify_by_push' => 'nullable|in:0,1',
            'theme_mode' => 'required|in:light,dark',
        ]);

        $oldTimezone = Setting::get('timezone', 'UTC');
        $oldMultilingual = Setting::get('multilingual', '0');
        $oldDefaultLanguage = Setting::get('default_language', 'en');
        $oldMultiCurrency = Setting::get('multi_currency', '0');
        $oldNotifyByEmail = Setting::get('notify_by_email', '1');
        $oldNotifyByPush = Setting::get('notify_by_push', '0');
        $oldThemeMode = Setting::get('theme_mode', 'light');

        Setting::set('timezone', $validated['timezone']);
        Setting::set('multilingual', $validated['multilingual']);
        Setting::set('default_language', $validated['default_language']);
        Setting::set('multi_currency', $validated['multi_currency']);
        Setting::set('notify_by_email', $validated['notify_by_email'] ?? '0');
        Setting::set('notify_by_push', $validated['notify_by_push'] ?? '0');
        Setting::set('theme_mode', $validated['theme_mode']);
        
        // If multi-currency is disabled, set the selected currency as default
        if ($validated['multi_currency'] === '0' && isset($validated['default_currency_id'])) {
            // Unset all other defaults
            Currency::where('is_default', true)->update(['is_default' => false]);
            // Set the selected currency as default
            $currency = Currency::find($validated['default_currency_id']);
            if ($currency) {
                $currency->is_default = true;
                $currency->is_active = true;
                $currency->updated_by = Auth::id();
                $currency->save();
            }
        }

        $oldValues = [
            'timezone' => $oldTimezone,
            'multilingual' => $oldMultilingual,
            'default_language' => $oldDefaultLanguage,
            'multi_currency' => $oldMultiCurrency,
            'notify_by_email' => $oldNotifyByEmail,
            'notify_by_push' => $oldNotifyByPush,
            'theme_mode' => $oldThemeMode,
        ];
        $newValues = [
            'timezone' => $validated['timezone'],
            'multilingual' => $validated['multilingual'],
            'default_language' => $validated['default_language'],
            'multi_currency' => $validated['multi_currency'],
            'notify_by_email' => $validated['notify_by_email'] ?? '0',
            'notify_by_push' => $validated['notify_by_push'] ?? '0',
            'theme_mode' => $validated['theme_mode'],
        ];

        $this->logAudit('updated', null, 'General settings updated', $oldValues, $newValues);

        return redirect()->route('settings.index')
            ->with('success', __('cms.settings_updated_successfully'));
    }

    /**
     * Store a new currency.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeCurrency(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:3|unique:currencies,code',
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:10',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        // If this is set as default, unset other defaults
        if (isset($validated['is_default']) && $validated['is_default']) {
            Currency::where('is_default', true)->update(['is_default' => false]);
        }

        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();
        $validated['is_default'] = $validated['is_default'] ?? false;
        $validated['is_active'] = $validated['is_active'] ?? true;

        $currency = Currency::create($validated);

        $this->logAudit('created', $currency, "Currency created: {$currency->code} - {$currency->name}");

        return redirect()->route('settings.index')
            ->with('success', __('cms.currency_added_successfully'));
    }

    /**
     * Update a currency.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateCurrency(Request $request, Currency $currency)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:3|unique:currencies,code,' . $currency->id,
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:10',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $oldValues = $currency->toArray();

        // If this is set as default, unset other defaults
        if (isset($validated['is_default']) && $validated['is_default']) {
            Currency::where('is_default', true)
                ->where('id', '!=', $currency->id)
                ->update(['is_default' => false]);
        }

        $validated['updated_by'] = Auth::id();
        $currency->update($validated);

        $this->logAudit('updated', $currency, "Currency updated: {$currency->code} - {$currency->name}", $oldValues, $currency->toArray());

        return redirect()->route('settings.index')
            ->with('success', __('cms.currency_updated_successfully'));
    }

    /**
     * Delete a currency.
     *
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteCurrency(Currency $currency)
    {
        // Don't allow deleting if it's the only currency or if it's default
        $currencyCount = Currency::count();
        if ($currencyCount <= 1) {
            return redirect()->route('settings.index')
                ->with('error', __('cms.cannot_delete_last_currency'));
        }

        if ($currency->is_default) {
            return redirect()->route('settings.index')
                ->with('error', __('cms.cannot_delete_default_currency'));
        }

        $currencyCode = $currency->code;
        $currency->delete();

        $this->logAudit('deleted', null, "Currency deleted: {$currencyCode}");

        return redirect()->route('settings.index')
            ->with('success', __('cms.currency_deleted_successfully'));
    }

    /**
     * Update exchange rates.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateExchangeRates(Request $request)
    {
        $validated = $request->validate([
            'rates' => 'required|array',
            'rates.*' => 'required|array',
            'rates.*.*' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            foreach ($validated['rates'] as $fromCurrencyId => $toCurrencies) {
                foreach ($toCurrencies as $toCurrencyId => $rate) {
                    if ($fromCurrencyId == $toCurrencyId) {
                        continue; // Skip same currency
                    }

                    if ($rate !== null && $rate > 0) {
                        CurrencyExchangeRate::updateOrCreate(
                            [
                                'from_currency_id' => $fromCurrencyId,
                                'to_currency_id' => $toCurrencyId,
                            ],
                            [
                                'rate' => $rate,
                                'updated_by' => Auth::id(),
                                'created_by' => Auth::id(),
                            ]
                        );
                    }
                }
            }

            DB::commit();
            $this->logAudit('updated', null, 'Exchange rates updated');

            return redirect()->route('settings.index')
                ->with('success', __('cms.exchange_rates_updated_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('settings.index')
                ->with('error', __('cms.failed_to_update_exchange_rates') . ': ' . $e->getMessage());
        }
    }

    /**
     * Update theme mode via AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTheme(Request $request)
    {
        $validated = $request->validate([
            'theme_mode' => 'required|in:light,dark',
        ]);

        Setting::set('theme_mode', $validated['theme_mode']);

        return response()->json([
            'success' => true,
            'theme_mode' => $validated['theme_mode']
        ]);
    }

    /**
     * Store a new shipping zone.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeShippingZone(Request $request)
    {
        $validated = $request->validate([
            'country_code' => 'required|string|max:3|exists:countries,country_code|unique:shipping_zones,country_code',
            'shipping_charge' => 'required|numeric|min:0',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        $shippingZone = ShippingZone::create($validated);
        $country = Country::where('country_code', $validated['country_code'])->first();

        $this->logAudit('created', $shippingZone, "Shipping zone created: {$country->country_name_en} - {$validated['shipping_charge']}");

        return redirect()->route('settings.index', ['#zones'])
            ->with('success', __('cms.shipping_zone_added_successfully'));
    }

    /**
     * Update a shipping zone.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShippingZone  $shippingZone
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateShippingZone(Request $request, ShippingZone $shippingZone)
    {
        $validated = $request->validate([
            'country_code' => 'required|string|max:3|exists:countries,country_code|unique:shipping_zones,country_code,' . $shippingZone->id,
            'shipping_charge' => 'required|numeric|min:0',
        ]);

        $oldValues = $shippingZone->toArray();
        $validated['updated_by'] = Auth::id();

        $shippingZone->update($validated);
        $country = Country::where('country_code', $validated['country_code'])->first();

        $this->logAudit('updated', $shippingZone, "Shipping zone updated: {$country->country_name_en} - {$validated['shipping_charge']}", $oldValues, $shippingZone->toArray());

        return redirect()->route('settings.index', ['#zones'])
            ->with('success', __('cms.shipping_zone_updated_successfully'));
    }

    /**
     * Delete a shipping zone.
     *
     * @param  \App\Models\ShippingZone  $shippingZone
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteShippingZone(ShippingZone $shippingZone)
    {
        $country = $shippingZone->country;
        $countryName = $country ? $country->country_name_en : $shippingZone->country_code;
        $shippingZone->delete();

        $this->logAudit('deleted', null, "Shipping zone deleted: {$countryName}");

        return redirect()->route('settings.index', ['#zones'])
            ->with('success', __('cms.shipping_zone_deleted_successfully'));
    }
}

