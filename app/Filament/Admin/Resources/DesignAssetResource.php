<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DesignAssets\Pages\CreateDesignAsset;
use App\Filament\Admin\Resources\DesignAssets\Pages\EditDesignAsset;
use App\Filament\Admin\Resources\DesignAssets\Pages\ListDesignAssets;
use App\Filament\Admin\Support\Columns;
use App\Filament\Admin\Support\Fields;
use App\Models\DesignAsset;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DesignAssetResource extends Resource
{
    protected static ?string $model = DesignAsset::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static ?string $slug = 'design-assets';

    public static function translatableFields(): array
    {
        return ['alt'];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.groups.design');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.resources.design_assets.nav');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.design_assets.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.design_assets.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make(__('admin.forms.media'))->description(__('admin.help.design_sizes'))->icon(Heroicon::OutlinedPhoto)->schema([
                Select::make('key')->label(__('admin.fields.key'))->options(array_combine(DesignAsset::KEYS, DesignAsset::KEYS))->required()->unique(ignoreRecord: true)->native(false),
                Toggle::make('is_active')->default(true)->label(__('admin.fields.active'))->inline(false),
                FileUpload::make('image_path')
                    ->label(__('admin.fields.image'))
                    ->disk('public')
                    ->directory('design')
                    ->imagePreviewHeight('180')
                    ->maxSize(4096)
                    ->acceptedFileTypes(fn ($get) => in_array($get('key'), ['logo', 'logo_dark', 'footer_mark'], true)
                        ? ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml']
                        : ['image/jpeg', 'image/png', 'image/webp'])
                    ->helperText(__('admin.help.design_sizes'))
                    ->columnSpanFull(),
                ...Fields::translated('alt', __('admin.fields.alt')),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('key')->label(__('admin.fields.key'))->searchable(),
            ImageColumn::make('preview')->label(__('admin.fields.image'))->state(fn (DesignAsset $record) => $record->url()),
            TextColumn::make('alt')->label(__('admin.fields.alt')),
            Columns::active(),
        ])->defaultGroup('key')->recordActions([
            EditAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDesignAssets::route('/'),
            'create' => CreateDesignAsset::route('/create'),
            'edit' => EditDesignAsset::route('/{record}/edit'),
        ];
    }
}
