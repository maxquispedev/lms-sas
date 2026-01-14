<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentGateway;
use App\Models\Course;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function __construct(
        private readonly EnrollmentService $enrollmentService
    ) {
    }

    /**
     * Create a manual order and enroll the user in the course.
     *
     * @param User $user The user making the purchase
     * @param Course $course The course being purchased
     * @param string|null $transactionRef Optional transaction reference
     * @return Order The created order
     */
    public function createManualOrder(User $user, Course $course, ?string $transactionRef = null): Order
    {
        return DB::transaction(function () use ($user, $course, $transactionRef): Order {
            // Step 1: Create Order
            $order = Order::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'total_amount' => $course->price,
                'status' => OrderStatus::Paid,
                'payment_gateway' => PaymentGateway::Manual,
                'transaction_id' => $transactionRef,
            ]);

            // Step 2: Enroll user in the course
            $this->enrollmentService->enrollUser($user, $course);

            return $order;
        });
    }

    /**
     * Create an order for a Culqi payment and enroll the user in the course.
     *
     * @param User $user The user making the purchase
     * @param Course $course The course being purchased
     * @param string $transactionId The Culqi charge ID
     * @return Order The created order
     */
    public function createCulqiOrder(User $user, Course $course, string $transactionId): Order
    {
        return DB::transaction(function () use ($user, $course, $transactionId): Order {
            // Step 1: Create Order
            $order = Order::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'total_amount' => $course->price,
                'status' => OrderStatus::Paid,
                'payment_gateway' => PaymentGateway::Culqi,
                'transaction_id' => $transactionId,
            ]);

            // Step 2: Enroll user in the course
            $this->enrollmentService->enrollUser($user, $course);

            return $order;
        });
    }
}

