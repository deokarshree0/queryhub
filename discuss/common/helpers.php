<?php
if (!defined('APP_BASE')) {
    define('APP_BASE', '/discuss');
}

if (!function_exists('h')) {
    function h($value): string
    {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('ensure_session')) {
    function ensure_session(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}

if (!function_exists('current_user')) {
    function current_user(): ?array
    {
        ensure_session();
        return $_SESSION['user'] ?? null;
    }
}

if (!function_exists('is_logged_in')) {
    function is_logged_in(): bool
    {
        return current_user() !== null;
    }
}

if (!function_exists('set_flash')) {
    function set_flash(string $type, string $message): void
    {
        ensure_session();
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }
}

if (!function_exists('get_flash')) {
    function get_flash(): ?array
    {
        ensure_session();
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $flash;
    }
}

if (!function_exists('redirect_to')) {
    function redirect_to(string $path = ''): void
    {
        header('Location: ' . APP_BASE . $path);
        exit;
    }
}
?>
