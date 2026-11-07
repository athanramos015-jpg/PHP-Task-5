<?php
/**
 * Registration form partial.
 * Expects: $data (array), $errors (array), $submitLabel (string),
 *          $editId (int|null), $csrf (string|null)
 */
$editId = $editId ?? null;
$csrf   = $csrf ?? null;
?>
<form method="post" action="index.php" id="person-form">
    <?php if ($csrf !== null): ?>
        <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
        <input type="hidden" name="action" value="<?= $editId ? 'update' : 'create' ?>">
    <?php endif; ?>
    <?php if ($editId): ?>
        <input type="hidden" name="id" value="<?= (int) $editId ?>">
    <?php endif; ?>

    <?php if (isset($errors['form'])): ?>
        <div class="alert danger"><?= e($errors['form']) ?></div>
    <?php endif; ?>

    <div class="grid">
        <div class="<?= field_class($errors, 'first_name') ?>">
            <label for="first_name">First name</label>
            <input type="text" id="first_name" name="first_name" maxlength="50" required
                   autocomplete="given-name" value="<?= e($data['first_name']) ?>">
            <?= field_error($errors, 'first_name') ?>
        </div>

        <div class="<?= field_class($errors, 'last_name') ?>">
            <label for="last_name">Last name</label>
            <input type="text" id="last_name" name="last_name" maxlength="50" required
                   autocomplete="family-name" value="<?= e($data['last_name']) ?>">
            <?= field_error($errors, 'last_name') ?>
        </div>

        <div class="<?= field_class($errors, 'age') ?>">
            <label for="age">Age</label>
            <input type="number" id="age" name="age" min="1" max="120" step="1" required
                   inputmode="numeric" value="<?= e($data['age']) ?>">
            <?= field_error($errors, 'age') ?>
        </div>

        <div class="<?= field_class($errors, 'gender') ?>">
            <span class="label" id="gender-label">Gender</span>
            <div class="radio-group" role="radiogroup" aria-labelledby="gender-label">
                <?php foreach (['Male', 'Female', 'Other'] as $g): ?>
                    <label>
                        <input type="radio" name="gender" value="<?= e($g) ?>" required
                            <?= $data['gender'] === $g ? 'checked' : '' ?>>
                        <?= e($g) ?>
                    </label>
                <?php endforeach; ?>
            </div>
            <?= field_error($errors, 'gender') ?>
        </div>

        <div class="<?= field_class($errors, 'email') ?>">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" maxlength="100" required
                   autocomplete="email" value="<?= e($data['email']) ?>">
            <?= field_error($errors, 'email') ?>
        </div>

        <div class="<?= field_class($errors, 'contact_number') ?>">
            <label for="contact_number">Contact number</label>
            <input type="tel" id="contact_number" name="contact_number" maxlength="13" required
                   pattern="^(09|\+639)\d{9}$" placeholder="09123456789"
                   autocomplete="tel" value="<?= e($data['contact_number']) ?>">
            <span class="hint">Mobile number, 09XXXXXXXXX or +639XXXXXXXXX.</span>
            <?= field_error($errors, 'contact_number') ?>
        </div>

        <div class="<?= field_class($errors, 'address', 'full') ?>">
            <label for="address">Address</label>
            <textarea id="address" name="address" maxlength="255" required
                      autocomplete="street-address"><?= e($data['address']) ?></textarea>
            <?= field_error($errors, 'address') ?>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn"><?= e($submitLabel) ?></button>
        <?php if ($editId): ?>
            <a class="btn secondary" href="index.php">Cancel</a>
        <?php else: ?>
            <button type="reset" class="btn secondary">Clear</button>
        <?php endif; ?>
    </div>
</form>
