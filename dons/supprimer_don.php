<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
checkAuth();

// Vérifier si l'ID est bien présent
if (!isset($_GET['id_don']) || !is_numeric($_GET['id_don'])) {
    header("Location: dons/liste_dons.php");
    exit();
}

$id_don = $_GET['id_don'];

// Connexion à la base de données
$database = new Database();
$db = $database->connect();

// Requête préparée pour la sécurité
$stmt = $db->prepare("DELETE FROM dons WHERE id_don = ?");
$stmt->execute([$id_don]);

header("Location: dons/liste_dons.php");
exit();
?>