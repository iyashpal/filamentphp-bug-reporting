<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MembershipResource\Pages;
use App\Filament\Supports\Helpers\HasResourceFields;
use App\Models\Membership;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MembershipResource extends Resource
{
    use HasResourceFields;

    protected static ?string $model = Membership::class;

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationIcon = 'heroicon-o-identification';


    public static function getNavigationBadge(): ?string
    {
        /** @var $membership Membership */
        $membership = static::getModel();
        return $membership::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('Belongs To')
                    ->columns(3)
                    ->schema(self::getFields(fields: ['user_id'])),

                Forms\Components\Fieldset::make('Membership')
                    ->schema(self::getFields(fields: ['join_date', 'membership_no', 'key_no', 'key_deposit_amount'])),

                Forms\Components\Fieldset::make('Options')
                    ->columns(3)
                    ->schema(self::getFields(fields: ['cops_event_no', 'working_bees', 'status'])),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::getColumns(columns: [
                'join_date',
                'membership_no',
                'avatar',
                'name',
                'first_name',
                'middle_name',
                'last_name',
                'email',
                'mobile',
                'phone',
                'date_of_birth',
                'gender',
                'address_street',
                'address_suburb',
                'address_state',
                'address_zip',
                'postal_street',
                'postal_suburb',
                'postal_state',
                'postal_zip',
                'cops_event_no',
                'working_bees',
                'key_no',
                'key_deposit_amount',
                'status',
                'created_at',
                'updated_at',
                'deleted_at'
            ]))
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMemberships::route('/'),
            'create' => Pages\CreateMembership::route('/create'),
            'edit' => Pages\EditMembership::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    /**
     * @return Forms\Components\Select
     */
    public static function getUserIdFormField(): Forms\Components\Select
    {
        return Forms\Components\Select::make('user_id')
            ->required()
            ->searchable()
            ->preload()
            ->relationship('user', 'name');
    }

    /**
     * @return Forms\Components\TextInput
     */
    public static function getMembershipNoFormField(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('membership_no')
            ->required()
            ->maxLength(255);
    }

    /**
     * @return Forms\Components\DatePicker
     */
    public static function getJoinDateFormField(): Forms\Components\DatePicker
    {
        return Forms\Components\DatePicker::make('join_date');
    }

    /**
     * @return Forms\Components\TextInput
     */
    public static function getCopsEventNoFormField(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('cops_event_no')
            ->maxLength(255)
            ->numeric();
    }

    /**
     * @return Forms\Components\TextInput
     */
    public static function getWorkingBeesFormField(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('working_bees')
            ->maxLength(255);
    }

    /**
     * @return Forms\Components\TextInput
     */
    public static function getKeyNoFormField(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('key_no')
            ->maxLength(255)
            ->numeric();
    }

    /**
     * @return Forms\Components\TextInput
     */
    public static function getKeyDepositAmountFormField(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('key_deposit_amount')
            ->maxLength(255)
            ->numeric();
    }

    /**
     * @return Forms\Components\Select
     */
    public static function getStatusFormField(): Forms\Components\Select
    {
        return Forms\Components\Select::make('status')
            ->default('active')
            ->options([
                'active' => 'Active',
                'cancelled' => 'Cancelled',
                'expired' => 'Expired'
            ]);
    }

    /**
     * @return Tables\Columns\TextColumn
     */
    public static function getNameTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('user.name')
            ->label('Name')
            ->toggleable(isToggledHiddenByDefault: true)
            ->searchable();
    }

    /**
     * @return Tables\Columns\TextColumn
     */
    public static function getFirstNameTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('user.first_name')
            ->label('First name')
            ->toggleable(isToggledHiddenByDefault: true)
            ->searchable();
    }

    /**
     * @return Tables\Columns\TextColumn
     */
    public static function getMiddleNameTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('user.middle_name')
            ->label('Middle name')
            ->toggleable(isToggledHiddenByDefault: true)
            ->searchable();
    }

    /**
     * @return Tables\Columns\TextColumn
     */
    public static function getLastNameTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('user.last_name')
            ->label('Last name')
            ->toggleable(isToggledHiddenByDefault: true)
            ->searchable();
    }


    /**
     * @return Tables\Columns\TextColumn
     */
    public static function getEmailTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('user.email')
            ->label('Email')
            ->searchable()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    /**
     * @return Tables\Columns\ImageColumn
     */
    public static function getAvatarTableColumn(): Tables\Columns\ImageColumn
    {
        return Tables\Columns\ImageColumn::make('user.avatar')
            ->label('Avatar')
            ->circular()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    /**
     * @return Tables\Columns\TextColumn
     */
    public static function getMobileTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('user.mobile')
            ->label('Mobile')
            ->searchable()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    /**
     * @return Tables\Columns\TextColumn
     */
    public static function getPhoneTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('user.phone')
            ->label('Phone')
            ->searchable()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    /**
     * @return Tables\Columns\TextColumn
     */
    public static function getDateOfBirthTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('user.date_of_birth')
            ->label('Date of birth')
            ->searchable()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    /**
     * @return Tables\Columns\TextColumn
     */
    public static function getGenderTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('user.gender')
            ->label('Gender')
            ->searchable()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    public static function getAddressStreetTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('user.address_street')
            ->searchable()
            ->sortable()
            ->label('Address street')
            ->toggleable(isToggledHiddenByDefault: true);
    }

    public static function getAddressSuburbTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('user.address_suburb')
            ->searchable()
            ->sortable()
            ->label('Address suburb')
            ->toggleable(isToggledHiddenByDefault: true);
    }

    public static function getAddressStateTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('user.address_state')
            ->searchable()
            ->sortable()
            ->label('Address state')
            ->toggleable(isToggledHiddenByDefault: true);
    }

    public static function getAddressZipTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('user.address_zip')
            ->searchable()
            ->sortable()
            ->label('Address zip')
            ->toggleable(isToggledHiddenByDefault: true);
    }

    public static function getPostalStreetTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('user.postal_street')
            ->searchable()
            ->sortable()
            ->label('Postal street')
            ->toggleable(isToggledHiddenByDefault: true);
    }

    public static function getPostalSuburbTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('user.postal_suburb')
            ->searchable()
            ->sortable()
            ->label('Postal suburb')
            ->toggleable(isToggledHiddenByDefault: true);
    }

    public static function getPostalStateTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('user.postal_state')
            ->searchable()
            ->sortable()
            ->label('Postal state')
            ->toggleable(isToggledHiddenByDefault: true);
    }

    public static function getPostalZipTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('user.postal_zip')
            ->searchable()
            ->sortable()
            ->label('Postal zip')
            ->toggleable(isToggledHiddenByDefault: true);
    }

    /**
     * @return Tables\Columns\TextColumn
     */
    public static function getMembershipNoTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('membership_no')
            ->toggleable()
            ->searchable()
            ->sortable();
    }

    /**
     * @return Tables\Columns\TextColumn
     */
    public static function getJoinDateTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('join_date')
            ->toggleable()
            ->searchable()
            ->sortable();
    }

    /**
     * @return Tables\Columns\TextColumn
     */
    public static function getCopsEventNoTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('cops_event_no')
            ->toggleable()
            ->searchable()
            ->sortable();
    }

    /**
     * @return Tables\Columns\TextColumn
     */
    public static function getWorkingBeesTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('working_bees')
            ->toggleable()
            ->searchable()
            ->sortable();
    }

    /**
     * @return Tables\Columns\TextColumn
     */
    public static function getKeyNoTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('key_no')
            ->toggleable()
            ->searchable()
            ->sortable();
    }

    /**
     * @return Tables\Columns\TextColumn
     */
    public static function getKeyDepositAmountTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('key_deposit_amount')
            ->toggleable()
            ->searchable()
            ->sortable();
    }

    /**
     * @return Tables\Columns\TextColumn
     */
    public static function getStatusTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('status')
            ->toggleable()
            ->sortable();
    }

    /**
     * @return Tables\Columns\TextColumn
     */
    public static function getCreatedAtTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('created_at')
            ->dateTime()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    /**
     * @return Tables\Columns\TextColumn
     */
    public static function getUpdatedAtTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('updated_at')
            ->dateTime()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    /**
     * @return Tables\Columns\TextColumn
     */
    public static function getDeletedAtTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('deleted_at')
            ->dateTime()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }
}
