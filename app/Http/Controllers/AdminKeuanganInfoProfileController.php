<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;

class AdminKeuanganInfoProfileController extends Controller
{
    public function view(){
        return view('profile');
    }

    public function update(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . auth()->id(),
        ]);

        $user = auth()->user();
        $user->update($request->only('name', 'email'));

        return redirect()->back()->with('status', 'Profile updated successfully.');
    }
    public function edit(){
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();
        $user->password = bcrypt($request->new_password);
        $user->save();

        return back()->with('status', 'Password successfully updated.');
    }





    public function changePasswordForm(){
        return view('profile.change-password');
    }
}
