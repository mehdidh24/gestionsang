<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
checkAuth();
checkRole(['Admin','Médecin']);

$db = (new Database())->connect();

// Vérification de l'ID et du statut
$id_don = isset($_GET['id_don']) ? (int)$_GET['id_don'] : 0;
$statut = isset($_GET['statut']) ? $_GET['statut'] : '';

if ($id_don <= 0 || !in_array($statut, ['VALIDE','REJETÉ','Utilisé'])) {
    header("Location: test_dons.php?msg=Paramètres invalides");
    exit();
}

// Mise à jour du statut
$stmt = $db->prepare("UPDATE dons SET statut = ? WHERE id_don = ?");
$stmt->execute([$statut, $id_don]);

header("Location: test_dons.php?msg=Don #$id_don mis à jour : $statut");
exit();
