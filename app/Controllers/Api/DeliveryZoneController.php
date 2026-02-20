<?php

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Helpers\Response;
use App\Models\DeliveryZone;
use App\Models\Setting;

class DeliveryZoneController extends Controller
{
    /** GET /api/v1/delivery-zones */
    public function index(): void
    {
        $zones = DeliveryZone::allForAdmin();
        Response::success($zones);
    }

    /** GET /api/v1/delivery-zones/{id} */
    public function show(string $id): void
    {
        $zone = DeliveryZone::findOrFail((int) $id);
        Response::success($zone);
    }

    /** POST /api/v1/delivery-zones */
    public function store(): void
    {
        $data = $this->getJsonBody();

        $name   = trim($data['name'] ?? '');
        $charge = $data['charge'] ?? null;

        if ($name === '' || $charge === null) {
            Response::error('Name and charge are required', 422);
            return;
        }

        $zone = DeliveryZone::create([
            'name'      => $name,
            'min_km'    => (float) ($data['min_km'] ?? 0),
            'max_km'    => (float) ($data['max_km'] ?? 0),
            'charge'    => (float) $charge,
            'is_active' => isset($data['is_active']) ? (int) $data['is_active'] : 1,
        ]);

        Response::created($zone);
    }

    /** PUT /api/v1/delivery-zones/{id} */
    public function update(string $id): void
    {
        DeliveryZone::findOrFail((int) $id);
        $data = $this->getJsonBody();

        $allowed = ['name', 'min_km', 'max_km', 'charge', 'is_active'];
        $payload  = [];
        foreach ($allowed as $key) {
            if (array_key_exists($key, $data)) {
                $payload[$key] = $data[$key];
            }
        }

        DeliveryZone::update((int) $id, $payload);
        Response::success(DeliveryZone::find((int) $id));
    }

    /** DELETE /api/v1/delivery-zones/{id} */
    public function destroy(string $id): void
    {
        DeliveryZone::findOrFail((int) $id);
        DeliveryZone::delete((int) $id);
        Response::success(['message' => 'Delivery zone deleted']);
    }

    /**
     * GET /api/v1/delivery-zones/check?lat=12.97&lng=80.14
     * Public endpoint — returns charge for a customer lat/lng.
     */
    public function check(): void
    {
        $lat = isset($_GET['lat']) ? (float) $_GET['lat'] : null;
        $lng = isset($_GET['lng']) ? (float) $_GET['lng'] : null;

        if ($lat === null || $lng === null) {
            Response::error('lat and lng query parameters are required', 422);
            return;
        }

        $originLat = (float) (Setting::getValue('shop_dispatch_lat') ?? 12.97350);
        $originLng = (float) (Setting::getValue('shop_dispatch_lng') ?? 80.14840);

        $distanceKm = DeliveryZone::haversineKm($originLat, $originLng, $lat, $lng);

        $zone = DeliveryZone::findByDistance($distanceKm);

        if (!$zone) {
            Response::error('Delivery not available to this location (' . round($distanceKm, 1) . ' km)', 404);
            return;
        }

        Response::success([
            'zone_id'     => $zone->id,
            'zone_name'   => $zone->name,
            'charge'      => (float) $zone->charge,
            'distance_km' => round($distanceKm, 2),
            'min_km'      => (float) $zone->min_km,
            'max_km'      => (float) $zone->max_km,
        ]);
    }
}
