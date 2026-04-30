<?php
/**
 * config.php — Central configuration
 * DO NOT commit this file with real credentials to a public repo.
 */

define('APP_ENV',    'development');   // Change to 'production' on live server
define('SITE_NAME',  'Verdana Studio');
define('SITE_URL',   'http://localhost/verdana-studio');
define('SITE_EMAIL', 'hello@verdanastudio.in');

// MySQL Database
define('DB_HOST',    'localhost');
define('DB_NAME',    'verdana_studio');
define('DB_USER',    'root');
define('DB_PASS',    '');           // Set your MySQL password here
define('DB_CHARSET', 'utf8mb4');

// Email — set true only on a live server with sendmail configured
define('MAIL_ENABLED',   false);
define('MAIL_FROM',      'noreply@verdanastudio.in');
define('MAIL_FROM_NAME', SITE_NAME);

// Error reporting
if (APP_ENV === 'development') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

/** PDO database connection (singleton) */
function getDB(): PDO {
    static $pdo = null;
    if ($pdo !== null) return $pdo;
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', DB_HOST, DB_NAME, DB_CHARSET);
    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } catch (PDOException $e) {
        die(APP_ENV === 'development' ? 'DB error: ' . $e->getMessage() : 'A database error occurred.');
    }
    return $pdo;
}
