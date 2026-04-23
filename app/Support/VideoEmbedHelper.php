<?php

declare(strict_types=1);

namespace App\Support;

final class VideoEmbedHelper
{
    /**
     * Convierte una entrada "iframe o URL" en HTML de iframe listo para renderizar.
     *
     * - Si se recibe un `<iframe ...>` lo devuelve tal cual.
     * - Si se recibe una URL (YouTube/Vimeo), construye un iframe usando un `src` embebible.
     * - Si no puede resolver un embed seguro, devuelve null.
     */
    public static function toIframeHtml(?string $raw): ?string
    {
        if ($raw === null) {
            return null;
        }

        $trimmed = trim($raw);
        if ($trimmed === '') {
            return null;
        }

        // Si ya es un iframe, lo devolvemos tal cual.
        if (stripos($trimmed, '<iframe') !== false) {
            return $trimmed;
        }

        $src = self::resolveEmbedSrcFromUrl($trimmed);
        if ($src === null) {
            return null;
        }

        // iframe “genérico” seguro para video embeds.
        return sprintf(
            '<iframe src="%s" title="Video" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>',
            e($src),
        );
    }

    /**
     * Devuelve una URL embebible a partir de una URL pegada.
     */
    public static function resolveEmbedSrcFromUrl(string $url): ?string
    {
        if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
            return null;
        }

        $parts = parse_url($url);
        if (!is_array($parts)) {
            return null;
        }

        $host = strtolower((string) ($parts['host'] ?? ''));
        $path = (string) ($parts['path'] ?? '');
        $query = (string) ($parts['query'] ?? '');

        // YouTube
        if ($host === 'www.youtube.com' || $host === 'youtube.com' || $host === 'm.youtube.com') {
            // /watch?v=ID
            if ($path === '/watch') {
                parse_str($query, $q);
                $id = isset($q['v']) ? (string) $q['v'] : '';
                $id = self::sanitizeYouTubeId($id);
                return $id !== '' ? "https://www.youtube.com/embed/{$id}" : null;
            }

            // /embed/ID
            if (str_starts_with($path, '/embed/')) {
                $id = self::sanitizeYouTubeId(substr($path, strlen('/embed/')));
                return $id !== '' ? "https://www.youtube.com/embed/{$id}" : null;
            }
        }

        // youtu.be/ID
        if ($host === 'youtu.be') {
            $id = ltrim($path, '/');
            $id = self::sanitizeYouTubeId($id);
            return $id !== '' ? "https://www.youtube.com/embed/{$id}" : null;
        }

        // Vimeo: vimeo.com/{id} o player.vimeo.com/video/{id}
        if ($host === 'vimeo.com') {
            $id = ltrim($path, '/');
            $id = preg_replace('/[^0-9]/', '', $id) ?? '';
            return $id !== '' ? "https://player.vimeo.com/video/{$id}" : null;
        }

        if ($host === 'player.vimeo.com' && str_starts_with($path, '/video/')) {
            $id = substr($path, strlen('/video/'));
            $id = preg_replace('/[^0-9]/', '', $id) ?? '';
            return $id !== '' ? "https://player.vimeo.com/video/{$id}" : null;
        }

        // Si no reconocemos el host, no intentamos embebido.
        return null;
    }

    private static function sanitizeYouTubeId(string $id): string
    {
        // YouTube IDs son típicamente [A-Za-z0-9_-] (11 chars), pero aceptamos más largo sin símbolos raros.
        $clean = preg_replace('/[^A-Za-z0-9_-]/', '', $id) ?? '';
        return $clean;
    }
}

