<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Intervention\Image\Laravel\Facades\Image;

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
        //dd($request->file('profile_picture')->store('profile_picture'));
        //dd($request->file('profile_picture')->store('docs','documents'));
        //dd($request->file('profile_picture')->store('profile_picture','public'));
        //dd($request->file('profile_picture')->storeAs('profile_picture','profile_picture.jpg'));

//        if (in_array($request->file('profile_picture')->extension(), ['jpg','png'])){
//            dd($request->file('profile_picture')->store('profile_picture','public'));
//        }else if (in_array($request->file('profile_picture')->extension(), ['pdf','doc','docx'])){
//            dd($request->file('profile_picture')->store('docs','documents'));
//        }else{
//            return 'error';
//        }


        //dd('ok');
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        /*custom function for image uploaded*/
        $this->handleUploadedProfilePicture($request);

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /*custom function for image uploaded*/
    public function handleUploadedProfilePicture(ProfileUpdateRequest $request)
    {
        if ($request->hasFile('profile_picture')){
            if($request->user()->profile_picture){
                Storage::disk('public')->delete('profile_picture/'.$request->user()->profile_picture);
            }

            $image = $request->file('profile_picture');
            $filename = time().'.'.$image->getClientOriginalExtension();

            $img = Image::read($image->getRealPath());
            $img->resize(200, 100, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            Storage::disk('public')->put('profile_picture/'.$filename, $img->encode());
            //$filename = $request->file('profile_picture')->store('profile_picture','public');
            $request->user()->profile_picture = $filename;

        }
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
}
