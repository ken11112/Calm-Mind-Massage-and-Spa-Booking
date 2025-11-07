<?php

namespace App\Livewire\Admin;

use App\Models\Service;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ServiceManager extends Component
{
    use WithFileUploads;

    public $services;
    public $name;
    public $description;
    public $price;
    public $duration;
    public $image;
    public $is_active = true;
    public $isEditing = false;
    public $editingService = null;
    public $editingId = null;
    public $tempImage = null;

    public function mount()
    {
        $this->refreshServices();
    }

    public function refreshServices()
    {
        $this->services = Service::orderBy('name')->get();
    }

    public function startAdd()
    {
        // Use tempImage consistently for uploads
        $this->reset(['name', 'description', 'price', 'duration', 'image', 'editingId', 'tempImage']);
        $this->is_active = true;
        $this->isEditing = false;
    }

    public function startEdit($id)
    {
        // Accept an id to be consistent with other managers and avoid Livewire method signature issues
        $service = Service::findOrFail($id);
        $this->editingId = $service->id;
        $this->name = $service->name;
        $this->description = $service->description;
        $this->price = $service->price;
        $this->duration = $service->duration;
        $this->is_active = $service->is_active;
        $this->isEditing = true;
    }

    public function save()
    {
        // Validate the tempImage field (used for uploads) rather than 'image'
        $this->validate([
            'name' => 'required|min:3',
            'description' => 'nullable',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'tempImage' => $this->editingId ? 'nullable|image|max:2048' : 'required|image|max:2048',
            'is_active' => 'boolean'
        ]);

        if ($this->editingId) {
            $service = Service::find($this->editingId);
        } else {
            $service = new Service();
        }

        $service->name = $this->name;
        $service->description = $this->description;
        $service->price = $this->price;
        $service->duration = $this->duration;
        $service->is_active = $this->is_active;

        if ($this->tempImage) {
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
            $service->image = $this->tempImage->store('services', 'public');
        }

        $service->save();

        $this->reset(['name', 'description', 'price', 'duration', 'image', 'editingId', 'tempImage']);
        $this->refreshServices();

        session()->flash('success', 'Service ' . ($this->isEditing ? 'updated' : 'added') . ' successfully.');
        $this->isEditing = false;
    }

    public function toggleActive($id)
    {
        $service = Service::findOrFail($id);
        $service->is_active = !$service->is_active;
        $service->save();
        $this->refreshServices();
    }

    public function delete($id)
    {
        $service = Service::findOrFail($id);

        if ($service->bookings()->count() > 0) {
            session()->flash('error', 'Cannot delete service with existing bookings.');
            return;
        }

        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }

        $service->delete();
        $this->refreshServices();
        session()->flash('success', 'Service deleted successfully.');
    }

    public function render()
    {
        return view('livewire.admin.service-manager');
    }
}
