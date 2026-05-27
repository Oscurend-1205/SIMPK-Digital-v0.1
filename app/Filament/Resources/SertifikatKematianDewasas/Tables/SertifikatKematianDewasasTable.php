<?php

namespace App\Filament\Resources\SertifikatKematianDewasas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class SertifikatKematianDewasasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama Jenazah')
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->label('L/P')
                    ->badge(),
                \Filament\Tables\Columns\TextColumn::make('waktu_meninggal')
                    ->label('Waktu Kematian')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('nama_dokter')
                    ->label('Dokter Pengesah')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
