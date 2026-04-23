<?php

namespace App\Filament\Resources\Admins\Pages;

use App\Filament\Resources\Admins\AdminResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAdmin extends CreateRecord
{
    protected static string $resource = AdminResource::class;

    protected array $pendingRoles = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->pendingRoles = $data['role_ids'] ?? [];
        unset($data['role_ids']);
        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->syncRoles($this->pendingRoles);
    }
}
