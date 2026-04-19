<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\TriggersPloiStaticDeploy;
use App\Support\HtmlRichContentHelper;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use HasFactory, SoftDeletes, TriggersPloiStaticDeploy;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'course_id',
        'title',
        'slug',
        'iframe_code',
        'content',
        'sort_order',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    /**
     * Contenido con adjuntos de archivo (PDF, Word) convertidos a enlaces de descarga.
     */
    protected function content(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value): string => HtmlRichContentHelper::fileAttachmentsToDownloadLinks($value),
        );
    }

    /**
     * Get the course that owns the module.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the lessons for the module.
     */
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }

    /**
     * Usuarios que han interactuado con este módulo (progreso por módulo).
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'module_user')
            ->withPivot(['completed', 'completed_at'])
            ->withTimestamps();
    }
}

