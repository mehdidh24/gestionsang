<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
checkAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->connect();
    
    $statut = $_POST['statut'];
    $id_centre = $_POST['id_centre'];

    
    $stmt = $db->prepare("INSERT INTO dons (statut, id_centre) VALUES (?, ?)");
    $stmt->execute([$statut, $id_centre]);

       
}
header("Location: liste_dons.php?msg=Don ajouté avec succès");
exit(); 

?>
