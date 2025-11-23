<?php
require_once '../includes/auth.php';
require_once '../config/database.php';


$db = (new Database())->connect();

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['nom']) && !empty($_POST['adresse'])) {

        $nom = trim($_POST['nom']);
        $adresse = trim($_POST['adresse']);

       
        $stmt = $db->prepare("INSERT INTO centres_collecte (nom_centre, adresse) VALUES (:nom, :adresse)");
        $stmt->execute([
            ':nom' => $nom,
            ':adresse' => $adresse
        ]);

        $message = "Centre ajouté avec succès.";
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}
header("Location: liste_centres.php?message=" . urlencode($message));
exit;
?>
