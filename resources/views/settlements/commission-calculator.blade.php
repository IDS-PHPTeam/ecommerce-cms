@extends('settlements.other')

@section('other-content')
<div class="card">
    <div class="card-section-header">
        <h3 class="card-section-title">Commission Calculator</h3>
        <p class="text-tertiary mt-2">Calculate commission for orders</p>
    </div>

    <div class="card-section-body">
        <form method="GET" action="{{ route('settlements.commission-calculator') }}" class="max-w-600">
            <div class="form-group">
                <label for="order_id" class="form-label">Select Order (Optional)</label>
                <select id="order_id" name="order_id" class="form-input">
                    <option value="">-- Select an order --</option>
                    @foreach($orders as $order)
                        <option value="{{ $order->id }}" {{ request('order_id') == $order->id ? 'selected' : '' }}>
                            Order #{{ $order->id }} - ${{ number_format($order->total, 2) }} - {{ $order->customer_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="order_total" class="form-label">Order Total (if not selecting order)</label>
                <input 
                    type="number" 
                    id="order_total" 
                    name="order_total" 
                    value="{{ request('order_total', 0) }}" 
                    class="form-input"
                    step="0.01"
                    min="0"
                    placeholder="Enter order total"
                >
            </div>

            <div class="form-group">
                <label for="commission_percentage" class="form-label">Commission Percentage (%)</label>
                <input 
                    type="number" 
                    id="commission_percentage" 
                    name="commission_percentage" 
                    value="{{ request('commission_percentage', $commissionPercentage) }}" 
                    class="form-input"
                    step="0.01"
                    min="0"
                    max="100"
                >
            </div>

            <button type="submit" class="btn btn-primary">Calculate</button>
        </form>

        @if($result)
        <div class="card mt-6 p-6" style="background-color: #eff6ff; border-left: 4px solid #3b82f6;">
            <h4 class="text-xl font-semibold mb-4">Calculation Result</h4>
            <div class="grid grid-auto-200 gap-4">
                @if(isset($result['order_id']))
                <div>
                    <div class="stat-label">Order ID</div>
                    <div class="stat-value-lg text-primary">#{{ $result['order_id'] }}</div>
                </div>
                @endif
                <div>
                    <div class="stat-label">Order Total</div>
                    <div class="stat-value-lg text-primary">${{ number_format($result['order_total'], 2) }}</div>
                </div>
                <div>
                    <div class="stat-label">Commission Percentage</div>
                    <div class="stat-value-lg text-primary">{{ number_format($result['commission_percentage'], 2) }}%</div>
                </div>
                <div>
                    <div class="stat-label">Commission</div>
                    <div class="stat-value-lg stat-value-green">${{ number_format($result['commission'], 2) }}</div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const activeSubTab = document.querySelector('.sub-tab-link.active');
        if (activeSubTab) {
            activeSubTab.style.color = '#099ecb';
            activeSubTab.style.borderBottomColor = '#099ecb';
        }

        // Auto-fill order total when order is selected
        const orderSelect = document.getElementById('order_id');
        const orderTotalInput = document.getElementById('order_total');
        
        if (orderSelect && orderTotalInput) {
            orderSelect.addEventListener('change', function() {
                if (this.value) {
                    const selectedOption = this.options[this.selectedIndex];
                    const match = selectedOption.text.match(/\$([\d,]+\.?\d*)/);
                    if (match) {
                        orderTotalInput.value = match[1].replace(/,/g, '');
                    }
                }
            });
        }
    });
</script>
@endpush
@endsection




