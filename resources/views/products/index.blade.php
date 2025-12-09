@extends('layouts.app')

@section('title', 'Products')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.875rem; font-weight: 700;">Products</h2>
        <a href="{{ route('products.create') }}" class="btn btn-primary">+ Add New</a>
    </div>

    <!-- Filters -->
    <div class="card" style="margin-bottom: 1.5rem; padding: 1rem;">
        <form method="GET" action="{{ route('products.index') }}" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: end;">
            <div style="flex: 1; min-width: 200px;">
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ request('name') }}" 
                    class="form-input"
                    placeholder="Search by name..."
                >
            </div>
            <div style="flex: 1; min-width: 200px;">
                <select id="status" name="status" class="form-input">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div style="flex: 1; min-width: 200px;">
                <select id="category" name="category" class="form-input">
                    <option value="">All Categories</option>
                    @foreach($categories as $categoryName)
                        <option value="{{ $categoryName }}" {{ request('category') == $categoryName ? 'selected' : '' }}>{{ $categoryName }}</option>
                    @endforeach
                </select>
            </div>
            <div style="flex: 1; min-width: 200px;">
                <select id="in_stock" name="in_stock" class="form-input">
                    <option value="">All Stock</option>
                    <option value="1" {{ request('in_stock') == '1' ? 'selected' : '' }}>In Stock</option>
                    <option value="0" {{ request('in_stock') == '0' ? 'selected' : '' }}>Out of Stock</option>
                </select>
            </div>
            <div style="flex: 1; min-width: 200px;">
                <select id="on_sale" name="on_sale" class="form-input">
                    <option value="">All Products</option>
                    <option value="1" {{ request('on_sale') == '1' ? 'selected' : '' }}>On Sale</option>
                    <option value="0" {{ request('on_sale') == '0' ? 'selected' : '' }}>Not On Sale</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('products.index') }}" class="btn" style="background-color: #6b7280; color: white; margin-left: 0.5rem;">Reset</a>
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f3f4f6; border-bottom: 2px solid #e5e7eb;">
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">ID</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Image</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Name</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Price</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Stock</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Status</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 0.75rem;">{{ $product->id }}</td>
                    <td style="padding: 0.75rem;">
                        @if($product->featured_image)
                            <img src="{{ asset('storage/' . $product->featured_image) }}" alt="{{ $product->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 0.25rem;">
                        @else
                            <div style="width: 50px; height: 50px; background-color: #e5e7eb; border-radius: 0.25rem; display: flex; align-items: center; justify-content: center; color: #9ca3af;">No Image</div>
                        @endif
                    </td>
                    <td style="padding: 0.75rem;">{{ $product->name }}</td>
                    <td style="padding: 0.75rem;">
                        @if($product->sale_price)
                            <span style="text-decoration: line-through; color: #9ca3af;">${{ number_format($product->price, 2) }}</span>
                            <span style="color: #ef4444; font-weight: 600; margin-left: 0.5rem;">${{ number_format($product->sale_price, 2) }}</span>
                        @else
                            ${{ number_format($product->price, 2) }}
                        @endif
                    </td>
                    <td style="padding: 0.75rem;">
                        @php
                            // Determine stock status based on track_stock setting
                            $isInStock = false;
                            $stockLabel = 'Out of Stock';
                            
                            if ($product->track_stock) {
                                // If tracking stock, check stock_quantity
                                $isInStock = ($product->stock_quantity ?? 0) > 0;
                                $stockLabel = $isInStock ? 'In Stock' : 'Out of Stock';
                            } else {
                                // If not tracking stock, use stock_status
                                $isInStock = $product->stock_status === 'in_stock';
                                $stockLabel = $product->stock_status === 'in_stock' ? 'In Stock' : ($product->stock_status === 'on_backorder' ? 'On Backorder' : 'Out of Stock');
                            }
                        @endphp
                        <span style="padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 500; {{ $isInStock ? 'background-color: #d1fae5; color: #065f46;' : ($product->stock_status === 'on_backorder' ? 'background-color: #fef3c7; color: #92400e;' : 'background-color: #fee2e2; color: #991b1b;') }}">
                            {{ $stockLabel }}
                        </span>
                    </td>
                    <td style="padding: 0.75rem;">
                        <span style="padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 500; {{ $product->status == 'active' ? 'background-color: #d1fae5; color: #065f46;' : 'background-color: #fee2e2; color: #991b1b;' }}">
                            {{ ucfirst($product->status) }}
                        </span>
                    </td>
                    <td style="padding: 0.75rem;">
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('products.edit', $product) }}" class="action-btn action-btn-edit" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" style="display: inline;" data-confirm="Are you sure you want to delete this product?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn action-btn-delete" title="Delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 2rem; text-align: center; color: #6b7280;">No products found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="margin-top: 1.5rem;">
        {{ $products->links() }}
    </div>
</div>
@endsection

