<?php

namespace App\Filament\Admin\Support;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class Fields
{
    /**
     * @return array<int, TextInput|Textarea>
     */
    public static function translated(string $name, string $label, bool $required = false, bool $area = false): array
    {
        $english = $area ? Textarea::make($name.'_en')->rows(4) : TextInput::make($name.'_en');
        $arabic = $area ? Textarea::make($name.'_ar')->rows(4) : TextInput::make($name.'_ar');

        return [
            $english->label($label.' (EN)')->required($required),
            $arabic->label($label.' (AR)')->required($required)->extraInputAttributes(['dir' => 'rtl']),
        ];
    }
}
