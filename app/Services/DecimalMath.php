<?php

namespace App\Services;

class DecimalMath
{
    public function scale(): int
    {
        return max(0, (int) config('store.money_scale', 4));
    }

    public function zero(): string
    {
        return $this->normalize('0');
    }

    public function normalize(string|int|null $value): string
    {
        if ($value === null || $value === '') {
            return bcadd('0', '0', $this->scale());
        }

        $raw = trim((string) $value);

        if (! preg_match('/^-?\d+(\.\d+)?$/', $raw)) {
            return bcadd('0', '0', $this->scale());
        }

        return bcadd($raw, '0', $this->scale());
    }

    public function add(string|int|null $left, string|int|null $right): string
    {
        return bcadd($this->normalize($left), $this->normalize($right), $this->scale());
    }

    public function sub(string|int|null $left, string|int|null $right): string
    {
        return bcsub($this->normalize($left), $this->normalize($right), $this->scale());
    }

    public function mul(string|int|null $left, string|int|null $right): string
    {
        $raw = bcmul($this->normalize($left), $this->normalize($right), $this->scale() + 6);

        return $this->roundHalfAwayFromZero($raw, $this->scale());
    }

    public function div(string|int|null $left, string|int|null $right): string
    {
        $divisor = $this->normalize($right);

        if (bccomp($divisor, '0', $this->scale()) === 0) {
            return $this->zero();
        }

        $raw = bcdiv($this->normalize($left), $divisor, $this->scale() + 6);

        return $this->roundHalfAwayFromZero($raw, $this->scale());
    }

    public function percent(string|int|null $base, string|int|null $percent): string
    {
        $raw = bcdiv(
            bcmul($this->normalize($base), $this->normalize($percent), $this->scale() + 6),
            '100',
            $this->scale() + 6,
        );

        return $this->roundHalfAwayFromZero($raw, $this->scale());
    }

    public function comp(string|int|null $left, string|int|null $right): int
    {
        return bccomp($this->normalize($left), $this->normalize($right), $this->scale());
    }

    public function maxZero(string|int|null $value): string
    {
        $normalized = $this->normalize($value);

        return bccomp($normalized, '0', $this->scale()) < 0 ? $this->zero() : $normalized;
    }

    public function min(string|int|null $left, string|int|null $right): string
    {
        return $this->comp($left, $right) <= 0 ? $this->normalize($left) : $this->normalize($right);
    }

    public function surcharge(string|int|null $base, string $type, string|int|null $value): string
    {
        if ($type === 'percent') {
            return $this->percent($base, $value);
        }

        return $this->normalize($value);
    }

    public function toCents(string|int|null $amount): int
    {
        $scaled = bcmul($this->normalize($amount), '100', $this->scale() + 6);

        return (int) $this->roundHalfAwayFromZero($scaled, 0);
    }

    public function format(string|int|null $amount, bool $withCurrency = true): string
    {
        $rounded = $this->roundHalfAwayFromZero($this->normalize($amount), 2);
        $negative = str_starts_with($rounded, '-');
        $absolute = ltrim($rounded, '-');
        [$whole, $fraction] = array_pad(explode('.', $absolute, 2), 2, '00');
        $fraction = str_pad(substr($fraction, 0, 2), 2, '0');
        $whole = preg_replace('/\B(?=(\d{3})+(?!\d))/', ',', $whole) ?? $whole;
        $formatted = ($negative ? '-' : '').$whole.'.'.$fraction;

        if (! $withCurrency) {
            return $formatted;
        }

        return $formatted.' '.config('store.currency', 'EGP');
    }

    public function roundHalfAwayFromZero(string $number, int $precision): string
    {
        $number = trim($number);

        if ($number === '' || $number === '-' || $number === '+') {
            return bcadd('0', '0', $precision);
        }

        $negative = str_starts_with($number, '-');
        $absolute = ltrim($number, '+-');

        if (! preg_match('/^\d+(\.\d+)?$/', $absolute)) {
            return bcadd('0', '0', $precision);
        }

        $delta = bcdiv('5', bcpow('10', (string) ($precision + 1)), $precision + 8);
        $adjusted = bcadd($absolute, $delta, $precision + 8);
        $truncated = bcdiv($adjusted, '1', $precision);

        if ($negative && bccomp($truncated, '0', $precision) !== 0) {
            return bcsub('0', $truncated, $precision);
        }

        return $this->ensureScale($truncated, $precision);
    }

    private function ensureScale(string $number, int $precision): string
    {
        if ($precision === 0) {
            return explode('.', $number)[0];
        }

        if (! str_contains($number, '.')) {
            return $number.'.'.str_repeat('0', $precision);
        }

        [$whole, $fraction] = explode('.', $number, 2);
        $fraction = substr(str_pad($fraction, $precision, '0'), 0, $precision);

        return $whole.'.'.$fraction;
    }
}
