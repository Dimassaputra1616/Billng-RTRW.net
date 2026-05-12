<?php

namespace App\Filament\Resources\InternetPackages\Pages;

use App\Filament\Resources\InternetPackages\InternetPackageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInternetPackage extends CreateRecord
{
    protected static string $resource = InternetPackageResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
