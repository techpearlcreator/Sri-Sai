<?php

namespace App\Core;

/**
 * Request — Handles all incoming request data with sanitization.
 *
 * Usage:
 *   $request = new Request();
 *   $title = $request->input('title');
 *   $all = $request->all();
 *   $body = $request->json();          // Parsed JSON body
 *   $file = $request->file('image');   // $_FILES entry
 *   $page = $request->query('page', 1);
 */
class Request
{
    private array $jsonBody;
    private array $queryParams;
    private array $postData;

    public function __construct()
    {
        $this->queryParams = $_GET;
        $this->postData = $_POST;

        // Parse JSON body for PUT/DELETE and JSON POST
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (str_contains($contentType, 'application/json')) {
            $raw = file_get_contents('php://input');
            $this->jsonBody = json_decode($raw, true) ?? [];
        } else {
            // For PUT/DELETE with form data, parse php://input
            $method = $_SERVER['REQUEST_METHOD'];
            if (in_array($method, ['PUT', 'DELETE', 'PATCH'])) {
                $raw = file_get_contents('php://input');
                if (str_contains($contentType, 'multipart/form-data')) {
                    // Multipart PUT is complex; for now use what's in $_POST
                    $this->jsonBody = [];
                } elseif (str_contains($contentType, 'application/x-www-form-urlencoded')) {
                    parse_str($raw, $parsed);
                    $this->jsonBody = $parsed;
                } else {
                    // Try JSON
                    $this->jsonBody = json_decode($raw, true) ?? [];
                }
            } else {
                $this->jsonBody = [];
            }
        }
    }

    /**
     * Get a single input value from JSON body, POST, or GET (in that priority).
     */
    public function input(string $key, mixed $default = null): mixed
    {
        return $this->jsonBody[$key] ?? $this->postData[$key] ?? $this->queryParams[$key] ?? $default;
    }

    /**
     * Get all input data (merged: JSON body + POST + GET).
     */
    public function all(): array
    {
        return array_merge($this->queryParams, $this->postData, $this->jsonBody);
    }

    /**
     * Get only specified keys from input.
     */
    public function only(array $keys): array
    {
        $all = $this->all();
        $result = [];
        foreach ($keys as $key) {
            if (array_key_exists($key, $all)) {
                $result[$key] = $all[$key];
            }
        }
        return $result;
    }

    /**
     * Get parsed JSON body.
     */
    public function json(): array
    {
        return $this->jsonBody;
    }

    /**
     * Get a query parameter.
     */
    public function query(string $key, mixed $default = null): mixed
    {
        return $this->queryParams[$key] ?? $default;
    }

    /**
     * Check if input key exists.
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->jsonBody)
            || array_key_exists($key, $this->postData)
            || array_key_exists($key, $this->queryParams);
    }

    /**
     * Get an uploaded file.
     */
    public function file(string $key): ?array
    {
        if (isset($_FILES[$key]) && $_FILES[$key]['error'] !== UPLOAD_ERR_NO_FILE) {
            return $_FILES[$key];
        }
        return null;
    }

    /**
     * Check if a file was uploaded.
     */
    public function hasFile(string $key): bool
    {
        return isset($_FILES[$key]) && $_FILES[$key]['error'] === UPLOAD_ERR_OK;
    }

    /**
     * Get request method.
     */
    public function method(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Get client IP address.
     */
    public function ip(): string
    {
        return $_SERVER['HTTP_X_FORWARDED_FOR']
            ?? $_SERVER['HTTP_CLIENT_IP']
            ?? $_SERVER['REMOTE_ADDR']
            ?? '0.0.0.0';
    }

    /**
     * Get user agent string.
     */
    public function userAgent(): string
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? '';
    }

    /**
     * Get the Authorization Bearer token.
     */
    public function bearerToken(): ?string
    {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
        if (str_starts_with($header, 'Bearer ')) {
            return substr($header, 7);
        }
        return null;
    }

    /**
     * Sanitize a string (strip tags, trim whitespace).
     */
    public static function sanitize(mixed $value): mixed
    {
        if (is_string($value)) {
            return trim(strip_tags($value));
        }
        if (is_array($value)) {
            return array_map([self::class, 'sanitize'], $value);
        }
        return $value;
    }

    /**
     * Sanitize all input but preserve HTML for specified keys (e.g., 'content').
     */
    public function sanitized(array $htmlAllowed = ['content']): array
    {
        $all = $this->all();
        $result = [];
        foreach ($all as $key => $value) {
            if (in_array($key, $htmlAllowed, true)) {
                // Allow HTML but clean dangerous tags
                $result[$key] = is_string($value) ? self::cleanHtml($value) : $value;
            } else {
                $result[$key] = self::sanitize($value);
            }
        }
        return $result;
    }

    /**
     * Clean HTML — remove script/iframe/object tags but keep safe formatting.
     */
    public static function cleanHtml(string $html): string
    {
        // Remove script, iframe, object, embed, form, and event attributes
        $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);
        $html = preg_replace('/<iframe\b[^>]*>(.*?)<\/iframe>/is', '', $html);
        $html = preg_replace('/<object\b[^>]*>(.*?)<\/object>/is', '', $html);
        $html = preg_replace('/<embed\b[^>]*>/is', '', $html);
        $html = preg_replace('/<form\b[^>]*>(.*?)<\/form>/is', '', $html);
        // Remove on* event attributes
        $html = preg_replace('/\s+on\w+\s*=\s*["\'][^"\']*["\']/i', '', $html);
        $html = preg_replace('/\s+on\w+\s*=\s*\S+/i', '', $html);
        return trim($html);
    }
}
