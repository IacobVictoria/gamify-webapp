<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewMediaRequest;
use App\Models\ReviewMedia;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReviewMediaController extends Controller
{
    public function store(ReviewMediaRequest $request, $reviewId)
    {
      
         $file = $request->file('media_file');
   
            $fileType = in_array($file->extension(), ['jpg', 'jpeg', 'png']) ? 'image' : (in_array($file->extension(), ['mp4', 'mov', 'avi', 'mkv', 'wmv']) ? 'video' : 'unknown');

            $filePath = $file->store('public/media', 's3');
        
            Storage::disk('s3')->setVisibility($filePath, 'public');

            $fileUrl = Storage::disk('s3')->url($filePath);
          
           $review_media= ReviewMedia::create([
                'id' => Uuid::uuid(),
                'review_id' => $reviewId,
                'filename' => $filePath,
                'url' => $fileUrl,
                'type' => $fileType
            ]);

        return back()->with('success', 'Files created successfully!');
    }

    public function destroy(string $reviewMediaId)
    {

        $reviewMedia = ReviewMedia::findOrFail($reviewMediaId);
        $filePath = "public/media/{$reviewMedia->filename}";

        try {
            $deleted = Storage::disk('s3')->delete($filePath);

            if (!$deleted) {
                return back()->with('error', 'Failed to delete media file from S3.');
            }
        } catch (\Exception $e) {
         
            \Log::error('S3 deletion error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while deleting the media file: ' . $e->getMessage());
        }

        $reviewMedia->delete();

        return back()->with('success', 'Media deleted successfully!');
    }
}
