<?php

namespace App\Filament\Admin\Pages;

use App\Models\Coupon;
use App\Models\SignupOffer;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
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

class EditSignupOffer extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    protected static ?string $slug = 'signup-offer';

    protected static ?int $navigationSort = 4;

    public ?array $data = [];

    public static function getNavigationGroup(): ?string
    {
        return __('admin.groups.catalog');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.pages.signup_offer');
    }

    public function getTitle(): string
    {
        return __('admin.pages.signup_offer');
    }

    public static function canAccess(): bool
    {
        return (bool) auth()->user()?->can('Update:Coupon');
    }

    public function mount(): void
    {
        abort_unless(static::canAccess(), 403);
        $offer = SignupOffer::current() ?? new SignupOffer([
            'is_active' => true,
            'delay_seconds' => 15,
        ]);
        $this->form->fill($offer->only([
            'is_active', 'delay_seconds', 'coupon_id', 'heading_en', 'heading_ar', 'body_en', 'body_ar',
        ]));
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('admin.signup.timing'))->schema([
                Toggle::make('is_active')->label(__('admin.fields.active'))->inline(false),
                TextInput::make('delay_seconds')->label(__('admin.signup.delay'))->numeric()->required()->minValue(1)->maxValue(600)->suffix(__('admin.signup.seconds')),
                Select::make('coupon_id')->label(__('admin.signup.coupon'))->options(fn () => Coupon::query()->orderBy('code')->pluck('code', 'id'))->searchable()->required()->native(false),
            ])->columns(2),
            Section::make(__('admin.signup.copy'))->schema([
                TextInput::make('heading_en')->label(__('admin.signup.heading').' (EN)')->required(),
                TextInput::make('heading_ar')->label(__('admin.signup.heading').' (AR)')->required()->extraInputAttributes(['dir' => 'rtl']),
                Textarea::make('body_en')->label(__('admin.signup.body').' (EN)')->required()->rows(3),
                Textarea::make('body_ar')->label(__('admin.signup.body').' (AR)')->required()->rows(3)->extraInputAttributes(['dir' => 'rtl']),
            ])->columns(2),
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
        $offer = SignupOffer::query()->first() ?? new SignupOffer;
        $offer->fill($this->form->getState());
        $offer->save();

        Notification::make()->title(__('admin.signup.saved'))->success()->send();
    }
}
