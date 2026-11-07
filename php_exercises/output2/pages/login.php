<?php
$errors = [];
$email  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = strtolower(post_str('email'));
    $password = post_str('password', false);

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Enter a valid email address.';
    }
    if ($password === '') {
        $errors['password'] = 'Password is required.';
    }

    if (!$errors) {
        $user = $_SESSION['users'][$email] ?? null;
        if ($user && password_verify($password, $user['hash'])) {
            session_regenerate_id(true);
            $_SESSION['user'] = $user['name'];
            header('Location: index.php?page=home');
            exit;
        }
        $errors['form'] = 'Incorrect email or password.';
    }
}
?>
<section class="card narrow">
    <h1>Log in</h1>
    <p class="lead">Enter the email and password you registered with.</p>

    <?php if (isset($_GET['loggedout'])): ?>
        <div class="alert info">You have been logged out.</div>
    <?php endif; ?>
    <?php if (isset($errors['form'])): ?>
        <div class="alert danger" role="alert"><?= e($errors['form']) ?></div>
    <?php endif; ?>

    <form method="post" action="index.php?page=login">
        <div class="grid" style="grid-template-columns:1fr">
            <div class="<?= field_class($errors, 'email') ?>">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" maxlength="100" required
                       autocomplete="email" value="<?= e($email) ?>">
                <?= field_error($errors, 'email') ?>
            </div>
            <div class="<?= field_class($errors, 'password') ?>">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required
                       autocomplete="current-password">
                <?= field_error($errors, 'password') ?>
            </div>
        </div>
        <div class="form-actions"><button type="submit" class="btn">Log in</button></div>
    </form>
    <p class="links">
        <a href="index.php?page=forgot">Forgot your password?</a><br>
        No account yet? <a href="index.php?page=register">Register</a>
    </p>
</section>
