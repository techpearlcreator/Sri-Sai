<?php

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Helpers\EnvLoader;

class TranslateController extends Controller
{
    /**
     * POST /api/v1/translate
     * Body: { "text": "...", "source": "en", "target": "ta" }
     * Returns: { "translatedText": "..." }
     *
     * Uses LibreTranslate (configurable via LIBRETRANSLATE_URL in .env).
     * Defaults to https://libretranslate.com — set a public mirror if needed.
     */
    public function translate(): void
    {
        header('Content-Type: application/json');

        $data   = json_decode(file_get_contents('php://input'), true);
        $text   = trim($data['text'] ?? '');
        $source = $data['source'] ?? 'en';
        $target = $data['target'] ?? 'ta';

        if ($text === '') {
            echo json_encode(['translatedText' => '']);
            exit;
        }

        // Max 5000 chars per request
        if (strlen($text) > 5000) {
            http_response_code(422);
            echo json_encode(['error' => 'Text too long (max 5000 characters)']);
            exit;
        }

        $url    = EnvLoader::get('LIBRETRANSLATE_URL', 'https://libretranslate.com/translate');
        $apiKey = EnvLoader::get('LIBRETRANSLATE_API_KEY', '');

        $payload = json_encode([
            'q'       => $text,
            'source'  => $source,
            'target'  => $target,
            'format'  => 'text',
            'api_key' => $apiKey,
        ]);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_TIMEOUT        => 20,
            CURLOPT_SSL_VERIFYPEER => false, // for local dev / self-signed certs
        ]);

        $result   = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr  = curl_error($ch);
        curl_close($ch);

        if ($result === false || $curlErr) {
            http_response_code(503);
            echo json_encode(['error' => 'Could not reach translation service: ' . $curlErr]);
            exit;
        }

        $response = json_decode($result, true);

        if ($httpCode !== 200 || !isset($response['translatedText'])) {
            $errMsg = $response['error'] ?? 'Translation failed (HTTP ' . $httpCode . ')';
            http_response_code(503);
            echo json_encode(['error' => $errMsg]);
            exit;
        }

        echo json_encode(['translatedText' => $response['translatedText']]);
        exit;
    }
}
