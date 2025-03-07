<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\ContentMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ContentController extends Controller
{
    public function uploadImage(string $entityType, Request $request)
    {
        $model = ContentMedia::getByEntity($entityType);
        $image = $model
            ->addMediaFromRequest('image')
            ->usingFileName(Str::random(24))
            ->withResponsiveImages()
            ->toMediaCollection('images');

        \Log::error($image->getUrl('webp'));
        return response()->json([
            'default' => $image->getUrl('webp')
        ]);
    }
}
