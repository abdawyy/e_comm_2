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

class EditAnnouncement extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    protected static ?string $slug = 'announcement';

    protected static ?int $navigationSort = 6;

    public ?array $data = [];

    public static function getNavigationGroup(): ?string
    {
        return __('admin.groups.content');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.pages.announcement');
    }

    public function getTitle(): string
    {
        return __('admin.pages.announcement');
    }

    public static function canAccess(): bool
    {
        return (bool) auth()->user()?->can('Update:ContentString');
    }

    public function mount(): void
    {
        abort_unless(static::canAccess(), 403);
        $row = StoreSetting::query()->orderByDesc('id')->first();
        $this->form->fill([
            'announcement_active' => (bool) ($row?->announcement_active),
            'announcement_en' => $row?->announcement_en,
            'announcement_ar' => $row?->announcement_ar,
        ]);
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('admin.pages.announcement'))->schema([
                Toggle::make('announcement_active')->label(__('admin.fields.active'))->default(false),
                TextInput::make('announcement_ar')->label(__('store.announcement.text').' (AR)')->maxLength(190)->extraInputAttributes(['dir' => 'rtl']),
                TextInput::make('announcement_en')->label(__('store.announcement.text').' (EN)')->maxLength(190),
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
        $setting = StoreSetting::query()->orderByDesc('id')->first() ?? new StoreSetting;
        $setting->fill($this->form->getState());
        $setting->save();

        Notification::make()->title(__('store.announcement.saved'))->success()->send();
    }
}
