<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Currency;
use App\Models\CurrencyExchangeRate;
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

        return view('settings.index', compact('settings', 'currencies', 'rateMatrix'));
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
        ]);

        $oldTimezone = Setting::get('timezone', 'UTC');
        $oldMultilingual = Setting::get('multilingual', '0');
        $oldDefaultLanguage = Setting::get('default_language', 'en');
        $oldMultiCurrency = Setting::get('multi_currency', '0');

        Setting::set('timezone', $validated['timezone']);
        Setting::set('multilingual', $validated['multilingual']);
        Setting::set('default_language', $validated['default_language']);
        Setting::set('multi_currency', $validated['multi_currency']);

        $oldValues = [
            'timezone' => $oldTimezone,
            'multilingual' => $oldMultilingual,
            'default_language' => $oldDefaultLanguage,
            'multi_currency' => $oldMultiCurrency,
        ];
        $newValues = [
            'timezone' => $validated['timezone'],
            'multilingual' => $validated['multilingual'],
            'default_language' => $validated['default_language'],
            'multi_currency' => $validated['multi_currency'],
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
            ->with('success', 'Currency added successfully.');
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
            ->with('success', 'Currency updated successfully.');
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
                ->with('error', 'Cannot delete the last currency.');
        }

        if ($currency->is_default) {
            return redirect()->route('settings.index')
                ->with('error', 'Cannot delete the default currency. Please set another currency as default first.');
        }

        $currencyCode = $currency->code;
        $currency->delete();

        $this->logAudit('deleted', null, "Currency deleted: {$currencyCode}");

        return redirect()->route('settings.index')
            ->with('success', 'Currency deleted successfully.');
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
                ->with('success', 'Exchange rates updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('settings.index')
                ->with('error', 'Failed to update exchange rates: ' . $e->getMessage());
        }
    }
}

