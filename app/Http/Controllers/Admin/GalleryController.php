<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * Display a listing of the gallery images (admin).
     */
    public function index()
    {
        // Pass required variables to view to prevent undefined errors
        // Will be replaced by Livewire component data
        return view('admin.gallery.index', [
            'isEditing' => false,
            'galleries' => collect(),  // empty collection until Livewire component is ready
        ]);
    }
}
