<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function registerPassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'digits:10', 'unique:users,mobile'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'dob' => ['required', 'date'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'full_name' => $validated['full_name'],
            'mobile' => $validated['mobile'],
            'email' => $validated['email'],
            'dob' => $validated['dob'],
            'password' => $validated['password'],
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('status', 'Account created successfully.');
    }

    public function loginPassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'mobile_or_email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $field = filter_var($validated['mobile_or_email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';

        if (! Auth::attempt([$field => $validated['mobile_or_email'], 'password' => $validated['password']])) {
            return back()
                ->withErrors(['password_login' => 'The provided credentials do not match our records.'])
                ->withInput()
                ->with('openAuthModal', 'login');
        }

        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('status', 'Login successful.');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetPasswordForm(string $token, Request $request)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email', old('email')),
        ]);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->password = Hash::make($password);
                $user->setRememberToken(Str::random(60));
                $user->save();

                Auth::login($user);
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('dashboard')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]])->withInput();
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('status', 'You have been logged out.');
    }
}
