<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Jika validasi gagal, kembalikan respons JSON dengan status 400
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 400);
        }

        // Membuat pengguna baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

       // $token = $user->createToken('auth_token')->plainTextToken;

        // Mengembalikan respons JSON
        return response()->json([
            'success' => true,
            //'token' => $token,
            'message' => 'Login successfully'
        ], 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
         // Jika validasi gagal, kembalikan respons JSON dengan status 400
         if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 400);
        }
        $user = User::where ('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Credentials'
            ], 401);
        }
        $token = $user->createToken('signin_token')->plainTextToken;
        // Mengembalikan respons JSON
        return response()->json([
            'success' => true,
            'token' => $token,
            'message' => 'Login successfully'
        ], 200);
    }

    public function logout(Request $request)
    {
        // Hapus token pengguna saat ini
        $request->user()->tokens()->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'Logout successful'
        ], 200);
    }
}