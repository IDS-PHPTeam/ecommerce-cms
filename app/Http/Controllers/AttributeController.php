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
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'values' => 'nullable|array',
            'values.*' => 'required|string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        $attribute = Attribute::create($validated);

        // Create attribute values
        if (isset($validated['values']) && is_array($validated['values'])) {
            foreach ($validated['values'] as $index => $value) {
                if (!empty(trim($value))) {
                    AttributeValue::create([
                        'attribute_id' => $attribute->id,
                        'value' => trim($value),
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
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'values' => 'nullable|array',
            'values.*' => 'required|string|max:255',
            'value_ids' => 'nullable|array',
            'value_ids.*' => 'exists:attribute_values,id',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['updated_by'] = Auth::id();

        $oldValues = $this->getOldValues($attribute, ['name', 'status']);
        $attribute->update($validated);
        $newValues = $this->getNewValues($validated, ['name', 'status']);

        // Update attribute values
        if (isset($validated['values']) && is_array($validated['values'])) {
            $existingValueIds = [];
            $valueIds = $validated['value_ids'] ?? [];

            foreach ($validated['values'] as $index => $value) {
                if (empty(trim($value))) continue;

                $valueId = isset($valueIds[$index]) ? $valueIds[$index] : null;

                if ($valueId) {
                    // Update existing value
                    AttributeValue::where('id', $valueId)
                        ->where('attribute_id', $attribute->id)
                        ->update([
                            'value' => trim($value),
                            'sort_order' => $index,
                            'updated_by' => Auth::id(),
                        ]);
                    $existingValueIds[] = $valueId;
                } else {
                    // Create new value
                    $newValue = AttributeValue::create([
                        'attribute_id' => $attribute->id,
                        'value' => trim($value),
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

