<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductGallery;
use App\Models\ProductVariant;
use App\Models\ProductVariantAttribute;
use App\Traits\LogsAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    use LogsAudit;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Product::with('categories');

        // Filter by name
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category (many-to-many)
        if ($request->filled('category')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        // Filter by product type
        if ($request->filled('product_type')) {
            $query->where('product_type', $request->product_type);
        }

        // Filter by stock status
        if ($request->filled('in_stock')) {
            if ($request->in_stock == '1') {
                // In stock: either track_stock with quantity > 0, or stock_status = 'in_stock'
                $query->where(function($q) {
                    $q->where(function($sq) {
                        $sq->where('track_stock', true)->where('stock_quantity', '>', 0);
                    })->orWhere(function($sq) {
                        $sq->where('track_stock', false)->where('stock_status', 'in_stock');
                    });
                });
            } else {
                // Out of stock: either track_stock with quantity = 0, or stock_status != 'in_stock'
                $query->where(function($q) {
                    $q->where(function($sq) {
                        $sq->where('track_stock', true)->where(function($qsq) {
                            $qsq->whereNull('stock_quantity')->orWhere('stock_quantity', '<=', 0);
                        });
                    })->orWhere(function($sq) {
                        $sq->where('track_stock', false)->where('stock_status', '!=', 'in_stock');
                    });
                });
            }
        }

        // Filter by on sale
        if ($request->filled('on_sale')) {
            if ($request->on_sale == '1') {
                $query->where(function($q) {
                    $q->whereNotNull('sale_price')->where('sale_price', '>', 0)
                      ->orWhereHas('variants', function($vq) {
                          $vq->whereNotNull('sale_price')->where('sale_price', '>', 0);
                      });
                });
            }
        }

        $products = $query->latest()->paginate(15);
        $categories = Category::where('status', 'active')->orderBy('name')->get();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('status', 'active')->orderBy('name')->get();
        $attributes = Attribute::where('status', 'active')->with('values')->orderBy('name')->get();
        return view('products.create', compact('categories', 'attributes'));
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'product_type' => 'required|in:simple,variable',
            'featured_image' => 'nullable|image|max:2048',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'gallery' => 'nullable|array',
            'gallery.*.media_path' => 'nullable|string',
            'gallery.*.media_type' => 'nullable|in:image,video',
            // Simple product fields
            'price' => 'required_if:product_type,simple|nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'track_stock' => 'nullable|boolean',
            'stock_quantity' => 'nullable|integer|min:0',
            'stock_status' => 'nullable|in:in_stock,out_of_stock,on_backorder',
            // Variable product fields
            'variants' => 'required_if:product_type,variable|nullable|array',
            'variants.*.description' => 'nullable|string',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.sale_price' => 'nullable|numeric|min:0',
            'variants.*.track_stock' => 'nullable|boolean',
            'variants.*.stock_quantity' => 'nullable|integer|min:0',
            'variants.*.stock_status' => 'nullable|in:in_stock,out_of_stock,on_backorder',
            'variants.*.image' => 'nullable|string',
            'variants.*.attributes' => 'nullable|array',
            'variants.*.attributes.*.name' => 'required_with:variants.*.attributes|string',
            'variants.*.attributes.*.value' => 'required_with:variants.*.attributes|string',
            'status' => 'required|in:active,inactive',
        ], [
            'sale_price.lt' => 'The sale price must be less than the regular price.',
            'variants.*.sale_price.lt' => 'The variant sale price must be less than the variant regular price.',
        ]);

        // Validate sale price is less than price
        if ($validated['product_type'] === 'simple') {
            if (isset($validated['sale_price']) && $validated['sale_price'] > 0 && 
                isset($validated['price']) && $validated['sale_price'] >= $validated['price']) {
                return back()->withErrors(['sale_price' => 'The sale price must be less than the regular price.'])->withInput();
            }
        }

        DB::beginTransaction();
        try {
            // Handle featured image
            if ($request->hasFile('featured_image')) {
                $year = date('Y');
                $month = date('m');
                $validated['featured_image'] = $request->file('featured_image')->store("{$year}/{$month}", 'public');
            } elseif ($request->filled('selected_media_path')) {
                $validated['featured_image'] = $request->selected_media_path;
            }

            // Set created_by
            $validated['created_by'] = Auth::id();
            $validated['updated_by'] = Auth::id();

            // Create product
            $product = Product::create($validated);

            // Attach categories
            if (isset($validated['categories'])) {
                $product->categories()->sync($validated['categories']);
            }

            // Handle gallery
            if (isset($validated['gallery']) && is_array($validated['gallery'])) {
                foreach ($validated['gallery'] as $index => $galleryItem) {
                    if (!empty($galleryItem['media_path'])) {
                        ProductGallery::create([
                            'product_id' => $product->id,
                            'media_path' => $galleryItem['media_path'],
                            'media_type' => $galleryItem['media_type'] ?? 'image',
                            'sort_order' => $index,
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
                        ]);
                    }
                }
            }

            // Handle variants for variable products
            if ($validated['product_type'] === 'variable' && isset($validated['variants'])) {
                foreach ($validated['variants'] as $variantIndex => $variantData) {
                    // Validate variant sale price
                    if (isset($variantData['sale_price']) && $variantData['sale_price'] > 0 && 
                        isset($variantData['price']) && $variantData['sale_price'] >= $variantData['price']) {
                        DB::rollBack();
                        return back()->withErrors(["variants.{$variantIndex}.sale_price" => 'The variant sale price must be less than the variant regular price.'])->withInput();
                    }

                    $variant = ProductVariant::create([
                        'product_id' => $product->id,
                        'description' => $variantData['description'] ?? null,
                        'price' => $variantData['price'],
                        'sale_price' => $variantData['sale_price'] ?? null,
                        'track_stock' => isset($variantData['track_stock']) ? (bool)$variantData['track_stock'] : true,
                        'stock_quantity' => (isset($variantData['track_stock']) && $variantData['track_stock']) ? ($variantData['stock_quantity'] ?? null) : null,
                        'stock_status' => (isset($variantData['track_stock']) && $variantData['track_stock']) ? null : ($variantData['stock_status'] ?? 'in_stock'),
                        'image' => $variantData['image'] ?? null,
                        'sort_order' => $variantIndex,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                    ]);

                    // Handle variant attributes
                    if (isset($variantData['attributes']) && is_array($variantData['attributes'])) {
                        foreach ($variantData['attributes'] as $attr) {
                            if (isset($attr['attribute_id']) && isset($attr['value_id'])) {
                                // Get attribute and value names
                                $attribute = Attribute::find($attr['attribute_id']);
                                $attributeValue = AttributeValue::find($attr['value_id']);
                                
                                if ($attribute && $attributeValue) {
                                    ProductVariantAttribute::create([
                                        'variant_id' => $variant->id,
                                        'attribute_name' => $attribute->name,
                                        'attribute_value' => $attributeValue->value,
                                        'created_by' => Auth::id(),
                                        'updated_by' => Auth::id(),
                                    ]);
                                }
                            } elseif (isset($attr['attribute_name']) && isset($attr['value_id'])) {
                                // Fallback: if attribute_name is provided directly
                                $attributeValue = AttributeValue::find($attr['value_id']);
                                if ($attributeValue) {
                                    ProductVariantAttribute::create([
                                        'variant_id' => $variant->id,
                                        'attribute_name' => $attr['attribute_name'],
                                        'attribute_value' => $attributeValue->value,
                                        'created_by' => Auth::id(),
                                        'updated_by' => Auth::id(),
                                    ]);
                                }
                            }
                        }
                    }
                }
            }

            DB::commit();

            $this->logAudit('created', $product, "Product created: {$product->name}");

            return redirect()->route('products.edit', $product)
                ->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'An error occurred while creating the product.'])->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $product->load(['categories', 'gallery', 'variants.attributes']);
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $product->load(['categories', 'gallery', 'variants.attributes']);
        $categories = Category::where('status', 'active')->orderBy('name')->get();
        $attributes = Attribute::where('status', 'active')->with('values')->orderBy('name')->get();
        return view('products.edit', compact('product', 'categories', 'attributes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'product_type' => 'required|in:simple,variable',
            'featured_image' => 'nullable|image|max:2048',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'gallery' => 'nullable|array',
            'gallery.*.media_path' => 'nullable|string',
            'gallery.*.media_type' => 'nullable|in:image,video',
            'gallery.*.id' => 'nullable|sometimes|exists:product_galleries,id',
            // Simple product fields
            'price' => 'required_if:product_type,simple|nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'track_stock' => 'nullable|boolean',
            'stock_quantity' => 'nullable|integer|min:0',
            'stock_status' => 'nullable|in:in_stock,out_of_stock,on_backorder',
            // Variable product fields
            'variants' => 'required_if:product_type,variable|nullable|array',
            'variants.*.id' => 'nullable|exists:product_variants,id',
            'variants.*.description' => 'nullable|string',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.sale_price' => 'nullable|numeric|min:0',
            'variants.*.track_stock' => 'nullable|boolean',
            'variants.*.stock_quantity' => 'nullable|integer|min:0',
            'variants.*.stock_status' => 'nullable|in:in_stock,out_of_stock,on_backorder',
            'variants.*.image' => 'nullable|string',
            'variants.*.attributes' => 'nullable|array',
            'variants.*.attributes.*.id' => 'nullable|exists:product_variant_attributes,id',
            'variants.*.attributes.*.name' => 'required_with:variants.*.attributes|string',
            'variants.*.attributes.*.value' => 'required_with:variants.*.attributes|string',
            'status' => 'required|in:active,inactive',
            'delete_gallery' => 'nullable|array',
            'delete_gallery.*' => 'exists:product_galleries,id',
            'delete_variants' => 'nullable|array',
            'delete_variants.*' => 'exists:product_variants,id',
        ], [
            'sale_price.lt' => 'The sale price must be less than the regular price.',
            'variants.*.sale_price.lt' => 'The variant sale price must be less than the variant regular price.',
        ]);

        // Validate sale price
        if ($validated['product_type'] === 'simple') {
            if (isset($validated['sale_price']) && $validated['sale_price'] > 0 && 
                isset($validated['price']) && $validated['sale_price'] >= $validated['price']) {
                return back()->withErrors(['sale_price' => 'The sale price must be less than the regular price.'])->withInput();
            }
        }

        DB::beginTransaction();
        try {
            // Handle featured image
            if ($request->hasFile('featured_image')) {
                if ($product->featured_image) {
                    Storage::disk('public')->delete($product->featured_image);
                }
                $year = date('Y');
                $month = date('m');
                $validated['featured_image'] = $request->file('featured_image')->store("{$year}/{$month}", 'public');
            } elseif ($request->filled('selected_media_path')) {
                // Don't delete the old featured image file - keep it in storage for reuse
                // Only update the database reference
                $validated['featured_image'] = $request->selected_media_path;
            } elseif ($request->input('delete_current_image') == '1') {
                // Don't delete the featured image file - keep it in storage for reuse
                // Only remove the database reference
                $validated['featured_image'] = null;
            }

            $validated['updated_by'] = Auth::id();

            // Update product
            $oldValues = $this->getOldValues($product, ['name', 'description', 'product_type', 'price', 'sale_price', 'status']);
            $product->update($validated);
            $newValues = $this->getNewValues($validated, ['name', 'description', 'product_type', 'price', 'sale_price', 'status']);

            // Sync categories
            if (isset($validated['categories'])) {
                $product->categories()->sync($validated['categories']);
            } else {
                $product->categories()->detach();
            }

            // Handle gallery deletions
            // Note: We only delete the database record, not the actual file from storage
            // This allows the media to be reused for other products
            if (isset($validated['delete_gallery'])) {
                ProductGallery::whereIn('id', $validated['delete_gallery'])->delete();
            }

            // Handle gallery updates/additions
            if (isset($validated['gallery']) && is_array($validated['gallery'])) {
                $existingGalleryIds = [];
                $newGalleryIds = [];
                $deletedGalleryIds = isset($validated['delete_gallery']) ? $validated['delete_gallery'] : [];
                
                \Log::info('Processing gallery', [
                    'gallery_count' => count($validated['gallery']),
                    'gallery_data' => $validated['gallery'],
                    'deleted_ids' => $deletedGalleryIds
                ]);
                
                foreach ($validated['gallery'] as $index => $galleryItem) {
                    // Check if media_path exists and is not empty
                    if (isset($galleryItem['media_path']) && !empty(trim($galleryItem['media_path']))) {
                        // Check if this is an existing item (has valid ID and not marked for deletion)
                        $hasValidId = isset($galleryItem['id']) && 
                                     !empty($galleryItem['id']) && 
                                     is_numeric($galleryItem['id']) && 
                                     !in_array($galleryItem['id'], $deletedGalleryIds);
                        
                        if ($hasValidId) {
                            // Update existing (only if not marked for deletion)
                            ProductGallery::where('id', $galleryItem['id'])
                                ->update([
                                    'media_path' => $galleryItem['media_path'],
                                    'media_type' => $galleryItem['media_type'] ?? 'image',
                                    'sort_order' => $index,
                                    'updated_by' => Auth::id(),
                                ]);
                            $existingGalleryIds[] = $galleryItem['id'];
                            \Log::info('Updated existing gallery item', ['id' => $galleryItem['id'], 'index' => $index]);
                        } else {
                            // Create new - no ID, empty ID, or invalid ID means it's a new item
                            $newGalleryItem = ProductGallery::create([
                                'product_id' => $product->id,
                                'media_path' => $galleryItem['media_path'],
                                'media_type' => $galleryItem['media_type'] ?? 'image',
                                'sort_order' => $index,
                                'created_by' => Auth::id(),
                                'updated_by' => Auth::id(),
                            ]);
                            $newGalleryIds[] = $newGalleryItem->id;
                            \Log::info('Created new gallery item', [
                                'id' => $newGalleryItem->id, 
                                'index' => $index, 
                                'path' => $galleryItem['media_path'],
                                'product_id' => $product->id
                            ]);
                        }
                    } else {
                        \Log::warning('Skipping gallery item - empty media_path', ['index' => $index, 'item' => $galleryItem]);
                    }
                }
                
                // Combine existing and new IDs to protect them from deletion
                $allKeptGalleryIds = array_merge($existingGalleryIds, $newGalleryIds);
                
                // Delete gallery items not in the submitted form (but not those already deleted via delete_gallery)
                // Only delete items that were NOT in the submitted form at all
                if (!empty($allKeptGalleryIds)) {
                    ProductGallery::where('product_id', $product->id)
                        ->whereNotIn('id', $allKeptGalleryIds)
                        ->whereNotIn('id', $deletedGalleryIds)
                        ->delete();
                } elseif (empty($validated['gallery']) || count($validated['gallery']) === 0) {
                    // If no gallery items were submitted, delete all except those explicitly marked for deletion
                    // But only if the gallery array was actually provided (empty array)
                    ProductGallery::where('product_id', $product->id)
                        ->whereNotIn('id', $deletedGalleryIds)
                        ->delete();
                }
            } else {
                // If gallery array is not provided, only delete items explicitly marked for deletion
                // Don't delete all items - this allows the gallery to remain unchanged if not in the form
            }

            // Handle variants for variable products
            if ($validated['product_type'] === 'variable' && isset($validated['variants'])) {
                // Delete removed variants
                if (isset($validated['delete_variants'])) {
                    $variantsToDelete = ProductVariant::whereIn('id', $validated['delete_variants'])->get();
                    foreach ($variantsToDelete as $variant) {
                        if ($variant->image) {
                            Storage::disk('public')->delete($variant->image);
                        }
                    }
                    ProductVariant::whereIn('id', $validated['delete_variants'])->delete();
                }

                $existingVariantIds = [];
                foreach ($validated['variants'] as $variantIndex => $variantData) {
                    // Validate variant sale price
                    if (isset($variantData['sale_price']) && $variantData['sale_price'] > 0 && 
                        isset($variantData['price']) && $variantData['sale_price'] >= $variantData['price']) {
                        DB::rollBack();
                        return back()->withErrors(["variants.{$variantIndex}.sale_price" => 'The variant sale price must be less than the variant regular price.'])->withInput();
                    }

                    $variantData['updated_by'] = Auth::id();
                    $variantData['stock_quantity'] = $variantData['track_stock'] ? ($variantData['stock_quantity'] ?? null) : null;
                    $variantData['stock_status'] = $variantData['track_stock'] ? null : ($variantData['stock_status'] ?? 'in_stock');

                    if (isset($variantData['id'])) {
                        // Update existing variant
                        $variant = ProductVariant::find($variantData['id']);
                        $variant->update($variantData);
                        $existingVariantIds[] = $variantData['id'];
                    } else {
                        // Create new variant
                        $variantData['product_id'] = $product->id;
                        $variantData['created_by'] = Auth::id();
                        $variantData['sort_order'] = $variantIndex;
                        $variant = ProductVariant::create($variantData);
                    }

                    // Handle variant attributes
                    $existingAttrIds = [];
                    
                    if (isset($variantData['attributes']) && is_array($variantData['attributes'])) {
                        // Delete all existing attributes for this variant (we'll recreate them)
                        ProductVariantAttribute::where('variant_id', $variant->id)->delete();
                        
                        foreach ($variantData['attributes'] as $attr) {
                            if (isset($attr['attribute_id']) && isset($attr['value_id'])) {
                                // Get attribute and value names
                                $attribute = Attribute::find($attr['attribute_id']);
                                $attributeValue = AttributeValue::find($attr['value_id']);
                                
                                if ($attribute && $attributeValue) {
                                    $newAttr = ProductVariantAttribute::create([
                                        'variant_id' => $variant->id,
                                        'attribute_name' => $attribute->name,
                                        'attribute_value' => $attributeValue->value,
                                        'created_by' => Auth::id(),
                                        'updated_by' => Auth::id(),
                                    ]);
                                    $existingAttrIds[] = $newAttr->id;
                                }
                            } elseif (isset($attr['attribute_name']) && isset($attr['value_id'])) {
                                // Fallback: if attribute_name is provided directly
                                $attributeValue = AttributeValue::find($attr['value_id']);
                                if ($attributeValue) {
                                    $newAttr = ProductVariantAttribute::create([
                                        'variant_id' => $variant->id,
                                        'attribute_name' => $attr['attribute_name'],
                                        'attribute_value' => $attributeValue->value,
                                        'created_by' => Auth::id(),
                                        'updated_by' => Auth::id(),
                                    ]);
                                    $existingAttrIds[] = $newAttr->id;
                                }
                            } elseif (isset($attr['id'])) {
                                // Update existing attribute (old structure support)
                                if (!empty($attr['name']) && !empty($attr['value'])) {
                                    ProductVariantAttribute::where('id', $attr['id'])->update([
                                        'attribute_name' => $attr['name'],
                                        'attribute_value' => $attr['value'],
                                        'updated_by' => Auth::id(),
                                    ]);
                                    $existingAttrIds[] = $attr['id'];
                                }
                            }
                        }
                    }
                }
                // Delete variants not in the update list
                ProductVariant::where('product_id', $product->id)
                    ->whereNotIn('id', $existingVariantIds)
                    ->delete();
            } else {
                // If switching from variable to simple, delete all variants
                if ($product->product_type === 'variable') {
                    $variants = ProductVariant::where('product_id', $product->id)->get();
                    foreach ($variants as $variant) {
                        if ($variant->image) {
                            Storage::disk('public')->delete($variant->image);
                        }
                    }
                    ProductVariant::where('product_id', $product->id)->delete();
                }
            }

            DB::commit();

            $this->logAudit('updated', $product, "Product updated: {$product->name}", $oldValues, $newValues);

            return redirect()->route('products.edit', $product)
                ->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'An error occurred while updating the product.'])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $productName = $product->name;

        // Delete featured image
        if ($product->featured_image) {
            Storage::disk('public')->delete($product->featured_image);
        }

        // Delete gallery images
        foreach ($product->gallery as $galleryItem) {
            Storage::disk('public')->delete($galleryItem->media_path);
        }

        // Delete variant images
        foreach ($product->variants as $variant) {
            if ($variant->image) {
                Storage::disk('public')->delete($variant->image);
            }
        }

        $this->logAudit('deleted', $product, "Product deleted: {$productName}");

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
