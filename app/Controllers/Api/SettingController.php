<?php

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Helpers\Response;
use App\Models\Setting;
use App\Services\ActivityLogger;

class SettingController extends Controller
{
    /**
     * GET /api/v1/settings
     * Query: group (general|contact|social|seo)
     */
    public function index(): void
    {
        $group = $this->getQuery('group');

        if ($group) {
            $settings = Setting::byGroup($group);
        } else {
            $settings = Setting::allAsArray();
        }

        $this->json($settings);
    }

    /**
     * PUT /api/v1/settings
     * Body: { "settings": { "site_name": "...", "site_email": "..." } }
     */
    public function update(): void
    {
        $user = $this->getAuthUser();
        $data = $this->getJsonBody();

        if (!isset($data['settings']) || !is_array($data['settings'])) {
            Response::error(422, 'VALIDATION_ERROR', 'Settings object is required');
        }

        Setting::bulkUpdate($data['settings']);

        ActivityLogger::log((int) $user->id, 'update', 'settings', null, 'Updated site settings (' . count($data['settings']) . ' keys)');

        $this->json(['message' => 'Settings updated successfully']);
    }
}
