<?php

namespace App\Filament\Resources\KycVerifications\Tables;

use App\Mail\KycAprrovedMail;
use App\Models\KycVerification;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class KycVerificationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('User')->searchable(),
                TextColumn::make('country')->searchable(),
                BadgeColumn::make('status')
                    ->colors([
                        'success' => 'approved',
                        'danger' => 'rejected',
                        'warning' => 'pending',
                    ]),
                BadgeColumn::make('address')->color('info'),
                TextColumn::make('date_of_birth')
                    ->date()
                    ->sortable(),
                TextColumn::make('document_type'),
                TextColumn::make('reviewed_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')->label('Submitted')->dateTime(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('approve')
                        ->label('Approve')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (KycVerification $record) {
                            $record->update(['status' => 'approved']);
                            $record->user->update(['kyc_status' => 'approved']);
                            \Mail::to($record->user->email)->send(new KycAprrovedMail($record->user));
                        })
                        ->visible(fn($record) => $record->status === 'pending'),

                    Action::make('reject')
                        ->label('Reject')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function (KycVerification $record) {
                            $record->update(['status' => 'rejected']);
                            $record->user->update(['kyc_status' => 'rejected']);
                            \Mail::to($record->user->email)->send(new \App\Mail\KycRejectedMail($record->user));
                        })
                        ->visible(fn($record) => $record->status === 'pending'),

                        ViewAction::make(),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
