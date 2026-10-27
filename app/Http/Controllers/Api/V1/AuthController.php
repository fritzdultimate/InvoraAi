<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Mail\VerifyEmailMail;
use App\Models\User;
use App\Services\ReferralCreationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'device_name' => ['nullable', 'string', 'max:100'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        if (! $user->email_verified_at) {
            return response()->json([
                'message' => 'Please verify your email before logging in.',
                'code' => 'email_unverified',
            ], 403);
        }

        if ($user->suspended_at) {
            return response()->json([
                'message' => 'Your account has been suspended. Please contact support.',
                'code' => 'suspended',
            ], 403);
        }

        if ($user->two_factor_secret && $user->two_factor_confirmed_at) {
            $challenge = Str::random(48);
            Cache::put('api-2fa:' . $challenge, $user->id, now()->addMinutes(5));

            return response()->json([
                'two_factor' => true,
                'challenge_token' => $challenge,
            ]);
        }

        return $this->issueToken($user, $data['device_name'] ?? 'mobile');
    }

    public function twoFactor(Request $request, TwoFactorAuthenticationProvider $provider)
    {
        $data = $request->validate([
            'challenge_token' => ['required', 'string'],
            'code' => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:100'],
        ]);

        $userId = Cache::get('api-2fa:' . $data['challenge_token']);
        $user = $userId ? User::find($userId) : null;

        if (! $user) {
            return response()->json([
                'message' => 'Your login session expired. Please sign in again.',
                'code' => 'challenge_expired',
            ], 422);
        }

        $code = str_replace(' ', '', $data['code']);
        $valid = $provider->verify(decrypt($user->two_factor_secret), $code);

        // allow a recovery code too
        if (! $valid) {
            $recoveryCodes = $user->recoveryCodes();
            if (in_array($code, $recoveryCodes)) {
                $user->replaceRecoveryCode($code);
                $valid = true;
            }
        }

        if (! $valid) {
            throw ValidationException::withMessages([
                'code' => 'The code you entered is invalid.',
            ]);
        }

        Cache::forget('api-2fa:' . $data['challenge_token']);

        return $this->issueToken($user, $data['device_name'] ?? 'mobile');
    }

    public function register(Request $request)
    {
        $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
            'accept_terms' => ['accepted'],
            'ref' => ['nullable', 'string', 'max:50'],
        ], [
            'email.unique' => 'This email is already registered.',
            'password.confirmed' => 'Passwords do not match.',
            'accept_terms.accepted' => 'You must accept the terms to continue.',
        ]);

        $nameParts = explode(' ', trim($request->fullname), 2);
        $firstName = $nameParts[0] ?? '';
        $lastName = $nameParts[1] ?? '';

        DB::beginTransaction();

        try {
            $referrer = $request->ref ? User::where('affiliate_code', $request->ref)->first() : null;

            $user = User::create([
                'firstname' => $firstName,
                'lastname' => $lastName,
                'name' => Str::slug($firstName . '-' . $lastName) . rand(100, 999),
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'affiliate_code' => $this->generateReferralCode(),
                'referrer_id' => $referrer?->id,
            ]);

            if ($referrer) {
                ReferralCreationService::createFor($user, $referrer);
            }

            $user->assignRole('user');

            $this->sendVerificationMail($user);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('API registration failed: ' . $e->getMessage(), ['email' => $request->email]);

            return response()->json([
                'message' => 'Registration failed. Please try again or contact support.',
            ], 500);
        }

        return response()->json([
            'message' => 'Account created. Please check your email to verify your account.',
        ], 201);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.exists' => 'We could not find an account with that email.',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        if ($status !== Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages(['email' => __($status)]);
        }

        return response()->json([
            'message' => 'We have emailed your password reset link.',
        ]);
    }

    public function resendVerification(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->email_verified_at) {
            return response()->json(['message' => 'Your email is already verified. You can log in.']);
        }

        if ($user->last_verification_sent_at && $user->last_verification_sent_at->diffInSeconds(now()) < 60) {
            $secondsLeft = round(60 - $user->last_verification_sent_at->diffInSeconds(now()));

            throw ValidationException::withMessages([
                'email' => "Please wait {$secondsLeft}s before requesting another email.",
            ]);
        }

        $this->sendVerificationMail($user);

        return response()->json(['message' => 'Verification link has been sent to your email.']);
    }

    public function me(Request $request)
    {
        return new UserResource($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out.']);
    }

    private function issueToken(User $user, string $deviceName)
    {
        $token = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => new UserResource($user),
        ]);
    }

    private function sendVerificationMail(User $user)
    {
        $url = URL::temporarySignedRoute('verification.link', now()->addMinutes(10), [
            'user' => $user->id,
            'hash' => sha1($user->email),
        ]);

        Mail::to($user->email)->send(new VerifyEmailMail($url));

        $user->update(['last_verification_sent_at' => now()]);
    }

    private function generateReferralCode(int $length = 8): string
    {
        do {
            $code = strtoupper(Str::random($length));
        } while (User::where('affiliate_code', $code)->exists());

        return $code;
    }
}
