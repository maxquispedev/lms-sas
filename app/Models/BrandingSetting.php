<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Singleton de configuración de marca (logo + nombre).
 *
 * Nota: se usa como un registro único (id = 1).
 */
class BrandingSetting extends Model
{
    /** @var array<int, string> */
    protected $fillable = [
        'academy_name',
        'logo_path',
        'logo_alt',
        'certificate_background_path',
    ];
}

