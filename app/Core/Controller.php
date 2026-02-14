<?php

namespace App\Core;

use App\Helpers\Response;

class Controller
{
    /**
     * Send a JSON success response.
     */
    protected function json(mixed $data, int $code = 200, string $message = ''): void
    {
        Response::json($data, $code, $message);
    }

    /**
     * Send a JSON error response.
     */
    protected function error(int $code, string $errorCode, string $message, array $details = []): void
    {
        Response::error($code, $errorCode, $message, $details);
    }

    /**
     * Render a PHP view (without layout).
     */
    protected function view(string $viewPath, array $data = []): void
    {
        extract($data);

        $file = VIEWS_PATH . '/' . str_replace('.', '/', $viewPath) . '.php';

        if (!file_exists($file)) {
            http_response_code(500);
            echo "View not found: {$viewPath}";
            return;
        }

        ob_start();
        require $file;
        echo ob_get_clean();
    }

    /**
     * Render a view within a layout.
     */
    protected function render(string $viewPath, array $data = [], string $layout = 'layouts.master'): void
    {
        extract($data);

        // Render the view content first
        $viewFile = VIEWS_PATH . '/' . str_replace('.', '/', $viewPath) . '.php';
        if (!file_exists($viewFile)) {
            http_response_code(500);
            echo "View not found: {$viewPath}";
            return;
        }

        ob_start();
        require $viewFile;
        $__content = ob_get_clean();

        // Render within layout
        $layoutFile = VIEWS_PATH . '/' . str_replace('.', '/', $layout) . '.php';
        if (!file_exists($layoutFile)) {
            echo $__content;
            return;
        }

        require $layoutFile;
    }

    /**
     * Get JSON request body.
     */
    protected function getJsonBody(): array
    {
        $body = file_get_contents('php://input');
        $data = json_decode($body, true);
        return is_array($data) ? $data : [];
    }

    /**
     * Get query parameters.
     */
    protected function getQuery(string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $_GET;
        }
        return $_GET[$key] ?? $default;
    }

    /**
     * Get the authenticated user (set by AuthMiddleware).
     */
    protected function getAuthUser(): ?object
    {
        global $authUser;
        return $authUser ?? null;
    }
}
