<?php

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Helpers\Response;
use App\Models\ShopEnquiry;
use App\Services\ActivityLogger;

class ShopEnquiryController extends Controller
{
    public function index(): void
    {
        $page = max(1, (int) $this->getQuery('page', 1));
        $perPage = min(50, max(1, (int) $this->getQuery('per_page', 15)));

        $query = ShopEnquiry::query()
            ->select('shop_enquiries.*, products.name as product_name')
            ->join('products', 'shop_enquiries.product_id = products.id');

        $status = $this->getQuery('status');
        if ($status && in_array($status, ['new', 'contacted', 'completed', 'cancelled'])) {
            $query->andWhere('shop_enquiries.status', $status);
        }

        $query->orderByRaw('shop_enquiries.created_at DESC');
        $result = $query->paginate($page, $perPage);

        Response::paginated($result['data'], $result['total'], $result['page'], $result['per_page']);
    }

    public function show(string $id): void
    {
        $enquiry = ShopEnquiry::withProduct((int) $id);
        if (!$enquiry) {
            Response::error(404, 'NOT_FOUND', 'Shop enquiry not found');
        }
        $this->json($enquiry);
    }

    public function update(string $id): void
    {
        $user = $this->getAuthUser();
        ShopEnquiry::findOrFail((int) $id);

        $data = $this->getJsonBody();
        $updateData = [];
        if (isset($data['status']) && in_array($data['status'], ['new', 'contacted', 'completed', 'cancelled'])) {
            $updateData['status'] = $data['status'];
        }

        if (!empty($updateData)) {
            ShopEnquiry::update((int) $id, $updateData);
        }

        ActivityLogger::log((int) $user->id, 'update', 'shop_enquiry', (int) $id, "Updated shop enquiry status");
        $this->json(['message' => 'Enquiry updated successfully']);
    }

    public function destroy(string $id): void
    {
        $user = $this->getAuthUser();
        ShopEnquiry::findOrFail((int) $id);
        ShopEnquiry::destroy((int) $id);
        ActivityLogger::log((int) $user->id, 'delete', 'shop_enquiry', (int) $id, "Deleted shop enquiry");
        $this->json(['message' => 'Enquiry deleted successfully']);
    }
}
