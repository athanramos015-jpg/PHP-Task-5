<?php
/**
 * Small helper functions used by controllers and views.
 */
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_valid(): bool
{
    return isset($_POST['csrf'])
        && is_string($_POST['csrf'])
        && hash_equals(csrf_token(), $_POST['csrf']);
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function flash_set(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'msg' => $message];
}

function flash_get(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

function field_error(array $errors, string $key): string
{
    return isset($errors[$key]) ? '<span class="error">' . e($errors[$key]) . '</span>' : '';
}

function field_class(array $errors, string $key, string $extra = ''): string
{
    $class = trim('field ' . $extra);
    return isset($errors[$key]) ? $class . ' has-error' : $class;
}
