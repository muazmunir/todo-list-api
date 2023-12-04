<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    // Register User
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $verification_code = Str::random(6); // Generate unique code

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'verified' => false, // Not verified initially
            'verification_code' => $verification_code, // Generating verification code
        ]);

        Mail::to($user->email)->send(new \App\Mail\VerificationMail($user, $verification_code));

        return response()->json([
            'message' => 'User registered successfully. Verification email sent.',
            'user' => $user,
        ], 201);
    }

    // Verify User
    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'verification_code' => 'required|digits:6', // Assuming verification code is 6 digits
        ]);

        $user = User::where('email', $request->email)
            ->where('verification_code', $request->verification_code)
            ->first();

        if (! $user) {
            return response()->json(['error' => 'Invalid verification code.'], 422);
        }

        // Mark the user as verified
        $user->verified = true;
        $user->verification_code = null; // Clear the verification code after successful verification
        $user->save();

        return response()->json(['message' => 'User verified successfully.'], 200);
    }

    // Login User
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (! Auth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials.'], 401);
        }

        $user = $request->user();

        if (! $user->verified) {
            Auth::logout();

            return response()->json(['error' => 'Account not verified.'], 401);
        }

        $token = Auth::attempt($credentials);

        return response()->json(['token' => $token]);
    }

    // Logout User
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }
}
