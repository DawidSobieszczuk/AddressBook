<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request) {
        $user = User::where('name', $request->input('name'))->first();

        if (!$user) 
            return response()->json([
                'message' => 'User not found',
            ]);
        if (!Hash::check($request->input('password'), $user->password)) 
            return response()->json([
                'message' => 'Wrong password',
            ]);

        return response()->json([
            'user_id' => $user->id,
            'token' => $user->createToken('user_token')->plainTextToken,
        ]);
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'User logged out',
        ]);
    }

    public function viewCurrentLogged() {
        $user = auth()->user();
        $isAdmin = $user->hasRole('admin');

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'is_admin' => $isAdmin,
        ]);
        
    }
}
