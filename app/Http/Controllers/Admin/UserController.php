<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Blade;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::latest();
            return DataTables::of($query)
                ->editColumn('role', function($row) {
                    $class = $row->role == 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800';
                    return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full '.$class.'">'.ucfirst($row->role).'</span>';
                })
                ->addColumn('action', function($row) {
                    $editUrl = route('admin.users.edit', $row);
                    $deleteUrl = route('admin.users.destroy', $row);
                    $isSelf = auth()->id() == $row->id;
                    
                    return Blade::render('
                        <div class="flex justify-end gap-2">
                            <x-primary-button href="{{ $editUrl }}" title="Edit" class="px-3 py-1.5">
                                <i class="fas fa-edit"></i>
                            </x-primary-button>
                            
                            @if(!$isSelf)
                                <x-danger-button type="button" class="px-3 py-1.5 btn-action" 
                                    data-action="{{ $deleteUrl }}" data-method="DELETE" data-confirm-title="Hapus User?" data-confirm-text="Apakah Anda yakin ingin menghapus user {{ $name }}?" data-confirm-icon="warning" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </x-danger-button>
                            @else
                                <x-danger-button type="button" class="px-3 py-1.5 opacity-50 cursor-not-allowed" title="Anda tidak bisa menghapus diri sendiri" disabled>
                                    <i class="fas fa-trash"></i>
                                </x-danger-button>
                            @endif
                        </div>', ['editUrl' => $editUrl, 'deleteUrl' => $deleteUrl, 'name' => $row->name, 'isSelf' => $isSelf]);
                })
                ->rawColumns(['role', 'action'])
                ->make(true);
        }
        return view('admin.users.index');
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,staff'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role' => ['required', 'in:admin,staff'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus diri sendiri');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus');
    }
}
