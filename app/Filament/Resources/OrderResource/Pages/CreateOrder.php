<?php

declare(strict_types=1);

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Course;
use App\Models\Order;
use App\Models\User;
use App\Services\PaymentService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // Recuperar User y Course
        $user = User::findOrFail($data['user_id']);
        $course = Course::findOrFail($data['course_id']);

        // Inyectar PaymentService
        $paymentService = app(PaymentService::class);

        // Crear orden manual usando el servicio
        $transactionId = $data['transaction_id'] ?? null;
        $order = $paymentService->createManualOrder($user, $course, $transactionId);

        return $order;
    }
}

