<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Blade;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = auth()->user()->bookings()->with('room');

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            return DataTables::of($query)
                ->editColumn('date', function ($row) {
                    $sortDate = Carbon::parse($row->date)->format('Y-m-d').' '.Carbon::parse($row->start_time)->format('H:i:s');

                    return '<span class="hidden">'.$sortDate.'</span>'.
                           Carbon::parse($row->date)->format('d M Y').'<br>'.
                           '<span class="text-xs text-gray-500">'.Carbon::parse($row->start_time)->format('H:i').' - '.Carbon::parse($row->end_time)->format('H:i').'</span>';
                })
                ->editColumn('status', function ($row) {
                    $statusClasses = [
                        'pending' => 'bg-yellow-50 text-yellow-800',
                        'approved' => 'bg-green-100 text-green-800',
                        'rejected' => 'bg-red-100 text-red-800',
                        'cancelled' => 'bg-gray-100 text-gray-800',
                        'completed' => 'bg-blue-100 text-blue-800',
                    ];
                    $class = $statusClasses[$row->status] ?? 'bg-gray-100';
                    $html = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full '.$class.'">'.ucfirst($row->status).'</span>';

                    if ($row->status == 'rejected' && $row->approval_notes) {
                        $html .= '<div class="text-[10px] text-red-500 mt-1 max-w-[150px] truncate" title="'.$row->approval_notes.'">Ket: '.$row->approval_notes.'</div>';
                    }

                    return $html;
                })
                ->editColumn('purpose', function ($row) {
                    return wordwrap($row->purpose, 30, '<br>');
                })
                ->addColumn('action', function ($row) {
                    if (in_array($row->status, ['pending', 'approved'])) {
                        $cancelUrl = route('staff.bookings.cancel', $row);

                        return Blade::render('
                            <div class="flex justify-end">
                                <x-danger-button type="button" 
                                    class="px-3 py-1.5 btn-action"
                                    data-action="{{ $cancelUrl }}"
                                    data-confirm-title="Batalkan Booking?"
                                    data-confirm-text="Apakah Anda yakin ingin membatalkan pengajuan booking ini?"
                                    data-confirm-icon="warning"
                                    title="Batalkan">
                                    <i class="fas fa-ban mr-1"></i> Cancel
                                </x-danger-button>
                            </div>', ['cancelUrl' => $cancelUrl]);
                    }

                    return '<span class="text-gray-400 italic text-xs">No Action</span>';
                })
                ->rawColumns(['date', 'status', 'purpose', 'action'])
                ->make(true);
        }

        return view('staff.bookings.index');
    }

    public function create(Request $request)
    {
        $rooms = Room::all();
        $selected_room = $request->room_id;

        return view('staff.bookings.create', compact('rooms', 'selected_room'));
    }

    public function store(StoreBookingRequest $request)
    {
        auth()->user()->bookings()->create($request->validated());

        return redirect()->route('staff.bookings.index')
            ->with('success', 'Booking berhasil diajukan. Menunggu approval admin.');
    }

    public function cancel(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if (! in_array($booking->status, ['pending', 'approved'])) {
            return back()->with('error', 'Booking tidak bisa dibatalkan.');
        }

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Booking berhasil dibatalkan.');
    }
}
