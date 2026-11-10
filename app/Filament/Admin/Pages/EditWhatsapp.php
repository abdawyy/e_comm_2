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

class EditWhatsapp extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static ?string $slug = 'whatsapp';

    protected static ?int $navigationSort = 8;

    public ?array $data = [];

    public static function getNavigationGroup(): ?string
    {
        return __('admin.groups.design');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.pages.whatsapp');
    }

    public function getTitle(): string
    {
        return __('admin.pages.whatsapp');
    }

    public static function canAccess(): bool
    {
        return (bool) auth()->user()?->can('Update:SiteTheme');
    }

    public function mount(): void
    {
        abort_unless(static::canAccess(), 403);
        $this->form->fill([
            'whatsapp_number' => StoreSetting::whatsappNumber(),
        ]);
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('admin.pages.whatsapp'))->schema([
                TextInput::make('whatsapp_number')
                    ->label(__('admin.fields.phone'))
                    ->tel()
                    ->maxLength(30)
                    ->helperText(__('store.whatsapp.help')),
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

        Notification::make()->title(__('store.whatsapp.saved'))->success()->send();
    }
}
