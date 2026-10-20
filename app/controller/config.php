<?php
if (!defined('DEBUG')) {
    define('DEBUG', FALSE);
}

// Valeurs par défaut : MySQL local (XAMPP / WAMP / MAMP)
$db_host = 'localhost';
$db_name = 'blablacar';
$db_user = 'root';
$db_password = '';

// Fichier privé (non versionné) pour un serveur précis, ex: dev-isi.utt.fr
// Voir config.local.example.php
if (file_exists(__DIR__ . '/config.local.php')) {
    include __DIR__ . '/config.local.php';
}

// Variables d'environnement (utilisées par Docker), prioritaires sur le reste
$db_host = getenv('DB_HOST') ?: $db_host;
$db_name = getenv('DB_NAME') ?: $db_name;
$db_user = getenv('DB_USER') ?: $db_user;
$db_password = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : $db_password;

$dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";
$username = $db_user;
$password = $db_password;

$root = dirname(dirname(__DIR__)) . "/";

if (DEBUG) {
    echo ("<ul>");
    echo (" <li>dsn = $dsn</li>");
    echo (" <li>username = $username</li>");
    echo (" <li>password = ****</li>");
    echo ("<li>---</li>");
    echo (" <li>root = $root</li>");
    echo ("</ul>");
}
?>
