<?php

namespace App\Filament\Admin\Support;

use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Model;

class Columns
{
    public static function active(): ToggleColumn
    {
        return ToggleColumn::make('is_active')
            ->label(__('admin.fields.active'))
            ->onColor('success')
            ->offColor('gray')
            ->disabled(fn (Model $record): bool => ! auth()->user()?->can('update', $record));
    }
}
