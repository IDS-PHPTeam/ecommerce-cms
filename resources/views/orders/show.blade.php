@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.875rem; font-weight: 700;">Order Details #{{ $order->id }}</h2>
        <a href="{{ route('orders.index') }}" class="btn" style="background-color: #6b7280; color: white;">Back to List</a>
    </div>

    <form method="POST" action="{{ route('orders.update', $order) }}">
        @csrf
        @method('PUT')

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <!-- Customer Information -->
            <div>
                <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem; color: #1f2937;">Customer Information</h3>
                <div style="display: grid; gap: 0.75rem;">
                    <div>
                        <strong style="color: #6b7280; display: block; margin-bottom: 0.25rem;">Name</strong>
                        <span style="font-size: 1.125rem;">{{ $order->customer_name }}</span>
                    </div>
                    @if($order->customer_email)
                    <div>
                        <strong style="color: #6b7280; display: block; margin-bottom: 0.25rem;">Email</strong>
                        <span style="color: #374151;">{{ $order->customer_email }}</span>
                    </div>
                    @endif
                    @if($order->customer_phone)
                    <div>
                        <strong style="color: #6b7280; display: block; margin-bottom: 0.25rem;">Phone</strong>
                        <span style="color: #374151;">{{ $order->customer_phone }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Location -->
            <div>
                <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem; color: #1f2937;">Location</h3>
                <div>
                    <strong style="color: #6b7280; display: block; margin-bottom: 0.25rem;">Delivery Address</strong>
                    <p style="color: #374151; line-height: 1.6;">{{ $order->location }}</p>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div style="margin-bottom: 2rem;">
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem; color: #1f2937;">Products</h3>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f3f4f6; border-bottom: 2px solid #e5e7eb;">
                            <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Product</th>
                            <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Quantity</th>
                            <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Price</th>
                            <th style="padding: 0.75rem; text-align: right; font-weight: 600;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 0.75rem;">
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    @if($item->product && $item->product->featured_image)
                                        <img src="{{ asset('storage/' . $item->product->featured_image) }}" alt="{{ $item->product_name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 0.25rem;">
                                    @endif
                                    <div>
                                        <div style="font-weight: 600;">{{ $item->product_name }}</div>
                                        @if($item->product)
                                            <div style="font-size: 0.875rem; color: #6b7280;">{{ $item->product->category }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 0.75rem;">{{ $item->quantity }}</td>
                            <td style="padding: 0.75rem;">${{ number_format($item->price, 2) }}</td>
                            <td style="padding: 0.75rem; text-align: right; font-weight: 600;">${{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pricing Summary -->
        <div style="margin-bottom: 2rem; padding: 1.5rem; background-color: #f9fafb; border-radius: 0.5rem;">
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem; color: #1f2937;">Pricing Summary</h3>
            <div style="display: grid; gap: 0.75rem;">
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #6b7280;">Subtotal:</span>
                    <span style="font-weight: 600;">${{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #6b7280;">Delivery Price:</span>
                    <span style="font-weight: 600;">${{ number_format($order->delivery_price, 2) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding-top: 0.75rem; border-top: 1px solid #e5e7eb; font-size: 1.125rem;">
                    <span style="font-weight: 700;">Total:</span>
                    <span style="font-weight: 700; color: var(--primary-blue);">${{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Order Management -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <div class="form-group">
                <label for="status" class="form-label">Status <span style="color: #ef4444;">*</span></label>
                <select id="status" name="status" required class="form-input">
                    <option value="pending" {{ old('status', $order->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="assigned" {{ old('status', $order->status) == 'assigned' ? 'selected' : '' }}>Assigned</option>
                    <option value="failed" {{ old('status', $order->status) == 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="completed" {{ old('status', $order->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
                @error('status')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="driver_id" class="form-label">Driver</label>
                <select id="driver_id" name="driver_id" class="form-input">
                    <option value="">Not Assigned</option>
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
                <label for="priority" class="form-label">Priority <span style="color: #ef4444;">*</span></label>
                <select id="priority" name="priority" required class="form-input">
                    <option value="low" {{ old('priority', $order->priority) == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ old('priority', $order->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ old('priority', $order->priority) == 'high' ? 'selected' : '' }}>High</option>
                </select>
                @error('priority')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="concurrency" class="form-label">Concurrency <span style="color: #ef4444;">*</span></label>
                <input type="number" id="concurrency" name="concurrency" value="{{ old('concurrency', $order->concurrency) }}" min="1" required class="form-input">
                @error('concurrency')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Feedback & Ratings -->
        @if($order->feedback || $order->rating)
        <div style="margin-bottom: 2rem; padding: 1.5rem; background-color: #f9fafb; border-radius: 0.5rem;">
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem; color: #1f2937;">Feedback & Ratings</h3>
            @if($order->rating)
            <div style="margin-bottom: 1rem;">
                <strong style="color: #6b7280; display: block; margin-bottom: 0.25rem;">Rating</strong>
                <div style="display: flex; gap: 0.25rem; align-items: center;">
                    @for($i = 1; $i <= 5; $i++)
                        <svg xmlns="http://www.w3.org/2000/svg" fill="{{ $i <= $order->rating ? '#fbbf24' : '#e5e7eb' }}" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    @endfor
                    <span style="margin-left: 0.5rem; font-weight: 600;">{{ $order->rating }}/5</span>
                </div>
            </div>
            @endif
            @if($order->feedback)
            <div>
                <strong style="color: #6b7280; display: block; margin-bottom: 0.25rem;">Feedback</strong>
                <p style="color: #374151; line-height: 1.6;">{{ $order->feedback }}</p>
            </div>
            @endif
        </div>
        @endif

        <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('orders.index') }}" class="btn" style="background-color: #6b7280; color: white;">Cancel</a>
        </div>
    </form>
</div>
@endsection





