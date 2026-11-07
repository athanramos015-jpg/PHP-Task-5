<?php /* Expects: $data, $errors, $editId (int|null) */ ?>
<section class="card">
    <h1><?= $editId ? 'Edit faculty member' : 'Add faculty member' ?></h1>
    <p class="lead">Fields marked optional can be left blank. Everything else is required.</p>

    <?php if ($errors): ?>
        <div class="alert danger" role="alert">Please fix the highlighted fields and submit again.</div>
    <?php endif; ?>

    <form method="post" action="index.php?action=<?= $editId ? 'update' : 'store' ?>">
        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
        <?php if ($editId): ?>
            <input type="hidden" name="id" value="<?= (int) $editId ?>">
        <?php endif; ?>

        <div class="grid">
            <div class="<?= field_class($errors, 'first_name') ?>">
                <label for="first_name">First name</label>
                <input type="text" id="first_name" name="first_name" maxlength="50" required
                       value="<?= e($data['first_name']) ?>">
                <?= field_error($errors, 'first_name') ?>
            </div>

            <div class="<?= field_class($errors, 'middle_name') ?>">
                <label for="middle_name">Middle name (optional)</label>
                <input type="text" id="middle_name" name="middle_name" maxlength="50"
                       value="<?= e($data['middle_name']) ?>">
                <?= field_error($errors, 'middle_name') ?>
            </div>

            <div class="<?= field_class($errors, 'last_name') ?>">
                <label for="last_name">Last name</label>
                <input type="text" id="last_name" name="last_name" maxlength="50" required
                       value="<?= e($data['last_name']) ?>">
                <?= field_error($errors, 'last_name') ?>
            </div>

            <div class="<?= field_class($errors, 'age') ?>">
                <label for="age">Age</label>
                <input type="number" id="age" name="age" min="18" max="100" step="1" required
                       inputmode="numeric" value="<?= e($data['age']) ?>">
                <?= field_error($errors, 'age') ?>
            </div>

            <div class="<?= field_class($errors, 'gender') ?>">
                <span class="label" id="gender-label">Gender</span>
                <div class="radio-group" role="radiogroup" aria-labelledby="gender-label">
                    <?php foreach (Faculty::GENDERS as $g): ?>
                        <label>
                            <input type="radio" name="gender" value="<?= e($g) ?>" required
                                <?= $data['gender'] === $g ? 'checked' : '' ?>>
                            <?= e($g) ?>
                        </label>
                    <?php endforeach; ?>
                </div>
                <?= field_error($errors, 'gender') ?>
            </div>

            <div class="<?= field_class($errors, 'position') ?>">
                <label for="position">Position</label>
                <input type="text" id="position" name="position" maxlength="100" required
                       placeholder="e.g. Instructor I" value="<?= e($data['position']) ?>">
                <?= field_error($errors, 'position') ?>
            </div>

            <div class="<?= field_class($errors, 'salary') ?>">
                <label for="salary">Salary (PHP)</label>
                <input type="number" id="salary" name="salary" min="0.01" max="9999999.99" step="0.01" required
                       inputmode="decimal" value="<?= e($data['salary']) ?>">
                <?= field_error($errors, 'salary') ?>
            </div>

            <div class="<?= field_class($errors, 'address', 'full') ?>">
                <label for="address">Address</label>
                <textarea id="address" name="address" maxlength="255" required><?= e($data['address']) ?></textarea>
                <?= field_error($errors, 'address') ?>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn"><?= $editId ? 'Update faculty' : 'Save faculty' ?></button>
            <a class="btn secondary" href="index.php">Cancel</a>
        </div>
    </form>
</section>
