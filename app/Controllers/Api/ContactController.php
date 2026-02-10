<?php

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Core\Database;
use App\Helpers\Response;
use App\Helpers\Validator;
use App\Models\ContactMessage;
use App\Services\ActivityLogger;

class ContactController extends Controller
{
    public function index(): void
    {
        $page = max(1, (int) $this->getQuery('page', 1));
        $perPage = min(50, max(1, (int) $this->getQuery('per_page', 15)));

        $query = ContactMessage::query()->orderBy('created_at', 'DESC');

        $status = $this->getQuery('status');
        if ($status && in_array($status, ['unread', 'read', 'replied', 'archived'])) {
            $query->where('status', '=', $status);
        }

        $search = $this->getQuery('search');
        if ($search) {
            $query->search(['name', 'email', 'subject'], $search);
        }

        $result = $query->paginate($page, $perPage);

        $items = array_map(fn($c) => [
            'id'         => (int) $c->id,
            'name'       => $c->name,
            'last_name'  => $c->last_name,
            'email'      => $c->email,
            'phone'      => $c->phone,
            'subject'    => $c->subject,
            'message'    => mb_substr($c->message, 0, 200),
            'status'     => $c->status,
            'created_at' => $c->created_at,
        ], $result['data']);

        Response::paginated($items, $result['total'], $result['page'], $result['per_page']);
    }

    public function show(string $id): void
    {
        $msg = ContactMessage::find((int) $id);
        if (!$msg) {
            Response::error(404, 'NOT_FOUND', 'Message not found');
        }

        // Auto-mark as read
        if ($msg->status === 'unread') {
            ContactMessage::update((int) $id, ['status' => 'read']);
        }

        $this->json([
            'id'          => (int) $msg->id,
            'name'        => $msg->name,
            'last_name'   => $msg->last_name,
            'email'       => $msg->email,
            'phone'       => $msg->phone,
            'subject'     => $msg->subject,
            'message'     => $msg->message,
            'source_page' => $msg->source_page,
            'ip_address'  => $msg->ip_address,
            'status'      => $msg->status === 'unread' ? 'read' : $msg->status,
            'admin_notes' => $msg->admin_notes,
            'replied_at'  => $msg->replied_at,
            'created_at'  => $msg->created_at,
        ]);
    }

    /**
     * PUT /api/v1/contacts/{id}
     * Update status or add admin notes
     */
    public function update(string $id): void
    {
        $user = $this->getAuthUser();
        $msg = ContactMessage::find((int) $id);
        if (!$msg) {
            Response::error(404, 'NOT_FOUND', 'Message not found');
        }

        $data = $this->getJsonBody();
        $updateData = [];

        if (isset($data['status']) && in_array($data['status'], ['unread', 'read', 'replied', 'archived'])) {
            $updateData['status'] = $data['status'];
            if ($data['status'] === 'replied' && !$msg->replied_at) {
                $updateData['replied_at'] = date('Y-m-d H:i:s');
            }
        }

        if (isset($data['admin_notes'])) {
            $updateData['admin_notes'] = $data['admin_notes'];
        }

        if (!empty($updateData)) {
            ContactMessage::update((int) $id, $updateData);
        }

        ActivityLogger::log((int) $user->id, 'update', 'contact', (int) $id, "Updated contact message from: {$msg->name}");
        $this->json(['message' => 'Contact message updated successfully']);
    }

    public function destroy(string $id): void
    {
        $user = $this->getAuthUser();
        $msg = ContactMessage::find((int) $id);
        if (!$msg) {
            Response::error(404, 'NOT_FOUND', 'Message not found');
        }

        ContactMessage::destroy((int) $id);
        ActivityLogger::log((int) $user->id, 'delete', 'contact', (int) $id, "Deleted message from: {$msg->name}");
        $this->json(['message' => 'Contact message deleted successfully']);
    }

    /**
     * PUT /api/v1/contacts/bulk
     * Body: { "ids": [1,2,3], "action": "read|archived|delete" }
     */
    public function bulk(): void
    {
        $user = $this->getAuthUser();
        $data = $this->getJsonBody();

        if (!isset($data['ids']) || !is_array($data['ids']) || empty($data['ids'])) {
            Response::error(422, 'VALIDATION_ERROR', 'IDs array is required');
        }

        $action = $data['action'] ?? '';
        $ids = array_map('intval', $data['ids']);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $db = Database::getInstance();

        if ($action === 'delete') {
            $db->query("DELETE FROM `contact_messages` WHERE `id` IN ({$placeholders})", $ids);
            ActivityLogger::log((int) $user->id, 'bulk_delete', 'contact', null, "Bulk deleted " . count($ids) . " messages");
        } elseif (in_array($action, ['read', 'archived'])) {
            $db->query("UPDATE `contact_messages` SET `status` = ? WHERE `id` IN ({$placeholders})", array_merge([$action], $ids));
            ActivityLogger::log((int) $user->id, 'bulk_update', 'contact', null, "Bulk marked " . count($ids) . " messages as {$action}");
        } else {
            Response::error(422, 'VALIDATION_ERROR', "Invalid action: {$action}");
        }

        $this->json(['message' => 'Bulk action completed', 'affected' => count($ids)]);
    }

    /**
     * GET /api/v1/contacts/unread-count
     */
    public function unreadCount(): void
    {
        $count = ContactMessage::unreadCount();
        $this->json(['unread_count' => $count]);
    }

    /**
     * POST /api/v1/contact (PUBLIC — no auth)
     * Public contact form submission
     */
    public function submit(): void
    {
        $data = $this->getJsonBody();

        $v = new Validator($data, [
            'name'    => 'required|string|min:2|max:100',
            'last_name' => 'nullable|string|max:100',
            'email'   => 'required|email|max:150',
            'phone'   => 'nullable|phone',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|min:10|max:5000',
        ]);

        if ($v->fails()) {
            Response::error(422, 'VALIDATION_ERROR', 'Invalid input', $v->errors());
        }

        ContactMessage::create([
            'name'        => $data['name'],
            'last_name'   => $data['last_name'] ?? null,
            'email'       => $data['email'],
            'phone'       => $data['phone'] ?? null,
            'subject'     => $data['subject'] ?? null,
            'message'     => $data['message'],
            'source_page' => $data['source_page'] ?? 'contact',
            'ip_address'  => $_SERVER['REMOTE_ADDR'] ?? null,
        ]);

        $this->json(['message' => 'Thank you for your message. We will get back to you soon.'], 201);
    }
}
