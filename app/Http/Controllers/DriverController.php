<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Traits\LogsAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DriverController extends Controller
{
    use LogsAudit;
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

        return view('drivers.show', compact('driver', 'orders', 'totalOrders', 'completedOrders', 'failedOrders', 'successRate', 'averageRating', 'feedback'));
    }

    /**
     * Show the form for creating a new driver.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('drivers.create');
    }

    /**
     * Store a newly created driver in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'driver_status' => 'required|in:active,inactive',
            'load_capacity' => 'nullable|numeric|min:0',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        $validated['name'] = $validated['first_name'] . ' ' . $validated['last_name'];
        $validated['role'] = 'driver';
        $validated['password'] = Hash::make($validated['password']);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $year = date('Y');
            $month = date('m');
            $validated['profile_image'] = $request->file('profile_image')->store("profiles/{$year}/{$month}", 'public');
        } elseif ($request->filled('selected_media_path')) {
            $validated['profile_image'] = $request->selected_media_path;
        }

        $driver = User::create($validated);

        $this->logAudit('created', $driver, "Driver created: {$driver->name} ({$driver->email})");

        return redirect()->route('drivers.index')
            ->with('success', 'Driver created successfully.');
    }

    /**
     * Show the form for editing the specified driver.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $driver = User::where('role', 'driver')->findOrFail($id);
        return view('drivers.edit', compact('driver'));
    }

    /**
     * Update the specified driver in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $driver = User::where('role', 'driver')->findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'driver_status' => 'required|in:active,inactive',
            'load_capacity' => 'nullable|numeric|min:0',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        $oldValues = $this->getOldValues($driver, ['first_name', 'last_name', 'email', 'phone', 'driver_status', 'load_capacity', 'profile_image']);

        $validated['name'] = $validated['first_name'] . ' ' . $validated['last_name'];
        
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($driver->profile_image) {
                Storage::disk('public')->delete($driver->profile_image);
            }
            $year = date('Y');
            $month = date('m');
            $validated['profile_image'] = $request->file('profile_image')->store("profiles/{$year}/{$month}", 'public');
        } elseif ($request->filled('selected_media_path')) {
            // Delete old image if different
            if ($driver->profile_image && $driver->profile_image !== $request->selected_media_path) {
                Storage::disk('public')->delete($driver->profile_image);
            }
            $validated['profile_image'] = $request->selected_media_path;
        } elseif ($request->input('delete_current_image') == '1') {
            if ($driver->profile_image) {
                Storage::disk('public')->delete($driver->profile_image);
            }
            $validated['profile_image'] = null;
        }

        $driver->update($validated);

        $newValues = $this->getNewValues($validated, ['first_name', 'last_name', 'email', 'phone', 'driver_status', 'load_capacity', 'profile_image']);
        $this->logAudit('updated', $driver, "Driver updated: {$driver->name} ({$driver->email})", $oldValues, $newValues);

        return redirect()->route('drivers.index')
            ->with('success', 'Driver updated successfully.');
    }

    /**
     * Remove the specified driver from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $driver = User::where('role', 'driver')->findOrFail($id);
        $driverName = $driver->name;
        $driverEmail = $driver->email;

        $this->logAudit('deleted', $driver, "Driver deleted: {$driverName} ({$driverEmail})");

        $driver->delete();

        return redirect()->route('drivers.index')
            ->with('success', 'Driver deleted successfully.');
    }
}
