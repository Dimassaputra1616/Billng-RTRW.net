<?php

namespace App\Filament\Resources\InternetPackages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class InternetPackagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('name')
                    ->label('Nama Paket')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                \Filament\Tables\Columns\TextColumn::make('speed_limit')
                    ->label('Kecepatan')
                    ->badge()
                    ->color('info')
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('price')
                    ->label('Harga / Bulan')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('customers_count')
                    ->counts('customers')
                    ->label('Pelanggan')
                    ->badge()
                    ->color('success'),
            ])
            ->filters([
                //
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada paket internet')
            ->emptyStateDescription('Buat paket internet pertama untuk mulai mendaftarkan pelanggan.')
            ->emptyStateIcon('heroicon-o-signal');
    }
}
