@extends('layouts.app')

@section('title', __('cms.attributes'))

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h2 class="section-heading-lg">{{ __('cms.attributes') }}</h2>
        <a href="{{ route('attributes.create') }}" class="btn btn-primary">{{ __('cms.add_new') }}</a>
    </div>

    <!-- Filters -->
    <div class="card mb-6 p-4">
        <form method="GET" action="{{ route('attributes.index') }}" class="flex gap-4 flex-wrap items-end">
            <div class="flex-1-min-200">
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ request('name') }}" 
                    class="form-input"
                    placeholder="{{ __('cms.search_by_name') }}"
                >
            </div>
            <div class="flex-1-min-200">
                <select id="status" name="status" class="form-input">
                    <option value="">{{ __('cms.all_status') }}</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('cms.active') }}</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('cms.inactive') }}</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">{{ __('cms.filter') }}</button>
                <a href="{{ route('attributes.index') }}" class="btn bg-gray-600 text-white ml-2">{{ __('cms.reset') }}</a>
            </div>
        </form>
    </div>

    <!-- Attributes Table -->
    <div class="overflow-x-auto">
        <table class="w-full" style="border-collapse: collapse;">
            <thead>
                <tr class="table-header-row">
                    <th class="table-cell-header">{{ __('cms.id') }}</th>
                    <th class="table-cell-header">{{ __('cms.name') }}</th>
                    <th class="table-cell-header">{{ __('cms.values') }}</th>
                    <th class="table-cell-header">{{ __('cms.status') }}</th>
                    <th class="table-cell-header">{{ __('cms.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attributes as $attribute)
                <tr class="table-row-border">
                    <td class="table-cell-padding">{{ $attribute->id }}</td>
                    <td class="table-cell-padding font-semibold">{{ $attribute->name }}</td>
                    <td class="table-cell-padding text-tertiary">
                        @if($attribute->values->count() > 0)
                            <div class="flex flex-wrap gap-1">
                                @foreach($attribute->values as $value)
                                    <span class="value-chip">{{ $value->value }}</span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-gray-400">{{ __('cms.no_values') }}</span>
                        @endif
                    </td>
                    <td class="table-cell-padding">
                        <span class="badge {{ $attribute->status == 'active' ? 'badge-green' : 'badge-red' }}">
                            {{ $attribute->status == 'active' ? __('cms.active') : __('cms.inactive') }}
                        </span>
                    </td>
                    <td class="table-cell-padding">
                        <div class="flex gap-2">
                            <a href="{{ route('attributes.edit', $attribute) }}" class="action-btn action-btn-edit" title="{{ __('cms.edit') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form action="{{ route('attributes.destroy', $attribute) }}" method="POST" class="d-inline" data-confirm="{{ __('cms.confirm_delete_attribute') }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn action-btn-delete" title="{{ __('cms.delete') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-tertiary">{{ __('cms.no_attributes_found') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $attributes->links() }}
    </div>
</div>
@endsection
