<?php

declare(strict_types=1);

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CourseApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'price' => (float) $this->price,
            'image_url' => $this->image_url 
                ? (str_starts_with($this->image_url, 'http') 
                    ? $this->image_url 
                    : Storage::disk('public')->url($this->image_url))
                : null,
            'description' => $this->description,
            'teacher' => $this->when($this->relationLoaded('teacher') && $this->teacher, [
                'name' => $this->teacher->name,
                'avatar_url' => $this->teacher->avatar_url 
                    ? (str_starts_with($this->teacher->avatar_url, 'http')
                        ? $this->teacher->avatar_url
                        : Storage::disk('public')->url($this->teacher->avatar_url))
                    : null,
            ]),
            'modules' => $this->whenLoaded('modules', function () {
                return $this->modules->map(function ($module) {
                    return [
                        'id' => $module->id,
                        'title' => $module->title,
                        'sort_order' => $module->sort_order,
                        'lessons' => $module->lessons->map(function ($lesson) {
                            return [
                                'id' => $lesson->id,
                                'title' => $lesson->title,
                                'slug' => $lesson->slug,
                                'duration' => $lesson->duration ?? null,
                                'is_free' => $lesson->is_free,
                                'sort_order' => $lesson->sort_order,
                            ];
                        })->sortBy('sort_order')->values(),
                    ];
                })->sortBy('sort_order')->values();
            }, []),
        ];
    }
}

