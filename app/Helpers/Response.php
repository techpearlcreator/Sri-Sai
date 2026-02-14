<?php

namespace App\Helpers;

class Response
{
    /**
     * Send a JSON success response.
     */
    public static function json(mixed $data, int $code = 200, string $message = ''): void
    {
        http_response_code($code);
        header('Content-Type: application/json');

        $response = ['success' => true, 'data' => $data];
        if ($message) {
            $response['message'] = $message;
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Send a paginated JSON response.
     */
    public static function paginated(array $data, int $total, int $page, int $perPage): void
    {
        http_response_code(200);
        header('Content-Type: application/json');

        echo json_encode([
            'success' => true,
            'data'    => $data,
            'meta'    => [
                'current_page' => $page,
                'per_page'     => $perPage,
                'total'        => $total,
                'last_page'    => (int) ceil($total / $perPage),
            ],
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Send a JSON error response.
     */
    public static function error(int $code, string $errorCode, string $message, array $details = []): void
    {
        http_response_code($code);
        header('Content-Type: application/json');

        $response = [
            'success' => false,
            'error'   => [
                'code'    => $errorCode,
                'message' => $message,
            ],
        ];

        if (!empty($details)) {
            $response['error']['details'] = $details;
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
