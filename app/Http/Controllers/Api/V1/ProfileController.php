<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $data = $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'min:7', 'max:20'],
            'country' => ['required', 'string', 'max:100'],
            'dob' => ['required', 'date', 'before:today'],
        ], [
            'phone.min' => 'Phone number looks too short.',
            'country.required' => 'Please select your country.',
        ]);

        $nameParts = explode(' ', trim($data['fullname']), 2);

        $request->user()->update([
            'firstname' => $nameParts[0] ?? '',
            'lastname' => $nameParts[1] ?? '',
            'phone_number' => $data['phone'],
            'country' => $data['country'],
            'dob' => $data['dob'],
        ]);

        return (new UserResource($request->user()->fresh()))
            ->additional(['message' => 'Profile updated successfully.']);
    }

    public function password(Request $request)
    {
        $data = $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'min:8', 'confirmed'],
        ], [
            'password.confirmed' => 'Passwords do not match.',
        ]);

        $user = $request->user();

        if (! Hash::check($data['current_password'], $user->password)) {
            throw ValidationException::withMessages(['current_password' => 'Current password is incorrect.']);
        }

        $user->update(['password' => Hash::make($data['password'])]);

        // sign out other devices, keep this one
        $user->tokens()->where('id', '!=', $user->currentAccessToken()->id)->delete();

        return response()->json(['message' => 'Password updated successfully.']);
    }

    public function preferences(Request $request)
    {
        $data = $request->validate([
            'notify_login_attempts' => ['sometimes', 'boolean'],
            'notify_email_notifications' => ['sometimes', 'boolean'],
            'notify_deposit_alerts' => ['sometimes', 'boolean'],
            'notify_withdrawal_alerts' => ['sometimes', 'boolean'],
            'notify_security_alerts' => ['sometimes', 'boolean'],
        ]);

        $request->user()->update($data);

        return (new UserResource($request->user()->fresh()))
            ->additional(['message' => 'Preferences updated.']);
    }
}
