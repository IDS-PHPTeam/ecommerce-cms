<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'customer');

        // Filter by account status
        if ($request->filled('status')) {
            $query->where('account_status', $request->status);
        }

        // Search by name, email, or phone
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy('first_name')->orderBy('last_name')->paginate(15);

        // Add order count for each customer
        foreach ($customers as $customer) {
            $customer->orders_count = Order::where('customer_email', $customer->email)->count();
        }

        return view('customers.index', compact('customers'));
    }

    /**
     * Display the specified customer.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);
        
        // Get order history
        $orders = Order::where('customer_email', $customer->email)
            ->orderBy('order_date', 'desc')
            ->paginate(10);

        // Parse addresses
        $addresses = [];
        if ($customer->addresses) {
            $addresses = json_decode($customer->addresses, true) ?? [];
        }

        // Feedback & Ratings
        $allOrders = Order::where('customer_email', $customer->email)->get();
        $feedback = $allOrders->whereNotNull('feedback')->map(function($order) {
            return [
                'order_id' => $order->id,
                'rating' => $order->rating,
                'feedback' => $order->feedback,
                'date' => $order->order_date,
            ];
        });

        return view('customers.show', compact('customer', 'orders', 'addresses', 'feedback'));
    }
}
