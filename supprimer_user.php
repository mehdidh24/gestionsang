<?php
require_once 'includes/auth.php';
require_once 'config/database.php';
checkRole(['Admin']);
$database = new Database();
$db = $database->connect();


if (!isset($_GET['id_utilisateur'])) {
    header("Location: utilisateurs.php");
    exit;
}

$id = $_GET['id_utilisateur'];


$stmt = $db->prepare("DELETE FROM utilisateurs WHERE id_utilisateur = ?");
$stmt->execute([$id]);

header("Location: utilisateurs.php");
exit;
?>
