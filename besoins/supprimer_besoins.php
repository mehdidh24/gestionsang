<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
checkAuth();

$db = (new Database())->connect();

if (!isset($_GET['id'])) {
    header("Location: liste_besoins.php?msg=ID manquant");
    exit;
}

$id = $_GET['id'];

// Vérifier que le besoin existe
$stmt = $db->prepare("SELECT * FROM besoins WHERE id_besoin = ?");
$stmt->execute([$id]);
$besoin = $stmt->fetch();

if (!$besoin) {
    header("Location: liste_besoins.php?msg=Besoin non trouvé");
    exit;
}

// Supprimer le besoin
$stmt = $db->prepare("DELETE FROM besoins WHERE id_besoin = ?");
$stmt->execute([$id]);

header("Location: liste_besoins.php?msg=Besoin supprimé avec succès");
exit;
?>
