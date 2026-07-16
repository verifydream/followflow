<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
    public function register(Request $request) {
        $data = $request->validate(['name' => 'required', 'email' => 'required|email|unique:users', 'phone' => 'nullable']);
        $data['password'] = Hash::make('default');
        $user = User::create($data);
        $token = $user->createToken('auth')->plainTextToken;
        return ['user' => $user, 'token' => $token];
    }

    public function login(Request $request) {
        $user = User::where('email', $request->email)->first();
        if (!$user) return response()->json(['error' => 'User not found'], 404);
        $token = $user->createToken('auth')->plainTextToken;
        return ['user' => $user, 'token' => $token];
    }
}
