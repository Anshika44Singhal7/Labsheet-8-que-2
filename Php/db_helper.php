<?php
/**
 * db_helper.php — Database functions for storing contact submissions.
 */

require_once __DIR__ . '/config.php';

/**
 * Save a form submission to the database.
 * @param array $data  name, email, phone, service, budget, message
 * @return int|false   Inserted row ID or false on failure
 */
function saveSubmission(array $data): int|false {
    try {
        $stmt = getDB()->prepare("
            INSERT INTO contact_submissions
              (name, email, phone, service, budget, message, ip_address)
            VALUES
              (:name, :email, :phone, :service, :budget, :message, :ip)
        ");
        $stmt->execute([
            ':name'    => $data['name'],
            ':email'   => $data['email'],
            ':phone'   => $data['phone']   ?? null,
            ':service' => $data['service'],
            ':budget'  => $data['budget']  ?? null,
            ':message' => $data['message'],
            ':ip'      => $_SERVER['REMOTE_ADDR'] ?? null,
        ]);
        return (int) getDB()->lastInsertId();
    } catch (PDOException $e) {
        error_log('saveSubmission failed: ' . $e->getMessage());
        return false;
    }
}

/**
 * Retrieve recent submissions.
 */
function getSubmissions(int $limit = 50, int $offset = 0): array {
    try {
        $stmt = getDB()->prepare("SELECT * FROM contact_submissions ORDER BY created_at DESC LIMIT :l OFFSET :o");
        $stmt->bindValue(':l', $limit,  PDO::PARAM_INT);
        $stmt->bindValue(':o', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log('getSubmissions failed: ' . $e->getMessage());
        return [];
    }
}
