<?php

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Helpers\Response;
use App\Models\ShopOrder;

class ShopOrderController extends Controller
{
    /** GET /api/v1/shop-orders */
    public function index(): void
    {
        $page    = max(1, (int) $this->getQuery('page', 1));
        $perPage = min(50, max(1, (int) $this->getQuery('per_page', 20)));

        $filters = [];
        $ps = $this->getQuery('payment_status');
        $os = $this->getQuery('order_status');
        if ($ps) $filters['payment_status'] = $ps;
        if ($os) $filters['order_status']   = $os;

        $result = ShopOrder::listPaginated($page, $perPage, $filters);
        Response::paginated($result['data'], $result['total'], $result['page'], $result['per_page']);
    }

    /** GET /api/v1/shop-orders/{id} */
    public function show(string $id): void
    {
        $order = ShopOrder::withProduct((int) $id);
        if (!$order) {
            Response::error('Order not found', 404);
            return;
        }
        Response::success($order);
    }

    /** PUT /api/v1/shop-orders/{id} — update order_status */
    public function update(string $id): void
    {
        ShopOrder::findOrFail((int) $id);
        $data = $this->getJsonBody();

        $allowed = ['order_status', 'payment_status', 'notes'];
        $payload  = [];
        foreach ($allowed as $key) {
            if (isset($data[$key])) {
                $payload[$key] = $data[$key];
            }
        }

        if (empty($payload)) {
            Response::error('Nothing to update', 422);
            return;
        }

        ShopOrder::update((int) $id, $payload);
        Response::success(['message' => 'Order updated']);
    }
}
