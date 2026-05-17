<?php
require_once '../config/database.php';

$pageTitle = 'Edit Branch';
$pdo = getPDO();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: /restaurant-system/branches/index.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM branches WHERE id = ?");
$stmt->execute([$id]);
$branch = $stmt->fetch();

if (!$branch) {
    header('Location: /restaurant-system/branches/index.php');
    exit;
}

$errors   = [];
$oldInput = [
    'branch_name' => $branch['branch_name'],
    'location'    => $branch['location'],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $branchName = trim($_POST['branch_name'] ?? '');
    $location   = trim($_POST['location']    ?? '');

    $oldInput = ['branch_name' => $branchName, 'location' => $location];

    // --- Validation ---
    if (empty($branchName)) {
        $errors['branch_name'] = 'Branch name is required.';
    } elseif (strlen($branchName) > 100) {
        $errors['branch_name'] = 'Branch name must be 100 characters or less.';
    }

    if (empty($location)) {
        $errors['location'] = 'Location is required.';
    } elseif (strlen($location) > 150) {
        $errors['location'] = 'Location must be 150 characters or less.';
    }

    // --- Update if valid ---
    if (empty($errors)) {
        // Prepared UPDATE: only changes branch_name and location for this id
        $stmt = $pdo->prepare("UPDATE branches SET branch_name = ?, location = ? WHERE id = ?");
        $stmt->execute([$branchName, $location, $id]);

        header('Location: /restaurant-system/branches/index.php?success=updated');
        exit;
    }
}

require_once '../includes/header.php';
?>

<div class="container">

    <nav class="app-breadcrumb">
        <a href="/restaurant-system/index.php">Dashboard</a>
        <span class="app-breadcrumb-sep">/</span>
        <a href="/restaurant-system/branches/index.php">Branches</a>
        <span class="app-breadcrumb-sep">/</span>
        <span class="app-breadcrumb-current">Edit Branch</span>
    </nav>

    <div class="page-header">
        <span class="page-eyebrow">Branch Management</span>
        <h1 class="page-title">Edit Branch</h1>
        <p class="page-subtitle">Update the details for this branch.</p>
    </div>

    <div class="app-form-card">

        <div class="app-alert app-alert-info" style="margin-bottom:1.5rem">
            Editing: <strong><?= htmlspecialchars($branch['branch_name']) ?></strong>
            &nbsp;&mdash;&nbsp; Branch ID: <?= $branch['id'] ?>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="app-alert app-alert-danger">
                Please fix the errors below before submitting.
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="app-validate" novalidate>

            <!-- Branch Name -->
            <div class="app-form-group">
                <label for="branch_name" class="app-form-label">Branch Name</label>
                <input
                    type="text"
                    id="branch_name"
                    name="branch_name"
                    class="app-form-control <?= isset($errors['branch_name']) ? 'is-invalid' : '' ?>"
                    placeholder="e.g. Bonifacio Global City"
                    value="<?= htmlspecialchars($oldInput['branch_name']) ?>"
                    required
                    maxlength="100"
                >
                <?php if (isset($errors['branch_name'])): ?>
                    <span class="app-error-text"><?= $errors['branch_name'] ?></span>
                <?php endif; ?>
            </div>

            <!-- Location -->
            <div class="app-form-group">
                <label for="location" class="app-form-label">Location</label>
                <input
                    type="text"
                    id="location"
                    name="location"
                    class="app-form-control <?= isset($errors['location']) ? 'is-invalid' : '' ?>"
                    placeholder="e.g. BGC, Taguig City"
                    value="<?= htmlspecialchars($oldInput['location']) ?>"
                    required
                    maxlength="150"
                >
                <?php if (isset($errors['location'])): ?>
                    <span class="app-error-text"><?= $errors['location'] ?></span>
                <?php endif; ?>
            </div>

            <hr class="form-divider">

            <div class="d-flex gap-2 flex-wrap">
                <button type="submit" class="app-btn-primary">
                    Update Branch
                </button>
                <a href="/restaurant-system/branches/index.php" class="app-btn-ghost">
                    Cancel
                </a>
            </div>

        </form>
    </div>

</div>

<?php require_once '../includes/footer.php'; ?>
