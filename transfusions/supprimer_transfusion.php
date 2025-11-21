<?php
require_once '../config/database.php';
require_once '../includes/auth.php';

checkRole(['Admin', 'MEDECIN']);

$db = (new Database())->connect();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: liste.php");
    exit;
}

$id = $_GET['id'];

$stmt = $db->prepare("SELECT id_don FROM transfusions WHERE id_transfusion = ?");
$stmt->execute([$id]);
$transfusion = $stmt->fetch();

if (!$transfusion) {
    header("Location: liste.php?error=notfound");
    exit;
}

$id_don = $transfusion['id_don'];

$delete = $db->prepare("DELETE FROM transfusions WHERE id_transfusion = ?");
$delete->execute([$id]);

$update = $db->prepare("UPDATE dons SET statut = 'en stock' WHERE id_don = ?");
$update->execute([$id_don]);

header("Location: liste.php?success=deleted");
exit;
