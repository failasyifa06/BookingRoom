<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Ruangan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($rooms as $room)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded border border-gray-200 hover:shadow-md transition duration-200">
                        @if($room->image)
                            <img src="{{ asset('storage/' . $room->image) }}" alt="{{ $room->name }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                                <i class="fas fa-image text-gray-300 text-4xl"></i>
                            </div>
                        @endif
                        <div class="p-6">
                            <div class="flex justify-between items-start">
                                <h3 class="text-lg font-bold text-gray-900">{{ $room->name }}</h3>
                                <span class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                    {{ $room->capacity }} Orang
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 mt-1"><i class="fas fa-map-marker-alt mr-1"></i> {{ $room->location }}</p>
                            
                            <div class="mt-4">
                                <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Fasilitas:</h4>
                                <div class="flex flex-wrap gap-1 mt-1">
                                    @foreach($room->facilities as $facility)
                                        <span class="text-[10px] bg-gray-100 text-gray-600 px-2 py-0.5 rounded">{{ $facility->name }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <p class="mt-4 text-sm text-gray-600 line-clamp-2">
                                {{ $room->description ?? 'Tidak ada deskripsi.' }}
                            </p>

                            <div class="mt-6">
                                <a href="{{ route('staff.bookings.create', ['room_id' => $room->id]) }}" class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition duration-150">
                                    Booking Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white p-12 text-center rounded shadow">
                        <p class="text-gray-500">Tidak ada ruangan tersedia saat ini.</p>
                    </div>
                @endforelse
            </div>
            
            <div class="mt-6">
                {{ $rooms->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
