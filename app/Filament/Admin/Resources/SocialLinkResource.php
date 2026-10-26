<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SocialLinks\Pages\CreateSocialLink;
use App\Filament\Admin\Resources\SocialLinks\Pages\EditSocialLink;
use App\Filament\Admin\Resources\SocialLinks\Pages\ListSocialLinks;
use App\Filament\Admin\Support\Columns;
use App\Models\SocialLink;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SocialLinkResource extends Resource
{
    protected static ?string $model = SocialLink::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedShare;

    protected static ?int $navigationSort = 4;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.groups.design');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.social.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.social.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Select::make('platform')
                ->label(__('admin.fields.platform'))
                ->options(fn () => SocialLink::platforms())
                ->required()
                ->unique(ignoreRecord: true)
                ->native(false),
            TextInput::make('url')
                ->label(__('admin.fields.url'))
                ->required()
                ->url()
                ->maxLength(255)
                ->helperText(__('admin.help.social'))
                ->placeholder('https://'),
            TextInput::make('sort_order')->numeric()->default(0)->label(__('admin.fields.sort')),
            Toggle::make('is_active')->default(true)->label(__('admin.fields.active')),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('platform')->label(__('admin.fields.platform'))->formatStateUsing(fn (?string $state): string => SocialLink::platforms()[$state] ?? (string) $state),
            TextColumn::make('url')->label(__('admin.fields.url'))->limit(40),
            Columns::active(),
            TextColumn::make('sort_order')->label(__('admin.fields.sort')),
        ])->defaultSort('sort_order')->recordActions([
            EditAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSocialLinks::route('/'),
            'create' => CreateSocialLink::route('/create'),
            'edit' => EditSocialLink::route('/{record}/edit'),
        ];
    }
}
