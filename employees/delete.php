<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /restaurant-system/employees/index.php');
    exit;
}

$pdo = getPDO();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: /restaurant-system/employees/index.php');
    exit;
}

$check = $pdo->prepare("SELECT id FROM employees WHERE id = ?");
$check->execute([$id]);

if (!$check->fetch()) {
    header('Location: /restaurant-system/employees/index.php');
    exit;
}

$stmt = $pdo->prepare("DELETE FROM employees WHERE id = ?");
$stmt->execute([$id]);

// Redirect back with success message
header('Location: /restaurant-system/employees/index.php?success=deleted');
exit;
