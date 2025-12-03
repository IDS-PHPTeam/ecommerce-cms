<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Traits\LogsAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    use LogsAudit;
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

    /**
     * Show the form for creating a new customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created customer in storage.
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
            'profile_image' => 'nullable|image|max:2048',
            'account_status' => 'required|in:active_not_verified,active_verified,deactivated,suspended',
        ]);

        $validated['name'] = $validated['first_name'] . ' ' . $validated['last_name'];
        $validated['role'] = 'customer';
        $validated['password'] = Hash::make($validated['password']);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $year = date('Y');
            $month = date('m');
            $validated['profile_image'] = $request->file('profile_image')->store("profiles/{$year}/{$month}", 'public');
        } elseif ($request->filled('selected_media_path')) {
            $validated['profile_image'] = $request->selected_media_path;
        }

        $customer = User::create($validated);

        $this->logAudit('created', $customer, "Customer created: {$customer->name} ({$customer->email})");

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Show the form for editing the specified customer.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'profile_image' => 'nullable|image|max:2048',
            'account_status' => 'required|in:active_not_verified,active_verified,deactivated,suspended',
        ]);

        $oldValues = $this->getOldValues($customer, ['first_name', 'last_name', 'email', 'phone', 'profile_image', 'account_status']);

        $validated['name'] = $validated['first_name'] . ' ' . $validated['last_name'];
        
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($customer->profile_image) {
                Storage::disk('public')->delete($customer->profile_image);
            }
            $year = date('Y');
            $month = date('m');
            $validated['profile_image'] = $request->file('profile_image')->store("profiles/{$year}/{$month}", 'public');
        } elseif ($request->filled('selected_media_path')) {
            // Delete old image if different
            if ($customer->profile_image && $customer->profile_image !== $request->selected_media_path) {
                Storage::disk('public')->delete($customer->profile_image);
            }
            $validated['profile_image'] = $request->selected_media_path;
        } elseif ($request->input('delete_current_image') == '1') {
            if ($customer->profile_image) {
                Storage::disk('public')->delete($customer->profile_image);
            }
            $validated['profile_image'] = null;
        }

        $customer->update($validated);

        $newValues = $this->getNewValues($validated, ['first_name', 'last_name', 'email', 'phone', 'profile_image', 'account_status']);
        $this->logAudit('updated', $customer, "Customer updated: {$customer->name} ({$customer->email})", $oldValues, $newValues);

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified customer from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);
        $customerName = $customer->name;
        $customerEmail = $customer->email;

        $this->logAudit('deleted', $customer, "Customer deleted: {$customerName} ({$customerEmail})");

        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }
}
