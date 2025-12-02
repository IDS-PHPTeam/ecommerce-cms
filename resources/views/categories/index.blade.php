@extends('layouts.app')

@section('title', 'Categories')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.875rem; font-weight: 700;">Categories</h2>
        <a href="{{ route('categories.create') }}" class="btn btn-primary">+ Add New</a>
    </div>

    <!-- Filters -->
    <div class="card" style="margin-bottom: 1.5rem; padding: 1rem;">
        <form method="GET" action="{{ route('categories.index') }}" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: end;">
            <div style="flex: 1; min-width: 200px;">
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ request('name') }}" 
                    class="form-input"
                    placeholder="Search by name..."
                >
            </div>
            <div style="flex: 1; min-width: 200px;">
                <select id="status" name="status" class="form-input">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('categories.index') }}" class="btn" style="background-color: #6b7280; color: white; margin-left: 0.5rem;">Reset</a>
            </div>
        </form>
    </div>

    <!-- Categories Table -->
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f3f4f6; border-bottom: 2px solid #e5e7eb;">
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">ID</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Name</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Description</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Status</th>
                    <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 0.75rem;">{{ $category->id }}</td>
                    <td style="padding: 0.75rem; font-weight: 600;">{{ $category->name }}</td>
                    <td style="padding: 0.75rem; color: #6b7280;">{{ Str::limit($category->description, 50) ?: 'No description' }}</td>
                    <td style="padding: 0.75rem;">
                        <span style="padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 500; {{ $category->status == 'active' ? 'background-color: #d1fae5; color: #065f46;' : 'background-color: #fee2e2; color: #991b1b;' }}">
                            {{ ucfirst($category->status) }}
                        </span>
                    </td>
                    <td style="padding: 0.75rem;">
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('categories.edit', $category) }}" class="action-btn action-btn-edit" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display: inline;" data-confirm="Are you sure you want to delete this category?">
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
                    <td colspan="5" style="padding: 2rem; text-align: center; color: #6b7280;">No categories found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="margin-top: 1.5rem;">
        {{ $categories->links() }}
    </div>
</div>
@endsection

