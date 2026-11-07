<?php /* Expects: $faculty, $search */ ?>
<section class="card">
    <div class="toolbar">
        <h1 style="margin:0">Faculty (<?= count($faculty) ?>)</h1>
        <div class="actions">
            <form method="get" action="index.php" role="search">
                <input type="hidden" name="action" value="index">
                <input type="search" name="q" placeholder="Search name, position or address"
                       aria-label="Search faculty" value="<?= e($search) ?>">
                <button class="btn small" type="submit">Search</button>
                <?php if ($search !== ''): ?>
                    <a class="btn small secondary" href="index.php">Reset</a>
                <?php endif; ?>
            </form>
            <a class="btn" href="index.php?action=create">Add faculty</a>
        </div>
    </div>

    <?php if (!$faculty): ?>
        <p class="empty">
            <?= $search !== '' ? 'No faculty match your search.' : 'No faculty records yet. Use "Add faculty" to create the first one.' ?>
        </p>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>ID</th><th>Name</th><th>Age</th><th>Gender</th>
                    <th>Position</th><th>Salary</th><th>Address</th><th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($faculty as $f): ?>
                    <?php
                    $fullName = trim($f['first_name'] . ' ' . ($f['middle_name'] ?? '') . ' ' . $f['last_name']);
                    $fullName = preg_replace('/\s+/', ' ', $fullName);
                    ?>
                    <tr>
                        <td><?= (int) $f['id'] ?></td>
                        <td><?= e($fullName) ?></td>
                        <td><?= (int) $f['age'] ?></td>
                        <td><?= e($f['gender']) ?></td>
                        <td><?= e($f['position']) ?></td>
                        <td>&#8369;<?= e(number_format((float) $f['salary'], 2)) ?></td>
                        <td><?= e($f['address']) ?></td>
                        <td>
                            <div class="actions">
                                <a class="btn small secondary" href="index.php?action=edit&amp;id=<?= (int) $f['id'] ?>">Edit</a>
                                <form method="post" action="index.php?action=delete"
                                      onsubmit="return confirm('Delete this faculty record permanently?');">
                                    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                                    <input type="hidden" name="id" value="<?= (int) $f['id'] ?>">
                                    <button type="submit" class="btn small danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
