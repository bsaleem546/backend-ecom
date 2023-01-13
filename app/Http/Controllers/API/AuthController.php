<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * Create user
     * @param Request $request
     * @return User
     */
    public function createUser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required'
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User register successfully',
                'token' => $user->createToken('API TOKEN')->plainTextToken
            ], 200);
        }
        catch (\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Login user
     * @param Request $request
     * @return User
     */
    public function makeLogin(Request $request)
    {
        try {
            Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                $user = User::where('email', $request->email)->first();
                return response()->json([
                    'status' => true,
                    'message' => 'User login successfully',
                    'user' => $user,
                    'token' => $user->createToken('API TOKEN')->plainTextToken
                ], 200);
            }
            return response()->json([
                'status' => false,
                'message' => 'User not found',
            ], 404);
        }
        catch (\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Log out account user.
     *
     * @return \Illuminate\Routing\Redirector
     */
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json(['status' => true, 'message' => 'User logged out'], 200);
    }
}
