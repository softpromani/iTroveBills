<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $valid = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'string', 'max:255', Rule::unique('users')->ignore(Auth::user())],
            'mobile' => 'required|numeric|digits:10', // Example mobile validation rules
            'appname' => 'required|string|max:255', // Example appname validation rules
            'avatar' => 'nullable|image|mimes:jpg,png,jpeg',
        ]);

        if ($request->hasFile('avatar')) {
            if (auth()->user()->app_logo) {
                $oldImagePath = public_path(auth()->user()->app_logo);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }
            $file = $request->avatar;
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $fileName);
            $upload_file ='uploads/'.$fileName;
            User::find(Auth::id())->update([
                'app_logo' => $upload_file,
            ]);
        }
        if ($valid) {
            User::find(Auth::id())->update([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'app_name' => $request->appname,
                'email' => $request->email,
            ]);
            return Redirect::route('profile.edit')->with('success', 'Profile updated successfully!');
        }
        return Redirect::route('profile.edit')->withErrors($valid);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
