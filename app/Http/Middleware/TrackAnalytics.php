<?php

namespace App\Http\Middleware;

use App\Models\ProductView;
use App\Models\SitePageView;
use App\Models\SiteVisitor;
use App\Services\VisitorLocator;
use App\Support\CountryNames;
use App\Support\Places;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TrackAnalytics
{
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        try {
            if (! $this->shouldTrack($request, $response) || ! Schema::hasTable('site_visitors')) {
                return;
            }

            $now = now();
            $sessionId = $request->session()->getId();
            $visitor = SiteVisitor::query()->firstOrNew(['session_id' => $sessionId]);
            $isNew = ! $visitor->exists;

            if ($isNew) {
                $visitor->first_seen_at = $now;
                $visitor->ip_hash = $this->hash((string) $request->ip());
                $visitor->user_agent_hash = $this->hash((string) $request->userAgent());
                $visitor->device_type = $this->device((string) $request->userAgent());
                $visitor->referrer = $request->headers->get('referer');
                $visitor->referrer_host = $this->host($request->headers->get('referer'));
                foreach (['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term'] as $utm) {
                    $visitor->{$utm} = $request->query($utm);
                }
            }

            if ($isNew || $this->geoNeedsRefresh($visitor)) {
                $this->applyGeo($visitor, $request);
            }

            if ($request->user()) {
                $visitor->user_id = $request->user()->id;
            }

            $visitor->last_seen_at = $now;
            $visitor->save();

            SitePageView::query()->create([
                'site_visitor_id' => $visitor->id,
                'path' => '/'.ltrim($request->path(), '/'),
                'viewed_at' => $now,
            ]);

            $product = $request->route('product');

            if ($request->routeIs('products.show') && $product) {
                ProductView::query()->create([
                    'session_id' => $sessionId,
                    'user_id' => $request->user()?->id,
                    'product_id' => $product->id,
                    'visited_at' => $now,
                ]);
            }
        } catch (Throwable) {
            // Analytics must never change the response.
        }
    }

    private function shouldTrack(Request $request, Response $response): bool
    {
        if (! $request->isMethod('GET') || $response->getStatusCode() !== 200) {
            return false;
        }

        if (! str_contains((string) $response->headers->get('content-type', 'text/html'), 'text/html')) {
            return false;
        }

        $path = trim($request->path(), '/');

        foreach (['admin', 'my', 'api', 'livewire', 'payments', 'build', 'storage', 'vendor', 'up'] as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix.'/')) {
                return false;
            }
        }

        if (! preg_match('/\.(css|js|map|png|jpe?g|webp|svg|ico|woff2?)$/i', $path)) {
            return ! $this->isBot((string) $request->userAgent());
        }

        return false;
    }

    private function isBot(string $userAgent): bool
    {
        return (bool) preg_match('/bot|crawl|spider|slurp|preview|facebookexternalhit|whatsapp|telegrambot|pingdom|uptimerobot|lighthouse|headless/i', $userAgent);
    }

    private function hash(string $value): string
    {
        return hash_hmac('sha256', $value, (string) config('app.key'));
    }

    private function device(string $userAgent): string
    {
        $agent = strtolower($userAgent);

        if (str_contains($agent, 'ipad') || str_contains($agent, 'tablet')) {
            return 'tablet';
        }

        if (preg_match('/mobile|android|iphone|ipod/', $agent)) {
            return 'mobile';
        }

        return 'desktop';
    }

    private function host(?string $referrer): ?string
    {
        if (blank($referrer)) {
            return null;
        }

        $host = parse_url($referrer, PHP_URL_HOST);

        return is_string($host) ? strtolower($host) : null;
    }

    private function geoNeedsRefresh(SiteVisitor $visitor): bool
    {
        if (blank($visitor->geo_resolved_at)) {
            return true;
        }

        if (filled($visitor->country) && filled($visitor->city) && filled($visitor->area)) {
            return false;
        }

        return $visitor->geo_resolved_at->lt(now()->subHours(12));
    }

    private function applyGeo(SiteVisitor $visitor, Request $request): void
    {
        $ip = $this->clientIp($request);
        $place = $ip ? app(VisitorLocator::class)->locate($ip) : null;
        $headerCountry = strtoupper((string) $request->headers->get('CF-IPCountry'));
        $headerCountry = in_array($headerCountry, ['', 'XX', 'T1'], true) ? null : $headerCountry;

        if (is_array($place) && ($headerCountry === null || strtoupper((string) $place['country_code']) === $headerCountry)) {
            $visitor->country_code = $place['country_code'];
            $visitor->country = $place['country'];
            $visitor->region = $place['region'];
            $visitor->city = $place['city'];
            $visitor->area = $place['area'];
            $visitor->geo_resolved_at = now();

            return;
        }

        if ($headerCountry === null) {
            return;
        }

        $city = $this->headerText($request->headers->get('CF-IPCity'));
        $region = $this->headerText($request->headers->get('CF-Region') ?: $request->headers->get('CF-IPRegion'));
        $visitor->country_code = $headerCountry;
        $visitor->country = app(CountryNames::class)->name($headerCountry);
        $visitor->region = $region;
        $visitor->city = $city;
        $visitor->area = app(Places::class)->match($city, $region);
        $visitor->geo_resolved_at = now();
    }

    private function clientIp(Request $request): ?string
    {
        foreach (['CF-Connecting-IP', 'True-Client-IP'] as $header) {
            $ip = $this->publicIp($request->headers->get($header));

            if ($ip !== null) {
                return $ip;
            }
        }

        foreach (explode(',', (string) $request->headers->get('X-Forwarded-For')) as $part) {
            $ip = $this->publicIp($part);

            if ($ip !== null) {
                return $ip;
            }
        }

        return $this->publicIp($request->ip());
    }

    private function publicIp(mixed $value): ?string
    {
        $ip = trim((string) $value);

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
            return null;
        }

        return $ip;
    }

    private function headerText(?string $value): ?string
    {
        $value = rawurldecode(trim((string) $value));

        return $value === '' ? null : $value;
    }
}
