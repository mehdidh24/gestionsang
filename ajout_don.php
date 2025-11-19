<?php
require_once 'includes/auth.php';
require_once 'config/database.php';
checkAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->connect();

    $id_donneur = $_POST['id_donneur'];
    $statut = $_POST['statut'];

    // Requête préparée pour l'ajout
    $stmt = $db->prepare("INSERT INTO dons (id_donneur, statut) VALUES (?, ?)");
    $stmt->execute([$id_donneur, $statut]);
}

header("Location: liste_dons.php");
exit();
?>




