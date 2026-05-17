<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /restaurant-system/branches/index.php');
    exit;
}

$pdo = getPDO();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: /restaurant-system/branches/index.php');
    exit;
}

$check = $pdo->prepare("SELECT id FROM branches WHERE id = ?");
$check->execute([$id]);

if (!$check->fetch()) {
    header('Location: /restaurant-system/branches/index.php');
    exit;
}

$stmt = $pdo->prepare("DELETE FROM branches WHERE id = ?");
$stmt->execute([$id]);

header('Location: /restaurant-system/branches/index.php?success=deleted');
exit;
