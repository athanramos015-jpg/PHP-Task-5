<?php
$errors  = [];
$old     = ['name' => '', 'email' => ''];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['name']  = post_str('name');
    $old['email'] = strtolower(post_str('email'));
    $password     = post_str('password', false);
    $confirm      = post_str('confirm', false);

    if ($old['name'] === '') {
        $errors['name'] = 'Full name is required.';
    } elseif (!preg_match('/^[\p{L}][\p{L} .\'\-]{1,79}$/u', $old['name'])) {
        $errors['name'] = 'Use letters only (2 to 80 characters).';
    }

    if ($old['email'] === '') {
        $errors['email'] = 'Email is required.';
    } elseif (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Enter a valid email address.';
    } elseif (isset($_SESSION['users'][$old['email']])) {
        $errors['email'] = 'That email is already registered.';
    }

    if (strlen($password) < 8 || !preg_match('/[A-Za-z]/', $password) || !preg_match('/\d/', $password)) {
        $errors['password'] = 'Password needs at least 8 characters with a letter and a number.';
    }

    if ($confirm !== $password) {
        $errors['confirm'] = 'Passwords do not match.';
    }

    if (!$errors) {
        $_SESSION['users'][$old['email']] = [
            'name' => $old['name'],
            'hash' => password_hash($password, PASSWORD_DEFAULT),
        ];
        $success = true;
        $old = ['name' => '', 'email' => ''];
    }
}
?>
<section class="card narrow">
    <h1>Create an account</h1>
    <p class="lead">Register to access the site.</p>

    <?php if ($success): ?>
        <div class="alert success">Account created. You can now <a href="index.php?page=login">log in</a>.</div>
    <?php endif; ?>

    <form method="post" action="index.php?page=register">
        <div class="grid" style="grid-template-columns:1fr">
            <div class="<?= field_class($errors, 'name') ?>">
                <label for="name">Full name</label>
                <input type="text" id="name" name="name" maxlength="80" required
                       autocomplete="name" value="<?= e($old['name']) ?>">
                <?= field_error($errors, 'name') ?>
            </div>
            <div class="<?= field_class($errors, 'email') ?>">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" maxlength="100" required
                       autocomplete="email" value="<?= e($old['email']) ?>">
                <?= field_error($errors, 'email') ?>
            </div>
            <div class="<?= field_class($errors, 'password') ?>">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" minlength="8" required
                       autocomplete="new-password">
                <span class="hint">At least 8 characters, with a letter and a number.</span>
                <?= field_error($errors, 'password') ?>
            </div>
            <div class="<?= field_class($errors, 'confirm') ?>">
                <label for="confirm">Confirm password</label>
                <input type="password" id="confirm" name="confirm" minlength="8" required
                       autocomplete="new-password">
                <?= field_error($errors, 'confirm') ?>
            </div>
        </div>
        <div class="form-actions"><button type="submit" class="btn">Register</button></div>
    </form>
    <p class="links">Already registered? <a href="index.php?page=login">Log in</a></p>
</section>
