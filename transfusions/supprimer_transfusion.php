<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
checkAuth();
checkRole(['Admin']); 


if (!isset($_GET['id_transfusion']) || !is_numeric($_GET['id_transfusion'])) {
    header("Location: liste_.php");
    exit();
}

$id_don = $_GET['id_transfusion'];

$database = new Database();
$db = $database->connect();

$stmt = $db->prepare("DELETE FROM transfusions WHERE id_transfusion = ?");
$stmt->execute([$id_transfusion]);

header("Location: liste.php");
exit();


