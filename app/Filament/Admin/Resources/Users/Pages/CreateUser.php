<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (blank($data['name'] ?? null)) {
            $data['name'] = $data['phone'];
        }

        if (blank($data['password'] ?? null)) {
            $data['password'] = Str::password(32);
        }

        return $data;
    }
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
