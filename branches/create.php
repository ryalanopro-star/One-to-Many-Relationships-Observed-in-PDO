<?php
require_once '../config/database.php';

$pageTitle = 'Add Branch';
$pdo = getPDO();

$errors   = [];
$oldInput = ['branch_name' => '', 'location' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Retrieve and sanitize input values
    $branchName = trim($_POST['branch_name'] ?? '');
    $location   = trim($_POST['location']    ?? '');

    // Store old input so form re-populates on error
    $oldInput = ['branch_name' => $branchName, 'location' => $location];

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

    // --- Insert into database if no errors ---
    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO branches (branch_name, location) VALUES (?, ?)");
        $stmt->execute([$branchName, $location]);

        // Redirect to branches list with success message
        header('Location: /restaurant-system/branches/index.php?success=created');
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
        <span class="app-breadcrumb-current">Add Branch</span>
    </nav>

    <div class="page-header">
        <span class="page-eyebrow">Branch Management</span>
        <h1 class="page-title">Add New Branch</h1>
        <p class="page-subtitle">Enter the details for the new restaurant branch.</p>
    </div>

    <div class="app-form-card">

        <?php if (!empty($errors)): ?>
            <div class="app-alert app-alert-danger">
                Please fix the errors below before submitting.
            </div>
        <?php endif; ?>

        <!-- Branch Form -->
        <form method="POST" action="" class="app-validate" novalidate>

            <!-- Branch Name Field -->
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
                <?php else: ?>
                    <span class="app-form-hint">The official name of the branch location.</span>
                <?php endif; ?>
            </div>

            <!-- Location Field -->
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
                <?php else: ?>
                    <span class="app-form-hint">Full address or city/district of the branch.</span>
                <?php endif; ?>
            </div>

            <hr class="form-divider">

            <div class="d-flex gap-2 flex-wrap">
                <button type="submit" class="app-btn-primary">
                    Save Branch
                </button>
                <a href="/restaurant-system/branches/index.php" class="app-btn-ghost">
                    Cancel
                </a>
            </div>

        </form>
    </div>

</div>

<?php require_once '../includes/footer.php'; ?>
