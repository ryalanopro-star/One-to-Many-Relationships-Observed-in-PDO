<?php

$currentPage = basename(dirname($_SERVER['PHP_SELF']));
$currentFile = basename($_SERVER['PHP_SELF']);

$base = '/restaurant-system';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - ' : '' ?>RY Dining</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">

    <link href="<?= $base ?>/assets/css/style.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top app-navbar">
    <div class="container">

        <a class="navbar-brand app-brand" href="<?= $base ?>/index.php">
            <span class="brand-dot"></span>
            RY DINING
        </a>

        <button class="navbar-toggler app-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#mainNav"
                aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <!-- Nav Links -->
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">

                <li class="nav-item">
                    <a class="nav-link app-nav-link <?= ($currentFile === 'index.php' && $currentPage === 'restaurant-system') ? 'active' : '' ?>"
                       href="<?= $base ?>/index.php">
                        Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link app-nav-link <?= ($currentPage === 'branches') ? 'active' : '' ?>"
                       href="<?= $base ?>/branches/index.php">
                        Branches
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link app-nav-link <?= ($currentPage === 'employees') ? 'active' : '' ?>"
                       href="<?= $base ?>/employees/index.php">
                        Employees
                    </a>
                </li>

                <li class="nav-item ms-lg-2">
                    <a class="btn app-btn-primary btn-sm px-3"
                       href="<?= $base ?>/branches/create.php">
                        Add Branch
                    </a>
                </li>

                <li class="nav-item">
                    <a class="btn app-btn-outline btn-sm px-3"
                       href="<?= $base ?>/employees/create.php">
                        Add Employee
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>

<main class="app-main">
