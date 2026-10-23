<?php

namespace App\Filament\Admin\Pages;

use App\Models\SiteTheme;
use App\Support\Contrast;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\ValidationException;

class EditSiteTheme extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedSwatch;

    protected static ?string $slug = 'site-theme';

    protected static ?int $navigationSort = 2;

    public ?array $data = [];

    public static function getNavigationGroup(): ?string
    {
        return __('admin.groups.design');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.pages.colors');
    }

    public function getTitle(): string
    {
        return __('admin.pages.colors');
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user && ($user->can('update', SiteTheme::current()) || $user->can('viewAny', SiteTheme::class));
    }

    public function mount(): void
    {
        abort_unless(static::canAccess(), 403);
        $this->form->fill(SiteTheme::current()->only([
            'brand_name', 'primary', 'primary_dark', 'primary_light', 'accent', 'accent_light',
            'canvas', 'surface', 'border', 'success', 'danger', 'theme_color',
        ]));
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        $color = fn (string $name) => ColorPicker::make($name)->required()->regex('/^#[0-9A-Fa-f]{6}$/')->live();

        return $schema->components([
            Section::make(__('admin.theme.brand'))->schema([
                TextInput::make('brand_name')->label(__('admin.fields.brand_name'))->required(),
            ]),
            Section::make(__('admin.theme.palette'))->schema([
                $color('primary')->label(__('admin.theme.primary')),
                $color('primary_dark')->label(__('admin.theme.primary_dark')),
                $color('primary_light')->label(__('admin.theme.primary_light')),
                $color('accent')->label(__('admin.theme.accent')),
                $color('accent_light')->label(__('admin.theme.accent_light')),
                $color('canvas')->label(__('admin.theme.canvas')),
                $color('surface')->label(__('admin.theme.surface')),
                $color('border')->label(__('admin.theme.border')),
                $color('success')->label(__('admin.theme.success')),
                $color('danger')->label(__('admin.theme.danger')),
                $color('theme_color')->label(__('admin.theme.theme_color')),
                Toggle::make('use_anyway')->label(__('admin.theme.use_anyway'))->helperText(__('admin.theme.contrast_help')),
            ])->columns(2),
            Placeholder::make('preview')->label(__('admin.theme.preview'))->content(function ($get) {
                return new HtmlString(view('filament.admin.theme-preview', [
                    'primary' => $get('primary'),
                    'accent' => $get('accent'),
                    'canvas' => $get('canvas'),
                    'surface' => $get('surface'),
                    'border' => $get('border'),
                ])->render());
            })->columnSpanFull(),
        ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Form::make([EmbeddedSchema::make('form')])
                ->id('form')
                ->livewireSubmitHandler('save')
                ->footer([
                    Actions::make([
                        Action::make('save')->label(__('admin.actions.save'))->submit('save'),
                    ]),
                ]),
        ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if (! Contrast::whiteOnPrimaryPasses((string) ($data['primary'] ?? '')) && empty($data['use_anyway'])) {
            Notification::make()->title(__('admin.theme.contrast_failed'))->danger()->send();

            throw ValidationException::withMessages([
                'data.primary' => __('admin.theme.contrast_failed'),
            ]);
        }

        $theme = SiteTheme::query()->first() ?? new SiteTheme;
        $theme->fill(collect($data)->except('use_anyway')->all());
        $theme->save();

        Notification::make()->title(__('admin.theme.saved'))->success()->send();
    }
}
