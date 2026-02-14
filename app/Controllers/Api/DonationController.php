<?php

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Helpers\Response;
use App\Helpers\Validator;
use App\Models\Donation;
use App\Services\ActivityLogger;

class DonationController extends Controller
{
    public function index(): void
    {
        $page = max(1, (int) $this->getQuery('page', 1));
        $perPage = min(50, max(1, (int) $this->getQuery('per_page', 15)));

        $query = Donation::query()->orderBy('donated_at', 'DESC');

        $status = $this->getQuery('status');
        if ($status && in_array($status, ['pending', 'completed', 'failed', 'refunded'])) {
            $query->where('status', '=', $status);
        }

        $method = $this->getQuery('payment_method');
        if ($method && in_array($method, ['online', 'cash', 'cheque', 'bank_transfer'])) {
            $query->andWhere('payment_method', '=', $method);
        }

        $search = $this->getQuery('search');
        if ($search) {
            $query->search(['donor_name', 'donor_email', 'receipt_number'], $search);
        }

        $result = $query->paginate($page, $perPage);

        $items = array_map(fn($d) => [
            'id'             => (int) $d->id,
            'donor_name'     => $d->donor_name,
            'donor_email'    => $d->donor_email,
            'donor_phone'    => $d->donor_phone,
            'amount'         => (float) $d->amount,
            'currency'       => $d->currency,
            'purpose'        => $d->purpose,
            'payment_method' => $d->payment_method,
            'receipt_number' => $d->receipt_number,
            'status'         => $d->status,
            'donated_at'     => $d->donated_at,
        ], $result['data']);

        Response::paginated($items, $result['total'], $result['page'], $result['per_page']);
    }

    public function show(string $id): void
    {
        $donation = Donation::find((int) $id);
        if (!$donation) {
            Response::error(404, 'NOT_FOUND', 'Donation not found');
        }

        $this->json([
            'id'             => (int) $donation->id,
            'donor_name'     => $donation->donor_name,
            'donor_email'    => $donation->donor_email,
            'donor_phone'    => $donation->donor_phone,
            'donor_address'  => $donation->donor_address,
            'donor_pan'      => $donation->donor_pan,
            'amount'         => (float) $donation->amount,
            'currency'       => $donation->currency,
            'purpose'        => $donation->purpose,
            'payment_method' => $donation->payment_method,
            'transaction_id' => $donation->transaction_id,
            'receipt_number' => $donation->receipt_number,
            'status'         => $donation->status,
            'notes'          => $donation->notes,
            'donated_at'     => $donation->donated_at,
            'created_at'     => $donation->created_at,
            'updated_at'     => $donation->updated_at,
        ]);
    }

    public function store(): void
    {
        $user = $this->getAuthUser();
        $data = $this->getJsonBody();

        $v = new Validator($data, [
            'donor_name'     => 'required|string|min:2|max:150',
            'donor_email'    => 'nullable|email|max:150',
            'donor_phone'    => 'nullable|phone',
            'donor_address'  => 'nullable|string',
            'donor_pan'      => 'nullable|string|max:10',
            'amount'         => 'required|numeric',
            'purpose'        => 'nullable|string|max:255',
            'payment_method' => 'required|in:online,cash,cheque,bank_transfer',
            'transaction_id' => 'nullable|string|max:100',
            'receipt_number' => 'nullable|string|max:50',
            'status'         => 'nullable|in:pending,completed,failed,refunded',
            'notes'          => 'nullable|string',
        ]);

        if ($v->fails()) {
            Response::error(422, 'VALIDATION_ERROR', 'Invalid input', $v->errors());
        }

        $donation = Donation::create([
            'donor_name'     => $data['donor_name'],
            'donor_email'    => $data['donor_email'] ?? null,
            'donor_phone'    => $data['donor_phone'] ?? null,
            'donor_address'  => $data['donor_address'] ?? null,
            'donor_pan'      => $data['donor_pan'] ?? null,
            'amount'         => $data['amount'],
            'purpose'        => $data['purpose'] ?? null,
            'payment_method' => $data['payment_method'],
            'transaction_id' => $data['transaction_id'] ?? null,
            'receipt_number' => $data['receipt_number'] ?? null,
            'status'         => $data['status'] ?? 'completed',
            'notes'          => $data['notes'] ?? null,
        ]);

        ActivityLogger::log((int) $user->id, 'create', 'donation', (int) $donation->id, "Recorded donation: {$donation->donor_name} - ₹{$donation->amount}");
        $this->json(['id' => (int) $donation->id, 'receipt_number' => $donation->receipt_number], 201);
    }

    public function update(string $id): void
    {
        $user = $this->getAuthUser();
        $donation = Donation::find((int) $id);
        if (!$donation) {
            Response::error(404, 'NOT_FOUND', 'Donation not found');
        }

        $data = $this->getJsonBody();
        $updateData = [];
        foreach (['donor_name', 'donor_email', 'donor_phone', 'donor_address', 'donor_pan', 'amount', 'purpose', 'payment_method', 'transaction_id', 'receipt_number', 'status', 'notes'] as $field) {
            if (array_key_exists($field, $data)) {
                $updateData[$field] = $data[$field];
            }
        }

        if (!empty($updateData)) {
            Donation::update((int) $id, $updateData);
        }

        ActivityLogger::log((int) $user->id, 'update', 'donation', (int) $id, "Updated donation for: {$donation->donor_name}");
        $this->json(['message' => 'Donation updated successfully']);
    }

    public function destroy(string $id): void
    {
        $user = $this->getAuthUser();
        $donation = Donation::find((int) $id);
        if (!$donation) {
            Response::error(404, 'NOT_FOUND', 'Donation not found');
        }

        Donation::destroy((int) $id);
        ActivityLogger::log((int) $user->id, 'delete', 'donation', (int) $id, "Deleted donation for: {$donation->donor_name}");
        $this->json(['message' => 'Donation deleted successfully']);
    }

    /**
     * GET /api/v1/donations/summary
     * Returns monthly summary stats
     */
    public function summary(): void
    {
        $monthly = Donation::monthlySummary();
        $this->json($monthly);
    }
}
