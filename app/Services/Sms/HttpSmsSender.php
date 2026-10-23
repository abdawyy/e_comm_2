<?php

namespace App\Services\Sms;

use App\Contracts\SmsSender;
use Illuminate\Support\Facades\Http;

class HttpSmsSender implements SmsSender
{
    public function send(string $phone, string $message): void
    {
        $url = config('store.sms_http_url');

        if (blank($url)) {
            return;
        }

        Http::timeout(10)->post($url, [
            'phone' => $phone,
            'message' => $message,
        ]);
    }
}
