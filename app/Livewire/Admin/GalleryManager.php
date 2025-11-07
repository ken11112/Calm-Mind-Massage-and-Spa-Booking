<?php

namespace App\Livewire\Admin;

use App\Models\Gallery;
use App\Models\Album;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class GalleryManager extends Component
{
    use WithFileUploads;

    public $galleries;
    public $title = '';
    public $albumId = null; // selected album id
    public $newAlbumName = null; // create new album inline
    public $albums = [];
    public $description = '';
    public $image;
    public $is_active = true;
    public $sort_order = 0;
    public $editingId = null;
    public $isEditing = false;
    public $tempImage = null;
    public $editingGallery = null;

    protected $rules = [
        // title is optional when uploading an image (UI hides title input)
        'title' => 'nullable|min:3',
        'description' => 'nullable',
        // require image on create, allow nullable when editing
        // increase max to 5MB (5120 KB) to accommodate phone photos
        'tempImage' => 'nullable|image|max:5120',
        'albumId' => 'nullable|integer',
        'newAlbumName' => 'nullable|string|min:2',
        'is_active' => 'boolean',
        'sort_order' => 'required|integer|min:0'
    ];

    public function mount()
    {
        $this->startAdd();
    }

    // When a new temp image is selected, clear the title (UI hides the title input)
    public function updatedTempImage()
    {
        $this->title = null;
    }

    public function startAdd()
    {
        $this->reset(['title', 'albumId', 'newAlbumName', 'description', 'image', 'editingId', 'editingGallery', 'tempImage']);
        $this->is_active = true;
        // handle empty table (max may be null)
        $this->sort_order = (Gallery::max('sort_order') ?? 0) + 1;
        $this->isEditing = false;
    }

    public function startEdit($id)
    {
        $this->editingGallery = Gallery::findOrFail($id);
        $this->editingId = $id;
        $this->title = $this->editingGallery->title;
        $this->albumId = $this->editingGallery->album_id;
        $this->description = $this->editingGallery->description;
        $this->is_active = $this->editingGallery->is_active;
        $this->sort_order = $this->editingGallery->sort_order;
        $this->isEditing = true;
    }

    public function save()
    {
        // Validate with dynamic rules:
        // - tempImage required when creating, nullable when editing
        // - title required only if no tempImage is present (user requested title be removable when uploading)
        $titleRule = $this->tempImage ? 'nullable|min:3' : 'required|min:3';

        $this->validate([
            'title' => $titleRule,
            'description' => 'nullable',
            'albumId' => 'nullable|integer',
            'newAlbumName' => 'nullable|string|min:2',
            // allow up to 5MB for uploads
            'tempImage' => $this->editingId ? 'nullable|image|max:5120' : 'required|image|max:5120',
            'is_active' => 'boolean',
            'sort_order' => 'required|integer|min:0'
        ]);

        if ($this->editingId) {
            $gallery = Gallery::findOrFail($this->editingId);
        } else {
            $gallery = new Gallery();
        }

        $gallery->title = $this->title;
        // If a new album name provided, create it; otherwise use selected album id.
        if (!empty(trim((string)$this->newAlbumName))) {
            $album = Album::firstOrCreate(['name' => trim($this->newAlbumName)], ['slug' => \Illuminate\Support\Str::slug($this->newAlbumName)]);
            $gallery->album_id = $album->id;
        } else {
            $gallery->album_id = $this->albumId;
        }
        $gallery->description = $this->description;
        $gallery->is_active = $this->is_active;
        $gallery->sort_order = $this->sort_order;

        if ($this->tempImage) {
            if ($gallery->image_path) {
                Storage::disk('public')->delete($gallery->image_path);
            }
            $gallery->image_path = $this->tempImage->store('gallery', 'public');
        }

        // Ensure we never insert a NULL title into the DB (some uploads hide the title input).
        // Prefer an explicit title, fall back to album name, then a generated placeholder.
        if (empty(trim((string) $gallery->title))) {
            $albumName = null;
            if ($gallery->album_id) {
                $albumName = Album::find($gallery->album_id)->name ?? null;
            }
            $gallery->title = $albumName ?: ('Photo ' . now()->format('YmdHis'));
        }

        $gallery->save();

        session()->flash('success', 'Gallery ' . ($this->isEditing ? 'updated' : 'created') . ' successfully.');
        $this->startAdd();
    }

    public function toggleActive($id)
    {
        $gallery = Gallery::findOrFail($id);
        $gallery->is_active = !$gallery->is_active;
        $gallery->save();
        session()->flash('success', 'Gallery status updated successfully.');
    }

    public function delete($id)
    {
        $gallery = Gallery::findOrFail($id);
        if ($gallery->image_path) {
            Storage::disk('public')->delete($gallery->image_path);
        }
        $gallery->delete();
        session()->flash('success', 'Gallery deleted successfully.');
    }

    public function render()
    {
        // Ensure the public property is populated so Livewire exposes it to the view
        $this->galleries = Gallery::orderBy('sort_order')->get();
        $this->albums = Album::orderBy('name')->get();
        return view('livewire.admin.gallery-manager');
    }
}
