<?php

namespace App\Filament\Resources\SertifikatKematianDewasas;

use App\Filament\Resources\SertifikatKematianDewasas\Pages\CreateSertifikatKematianDewasa;
use App\Filament\Resources\SertifikatKematianDewasas\Pages\EditSertifikatKematianDewasa;
use App\Filament\Resources\SertifikatKematianDewasas\Pages\ListSertifikatKematianDewasas;
use App\Filament\Resources\SertifikatKematianDewasas\Schemas\SertifikatKematianDewasaForm;
use App\Filament\Resources\SertifikatKematianDewasas\Tables\SertifikatKematianDewasasTable;
use App\Models\SertifikatKematianDewasa;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SertifikatKematianDewasaResource extends Resource
{
    protected static ?string $model = SertifikatKematianDewasa::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return SertifikatKematianDewasaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SertifikatKematianDewasasTable::configure($table);
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
            'index' => ListSertifikatKematianDewasas::route('/'),
            'create' => CreateSertifikatKematianDewasa::route('/create'),
            'edit' => EditSertifikatKematianDewasa::route('/{record}/edit'),
        ];
    }
}
