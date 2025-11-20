<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
checkAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->connect();
    
    $cin = $_POST['cin'];
    $groupe_sanguin = $_POST['groupe_sanguin'];
    $rhesus = $_POST['rhesus'];
    $ville = $_POST['ville'];
    
    // Requête préparée pour l'ajout
    $stmt = $db->prepare("INSERT INTO donneurs (cin, groupe_sanguin, rhesus,ville) VALUES (?, ?, ?,?)");
    $stmt->execute([$cin, $groupe_sanguin, $rhesus,$ville]);
}

header("Location: liste_donneurs.php");
exit();
?>