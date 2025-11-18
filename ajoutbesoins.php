<?php
require_once "config/database.php";
require_once "includes/auth.php";

session_start();
checkAuth();
checkRole(['Sécretaire', 'Admin', 'Médecin']);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $groupe = $_POST['groupe_sanguin'];
    $quantite = $_POST['quantite'];
    $urgence = $_POST['urgence'];
    $hopital = $_POST['hopital'];

    $db = (new Database())->connect();

    $sql = "INSERT INTO besoins (groupe_sanguin, quantite, urgence, hopital, statut)
            VALUES (:groupe, :quantite, :urgence, :hopital, 'ACTIF')";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(":groupe", $groupe);
    $stmt->bindParam(":quantite", $quantite);
    $stmt->bindParam(":urgence", $urgence);
    $stmt->bindParam(":hopital", $hopital);

    if ($stmt->execute()) {
        header("Location: liste.php?success=1");
    } else {
        header("Location: liste.php?error=1");
    }
    exit;
}
?>
