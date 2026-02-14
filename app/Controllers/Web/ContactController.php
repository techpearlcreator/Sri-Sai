<?php

namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\ContactMessage;
use App\Models\Setting;

class ContactController extends Controller
{
    public function index(): void
    {
        $settings = Setting::allAsArray();

        $this->render('contact.index', [
            'pageTitle' => 'Contact Us — Sri Sai Mission',
            'pageClass' => 'contact',
            'settings'  => $settings,
        ]);
    }

    public function submit(): void
    {
        $data = $this->getJsonBody();

        $name    = trim($data['name'] ?? '');
        $email   = trim($data['email'] ?? '');
        $phone   = trim($data['phone'] ?? '');
        $subject = trim($data['subject'] ?? '');
        $message = trim($data['message'] ?? '');

        if ($name === '' || $email === '' || $message === '') {
            $this->error(422, 'VALIDATION_ERROR', 'Name, email, and message are required.');
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error(422, 'VALIDATION_ERROR', 'Please provide a valid email address.');
            return;
        }

        ContactMessage::create([
            'name'    => $name,
            'email'   => $email,
            'phone'   => $phone,
            'subject' => $subject,
            'message' => $message,
        ]);

        $this->json(['sent' => true], 200, 'Message sent successfully.');
    }
}
