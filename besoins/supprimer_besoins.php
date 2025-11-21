<?php
require_once '../config/database.php';
require_once '../includes/auth.php';

checkAuth();
checkRole(['SECRETAIRE', 'ADMIN', 'Médecin']);

if (!isset($_GET['id'])) {
    header('Location: liste_besoins.php');
    exit;
}

$id_besoin = $_GET['id'];

$database = new Database();
$db = $database->connect();

$stmt = $db->prepare("DELETE FROM besoins WHERE id_besoin = ?");
$stmt->execute([$id_besoin]);

header('Location: liste_besoins.php');
exit;
?>
