@extends('layouts.app')

@section('title', 'Media')

@section('content')
<div class="card">
    <div class="flex justify-between items-center mb-6">
        <h2 class="section-heading-lg">Media Library</h2>
    </div>

    <!-- Filters -->
    <div class="card mb-6 p-4">
        <form method="GET" action="{{ route('media.index') }}">
            <div class="flex gap-4 flex-wrap items-end">
                <div class="flex-1-min-200">
                    <input 
                        type="text" 
                        id="search_name" 
                        name="search_name" 
                        value="{{ request('search_name') }}" 
                        class="form-input"
                        placeholder="Search by name..."
                    >
                </div>
                <div class="flex-1-min-200">
                    <label for="date_from" class="form-label">Date From</label>
                    <input 
                        type="date" 
                        id="date_from" 
                        name="date_from" 
                        value="{{ request('date_from') }}" 
                        class="form-input"
                    >
                </div>
                <div class="flex-1-min-200">
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
                    <a href="{{ route('media.index') }}" class="btn bg-gray-600 text-white ml-2">Reset</a>
                </div>
            </div>
        </form>
    </div>

    @if($images->count() > 0)
        <div class="grid-auto-200 gap-6">
            @foreach($images as $image)
            <div class="media-item relative bg-white border border-gray-200 rounded-md overflow-hidden transition-all" data-image-url="{{ $image['url'] }}" data-image-name="{{ $image['name'] }}" data-media-type="{{ $image['type'] ?? 'image' }}">
                <div class="relative w-full aspect-square bg-gray-100">
                    @if(isset($image['type']) && $image['type'] === 'video')
                        <video src="{{ $image['url'] }}" class="media-video absolute top-0 left-0 w-full h-full object-cover cursor-pointer" muted></video>
                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 pointer-events-none z-5">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" width="48" height="48" class="opacity-80">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </div>
                    @else
                        <img src="{{ $image['url'] }}" alt="{{ $image['name'] }}" class="media-image absolute top-0 left-0 w-full h-full object-cover cursor-pointer">
                    @endif
                    <form action="{{ route('media.destroy') }}" method="POST" class="absolute top-2 right-2 z-10" data-confirm="Are you sure you want to delete this {{ isset($image['type']) && $image['type'] === 'video' ? 'video' : 'image' }}?">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="path" value="{{ $image['path'] }}">
                        <button type="submit" class="action-btn action-btn-delete w-7 h-7" title="Delete {{ isset($image['type']) && $image['type'] === 'video' ? 'video' : 'image' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
                <div class="p-3">
                    <p class="text-sm font-semibold text-primary mb-1 whitespace-nowrap overflow-hidden text-ellipsis" title="{{ $image['name'] }}">{{ $image['name'] }}</p>
                    <p class="text-xs text-tertiary mb-1">{{ number_format($image['size'] / 1024, 2) }} KB</p>
                    <p class="text-xs text-tertiary">{{ isset($image['date']) ? date('M d, Y', strtotime($image['date'])) : date('M d, Y', $image['modified']) }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $images->links() }}
        </div>
    @else
        <div class="text-center p-12 text-tertiary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="64" height="64" class="mx-auto mb-4 text-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <p class="text-lg font-semibold">No images found</p>
            <p class="text-sm mt-2">Upload images through products to see them here.</p>
        </div>
    @endif
</div>

<!-- Media Modal -->
<div id="imageModal" class="image-modal-overlay d-none" onclick="closeImageModal()">
    <div class="image-modal-content" onclick="event.stopPropagation();">
        <button type="button" class="image-modal-close" id="closeImageModal">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <div class="image-modal-body">
            <img id="modalImage" src="" alt="" class="image-modal-img d-none">
            <video id="modalVideo" src="" controls class="image-modal-img d-none max-w-full max-h-80vh"></video>
        </div>
        <div class="image-modal-footer">
            <p id="modalImageName"></p>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/media.js') }}"></script>
@endpush
@endsection

