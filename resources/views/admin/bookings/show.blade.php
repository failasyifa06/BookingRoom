<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Pengajuan Booking') }}
            </h2>
            <x-secondary-button href="{{ route('admin.bookings.approval') }}">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </x-secondary-button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-lg border border-gray-100">
                <!-- Status Header -->
                <div class="px-8 py-6 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest block mb-1">Status Saat Ini</span>
                        @php
                            $statusClasses = [
                                'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                'approved' => 'bg-green-100 text-green-800 border-green-200',
                                'rejected' => 'bg-red-100 text-red-800 border-red-200',
                                'cancelled' => 'bg-gray-100 text-gray-800 border-gray-200',
                                'completed' => 'bg-blue-100 text-blue-800 border-blue-200',
                            ];
                            $class = $statusClasses[$booking->status] ?? 'bg-gray-100 border-gray-200';
                        @endphp
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-full border {{ $class }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest block mb-1">ID Booking</span>
                        <span class="text-lg font-mono font-bold text-gray-900">#{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </div>
                </div>

                <div class="p-8 md:p-10">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-12">
                        <!-- Left Column: User Info -->
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-sm font-bold text-indigo-600 uppercase tracking-widest mb-4">Informasi Peminjam</h3>
                                <div class="flex items-center gap-4">
                                    <div class="h-14 w-14 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xl">
                                        {{ substr($booking->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-bold text-gray-900">{{ $booking->user->name }}</h4>
                                        <p class="text-sm text-gray-500">{{ $booking->user->email }}</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-sm font-bold text-indigo-600 uppercase tracking-widest mb-4">Tujuan Peminjaman</h3>
                                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 italic text-gray-700">
                                    "{{ $booking->purpose }}"
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Room & Time Info -->
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-sm font-bold text-indigo-600 uppercase tracking-widest mb-4">Ruangan & Waktu</h3>
                                <div class="space-y-4">
                                    <div class="flex items-start gap-3">
                                        <div class="mt-1 w-8 h-8 rounded bg-blue-50 flex items-center justify-center text-blue-500">
                                            <i class="fas fa-door-open"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Ruangan</p>
                                            <p class="text-gray-900 font-semibold">{{ $booking->room->name }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-3">
                                        <div class="mt-1 w-8 h-8 rounded bg-blue-50 flex items-center justify-center text-blue-500">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tanggal</p>
                                            <p class="text-gray-900 font-semibold">{{ \Carbon\Carbon::parse($booking->date)->format('d F Y') }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-3">
                                        <div class="mt-1 w-8 h-8 rounded bg-blue-50 flex items-center justify-center text-blue-500">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Waktu</p>
                                            <p class="text-gray-900 font-semibold">
                                                {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - 
                                                {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($booking->approval_notes)
                    <div class="mb-12">
                        <h3 class="text-sm font-bold text-red-600 uppercase tracking-widest mb-4">Catatan Penolakan / Approval</h3>
                        <div class="bg-red-50 rounded-xl p-5 border border-red-100 text-red-700">
                            {{ $booking->approval_notes }}
                        </div>
                    </div>
                    @endif

                    <!-- Actions -->
                    @if($booking->status === 'pending')
                    <div class="pt-8 border-t border-gray-100">
                        <div class="flex flex-wrap gap-3">
                            @php
                                $approveUrl = route('admin.bookings.approve', $booking);
                            @endphp
                            <x-success-button type="button" 
                                class="px-6 py-2 text-sm btn-action"
                                data-action="{{ $approveUrl }}"
                                data-confirm-title="Setujui Booking?"
                                data-confirm-text="Apakah Anda yakin ingin menyetujui pengajuan booking dari {{ $booking->user->name }}?"
                                data-confirm-icon="question">
                                <i class="fas fa-check mr-2"></i> Approve Pengajuan
                            </x-success-button>
                            
                            <x-danger-button type="button" 
                                onclick="openRejectModal({{ $booking->id }})" 
                                class="px-6 py-2 text-sm">
                                <i class="fas fa-times mr-2"></i> Tolak Pengajuan
                            </x-danger-button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeRejectModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-middle bg-white rounded text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="rejectForm" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Tolak Pengajuan Booking</h3>
                        <div class="mt-4">
                            <x-input-label for="approval_notes" :value="__('Alasan Penolakan')" />
                            <textarea id="approval_notes" name="approval_notes" required class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded shadow-sm" rows="3" placeholder="Berikan alasan mengapa pengajuan ini ditolak..."></textarea>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex justify-end items-center gap-3">
                        <x-secondary-button type="button" onclick="closeRejectModal()">
                            Batal
                        </x-secondary-button>
                        <x-danger-button type="submit">
                            Konfirmasi Tolak
                        </x-danger-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openRejectModal(id) {
            const form = document.getElementById('rejectForm');
            form.action = `/admin/bookings/${id}/reject`;
            document.getElementById('rejectModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
    @endpush
</x-app-layout>
