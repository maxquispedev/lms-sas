<?php

namespace App\Filament\Resources\Admins\Pages;

use App\Filament\Resources\Admins\AdminResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAdmin extends EditRecord
{
    protected static string $resource = AdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['role_ids'] = $this->record->roles->pluck('name')->toArray();
        return $data;
    }

    protected array $pendingRoles = [];

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->pendingRoles = $data['role_ids'] ?? [];
        unset($data['role_ids']);
        return $data;
    }

    protected function afterSave(): void
    {
        $this->record->syncRoles($this->pendingRoles);
    }
}
