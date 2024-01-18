<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Resources\MembershipResource;
use App\Filament\Resources\UserResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MembershipsRelationManager extends RelationManager
{
    protected static string $relationship = 'memberships';

    protected static ?string $icon = 'heroicon-o-identification';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('Membership')
                    ->schema(MembershipResource::getFields(fields: ['join_date', 'membership_no', 'key_no', 'key_deposit_amount'])),

                Forms\Components\Fieldset::make('Options')
                    ->columns(3)
                    ->schema(MembershipResource::getFields(fields: ['cops_event_no', 'working_bees', 'status'])),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('membership_no')
            ->columns(MembershipResource::getColumns(columns: [
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
            ->headerActions([
                Tables\Actions\CreateAction::make()->icon('heroicon-o-plus-circle')->slideOver(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()->slideOver(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
