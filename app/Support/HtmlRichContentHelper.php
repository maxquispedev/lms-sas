<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\Storage;

/**
 * Convierte etiquetas <img> que apuntan a archivos no imagen (PDF, Word, etc.)
 * en enlaces de descarga <a download>, para que se muestren como enlaces clicables
 * en lugar de imágenes rotas (RichEditor de Filament guarda todos los adjuntos como img).
 */
final class HtmlRichContentHelper
{
    /**
     * Transforma el HTML de contenido rico: reemplaza <img> con src a PDF/doc/docx
     * por <a href="..." download> para permitir descarga.
     *
     * @param string|null $html Contenido HTML (descripción o content de módulo/lección).
     * @param string|null $baseUrl URL base para convertir rutas relativas en absolutas (ej. para API/frontend).
     */
    public static function fileAttachmentsToDownloadLinks(?string $html, ?string $baseUrl = null): string
    {
        if ($html === null || $html === '') {
            return '';
        }

        $pattern = '/<img([^>]*)\ssrc\s*=\s*["\']([^"\']+\.(?:pdf|doc|docx))["\']([^>]*)>/iu';

        $html = (string) preg_replace_callback($pattern, function (array $matches) use ($baseUrl): string {
            $before = $matches[1];
            $src = $matches[2];
            $after = $matches[3];

            $alt = '';
            if (preg_match('/\salt\s*=\s*["\']([^"\']*)["\']/iu', $before . $after, $altMatch)) {
                $alt = trim($altMatch[1]);
            }
            $href = self::ensureAbsoluteUrl($src, $baseUrl);
            $label = $alt !== '' ? $alt : self::filenameFromUrl($src);

            return '<a href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '" download class="inline-flex items-center gap-1.5 text-primary hover:underline">'
                . '<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>'
                . '<span>' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</span>'
                . '</a>';
        }, $html);

        return self::ensureAbsoluteUrlsInFileLinks($html, $baseUrl);
    }

    /**
     * Convierte hrefs relativos en enlaces a archivos (.pdf, .doc, .docx) a URLs absolutas.
     */
    private static function ensureAbsoluteUrlsInFileLinks(string $html, ?string $baseUrl): string
    {
        $pattern = '/<a([^>]*)\shref\s*=\s*["\']([^"\']+\.(?:pdf|doc|docx))["\']([^>]*)>/iu';
        return (string) preg_replace_callback($pattern, function (array $matches) use ($baseUrl): string {
            $before = $matches[1];
            $href = $matches[2];
            $after = $matches[3];
            $absolute = self::ensureAbsoluteUrl($href, $baseUrl);
            return '<a' . $before . ' href="' . htmlspecialchars($absolute, ENT_QUOTES, 'UTF-8') . '"' . $after . '>';
        }, $html);
    }

    private static function filenameFromUrl(string $url): string
    {
        $path = parse_url($url, PHP_URL_PATH);
        if (is_string($path)) {
            $name = basename($path);
            if ($name !== '') {
                return $name;
            }
        }
        return 'Descargar archivo';
    }

    private static function ensureAbsoluteUrl(string $url, ?string $baseUrl): string
    {
        $trimmed = trim($url);
        if (str_starts_with($trimmed, 'http://') || str_starts_with($trimmed, 'https://')) {
            return $url;
        }
        if ($baseUrl !== null && $baseUrl !== '') {
            $base = rtrim($baseUrl, '/');
            return $base . '/' . ltrim($trimmed, '/');
        }
        // En contexto Laravel (Blade) sin baseUrl, devolver URL del disco public si es ruta relativa
        if (str_starts_with($trimmed, 'storage/') || !str_contains($trimmed, '://')) {
            return Storage::disk('public')->url($trimmed);
        }
        return $url;
    }
}
