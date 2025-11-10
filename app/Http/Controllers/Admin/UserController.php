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
            'role' => ['required', Rule::in(['journalist', 'editor', 'admin'])],
            // Validasi field baru
            'bio' => 'nullable|string|max:1000',
            'twitter_handle' => 'nullable|string|max:50',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5024', // maks 1MB
        ]);

        unset($validatedData['avatar']);

        // CEK KEAMANAN: Jangan biarkan admin terakhir menurunkan jabatannya sendiri
        if (
            $user->id === auth()->id() &&
            $user->role === 'admin' &&
            $request->role !== 'admin'
        ) {
            return back()->with('error', 'Anda tidak bisa menurunkan role admin Anda sendiri.');
        }
        if ($request->hasFile('avatar')) {
            $user
                ->addMediaFromRequest('avatar')
                ->toMediaCollection('avatar');
        }

        $user->update($validatedData);

        return redirect()->route('admin.users.index')->with('success', 'Role pengguna berhasil diperbarui.');
    }

}
