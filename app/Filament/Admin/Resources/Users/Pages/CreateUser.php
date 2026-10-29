<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    protected function afterCreate(): void
    {
        if (! auth()->user()?->hasRole('super_admin')) {
            return;
        }

        $this->record->forceFill([
            'is_admin' => (bool) ($this->data['is_admin'] ?? false),
        ])->save();
    }
}
