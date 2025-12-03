<!DOCTYPE html>
<html>
<head>
    <title>Settlement History Export</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f3f4f6; font-weight: 600; }
    </style>
</head>
<body>
    <h1>Settlement History</h1>
    <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>
    
    <table>
        <thead>
            <tr>
                <th>Driver</th>
                <th>Settlement Date</th>
                <th>Value</th>
                <th>Status</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($settlements as $settlement)
            <tr>
                <td>{{ $settlement->driver ? ($settlement->driver->first_name && $settlement->driver->last_name ? $settlement->driver->first_name . ' ' . $settlement->driver->last_name : $settlement->driver->name) : 'N/A' }}</td>
                <td>{{ $settlement->settlement_date->format('Y-m-d H:i:s') }}</td>
                <td>${{ number_format($settlement->value, 2) }}</td>
                <td>{{ ucfirst($settlement->status) }}</td>
                <td>{{ $settlement->notes ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

