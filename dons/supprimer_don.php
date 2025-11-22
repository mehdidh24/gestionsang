<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
checkAuth();
checkRole(['Admin','Secretaire']); 
if (!isset($_GET['id_don']) || !is_numeric($_GET['id_don'])) {
    header("Location: liste_dons.php");
    exit();
}

$id_don = $_GET['id_don'];

$database = new Database();
$db = $database->connect();

$stmt = $db->prepare("DELETE FROM dons WHERE id_don = ?");
$stmt->execute([$id_don]);

header("Location: liste_dons.php");
exit();
?>