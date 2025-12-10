<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Traits\LogsAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AttributeController extends Controller
{
    use LogsAudit;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Attribute::with('values');

        // Filter by name
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attributes = $query->latest()->paginate(15);

        return view('attributes.index', compact('attributes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('attributes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:attributes,name',
            'name_en' => 'nullable|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'values' => 'nullable|array',
            'values.*' => 'nullable|string|max:255',
            'values_en' => 'nullable|array',
            'values_en.*' => 'nullable|string|max:255',
            'values_ar' => 'nullable|array',
            'values_ar.*' => 'nullable|string|max:255',
        ]);

        // Determine the main name and description based on current locale or fallback
        $currentLocale = app()->getLocale();
        if (empty($validated['name']) && !empty($validated['name_' . $currentLocale])) {
            $validated['name'] = $validated['name_' . $currentLocale];
        } elseif (empty($validated['name']) && !empty($validated['name_en'])) {
            $validated['name'] = $validated['name_en'];
        } elseif (empty($validated['name']) && !empty($validated['name_ar'])) {
            $validated['name'] = $validated['name_ar'];
        }

        if (empty($validated['description']) && !empty($validated['description_' . $currentLocale])) {
            $validated['description'] = $validated['description_' . $currentLocale];
        } elseif (empty($validated['description']) && !empty($validated['description_en'])) {
            $validated['description'] = $validated['description_en'];
        } elseif (empty($validated['description']) && !empty($validated['description_ar'])) {
            $validated['description'] = $validated['description_ar'];
        }

        $validated['slug'] = Str::slug($validated['name']);
        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        $attribute = Attribute::create($validated);

        // Create attribute values
        $valuesEn = $validated['values_en'] ?? [];
        $valuesAr = $validated['values_ar'] ?? [];
        $values = $validated['values'] ?? [];

        if (!empty($valuesEn) || !empty($valuesAr) || !empty($values)) {
            $maxIndex = max(count($valuesEn), count($valuesAr), count($values));

            for ($index = 0; $index < $maxIndex; $index++) {
                $valueEn = trim($valuesEn[$index] ?? '');
                $valueAr = trim($valuesAr[$index] ?? '');
                $value = trim($values[$index] ?? '');

                // Determine the main value based on current locale or fallback
                if (empty($value)) {
                    if (!empty($valueAr) && $currentLocale === 'ar') {
                        $value = $valueAr;
                    } elseif (!empty($valueEn)) {
                        $value = $valueEn;
                    } elseif (!empty($valueAr)) {
                        $value = $valueAr;
                    }
                }

                if (!empty($value)) {
                    AttributeValue::create([
                        'attribute_id' => $attribute->id,
                        'value' => $value,
                        'value_en' => $valueEn ?: null,
                        'value_ar' => $valueAr ?: null,
                        'sort_order' => $index,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                    ]);
                }
            }
        }

        $this->logAudit('created', $attribute, "Attribute created: {$attribute->name}");

        return redirect()->route('attributes.index')
            ->with('success', 'Attribute created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Attribute  $attribute
     * @return \Illuminate\Http\Response
     */
    public function edit(Attribute $attribute)
    {
        $attribute->load('values');
        return view('attributes.edit', compact('attribute'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Attribute  $attribute
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attribute $attribute)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:attributes,name,' . $attribute->id,
            'name_en' => 'nullable|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'values' => 'nullable|array',
            'values.*' => 'nullable|string|max:255',
            'values_en' => 'nullable|array',
            'values_en.*' => 'nullable|string|max:255',
            'values_ar' => 'nullable|array',
            'values_ar.*' => 'nullable|string|max:255',
            'value_ids' => 'nullable|array',
            'value_ids.*' => 'exists:attribute_values,id',
        ]);

        // Determine the main name and description based on current locale or fallback
        $currentLocale = app()->getLocale();
        if (empty($validated['name']) && !empty($validated['name_' . $currentLocale])) {
            $validated['name'] = $validated['name_' . $currentLocale];
        } elseif (empty($validated['name']) && !empty($validated['name_en'])) {
            $validated['name'] = $validated['name_en'];
        } elseif (empty($validated['name']) && !empty($validated['name_ar'])) {
            $validated['name'] = $validated['name_ar'];
        }

        if (empty($validated['description']) && !empty($validated['description_' . $currentLocale])) {
            $validated['description'] = $validated['description_' . $currentLocale];
        } elseif (empty($validated['description']) && !empty($validated['description_en'])) {
            $validated['description'] = $validated['description_en'];
        } elseif (empty($validated['description']) && !empty($validated['description_ar'])) {
            $validated['description'] = $validated['description_ar'];
        }

        $validated['slug'] = Str::slug($validated['name']);
        $validated['updated_by'] = Auth::id();

        $oldValues = $this->getOldValues($attribute, ['name', 'name_en', 'name_ar', 'description', 'description_en', 'description_ar', 'status']);
        $attribute->update($validated);
        $newValues = $this->getNewValues($validated, ['name', 'name_en', 'name_ar', 'description', 'description_en', 'description_ar', 'status']);

        // Update attribute values
        $valuesEn = $validated['values_en'] ?? [];
        $valuesAr = $validated['values_ar'] ?? [];
        $values = $validated['values'] ?? [];
        $valueIds = $validated['value_ids'] ?? [];

        if (!empty($valuesEn) || !empty($valuesAr) || !empty($values)) {
            $existingValueIds = [];
            $maxIndex = max(count($valuesEn), count($valuesAr), count($values));

            for ($index = 0; $index < $maxIndex; $index++) {
                $valueEn = trim($valuesEn[$index] ?? '');
                $valueAr = trim($valuesAr[$index] ?? '');
                $value = trim($values[$index] ?? '');

                // Determine the main value based on current locale or fallback
                if (empty($value)) {
                    if (!empty($valueAr) && $currentLocale === 'ar') {
                        $value = $valueAr;
                    } elseif (!empty($valueEn)) {
                        $value = $valueEn;
                    } elseif (!empty($valueAr)) {
                        $value = $valueAr;
                    }
                }

                if (empty($value)) continue;

                $valueId = isset($valueIds[$index]) && !empty($valueIds[$index]) ? $valueIds[$index] : null;

                if ($valueId) {
                    // Update existing value
                    AttributeValue::where('id', $valueId)
                        ->where('attribute_id', $attribute->id)
                        ->update([
                            'value' => $value,
                            'value_en' => $valueEn ?: null,
                            'value_ar' => $valueAr ?: null,
                            'sort_order' => $index,
                            'updated_by' => Auth::id(),
                        ]);
                    $existingValueIds[] = $valueId;
                } else {
                    // Create new value
                    $newValue = AttributeValue::create([
                        'attribute_id' => $attribute->id,
                        'value' => $value,
                        'value_en' => $valueEn ?: null,
                        'value_ar' => $valueAr ?: null,
                        'sort_order' => $index,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                    ]);
                    $existingValueIds[] = $newValue->id;
                }
            }

            // Delete removed values
            AttributeValue::where('attribute_id', $attribute->id)
                ->whereNotIn('id', $existingValueIds)
                ->delete();
        }

        $this->logAudit('updated', $attribute, "Attribute updated: {$attribute->name}", $oldValues, $newValues);

        return redirect()->route('attributes.index')
            ->with('success', 'Attribute updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attribute  $attribute
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attribute $attribute)
    {
        $attributeName = $attribute->name;

        $this->logAudit('deleted', $attribute, "Attribute deleted: {$attributeName}");

        $attribute->delete();

        return redirect()->route('attributes.index')
            ->with('success', 'Attribute deleted successfully.');
    }
}




