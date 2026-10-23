<?php

namespace App\Http\Middleware;

use App\Models\ProductView;
use App\Models\SitePageView;
use App\Models\SiteVisitor;
use App\Services\VisitorLocator;
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

            if ($isNew || blank($visitor->geo_resolved_at)) {
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

        return ! preg_match('/\.(css|js|map|png|jpe?g|webp|svg|ico|woff2?)$/i', $path);
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

    private function applyGeo(SiteVisitor $visitor, Request $request): void
    {
        $country = $request->headers->get('CF-IPCountry');
        $city = $request->headers->get('CF-IPCity');
        $region = $request->headers->get('CF-Region') ?: $request->headers->get('CF-IPRegion');

        if (filled($country) && ! in_array(strtoupper($country), ['XX', 'T1'], true)) {
            $visitor->country_code = strtoupper($country);
            $visitor->country = strtoupper($country);
            $visitor->region = $region;
            $visitor->city = $city;
            $visitor->area = app(Places::class)->match($city, $region);
            $visitor->geo_resolved_at = now();

            return;
        }

        $place = app(VisitorLocator::class)->locate($request->ip());

        if ($place === null) {
            return;
        }

        $visitor->country_code = $place['country_code'];
        $visitor->country = $place['country'];
        $visitor->region = $place['region'];
        $visitor->city = $place['city'];
        $visitor->area = $place['area'];
        $visitor->geo_resolved_at = now();
    }
}
