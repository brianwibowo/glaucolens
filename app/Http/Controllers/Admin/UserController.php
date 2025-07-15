<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{    public function index()
    {
        $users = User::all();
        
        $roles = Role::all();
        
        return view('admin.users.index', compact('users', 'roles'));
    }    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
            'jenis_guru' => 'nullable|string|in:guru pembimbing,guru produktif',
        ]);

        if ($user->id === auth()->id() && $request->role !== 'admin') {
            return redirect()->route('admin-users-index')
                ->with('error', 'Anda tidak dapat mengubah role admin untuk akun Anda sendiri.');
        }

        $user->syncRoles([$request->role]);
        
        if ($request->role === 'guru' && $request->has('jenis_guru')) {
            $user->update(['jenis_guru' => $request->jenis_guru]);
        } else {
            $user->update(['jenis_guru' => null]);
        }

        return redirect()->route('admin-users-index')
            ->with('success', 'Role berhasil diupdate');
    }
}