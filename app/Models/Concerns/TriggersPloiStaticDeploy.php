<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Http;

/**
 * Dispara el webhook de despliegue del sitio estático (Ploi) tras cambios en datos públicos.
 *
 * Laravel invoca automáticamente {@see bootTriggersPloiStaticDeploy()} al arrancar el modelo,
 * equivalente a registrar los mismos callbacks dentro de {@see Model::booted()}.
 */
trait TriggersPloiStaticDeploy
{
    protected static function bootTriggersPloiStaticDeploy(): void
    {
        $webhookUrl = config('services.ploi.deploy_webhook_url');

        if (! is_string($webhookUrl) || $webhookUrl === '') {
            return;
        }

        $schedule = static function () use ($webhookUrl): void {
            PloiDeployWebhookRequestGate::runOnce(function () use ($webhookUrl): void {
                dispatch(
                    fn () => Http::timeout(10)->asJson()->post($webhookUrl)
                )->afterResponse();
            });
        };

        static::saved($schedule);
        static::deleted($schedule);
    }
}
