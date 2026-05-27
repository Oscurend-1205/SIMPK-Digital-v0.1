<?php

namespace App\Filament\Resources\SertifikatKematianDewasas\Pages;

use App\Filament\Resources\SertifikatKematianDewasas\SertifikatKematianDewasaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSertifikatKematianDewasa extends EditRecord
{
    protected static string $resource = SertifikatKematianDewasaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
