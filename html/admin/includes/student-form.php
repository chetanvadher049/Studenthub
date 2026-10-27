<?php
$studentData = $studentData ?? [];
$isEdit = $isEdit ?? false;
?>
<form class="admin-card admin-form" method="post">
    <input type="hidden" name="csrf_token" value="<?= adminEscape(csrfToken()) ?>">
    <?php if ($isEdit): ?><input type="hidden" name="student_id" value="<?= adminEscape($studentData["student_id"] ?? "") ?>"><?php endif; ?>
    <label>Full name
        <input name="full_name" maxlength="100" required value="<?= adminEscape($studentData["student_name"] ?? $studentData["full_name"] ?? "") ?>">
    </label>
    <label>Username
        <input name="username" maxlength="50" pattern="[A-Za-z0-9_]{3,50}" required value="<?= adminEscape($studentData["username"] ?? "") ?>">
    </label>
    <label>Email
        <input type="email" name="email" maxlength="255" required value="<?= adminEscape($studentData["email"] ?? "") ?>">
    </label>
    <label>Phone
        <input name="phone" maxlength="10" value="<?= adminEscape($studentData["mobile_no"] ?? $studentData["phone"] ?? "") ?>">
    </label>
    <label>Course
        <input name="course" maxlength="100" value="<?= adminEscape($studentData["course"] ?? "") ?>">
    </label>
    <label>Gender
        <select name="gender">
            <option value="">Not specified</option>
            <?php foreach (["Male", "Female", "Other"] as $genderOption): ?>
                <option value="<?= adminEscape($genderOption) ?>"<?= ($studentData["gender"] ?? "") === $genderOption ? " selected" : "" ?>>
                    <?= adminEscape($genderOption) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>Academic year
        <input name="academic_year" maxlength="30" value="<?= adminEscape($studentData["academic_year"] ?? "") ?>">
    </label>
    <label>Status
        <select name="status">
            <?php foreach (["active", "inactive"] as $statusOption): ?>
                <option value="<?= $statusOption ?>"<?= ($studentData["status"] ?? "active") === $statusOption ? " selected" : "" ?>>
                    <?= ucfirst($statusOption) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>Password<?= $isEdit ? " (leave blank to keep current)" : "" ?>
        <input type="password" name="password" minlength="8" maxlength="100"<?= $isEdit ? "" : " required" ?> autocomplete="new-password">
    </label>
    <div class="admin-actions admin-full">
        <button class="admin-button" type="submit"><?= $isEdit ? "Save changes" : "Create student" ?></button>
        <a class="admin-button admin-button--muted" href="students.php">Cancel</a>
    </div>
</form>
