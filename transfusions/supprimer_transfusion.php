<?php
require_once '../includes/auth.php';
require_once '../config/database.php';

checkRole(['Admin']); 




$database = new Database();
$db = $database->connect();

if (!isset($_GET['id_transfusion']) || !is_numeric($_GET['id_transfusion'])) {
    header("Location: liste.php");
    exit();
}

$id_transfusion = $_GET['id_transfusion'];

$stmt = $db->prepare("DELETE FROM transfusions WHERE id_transfusion = ?");
$stmt->execute([$id_transfusion]);  

header("Location: liste.php");
exit();



