<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Import Rule

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role' => [
                'required',
                Rule::in(['journalist', 'editor', 'admin']), // Pastikan role-nya valid
            ],
        ]);

        // CEK KEAMANAN: Jangan biarkan admin terakhir menurunkan jabatannya sendiri
        if ($user->id === auth()->id() && 
            $user->role === 'admin' && 
            $request->role !== 'admin') 
        {
            return back()->with('error', 'Anda tidak bisa menurunkan role admin Anda sendiri.');
        }

        $user->update($validatedData);

        return redirect()->route('admin.users.index')->with('success', 'Role pengguna berhasil diperbarui.');
    }
    
}