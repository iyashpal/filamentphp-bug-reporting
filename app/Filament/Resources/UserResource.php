<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers\{BranchesRelationManager, ClubsRelationManager, MembershipsRelationManager, OrganizationsRelationManager};
use App\Filament\Supports\Helpers\HasResourceFields;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    use HasResourceFields;

    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        /** @var $user User */
        $user = static::getModel();

        return $user::count();
    }

    public static function form(Form $form): Form
    {
        return $form->schema(static::getDefaultFormLayout(form: $form));
    }

    public static function table(Table $table): Table
    {
        return $table->columns(static::getDefaultTableColumns(table: $table))
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ])->tooltip('Options')

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
            MembershipsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getDefaultFormLayout(Form $form)
    {
        return [
            Forms\Components\Grid::make(3)->schema([
                Forms\Components\Group::make([
                    Forms\Components\Fieldset::make('User')
                        ->columns(1)
                        ->schema([
                            Forms\Components\Group::make()->columns(3)
                                ->schema(self::getFields(fields: ['first_name', 'middle_name', 'last_name'], form: $form)),

                            Forms\Components\Group::make()->columns(2)
                                ->schema(self::getFields(fields: ['gender', 'date_of_birth'], form: $form))
                        ]),

                    Forms\Components\Fieldset::make('Address')
                        ->columns(3)
                        ->schema(self::getFields(fields: ['address_street' => fn(Forms\Components\TextInput $input) => $input->columnSpanFull(), 'address_suburb', 'address_state', 'address_zip'], form: $form)),

                    Forms\Components\Fieldset::make('Postal Address')
                        ->columns(3)
                        ->schema(self::getFields(fields: ['postal_street' => fn(Forms\Components\TextInput $input) => $input->columnSpanFull(), 'postal_suburb', 'postal_state', 'postal_zip'], form: $form)),

                    Forms\Components\Fieldset::make('Security')
                        ->schema(self::getFields(fields: ['password', 'password_confirmation'], form: $form)),

                ])->columnSpan(2),

                Forms\Components\Group::make([
                    Forms\Components\Fieldset::make('Avatar')->schema([
                        static::getAvatarFormField($form)->hiddenLabel()->columnSpanFull()
                    ]),
                    Forms\Components\Fieldset::make('Contact')
                        ->columns(1)
                        ->schema(self::getFields(fields: ['email', 'mobile', 'phone'], form: $form)),
                    Forms\Components\Fieldset::make('Metadata')
                        ->columns(1)
                        ->schema(self::getFields(fields: ['email_verified_at'], form: $form)),
                ]),
            ])->columnSpanFull()
        ];
    }


    /**
     * Get name form field.
     */
    public static function getNameFormField(?Form $form): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('name')
            ->required()
            ->maxLength(255);
    }

    /**
     * @return Forms\Components\TextInput
     */
    public static function getFirstNameFormField(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('first_name')->required();
    }

    /**
     * @return Forms\Components\TextInput
     */
    public static function getMiddleNameFormField(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('middle_name');
    }

    /**
     * @return Forms\Components\TextInput
     */
    public static function getLastNameFormField(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('last_name');
    }

    /**
     * Get email form field.
     */
    public static function getEmailFormField(?Form $form): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('email')
            ->email()
            ->required()
            ->maxLength(255);
    }


    /**
     * Get email form field.
     */
    public static function getMobileFormField(?Form $form): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('mobile')
            ->maxLength(255);
    }

    /**
     * Get email form field.
     */
    public static function getPhoneFormField(?Form $form): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('phone')
            ->maxLength(255);
    }


    /**
     * Get email form field.
     */
    public static function getDateOfBirthFormField(?Form $form): Forms\Components\DatePicker
    {
        return Forms\Components\DatePicker::make('date_of_birth');
    }

    public static function getAddressStreetFormField(?Form $form): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('address_street');
    }


    public static function getAddressSuburbFormField(?Form $form): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('address_suburb');
    }


    public static function getAddressStateFormField(?Form $form): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('address_state');
    }


    public static function getAddressZipFormField(?Form $form): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('address_zip');
    }


    public static function getPostalStreetFormField(?Form $form): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('postal_street');
    }


    public static function getPostalSuburbFormField(?Form $form): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('postal_suburb');
    }


    public static function getPostalStateFormField(?Form $form): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('postal_state');
    }


    public static function getPostalZipFormField(?Form $form): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('postal_zip');
    }

    /**
     * Get email form field.
     */
    public static function getGenderFormField(?Form $form): Forms\Components\Select
    {
        return Forms\Components\Select::make('gender')->options([
            'male' => 'Male',
            'female' => 'Female',
            'other' => 'Other'
        ]);
    }

    /**
     * Get email_verified_at field.
     */
    public static function getEmailVerifiedAtFormField(?Form $form): Forms\Components\DateTimePicker
    {
        return Forms\Components\DateTimePicker::make('email_verified_at');
    }

    /**
     * Get avatar field.
     */
    private static function getAvatarFormField(?Form $form): Forms\Components\FileUpload
    {
        return Forms\Components\FileUpload::make('avatar')
            ->previewable()
            ->moveFiles();
    }

    /**
     * Get password form field.
     */
    public static function getPasswordFormField(?Form $form): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('password')
            ->password()
            ->confirmed()
            ->maxLength(255)
            ->required(self::routeNameIncludes('users.create'));
    }

    /**
     * Get password_confirmation form field.
     */
    public static function getPasswordConfirmationFormField(?Form $form): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('password_confirmation')
            ->password()
            ->maxLength(255)
            ->required(self::routeNameIncludes('users.create'));
    }


    /**
     * Get the default table columns.
     */
    public static function getDefaultTableColumns(?Table $table): array
    {
        return self::getColumns(columns: [
            'avatar',
            'name',
            'first_name',
            'middle_name',
            'last_name',
            'email',
            'email_verified_at',
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
            'created_at',
            'updated_at',
            'deleted_at'
        ]);
    }

    public static function getNameTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('name')
            ->searchable()
            ->sortable()
            ->toggleable();
    }


    public static function getFirstNameTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('first_name')
            ->searchable()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }


    public static function getMiddleNameTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('middle_name')
            ->searchable()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }


    public static function getLastNameTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('last_name')
            ->searchable()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    public static function getEmailTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('email')
            ->searchable()
            ->sortable()
            ->toggleable();
    }

    public static function getEmailVerifiedAtTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('email_verified_at')
            ->dateTime()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    public static function getMobileTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('mobile')
            ->searchable()
            ->sortable()
            ->toggleable();
    }

    public static function getPhoneTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('phone')
            ->searchable()
            ->sortable()
            ->toggleable();
    }


    public static function getDateOfBirthTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('date_of_birth')
            ->searchable()
            ->sortable()
            ->toggleable();
    }


    public static function getGenderTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('gender')
            ->searchable()
            ->sortable()
            ->toggleable();
    }

    public static function getAddressStreetTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('address_street')
            ->searchable()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    public static function getAddressSuburbTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('address_suburb')
            ->searchable()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    public static function getAddressStateTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('address_state')
            ->searchable()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    public static function getAddressZipTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('address_zip')
            ->searchable()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    public static function getPostalStreetTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('postal_street')
            ->searchable()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    public static function getPostalSuburbTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('postal_suburb')
            ->searchable()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    public static function getPostalStateTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('postal_state')
            ->searchable()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    public static function getPostalZipTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('postal_zip')
            ->searchable()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    public static function getCreatedAtTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('created_at')
            ->dateTime()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    public static function getUpdatedAtTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('updated_at')
            ->dateTime()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    public static function getDeletedAtTableColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('deleted_at')
            ->dateTime()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    private static function getAvatarTableColumn()
    {
        return Tables\Columns\ImageColumn::make('avatar')
            ->circular()
            ->toggleable();
    }
}
