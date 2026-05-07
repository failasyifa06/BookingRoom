<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Selamat Datang, {{ Auth::user()->name }}!</h3>
                    
                    @if(Auth::user()->isAdmin())
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                            <div class="bg-indigo-50 p-6 rounded border border-indigo-100">
                                    <div class="text-indigo-600 font-bold text-xl">{{ \App\Models\Room::count() }}</div>
                                    <div class="text-sm text-gray-600 uppercase tracking-wider font-semibold">Total Ruangan</div>
                                <a href="{{ route('admin.rooms.index') }}" class="text-indigo-600 text-sm mt-4 inline-block font-bold">Kelola →</a>
                            </div>
                            <div class="bg-yellow-50 p-6 rounded border border-yellow-100">
                                    <div class="text-yellow-600 font-bold text-xl">{{ \App\Models\Booking::where('status', 'pending')->count() }}</div>
                                    <div class="text-sm text-gray-600 uppercase tracking-wider font-semibold">Pending Approval</div>
                                <a href="{{ route('admin.bookings.approval') }}" class="text-yellow-600 text-sm mt-4 inline-block font-bold">Lihat Semua →</a>
                            </div>
                            <div class="bg-green-50 p-6 rounded border border-green-100">
                                    <div class="text-green-600 font-bold text-xl">{{ \App\Models\Booking::where('status', 'approved')->count() }}</div>
                                    <div class="text-sm text-gray-600 uppercase tracking-wider font-semibold">Booking Disetujui</div>
                                <a href="{{ route('admin.bookings.all') }}" class="text-green-600 text-sm mt-4 inline-block font-bold">Lihat Riwayat →</a>
                            </div>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div class="bg-blue-50 p-6 rounded border border-blue-100">
                                <h4 class="font-bold text-blue-800">Cari Ruangan</h4>
                                <p class="text-sm text-gray-600 mt-2">Lihat daftar ruangan tersedia dan fasilitas yang ada.</p>
                                <div class="mt-4">
                                    <x-primary-button href="{{ route('staff.rooms.index') }}">
                                        Lihat Ruangan
                                    </x-primary-button>
                                </div>
                            </div>
                            <div class="bg-indigo-50 p-6 rounded border border-indigo-100">
                                <h4 class="font-bold text-indigo-800">Booking Saya</h4>
                                <p class="text-sm text-gray-600 mt-2">Pantau status pengajuan booking Anda.</p>
                                <div class="mt-4">
                                    <x-primary-button href="{{ route('staff.bookings.index') }}">
                                        Cek Status
                                    </x-primary-button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
