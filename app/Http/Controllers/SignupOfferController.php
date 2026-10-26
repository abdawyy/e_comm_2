<?php

namespace App\Http\Controllers;

use App\Mail\SignupCouponMail;
use App\Models\SignupLead;
use App\Models\SignupOffer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SignupOfferController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $offer = SignupOffer::current();

        if (! $offer || ! $offer->isVisible()) {
            return response()->json(['message' => __('store.signup.unavailable')], 422);
        }

        $email = strtolower($data['email']);
        $lead = SignupLead::query()->firstOrNew([
            'email' => $email,
            'coupon_id' => $offer->coupon_id,
        ]);

        if (! $lead->exists) {
            $lead->locale = app()->getLocale();
            $lead->sent_at = now();
            $lead->save();
            Mail::to($email)->send(new SignupCouponMail($offer->coupon, app()->getLocale()));
        }

        return response()->json([
            'ok' => true,
            'message' => __('store.signup.sent'),
        ]);
    }
}
