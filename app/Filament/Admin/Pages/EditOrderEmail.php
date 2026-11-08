<?php

namespace App\Filament\Admin\Pages;

use App\Models\StoreSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class EditOrderEmail extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static ?string $slug = 'order-email';

    protected static ?int $navigationSort = 15;

    public ?array $data = [];

    public static function getNavigationGroup(): ?string
    {
        return __('admin.groups.sales');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.pages.order_email');
    }

    public function getTitle(): string
    {
        return __('admin.pages.order_email');
    }

    public static function canAccess(): bool
    {
        return (bool) auth()->user()?->can('Update:Order');
    }

    public function mount(): void
    {
        abort_unless(static::canAccess(), 403);
        $this->form->fill([
            'order_email' => StoreSetting::orderEmail(),
        ]);
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('admin.mail.order_email'))->schema([
                TextInput::make('order_email')
                    ->label(__('admin.fields.email'))
                    ->email()
                    ->required()
                    ->maxLength(190)
                    ->helperText(__('admin.mail.order_email_help')),
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
        $setting = StoreSetting::query()->first() ?? new StoreSetting;
        $setting->fill($this->form->getState());
        $setting->save();

        Notification::make()->title(__('admin.mail.order_email_saved'))->success()->send();
    }
}
