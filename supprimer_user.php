<?php
require_once 'includes/auth.php';
checkRole(['Admin','Médecin']);
require_once 'config/database.php';

if (!isset($_GET['id'])) {
    header("Location: utilisateurs.php");
    exit;
}

$id = intval($_GET['id']);
$db = (new Database())->connect();

$stmt = $db->prepare("DELETE FROM utilisateurs WHERE id_utilisateur = ?");
$stmt->execute([$id]);

header("Location:utilisateurs.php");
exit;
?>
