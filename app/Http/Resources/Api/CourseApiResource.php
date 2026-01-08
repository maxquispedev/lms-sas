<?php

declare(strict_types=1);

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'image_url' => $this->image_url ? url($this->image_url) : null,
            'description' => $this->description,
            'teacher' => $this->when($this->relationLoaded('teacher') && $this->teacher, [
                'name' => $this->teacher->name,
                'avatar' => $this->teacher->avatar_url ? url($this->teacher->avatar_url) : null,
            ]),
            'modules_count' => $this->whenLoaded('modules', function () {
                return $this->modules->count();
            }, 0),
        ];
    }
}

