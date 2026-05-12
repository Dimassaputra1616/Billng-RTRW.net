<?php

namespace App\Filament\Resources\Settings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class SettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('key')
                    ->label('Kunci')
                    ->searchable()
                    ->weight('bold')
                    ->copyable(),
                \Filament\Tables\Columns\TextColumn::make('value')
                    ->label('Nilai')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->value),
                \Filament\Tables\Columns\TextColumn::make('group')
                    ->label('Grup')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'general' => 'info',
                        'mikrotik' => 'warning',
                        'wa' => 'success',
                        default => 'gray',
                    }),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('group')
                    ->label('Grup')
                    ->options([
                        'general' => 'General',
                        'mikrotik' => 'Mikrotik',
                        'wa' => 'WhatsApp',
                    ]),
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada pengaturan')
            ->emptyStateDescription('Tambahkan pengaturan sistem seperti koneksi Mikrotik dan WhatsApp.')
            ->emptyStateIcon('heroicon-o-cog-6-tooth');
    }
}
