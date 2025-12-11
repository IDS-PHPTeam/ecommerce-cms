@extends('layouts.app')

@section('title', __('cms.product_details'))

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h2 class="section-heading-lg">{{ __('cms.product_details') }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('products.edit', $product) }}" class="btn bg-yellow-500 text-white">{{ __('cms.edit') }}</a>
            <a href="{{ route('products.index') }}" class="btn bg-gray-600 text-white">{{ __('cms.back_to_list') }}</a>
        </div>
    </div>

    <div class="grid grid-cols-300-1fr gap-8">
        <!-- Product Image -->
        <div>
            @if($product->featured_image)
                <img src="{{ asset('storage/' . $product->featured_image) }}" alt="{{ $product->name }}" class="w-full h-auto rounded-md border border-gray-200">
            @else
                <div class="w-full aspect-square bg-gray-200 rounded-md flex-center text-gray-400">
                    {{ __('cms.no_image') }}
                </div>
            @endif
        </div>

        <!-- Product Details -->
        <div>
            <div class="mb-6">
                <h3 class="text-2xl font-bold mb-2">{{ $product->name }}</h3>
                <span class="badge {{ $product->status == 'active' ? 'badge-green' : 'badge-red' }}">
                    {{ ucfirst($product->status) }}
                </span>
            </div>

            <div class="grid gap-4">
                <div>
                    <strong class="text-label">{{ __('cms.id') }}</strong>
                    <span class="text-lg">{{ $product->id }}</span>
                </div>

                <div>
                    <strong class="text-label">{{ __('cms.description') }}</strong>
                    <p class="text-gray-700">{{ $product->description ?: __('cms.no_description_provided') }}</p>
                </div>

                <div class="grid grid-auto-200 gap-4">
                    <div>
                        <strong class="text-label">{{ __('cms.price') }}</strong>
                        <span class="stat-value-lg text-primary">${{ number_format($product->price, 2) }}</span>
                    </div>

                    @if($product->sale_price)
                    <div>
                        <strong class="text-label">{{ __('cms.sale_price') }}</strong>
                        <span class="stat-value-lg text-red-500">${{ number_format($product->sale_price, 2) }}</span>
                    </div>
                    @endif

                    <div>
                        <strong class="text-label">{{ __('cms.category') }}</strong>
                        <span class="text-lg">{{ $product->category ?: __('cms.na') }}</span>
                    </div>

                    <div>
                        <strong class="text-label">{{ __('cms.stock') }}</strong>
                        <span class="badge {{ $product->in_stock == 1 ? 'badge-green' : 'badge-red' }}">
                            {{ $product->in_stock == 1 ? __('cms.in_stock') : __('cms.out_of_stock') }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <form action="{{ route('products.destroy', $product) }}" method="POST" data-confirm="{{ __('cms.confirm_delete_product') }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('cms.delete_product') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

