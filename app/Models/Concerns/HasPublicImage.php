<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Storage;

trait HasPublicImage
{
    public function publicUrl(?string $path, ?string $external = null): ?string
    {
        if (filled($path)) {
            if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                return $path;
            }

            return Storage::disk('public')->url($path);
        }

        return filled($external) ? $external : null;
    }

    public static function deletePublicFile(?string $path): void
    {
        if (filled($path) && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
