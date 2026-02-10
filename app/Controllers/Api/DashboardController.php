<?php

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Core\Database;

class DashboardController extends Controller
{
    /**
     * GET /api/v1/dashboard
     * Returns aggregate counts + recent activity for admin dashboard
     */
    public function index(): void
    {
        $db = Database::getInstance();

        // Aggregate counts
        $blogs      = $db->fetch("SELECT COUNT(*) as total, SUM(status='published') as published FROM blogs");
        $magazines  = $db->fetch("SELECT COUNT(*) as total, SUM(status='published') as published FROM magazines");
        $events     = $db->fetch("SELECT COUNT(*) as total, SUM(status='upcoming') as upcoming FROM events");
        $albums     = $db->fetch("SELECT COUNT(*) as total FROM gallery_albums");
        $images     = $db->fetch("SELECT COUNT(*) as total FROM gallery_images");
        $trustees   = $db->fetch("SELECT COUNT(*) as total, SUM(is_active=1) as active FROM trustees");
        $donations  = $db->fetch("SELECT COUNT(*) as total, COALESCE(SUM(CASE WHEN status='completed' THEN amount END), 0) as total_amount FROM donations");
        $contacts   = $db->fetch("SELECT COUNT(*) as total, SUM(status='unread') as unread FROM contact_messages");
        $pages      = $db->fetch("SELECT COUNT(*) as total FROM pages");
        $media      = $db->fetch("SELECT COUNT(*) as total FROM media");

        // Recent activity (last 20)
        $activity = $db->fetchAll(
            "SELECT a.id, a.action, a.entity_type, a.description, a.created_at, u.name as user_name
             FROM activity_log a
             LEFT JOIN admin_users u ON a.user_id = u.id
             ORDER BY a.created_at DESC
             LIMIT 20"
        );

        $recentActivity = array_map(fn($a) => [
            'id'          => (int) $a->id,
            'action'      => $a->action,
            'entity_type' => $a->entity_type,
            'description' => $a->description,
            'user_name'   => $a->user_name,
            'created_at'  => $a->created_at,
        ], $activity);

        $this->json([
            'counts' => [
                'blogs'           => ['total' => (int) $blogs->total, 'published' => (int) $blogs->published],
                'magazines'       => ['total' => (int) $magazines->total, 'published' => (int) $magazines->published],
                'events'          => ['total' => (int) $events->total, 'upcoming' => (int) $events->upcoming],
                'gallery_albums'  => (int) $albums->total,
                'gallery_images'  => (int) $images->total,
                'trustees'        => ['total' => (int) $trustees->total, 'active' => (int) $trustees->active],
                'donations'       => ['total' => (int) $donations->total, 'amount' => (float) $donations->total_amount],
                'contacts'        => ['total' => (int) $contacts->total, 'unread' => (int) $contacts->unread],
                'pages'           => (int) $pages->total,
                'media'           => (int) $media->total,
            ],
            'recent_activity' => $recentActivity,
        ]);
    }
}
