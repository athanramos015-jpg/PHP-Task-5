<?php
/**
 * PHP Output #3 and #4
 * Registration form saved to MySQL, with the list of registered persons below it
 * (search, pagination, edit and delete).
 */
session_start();
require __DIR__ . '/config.php';
require __DIR__ . '/includes/validation.php';

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf'];

function flash_set(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'msg' => $message];
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

$pdo     = null;
$dbError = null;
try {
    $pdo = db_connect();
} catch (PDOException $ex) {
    $dbError = 'Cannot connect to the database. Start MySQL, check config.php, and import database/php_exercises.sql.';
}

$data   = person_defaults();
$errors = [];
$editId = null;

/* ---------- Handle form submissions (create / update / delete) ---------- */
if ($pdo && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = is_string($_POST['action'] ?? null) ? $_POST['action'] : '';

    if (!hash_equals($csrf, (string) ($_POST['csrf'] ?? ''))) {
        flash_set('danger', 'Your session expired. Please try again.');
        redirect('index.php');
    }

    if ($action === 'delete') {
        $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
        if ($id) {
            $st = $pdo->prepare('DELETE FROM registered_persons WHERE id = :id');
            $st->execute([':id' => $id]);
            flash_set('success', 'Record deleted.');
        }
        redirect('index.php');
    }

    if ($action === 'create' || $action === 'update') {
        [$data, $errors] = validate_person($_POST);

        $id = null;
        if ($action === 'update') {
            $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
            if (!$id) {
                flash_set('danger', 'That record could not be found.');
                redirect('index.php');
            }
            $editId = $id;
        }

        if (!$errors) {
            try {
                if ($action === 'create') {
                    $st = $pdo->prepare(
                        'INSERT INTO registered_persons
                            (first_name, last_name, age, gender, email, address, contact_number)
                         VALUES (:first_name, :last_name, :age, :gender, :email, :address, :contact_number)'
                    );
                    $st->execute([
                        ':first_name'     => $data['first_name'],
                        ':last_name'      => $data['last_name'],
                        ':age'            => (int) $data['age'],
                        ':gender'         => $data['gender'],
                        ':email'          => $data['email'],
                        ':address'        => $data['address'],
                        ':contact_number' => $data['contact_number'],
                    ]);
                    flash_set('success', 'Record saved successfully.');
                } else {
                    $st = $pdo->prepare(
                        'UPDATE registered_persons
                            SET first_name = :first_name, last_name = :last_name, age = :age,
                                gender = :gender, email = :email, address = :address,
                                contact_number = :contact_number
                          WHERE id = :id'
                    );
                    $st->execute([
                        ':first_name'     => $data['first_name'],
                        ':last_name'      => $data['last_name'],
                        ':age'            => (int) $data['age'],
                        ':gender'         => $data['gender'],
                        ':email'          => $data['email'],
                        ':address'        => $data['address'],
                        ':contact_number' => $data['contact_number'],
                        ':id'             => $id,
                    ]);
                    flash_set('success', 'Record updated.');
                }
                redirect('index.php');
            } catch (PDOException $ex) {
                if ((string) $ex->getCode() === '23000') {
                    $errors['email'] = 'That email address is already registered.';
                } else {
                    $errors['form'] = 'The record could not be saved. Please try again.';
                }
            }
        }
    }
}

/* ---------- Load a record into the form for editing ---------- */
if ($pdo && $_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['edit'])) {
    $id = filter_var($_GET['edit'], FILTER_VALIDATE_INT);
    if ($id) {
        $st = $pdo->prepare(
            'SELECT first_name, last_name, age, gender, email, address, contact_number
               FROM registered_persons WHERE id = :id'
        );
        $st->execute([':id' => $id]);
        $row = $st->fetch();
        if ($row) {
            $data   = array_merge(person_defaults(), $row);
            $editId = $id;
        }
    }
}

/* ---------- List: search + pagination ---------- */
$search  = isset($_GET['q']) && is_string($_GET['q']) ? trim($_GET['q']) : '';
$perPage = 5;
$total   = 0;
$people  = [];
$pages   = 1;
$page    = 1;

if ($pdo) {
    $where  = '';
    $params = [];
    if ($search !== '') {
        $where = 'WHERE first_name LIKE :q1 OR last_name LIKE :q2 OR email LIKE :q3 OR address LIKE :q4';
        $like  = '%' . $search . '%';
        $params = [':q1' => $like, ':q2' => $like, ':q3' => $like, ':q4' => $like];
    }

    $st = $pdo->prepare("SELECT COUNT(*) FROM registered_persons $where");
    $st->execute($params);
    $total = (int) $st->fetchColumn();

    $pages  = max(1, (int) ceil($total / $perPage));
    $page   = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $page   = min(max(1, $page), $pages);
    $offset = ($page - 1) * $perPage;

    $st = $pdo->prepare(
        "SELECT id, first_name, last_name, age, gender, email, contact_number, address, created_at
           FROM registered_persons $where
       ORDER BY id DESC
          LIMIT $perPage OFFSET $offset"
    );
    $st->execute($params);
    $people = $st->fetchAll();
}

function page_url(int $page, string $search): string
{
    $query = ['page' => $page];
    if ($search !== '') {
        $query['q'] = $search;
    }
    return 'index.php?' . http_build_query($query) . '#registered';
}

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$submitLabel = $editId ? 'Update record' : 'Save record';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP Output #3 and #4 - Registration with Database</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="site-header">
    <div class="inner">
        <a class="brand" href="index.php">Saint Michael College of Caraga
            <small>IT/IS WEB - PHP Output #3 and #4</small></a>
    </div>
</header>

<main>
    <?php if ($flash): ?>
        <div class="alert <?= e($flash['type']) ?>" role="status"><?= e($flash['msg']) ?></div>
    <?php endif; ?>
    <?php if ($dbError): ?>
        <div class="alert danger" role="alert"><?= e($dbError) ?></div>
    <?php endif; ?>

    <section class="card">
        <h1><?= $editId ? 'Edit registered person' : 'Register a person' ?></h1>
        <p class="lead">The record is saved directly to the database when you submit.</p>
        <?php if ($errors && !isset($errors['form'])): ?>
            <div class="alert danger" role="alert">Please fix the highlighted fields and submit again.</div>
        <?php endif; ?>
        <?php if ($pdo) { require __DIR__ . '/includes/person_form.php'; } ?>
    </section>

    <section class="card" id="registered">
        <div class="toolbar">
            <h2 style="margin:0">List of registered persons (<?= (int) $total ?>)</h2>
            <form method="get" action="index.php#registered" role="search">
                <input type="search" name="q" placeholder="Search name, email or address"
                       aria-label="Search registered persons" value="<?= e($search) ?>">
                <button class="btn small" type="submit">Search</button>
                <?php if ($search !== ''): ?>
                    <a class="btn small secondary" href="index.php#registered">Reset</a>
                <?php endif; ?>
            </form>
        </div>

        <?php if (!$people): ?>
            <p class="empty">
                <?= $search !== '' ? 'No records match your search.' : 'No records yet. Submit the form above to add the first one.' ?>
            </p>
        <?php else: ?>
            <div class="table-wrap">
                <table>
                    <thead>
                    <tr>
                        <th>#</th><th>Name</th><th>Age</th><th>Gender</th><th>Email</th>
                        <th>Contact</th><th>Address</th><th>Registered</th><th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($people as $p): ?>
                        <tr>
                            <td><?= (int) $p['id'] ?></td>
                            <td><?= e($p['first_name'] . ' ' . $p['last_name']) ?></td>
                            <td><?= (int) $p['age'] ?></td>
                            <td><?= e($p['gender']) ?></td>
                            <td><?= e($p['email']) ?></td>
                            <td><?= e($p['contact_number']) ?></td>
                            <td><?= e($p['address']) ?></td>
                            <td><?= e(date('M j, Y', strtotime($p['created_at']))) ?></td>
                            <td>
                                <div class="actions">
                                    <a class="btn small secondary" href="index.php?edit=<?= (int) $p['id'] ?>">Edit</a>
                                    <form method="post" action="index.php"
                                          onsubmit="return confirm('Delete this record permanently?');">
                                        <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
                                        <button type="submit" class="btn small danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($pages > 1): ?>
                <nav class="pagination" aria-label="Pages">
                    <?php for ($i = 1; $i <= $pages; $i++): ?>
                        <?php if ($i === $page): ?>
                            <span class="current" aria-current="page"><?= $i ?></span>
                        <?php else: ?>
                            <a href="<?= e(page_url($i, $search)) ?>"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </section>
</main>

<footer class="site-footer">IT/IS WEB - Web Systems and Technologies</footer>
</body>
</html>
