<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function requestOtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'mode' => ['required', Rule::in(['login', 'register'])],
            'name' => ['nullable', 'string', 'max:255'],
            'mobile' => ['required', 'digits:10'],
            'email' => ['nullable', 'email', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
        ]);

        $user = User::where('mobile', $validated['mobile'])->first();

        if ($validated['mode'] === 'register') {
            if ($user) {
                return back()
                    ->withErrors(['otp_register' => 'This mobile number is already registered. Please log in instead.'])
                    ->withInput()
                    ->with('openAuthModal', 'register');
            }

            $user = User::create([
                'name' => $validated['name'] ?: 'Student ' . Str::substr($validated['mobile'], -4),
                'mobile' => $validated['mobile'],
                'email' => $validated['email'] ?? null,
                'date_of_birth' => $validated['date_of_birth'] ?? null,
            ]);
        } elseif (! $user) {
            return back()
                ->withErrors(['otp_login' => 'No account was found for that mobile number.'])
                ->withInput()
                ->with('openAuthModal', 'login');
        }

        $otp = (string) random_int(100000, 999999);

        $user->forceFill([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ])->save();

        return back()
            ->with('otp_mobile', $user->mobile)
            ->with('otp_notice', 'OTP generated for demo use: ' . $otp)
            ->with('openAuthModal', $validated['mode'])
            ->with('status', 'OTP has been generated. Enter it below to continue.');
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'mobile' => ['required', 'digits:10'],
            'otp' => ['required', 'digits:6'],
        ]);

        $user = User::where('mobile', $validated['mobile'])->first();

        if (! $user || $user->otp !== $validated['otp'] || ! $user->otp_expires_at || $user->otp_expires_at->isPast()) {
            return back()
                ->withErrors(['otp' => 'The OTP is invalid or has expired.'])
                ->withInput()
                ->with('openAuthModal', 'login');
        }

        $user->forceFill([
            'otp' => null,
            'otp_expires_at' => null,
        ])->save();

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('status', 'Login successful.');
    }

    public function registerPassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['nullable', 'digits:10', 'unique:users,mobile'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'date_of_birth' => ['nullable', 'date'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if (blank($validated['mobile'] ?? null) && blank($validated['email'] ?? null)) {
            return back()
                ->withErrors(['password_register' => 'Provide either a mobile number or an email address.'])
                ->withInput()
                ->with('openAuthModal', 'register');
        }

        $user = User::create($validated);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('status', 'Account created successfully.');
    }

    public function loginPassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $field = filter_var($validated['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';
        $user = User::where($field, $validated['login'])->first();

        if (! $user || ! $user->password || ! Hash::check($validated['password'], $user->password)) {
            return back()
                ->withErrors(['password_login' => 'The provided credentials do not match our records.'])
                ->withInput()
                ->with('openAuthModal', 'login');
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('status', 'Login successful.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('status', 'You have been logged out.');
    }
}
