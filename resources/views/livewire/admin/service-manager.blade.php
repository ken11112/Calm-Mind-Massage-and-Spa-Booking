<div class="space-y-6">
    <!-- Add/Edit Service Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">{{ $isEditing ? 'Edit Service' : 'Add New Service' }}</h3>
        <form wire:submit.prevent="save">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Service Name</label>
                    <input type="text" id="name" wire:model="name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Price -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price ($)</label>
                    <input type="number" step="0.01" id="price" wire:model="price" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                    @error('price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Duration -->
                <div>
                    <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">Duration (minutes)</label>
                    <input type="number" id="duration" wire:model="duration" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                    @error('duration') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="description" wire:model="description" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"></textarea>
                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Image -->
                <div class="md:col-span-2">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Service Image</label>
                    <input type="file" id="image" wire:model="tempImage" class="w-full">
                    @error('tempImage') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    @if ($tempImage)
                        <div class="mt-2">
                            <img src="{{ $tempImage->temporaryUrl() }}" class="h-32 w-32 object-cover rounded">
                        </div>
                    @elseif ($isEditing && $editingService && $editingService->image)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $editingService->image) }}" class="h-32 w-32 object-cover rounded">
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                @if($isEditing)
                    <button type="button" wire:click="startAdd" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-200">
                        Cancel
                    </button>
                @endif
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    {{ $isEditing ? 'Update Service' : 'Add Service' }}
                </button>
            </div>
        </form>
    </div>

    <!-- Services List -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($services as $service)
                <div class="bg-white rounded-lg border p-6 relative group">
                    @if($service->image)
                        <img src="{{ asset('storage/' . $service->image) }}" 
                             alt="{{ $service->name }}" 
                             class="w-full h-48 object-cover rounded-lg mb-4">
                    @endif
                    <h3 class="text-lg font-semibold">{{ $service->name }}</h3>
                    <p class="text-gray-600 mt-2">{{ $service->description }}</p>
                    <div class="mt-4 flex justify-between items-center">
                        <span class="text-lg font-bold text-blue-600">${{ number_format($service->price, 2) }}</span>
                        <span class="text-sm text-gray-500">{{ $service->duration }} min</span>
                    </div>
                    
                    <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg flex items-center justify-center space-x-4">
                        <button wire:click="startEdit({{ $service->id }})" class="text-white hover:text-blue-200">
                            <i class="fas fa-edit text-xl"></i>
                        </button>
                        <button wire:click="toggleActive({{ $service->id }})" class="text-white hover:text-{{ $service->is_active ? 'red' : 'green' }}-200">
                            <i class="fas fa-{{ $service->is_active ? 'eye-slash' : 'eye' }} text-xl"></i>
                        </button>
                        <button wire:click="delete({{ $service->id }})" 
                                class="text-white hover:text-red-200"
                                onclick="return confirm('Are you sure you want to delete this service?')">
                            <i class="fas fa-trash text-xl"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
