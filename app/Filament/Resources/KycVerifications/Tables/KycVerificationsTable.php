<?php

namespace App\Filament\Resources\KycVerifications\Tables;

use App\Mail\KycApprovedMail;
use App\Mail\KycRejectedMail;
use App\Models\KycVerification;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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
                            DB::transaction(function() use($record) {
                                $record->update(['status' => 'approved', 'reviewed_at' => now()]);
                                $record->user->update(['kyc_status' => 'approved']);
                                \Mail::to($record->user->email)->send(new KycApprovedMail($record->user));
                            });
                        })
                        ->visible(fn($record) => $record->status === 'pending'),

                    Action::make('reject')
                        ->label('Reject')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->form([
                            Textarea::make('reason')
                                ->label('Rejection Reason')
                                ->placeholder('Enter reason for rejecting this KYC submission')
                                ->required()
                                ->rows(3),
                        ])
                        ->action(function (KycVerification $record, array $data) {
                            DB::transaction(function() use ($record, $data) {
                                // Update KYC record and user status
                                $record->update([
                                    'status' => 'rejected',
                                    'reviewed_at' => now()
                                    // 'rejection_reason' => $data['reason'] ?? null,
                                ]);

                                $record->user->update([
                                    'kyc_status' => 'rejected',
                                ]);

                                // Send mail with reason
                                Mail::to($record->user->email)
                                    ->send(new KycRejectedMail($record->user, $data['reason'] ?? null));
                            });
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
