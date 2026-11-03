<?php

namespace App\Filament\Admin\Resources\Orders\Pages;

use App\Actions\CreateOrderAction;
use App\DTO\CreateOrderPayload;
use App\Filament\Admin\Resources\OrderResource;
use App\Models\City;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    public function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make(__('admin.orders.customer'))->schema([
                Select::make('user_id')
                    ->label(__('admin.fields.customer'))
                    ->options(fn () => User::query()->orderBy('phone')->get()->mapWithKeys(fn (User $user) => [
                        $user->id => trim(($user->name ?: $user->phone).' — '.$user->phone),
                    ]))
                    ->searchable()
                    ->required()
                    ->native(false),
                Select::make('city_id')
                    ->label(__('admin.fields.city'))
                    ->options(fn () => City::query()->where('is_active', true)->orderBy('sort_order')->get()->mapWithKeys(fn (City $city) => [$city->id => $city->name]))
                    ->searchable()
                    ->required()
                    ->native(false),
                Textarea::make('address')->label(__('admin.fields.address'))->rows(2),
                Textarea::make('notes')->label(__('admin.fields.notes'))->rows(2),
            ])->columns(2),
            Section::make(__('admin.fields.items'))->schema([
                Repeater::make('lines')->schema([
                    Select::make('product_id')
                        ->label(__('admin.resources.products.label'))
                        ->options(fn () => Product::query()->forAdmin()->orderBy('sku')->get()->mapWithKeys(fn (Product $product) => [
                            $product->id => $product->sku.' — '.$product->name,
                        ]))
                        ->searchable()
                        ->required()
                        ->native(false),
                    TextInput::make('quantity')->label(__('admin.fields.quantity'))->required()->default('1'),
                ])->minItems(1)->defaultItems(1)->columns(2)->columnSpanFull(),
            ]),
        ]);
    }

    protected function handleRecordCreation(array $data): Model
    {
        $user = User::query()->findOrFail($data['user_id']);
        $lines = [];

        foreach ($data['lines'] as $line) {
            $product = Product::query()->findOrFail($line['product_id']);
            $lines[] = [
                'kind' => 'product',
                'product_id' => $product->id,
                'quantity' => $line['quantity'],
                'unit' => $product->unit,
            ];
        }

        return app(CreateOrderAction::class)->execute(new CreateOrderPayload(
            userId: $user->id,
            customerName: $user->name ?: $user->phone,
            customerEmail: $user->email,
            customerPhone: $user->phone,
            cityId: (int) $data['city_id'],
            address: filled($data['address'] ?? null) ? $data['address'] : ($user->address ?: '-'),
            paymentGateway: 'cod',
            lines: $lines,
            notes: $data['notes'] ?? null,
            locale: app()->getLocale(),
            includeInternal: true,
        ));
    }

    protected function getRedirectUrl(): string
    {
        return OrderResource::getUrl('view', ['record' => $this->getRecord()]);
    }
}
