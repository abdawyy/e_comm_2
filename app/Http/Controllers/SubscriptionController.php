<?php

namespace App\Http\Controllers;

use App\Actions\CreateSubscriptionAction;
use App\Exceptions\CartException;
use App\Models\Bundle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function store(Request $request, Bundle $bundle, CreateSubscriptionAction $action): RedirectResponse
    {
        abort_unless($bundle->is_active && $bundle->is_subscribable, 404);

        $data = $request->validate([
            'interval' => ['required', 'in:weekly,monthly'],
            'quantity' => ['required'],
            'city_id' => ['required', 'integer', 'exists:cities,id'],
            'address' => ['required', 'string', 'max:500'],
            'phone' => ['required', 'string', 'max:32'],
            'customer_name' => ['nullable', 'string', 'max:120'],
        ]);

        try {
            $action->execute($request->user(), $bundle, $data);
        } catch (CartException $exception) {
            return back()->withInput()->with('error', __($exception->errorKey));
        }

        return redirect()->route('account.home')->with('status', __('store.subscription.started'));
    }
}
