<!DOCTYPE html>
<html>
<head>
    <title>Settlement Request Export</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f3f4f6; font-weight: 600; }
        .driver-section { margin-bottom: 30px; page-break-inside: avoid; }
    </style>
</head>
<body>
    <h1>Settlement Request</h1>
    <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>
    
    @foreach($driverOrders as $driverId => $data)
    <div class="driver-section">
        <h2>Driver: {{ isset($data['driver']) && $data['driver'] ? ($data['driver']->first_name && $data['driver']->last_name ? $data['driver']->first_name . ' ' . $data['driver']->last_name : $data['driver']->name) : 'N/A' }}</h2>
        
        <table>
            <thead>
                <tr>
                    <th>Order Number</th>
                    <th>Commission Value</th>
                    <th>Pending Money</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['orders'] as $orderData)
                <tr>
                    <td>#{{ $orderData['order_number'] }}</td>
                    <td>${{ number_format($orderData['commission_value'], 2) }}</td>
                    <td>${{ number_format($orderData['pending_money'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td><strong>Total Commission</strong></td>
                    <td colspan="2"><strong>${{ number_format($data['total_commission'], 2) }}</strong></td>
                </tr>
                <tr>
                    <td><strong>Total Pending</strong></td>
                    <td colspan="2"><strong>${{ number_format($data['total_pending'], 2) }}</strong></td>
                </tr>
                <tr>
                    <td><strong>Settlement Value</strong></td>
                    <td colspan="2"><strong>${{ number_format($data['settlement_value'], 2) }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endforeach
</body>
</html>

