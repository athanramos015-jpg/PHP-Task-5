<?php
require __DIR__ . '/includes/validation.php';

$data   = person_defaults();
$errors = [];
$saved  = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    [$data, $errors] = validate_person($_POST);
    if (!$errors) {
        $saved = $data;               // valid: show the summary
        $data  = person_defaults();   // and reset the form
    }
}

$editId      = null;
$csrf        = null;
$submitLabel = 'Submit';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP Output #1 - Personal Information Form</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="site-header">
    <div class="inner">
        <a class="brand" href="index.php">Saint Michael College of Caraga
            <small>IT/IS WEB - PHP Output #1</small></a>
    </div>
</header>

<main>
    <?php if ($saved): ?>
        <section class="card" aria-live="polite">
            <div class="alert success">Form submitted successfully. Here is the information you entered.</div>
            <h2>Submitted information</h2>
            <dl class="summary">
                <dt>First name</dt><dd><?= e($saved['first_name']) ?></dd>
                <dt>Last name</dt><dd><?= e($saved['last_name']) ?></dd>
                <dt>Age</dt><dd><?= e($saved['age']) ?></dd>
                <dt>Gender</dt><dd><?= e($saved['gender']) ?></dd>
                <dt>Email</dt><dd><?= e($saved['email']) ?></dd>
                <dt>Contact number</dt><dd><?= e($saved['contact_number']) ?></dd>
                <dt>Address</dt><dd><?= nl2br(e($saved['address'])) ?></dd>
            </dl>
        </section>
    <?php endif; ?>

    <section class="card">
        <h1>Personal information form</h1>
        <p class="lead">Fill in every field. Entries are checked in your browser and again on the server.</p>
        <?php if ($errors): ?>
            <div class="alert danger" role="alert">Please fix the highlighted fields and submit again.</div>
        <?php endif; ?>
        <?php require __DIR__ . '/includes/person_form.php'; ?>
    </section>
</main>

<footer class="site-footer">IT/IS WEB - Web Systems and Technologies</footer>
</body>
</html>
