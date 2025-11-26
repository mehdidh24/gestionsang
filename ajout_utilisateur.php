<?php
require_once 'config/database.php';
require_once 'includes/auth.php';
checkRole(['Admin','Médecin']); 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: utilisateurs.php");
    exit;
}

$db = (new Database())->connect();

$nom = $_POST['nom'];

$role = $_POST['role'];
$mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
$centre = $_POST['id_centre'];

$stmt = $db->prepare("INSERT INTO utilisateurs (nom,role,mot_de_passe,id_centre) VALUES (?, ?, ?,?)");
$stmt->execute([$nom,$role, $mot_de_passe,$centre]);

header("Location: utilisateurs.php?success=1");
exit;
?>

