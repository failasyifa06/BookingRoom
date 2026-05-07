<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Semua Riwayat Booking') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded">
                <div class="p-6 text-gray-900">
                    <form id="filter-form" class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div class="space-y-1">
                                <x-input-label for="room_id" :value="__('Ruangan')" class="text-xs font-bold text-gray-500 uppercase tracking-wider" />
                                <select id="room_id" name="room_id" class="block w-full border-gray-200 focus:border-indigo-500 rounded shadow-sm text-sm transition duration-150">
                                    <option value="">Semua Ruangan</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}">{{ $room->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1">
                                <x-input-label for="status" :value="__('Status')" class="text-xs font-bold text-gray-500 uppercase tracking-wider" />
                                <select id="status" name="status" class="block w-full border-gray-200 focus:border-indigo-500 rounded shadow-sm text-sm transition duration-150">
                                    <option value="">Semua Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                    <option value="cancelled">Cancelled</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>
                            <div class="space-y-1">
                                <x-input-label for="date_from" :value="__('Mulai Tanggal')" class="text-xs font-bold text-gray-500 uppercase tracking-wider" />
                                <x-text-input id="date_from" name="date_from" type="date" class="block w-full text-sm border-gray-200 focus:border-indigo-500 rounded shadow-sm transition duration-150" />
                            </div>
                            <div class="space-y-1">
                                <x-input-label for="date_to" :value="__('Sampai Tanggal')" class="text-xs font-bold text-gray-500 uppercase tracking-wider" />
                                <x-text-input id="date_to" name="date_to" type="date" class="block w-full text-sm border-gray-200 focus:border-indigo-500 rounded shadow-sm transition duration-150" />
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-between border-t border-gray-100 pt-6">
                            <div class="flex gap-3">
                                <x-primary-button>
                                    <i class="fas fa-filter mr-2"></i> Terapkan Filter
                                </x-primary-button>
                                <x-secondary-button href="{{ route('admin.bookings.all') }}">
                                    <i class="fas fa-sync-alt mr-2"></i> Reset
                                </x-secondary-button>
                            </div>
                            <a id="export-btn" href="{{ route('admin.bookings.export') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded text-xs font-semibold text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 transition ease-in-out duration-150">
                                <i class="fas fa-file-export mr-2"></i> Export CSV
                            </a>
                        </div>
                    </form>
                    <div class="overflow-x-auto">
                        <table id="report-datatable" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peminjam</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruangan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
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
            // Initialize DataTable
            const table = $('#report-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.bookings.all') }}",
                    data: function (d) {
                        d.room_id = $('#room_id').val();
                        d.status = $('#status').val();
                        d.date_from = $('#date_from').val();
                        d.date_to = $('#date_to').val();
                    }
                },
                order: [[2, 'asc']],
                columns: [
                    { data: 'user_name', name: 'user.name' },
                    { data: 'room_name', name: 'room.name' },
                    { data: 'date', name: 'date' },
                    { data: 'status', name: 'status' },
                    { data: 'approval_notes', name: 'approval_notes', defaultContent: '-' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
                }
            });

            const exportBase = "{{ route('admin.bookings.export') }}";

            function updateExportUrl() {
                const params = new URLSearchParams();
                const roomId   = $('#room_id').val();
                const status   = $('#status').val();
                const dateFrom = $('#date_from').val();
                const dateTo   = $('#date_to').val();

                if (roomId)   params.set('room_id',   roomId);
                if (status)   params.set('status',    status);
                if (dateFrom) params.set('date_from', dateFrom);
                if (dateTo)   params.set('date_to',   dateTo);

                const qs = params.toString();
                $('#export-btn').attr('href', exportBase + (qs ? '?' + qs : ''));
            }

            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                updateExportUrl();
                table.draw();
            });

            updateExportUrl();
        });
    </script>
    @endpush
</x-app-layout>
