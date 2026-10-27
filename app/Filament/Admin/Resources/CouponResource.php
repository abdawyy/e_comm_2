<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\Coupons\Pages\CreateCoupon;
use App\Filament\Admin\Resources\Coupons\Pages\EditCoupon;
use App\Filament\Admin\Resources\Coupons\Pages\ListCoupons;
use App\Filament\Admin\Support\Columns;
use App\Models\Coupon;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedTicket;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.groups.catalog');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.coupons.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.coupons.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make(__('admin.forms.offer'))->icon(Heroicon::OutlinedTicket)->schema([
                TextInput::make('code')->required()->unique(ignoreRecord: true)->label(__('admin.fields.code'))->extraInputAttributes(['style' => 'text-transform:uppercase']),
                Select::make('type')->options(['percent' => __('admin.fields.percent'), 'fixed' => __('admin.fields.fixed')])->required()->native(false)->label(__('admin.fields.type')),
                TextInput::make('value')->required()->label(__('admin.fields.value'))->rule('regex:/^\d+(\.\d{1,4})?$/'),
                Toggle::make('is_active')->onColor('success')->offColor('gray')->default(true)->label(__('admin.fields.active'))->inline(false),
            ])->columns(2),
            Section::make(__('admin.forms.limits'))->icon(Heroicon::OutlinedCalendar)->schema([
                TextInput::make('usage_limit')->numeric()->label(__('admin.fields.usage_limit')),
                TextInput::make('min_subtotal')->prefix(config('store.currency', 'EGP'))->label(__('admin.fields.min_subtotal')),
                DateTimePicker::make('starts_at')->label(__('admin.fields.starts_at'))->native(false),
                DateTimePicker::make('ends_at')->label(__('admin.fields.ends_at'))->native(false),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('code')->searchable()->label(__('admin.fields.code')),
            TextColumn::make('type')->label(__('admin.fields.type')),
            TextColumn::make('value')->label(__('admin.fields.value')),
            TextColumn::make('used_count')->label(__('admin.fields.used')),
            Columns::active(),
        ])->recordActions([EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCoupons::route('/'),
            'create' => CreateCoupon::route('/create'),
            'edit' => EditCoupon::route('/{record}/edit'),
        ];
    }
}
