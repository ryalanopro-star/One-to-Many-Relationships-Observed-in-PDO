<?php
require_once '../config/database.php';

$pageTitle = 'Edit Employee';
$pdo = getPDO();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: /restaurant-system/employees/index.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM employees WHERE id = ?");
$stmt->execute([$id]);
$employee = $stmt->fetch();

if (!$employee) {
    header('Location: /restaurant-system/employees/index.php');
    exit;
}

$branches = $pdo->query("SELECT id, branch_name FROM branches ORDER BY branch_name ASC")->fetchAll();

$errors   = [];
$oldInput = [
    'employee_name' => $employee['employee_name'],
    'position'      => $employee['position'],
    'branch_id'     => $employee['branch_id'],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $employeeName = trim($_POST['employee_name'] ?? '');
    $position     = trim($_POST['position']      ?? '');
    $branchId     = (int) ($_POST['branch_id']   ?? 0);

    $oldInput = [
        'employee_name' => $employeeName,
        'position'      => $position,
        'branch_id'     => $branchId,
    ];

    // --- Validation ---
    if (empty($employeeName)) {
        $errors['employee_name'] = 'Employee name is required.';
    } elseif (strlen($employeeName) > 100) {
        $errors['employee_name'] = 'Name must be 100 characters or less.';
    }

    if (empty($position)) {
        $errors['position'] = 'Position is required.';
    } elseif (strlen($position) > 100) {
        $errors['position'] = 'Position must be 100 characters or less.';
    }

    if (!$branchId) {
        $errors['branch_id'] = 'Please select a branch.';
    } else {
        $check = $pdo->prepare("SELECT id FROM branches WHERE id = ?");
        $check->execute([$branchId]);
        if (!$check->fetch()) {
            $errors['branch_id'] = 'Selected branch is invalid.';
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("
            UPDATE employees
            SET branch_id = ?, employee_name = ?, position = ?
            WHERE id = ?
        ");
        $stmt->execute([$branchId, $employeeName, $position, $id]);

        header('Location: /restaurant-system/employees/index.php?success=updated');
        exit;
    }
}

require_once '../includes/header.php';
?>

<div class="container">

    <nav class="app-breadcrumb">
        <a href="/restaurant-system/index.php">Dashboard</a>
        <span class="app-breadcrumb-sep">/</span>
        <a href="/restaurant-system/employees/index.php">Employees</a>
        <span class="app-breadcrumb-sep">/</span>
        <span class="app-breadcrumb-current">Edit Employee</span>
    </nav>

    <div class="page-header">
        <span class="page-eyebrow">Employee Management</span>
        <h1 class="page-title">Edit Employee</h1>
        <p class="page-subtitle">Update the information for this employee.</p>
    </div>

    <div class="app-form-card">

        <div class="app-alert app-alert-info" style="margin-bottom:1.5rem">
            Editing: <strong><?= htmlspecialchars($employee['employee_name']) ?></strong>
            - Employee ID: <?= $employee['id'] ?>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="app-alert app-alert-danger">
                Please fix the errors below before submitting.
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="app-validate" novalidate>

            <div class="app-form-group">
                <label for="branch_id" class="app-form-label">Assigned Branch</label>
                <select
                    id="branch_id"
                    name="branch_id"
                    class="app-form-control <?= isset($errors['branch_id']) ? 'is-invalid' : '' ?>"
                    required
                >
                    <option value="">Select a branch...</option>
                    <?php foreach ($branches as $branch): ?>
                        <option value="<?= $branch['id'] ?>"
                            <?= ($oldInput['branch_id'] == $branch['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($branch['branch_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['branch_id'])): ?>
                    <span class="app-error-text"><?= $errors['branch_id'] ?></span>
                <?php else: ?>
                    <span class="app-form-hint">You can reassign this employee to a different branch.</span>
                <?php endif; ?>
            </div>

            <div class="app-form-group">
                <label for="employee_name" class="app-form-label">Full Name</label>
                <input
                    type="text"
                    id="employee_name"
                    name="employee_name"
                    class="app-form-control <?= isset($errors['employee_name']) ? 'is-invalid' : '' ?>"
                    placeholder="e.g. Miguel Santos"
                    value="<?= htmlspecialchars($oldInput['employee_name']) ?>"
                    required
                    maxlength="100"
                >
                <?php if (isset($errors['employee_name'])): ?>
                    <span class="app-error-text"><?= $errors['employee_name'] ?></span>
                <?php endif; ?>
            </div>

            <div class="app-form-group">
                <label for="position" class="app-form-label">Position / Role</label>
                <input
                    type="text"
                    id="position"
                    name="position"
                    class="app-form-control <?= isset($errors['position']) ? 'is-invalid' : '' ?>"
                    placeholder="e.g. Head Chef, Cashier"
                    value="<?= htmlspecialchars($oldInput['position']) ?>"
                    required
                    maxlength="100"
                >
                <?php if (isset($errors['position'])): ?>
                    <span class="app-error-text"><?= $errors['position'] ?></span>
                <?php endif; ?>
            </div>

            <hr class="form-divider">

            <div class="d-flex gap-2 flex-wrap">
                <button type="submit" class="app-btn-primary">Update Employee</button>
                <a href="/restaurant-system/employees/index.php" class="app-btn-ghost">Cancel</a>
            </div>

        </form>
    </div>

</div>

<?php require_once '../includes/footer.php'; ?>
