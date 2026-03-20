<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CourseStatus;
use App\Support\HtmlRichContentHelper;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'teacher_id',
        'title',
        'slug',
        'description',
        'access_text',
        'badge_label',
        'price',
        'sale_price',
        'image_url',
        'cover_type',
        'cover_video_embed',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'status' => CourseStatus::class,
        ];
    }

    /**
     * Precio efectivo a cobrar: sale_price si existe, sino price.
     */
    protected function effectivePrice(): Attribute
    {
        return Attribute::make(
            get: fn (): float => (float) ($this->sale_price ?? $this->price),
        );
    }

    /**
     * Texto editable que se muestra en el frontend (ej. "Acceso por 1 año").
     */
    protected function accessText(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value): string => match (true) {
                $value === null => 'Acceso por 1 año',
                trim($value) === '' => 'Acceso por 1 año',
                default => $value,
            },
        );
    }

    /**
     * Etiqueta destacada del curso para cards del frontend.
     */
    protected function badgeLabel(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value): string => match (true) {
                $value === null => 'Más demandado',
                trim($value) === '' => 'Más demandado',
                default => $value,
            },
        );
    }

    /**
     * Descripción con adjuntos de archivo (PDF, Word) convertidos a enlaces de descarga.
     */
    protected function description(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value): string => HtmlRichContentHelper::fileAttachmentsToDownloadLinks($value),
        );
    }

    /**
     * Indica si el curso tiene precio rebajado.
     */
    public function hasSalePrice(): bool
    {
        return $this->sale_price !== null && $this->sale_price !== '';
    }

    /**
     * Get the teacher that owns the course.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Get the public URL of the course cover image (with placeholder fallback).
     */
    protected function coverUrl(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->image_url
                ? Storage::disk('public')->url($this->image_url)
                : 'https://placehold.co/800x450?text=Curso'
        );
    }

    /**
     * Get the modules for the course.
     */
    public function modules(): HasMany
    {
        return $this->hasMany(Module::class);
    }

    /**
     * Categorías a las que pertenece el curso.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Get the students enrolled in the course.
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_user')
            ->withPivot(['status', 'enrolled_at', 'expires_at'])
            ->withTimestamps();
    }

    /**
     * Get all reviews for the course.
     */
    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }
}

