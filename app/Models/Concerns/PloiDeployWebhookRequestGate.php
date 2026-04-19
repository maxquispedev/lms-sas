<?php

declare(strict_types=1);

namespace App\Models\Concerns;

/**
 * Evita encolar varias veces el mismo webhook en una sola petición HTTP
 * (p. ej. al guardar un curso con muchas lecciones/módulos).
 *
 * En servidores de proceso largo (p. ej. Laravel Octane) habría que reiniciar
 * este estado por petición; en PHP-FPM/CGI cada request es un proceso nuevo.
 */
final class PloiDeployWebhookRequestGate
{
    private static bool $queued = false;

    public static function runOnce(callable $callback): void
    {
        if (self::$queued) {
            return;
        }

        self::$queued = true;
        $callback();
    }
}
