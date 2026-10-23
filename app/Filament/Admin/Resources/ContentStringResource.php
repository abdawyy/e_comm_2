<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ContentStrings\Pages\CreateContentString;
use App\Filament\Admin\Resources\ContentStrings\Pages\EditContentString;
use App\Filament\Admin\Resources\ContentStrings\Pages\ListContentStrings;
use App\Filament\Admin\Support\Fields;
use App\Models\ContentString;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContentStringResource extends Resource
{
    protected static ?string $model = ContentString::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    public static function translatableFields(): array
    {
        return ['value'];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.groups.content');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.content.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.content.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            TextInput::make('key')->required()->unique(ignoreRecord: true)->label(__('admin.fields.key')),
            ...Fields::translated('value', __('admin.fields.value'), true, true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('key')->searchable()->label(__('admin.fields.key')),
            TextColumn::make('value')->limit(60)->label(__('admin.fields.value')),
        ])->recordActions([EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListContentStrings::route('/'),
            'create' => CreateContentString::route('/create'),
            'edit' => EditContentString::route('/{record}/edit'),
        ];
    }
}
