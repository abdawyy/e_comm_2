<?php

namespace App\Services;

use App\Support\CountryNames;
use App\Support\Places;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Throwable;

class VisitorLocator
{
    public function __construct(
        private Places $places,
        private CountryNames $countries,
    ) {}

    /**
     * @return array{country_code: ?string, country: ?string, region: ?string, city: ?string, area: ?string}|null
     */
    public function locate(?string $ip): ?array
    {
        if ($this->isPrivate($ip)) {
            return null;
        }

        $cacheKey = 'geo.place.v3.'.hash('sha256', (string) $ip);

        $cached = Cache::get($cacheKey);

        if ($cached === false) {
            return null;
        }

        if (is_array($cached)) {
            return $cached;
        }

        $lookup = $this->lookup($ip);

        if ($lookup === null) {
            Cache::put($cacheKey, false, now()->addMinutes(10));

            return null;
        }

        $area = $this->places->match($lookup['neighbourhood'], $lookup['city'], $lookup['region'])
            ?: $lookup['neighbourhood'];

        $place = [
            'country_code' => $lookup['country_code'],
            'country' => $this->countries->name($lookup['country'] ?: $lookup['country_code']),
            'region' => $lookup['region'],
            'city' => $lookup['city'],
            'area' => filled($area) ? $area : null,
        ];

        Cache::put($cacheKey, $place, now()->addDay());

        return $place;
    }

    /**
     * @return array{country_code: ?string, country: ?string, region: ?string, city: ?string, neighbourhood: ?string}|null
     */
    private function lookup(string $ip): ?array
    {
        $template = (string) config('store.geoip_url');

        if ($template === '') {
            return null;
        }

        $url = str_replace('{ip}', urlencode($ip), $template);
        $url = preg_replace('#(?<!:)/{2,}#', '/', $url) ?? $url;

        try {
            $response = Http::timeout(4)->acceptJson()->get($url);
        } catch (Throwable) {
            return null;
        }

        if (! $response->successful() || $response->json('status') === 'fail') {
            return null;
        }

        $city = $response->json('city');
        $region = $response->json('regionName') ?: $response->json('region');
        $neighbourhood = $this->neighbourhood(
            $response->json('lat'),
            $response->json('lon'),
        );

        return [
            'country_code' => $response->json('countryCode') ?: $response->json('country_code'),
            'country' => $response->json('country'),
            'region' => is_string($region) ? $region : null,
            'city' => is_string($city) ? $city : null,
            'neighbourhood' => $neighbourhood,
        ];
    }

    private function neighbourhood(mixed $lat, mixed $lon): ?string
    {
        if (! is_numeric($lat) || ! is_numeric($lon)) {
            return null;
        }

        $key = 'geo.neighbourhood.v2.'.round((float) $lat, 2).','.round((float) $lon, 2);
        $cached = Cache::get($key);

        if (is_string($cached) && $cached !== '') {
            return $cached;
        }

        $nominatim = $this->nominatim((float) $lat, (float) $lon);
        $cloud = $this->bigDataCloud((float) $lat, (float) $lon);
        $matched = $this->places->match($nominatim, $cloud);
        $place = $matched ?: $nominatim ?: $cloud;

        if (filled($place)) {
            Cache::put($key, $place, now()->addDays(7));
        }

        return filled($place) ? $place : null;
    }

    private function nominatim(float $lat, float $lon): ?string
    {
        try {
            $response = Http::timeout(4)
                ->withHeaders(['User-Agent' => 'AtlasMarket/1.0 (store analytics)'])
                ->acceptJson()
                ->get('https://nominatim.openstreetmap.org/reverse', [
                    'format' => 'jsonv2',
                    'lat' => $lat,
                    'lon' => $lon,
                    'zoom' => 18,
                    'addressdetails' => 1,
                ]);
        } catch (Throwable) {
            return null;
        }

        if (! $response->successful()) {
            return null;
        }

        $address = $response->json('address');

        if (! is_array($address)) {
            return null;
        }

        $blob = trim(implode(' ', array_map(fn ($part) => is_scalar($part) ? (string) $part : '', $address)).' '.(string) $response->json('display_name'));
        $matched = $this->places->match($blob);

        if ($matched !== null) {
            return $matched;
        }

        foreach (['neighbourhood', 'suburb', 'quarter', 'city_district', 'municipality', 'village', 'hamlet', 'town'] as $field) {
            if (filled($address[$field] ?? null)) {
                return (string) $address[$field];
            }
        }

        return null;
    }

    private function bigDataCloud(float $lat, float $lon): ?string
    {
        try {
            $response = Http::timeout(4)->acceptJson()->get('https://api.bigdatacloud.net/data/reverse-geocode-client', [
                'latitude' => $lat,
                'longitude' => $lon,
                'localityLanguage' => 'en',
            ]);
        } catch (Throwable) {
            return null;
        }

        if (! $response->successful()) {
            return null;
        }

        $names = [];

        foreach (['locality', 'city'] as $field) {
            if (filled($response->json($field))) {
                $names[] = (string) $response->json($field);
            }
        }

        foreach (['localityInfo.informative', 'localityInfo.administrative'] as $path) {
            $rows = $response->json($path);

            if (! is_array($rows)) {
                continue;
            }

            foreach ($rows as $row) {
                if (is_array($row) && filled($row['name'] ?? null)) {
                    $names[] = (string) $row['name'];
                }
            }
        }

        return $this->places->match(...$names) ?: ($names[0] ?? null);
    }

    private function isPrivate(?string $ip): bool
    {
        if (blank($ip)) {
            return true;
        }

        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false;
    }
}
