<?php
require_once 'config/database.php';

$pageTitle = 'Dashboard';
$pdo = getPDO();

// Total number of branches
$branchCount = $pdo->query("SELECT COUNT(*) FROM branches")->fetchColumn();

// Total number of employees
$employeeCount = $pdo->query("SELECT COUNT(*) FROM employees")->fetchColumn();

// Most staffed branch (branch with the most employees)
$topBranch = $pdo->query("
    SELECT b.branch_name, COUNT(e.id) AS emp_count
    FROM branches b
    LEFT JOIN employees e ON e.branch_id = b.id
    GROUP BY b.id
    ORDER BY emp_count DESC
    LIMIT 1
")->fetch();

// Average employees per branch
$avgPerBranch = ($branchCount > 0)
    ? round($employeeCount / $branchCount, 1)
    : 0;

$recentBranches = $pdo->query("
    SELECT b.id, b.branch_name, b.location,
           COUNT(e.id) AS emp_count
    FROM branches b
    LEFT JOIN employees e ON e.branch_id = b.id
    GROUP BY b.id
    ORDER BY b.id DESC
    LIMIT 5
")->fetchAll();

$recentEmployees = $pdo->query("
    SELECT e.employee_name, e.position,
           b.branch_name
    FROM employees e
    INNER JOIN branches b ON e.branch_id = b.id
    ORDER BY e.id DESC
    LIMIT 5
")->fetchAll();

require_once 'includes/header.php';
?>

<div class="container">

    <div class="dashboard-hero fade-in-up">
        <p class="hero-eyebrow">RY Business Management System</p>
        <h1 class="hero-title">Welcome Back</h1>
        <p class="hero-sub">
            Manage your restaurant branches and employees from one central place.
            All records, all branches, always in sync.
        </p>
        <div class="d-flex gap-2 mt-4 flex-wrap">
            <a href="/restaurant-system/branches/index.php" class="app-btn-primary">View Branches</a>
            <a href="/restaurant-system/employees/index.php" class="app-btn-ghost">View Employees</a>
        </div>
    </div>

    <div class="row g-3 mb-4">

        <div class="col-6 col-md-3">
            <div class="stat-card fade-in-up fade-in-up-1">
                <div class="stat-icon stat-icon-blue">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                </div>
                <div class="stat-value"><?= $branchCount ?></div>
                <div class="stat-label">Total Branches</div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="stat-card fade-in-up fade-in-up-2">
                <div class="stat-icon stat-icon-green">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <div class="stat-value"><?= $employeeCount ?></div>
                <div class="stat-label">Total Employees</div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="stat-card fade-in-up fade-in-up-3">
                <div class="stat-icon stat-icon-warn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="20" x2="18" y2="10"/>
                        <line x1="12" y1="20" x2="12" y2="4"/>
                        <line x1="6"  y1="20" x2="6"  y2="14"/>
                    </svg>
                </div>
                <div class="stat-value"><?= $avgPerBranch ?></div>
                <div class="stat-label">Avg. Employees / Branch</div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="stat-card fade-in-up fade-in-up-4">
                <div class="stat-icon stat-icon-gray">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                </div>
                <div class="stat-value" style="font-size:1.1rem;padding-top:0.3rem">
                    <?= $topBranch ? htmlspecialchars($topBranch['branch_name']) : '—' ?>
                </div>
                <div class="stat-label">Most Staffed Branch</div>
            </div>
        </div>

    </div>

    <div class="app-card mb-4 fade-in-up">
        <div class="app-card-header">
            <span class="app-card-title">Database Relationship</span>
            <span class="app-badge badge-branch">One-to-Many</span>
        </div>
        <div class="app-card-body">
            <div class="rel-diagram">
                <div class="rel-node">
                    <div class="rel-node-label">Table</div>
                    <div class="rel-node-name">branches</div>
                    <div class="text-muted-sm mt-1">id, branch_name, location</div>
                </div>
                <div class="rel-arrow flex-fill">
                    <div class="rel-arrow-label">1 branch</div>
                    <div class="rel-arrow-line"></div>
                    <div class="rel-arrow-label">Many employees</div>
                </div>
                <div class="rel-node">
                    <div class="rel-node-label">Table</div>
                    <div class="rel-node-name">employees</div>
                    <div class="text-muted-sm mt-1">id, branch_id (FK), name, position</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-lg-6">
            <div class="app-card h-100">
                <div class="app-card-header">
                    <span class="app-card-title">Recent Branches</span>
                    <a href="/restaurant-system/branches/index.php" class="action-btn action-btn-edit" style="text-decoration:none">View All</a>
                </div>
                <div class="app-table-wrap">
                    <table class="app-table">
                        <thead>
                            <tr>
                                <th>Branch Name</th>
                                <th>Location</th>
                                <th>Staff</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($recentBranches) === 0): ?>
                                <tr>
                                    <td colspan="3">
                                        <div class="empty-state" style="padding:2rem">
                                            <div class="empty-state-desc">No branches found.</div>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recentBranches as $branch): ?>
                                    <tr>
                                        <td>
                                            <a href="/restaurant-system/branches/index.php" style="color:var(--dark);font-weight:500">
                                                <?= htmlspecialchars($branch['branch_name']) ?>
                                            </a>
                                        </td>
                                        <td class="text-muted-sm"><?= htmlspecialchars($branch['location']) ?></td>
                                        <td>
                                            <span class="app-badge badge-count"><?= $branch['emp_count'] ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="app-card h-100">
                <div class="app-card-header">
                    <span class="app-card-title">Recent Employees</span>
                    <a href="/restaurant-system/employees/index.php" class="action-btn action-btn-edit" style="text-decoration:none">View All</a>
                </div>
                <div class="app-table-wrap">
                    <table class="app-table">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Position</th>
                                <th>Branch</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($recentEmployees) === 0): ?>
                                <tr>
                                    <td colspan="3">
                                        <div class="empty-state" style="padding:2rem">
                                            <div class="empty-state-desc">No employees found.</div>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recentEmployees as $emp): ?>
                                    <tr>
                                        <td style="font-weight:500"><?= htmlspecialchars($emp['employee_name']) ?></td>
                                        <td class="text-muted-sm"><?= htmlspecialchars($emp['position']) ?></td>
                                        <td>
                                            <span class="app-badge badge-branch">
                                                <?= htmlspecialchars($emp['branch_name']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>

<?php require_once 'includes/footer.php'; ?>
