@extends('settlements.other')

@section('other-content')
<div class="card">
    <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb;">
        <h3 style="font-size: 1.25rem; font-weight: 600;">Commission Calculator</h3>
        <p style="color: #6b7280; margin-top: 0.5rem;">Calculate commission for orders</p>
    </div>

    <div style="padding: 1.5rem;">
        <form method="GET" action="{{ route('settlements.commission-calculator') }}" style="max-width: 600px;">
            <div style="margin-bottom: 1rem;">
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

            <div style="margin-bottom: 1rem;">
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

            <div style="margin-bottom: 1rem;">
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
        <div class="card" style="margin-top: 1.5rem; padding: 1.5rem; background-color: #eff6ff; border-left: 4px solid #3b82f6;">
            <h4 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem;">Calculation Result</h4>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                @if(isset($result['order_id']))
                <div>
                    <div style="font-size: 0.875rem; color: #6b7280;">Order ID</div>
                    <div style="font-size: 1.25rem; font-weight: 600;">#{{ $result['order_id'] }}</div>
                </div>
                @endif
                <div>
                    <div style="font-size: 0.875rem; color: #6b7280;">Order Total</div>
                    <div style="font-size: 1.25rem; font-weight: 600;">${{ number_format($result['order_total'], 2) }}</div>
                </div>
                <div>
                    <div style="font-size: 0.875rem; color: #6b7280;">Commission Percentage</div>
                    <div style="font-size: 1.25rem; font-weight: 600;">{{ number_format($result['commission_percentage'], 2) }}%</div>
                </div>
                <div>
                    <div style="font-size: 0.875rem; color: #6b7280;">Commission</div>
                    <div style="font-size: 1.5rem; font-weight: 700; color: #059669;">${{ number_format($result['commission'], 2) }}</div>
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




