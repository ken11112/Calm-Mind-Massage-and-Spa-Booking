<div class="space-y-6">
    <!-- Add/Edit Gallery Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">{{ $isEditing ? 'Edit Gallery Image' : 'Add New Gallery Image' }}</h3>
        <form wire:submit.prevent="save">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title (hidden when an image is selected) -->
                @if(!$tempImage)
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                    <input type="text" id="title" wire:model="title" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                    @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="albumId" class="block text-sm font-medium text-gray-700 mb-2">Album</label>
                    <select id="albumId" wire:model="albumId" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                        <option value="">-- Select album --</option>
                        @foreach($albums as $a)
                            <option value="{{ $a->id }}">{{ $a->name }}</option>
                        @endforeach
                    </select>
                    <div class="text-xs text-gray-500 mt-1">Or create a new album below</div>
                    @error('albumId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="newAlbumName" class="block text-sm font-medium text-gray-700 mb-2">New album (optional)</label>
                    <input type="text" id="newAlbumName" wire:model="newAlbumName" placeholder="New album name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                    @error('newAlbumName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                @else
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                    <div class="text-sm text-gray-500">Title input hidden while uploading an image (not required).</div>
                </div>
                @endif

                <!-- Sort Order -->
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                    <input type="number" id="sort_order" wire:model="sort_order" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                    @error('sort_order') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="is_active" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="is_active" wire:model="is_active" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    @error('is_active') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Image -->
                <div class="md:col-span-2">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Image</label>
                    <input type="file" id="image" wire:model="tempImage" class="w-full">
                    @error('tempImage') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    @if ($tempImage)
                        <div class="mt-2">
                            <img src="{{ $tempImage->temporaryUrl() }}" class="h-32 w-32 object-cover rounded">
                        </div>
                    @elseif ($isEditing && $editingGallery && $editingGallery->image_path)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $editingGallery->image_path) }}" class="h-32 w-32 object-cover rounded">
                        </div>
                    @endif
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="description" wire:model="description" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"></textarea>
                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                @if($isEditing)
                    <button type="button" wire:click="startAdd" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-200">
                        Cancel
                    </button>
                @endif
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    {{ $isEditing ? 'Update Image' : 'Add Image' }}
                </button>
            </div>
        </form>
    </div>

    <!-- Gallery Grid -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold">Gallery Images</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($galleries as $gallery)
                    <div class="relative group">
                        <img src="{{ asset('storage/' . $gallery->image_path) }}" alt="{{ $gallery->title }}" class="w-full aspect-square object-cover rounded-lg">
                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg flex items-center justify-center space-x-4">
                            <button wire:click="startEdit({{ $gallery->id }})" class="text-white hover:text-blue-200">
                                <i class="fas fa-edit text-xl"></i>
                            </button>
                            <button wire:click="toggleActive({{ $gallery->id }})" class="text-white hover:text-{{ $gallery->is_active ? 'red' : 'green' }}-200">
                                <i class="fas fa-{{ $gallery->is_active ? 'eye-slash' : 'eye' }} text-xl"></i>
                            </button>
                            <button wire:click="delete({{ $gallery->id }})" class="text-white hover:text-red-200" onclick="return confirm('Are you sure you want to delete this image?')">
                                <i class="fas fa-trash text-xl"></i>
                            </button>
                        </div>
                        <div class="mt-2">
                            <h4 class="font-medium">{{ $gallery->title }}</h4>
                            <p class="text-sm text-gray-500">Order: {{ $gallery->sort_order }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
