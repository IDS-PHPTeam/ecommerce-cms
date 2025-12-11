@extends('layouts.app')

@section('title', __('cms.customer_details'))

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h2 class="section-heading-lg">{{ __('cms.customer_details') }}</h2>
        <a href="{{ route('customers.index') }}" class="btn bg-gray-600 text-white">{{ __('cms.back_to_list') }}</a>
    </div>

    <!-- Customer Info -->
    <div class="card mb-6 p-6">
        <div class="flex gap-8 items-start">
            @if($customer->profile_image)
                <div>
                    <img src="{{ asset('storage/' . $customer->profile_image) }}" alt="Profile" class="w-150 h-150 rounded-full border-3 border-primary-blue object-cover">
                </div>
            @else
                <div class="w-150 h-150 rounded-full border-3 border-gray-200 bg-gray-100 flex-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="60" height="60" class="text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            @endif
            <div class="flex-1">
                <h3 class="section-heading mb-4">{{ __('cms.customer_information') }}</h3>
                <div class="grid grid-auto-250 gap-4">
                    <div>
                        <label class="form-label">{{ __('cms.id') }}</label>
                        <p class="mt-1 font-semibold">#{{ $customer->id }}</p>
                    </div>
                    <div>
                        <label class="form-label">{{ __('cms.name') }}</label>
                        <p class="mt-1">{{ $customer->first_name && $customer->last_name ? $customer->first_name . ' ' . $customer->last_name : $customer->name }}</p>
                    </div>
                    <div>
                        <label class="form-label">{{ __('cms.email') }}</label>
                        <p class="mt-1">{{ $customer->email }}</p>
                    </div>
                    <div>
                        <label class="form-label">{{ __('cms.mobile') }}</label>
                        <p class="mt-1">{{ $customer->phone ?? __('cms.na') }}</p>
                    </div>
                    <div>
                        <label class="form-label">{{ __('cms.account_status') }}</label>
                        <p class="mt-1">
                            @php
                                $statusBadgeClasses = [
                                    'active_not_verified' => 'badge-yellow',
                                    'active_verified' => 'badge-green',
                                    'deactivated' => 'badge-red',
                                    'suspended' => 'badge-red',
                                ];
                                $statusLabels = [
                                    'active_not_verified' => __('cms.active_not_verified'),
                                    'active_verified' => __('cms.active_verified'),
                                    'deactivated' => __('cms.deactivated'),
                                    'suspended' => __('cms.suspended'),
                                ];
                                $status = $customer->account_status ?? 'active_not_verified';
                            @endphp
                            <span class="badge {{ $statusBadgeClasses[$status] ?? 'badge-yellow' }}">
                                {{ $statusLabels[$status] ?? ucfirst($status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Addresses -->
    @if(count($addresses) > 0)
    <div class="card mb-6 p-6">
        <h3 class="section-heading mb-4">{{ __('cms.addresses') }}</h3>
        <div class="flex-col gap-4">
            @foreach($addresses as $index => $address)
            <div class="p-4 bg-gray-50 rounded-md border-l-4 border-primary-blue">
                <p class="font-semibold text-primary mb-2">{{ __('cms.address') }} {{ $index + 1 }}</p>
                @if(is_array($address))
                    <p class="text-gray-700">{{ implode(', ', array_filter($address)) }}</p>
                @else
                    <p class="text-gray-700">{{ $address }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Order History -->
    <div class="card mb-6 p-6">
        <h3 class="section-heading mb-4">{{ __('cms.order_history') }}</h3>
        <div class="overflow-x-auto">
            <table class="w-full" style="border-collapse: collapse;">
                <thead>
                    <tr class="table-header-row">
                        <th class="table-cell-header">{{ __('cms.order_id') }}</th>
                        <th class="table-cell-header">{{ __('cms.location') }}</th>
                        <th class="table-cell-header">{{ __('cms.total') }}</th>
                        <th class="table-cell-header">{{ __('cms.status') }}</th>
                        <th class="table-cell-header">{{ __('cms.order_date') }}</th>
                        <th class="table-cell-header">{{ __('cms.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr class="table-row-border">
                        <td class="table-cell-padding">{{ $order->id }}</td>
                        <td class="table-cell-padding max-w-200 word-break">{{ Str::limit($order->location, 50) }}</td>
                        <td class="table-cell-padding">${{ number_format($order->total, 2) }}</td>
                        <td class="table-cell-padding">
                            @php
                                $statusBadgeClass = $order->status == 'completed' ? 'badge-green' : 
                                                   ($order->status == 'failed' ? 'badge-red' : 
                                                   ($order->status == 'assigned' ? 'badge-blue' : 'badge-yellow'));
                                $statusTranslations = [
                                    'pending' => __('cms.pending'),
                                    'assigned' => __('cms.assigned'),
                                    'failed' => __('cms.failed'),
                                    'completed' => __('cms.completed'),
                                ];
                            @endphp
                            <span class="badge {{ $statusBadgeClass }}">
                                {{ $statusTranslations[$order->status] ?? ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="table-cell-padding">{{ $order->order_date->format('M d, Y') }}</td>
                        <td class="table-cell-padding">
                            <a href="{{ route('orders.show', $order) }}" class="action-btn action-btn-edit" title="{{ __('cms.view') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-tertiary">{{ __('cms.no_orders_found') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>

    <!-- Feedback & Ratings -->
    @if($feedback->count() > 0)
    <div class="card p-6">
        <h3 class="section-heading mb-4">{{ __('cms.feedback_ratings') }}</h3>
        <div class="flex-col gap-4">
            @foreach($feedback as $item)
            <div class="p-4 bg-gray-50 rounded-md border-l-4 border-primary-blue">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <p class="font-semibold text-primary">{{ __('cms.order') }} #{{ $item['order_id'] }}</p>
                        <p class="text-sm text-tertiary">{{ \Carbon\Carbon::parse($item['date'])->format('M d, Y') }}</p>
                    </div>
                    @if($item['rating'])
                    <span class="text-yellow-500 font-semibold">★ {{ $item['rating'] }}/5</span>
                    @endif
                </div>
                @if($item['feedback'])
                <p class="text-gray-700 mt-2">{{ $item['feedback'] }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection


