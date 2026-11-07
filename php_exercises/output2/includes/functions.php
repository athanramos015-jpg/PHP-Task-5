<?php
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/** Read a POST value safely as a string (arrays or missing keys become ''). */
function post_str(string $key, bool $trim = true): string
{
    $value = $_POST[$key] ?? '';
    if (!is_string($value)) {
        return '';
    }
    return $trim ? trim($value) : $value;
}

function field_error(array $errors, string $key): string
{
    return isset($errors[$key]) ? '<span class="error">' . e($errors[$key]) . '</span>' : '';
}

function field_class(array $errors, string $key): string
{
    return isset($errors[$key]) ? 'field has-error' : 'field';
}

function is_logged_in(): bool
{
    return !empty($_SESSION['user']);
}
