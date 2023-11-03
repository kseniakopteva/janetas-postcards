<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // dd($request);

        if ($request->image && $request->image->isValid() && fnmatch(auth()->user()->image, 'default.png')) {
            File::delete(public_path() . '\\' . auth()->user()->image);
        }

        $request->user()->fill($request->validated());

        $user =  User::find(Auth::id());
        if (!$user) {
            return response()->json(['message' => 'No User Found'], 404);
        }

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }
        if ($request->image && $request->image->isValid()) {

            $imageName = 'profile_picture' . '.' . $request->image->extension();
            $filePath = storage_path('app\public');

            $request->user()->image = $imageName;

            $request->image->move($filePath, $imageName);

            \Intervention\Image\Facades\Image::make($filePath . '\\' . $imageName)->fit(500)->save($filePath . '\\' . $imageName);
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated')->with('success', 'You have updated your profile!');
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function remove_image()
    {
        $user =  User::find(Auth::id());
        if (!fnmatch('default.png', $user->image))
            File::delete(public_path() . '\\' . $user->image);

        $user->image = 'default.png';
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
}
