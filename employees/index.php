<?php
require_once '../config/database.php';

$pageTitle = 'Employees';
$pdo = getPDO();

$success = $_GET['success'] ?? '';

$filterBranchId = filter_input(INPUT_GET, 'branch_id', FILTER_VALIDATE_INT);

if ($filterBranchId) {
    $stmt = $pdo->prepare("
        SELECT
            e.id,
            e.employee_name,
            e.position,
            b.id          AS branch_id,
            b.branch_name,
            b.location
        FROM employees e
        INNER JOIN branches b ON e.branch_id = b.id
        WHERE e.branch_id = ?
        ORDER BY e.id DESC
    ");
    $stmt->execute([$filterBranchId]);

    $filterBranch = $pdo->prepare("SELECT branch_name FROM branches WHERE id = ?");
    $filterBranch->execute([$filterBranchId]);
    $filterBranchName = $filterBranch->fetchColumn();
} else {
    $stmt = $pdo->query("
        SELECT
            e.id,
            e.employee_name,
            e.position,
            b.id          AS branch_id,
            b.branch_name,
            b.location
        FROM employees e
        INNER JOIN branches b ON e.branch_id = b.id
        ORDER BY e.id DESC
    ");
    $filterBranchName = null;
}

$employees = $stmt->fetchAll();

$branches = $pdo->query("SELECT id, branch_name FROM branches ORDER BY branch_name ASC")->fetchAll();

require_once '../includes/header.php';
?>

<div class="container">

    <nav class="app-breadcrumb">
        <a href="/restaurant-system/index.php">Dashboard</a>
        <span class="app-breadcrumb-sep">/</span>
        <span class="app-breadcrumb-current">Employees</span>
    </nav>

    <div class="d-flex align-items-start justify-content-between flex-wrap gap-3 page-header">
        <div>
            <span class="page-eyebrow">Management</span>
            <h1 class="page-title">Employees</h1>
            <p class="page-subtitle">
                <?php if ($filterBranchName): ?>
                    Showing staff for: <strong><?= htmlspecialchars($filterBranchName) ?></strong>
                    &mdash; <a href="/restaurant-system/employees/index.php">Show all</a>
                <?php else: ?>
                    All employees across every branch.
                <?php endif; ?>
            </p>
        </div>
        <a href="/restaurant-system/employees/create.php" class="app-btn-primary">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add Employee
        </a>
    </div>

    <?php if ($success === 'created'): ?>
        <div class="app-alert app-alert-success auto-dismiss">Employee was added successfully.</div>
    <?php elseif ($success === 'updated'): ?>
        <div class="app-alert app-alert-success auto-dismiss">Employee was updated successfully.</div>
    <?php elseif ($success === 'deleted'): ?>
        <div class="app-alert app-alert-success auto-dismiss">Employee was deleted successfully.</div>
    <?php endif; ?>

    <?php if (!empty($branches)): ?>
        <div class="d-flex align-items-center gap-2 flex-wrap mb-4">
            <span class="text-muted-sm" style="white-space:nowrap">Filter by branch:</span>
            <a href="/restaurant-system/employees/index.php"
               class="action-btn <?= !$filterBranchId ? 'action-btn-edit' : 'action-btn-ghost' ?>"
               style="text-decoration:none;border:1px solid var(--gray-200)">
                All
            </a>
            <?php foreach ($branches as $b): ?>
                <a href="/restaurant-system/employees/index.php?branch_id=<?= $b['id'] ?>"
                   class="action-btn <?= ($filterBranchId == $b['id']) ? 'action-btn-edit' : '' ?>"
                   style="text-decoration:none;border:1px solid var(--gray-200)">
                    <?= htmlspecialchars($b['branch_name']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="app-card">
        <div class="app-card-header">
            <span class="app-card-title">Employee List</span>
            <span class="app-badge badge-count"><?= count($employees) ?> total</span>
        </div>
        <div class="app-table-wrap">
            <table class="app-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee Name</th>
                        <th>Position</th>
                        <th>Branch</th>
                        <th>Location</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($employees) === 0): ?>
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                    </div>
                                    <div class="empty-state-title">No employees found</div>
                                    <div class="empty-state-desc">
                                        <?= $filterBranchName ? 'No employees in this branch yet.' : 'Add your first employee to get started.' ?>
                                    </div>
                                    <a href="/restaurant-system/employees/create.php" class="app-btn-primary">Add Employee</a>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($employees as $index => $emp): ?>
                            <tr>
                                <td class="text-muted-sm"><?= $index + 1 ?></td>

                                <td style="font-weight:500;color:var(--dark)">
                                    <?= htmlspecialchars($emp['employee_name']) ?>
                                </td>

                                <td class="text-muted-sm">
                                    <?= htmlspecialchars($emp['position']) ?>
                                </td>

                                <!-- Branch name comes from INNER JOIN on branches table -->
                                <td>
                                    <span class="app-badge badge-branch">
                                        <?= htmlspecialchars($emp['branch_name']) ?>
                                    </span>
                                </td>

                                <td class="text-muted-sm">
                                    <?= htmlspecialchars($emp['location']) ?>
                                </td>

                                <td>
                                    <div class="table-actions">
                                        <a href="/restaurant-system/employees/edit.php?id=<?= $emp['id'] ?>"
                                           class="action-btn action-btn-edit">
                                            Edit
                                        </a>

                                        <form method="POST"
                                              action="/restaurant-system/employees/delete.php"
                                              class="delete-form"
                                              data-name="<?= htmlspecialchars($emp['employee_name']) ?>">
                                            <input type="hidden" name="id" value="<?= $emp['id'] ?>">
                                            <button type="submit" class="action-btn action-btn-delete">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php require_once '../includes/footer.php'; ?>
