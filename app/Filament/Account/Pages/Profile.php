<?php

namespace App\Filament\Account\Pages;

use App\Models\City;
use App\Support\Phone;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class Profile extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedUser;

    protected static ?string $slug = 'profile';

    public ?array $data = [];

    public static function getNavigationLabel(): string
    {
        return __('store.account.profile');
    }

    public function getTitle(): string
    {
        return __('store.account.profile');
    }

    public function mount(): void
    {
        $user = auth()->user();
        $this->form->fill([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'city_id' => $user->city_id,
            'address' => $user->address,
        ]);
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->required()->label(__('store.account.name')),
            TextInput::make('email')->email()->required()->label(__('store.account.email')),
            TextInput::make('phone')->label(__('store.account.phone')),
            Select::make('city_id')->label(__('store.checkout.city'))->options(fn () => City::query()->where('is_active', true)->get()->mapWithKeys(fn ($city) => [$city->id => $city->name])),
            TextInput::make('address')->label(__('store.checkout.address')),
            TextInput::make('password')->password()->label(__('store.account.password'))->dehydrated(fn ($state) => filled($state)),
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
        $user = auth()->user();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = Phone::normalize($data['phone'] ?? '');
        $user->city_id = $data['city_id'] ?? null;
        $user->address = $data['address'] ?? null;

        if (filled($data['password'] ?? null)) {
            $user->password = $data['password'];
        }

        $user->save();
        Notification::make()->title(__('store.account.saved'))->success()->send();
    }
}
