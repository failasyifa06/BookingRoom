<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Ruangan: ') . $room->name }}
            </h2>
            <div class="flex gap-2">
                <x-secondary-button href="{{ route('admin.rooms.index') }}">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </x-secondary-button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-0">
                    <!-- Image Section -->
                    <div class="relative h-96 md:h-auto overflow-hidden bg-gray-100">
                        @if($room->image)
                            <img src="{{ asset('storage/' . $room->image) }}" alt="{{ $room->name }}" class="absolute inset-0 w-full h-full object-cover transform hover:scale-105 transition duration-700">
                        @else
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-400">
                                <i class="fas fa-image text-6xl mb-4"></i>
                                <span>Tidak ada foto</span>
                            </div>
                        @endif
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-xs font-bold text-gray-700 uppercase tracking-widest rounded shadow-sm">
                                <i class="fas fa-map-marker-alt mr-1 text-indigo-500"></i> {{ $room->location }}
                            </span>
                        </div>
                    </div>

                    <!-- Details Section -->
                    <div class="p-8 md:p-12 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <span class="px-4 py-1.5 bg-indigo-50 text-indigo-700 text-sm font-bold rounded-full border border-indigo-100">
                                    <i class="fas fa-users mr-2"></i> Kapasitas: {{ $room->capacity }} Orang
                                </span>
                            </div>

                            <h1 class="text-4xl font-extrabold text-gray-900 mb-4 tracking-tight">
                                {{ $room->name }}
                            </h1>

                            <p class="text-gray-600 leading-relaxed mb-8 text-lg">
                                {{ $room->description ?: 'Tidak ada deskripsi untuk ruangan ini.' }}
                            </p>

                            <div class="mb-8">
                                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4">Fasilitas Ruangan</h3>
                                <div class="flex flex-wrap gap-2">
                                    @forelse($room->facilities as $facility)
                                        <span class="inline-flex items-center px-3 py-1.5 rounded bg-gray-50 border border-gray-200 text-sm font-medium text-gray-700 hover:border-indigo-300 hover:bg-indigo-50 transition duration-150">
                                            <i class="fas fa-check-circle mr-2 text-green-500"></i> {{ $facility->name }}
                                        </span>
                                    @empty
                                        <span class="text-sm text-gray-500 italic">Belum ada fasilitas yang ditambahkan.</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-3 pt-8 border-t border-gray-100">
                            <x-primary-button href="{{ route('admin.rooms.edit', $room) }}" class="px-6 py-2">
                                <i class="fas fa-edit mr-2"></i> Edit Ruangan
                            </x-primary-button>
                            
                            <x-danger-button type="button" class="px-6 py-2 btn-action" 
                                data-action="{{ route('admin.rooms.destroy', $room) }}" 
                                data-method="DELETE" 
                                data-confirm-title="Hapus Ruangan?" 
                                data-confirm-text="Apakah Anda yakin ingin menghapus ruangan {{ $room->name }}? Tindakan ini tidak dapat dibatalkan." 
                                data-confirm-icon="warning">
                                <i class="fas fa-trash mr-2"></i> Hapus Ruangan
                            </x-danger-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
