<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DriverController extends Controller
{
    /**
     * Display a listing of drivers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'driver');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('driver_status', $request->status);
        }

        // Search by name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $drivers = $query->orderBy('first_name')->orderBy('last_name')->paginate(15);

        // Calculate performance metrics for each driver
        foreach ($drivers as $driver) {
            $orders = Order::where('driver_id', $driver->id)->get();
            $driver->total_orders_count = $orders->count();
            $driver->completed_orders_count = $orders->where('status', 'completed')->count();
            $driver->failed_orders_count = $orders->where('status', 'failed')->count();
            $driver->success_rate = $driver->total_orders_count > 0 
                ? round(($driver->completed_orders_count / $driver->total_orders_count) * 100, 2) 
                : 0;
            
            // Calculate average rating
            $ratings = $orders->whereNotNull('rating')->pluck('rating');
            $driver->average_rating = $ratings->count() > 0 ? round($ratings->avg(), 2) : 0;
        }

        return view('drivers.index', compact('drivers'));
    }

    /**
     * Display the specified driver.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $driver = User::where('role', 'driver')->findOrFail($id);
        
        // Get order history
        $orders = Order::where('driver_id', $id)
            ->orderBy('order_date', 'desc')
            ->paginate(10);

        // Calculate performance metrics
        $allOrders = Order::where('driver_id', $id)->get();
        $totalOrders = $allOrders->count();
        $completedOrders = $allOrders->where('status', 'completed')->count();
        $failedOrders = $allOrders->where('status', 'failed')->count();
        $successRate = $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100, 2) : 0;
        
        // Average rating
        $ratings = $allOrders->whereNotNull('rating')->pluck('rating');
        $averageRating = $ratings->count() > 0 ? round($ratings->avg(), 2) : 0;

        // COD Logs
        $codLogs = DB::table('cod_logs')
            ->where('driver_id', $id)
            ->join('orders', 'cod_logs.order_id', '=', 'orders.id')
            ->select('cod_logs.*', 'orders.order_date', 'orders.customer_name', 'orders.total')
            ->orderBy('cod_logs.created_at', 'desc')
            ->paginate(10);

        // Feedback & Ratings
        $feedback = $allOrders->whereNotNull('feedback')->map(function($order) {
            return [
                'order_id' => $order->id,
                'rating' => $order->rating,
                'feedback' => $order->feedback,
                'date' => $order->order_date,
                'customer' => $order->customer_name,
            ];
        });

        return view('drivers.show', compact('driver', 'orders', 'totalOrders', 'completedOrders', 'failedOrders', 'successRate', 'averageRating', 'codLogs', 'feedback'));
    }
}
