<?php

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Helpers\Response;
use App\Helpers\Validator;
use App\Models\Product;
use App\Services\ActivityLogger;
use App\Services\SlugService;

class ProductController extends Controller
{
    public function index(): void
    {
        $page = max(1, (int) $this->getQuery('page', 1));
        $perPage = min(50, max(1, (int) $this->getQuery('per_page', 15)));

        $query = Product::query();

        $category = $this->getQuery('category');
        if ($category && in_array($category, ['book', 'pooja_item', 'other'])) {
            $query->andWhere('category', $category);
        }

        $query->orderBy('sort_order');
        $result = $query->paginate($page, $perPage);

        Response::paginated($result['data'], $result['total'], $result['page'], $result['per_page']);
    }

    public function show(string $id): void
    {
        $product = Product::findOrFail((int) $id);
        $this->json($product);
    }

    public function store(): void
    {
        $user = $this->getAuthUser();
        $data = $this->getJsonBody();

        $v = new Validator($data, [
            'name'           => 'required|string|min:2|max:255',
            'category'       => 'required|in:book,pooja_item,other',
            'price'          => 'required|numeric',
            'description'    => 'nullable|string',
            'featured_image' => 'nullable|string|max:255',
            'stock_qty'      => 'nullable|integer',
            'is_active'      => 'nullable|boolean',
            'sort_order'     => 'nullable|integer',
        ]);

        if ($v->fails()) {
            Response::error(422, 'VALIDATION_ERROR', 'Invalid input', $v->errors());
        }

        $slug = SlugService::slugify($data['name'], 'products');

        $product = Product::create([
            'name'           => $data['name'],
            'slug'           => $slug,
            'category'       => $data['category'],
            'description'    => $data['description'] ?? null,
            'name_ta'        => $data['name_ta'] ?? null,
            'description_ta' => $data['description_ta'] ?? null,
            'price'          => $data['price'],
            'featured_image' => $data['featured_image'] ?? null,
            'stock_qty'      => $data['stock_qty'] ?? 0,
            'is_active'      => $data['is_active'] ?? 1,
            'sort_order'     => $data['sort_order'] ?? 0,
        ]);

        ActivityLogger::log((int) $user->id, 'create', 'product', (int) $product->id, "Created product: {$product->name}");
        $this->json($product, 201);
    }

    public function update(string $id): void
    {
        $user = $this->getAuthUser();
        $product = Product::findOrFail((int) $id);
        $data = $this->getJsonBody();

        $updateData = [];
        foreach (['name', 'name_ta', 'category', 'description', 'description_ta', 'price', 'featured_image', 'stock_qty', 'is_active', 'sort_order'] as $field) {
            if (array_key_exists($field, $data)) {
                $updateData[$field] = $data[$field];
            }
        }

        if (isset($updateData['name']) && $updateData['name'] !== $product->name) {
            $updateData['slug'] = SlugService::slugify($updateData['name'], 'products');
        }

        if (!empty($updateData)) {
            Product::update((int) $id, $updateData);
        }

        ActivityLogger::log((int) $user->id, 'update', 'product', (int) $id, "Updated product: {$product->name}");
        $this->json(['message' => 'Product updated successfully']);
    }

    public function destroy(string $id): void
    {
        $user = $this->getAuthUser();
        $product = Product::findOrFail((int) $id);
        Product::destroy((int) $id);
        ActivityLogger::log((int) $user->id, 'delete', 'product', (int) $id, "Deleted product: {$product->name}");
        $this->json(['message' => 'Product deleted successfully']);
    }
}
