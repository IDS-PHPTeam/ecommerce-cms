@extends('settlements.index')

@section('settlements-content')
<!-- Filters -->
<div class="card" style="margin-bottom: 1.5rem; padding: 1rem;">
    <form method="GET" action="{{ route('settlements.history') }}">
        <div style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: end;">
            <div style="flex: 1; min-width: 200px;">
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
            <div style="flex: 1; min-width: 200px;">
                <label for="status" class="form-label">{{ __('cms.status') }}</label>
                <select id="status" name="status" class="form-input">
                    <option value="">{{ __('cms.all_status') }}</option>
                    <option value="requested" {{ request('status') == 'requested' ? 'selected' : '' }}>{{ __('cms.requested') }}</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>{{ __('cms.paid') }}</option>
                </select>
            </div>
            <div style="flex: 1; min-width: 200px;">
                <label for="date_from" class="form-label">{{ __('cms.from_date') }}</label>
                <input 
                    type="date" 
                    id="date_from" 
                    name="date_from" 
                    value="{{ request('date_from') }}" 
                    class="form-input"
                >
            </div>
            <div style="flex: 1; min-width: 200px;">
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
                <a href="{{ route('settlements.history') }}" class="btn" style="background-color: #6b7280; color: white; margin-left: 0.5rem;">{{ __('cms.reset') }}</a>
            </div>
        </div>
    </form>
</div>

<!-- Export Buttons -->
<div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-bottom: 1rem;">
    <a href="{{ route('settlements.export-history', ['format' => 'excel']) . '?' . http_build_query(request()->all()) }}" 
       class="btn" 
       style="background-color: #059669; color: white;">
        {{ __('cms.export_excel') }}
    </a>
    <a href="{{ route('settlements.export-history', ['format' => 'pdf']) . '?' . http_build_query(request()->all()) }}" 
       class="btn" 
       style="background-color: #dc2626; color: white;">
        {{ __('cms.export_pdf') }}
    </a>
</div>

<!-- Settlement History Table -->
<div style="overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr class="table-header-row">
                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">{{ __('cms.id') }}</th>
                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">{{ __('cms.driver') }}</th>
                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">{{ __('cms.settlement_date_time') }}</th>
                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">{{ __('cms.value') }}</th>
                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">{{ __('cms.status') }}</th>
                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">{{ __('cms.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($settlements as $settlement)
            <tr style="border-bottom: 1px solid #e5e7eb;">
                <td style="padding: 0.75rem;">{{ $settlement->id }}</td>
                <td style="padding: 0.75rem;">
                    @if($settlement->driver)
                        {{ $settlement->driver->first_name && $settlement->driver->last_name ? $settlement->driver->first_name . ' ' . $settlement->driver->last_name : $settlement->driver->name }}
                    @else
                        <span style="color: #9ca3af;">{{ __('cms.na') }}</span>
                    @endif
                </td>
                <td style="padding: 0.75rem;">{{ $settlement->settlement_date->format('Y-m-d H:i:s') }}</td>
                <td style="padding: 0.75rem; font-weight: 600;">${{ number_format($settlement->value, 2) }}</td>
                <td style="padding: 0.75rem;">
                    <span style="padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 500; 
                        {{ $settlement->status == 'paid' ? 'background-color: #d1fae5; color: #065f46;' : 'background-color: #fef3c7; color: #92400e;' }}">
                        {{ $settlement->status == 'paid' ? __('cms.paid') : __('cms.requested') }}
                    </span>
                </td>
                <td style="padding: 0.75rem;">
                    @if($settlement->status == 'requested')
                        <form method="POST" action="{{ route('settlements.update-status', $settlement) }}" style="display: inline;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="paid">
                            <button type="submit" class="btn" style="background-color: #059669; color: white; padding: 0.25rem 0.75rem; font-size: 0.875rem;">
                                {{ __('cms.mark_as_paid') }}
                            </button>
                        </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding: 2rem; text-align: center; color: #6b7280;">{{ __('cms.no_settlements_found') }}</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div style="margin-top: 1.5rem;">
    {{ $settlements->links() }}
</div>
@endsection




