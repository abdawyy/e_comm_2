<?php

namespace App\Actions;

use App\Models\Order;
use App\Models\User;
use App\Support\Phone;

class LinkGuestOrders
{
    public function execute(User $user): void
    {
        $phone = Phone::normalize($user->phone);
        $email = strtolower((string) $user->email);

        Order::query()
            ->whereNull('user_id')
            ->where(function ($query) use ($phone, $email) {
                if ($email !== '') {
                    $query->whereRaw('lower(customer_email) = ?', [$email]);
                }

                if ($phone !== '') {
                    $method = $email !== '' ? 'orWhere' : 'where';
                    $query->{$method}('customer_phone', $phone);
                }
            })
            ->update(['user_id' => $user->id]);
    }
}
