<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class MediaController extends Controller
{
    /**
     * Display a listing of all media files.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $images = [];
        $storagePath = storage_path('app/public');
        
        // Get all image files from storage root (recursively)
        // Display all images regardless of folder structure
        if (File::exists($storagePath)) {
            $files = File::allFiles($storagePath);
            foreach ($files as $file) {
                $extension = strtolower($file->getExtension());
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
                    // Get relative path from storage/app/public
                    $fullPath = $file->getPathname();
                    $relativePath = str_replace($storagePath . DIRECTORY_SEPARATOR, '', str_replace('\\', '/', $fullPath));
                    
                    // Skip system/hidden files and directories
                    $pathParts = explode('/', $relativePath);
                    $skipDirs = ['.git', '.svn', 'cache', 'logs', 'framework'];
                    $shouldSkip = false;
                    foreach ($pathParts as $part) {
                        if (in_array(strtolower($part), $skipDirs) || strpos($part, '.') === 0) {
                            $shouldSkip = true;
                            break;
                        }
                    }
                    
                    if (!$shouldSkip) {
                        $modified = filemtime($file->getPathname());
                        $imageDate = date('Y-m-d', $modified);
                        
                        // Determine directory for display
                        $directory = '';
                        if (count($pathParts) >= 2) {
                            // If it's year/month structure, show that
                            if (is_numeric($pathParts[0]) && is_numeric($pathParts[1])) {
                                $directory = $pathParts[0] . '/' . $pathParts[1];
                            } else {
                                // Otherwise show the first directory
                                $directory = $pathParts[0];
                            }
                        }
                        
                        $images[] = [
                            'path' => $relativePath,
                            'url' => asset('storage/' . $relativePath),
                            'name' => $file->getFilename(),
                            'size' => $file->getSize(),
                            'modified' => $modified,
                            'date' => $imageDate,
                            'directory' => $directory,
                        ];
                    }
                }
            }
        }
        
        // Apply filters
        $searchName = $request->get('search_name');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        
        if ($searchName) {
            $images = array_filter($images, function($image) use ($searchName) {
                return stripos($image['name'], $searchName) !== false;
            });
        }
        
        if ($dateFrom) {
            $images = array_filter($images, function($image) use ($dateFrom) {
                return $image['date'] >= $dateFrom;
            });
        }
        
        if ($dateTo) {
            $images = array_filter($images, function($image) use ($dateTo) {
                return $image['date'] <= $dateTo;
            });
        }
        
        // Re-index array after filtering
        $images = array_values($images);
        
        // Sort by modified date (newest first)
        usort($images, function($a, $b) {
            return $b['modified'] - $a['modified'];
        });
        
        // Pagination
        $perPage = 24;
        $currentPage = $request->get('page', 1);
        $total = count($images);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedImages = array_slice($images, $offset, $perPage);
        
        // Create paginator manually
        $images = new \Illuminate\Pagination\LengthAwarePaginator(
            collect($paginatedImages),
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        
        return view('media.index', compact('images'));
    }
    
    /**
     * Delete a media file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $path = $request->input('path');
        
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            return redirect()->route('media.index')
                ->with('success', 'Image deleted successfully.');
        }
        
        return redirect()->route('media.index')
            ->with('error', 'Image not found.');
    }
    
    /**
     * Get all media files as JSON for selection modal.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMediaJson()
    {
        $images = [];
        $storagePath = storage_path('app/public');
        
        // Get all image files from storage root (recursively)
        // Display all images regardless of folder structure
        if (File::exists($storagePath)) {
            $files = File::allFiles($storagePath);
            foreach ($files as $file) {
                $extension = strtolower($file->getExtension());
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
                    // Get relative path from storage/app/public
                    $fullPath = $file->getPathname();
                    $relativePath = str_replace($storagePath . DIRECTORY_SEPARATOR, '', str_replace('\\', '/', $fullPath));
                    
                    // Skip system/hidden files and directories
                    $pathParts = explode('/', $relativePath);
                    $skipDirs = ['.git', '.svn', 'cache', 'logs', 'framework'];
                    $shouldSkip = false;
                    foreach ($pathParts as $part) {
                        if (in_array(strtolower($part), $skipDirs) || strpos($part, '.') === 0) {
                            $shouldSkip = true;
                            break;
                        }
                    }
                    
                    if (!$shouldSkip) {
                        $images[] = [
                            'path' => $relativePath,
                            'url' => asset('storage/' . $relativePath),
                            'name' => $file->getFilename(),
                        ];
                    }
                }
            }
        }
        
        // Sort by modified date (newest first)
        usort($images, function($a, $b) use ($storagePath) {
            $timeA = filemtime($storagePath . '/' . $a['path']);
            $timeB = filemtime($storagePath . '/' . $b['path']);
            return $timeB - $timeA;
        });
        
        return response()->json($images);
    }
}
