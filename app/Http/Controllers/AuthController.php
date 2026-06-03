<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'admin_username' => 'required|string|max:50|unique:admin,admin_username',
            'admin_password' => 'required|string|min:3',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to register. Please check your input data',
                'data' => null,
                'errors' => $validator->errors(),
            ], 400);
        }

        $admin = Admin::create([
            'admin_username' => $request->admin_username,
            'admin_password' => Hash::make($request->admin_password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully registered.',
            'data' => $admin,
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'admin_username' => 'required|string',
            'admin_password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to login. Please check your input data',
                'data' => null,
                'errors' => $validator->errors(),
            ], 400);
        }

        // Cari admin berdasarkan username dulu
        $admin = Admin::where('admin_username', $request->admin_username)->first();

        if (! $admin || ! Hash::check($request->admin_password, $admin->admin_password)) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to login. Wrong username or password',
                'data' => null,
            ], 401);
        }

        // Generate JWT token
        $token = auth()->guard('api')->login($admin);

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged in.',
            'data' => $admin,
            'accesstoken' => $token,
        ], 200);
    }

    public function logout()
    {
        auth()->guard('api')->logout();

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out.',
        ], 200);
    }
}
