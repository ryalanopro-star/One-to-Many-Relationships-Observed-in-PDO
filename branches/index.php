<?php
require_once '../config/database.php';

$pageTitle = 'Branches';
$pdo = getPDO();

$success = $_GET['success'] ?? '';
$error   = $_GET['error']   ?? '';

$branches = $pdo->query("
    SELECT
        b.id,
        b.branch_name,
        b.location,
        COUNT(e.id) AS emp_count
    FROM branches b
    LEFT JOIN employees e ON e.branch_id = b.id
    GROUP BY b.id
    ORDER BY b.id DESC
")->fetchAll();

require_once '../includes/header.php';
?>

<div class="container">

    <nav class="app-breadcrumb">
        <a href="/restaurant-system/index.php">Dashboard</a>
        <span class="app-breadcrumb-sep">/</span>
        <span class="app-breadcrumb-current">Branches</span>
    </nav>

    <div class="d-flex align-items-start justify-content-between flex-wrap gap-3 page-header">
        <div>
            <span class="page-eyebrow">Management</span>
            <h1 class="page-title">Branches</h1>
            <p class="page-subtitle">All restaurant branches and their staff counts.</p>
        </div>
        <a href="/restaurant-system/branches/create.php" class="app-btn-primary">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add Branch
        </a>
    </div>

    <?php if ($success === 'created'): ?>
        <div class="app-alert app-alert-success auto-dismiss">
            Branch was added successfully.
        </div>
    <?php elseif ($success === 'updated'): ?>
        <div class="app-alert app-alert-success auto-dismiss">
            Branch was updated successfully.
        </div>
    <?php elseif ($success === 'deleted'): ?>
        <div class="app-alert app-alert-success auto-dismiss">
            Branch was deleted successfully.
        </div>
    <?php endif; ?>

    <div class="app-card">
        <div class="app-card-header">
            <span class="app-card-title">All Branches</span>
            <span class="app-badge badge-count"><?= count($branches) ?> total</span>
        </div>
        <div class="app-table-wrap">
            <table class="app-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Branch Name</th>
                        <th>Location</th>
                        <th>Employees</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($branches) === 0): ?>
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                    </div>
                                    <div class="empty-state-title">No branches yet</div>
                                    <div class="empty-state-desc">Add your first branch to get started.</div>
                                    <a href="/restaurant-system/branches/create.php" class="app-btn-primary">Add Branch</a>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($branches as $index => $branch): ?>
                            <tr>
                                <!-- Row number (display only, not DB ID) -->
                                <td class="text-muted-sm"><?= $index + 1 ?></td>

                                <td>
                                    <span style="font-weight:500;color:var(--dark)">
                                        <?= htmlspecialchars($branch['branch_name']) ?>
                                    </span>
                                </td>

                                <td class="text-muted-sm">
                                    <?= htmlspecialchars($branch['location']) ?>
                                </td>

                                <td>
                                    <a href="/restaurant-system/employees/index.php?branch_id=<?= $branch['id'] ?>"
                                       style="text-decoration:none">
                                        <span class="app-badge badge-count">
                                            <?= $branch['emp_count'] ?>
                                            <?= $branch['emp_count'] == 1 ? 'employee' : 'employees' ?>
                                        </span>
                                    </a>
                                </td>

                                <td>
                                    <div class="table-actions">
                                        <!-- Edit button -->
                                        <a href="/restaurant-system/branches/edit.php?id=<?= $branch['id'] ?>"
                                           class="action-btn action-btn-edit">
                                            Edit
                                        </a>

                                        <!-- Delete form -->
                                        <form method="POST"
                                              action="/restaurant-system/branches/delete.php"
                                              class="delete-form"
                                              data-name="<?= htmlspecialchars($branch['branch_name']) ?>">
                                            <input type="hidden" name="id" value="<?= $branch['id'] ?>">
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
