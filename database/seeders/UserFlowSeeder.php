<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\CourseStatus;
use App\Enums\OrderStatus;
use App\Enums\PaymentGateway;
use App\Models\Category;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\Order;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserFlowSeeder extends Seeder
{
    /**
     * Seed the database with user flow test data.
     */
    public function run(): void
    {

        // ==========================================
        // 1. CREAR TU SUPER ADMIN (ESTO ES LO NUEVO)
        // ==========================================
        $role = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin = User::firstOrCreate(
            ['email' => 'hola@maxquispe.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('Nickmax1730*#'),
                'email_verified_at' => now(),
            ]
        );

        $this->command->warn("--- SUPER ADMIN CREADO: hola@maxquispe.com ---");
        $superAdmin->assignRole($role);

        // Create test user
        $user = User::firstOrCreate(
            ['email' => 'user@test.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
            ]
        );

        // Create teacher
        $teacher = User::firstOrCreate(
            ['email' => 'teacher@test.com'],
            [
                'name' => 'Test Teacher',
                'password' => Hash::make('password'),
            ]
        );

        // Create course
        $course = Course::firstOrCreate(
            ['slug' => 'test-course'],
            [
                'teacher_id' => $teacher->id,
                'title' => 'Test Course',
                'description' => 'Test Course Description',
                'price' => 99.99,
                'status' => CourseStatus::Published,
            ]
        );

        // Create some categories and attach to course
        $categories = [
            [
                'name' => 'Ambiental',
                'slug' => 'ambiental',
                'description' => 'Cursos relacionados con gestión y normativa ambiental.',
            ],
            [
                'name' => 'Seguridad y Salud',
                'slug' => 'seguridad-y-salud',
                'description' => 'Cursos de seguridad y salud en el trabajo.',
            ],
        ];

        foreach ($categories as $categoryData) {
            $category = Category::firstOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );

            if (! $course->categories()->where('categories.id', $category->id)->exists()) {
                $course->categories()->attach($category->id);
            }
        }

        // Create module
        $module = Module::firstOrCreate(
            [
                'course_id' => $course->id,
                'title' => 'Test Module',
            ],
            [
                'sort_order' => 1,
            ]
        );

        // Create lessons
        $lessons = [
            [
                'module_id' => $module->id,
                'title' => 'Lesson 1',
                'slug' => 'lesson-1',
                'content' => 'Content 1',
                'is_free' => false,
                'sort_order' => 1,
            ],
            [
                'module_id' => $module->id,
                'title' => 'Lesson 2',
                'slug' => 'lesson-2',
                'content' => 'Content 2',
                'is_free' => false,
                'sort_order' => 2,
            ],
            [
                'module_id' => $module->id,
                'title' => 'Lesson 3',
                'slug' => 'lesson-3',
                'content' => 'Content 3',
                'is_free' => false,
                'sort_order' => 3,
            ],
        ];

        foreach ($lessons as $lessonData) {
            Lesson::firstOrCreate(
                [
                    'module_id' => $lessonData['module_id'],
                    'slug' => $lessonData['slug'],
                ],
                $lessonData
            );
        }

        // Create order and enroll user in course
        $orderExists = Order::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->exists();

        if (!$orderExists) {
            $paymentService = app(PaymentService::class);
            $order = $paymentService->createManualOrder($user, $course, 'seeder-transaction-' . now()->timestamp);

            $this->command->info("Order created: #{$order->id} - Status: {$order->status->value}");
        } else {
            $this->command->info('User already enrolled in course');
        }

        $this->command->info('');
        $this->command->info('User flow test data seeded successfully!');
        $this->command->info("User: {$user->email} (password: password)");
        $this->command->info("Teacher: {$teacher->email} (password: password)");
        $this->command->info("Course: {$course->title} (slug: {$course->slug})");
        $this->command->info("User is enrolled in the course and can access lessons");
    }
}
