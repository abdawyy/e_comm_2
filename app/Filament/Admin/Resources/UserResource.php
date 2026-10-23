<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\Users\Pages\EditUser;
use App\Filament\Admin\Resources\Users\Pages\ListUsers;
use App\Filament\Admin\Resources\Users\Pages\ViewUser;
use App\Filament\Admin\Resources\Users\RelationManagers\OrdersRelationManager;
use App\Models\User;
use App\Services\CustomerInsights;
use App\Services\DecimalMath;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.groups.customers');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.users.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.users.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make(__('admin.forms.account'))->icon(Heroicon::OutlinedUser)->schema([
                TextInput::make('name')->label(__('admin.fields.name'))->required(),
                TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
                TextInput::make('phone')->label(__('admin.fields.phone'))->tel(),
                TextInput::make('address')->label(__('admin.fields.address'))->columnSpanFull(),
                TextInput::make('password')->password()->revealable()->dehydrated(fn ($state) => filled($state))->required(fn (string $operation) => $operation === 'create')->columnSpanFull(),
            ])->columns(2),
            Section::make(__('admin.forms.access'))->icon(Heroicon::OutlinedShieldCheck)->schema([
                Toggle::make('is_admin')->label(__('admin.fields.is_admin'))->inline(false)->visible(fn () => auth()->user()?->hasRole('super_admin')),
                Select::make('roles')->relationship('roles', 'name', modifyQueryUsing: function ($query) {
                    if (! auth()->user()?->hasRole('super_admin')) {
                        $query->where('name', '!=', 'super_admin');
                    }
                })->multiple()->preload()->native(false)->visible(fn () => auth()->user()?->hasRole('super_admin'))->label(__('admin.fields.roles')),
            ])->columns(2),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make(__('admin.insights.title'))->schema([
                TextEntry::make('name')->label(__('admin.fields.name')),
                TextEntry::make('email'),
                TextEntry::make('insights_locations')->label(__('admin.insights.locations'))->getStateUsing(fn (User $record) => implode(', ', app(CustomerInsights::class)->for($record)['locations'])),
                TextEntry::make('insights_devices')->label(__('admin.insights.devices'))->getStateUsing(fn (User $record) => collect(app(CustomerInsights::class)->for($record)['devices'])->map(fn ($count, $device) => $device.': '.$count)->implode(', ')),
                TextEntry::make('insights_sessions')->label(__('admin.insights.sessions'))->getStateUsing(fn (User $record) => (string) app(CustomerInsights::class)->for($record)['sessions']),
                TextEntry::make('insights_browse')->label(__('admin.insights.browse_hour'))->getStateUsing(fn (User $record) => (string) (app(CustomerInsights::class)->for($record)['usual_browse_hour'] ?? '—')),
                TextEntry::make('insights_purchase')->label(__('admin.insights.purchase_hour'))->getStateUsing(fn (User $record) => (string) (app(CustomerInsights::class)->for($record)['usual_purchase_hour'] ?? '—')),
                TextEntry::make('insights_spent')->label(__('admin.insights.spent'))->getStateUsing(fn (User $record) => app(DecimalMath::class)->format(app(CustomerInsights::class)->for($record)['total_spent'])),
                TextEntry::make('insights_viewed')->label(__('admin.insights.viewed'))->getStateUsing(fn (User $record) => collect(app(CustomerInsights::class)->for($record)['most_viewed'])->pluck('name')->implode(', ')),
                TextEntry::make('insights_bought')->label(__('admin.insights.purchased'))->getStateUsing(fn (User $record) => collect(app(CustomerInsights::class)->for($record)['most_purchased'])->pluck('name')->implode(', ')),
                ViewEntry::make('insights_chart')->view('filament.admin.customer-insights')->viewData(fn (User $record) => ['insights' => app(CustomerInsights::class)->for($record)]),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->searchable()->label(__('admin.fields.name')),
            TextColumn::make('email')->searchable(),
            IconColumn::make('is_admin')->boolean()->label(__('admin.fields.is_admin')),
            TextColumn::make('total_spent')->label(__('admin.insights.spent'))->getStateUsing(fn (User $record) => app(DecimalMath::class)->format(app(CustomerInsights::class)->for($record)['total_spent'])),
            TextColumn::make('last_order')->label(__('admin.insights.last_order'))->getStateUsing(fn (User $record) => optional(app(CustomerInsights::class)->for($record)['last_order'])->toDateTimeString()),
            TextColumn::make('last_location')->label(__('admin.insights.last_location'))->getStateUsing(fn (User $record) => app(CustomerInsights::class)->for($record)['last_location']),
            TextColumn::make('last_visit')->label(__('admin.insights.last_visit'))->getStateUsing(fn (User $record) => optional(app(CustomerInsights::class)->for($record)['last_visit'])->toDateTimeString()),
        ])->recordActions([
            ViewAction::make(),
            EditAction::make(),
        ]);
    }

    public static function getRelations(): array
    {
        return [
            OrdersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => \App\Filament\Admin\Resources\Users\Pages\CreateUser::route('/create'),
            'view' => ViewUser::route('/{record}'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
