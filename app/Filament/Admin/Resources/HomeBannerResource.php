<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\HomeBanners\Pages\CreateHomeBanner;
use App\Filament\Admin\Resources\HomeBanners\Pages\EditHomeBanner;
use App\Filament\Admin\Resources\HomeBanners\Pages\ListHomeBanners;
use App\Filament\Admin\Support\Columns;
use App\Filament\Admin\Support\Fields;
use App\Models\HomeBanner;
use Filament\Actions\EditAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HomeBannerResource extends Resource
{
    protected static ?string $model = HomeBanner::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    public static function translatableFields(): array
    {
        return ['title', 'subtitle', 'badge'];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.groups.content');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.banners.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.banners.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make(__('admin.forms.details'))->description(__('admin.forms.details_help'))->icon(Heroicon::OutlinedLanguage)->schema([
                ...Fields::translated('title', __('admin.fields.title'), true),
                ...Fields::translated('subtitle', __('admin.fields.subtitle')),
                ...Fields::translated('badge', __('admin.fields.badge')),
            ])->columns(2),
            Section::make(__('admin.forms.appearance'))->icon(Heroicon::OutlinedSwatch)->schema([
                FileUpload::make('image_path')->disk('public')->directory('banners')->image()->imagePreviewHeight('180')->maxSize(4096)->helperText(__('admin.help.banner'))->label(__('admin.fields.image'))->columnSpanFull(),
                TextInput::make('image_url')->label(__('admin.fields.image_url'))->url()->columnSpanFull(),
                ColorPicker::make('gradient_start')->label(__('admin.fields.gradient_start')),
                ColorPicker::make('gradient_end')->label(__('admin.fields.gradient_end')),
                Repeater::make('buttons')->schema([
                    TextInput::make('label.en')->label(__('admin.fields.label').' EN')->required(),
                    TextInput::make('label.ar')->label(__('admin.fields.label').' AR')->extraInputAttributes(['dir' => 'rtl'])->required(),
                    TextInput::make('url')->url()->required()->label(__('admin.fields.url'))->columnSpanFull(),
                ])->columns(2)->label(__('admin.fields.buttons'))->columnSpanFull(),
            ])->columns(2),
            Section::make(__('admin.forms.schedule'))->icon(Heroicon::OutlinedCalendar)->schema([
                DateTimePicker::make('starts_at')->label(__('admin.fields.starts_at'))->native(false),
                DateTimePicker::make('ends_at')->label(__('admin.fields.ends_at'))->native(false),
                TextInput::make('sort_order')->numeric()->default(0)->label(__('admin.fields.sort')),
                Toggle::make('is_active')->default(true)->label(__('admin.fields.active'))->inline(false),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('title')->label(__('admin.fields.title')),
            TextColumn::make('sort_order')->label(__('admin.fields.sort')),
            Columns::active(),
        ])->recordActions([EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHomeBanners::route('/'),
            'create' => CreateHomeBanner::route('/create'),
            'edit' => EditHomeBanner::route('/{record}/edit'),
        ];
    }
}
