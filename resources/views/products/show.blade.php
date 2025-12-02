@extends('layouts.app')

@section('title', 'Product Details')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.875rem; font-weight: 700;">Product Details</h2>
        <div style="display: flex; gap: 0.5rem;">
            <a href="{{ route('products.edit', $product) }}" class="btn" style="background-color: #f59e0b; color: white;">Edit</a>
            <a href="{{ route('products.index') }}" class="btn" style="background-color: #6b7280; color: white;">Back to List</a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 300px 1fr; gap: 2rem;">
        <!-- Product Image -->
        <div>
            @if($product->featured_image)
                <img src="{{ asset('storage/' . $product->featured_image) }}" alt="{{ $product->name }}" style="width: 100%; height: auto; border-radius: 0.5rem; border: 1px solid #e5e7eb;">
            @else
                <div style="width: 100%; aspect-ratio: 1; background-color: #e5e7eb; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; color: #9ca3af;">
                    No Image
                </div>
            @endif
        </div>

        <!-- Product Details -->
        <div>
            <div style="margin-bottom: 1.5rem;">
                <h3 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">{{ $product->name }}</h3>
                <span style="padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 500; {{ $product->status == 'active' ? 'background-color: #d1fae5; color: #065f46;' : 'background-color: #fee2e2; color: #991b1b;' }}">
                    {{ ucfirst($product->status) }}
                </span>
            </div>

            <div style="display: grid; gap: 1rem;">
                <div>
                    <strong style="color: #6b7280; display: block; margin-bottom: 0.25rem;">ID</strong>
                    <span style="font-size: 1.125rem;">{{ $product->id }}</span>
                </div>

                <div>
                    <strong style="color: #6b7280; display: block; margin-bottom: 0.25rem;">Description</strong>
                    <p style="color: #374151;">{{ $product->description ?: 'No description provided.' }}</p>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                    <div>
                        <strong style="color: #6b7280; display: block; margin-bottom: 0.25rem;">Price</strong>
                        <span style="font-size: 1.25rem; font-weight: 600;">${{ number_format($product->price, 2) }}</span>
                    </div>

                    @if($product->sale_price)
                    <div>
                        <strong style="color: #6b7280; display: block; margin-bottom: 0.25rem;">Sale Price</strong>
                        <span style="font-size: 1.25rem; font-weight: 600; color: #ef4444;">${{ number_format($product->sale_price, 2) }}</span>
                    </div>
                    @endif

                    <div>
                        <strong style="color: #6b7280; display: block; margin-bottom: 0.25rem;">Category</strong>
                        <span style="font-size: 1.125rem;">{{ $product->category ?: 'N/A' }}</span>
                    </div>

                    <div>
                        <strong style="color: #6b7280; display: block; margin-bottom: 0.25rem;">Stock</strong>
                        <span style="padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 500; {{ $product->in_stock == 1 ? 'background-color: #d1fae5; color: #065f46;' : 'background-color: #fee2e2; color: #991b1b;' }}">
                            {{ $product->in_stock == 1 ? 'In Stock' : 'Out of Stock' }}
                        </span>
                    </div>
                </div>
            </div>

            <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
                <form action="{{ route('products.destroy', $product) }}" method="POST" data-confirm="Are you sure you want to delete this product?">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Product</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

