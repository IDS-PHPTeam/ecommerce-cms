@extends('layouts.app')

@section('title', 'Categories')

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h2 class="section-heading-lg">Categories</h2>
        <a href="{{ route('categories.create') }}" class="btn btn-primary">+ Add New</a>
    </div>

    <!-- Filters -->
    <div class="card mb-6 p-4">
        <form method="GET" action="{{ route('categories.index') }}" class="flex gap-4 flex-wrap items-end">
            <div class="flex-1-min-200">
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ request('name') }}" 
                    class="form-input"
                    placeholder="Search by name..."
                >
            </div>
            <div class="flex-1-min-200">
                <select id="status" name="status" class="form-input">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('categories.index') }}" class="btn bg-gray-600 text-white ml-2">Reset</a>
            </div>
        </form>
    </div>

    <!-- Categories Table -->
    <div class="overflow-x-auto">
        <table class="w-full" style="border-collapse: collapse;">
            <thead>
                <tr class="table-header-row">
                    <th class="table-cell-header">ID</th>
                    <th class="table-cell-header">Name</th>
                    <th class="table-cell-header">Description</th>
                    <th class="table-cell-header">Status</th>
                    <th class="table-cell-header">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr class="table-row-border">
                    <td class="table-cell-padding">{{ $category->id }}</td>
                    <td class="table-cell-padding font-semibold">{{ $category->name }}</td>
                    <td class="table-cell-padding text-tertiary">{{ Str::limit($category->description, 50) ?: 'No description' }}</td>
                    <td class="table-cell-padding">
                        <span class="badge {{ $category->status == 'active' ? 'badge-green' : 'badge-red' }}">
                            {{ ucfirst($category->status) }}
                        </span>
                    </td>
                    <td class="table-cell-padding">
                        <div class="flex gap-2">
                            <a href="{{ route('categories.edit', $category) }}" class="action-btn action-btn-edit" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline" data-confirm="Are you sure you want to delete this category?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn action-btn-delete" title="Delete">
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
                    <td colspan="5" class="p-8 text-center text-tertiary">No categories found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $categories->links() }}
    </div>
</div>
@endsection

