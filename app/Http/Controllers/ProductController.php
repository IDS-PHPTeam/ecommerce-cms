<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // Filter by name
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by stock
        if ($request->filled('in_stock')) {
            $query->where('in_stock', $request->in_stock);
        }

        // Filter by on sale
        if ($request->filled('on_sale')) {
            if ($request->on_sale == '1') {
                // Products on sale (have sale_price and it's greater than 0)
                $query->whereNotNull('sale_price')->where('sale_price', '>', 0);
            } else {
                // Products not on sale (no sale_price or sale_price is 0 or null)
                $query->where(function($q) {
                    $q->whereNull('sale_price')->orWhere('sale_price', '<=', 0);
                });
            }
        }

        $products = $query->latest()->paginate(15);
        $categories = Category::where('status', 'active')->orderBy('name')->pluck('name');

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('status', 'active')->orderBy('name')->pluck('name');
        return view('products.create', compact('categories'));
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
            'featured_image' => 'nullable|image|max:2048',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'category' => 'nullable|string|max:255',
            'in_stock' => 'required|in:0,1',
            'status' => 'required|in:active,inactive',
        ], [
            'sale_price.lt' => 'The sale price must be less than the regular price.',
        ]);

        if ($request->hasFile('featured_image')) {
            // Store in year/month folder structure (directly in storage, not in products subfolder)
            $year = date('Y');
            $month = date('m');
            $validated['featured_image'] = $request->file('featured_image')->store("{$year}/{$month}", 'public');
        } elseif ($request->filled('selected_media_path')) {
            // Use selected image from media library
            $validated['featured_image'] = $request->selected_media_path;
        }

        // Convert in_stock to integer
        $validated['in_stock'] = (int) $validated['in_stock'];

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
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
        $categories = Category::where('status', 'active')->orderBy('name')->pluck('name');
        return view('products.edit', compact('product', 'categories'));
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
            'featured_image' => 'nullable|image|max:2048',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'category' => 'nullable|string|max:255',
            'in_stock' => 'required|in:0,1',
            'status' => 'required|in:active,inactive',
        ], [
            'sale_price.lt' => 'The sale price must be less than the regular price.',
        ]);

        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($product->featured_image) {
                Storage::disk('public')->delete($product->featured_image);
            }
            // Store in year/month folder structure (directly in storage, not in products subfolder)
            $year = date('Y');
            $month = date('m');
            $validated['featured_image'] = $request->file('featured_image')->store("{$year}/{$month}", 'public');
        } elseif ($request->filled('selected_media_path')) {
            // Use selected image from media library
            // Only delete old image if it's different from the selected one
            if ($product->featured_image && $product->featured_image !== $request->selected_media_path) {
                Storage::disk('public')->delete($product->featured_image);
            }
            $validated['featured_image'] = $request->selected_media_path;
        } elseif ($request->input('delete_current_image') == '1') {
            // Delete current image if delete flag is set
            if ($product->featured_image) {
                Storage::disk('public')->delete($product->featured_image);
            }
            $validated['featured_image'] = null;
        }

        // Convert in_stock to integer
        $validated['in_stock'] = (int) $validated['in_stock'];

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        // Delete image if exists
        if ($product->featured_image) {
            Storage::disk('public')->delete($product->featured_image);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
