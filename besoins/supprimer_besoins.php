<?php
require_once '../includes/auth.php';
require_once '../config/database.php';


$db = (new Database())->connect();

if (!isset($_GET['id'])) {
    header("Location: liste_besoins.php?msg=ID manquant");
    exit;
}

$id = $_GET['id'];


$stmt = $db->prepare("SELECT * FROM besoins WHERE id_besoin = ?");
$stmt->execute([$id]);
$besoin = $stmt->fetch();

if (!$besoin) {
    header("Location: liste_besoins.php?msg=Besoin non trouvé");
    exit;
}


$stmt = $db->prepare("DELETE FROM besoins WHERE id_besoin = ?");
$stmt->execute([$id]);

header("Location: liste_besoins.php?msg=Besoin supprimé avec succès");
exit;
?>
