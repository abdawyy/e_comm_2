<?php

namespace App\Http\Controllers;

use App\Actions\LinkGuestOrders;
use App\Contracts\SmsSender;
use App\Models\PhoneOtp;
use App\Models\User;
use App\Support\Phone;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('store.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withInput()->with('error', __('store.auth.failed'));
        }

        $request->session()->regenerate();
        app(LinkGuestOrders::class)->execute($request->user());

        return redirect()->intended(route('account.home'));
    }

    public function showRegister(): View
    {
        return view('store.auth.register');
    }

    public function register(Request $request, LinkGuestOrders $linker): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:32'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = new User;
        $user->forceFill([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => Phone::normalize($data['phone'] ?? ''),
            'password' => $data['password'],
            'is_admin' => false,
        ])->save();

        event(new Registered($user));
        Auth::login($user);
        $linker->execute($user);

        return redirect()->route('account.home');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    public function showForgot(): View
    {
        return view('store.auth.forgot');
    }

    public function sendReset(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);
        Password::sendResetLink($request->only('email'));

        return back()->with('status', __('store.auth.reset_sent'));
    }

    public function showReset(string $token): View
    {
        return view('store.auth.reset', ['token' => $token]);
    }

    public function reset(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $status = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function (User $user, string $password) {
            $user->forceFill([
                'password' => $password,
                'remember_token' => Str::random(60),
            ])->save();
            event(new PasswordReset($user));
        });

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __('store.auth.reset_done'))
            : back()->with('error', __($status));
    }

    public function sendOtp(Request $request, SmsSender $sms): RedirectResponse
    {
        $data = $request->validate(['phone' => ['required', 'string', 'max:32']]);
        $phone = Phone::normalize($data['phone']);
        $code = (string) random_int(100000, 999999);

        PhoneOtp::query()->create([
            'phone' => $phone,
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes(10),
        ]);

        $sms->send($phone, __('store.auth.otp_sms', ['code' => $code]));

        return back()->with('status', __('store.auth.otp_sent'))->with('otp_phone', $phone);
    }

    public function verifyOtp(Request $request, LinkGuestOrders $linker): RedirectResponse
    {
        $data = $request->validate([
            'phone' => ['required', 'string'],
            'code' => ['required', 'string'],
            'name' => ['nullable', 'string', 'max:120'],
        ]);

        $phone = Phone::normalize($data['phone']);
        $otp = PhoneOtp::query()
            ->where('phone', $phone)
            ->whereNull('consumed_at')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (! $otp || ! Hash::check($data['code'], $otp->code_hash)) {
            return back()->with('error', __('store.auth.otp_invalid'));
        }

        $otp->forceFill(['consumed_at' => now()])->save();
        $user = User::query()->where('phone', $phone)->first();

        if (! $user) {
            $user = new User;
            $user->forceFill([
                'name' => $data['name'] ?: __('store.auth.phone_customer'),
                'email' => $phone.'@phone.atlas.test',
                'phone' => $phone,
                'password' => Str::random(32),
                'is_admin' => false,
            ])->save();
        }

        Auth::login($user);
        $request->session()->regenerate();
        $linker->execute($user);

        return redirect()->route('account.home');
    }
}
