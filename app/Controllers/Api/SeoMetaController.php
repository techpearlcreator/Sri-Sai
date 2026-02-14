<?php

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Helpers\Response;
use App\Helpers\Validator;
use App\Models\SeoMeta;
use App\Services\ActivityLogger;

class SeoMetaController extends Controller
{
    /**
     * GET /api/v1/seo/{entityType}/{entityId}
     */
    public function show(string $entityType, string $entityId): void
    {
        $seo = SeoMeta::forEntity($entityType, (int) $entityId);

        if (!$seo) {
            $this->json([
                'entity_type'      => $entityType,
                'entity_id'        => (int) $entityId,
                'meta_title'       => null,
                'meta_description' => null,
                'meta_keywords'    => null,
                'og_title'         => null,
                'og_description'   => null,
                'og_image'         => null,
                'canonical_url'    => null,
                'no_index'         => false,
                'no_follow'        => false,
            ]);
            return;
        }

        $this->json([
            'id'               => (int) $seo->id,
            'entity_type'      => $seo->entity_type,
            'entity_id'        => (int) $seo->entity_id,
            'meta_title'       => $seo->meta_title,
            'meta_description' => $seo->meta_description,
            'meta_keywords'    => $seo->meta_keywords,
            'og_title'         => $seo->og_title,
            'og_description'   => $seo->og_description,
            'og_image'         => $seo->og_image,
            'canonical_url'    => $seo->canonical_url,
            'no_index'         => (bool) $seo->no_index,
            'no_follow'        => (bool) $seo->no_follow,
        ]);
    }

    /**
     * PUT /api/v1/seo/{entityType}/{entityId}
     */
    public function upsert(string $entityType, string $entityId): void
    {
        $user = $this->getAuthUser();
        $data = $this->getJsonBody();

        $v = new Validator($data, [
            'meta_title'       => 'nullable|string|max:70',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords'    => 'nullable|string|max:255',
            'og_title'         => 'nullable|string|max:100',
            'og_description'   => 'nullable|string|max:200',
            'og_image'         => 'nullable|string|max:255',
            'canonical_url'    => 'nullable|url|max:255',
            'no_index'         => 'nullable|boolean',
            'no_follow'        => 'nullable|boolean',
        ]);

        if ($v->fails()) {
            Response::error(422, 'VALIDATION_ERROR', 'Invalid input', $v->errors());
        }

        $seoData = [];
        foreach (['meta_title', 'meta_description', 'meta_keywords', 'og_title', 'og_description', 'og_image', 'canonical_url', 'no_index', 'no_follow'] as $field) {
            if (array_key_exists($field, $data)) {
                $seoData[$field] = $data[$field];
            }
        }

        SeoMeta::upsert($entityType, (int) $entityId, $seoData);

        ActivityLogger::log((int) $user->id, 'update', 'seo_meta', null, "Updated SEO for {$entityType}#{$entityId}");

        $this->json(['message' => 'SEO metadata saved successfully']);
    }
}
