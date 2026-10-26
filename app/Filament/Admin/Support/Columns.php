<?php

namespace App\Filament\Admin\Support;

use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;

class Columns
{
    public static function active(): TextColumn
    {
        return TextColumn::make('is_active')
            ->label(__('admin.fields.active'))
            ->badge()
            ->formatStateUsing(fn (mixed $state): string => $state ? __('admin.fields.active') : __('admin.fields.inactive'))
            ->color(fn (mixed $state): string => $state ? 'success' : 'gray')
            ->action(function (Model $record): void {
                abort_unless(auth()->user()?->can('update', $record), 403);

                $record->update(['is_active' => ! $record->is_active]);
            });
    }
}
