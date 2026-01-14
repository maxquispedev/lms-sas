<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Course;
use App\Models\User;
use Culqi\Culqi;
use Culqi\Error\CulqiException;
use Illuminate\Support\Facades\Log;

/**
 * Servicio para interactuar con la API de Culqi.
 *
 * Este servicio maneja la creación de cargos utilizando el SDK oficial de Culqi.
 */
class CulqiService
{
    /**
     * Instancia del cliente Culqi.
     */
    private Culqi $culqi;

    /**
     * Configuración de Culqi desde config/services.php.
     */
    private array $config;

    /**
     * Constructor del servicio.
     *
     * Inicializa el cliente Culqi con la clave secreta de la configuración.
     */
    public function __construct()
    {
        $this->config = config('services.culqi', []);

        $this->culqi = new Culqi([
            'api_key' => $this->config['secret_key'] ?? '',
        ]);
    }

    /**
     * Crea un cargo en Culqi para un curso.
     *
     * @param User $user El usuario que realiza la compra
     * @param Course $course El curso que se está comprando
     * @param string $tokenId El token generado por el frontend de Culqi
     * @param array<string, mixed> $antifraudDetails Detalles opcionales para antifraude
     * @return array{success: bool, data?: mixed, message?: string} Respuesta estandarizada
     */
    public function createCharge(
        User $user,
        Course $course,
        string $tokenId,
        array $antifraudDetails = []
    ): array {
        try {
            // Preparar los datos del cargo
            $chargeData = [
                'amount' => (int) ($course->price * 100), // Culqi requiere el monto en centavos
                'currency_code' => 'PEN', // Soles peruanos
                'email' => $user->email,
                'source_id' => $tokenId,
                'description' => "Compra del curso: {$course->title}",
                'metadata' => [
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'course_title' => $course->title,
                ],
            ];

            // Agregar detalles de antifraude si se proporcionan
            if (! empty($antifraudDetails)) {
                $chargeData['antifraud_details'] = $antifraudDetails;
            }

            // Crear el cargo usando el SDK de Culqi
            $charge = $this->culqi->Charges->create($chargeData);

            return [
                'success' => true,
                'data' => $charge,
            ];
        } catch (CulqiException $e) {
            Log::error('Error de Culqi al crear cargo', [
                'user_id' => $user->id,
                'course_id' => $course->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        } catch (\Exception $e) {
            Log::error('Error inesperado al crear cargo en Culqi', [
                'user_id' => $user->id,
                'course_id' => $course->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'Ocurrió un error al procesar el pago. Por favor, intenta nuevamente.',
            ];
        }
    }
}
