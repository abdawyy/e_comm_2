<?php

namespace App\Filament\Admin\Pages;

use App\Models\StoreSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
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

class EditInvoice extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $slug = 'invoice';

    protected static ?int $navigationSort = 7;

    public ?array $data = [];

    public static function getNavigationGroup(): ?string
    {
        return __('admin.groups.content');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.pages.invoice');
    }

    public function getTitle(): string
    {
        return __('admin.pages.invoice');
    }

    public static function canAccess(): bool
    {
        return (bool) auth()->user()?->can('Update:ContentString');
    }

    public function mount(): void
    {
        abort_unless(static::canAccess(), 403);
        $profile = StoreSetting::invoiceProfile();
        $this->form->fill([
            'paper' => $profile['paper'],
            'accent' => $profile['accent'],
            'card' => $profile['card'],
            'border' => $profile['border'],
            'show_qr' => $profile['show_qr'],
            'show_tax' => $profile['show_tax'],
            'tax_percent' => $profile['tax_percent'],
            'seller_phone' => $profile['seller_phone'],
            'seller_tax' => $profile['seller_tax'],
            'contact' => $profile['contact'],
            'address_ar' => $profile['ar']['address'],
            'address_en' => $profile['en']['address'],
            'title_ar' => $profile['ar']['title'] ?: __('store.invoice.title', [], 'ar'),
            'title_en' => $profile['en']['title'] ?: __('store.invoice.title', [], 'en'),
            'tagline_ar' => $profile['ar']['tagline'] ?: __('store.invoice.tagline', [], 'ar'),
            'tagline_en' => $profile['en']['tagline'] ?: __('store.invoice.tagline', [], 'en'),
            'policy_ar' => $profile['ar']['policy'] ?: __('store.invoice.policy', [], 'ar'),
            'policy_en' => $profile['en']['policy'] ?: __('store.invoice.policy', [], 'en'),
            'thanks_ar' => $profile['ar']['thanks'] ?: __('store.invoice.thanks', [], 'ar'),
            'thanks_en' => $profile['en']['thanks'] ?: __('store.invoice.thanks', [], 'en'),
        ]);
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('store.invoice.colors'))->schema([
                TextInput::make('paper')->label(__('store.invoice.paper'))->type('color')->required(),
                TextInput::make('accent')->label(__('store.invoice.accent'))->type('color')->required(),
                TextInput::make('card')->label(__('store.invoice.card'))->type('color')->required(),
                TextInput::make('border')->label(__('store.invoice.border'))->type('color')->required(),
            ])->columns(2),
            Section::make(__('store.invoice.shop'))->schema([
                TextInput::make('seller_phone')->label(__('store.invoice.phone'))->maxLength(40),
                TextInput::make('seller_tax')->label(__('store.invoice.tax_number'))->maxLength(40),
                TextInput::make('contact')->label(__('store.invoice.contact'))->maxLength(120),
                Textarea::make('address_ar')->label(__('store.invoice.address').' (AR)')->rows(2)->extraInputAttributes(['dir' => 'rtl']),
                Textarea::make('address_en')->label(__('store.invoice.address').' (EN)')->rows(2),
                Toggle::make('show_qr')->label(__('store.invoice.qr')),
                Toggle::make('show_tax')->label(__('store.invoice.tax')),
                TextInput::make('tax_percent')->label(__('store.invoice.tax_percent'))->helperText(__('store.invoice.tax_help')),
            ])->columns(2),
            Section::make('AR')->schema([
                TextInput::make('title_ar')->label(__('store.invoice.title'))->maxLength(80)->extraInputAttributes(['dir' => 'rtl']),
                TextInput::make('tagline_ar')->label(__('store.invoice.tagline'))->maxLength(120)->extraInputAttributes(['dir' => 'rtl']),
                Textarea::make('thanks_ar')->label(__('store.invoice.thanks'))->rows(2)->extraInputAttributes(['dir' => 'rtl']),
                Textarea::make('policy_ar')->label(__('store.invoice.policy'))->rows(3)->extraInputAttributes(['dir' => 'rtl']),
            ]),
            Section::make('EN')->schema([
                TextInput::make('title_en')->label(__('store.invoice.title'))->maxLength(80),
                TextInput::make('tagline_en')->label(__('store.invoice.tagline'))->maxLength(120),
                Textarea::make('thanks_en')->label(__('store.invoice.thanks'))->rows(2),
                Textarea::make('policy_en')->label(__('store.invoice.policy'))->rows(3),
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
        $setting = StoreSetting::query()->orderByDesc('id')->first() ?? new StoreSetting;
        $setting->invoice_pdf = [
            'paper' => $state['paper'],
            'accent' => $state['accent'],
            'card' => $state['card'],
            'border' => $state['border'],
            'show_qr' => (bool) ($state['show_qr'] ?? false),
            'show_tax' => (bool) ($state['show_tax'] ?? false),
            'tax_percent' => (string) ($state['tax_percent'] ?? '0'),
            'seller_phone' => (string) ($state['seller_phone'] ?? ''),
            'seller_tax' => (string) ($state['seller_tax'] ?? ''),
            'contact' => (string) ($state['contact'] ?? ''),
            'ar' => [
                'title' => (string) ($state['title_ar'] ?? ''),
                'tagline' => (string) ($state['tagline_ar'] ?? ''),
                'policy' => (string) ($state['policy_ar'] ?? ''),
                'thanks' => (string) ($state['thanks_ar'] ?? ''),
                'address' => (string) ($state['address_ar'] ?? ''),
            ],
            'en' => [
                'title' => (string) ($state['title_en'] ?? ''),
                'tagline' => (string) ($state['tagline_en'] ?? ''),
                'policy' => (string) ($state['policy_en'] ?? ''),
                'thanks' => (string) ($state['thanks_en'] ?? ''),
                'address' => (string) ($state['address_en'] ?? ''),
            ],
        ];
        $setting->save();

        Notification::make()->title(__('store.invoice.saved'))->success()->send();
    }
}
