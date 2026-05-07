<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Jadwal Penggunaan Ruangan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded">
                <div class="p-6 text-gray-900">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
        <style>
            #calendar {
                max-height: 800px;
                font-family: inherit;
            }
            .fc-event {
                cursor: pointer;
                transition: transform 0.1s ease;
            }
            .fc-event:hover {
                transform: scale(1.02);
            }
            .fc-header-toolbar {
                margin-bottom: 2rem !important;
            }
            .fc-button-primary {
                background-color: #4f46e5 !important; /* bg-indigo-600 */
                border-color: transparent !important;
                font-weight: 600 !important;
                text-transform: uppercase !important; /* uppercase */
                letter-spacing: 0.1em !important; /* tracking-widest */
                font-size: 0.75rem !important; /* text-xs */
                padding: 0.5rem 1rem !important;
                border-radius: 4px !important; /* rounded */
                box-shadow: 0 4px 6px -1px rgb(199 210 254), 0 2px 4px -2px rgb(199 210 254) !important; /* shadow-md shadow-indigo-200 */
                transition: all 0.15s ease-in-out !important;
            }
            .fc-button-group > .fc-button {
                border-radius: 0 !important;
                box-shadow: none !important;
            }
            .fc-button-group > .fc-button:first-child {
                border-top-left-radius: 4px !important;
                border-bottom-left-radius: 4px !important;
            }
            .fc-button-group > .fc-button:last-child {
                border-top-right-radius: 4px !important;
                border-bottom-right-radius: 4px !important;
            }
            .fc-button-primary:hover {
                background-color: #4338ca !important; /* hover:bg-indigo-700 */
                border-color: transparent !important;
                /* transform: translateY(-1px); */
            }
            .fc-button-primary:active {
                background-color: #312e81 !important; /* active:bg-indigo-900 */
                transform: translateY(0);
            }
            .fc-button-active {
                background-color: #312e81 !important; /* bg-indigo-900 */
                border-color: transparent !important;
            }
            .fc-toolbar-title {
                font-size: 1.5rem !important;
                font-weight: 800 !important;
                color: #1e293b !important;
                text-transform: capitalize;
            }
            .fc-col-header-cell-cushion {
                font-size: 0.875rem !important;
                font-weight: 600 !important;
                color: #64748b !important;
                text-decoration: none !important;
                text-transform: uppercase;
                letter-spacing: 0.025em;
            }
            .fc-daygrid-day-number {
                font-size: 0.875rem !important;
                font-weight: 500 !important;
                color: #475569 !important;
                text-decoration: none !important;
            }
            .fc-daygrid-event {
                border-radius: 4px !important;
                padding: 2px 6px !important;
                font-size: 0.75rem !important;
                border: none !important;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            }
        </style>
    @endpush

    @push('scripts')
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    locale: 'id',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    events: '/calendar/events',
                    eventClick: function(info) {
                        Swal.fire({
                            title: `<div class="flex items-center justify-center gap-2 mb-2">
                                        <div class="w-1.5 h-6 bg-indigo-600 rounded-sm"></div>
                                        <span class="text-xl font-extrabold text-gray-800">${info.event.title}</span>
                                    </div>`,
                            html: `
                                <div class="mt-4 text-left space-y-4">
                                    <div class="flex items-start p-4 bg-indigo-50/50 rounded border border-indigo-100">
                                        <div class="flex-shrink-0 mt-1">
                                            <i class="fas fa-door-open text-indigo-600 w-5"></i>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em]">Ruangan</p>
                                            <p class="text-sm font-bold text-gray-800">${info.event.extendedProps.room}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-start p-4 bg-gray-50 rounded border border-gray-100">
                                        <div class="flex-shrink-0 mt-1">
                                            <i class="fas fa-user text-gray-400 w-5"></i>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Peminjam</p>
                                            <p class="text-sm font-bold text-gray-800">${info.event.extendedProps.user}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-start p-4 bg-gray-50 rounded border border-gray-100">
                                        <div class="flex-shrink-0 mt-1">
                                            <i class="fas fa-clock text-gray-400 w-5"></i>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Waktu & Tanggal</p>
                                            <p class="text-sm font-bold text-gray-800">
                                                ${info.event.start.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}<br>
                                                <span class="text-indigo-600 font-black">${info.event.start.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})} - ${info.event.end ? info.event.end.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : ''}</span>
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-start p-4 bg-gray-50 rounded border border-gray-100">
                                        <div class="flex-shrink-0 mt-1">
                                            <i class="fas fa-comment-alt text-gray-400 w-5"></i>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Tujuan</p>
                                            <p class="text-sm text-gray-600 italic">"${info.event.extendedProps.description || '-'}"</p>
                                        </div>
                                    </div>
                                </div>
                            `,
                            showCloseButton: true,
                            showConfirmButton: false,
                            buttonsStyling: false,
                            customClass: {
                                popup: 'rounded shadow-2xl border-0',
                                closeButton: 'focus:outline-none'
                            }
                        });
                    }
                });
                calendar.render();
            });
        </script>
    @endpush
</x-app-layout>
