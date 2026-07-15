<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class AuthController extends Controller
{
    private const OTP_CACHE_PREFIX = 'password_reset_otp:';
    private const OTP_TTL_MINUTES = 10;

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
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $request->string('email')->toString())->firstOrFail();
        $otp = (string) random_int(100000, 999999);
        $cacheKey = self::OTP_CACHE_PREFIX . strtolower($user->email);

        Cache::put($cacheKey, [
            'otp' => $otp,
            'user_id' => $user->id,
            'email' => $user->email,
            'expires_at' => now()->addMinutes(self::OTP_TTL_MINUTES)->toDateTimeString(),
        ], now()->addMinutes(self::OTP_TTL_MINUTES));

        try {
            Log::info('Password reset OTP send attempt', [
                'email' => $user->email,
                'mailer' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'scheme' => config('mail.mailers.smtp.scheme'),
            ]);

            Mail::send('emails.password-otp', [
                'user' => $user,
                'otp' => $otp,
                'minutes' => self::OTP_TTL_MINUTES,
            ], function ($message) use ($user): void {
                $message->to($user->email, $user->full_name ?? $user->name ?? 'User')
                    ->subject('Your Password Reset OTP');
            });

            Log::info('Password reset OTP sent successfully', [
                'email' => $user->email,
            ]);
        } catch (TransportExceptionInterface $e) {
            Log::error('Password reset OTP failed', [
                'email' => $user->email,
                'message' => $e->getMessage(),
                'class' => get_class($e),
            ]);
            report($e);

            Cache::forget($cacheKey);

            return back()
                ->withErrors([
                    'email' => 'Email send nathi thatu. Please SMTP settings check karo.',
                ])
                ->withInput();
        }

        return redirect()->route('password.otp.form', ['email' => $user->email])
            ->with('status', 'OTP sent to your email address.');
    }

    public function showResetPasswordForm(Request $request)
    {
        $email = $request->query('email', old('email'));

        return view('auth.reset-password', [
            'email' => $email,
        ]);
    }

    public function showOtpForm(Request $request)
    {
        return view('auth.verify-otp', [
            'email' => $request->query('email', old('email')),
        ]);
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'otp' => ['required', 'digits:6'],
        ]);

        $cacheKey = self::OTP_CACHE_PREFIX . strtolower($request->string('email')->toString());
        $payload = Cache::get($cacheKey);

        if (! $payload || ! hash_equals((string) ($payload['otp'] ?? ''), (string) $request->otp)) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.'])->withInput();
        }

        Cache::put($cacheKey . ':verified', true, now()->addMinutes(10));

        return redirect()->route('password.reset.form', ['email' => $request->email])
            ->with('status', 'OTP verified. Set your new password.');
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $cacheKey = self::OTP_CACHE_PREFIX . strtolower($request->string('email')->toString());
        $verified = Cache::get($cacheKey . ':verified');

        if (! $verified) {
            return redirect()->route('password.otp.form', ['email' => $request->email])
                ->withErrors(['otp' => 'Please verify OTP first.']);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $user->password = Hash::make($request->password);
        $user->setRememberToken(Str::random(60));
        $user->save();

        Cache::forget($cacheKey);
        Cache::forget($cacheKey . ':verified');

        Auth::login($user);

        return redirect()->route('dashboard')->with('status', 'Password reset successful.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('status', 'You have been logged out.');
    }
}
