<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordReset;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password-new');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Check if user exists
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'We could not find a user with that email address.']);
        }

        // Delete any existing tokens for this email
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Generate a secure token
        $token = Str::random(64);

        // Store the token in the database
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        // Create reset URL
        $resetUrl = route('password.reset', ['token' => $token, 'email' => $request->email]);

        // Send password reset email
        try {
            Mail::to($request->email)->send(new PasswordReset($resetUrl));
            
            \Log::info('Password reset email sent successfully', [
                'email' => $request->email,
                'reset_url' => $resetUrl
            ]);
            
            return back()->with('status', 'We have sent you a password reset link to your email address. Please check your inbox.');
        } catch (\Exception $e) {
            \Log::error('Failed to send password reset email', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Failed to send reset email: ' . $e->getMessage()]);
        }
    }
}
