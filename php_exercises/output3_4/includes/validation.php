<?php
/**
 * Shared helpers: output escaping + validation for the "person" form.
 */

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function person_defaults(): array
{
    return [
        'first_name'     => '',
        'last_name'      => '',
        'age'            => '',
        'gender'         => '',
        'email'          => '',
        'address'        => '',
        'contact_number' => '',
    ];
}

function field_error(array $errors, string $key): string
{
    return isset($errors[$key]) ? '<span class="error">' . e($errors[$key]) . '</span>' : '';
}

function field_class(array $errors, string $key, string $extra = ''): string
{
    $class = trim('field ' . $extra);
    if (isset($errors[$key])) {
        $class .= ' has-error';
    }
    return $class;
}

/**
 * Validate and sanitize the submitted form values.
 * Returns [cleanData, errors]. $errors is empty when everything is valid.
 */
function validate_person(array $input): array
{
    $data = person_defaults();
    foreach ($data as $key => $default) {
        $data[$key] = (isset($input[$key]) && is_string($input[$key])) ? trim($input[$key]) : '';
    }

    $errors = [];
    $namePattern = '/^[\p{L}][\p{L} .\'\-]{1,49}$/u';

    // First name
    if ($data['first_name'] === '') {
        $errors['first_name'] = 'First name is required.';
    } elseif (!preg_match($namePattern, $data['first_name'])) {
        $errors['first_name'] = 'Use letters only (2 to 50 characters).';
    }

    // Last name
    if ($data['last_name'] === '') {
        $errors['last_name'] = 'Last name is required.';
    } elseif (!preg_match($namePattern, $data['last_name'])) {
        $errors['last_name'] = 'Use letters only (2 to 50 characters).';
    }

    // Age
    if ($data['age'] === '') {
        $errors['age'] = 'Age is required.';
    } else {
        $age = filter_var($data['age'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 120]]);
        if ($age === false) {
            $errors['age'] = 'Enter a whole number from 1 to 120.';
        } else {
            $data['age'] = (string) $age;
        }
    }

    // Gender
    if (!in_array($data['gender'], ['Male', 'Female', 'Other'], true)) {
        $errors['gender'] = 'Please select a gender.';
    }

    // Email
    if ($data['email'] === '') {
        $errors['email'] = 'Email is required.';
    } elseif (strlen($data['email']) > 100 || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Enter a valid email address, for example juan@example.com.';
    }

    // Address
    if ($data['address'] === '') {
        $errors['address'] = 'Address is required.';
    } elseif (strlen($data['address']) < 5 || strlen($data['address']) > 255) {
        $errors['address'] = 'Address must be 5 to 255 characters.';
    }

    // Contact number (Philippine mobile: 09XXXXXXXXX or +639XXXXXXXXX)
    $digits = preg_replace('/[\s\-]/', '', $data['contact_number']);
    if ($data['contact_number'] === '') {
        $errors['contact_number'] = 'Contact number is required.';
    } elseif (!preg_match('/^(09\d{9}|\+639\d{9})$/', $digits)) {
        $errors['contact_number'] = 'Use a mobile number like 09123456789 or +639123456789.';
    } else {
        $data['contact_number'] = $digits;
    }

    return [$data, $errors];
}
