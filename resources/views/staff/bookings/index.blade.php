<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Booking Saya') }}
            </h2>
            <x-primary-button href="{{ route('staff.bookings.create') }}">
                <i class="fas fa-plus mr-2"></i> Booking Baru
            </x-primary-button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}
                </div>
            @endif

            <div class="mb-4 flex gap-2">
                <a href="{{ route('staff.bookings.index') }}" class="px-3 py-2 text-sm font-medium rounded {{ !request('status') ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 border' }}">Semua</a>
                <a href="{{ route('staff.bookings.index', ['status' => 'pending']) }}" class="px-3 py-2 text-sm font-medium rounded {{ request('status') == 'pending' ? 'bg-yellow-500 text-white' : 'bg-white text-gray-700 border' }}">Pending</a>
                <a href="{{ route('staff.bookings.index', ['status' => 'approved']) }}" class="px-3 py-2 text-sm font-medium rounded {{ request('status') == 'approved' ? 'bg-green-600 text-white' : 'bg-white text-gray-700 border' }}">Approved</a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table id="staff-bookings-datatable" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruangan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tujuan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#staff-bookings-datatable').DataTable({
                processing: true,
                ajax: {
                    url: "{{ route('staff.bookings.index') }}",
                    data: function (d) {
                        d.status = new URLSearchParams(window.location.search).get('status');
                    }
                },
                order: [[1, 'asc']],
                columns: [
                    { data: 'room.name', name: 'room.name' },
                    { data: 'date', name: 'date', },
                    { data: 'purpose', name: 'purpose' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
