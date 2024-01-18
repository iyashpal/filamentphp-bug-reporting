<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->icon('heroicon-o-pencil-square'),
            Actions\DeleteAction::make()->icon('heroicon-o-trash'),
            Actions\RestoreAction::make()->icon('heroicon-o-arrow-uturn-left'),
            Actions\ForceDeleteAction::make()->icon('heroicon-o-trash'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Split::make([
                Section::make('Details')
                    ->columns(2)
                    ->grow()
                    ->description('Personal details of the user')
                    ->schema([
                        TextEntry::make('name')->weight(FontWeight::Bold),
                        TextEntry::make('email')->weight(FontWeight::Bold),
                        IconEntry::make('email_verified_at')->boolean(),
                        TextEntry::make('created_at')->weight(FontWeight::Bold),
                        TextEntry::make('updated_at')->weight(FontWeight::Bold),
                        TextEntry::make('deleted_at')->weight(FontWeight::Bold)->color(Color::Red),
                    ]),
                Section::make('Avatar')
                    ->grow(false)
                    ->description('The uploaded profile photo.')
                    ->schema([
                        ImageEntry::make('avatar')
                            ->hiddenLabel()
                            ->circular()
                            ->height(220)
                            ->extraImgAttributes([
                                'alt' => 'Profile Image',
                            ])
                    ]),
            ])->columnSpanFull(),
        ]);
    }
}
