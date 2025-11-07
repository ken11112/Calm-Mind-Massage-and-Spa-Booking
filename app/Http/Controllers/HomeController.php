<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Gallery;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $services = Service::where('is_active', true)->get();
        $galleries = Gallery::where('is_active', true)
            ->orderBy('sort_order')
            ->take(8)
            ->get();

        return view('home', compact('services', 'galleries'));
    }

    public function gallery()
    {
        // Load albums with their active galleries to support 1 album -> many images
        $albums = \App\Models\Album::with(['galleries' => function ($q) {
            $q->where('is_active', true)->orderBy('sort_order');
        }])->get();

        // Also allow static images placed in public/images/gallery to be shown (kept for backwards compat)
        $staticFiles = [];
        $dir = public_path('images/gallery');
        if (is_dir($dir)) {
            $files = scandir($dir);
            foreach ($files as $file) {
                if (in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif'])) {
                    $staticFiles[] = 'images/gallery/' . $file;
                }
            }
        }

        return view('gallery', compact('albums', 'staticFiles'));
    }
}
