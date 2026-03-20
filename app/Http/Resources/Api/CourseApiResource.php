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
            'access_text' => $this->access_text,
            'badge_label' => $this->badge_label,
            'price' => (float) $this->price,
            'sale_price' => $this->sale_price !== null ? (float) $this->sale_price : null,
            'effective_price' => (float) $this->effective_price,
            'image_url' => $this->image_url
                ? (str_starts_with($this->image_url, 'http')
                    ? $this->image_url
                    : Storage::disk('public')->url($this->image_url))
                : null,
            'cover_type' => $this->cover_type ?? 'image',
            'cover_video_embed' => $this->cover_video_embed,
            'trailer_embed_src' => $this->resolveTrailerEmbedSrc(),
            'description' => $this->description,
            'categories' => $this->whenLoaded('categories', function () {
                return $this->categories->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                        'slug' => $category->slug,
                    ];
                })->values();
            }, []),
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

    /**
     * Devuelve la URL segura para usar como src del iframe del trailer.
     * Acepta URL directa o HTML con iframe (extrae el src).
     */
    private function resolveTrailerEmbedSrc(): ?string
    {
        $raw = $this->cover_video_embed;
        if (empty($raw)) {
            return null;
        }
        $trimmed = trim($raw);
        if (str_starts_with($trimmed, 'http://') || str_starts_with($trimmed, 'https://')) {
            return $trimmed;
        }
        if (preg_match('/src\s*=\s*["\']([^"\']+)["\']/', (string) $raw, $m)) {
            return $m[1];
        }
        return null;
    }
}

