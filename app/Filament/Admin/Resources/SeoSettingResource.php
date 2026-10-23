<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SeoSettings\Pages\CreateSeoSetting;
use App\Filament\Admin\Resources\SeoSettings\Pages\EditSeoSetting;
use App\Filament\Admin\Resources\SeoSettings\Pages\ListSeoSettings;
use App\Filament\Admin\Support\Fields;
use App\Models\SeoSetting;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SeoSettingResource extends Resource
{
    protected static ?string $model = SeoSetting::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    public static function translatableFields(): array
    {
        return ['meta_title', 'meta_description'];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.groups.content');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.seo.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.seo.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            TextInput::make('route_key')->required()->unique(ignoreRecord: true)->label(__('admin.fields.route')),
            ...Fields::translated('meta_title', __('admin.fields.meta_title')),
            ...Fields::translated('meta_description', __('admin.fields.meta_description'), false, true),
            FileUpload::make('og_image_path')->disk('public')->directory('seo')->image()->maxSize(4096)->helperText(__('admin.help.og'))->label(__('admin.fields.og_image')),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('route_key')->label(__('admin.fields.route')),
        ])->recordActions([EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSeoSettings::route('/'),
            'create' => CreateSeoSetting::route('/create'),
            'edit' => EditSeoSetting::route('/{record}/edit'),
        ];
    }
}
