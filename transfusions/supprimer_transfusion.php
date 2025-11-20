<?php
require_once 'config/database.php';
require_once 'includes/auth.php';

checkRole(['ADMIN', 'MEDECIN']); // seuls autorisés

$db = (new Database())->connect();

// Vérifier si un ID est présent
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: liste.php");
    exit;
}

$id = $_GET['id'];

// Récupérer la transfusion pour retrouver l'id_don
$stmt = $db->prepare("SELECT id_don FROM transfusions WHERE id_transfusion = ?");
$stmt->execute([$id]);
$transfusion = $stmt->fetch();

if (!$transfusion) {
    header("Location: liste.php?error=notfound");
    exit;
}

$id_don = $transfusion['id_don'];

// Supprimer la transfusion
$delete = $db->prepare("DELETE FROM transfusions WHERE id_transfusion = ?");
$delete->execute([$id]);

// Remettre le don en stock
$update = $db->prepare("UPDATE dons SET statut = 'en stock' WHERE id_don = ?");
$update->execute([$id_don]);

// Redirection
header("Location: liste.php?success=deleted");
exit;
