<?php
$errors = [];
$email  = '';
$sent   = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(post_str('email'));
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Enter a valid email address.';
    } else {
        // Same message whether or not the account exists, so emails cannot be guessed.
        // A real site would generate a token here and send a reset link by mail.
        $sent  = true;
        $email = '';
    }
}
?>
<section class="card narrow">
    <h1>Forgot password</h1>
    <p class="lead">Enter your email and we will send you a link to reset your password.</p>

    <?php if ($sent): ?>
        <div class="alert success" role="status">
            If that email is registered, a reset link has been sent. Check your inbox.
        </div>
    <?php endif; ?>

    <form method="post" action="index.php?page=forgot">
        <div class="<?= field_class($errors, 'email') ?>">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" maxlength="100" required
                   autocomplete="email" value="<?= e($email) ?>">
            <?= field_error($errors, 'email') ?>
        </div>
        <div class="form-actions"><button type="submit" class="btn">Send reset link</button></div>
    </form>
    <p class="links"><a href="index.php?page=login">Back to log in</a></p>
</section>
