<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
checkAuth();

if (!isset($_GET['id_donneur']) || !is_numeric($_GET['id_donneur'])) {
    header("Location: donneurs/liste_donneurs.php");
    exit();
}

$id_donneur = $_GET['id_donneur'];

$database = new Database();
$db = $database->connect();

$stmt = $db->prepare("DELETE FROM donneurs WHERE id_donneur = ?");
$stmt->execute([$id_donneur]);

header("Location: liste_donneurs.php");
exit();
?>