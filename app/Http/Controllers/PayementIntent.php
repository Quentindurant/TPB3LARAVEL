<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class PayementIntent extends Controller
{
    public function __invoke(Request $request)
    {
        if (! $request->session()->has('pending_location')) {
            return redirect()
                ->route('locations.create');
        }
        Stripe::setApiKey(config('stripe.secret_key'));
        $intent = PaymentIntent::create([
            'amount' => 500,
            'currency' => 'eur',
        ]);

        return view('locations.payment', [
            'secret' => $intent->client_secret,
        ]);
    }

    public function confirm(Request $request): void
    {
        $paymentIntentId = $request->input('payment_intent_id');
        if ($paymentIntentId === 'error') {
            return;
        }

        Stripe::setApiKey(config('stripe.secret_key'));
        $intent = PaymentIntent::retrieve($paymentIntentId);

        if ($intent->status === 'succeeded') {
            $data = $request->session()->pull('pending_location');
            Location::create([
                ...$data,
                'user_id' => $request->user()->id,
            ]);
        }
    }
}
