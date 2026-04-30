<?php
/**
 * mailer.php — Email sending functions using PHP mail().
 */

require_once __DIR__ . '/config.php';

/**
 * Send notification email to the studio.
 */
function sendNotificationEmail(array $data): bool {
    if (!MAIL_ENABLED) {
        // Log locally during development
        $log = date('[Y-m-d H:i:s]') . " MAIL — {$data['name']} <{$data['email']}>\n";
        file_put_contents(__DIR__ . '/../logs/mail.log', $log, FILE_APPEND);
        return true;
    }
    $subject = '=?UTF-8?B?' . base64_encode('New Enquiry — ' . SITE_NAME) . '?=';
    $body    = "Name: {$data['name']}\nEmail: {$data['email']}\n"
             . "Phone: " . ($data['phone'] ?: 'N/A') . "\n"
             . "Service: {$data['service']}\nBudget: " . ($data['budget'] ?: 'N/A') . "\n\n"
             . "Message:\n{$data['message']}";
    $headers = "From: " . MAIL_FROM_NAME . " <" . MAIL_FROM . ">\r\n"
             . "Reply-To: {$data['email']}\r\nMIME-Version: 1.0\r\n"
             . "Content-Type: text/plain; charset=UTF-8";
    return mail(SITE_EMAIL, $subject, $body, $headers);
}

/**
 * Send auto-reply confirmation to the user.
 */
function sendConfirmationEmail(array $data): bool {
    if (!MAIL_ENABLED) return true;
    $subject = '=?UTF-8?B?' . base64_encode('We received your message — ' . SITE_NAME) . '?=';
    $body    = "Hi {$data['name']},\n\nThank you for reaching out to Verdana Studio!\n"
             . "We'll get back to you within 24 hours.\n\n"
             . "Warm regards,\nThe Verdana Studio Team";
    $headers = "From: " . MAIL_FROM_NAME . " <" . MAIL_FROM . ">\r\n"
             . "Reply-To: " . MAIL_FROM . "\r\nMIME-Version: 1.0\r\n"
             . "Content-Type: text/plain; charset=UTF-8";
    return mail($data['email'], $subject, $body, $headers);
}
