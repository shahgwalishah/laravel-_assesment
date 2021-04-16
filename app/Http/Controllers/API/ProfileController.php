<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Image;

class ProfileController extends Controller
{
    public function profile() {
        return response()->json(['user' => auth()->user()], 200);
    }

    public function updateProfile(Request $request){
        $this->validate($request, [
            'username' => 'required|min:4|max:20',
        ]);
        if($request->hasFile('avatar')) {
            $this->uploadAvatar($request);
        }
        $user = User::where('id','=',auth()->user()->id)->first();
        User::where('id','=',auth()->user()->id)->update([
            'username' => isset($request->username) ? $request->username : $user->username,
            'email' => isset($request->email) ? $request->email : $user->email,
            'user_role' => isset($request->user_role) ? $request->user_role : $user->user_role,
        ]);
        return collect([
            'status' => true,
            'message' => 'profile updated successfully .... !',
        ]);
    }

    public function uploadAvatar($request){
        $image = $request->file('avatar');
        $input['avatar'] = time().'.'.$image->extension();
        $destinationPath = 'uploads';
        $img = Image::make($image->path());
        $img->resize(250, 250, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$input['avatar']);
        $destinationPath = 'uploads';
        $image->move($destinationPath, $input['avatar']);
        $avatar = $destinationPath.'/'.$input['avatar'];
        User::where('id','=',auth()->user()->id)->update([
            'avatar' => $avatar
        ]);
    }
}
