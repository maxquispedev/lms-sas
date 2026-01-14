<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Course;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class PaymentSuccess extends Component
{
    public Course $course;
    public ?Order $order = null;

    /**
     * Mount the component.
     */
    public function mount(Course $course): void
    {
        $this->course = $course;

        // Get the latest order for this user and course
        $user = Auth::user();
        if ($user) {
            $this->order = Order::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->latest('created_at')
                ->first();
        }
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        return view('livewire.payment-success');
    }
}
