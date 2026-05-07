<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Yajra\DataTables\Facades\DataTables;

class BookingApprovalController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $bookings = Booking::with(['room', 'user'])
                ->where('status', 'pending')
                ->latest();

            return DataTables::of($bookings)
                ->editColumn('user_name', function ($row) {
                    return '<div>'.$row->user->name.'</div><div class="text-xs text-gray-500">'.$row->user->email.'</div>';
                })
                ->editColumn('date', function ($row) {
                    $sortDate = Carbon::parse($row->date)->format('Y-m-d').' '.Carbon::parse($row->start_time)->format('H:i:s');

                    return '<span class="hidden">'.$sortDate.'</span>'.
                           Carbon::parse($row->date)->format('d M Y').'<br>'.
                           '<span class="text-xs text-gray-500">'.Carbon::parse($row->start_time)->format('H:i').' - '.Carbon::parse($row->end_time)->format('H:i').'</span>';
                })
                ->editColumn('purpose', function ($row) {
                    return wordwrap($row->purpose, 30, '<br>');
                })
                ->addColumn('action', function ($row) {
                    $showUrl = route('admin.bookings.show', $row);
                    $approveUrl = route('admin.bookings.approve', $row);

                    return Blade::render('
                        <div class="flex justify-end gap-2">
                            <x-secondary-button href="{{ $showUrl }}" title="Detail" class="px-3 py-1.5">
                                <i class="fas fa-eye mr-1"></i> Detail
                            </x-secondary-button>

                            <x-success-button type="button" 
                                class="px-3 py-1.5 btn-action"
                                data-action="{{ $approveUrl }}"
                                data-confirm-title="Setujui Booking?"
                                data-confirm-text="Apakah Anda yakin ingin menyetujui pengajuan booking dari {{ $name }}?"
                                data-confirm-icon="question">
                                <i class="fas fa-check mr-1"></i> Approve
                            </x-success-button>
                            
                            <x-danger-button type="button" 
                                onclick="openRejectModal({{ $id }})" 
                                class="px-3 py-1.5">
                                <i class="fas fa-times mr-1"></i> Reject
                            </x-danger-button>
                        </div>', ['showUrl' => $showUrl, 'approveUrl' => $approveUrl, 'name' => $row->user->name, 'id' => $row->id]);
                })
                ->rawColumns(['user_name', 'date', 'purpose', 'action'])
                ->make(true);
        }

        return view('admin.bookings.approval');
    }

    public function show(Booking $booking)
    {
        $booking->load(['room', 'user']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function allBookings(Request $request)
    {
        if ($request->ajax()) {
            $query = Booking::with(['room', 'user']);

            if ($request->filled('room_id')) {
                $query->where('room_id', $request->room_id);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('date_from')) {
                $query->where('date', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->where('date', '<=', $request->date_to);
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

                    return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full '.$class.'">'.ucfirst($row->status).'</span>';
                })
                ->addColumn('user_name', function ($row) {
                    return $row->user->name;
                })
                ->addColumn('room_name', function ($row) {
                    return $row->room->name;
                })
                ->addColumn('action', function ($row) {
                    $showUrl = route('admin.bookings.show', $row);
                    return Blade::render('
                        <div class="flex justify-end">
                            <x-secondary-button href="{{ $showUrl }}" title="Detail" class="px-3 py-1.5">
                                <i class="fas fa-eye mr-1"></i> Detail
                            </x-secondary-button>
                        </div>', ['showUrl' => $showUrl]);
                })
                ->rawColumns(['date', 'status', 'action'])
                ->make(true);
        }

        $rooms = Room::all();

        return view('admin.bookings.index', compact('rooms'));
    }

    public function export(Request $request)
    {
        $query = Booking::with(['room', 'user']);

        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        $bookings = $query->latest()->get();

        $filename = 'laporan-booking-'.date('Y-m-d').time().'.csv';
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $columns = ['User', 'Email', 'Ruangan', 'Tanggal', 'Jam Mulai', 'Jam Selesai', 'Tujuan', 'Status', 'Catatan'];

        $callback = function () use ($bookings, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->user->name,
                    $booking->user->email,
                    $booking->room->name,
                    Carbon::parse($booking->date)->format('Y-m-d'),
                    Carbon::parse($booking->start_time)->format('H:i'),
                    Carbon::parse($booking->end_time)->format('H:i'),
                    $booking->purpose,
                    $booking->status,
                    $booking->approval_notes,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function approve(Booking $booking)
    {
        $booking->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
        ]);

        return back()->with('success', 'Booking berhasil disetujui');
    }

    public function reject(Request $request, Booking $booking)
    {
        $request->validate([
            'approval_notes' => 'required|string|max:1000',
        ]);

        $booking->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approval_notes' => $request->approval_notes,
        ]);

        return back()->with('success', 'Booking berhasil ditolak');
    }
}
