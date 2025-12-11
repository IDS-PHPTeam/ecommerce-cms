@extends('layouts.app')

@section('title', __('cms.order_details'))

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h2 class="section-heading-lg">{{ __('cms.order_details') }} #{{ $order->id }}</h2>
        <a href="{{ route('orders.index') }}" class="btn bg-gray-600 text-white">{{ __('cms.back_to_list') }}</a>
    </div>

    <form method="POST" action="{{ route('orders.update', $order) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-auto-300 gap-6 mb-8">
            <!-- Customer Information -->
            <div>
                <h3 class="section-heading mb-4">{{ __('cms.customer_information') }}</h3>
                <div class="grid gap-3">
                    <div>
                        <strong class="text-label">{{ __('cms.name') }}</strong>
                        <span class="text-lg">{{ $order->customer_name }}</span>
                    </div>
                    @if($order->customer_email)
                    <div>
                        <strong class="text-label">{{ __('cms.email') }}</strong>
                        <span class="text-gray-700">{{ $order->customer_email }}</span>
                    </div>
                    @endif
                    @if($order->customer_phone)
                    <div>
                        <strong class="text-label">{{ __('cms.phone') }}</strong>
                        <span class="text-gray-700">{{ $order->customer_phone }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Location -->
            <div>
                <h3 class="section-heading mb-4">{{ __('cms.location') }}</h3>
                <div>
                    <strong class="text-label">{{ __('cms.delivery_address') }}</strong>
                    <p class="text-gray-700 leading-relaxed">{{ $order->location }}</p>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="mb-8">
            <h3 class="section-heading mb-4">{{ __('cms.products') }}</h3>
            <div class="overflow-x-auto">
                <table class="w-full" style="border-collapse: collapse;">
                    <thead>
                        <tr class="table-header-row">
                            <th class="table-cell-header">{{ __('cms.product') }}</th>
                            <th class="table-cell-header">{{ __('cms.quantity') }}</th>
                            <th class="table-cell-header">{{ __('cms.price') }}</th>
                            <th class="table-cell-header text-right">{{ __('cms.subtotal') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr class="table-row-border">
                            <td class="table-cell-padding">
                                <div class="flex items-center gap-3">
                                    @if($item->product && $item->product->featured_image)
                                        <img src="{{ asset('storage/' . $item->product->featured_image) }}" alt="{{ $item->product_name }}" class="w-12 h-12 object-cover rounded">
                                    @endif
                                    <div>
                                        <div class="font-semibold">{{ $item->product_name }}</div>
                                        @if($item->product)
                                            <div class="text-sm text-tertiary">{{ $item->product->category }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="table-cell-padding">{{ $item->quantity }}</td>
                            <td class="table-cell-padding">${{ number_format($item->price, 2) }}</td>
                            <td class="table-cell-padding text-right font-semibold">${{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pricing Summary -->
        <div class="mb-8 p-6 bg-gray-50 rounded-md">
            <h3 class="section-heading mb-4">{{ __('cms.pricing_summary') }}</h3>
            <div class="grid gap-3">
                <div class="flex justify-between">
                    <span class="text-tertiary">{{ __('cms.subtotal') }}:</span>
                    <span class="font-semibold">${{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-tertiary">{{ __('cms.delivery_price') }}:</span>
                    <span class="font-semibold">${{ number_format($order->delivery_price, 2) }}</span>
                </div>
                <div class="flex justify-between pt-3 border-t border-gray-200 text-lg">
                    <span class="font-bold">{{ __('cms.total') }}:</span>
                    <span class="font-bold text-primary-blue">${{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Order Management -->
        <div class="grid grid-auto-250 gap-6 mb-8">
            <div class="form-group">
                <label for="status" class="form-label">{{ __('cms.status') }}</label>
                <select id="status" name="status" required class="form-input">
                    <option value="pending" {{ old('status', $order->status) == 'pending' ? 'selected' : '' }}>{{ __('cms.pending') }}</option>
                    <option value="assigned" {{ old('status', $order->status) == 'assigned' ? 'selected' : '' }}>{{ __('cms.assigned') }}</option>
                    <option value="failed" {{ old('status', $order->status) == 'failed' ? 'selected' : '' }}>{{ __('cms.failed') }}</option>
                    <option value="completed" {{ old('status', $order->status) == 'completed' ? 'selected' : '' }}>{{ __('cms.completed') }}</option>
                </select>
                @error('status')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="driver_id" class="form-label">{{ __('cms.driver') }}</label>
                <select id="driver_id" name="driver_id" class="form-input">
                    <option value="">{{ __('cms.not_assigned') }}</option>
                    @foreach($drivers as $driver)
                        <option value="{{ $driver->id }}" {{ old('driver_id', $order->driver_id) == $driver->id ? 'selected' : '' }}>
                            {{ $driver->first_name && $driver->last_name ? $driver->first_name . ' ' . $driver->last_name : $driver->name }}
                        </option>
                    @endforeach
                </select>
                @error('driver_id')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="priority" class="form-label">{{ __('cms.priority') }}</label>
                <select id="priority" name="priority" required class="form-input">
                    <option value="low" {{ old('priority', $order->priority) == 'low' ? 'selected' : '' }}>{{ __('cms.low') }}</option>
                    <option value="medium" {{ old('priority', $order->priority) == 'medium' ? 'selected' : '' }}>{{ __('cms.medium') }}</option>
                    <option value="high" {{ old('priority', $order->priority) == 'high' ? 'selected' : '' }}>{{ __('cms.high') }}</option>
                </select>
                @error('priority')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="concurrency" class="form-label">{{ __('cms.concurrency') }}</label>
                <input type="number" id="concurrency" name="concurrency" value="{{ old('concurrency', $order->concurrency) }}" min="1" required class="form-input">
                @error('concurrency')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Feedback & Ratings -->
        @if($order->feedback || $order->rating)
        <div class="mb-8 p-6 bg-gray-50 rounded-md">
            <h3 class="section-heading mb-4">{{ __('cms.feedback_ratings') }}</h3>
            @if($order->rating)
            <div class="mb-4">
                <strong class="text-label">{{ __('cms.rating') }}</strong>
                <div class="flex gap-1 items-center mt-1">
                    @for($i = 1; $i <= 5; $i++)
                        <svg xmlns="http://www.w3.org/2000/svg" fill="{{ $i <= $order->rating ? '#fbbf24' : '#e5e7eb' }}" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    @endfor
                    <span class="ml-2 font-semibold">{{ $order->rating }}/5</span>
                </div>
            </div>
            @endif
            @if($order->feedback)
            <div>
                <strong class="text-label">{{ __('cms.feedback') }}</strong>
                <p class="text-gray-700 leading-relaxed mt-1">{{ $order->feedback }}</p>
            </div>
            @endif
        </div>
        @endif

        <div class="flex gap-4 mt-6">
            <button type="submit" class="btn btn-primary">{{ __('cms.save') }}</button>
            <a href="{{ route('orders.index') }}" class="btn bg-gray-600 text-white">{{ __('cms.cancel') }}</a>
        </div>
    </form>
</div>
@endsection





