<?php
require_once '../includes/auth.php';
require_once '../config/database.php';

checkAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $db = (new Database())->connect();

    $id_donneur = $_POST['id_donneur'];
    $statut = $_POST['statut'];
    $id_centre = $_POST['id_centre'];

    $stmt = $db->prepare("INSERT INTO dons (id_donneur, statut,id_centre) VALUES (?, ?, ?)");
    $stmt->execute([$id_donneur, $statut,$id_centre]);

    $insert = $db->prepare("
    INSERT INTO transfusions (id_don, id_donneur, date_transfusion)
    VALUES (?, ?, NOW())");

    

}

header("Location: dons/liste_dons.php");
exit();






