<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Room;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Blade;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Room::with('facilities')->latest();
            return DataTables::of($query)
                ->addColumn('facilities_list', function($row) {
                    return $row->facilities->map(function($f) {
                        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-1">'.$f->name.'</span>';
                    })->implode('');
                })
                ->addColumn('action', function($row) {
                    $showUrl = route('admin.rooms.show', $row);
                    $editUrl = route('admin.rooms.edit', $row);
                    $deleteUrl = route('admin.rooms.destroy', $row);
                    
                    return Blade::render('
                        <div class="flex justify-end gap-2">
                            <x-secondary-button href="{{ $showUrl }}" title="Detail" class="px-3 py-1.5">
                                <i class="fas fa-eye"></i>
                            </x-secondary-button>

                            <x-primary-button href="{{ $editUrl }}" title="Edit" class="px-3 py-1.5">
                                <i class="fas fa-edit"></i>
                            </x-primary-button>
                            
                            <x-danger-button type="button" class="px-3 py-1.5 btn-action" 
                                data-action="{{ $deleteUrl }}" data-method="DELETE" data-confirm-title="Hapus Ruangan?" data-confirm-text="Apakah Anda yakin ingin menghapus ruangan {{ $name }}?" data-confirm-icon="warning" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </x-danger-button>
                        </div>', ['showUrl' => $showUrl, 'editUrl' => $editUrl, 'deleteUrl' => $deleteUrl, 'name' => $row->name]);
                })
                ->rawColumns(['facilities_list', 'action'])
                ->make(true);
        }
        return view('admin.rooms.index');
    }

    public function create()
    {
        $facilities = Facility::all();

        return view('admin.rooms.create', compact('facilities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:facilities,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'capacity', 'location', 'description']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('rooms', 'public');
        }

        $room = Room::create($data);

        if ($request->has('facilities')) {
            $room->facilities()->sync($request->facilities);
        }

        return redirect()->route('admin.rooms.index')->with('success', 'Ruangan berhasil ditambahkan');
    }

    public function edit(Room $room)
    {
        $facilities = Facility::all();

        return view('admin.rooms.edit', compact('room', 'facilities'));
    }

    public function update(Request $request, Room $room)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:facilities,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'capacity', 'location', 'description']);

        if ($request->hasFile('image')) {
            if ($room->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($room->image);
            }
            $data['image'] = $request->file('image')->store('rooms', 'public');
        }

        $room->update($data);

        $room->facilities()->sync($request->facilities ?? []);

        return redirect()->route('admin.rooms.index')->with('success', 'Ruangan berhasil diperbarui');
    }

    public function show(Room $room)
    {
        $room->load('facilities');
        return view('admin.rooms.show', compact('room'));
    }

    public function destroy(Room $room)
    {
        if ($room->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($room->image);
        }
        $room->delete();

        return redirect()->route('admin.rooms.index')->with('success', 'Ruangan berhasil dihapus');
    }
}
