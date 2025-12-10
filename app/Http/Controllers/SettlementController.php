<?php

namespace App\Http\Controllers;

use App\Models\Settlement;
use App\Models\Order;
use App\Models\User;
use App\Traits\LogsAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SettlementController extends Controller
{
    use LogsAudit;

    // Commission percentage (can be moved to settings)
    private const COMMISSION_PERCENTAGE = 10; // 10% of order total

    /**
     * Dashboard - Main settlements dashboard with KPIs and map
     */
    public function dashboard(Request $request)
    {
        // Default to today if no dates provided
        $dateFrom = $request->filled('date_from') ? $request->date_from : Carbon::today()->toDateString();
        $dateTo = $request->filled('date_to') ? $request->date_to : Carbon::today()->toDateString();

        $query = Order::query();

        // Apply date filter
        $query->whereDate('order_date', '>=', $dateFrom)
              ->whereDate('order_date', '<=', $dateTo);

        // Filter by client (customer)
        if ($request->filled('client')) {
            $query->where(function($q) use ($request) {
                $q->where('customer_name', 'like', '%' . $request->client . '%')
                  ->orWhere('customer_email', 'like', '%' . $request->client . '%');
            });
        }

        // Filter by driver
        if ($request->filled('driver')) {
            $query->where('driver_id', $request->driver);
        }

        $orders = $query->get();

        // Calculate KPIs
        $totalOrders = $orders->count();
        $successfulOrders = $orders->where('status', 'completed')->count();
        $canceledOrders = $orders->where('status', 'failed')->count();
        $failedOrders = $orders->where('status', 'failed')->count();
        $totalValue = $orders->sum('total');
        $totalCommission = $orders->sum(function($order) {
            return $order->total * (self::COMMISSION_PERCENTAGE / 100);
        });

        // Current orders
        $currentOrders = Order::whereIn('status', ['pending', 'assigned'])->get();
        $assignedOrders = $currentOrders->where('status', 'assigned')->count();
        $notAssignedOrders = $currentOrders->where('status', 'pending')->count();

        // Drivers stats
        $totalDrivers = User::where('role', 'driver')->count();
        $activeDrivers = User::where('role', 'driver')
            ->where('driver_status', 'active')
            ->count();

        // Average delivery time (assuming we have delivery_time field or calculate from order_date to completion)
        // For now, using a placeholder calculation
        $averageDeliveryTime = 0; // This would need delivery_time tracking

        // Failed delivery rate
        $failedDeliveryRate = $totalOrders > 0 
            ? round(($failedOrders / $totalOrders) * 100, 2) 
            : 0;

        // Get orders for map (unassigned, delays, exceptions)
        $mapOrders = Order::with('driver')
            ->whereIn('status', ['pending', 'assigned', 'failed'])
            ->get();

        // Get active drivers for map
        $mapDrivers = User::where('role', 'driver')
            ->where('driver_status', 'active')
            ->get();

        $drivers = User::where('role', 'driver')->get();
        $customers = Order::select('customer_name', 'customer_email')
            ->distinct()
            ->get();

        return view('dashboard', compact(
            'totalOrders', 'successfulOrders', 'canceledOrders', 'failedOrders',
            'totalValue', 'totalCommission', 'assignedOrders', 'notAssignedOrders',
            'totalDrivers', 'activeDrivers', 'averageDeliveryTime', 'failedDeliveryRate',
            'mapOrders', 'mapDrivers', 'drivers', 'customers', 'dateFrom', 'dateTo'
        ));
    }

    /**
     * Settlement History
     */
    public function history(Request $request)
    {
        $query = Settlement::with('driver');

        // Filter by driver
        if ($request->filled('driver')) {
            $query->where('driver_id', $request->driver);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->filled('date_from')) {
            $query->whereDate('settlement_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('settlement_date', '<=', $request->date_to);
        }

        $settlements = $query->latest('settlement_date')->paginate(15);
        $drivers = User::where('role', 'driver')->get();

        return view('settlements.history', compact('settlements', 'drivers'));
    }

    /**
     * Export Settlement History
     */
    public function exportHistory(Request $request, $format = 'excel')
    {
        $query = Settlement::with('driver');

        if ($request->filled('driver')) {
            $query->where('driver_id', $request->driver);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('settlement_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('settlement_date', '<=', $request->date_to);
        }

        $settlements = $query->get();

        if ($format === 'pdf') {
            return $this->exportHistoryPDF($settlements);
        } else {
            return $this->exportHistoryExcel($settlements);
        }
    }

    /**
     * Settlement Request - Show pending orders and create settlement request
     */
    public function request(Request $request)
    {
        // Get all pending orders (completed orders)
        // For simplicity, we'll show all completed orders - in production you'd track which orders are settled
        $query = Order::with('driver')
            ->where('status', 'completed');

        // Filter by driver
        if ($request->filled('driver')) {
            $query->where('driver_id', $request->driver);
        }

        $orders = $query->get();

        // Group by driver and calculate totals
        $driverOrders = $orders->groupBy('driver_id')->map(function($driverOrders, $driverId) {
            $firstOrder = $driverOrders->first();
            $driver = $firstOrder ? $firstOrder->driver : null;
            $totalCommission = $driverOrders->sum(function($order) {
                return $order->total * (self::COMMISSION_PERCENTAGE / 100);
            });
            $totalPending = $totalCommission; // All pending since not settled
            $settlementValue = round($totalPending, 2);

            return [
                'driver' => $driver,
                'orders' => $driverOrders->map(function($order) {
                    $commission = $order->total * (self::COMMISSION_PERCENTAGE / 100);
                    return [
                        'order' => $order,
                        'order_number' => $order->id,
                        'commission_value' => round($commission, 2),
                        'pending_money' => round($commission, 2),
                    ];
                }),
                'total_commission' => round($totalCommission, 2),
                'total_pending' => round($totalPending, 2),
                'settlement_value' => $settlementValue,
            ];
        })->filter(function($data) {
            return $data['driver'] !== null; // Filter out orders without drivers
        });

        $drivers = User::where('role', 'driver')->get();

        return view('settlements.request', compact('driverOrders', 'drivers'));
    }

    /**
     * Store Settlement Request
     */
    public function storeRequest(Request $request)
    {
        $validated = $request->validate([
            'driver_id' => 'required|exists:users,id',
            'value' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $settlement = Settlement::create([
            'driver_id' => $validated['driver_id'],
            'value' => $validated['value'],
            'status' => 'requested',
            'settlement_date' => now(),
            'notes' => $validated['notes'] ?? null,
        ]);

        $this->logAudit('created', $settlement, "Settlement request created for driver #{$validated['driver_id']}");

        return redirect()->route('settlements.request')
            ->with('success', __('cms.settlement_request_created_successfully'));
    }

    /**
     * Export Settlement Request
     */
    public function exportRequest(Request $request, $format = 'excel')
    {
        $query = Order::with('driver')
            ->where('status', 'completed');

        if ($request->filled('driver')) {
            $query->where('driver_id', $request->driver);
        }

        $orders = $query->get();
        $driverOrders = $orders->groupBy('driver_id');

        if ($format === 'pdf') {
            return $this->exportRequestPDF($driverOrders);
        } else {
            return $this->exportRequestExcel($driverOrders);
        }
    }

    /**
     * Update Settlement Status
     */
    public function updateStatus(Request $request, Settlement $settlement)
    {
        $validated = $request->validate([
            'status' => 'required|in:requested,paid',
        ]);

        $oldStatus = $settlement->status;
        $settlement->update(['status' => $validated['status']]);

        $this->logAudit('updated', $settlement, "Settlement status updated from {$oldStatus} to {$validated['status']}");

        return redirect()->route('settlements.history')
            ->with('success', __('cms.settlement_status_updated_successfully'));
    }

    /**
     * Other - Discrepancy Reports
     */
    public function discrepancyReports()
    {
        // Find discrepancies between expected and actual settlements
        $discrepancies = [];
        
        $drivers = User::where('role', 'driver')->get();
        
        foreach ($drivers as $driver) {
            $completedOrders = Order::where('driver_id', $driver->id)
                ->where('status', 'completed')
                ->get();
            
            $expectedCommission = $completedOrders->sum(function($order) {
                return $order->total * (self::COMMISSION_PERCENTAGE / 100);
            });
            
            $paidSettlements = Settlement::where('driver_id', $driver->id)
                ->where('status', 'paid')
                ->sum('value');
            
            $difference = $expectedCommission - $paidSettlements;
            
            if (abs($difference) > 0.01) { // Only show if difference is more than 1 cent
                $discrepancies[] = [
                    'driver' => $driver,
                    'expected' => round($expectedCommission, 2),
                    'paid' => round($paidSettlements, 2),
                    'difference' => round($difference, 2),
                ];
            }
        }

        return view('settlements.discrepancy-reports', compact('discrepancies'));
    }

    /**
     * Other - Payout Summary Generator
     */
    public function payoutSummary(Request $request)
    {
        $dateFrom = $request->filled('date_from') ? $request->date_from : Carbon::today()->startOfMonth()->toDateString();
        $dateTo = $request->filled('date_to') ? $request->date_to : Carbon::today()->toDateString();

        $query = Settlement::with('driver')
            ->whereDate('settlement_date', '>=', $dateFrom)
            ->whereDate('settlement_date', '<=', $dateTo);

        if ($request->filled('driver')) {
            $query->where('driver_id', $request->driver);
        }

        $settlements = $query->get();

        $summary = $settlements->groupBy('driver_id')->map(function($driverSettlements, $driverId) {
            $driver = $driverSettlements->first()->driver;
            return [
                'driver' => $driver,
                'total_settlements' => $driverSettlements->count(),
                'total_value' => $driverSettlements->sum('value'),
                'requested' => $driverSettlements->where('status', 'requested')->sum('value'),
                'paid' => $driverSettlements->where('status', 'paid')->sum('value'),
            ];
        });

        $drivers = User::where('role', 'driver')->get();

        return view('settlements.payout-summary', compact('summary', 'drivers', 'dateFrom', 'dateTo'));
    }

    /**
     * Other - Commission Calculator
     */
    public function commissionCalculator(Request $request)
    {
        $orderId = $request->input('order_id');
        $orderTotal = $request->input('order_total', 0);
        $commissionPercentage = $request->input('commission_percentage', self::COMMISSION_PERCENTAGE);

        $result = null;
        if ($orderId) {
            $order = Order::find($orderId);
            if ($order) {
                $orderTotal = $order->total;
                $commission = $orderTotal * ($commissionPercentage / 100);
                $result = [
                    'order_id' => $order->id,
                    'order_total' => $orderTotal,
                    'commission_percentage' => $commissionPercentage,
                    'commission' => round($commission, 2),
                ];
            }
        } elseif ($orderTotal > 0) {
            $commission = $orderTotal * ($commissionPercentage / 100);
            $result = [
                'order_total' => $orderTotal,
                'commission_percentage' => $commissionPercentage,
                'commission' => round($commission, 2),
            ];
        }

        $orders = Order::latest('order_date')->limit(100)->get();

        return view('settlements.commission-calculator', compact('result', 'orders', 'commissionPercentage'));
    }

    /**
     * Export History to Excel (CSV format)
     */
    private function exportHistoryExcel($settlements)
    {
        $filename = 'settlement_history_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($settlements) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, ['Driver', 'Settlement Date', 'Value', 'Status', 'Notes']);
            
            // Data
            foreach ($settlements as $settlement) {
                fputcsv($file, [
                    $settlement->driver ? ($settlement->driver->first_name && $settlement->driver->last_name ? $settlement->driver->first_name . ' ' . $settlement->driver->last_name : $settlement->driver->name) : 'N/A',
                    $settlement->settlement_date->format('Y-m-d H:i:s'),
                    number_format($settlement->value, 2),
                    ucfirst($settlement->status),
                    $settlement->notes ?? '',
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export History to PDF (Simple HTML to PDF)
     */
    private function exportHistoryPDF($settlements)
    {
        // For now, return a simple HTML view that can be printed as PDF
        // In production, you'd use a library like dompdf or snappy
        return view('settlements.exports.history-pdf', compact('settlements'));
    }

    /**
     * Export Request to Excel (CSV format)
     */
    private function exportRequestExcel($driverOrders)
    {
        $filename = 'settlement_request_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($driverOrders) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, ['Driver', 'Order Number', 'Commission Value', 'Pending Money', 'Total Commission', 'Total Pending', 'Settlement Value']);
            
            // Data
            foreach ($driverOrders as $driverId => $data) {
                $driver = $data['driver'] ?? null;
                $driverName = $driver ? ($driver->first_name && $driver->last_name ? $driver->first_name . ' ' . $driver->last_name : $driver->name) : 'N/A';
                
                foreach ($data['orders'] as $orderData) {
                    fputcsv($file, [
                        $driverName,
                        $orderData['order_number'],
                        number_format($orderData['commission_value'], 2),
                        number_format($orderData['pending_money'], 2),
                        number_format($data['total_commission'], 2),
                        number_format($data['total_pending'], 2),
                        number_format($data['settlement_value'], 2),
                    ]);
                }
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export Request to PDF
     */
    private function exportRequestPDF($driverOrders)
    {
        return view('settlements.exports.request-pdf', compact('driverOrders'));
    }
}
