<?php

namespace App\Helpers;

use Awcodes\Curator\Models\Media;
use Illuminate\Support\Facades\Storage;

class MediaHelper
{
    public static function getMediaImagePath($mediaId)
    {
        $media = Media::find($mediaId);

        if ($media) {
            // Generate the full URL for the image path
            return Storage::url($media->path);
        }

        return null; // Return null if the media item doesn't exist
    }

    public function getMedia($mediaId)
    {
        $path = self::getMediaImagePath($mediaId);

        if ($path) {
            return response()->json(['path' => $path]);
        }

        return response()->json(['error' => 'Media not found'], 404);
    }
}
