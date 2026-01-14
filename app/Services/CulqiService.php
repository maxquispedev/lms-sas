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
     * Parsea el mensaje de error de Culqi para extraer información estructurada.
     *
     * @param string $errorMessage El mensaje de error de la excepción
     * @return array<string, mixed> Datos del error parseados
     */
    private function parseCulqiError(string $errorMessage): array
    {
        // Intentar decodificar el mensaje como JSON
        $decoded = json_decode($errorMessage, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        // Si no es JSON válido, retornar estructura básica
        return [
            'merchant_message' => $errorMessage,
        ];
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
            $errorMessage = $e->getMessage();
            $errorData = $this->parseCulqiError($errorMessage);

            Log::error('Error de Culqi al crear cargo', [
                'user_id' => $user->id,
                'course_id' => $course->id,
                'error' => $errorMessage,
                'error_type' => $errorData['type'] ?? null,
                'error_code' => $errorData['code'] ?? null,
                'decline_code' => $errorData['decline_code'] ?? null,
                'trace' => $e->getTraceAsString(),
            ]);

            // Si es un error de tarjeta, usar el mensaje específico
            if (($errorData['type'] ?? null) === 'card_error') {
                $userMessage = $errorData['user_message'] ?? $errorData['merchant_message'] ?? 'El pago fue denegado. Por favor, verifica los datos de tu tarjeta o intenta con otra tarjeta.';
                
                return [
                    'success' => false,
                    'message' => $userMessage,
                    'error_type' => 'card_error',
                    'decline_code' => $errorData['decline_code'] ?? null,
                ];
            }

            // Para otros tipos de errores, usar el mensaje del error
            $message = $errorData['user_message'] ?? $errorData['merchant_message'] ?? $errorMessage;

            return [
                'success' => false,
                'message' => $message,
                'error_type' => $errorData['type'] ?? 'unknown',
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
