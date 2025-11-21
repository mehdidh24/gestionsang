<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
checkAuth();


$db = (new Database())->connect();

if (!empty($_GET['id'])) {

    $id = intval($_GET['id']);

    // Vérifier si le centre existe
    $check = $db->prepare("SELECT id_centre FROM centres_collecte WHERE id_centre = :id");
    $check->execute([':id' => $id]);

    if ($check->rowCount() === 1) {

        // Supprimer
        $stmt = $db->prepare("DELETE FROM centres_collecte WHERE id_centre = :id");
        $stmt->execute([':id' => $id]);

        header("Location: liste_centres.php?msg=Centre supprimé");
        exit;

    } else {
        echo "Centre introuvable.";
        exit;
    }
} else {
    echo "ID manquant.";
    exit;
}
?>
