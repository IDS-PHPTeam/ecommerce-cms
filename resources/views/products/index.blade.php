@extends('layouts.app')

@section('title', __('cms.products'))

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h2 class="section-heading-lg">{{ __('cms.products') }}</h2>
        <a href="{{ route('products.create') }}" class="btn btn-primary">{{ __('cms.add_new') }}</a>
    </div>

    <!-- Filters -->
    <div class="card mb-6 p-4">
        <form method="GET" action="{{ route('products.index') }}" class="flex gap-4 flex-wrap items-end">
            <div class="flex-1-min-200">
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ request('name') }}" 
                    class="form-input"
                    placeholder="{{ __('cms.search_by_name') }}"
                >
            </div>
            <div class="flex-1-min-200">
                <select id="status" name="status" class="form-input">
                    <option value="">{{ __('cms.all_status') }}</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('cms.active') }}</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('cms.inactive') }}</option>
                </select>
            </div>
            <div class="flex-1-min-200">
                <select id="category" name="category" class="form-input">
                    <option value="">{{ __('cms.all_categories') }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1-min-200">
                <select id="in_stock" name="in_stock" class="form-input">
                    <option value="">{{ __('cms.all_stock') }}</option>
                    <option value="1" {{ request('in_stock') == '1' ? 'selected' : '' }}>{{ __('cms.in_stock') }}</option>
                    <option value="0" {{ request('in_stock') == '0' ? 'selected' : '' }}>{{ __('cms.out_of_stock') }}</option>
                </select>
            </div>
            <div class="flex-1-min-200">
                <select id="on_sale" name="on_sale" class="form-input">
                    <option value="">{{ __('cms.all_products') }}</option>
                    <option value="1" {{ request('on_sale') == '1' ? 'selected' : '' }}>{{ __('cms.on_sale') }}</option>
                    <option value="0" {{ request('on_sale') == '0' ? 'selected' : '' }}>{{ __('cms.not_on_sale') }}</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">{{ __('cms.filter') }}</button>
                <a href="{{ route('products.index') }}" class="btn bg-gray-600 text-white ml-2">{{ __('cms.reset') }}</a>
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
                <tr class="table-header-row">
                    <th class="table-cell-header">{{ __('cms.id') }}</th>
                    <th class="table-cell-header">{{ __('cms.image') }}</th>
                    <th class="table-cell-header">{{ __('cms.name') }}</th>
                    <th class="table-cell-header">{{ __('cms.price') }}</th>
                    <th class="table-cell-header">{{ __('cms.stock') }}</th>
                    <th class="table-cell-header">{{ __('cms.status') }}</th>
                    <th class="table-cell-header">{{ __('cms.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr class="table-row-border">
                    <td class="table-cell-padding">{{ $product->id }}</td>
                    <td class="table-cell-padding">
                        @if($product->featured_image)
                            <img src="{{ asset('storage/' . $product->featured_image) }}" alt="{{ $product->name }}" class="product-table-image" width="60" height="60">
                        @else
                            <div class="product-table-image bg-gray-200 flex-center text-gray-400 text-xs">{{ __('cms.no_image') }}</div>
                        @endif
                    </td>
                    <td class="table-cell-padding">{{ $product->name }}</td>
                    <td class="table-cell-padding">
                        @if($product->sale_price)
                            <span class="product-regular-price">${{ number_format($product->price, 2) }}</span>
                            <span class="product-sale-price">${{ number_format($product->sale_price, 2) }}</span>
                        @else
                            <span>${{ number_format($product->price, 2) }}</span>
                        @endif
                    </td>
                    <td class="table-cell-padding">
                        @php
                            // Determine stock status based on track_stock setting
                            $isInStock = false;
                            $stockLabel = 'Out of Stock';
                            
                            if ($product->track_stock) {
                                // If tracking stock, check stock_quantity
                                $isInStock = ($product->stock_quantity ?? 0) > 0;
                                $stockLabel = $isInStock ? __('cms.in_stock') : __('cms.out_of_stock');
                            } else {
                                // If not tracking stock, use stock_status
                                $isInStock = $product->stock_status === 'in_stock';
                                $stockLabel = $product->stock_status === 'in_stock' ? __('cms.in_stock') : ($product->stock_status === 'on_backorder' ? __('cms.on_backorder') : __('cms.out_of_stock'));
                            }
                            $stockBadgeClass = $isInStock ? 'badge-green' : ($product->stock_status === 'on_backorder' ? 'badge-yellow' : 'badge-red');
                        @endphp
                        <span class="badge {{ $stockBadgeClass }}">
                            {{ $stockLabel }}
                        </span>
                    </td>
                    <td class="table-cell-padding">
                        <span class="badge {{ $product->status == 'active' ? 'badge-green' : 'badge-red' }}">
                            {{ ucfirst($product->status) }}
                        </span>
                    </td>
                    <td class="table-cell-padding">
                        <div class="flex gap-2">
                            <a href="{{ route('products.edit', $product) }}" class="action-btn action-btn-edit" title="{{ __('cms.edit') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline" data-confirm="{{ __('cms.confirm_delete_product') }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn action-btn-delete" title="{{ __('cms.delete') }}">
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
                    <td colspan="7" class="p-8 text-center text-tertiary">{{ __('cms.no_products_found') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $products->links() }}
    </div>
</div>
@endsection

