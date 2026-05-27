<?php

namespace App\Filament\Resources\SertifikatKematianDewasas\Pages;

use App\Filament\Resources\SertifikatKematianDewasas\SertifikatKematianDewasaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSertifikatKematianDewasas extends ListRecords
{
    protected static string $resource = SertifikatKematianDewasaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
