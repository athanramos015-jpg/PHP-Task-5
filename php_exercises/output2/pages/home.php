<section class="card">
    <h1>Welcome<?= is_logged_in() ? ', ' . e($_SESSION['user']) : '' ?></h1>
    <p class="lead">
        This site has four pages built from reusable PHP parts: the header, navigation
        and footer are loaded with <code>require</code> on every page.
    </p>
    <?php if (is_logged_in()): ?>
        <div class="alert success">You are logged in.</div>
        <p class="links"><a class="btn secondary" href="index.php?page=logout">Log out</a></p>
    <?php else: ?>
        <p class="links">
            <a class="btn" href="index.php?page=register">Create an account</a>
            <a class="btn secondary" href="index.php?page=login">Log in</a>
        </p>
    <?php endif; ?>
</section>
