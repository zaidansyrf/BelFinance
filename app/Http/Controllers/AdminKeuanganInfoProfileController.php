<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminKeuanganInfoProfileController extends Controller
{
    public function view()
    {
        return view('profile');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.auth()->id(),
        ]);

        $user = auth()->user();
        $user->update($request->only('name', 'email'));

        return redirect()->back()->with('status', 'Profile updated successfully.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|string|min:8|confirmed|different:current_password',
        ], [
            'current_password.current_password' => 'Password lama tidak sesuai.',
            'new_password.different' => 'Password baru harus berbeda dari password lama.',
        ]);

        $user = auth()->user();
        $user->password = bcrypt($request->new_password);
        $user->save();

        return back()->with('status', 'Password successfully updated.');
    }
}
