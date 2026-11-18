<?php

namespace App\Filament\Admin\Pages;

use App\Models\StoreSetting;
use Filament\Actions\Action;
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

class EditInstapay extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?string $slug = 'instapay';

    protected static ?int $navigationSort = 4;

    public ?array $data = [];

    public static function getNavigationGroup(): ?string
    {
        return __('admin.groups.sales');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.pages.instapay');
    }

    public function getTitle(): string
    {
        return __('admin.pages.instapay');
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return (bool) ($user?->can('Update:SiteTheme') || $user?->can('Update:Order'));
    }

    public function mount(): void
    {
        abort_unless(static::canAccess(), 403);
        $this->form->fill([
            'instapay_number' => StoreSetting::instapayNumber(),
            'instapay_active' => StoreSetting::instapayActive(),
        ]);
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('admin.pages.instapay'))->schema([
                TextInput::make('instapay_number')
                    ->label(__('admin.fields.instapay_number'))
                    ->maxLength(40)
                    ->helperText(__('admin.help.instapay_number')),
                Toggle::make('instapay_active')
                    ->label(__('admin.fields.active'))
                    ->onColor('success')
                    ->offColor('gray')
                    ->helperText(__('admin.help.instapay_active')),
            ]),
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
        $state = $this->form->getState();
        $setting = StoreSetting::query()->first() ?? new StoreSetting;
        $setting->instapay_number = trim((string) ($state['instapay_number'] ?? ''));
        $setting->instapay_active = (bool) ($state['instapay_active'] ?? false);
        $setting->save();

        Notification::make()->title(__('admin.instapay.saved'))->success()->send();
    }
}
