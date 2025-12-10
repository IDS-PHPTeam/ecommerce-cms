<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Currency;
use App\Models\CurrencyExchangeRate;
use App\Models\Country;
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
        // Get delivery countries and decode JSON if needed
        $deliveryCountriesSetting = Setting::get('delivery_countries', 'LB');
        $deliveryCountries = is_string($deliveryCountriesSetting) ? json_decode($deliveryCountriesSetting, true) : $deliveryCountriesSetting;
        if (!is_array($deliveryCountries)) {
            // Handle legacy single country value
            $deliveryCountries = [$deliveryCountriesSetting];
        }

        $settings = [
            'timezone' => Setting::get('timezone', 'UTC'),
            'multilingual' => Setting::get('multilingual', '0'),
            'default_language' => Setting::get('default_language', 'en'),
            'multi_currency' => Setting::get('multi_currency', '0'),
            'notify_by_email' => Setting::get('notify_by_email', '1'),
            'notify_by_push' => Setting::get('notify_by_push', '0'),
            'theme_mode' => Setting::get('theme_mode', 'light'),
            'delivery_countries' => $deliveryCountries,
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

        return view('settings.index', compact('settings', 'currencies', 'rateMatrix', 'countries'));
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
            'notify_by_email' => 'nullable|in:0,1',
            'notify_by_push' => 'nullable|in:0,1',
            'theme_mode' => 'required|in:light,dark',
            'delivery_countries' => 'nullable|array',
            'delivery_countries.*' => 'required|string|exists:countries,country_code',
        ]);

        $oldTimezone = Setting::get('timezone', 'UTC');
        $oldMultilingual = Setting::get('multilingual', '0');
        $oldDefaultLanguage = Setting::get('default_language', 'en');
        $oldMultiCurrency = Setting::get('multi_currency', '0');
        $oldNotifyByEmail = Setting::get('notify_by_email', '1');
        $oldNotifyByPush = Setting::get('notify_by_push', '0');
        $oldThemeMode = Setting::get('theme_mode', 'light');
        $oldDeliveryCountriesSetting = Setting::get('delivery_countries', 'LB');
        $oldDeliveryCountries = is_string($oldDeliveryCountriesSetting) ? json_decode($oldDeliveryCountriesSetting, true) : $oldDeliveryCountriesSetting;
        if (!is_array($oldDeliveryCountries)) {
            $oldDeliveryCountries = [$oldDeliveryCountriesSetting];
        }

        Setting::set('timezone', $validated['timezone']);
        Setting::set('multilingual', $validated['multilingual']);
        Setting::set('default_language', $validated['default_language']);
        Setting::set('multi_currency', $validated['multi_currency']);
        Setting::set('notify_by_email', $validated['notify_by_email'] ?? '0');
        Setting::set('notify_by_push', $validated['notify_by_push'] ?? '0');
        Setting::set('theme_mode', $validated['theme_mode']);
        // Store delivery countries as JSON array
        $deliveryCountries = $validated['delivery_countries'] ?? ['LB'];
        Setting::set('delivery_countries', json_encode($deliveryCountries));

        $oldValues = [
            'timezone' => $oldTimezone,
            'multilingual' => $oldMultilingual,
            'default_language' => $oldDefaultLanguage,
            'multi_currency' => $oldMultiCurrency,
            'notify_by_email' => $oldNotifyByEmail,
            'notify_by_push' => $oldNotifyByPush,
            'theme_mode' => $oldThemeMode,
            'delivery_countries' => $oldDeliveryCountries,
        ];
        $newValues = [
            'timezone' => $validated['timezone'],
            'multilingual' => $validated['multilingual'],
            'default_language' => $validated['default_language'],
            'multi_currency' => $validated['multi_currency'],
            'notify_by_email' => $validated['notify_by_email'] ?? '0',
            'notify_by_push' => $validated['notify_by_push'] ?? '0',
            'theme_mode' => $validated['theme_mode'],
            'delivery_countries' => $deliveryCountries,
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
}

