<?php
require_once 'includes/auth.php';
require_once 'config/database.php';

checkAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // connexion BD
    $db = (new Database())->connect();

    $id_donneur = $_POST['id_donneur'];
    $statut = $_POST['statut'];
    $id_centre = $_POST['id_centre'];

    // Requête préparée
    $stmt = $db->prepare("INSERT INTO dons (id_donneur, statut,id_centre) VALUES (?, ?, ?)");
    $stmt->execute([$id_donneur, $statut,$id_centre]);
}

// Retour vers la liste
header("Location: liste_dons.php");
exit();






