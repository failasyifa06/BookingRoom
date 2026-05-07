<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Ruangan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.rooms.update', $room) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="mb-4">
                                <x-input-label for="name" :value="__('Nama Ruangan')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $room->name)" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="location" :value="__('Lokasi')" />
                                <x-text-input id="location" class="block mt-1 w-full" type="text" name="location" :value="old('location', $room->location)" required />
                                <x-input-error :messages="$errors->get('location')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="capacity" :value="__('Kapasitas (Orang)')" />
                                <x-text-input id="capacity" class="block mt-1 w-full" type="number" name="capacity" :value="old('capacity', $room->capacity)" required />
                                <x-input-error :messages="$errors->get('capacity')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="image" :value="__('Foto Ruangan')" />
                                @if($room->image)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $room->image) }}" alt="{{ $room->name }}" class="h-20 w-32 object-cover rounded shadow">
                                    </div>
                                @endif
                                <input type="file" id="image" name="image" class="block mt-1 w-full border border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500" accept="image/*">
                                <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengubah foto.</p>
                                <x-input-error :messages="$errors->get('image')" class="mt-2" />
                            </div>

                            <div class="mb-4 md:col-span-2">
                                <x-input-label for="description" :value="__('Deskripsi')" />
                                <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded shadow-sm" rows="3">{{ old('description', $room->description) }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mb-4">
                            <x-input-label :value="__('Fasilitas')" />
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-2">
                                @foreach($facilities as $facility)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="facilities[]" value="{{ $facility->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ in_array($facility->id, old('facilities', $room->facilities->pluck('id')->toArray())) ? 'checked' : '' }}>
                                        <span class="ms-2 text-sm text-gray-600">{{ $facility->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('facilities')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-secondary-button type="button" onclick="history.back()" class="mr-3">
                                {{ __('Batal') }}
                            </x-secondary-button>
                            <x-primary-button>
                                {{ __('Perbarui') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
