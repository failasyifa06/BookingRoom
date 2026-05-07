<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Facility;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Blade;

class FacilityController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Facility::latest();
            return DataTables::of($query)
                ->addColumn('action', function($row) {
                    $editUrl = route('admin.facilities.edit', $row);
                    $deleteUrl = route('admin.facilities.destroy', $row);
                    
                    return Blade::render('
                        <div class="flex justify-end gap-2">
                            <x-primary-button href="{{ $editUrl }}" title="Edit" class="px-3 py-1.5">
                                <i class="fas fa-edit"></i>
                            </x-primary-button>
                            
                            <x-danger-button type="button" class="px-3 py-1.5 btn-action" 
                                data-action="{{ $deleteUrl }}" data-method="DELETE" data-confirm-title="Hapus Fasilitas?" data-confirm-text="Apakah Anda yakin ingin menghapus fasilitas {{ $name }}?" data-confirm-icon="warning" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </x-danger-button>
                        </div>', ['editUrl' => $editUrl, 'deleteUrl' => $deleteUrl, 'name' => $row->name]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.facilities.index');
    }

    public function create()
    {
        return view('admin.facilities.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:facilities']);
        Facility::create($request->all());
        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas berhasil ditambahkan');
    }

    public function edit(Facility $facility)
    {
        return view('admin.facilities.edit', compact('facility'));
    }

    public function update(Request $request, Facility $facility)
    {
        $request->validate(['name' => 'required|string|max:255|unique:facilities,name,' . $facility->id]);
        $facility->update($request->all());
        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas berhasil diperbarui');
    }

    public function destroy(Facility $facility)
    {
        $facility->delete();
        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas berhasil dihapus');
    }
}
