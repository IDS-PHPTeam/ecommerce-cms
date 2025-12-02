@extends('layouts.app')

@section('title', 'Media')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.875rem; font-weight: 700;">Media Library</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 1.5rem;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error" style="margin-bottom: 1.5rem;">
            {{ session('error') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="card" style="margin-bottom: 1.5rem; padding: 1rem;">
        <form method="GET" action="{{ route('media.index') }}">
            <div style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: end;">
                <div style="flex: 1; min-width: 200px;">
                    <input 
                        type="text" 
                        id="search_name" 
                        name="search_name" 
                        value="{{ request('search_name') }}" 
                        class="form-input"
                        placeholder="Search by name..."
                    >
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <label for="date_from" class="form-label">Date From</label>
                    <input 
                        type="date" 
                        id="date_from" 
                        name="date_from" 
                        value="{{ request('date_from') }}" 
                        class="form-input"
                    >
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <label for="date_to" class="form-label">Date To</label>
                    <input 
                        type="date" 
                        id="date_to" 
                        name="date_to" 
                        value="{{ request('date_to') }}" 
                        class="form-input"
                    >
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('media.index') }}" class="btn" style="background-color: #6b7280; color: white; margin-left: 0.5rem;">Reset</a>
                </div>
            </div>
        </form>
    </div>

    @if($images->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem;">
            @foreach($images as $image)
            <div class="media-item" data-image-url="{{ $image['url'] }}" data-image-name="{{ $image['name'] }}" style="position: relative; background: white; border: 1px solid #e5e7eb; border-radius: 0.5rem; overflow: hidden; transition: transform 0.2s, box-shadow 0.2s;">
                <div style="position: relative; width: 100%; padding-top: 100%; background-color: #f3f4f6;">
                    <img src="{{ $image['url'] }}" alt="{{ $image['name'] }}" class="media-image" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                    <form action="{{ route('media.destroy') }}" method="POST" style="position: absolute; top: 0.5rem; right: 0.5rem; z-index: 10;" data-confirm="Are you sure you want to delete this image?">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="path" value="{{ $image['path'] }}">
                        <button type="submit" class="action-btn action-btn-delete" title="Delete image" style="width: 28px; height: 28px;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
                <div style="padding: 0.75rem;">
                    <p style="font-size: 0.875rem; font-weight: 600; color: #1f2937; margin-bottom: 0.25rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $image['name'] }}">{{ $image['name'] }}</p>
                    <p style="font-size: 0.75rem; color: #6b7280; margin-bottom: 0.25rem;">{{ number_format($image['size'] / 1024, 2) }} KB</p>
                    <p style="font-size: 0.75rem; color: #6b7280;">{{ isset($image['date']) ? date('M d, Y', strtotime($image['date'])) : date('M d, Y', $image['modified']) }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div style="margin-top: 2rem;">
            {{ $images->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 3rem; color: #6b7280;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="64" height="64" style="margin: 0 auto 1rem; color: #9ca3af;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <p style="font-size: 1.125rem; font-weight: 600;">No images found</p>
            <p style="font-size: 0.875rem; margin-top: 0.5rem;">Upload images through products to see them here.</p>
        </div>
    @endif
</div>

<!-- Image Modal -->
<div id="imageModal" class="modal-overlay" style="display: none;" onclick="closeImageModal()">
    <div class="modal-content" style="max-width: 90vw; max-height: 90vh; padding: 0; background: transparent; box-shadow: none;" onclick="event.stopPropagation();">
        <button type="button" class="modal-close" id="closeImageModal" style="position: absolute; top: -2.5rem; right: 0; color: white; background: rgba(0,0,0,0.5); border-radius: 50%; width: 40px; height: 40px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <img id="modalImage" src="" alt="" style="max-width: 100%; max-height: 90vh; object-fit: contain; border-radius: 0.5rem;">
        <p id="modalImageName" style="color: white; text-align: center; margin-top: 1rem; font-weight: 600;"></p>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/media.js') }}"></script>
@endpush
@endsection

