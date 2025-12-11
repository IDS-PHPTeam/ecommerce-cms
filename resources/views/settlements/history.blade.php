@extends('settlements.index')

@section('settlements-content')
<!-- Filters -->
<div class="card mb-6 p-4">
    <form method="GET" action="{{ route('settlements.history') }}">
        <div class="flex gap-4 flex-wrap items-end">
            <div class="flex-1-min-200">
                <label for="driver" class="form-label">{{ __('cms.driver') }}</label>
                <select id="driver" name="driver" class="form-input">
                    <option value="">{{ __('cms.all_drivers') }}</option>
                    @foreach($drivers as $driver)
                        <option value="{{ $driver->id }}" {{ request('driver') == $driver->id ? 'selected' : '' }}>
                            {{ $driver->first_name && $driver->last_name ? $driver->first_name . ' ' . $driver->last_name : $driver->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1-min-200">
                <label for="status" class="form-label">{{ __('cms.status') }}</label>
                <select id="status" name="status" class="form-input">
                    <option value="">{{ __('cms.all_status') }}</option>
                    <option value="requested" {{ request('status') == 'requested' ? 'selected' : '' }}>{{ __('cms.requested') }}</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>{{ __('cms.paid') }}</option>
                </select>
            </div>
            <div class="flex-1-min-200">
                <label for="date_from" class="form-label">{{ __('cms.from_date') }}</label>
                <input 
                    type="date" 
                    id="date_from" 
                    name="date_from" 
                    value="{{ request('date_from') }}" 
                    class="form-input"
                >
            </div>
            <div class="flex-1-min-200">
                <label for="date_to" class="form-label">{{ __('cms.to_date') }}</label>
                <input 
                    type="date" 
                    id="date_to" 
                    name="date_to" 
                    value="{{ request('date_to') }}" 
                    class="form-input"
                >
            </div>
            <div>
                <button type="submit" class="btn btn-primary">{{ __('cms.filter') }}</button>
                <a href="{{ route('settlements.history') }}" class="btn bg-gray-600 text-white ml-2">{{ __('cms.reset') }}</a>
            </div>
        </div>
    </form>
</div>

<!-- Export Buttons -->
<div class="flex justify-end gap-2 mb-4">
    <a href="{{ route('settlements.export-history', ['format' => 'excel']) . '?' . http_build_query(request()->all()) }}" 
       class="btn bg-green-600 text-white">
        {{ __('cms.export_excel') }}
    </a>
    <a href="{{ route('settlements.export-history', ['format' => 'pdf']) . '?' . http_build_query(request()->all()) }}" 
       class="btn bg-red-600 text-white">
        {{ __('cms.export_pdf') }}
    </a>
</div>

<!-- Settlement History Table -->
<div class="overflow-x-auto">
    <table class="w-full" style="border-collapse: collapse;">
        <thead>
            <tr class="table-header-row">
                <th class="table-cell-header">{{ __('cms.id') }}</th>
                <th class="table-cell-header">{{ __('cms.driver') }}</th>
                <th class="table-cell-header">{{ __('cms.settlement_date_time') }}</th>
                <th class="table-cell-header">{{ __('cms.value') }}</th>
                <th class="table-cell-header">{{ __('cms.status') }}</th>
                <th class="table-cell-header">{{ __('cms.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($settlements as $settlement)
            <tr class="table-row-border">
                <td class="table-cell-padding">{{ $settlement->id }}</td>
                <td class="table-cell-padding">
                    @if($settlement->driver)
                        {{ $settlement->driver->first_name && $settlement->driver->last_name ? $settlement->driver->first_name . ' ' . $settlement->driver->last_name : $settlement->driver->name }}
                    @else
                        <span class="text-gray-400">{{ __('cms.na') }}</span>
                    @endif
                </td>
                <td class="table-cell-padding">{{ $settlement->settlement_date->format('Y-m-d H:i:s') }}</td>
                <td class="table-cell-padding font-semibold">${{ number_format($settlement->value, 2) }}</td>
                <td class="table-cell-padding">
                    <span class="badge {{ $settlement->status == 'paid' ? 'badge-green' : 'badge-yellow' }}">
                        {{ $settlement->status == 'paid' ? __('cms.paid') : __('cms.requested') }}
                    </span>
                </td>
                <td class="table-cell-padding">
                    @if($settlement->status == 'requested')
                        <form method="POST" action="{{ route('settlements.update-status', $settlement) }}" class="d-inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="paid">
                            <button type="submit" class="btn bg-green-600 text-white px-3 py-1 text-sm">
                                {{ __('cms.mark_as_paid') }}
                            </button>
                        </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="p-8 text-center text-tertiary">{{ __('cms.no_settlements_found') }}</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $settlements->links() }}
</div>
@endsection




