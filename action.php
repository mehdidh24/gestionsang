<?php
require_once 'config/database.php';
$database = new Database();
$db = $database->connect();

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    // ---------------------
    // AJOUT DONNEUR
    // ---------------------
    if ($action == 'add_donneur') {
        $stmt = $db->prepare("INSERT INTO donneurs (groupe_sanguin) VALUES (?, ?, ?)");
        $stmt->execute(['Nouveau', 'Donneur', 'O+']);
    }

    // ---------------------
    // SUPPRESSION DONNEUR
    // ---------------------
    if ($action == 'delete_donneur') {
        $stmt = $db->prepare("DELETE FROM donneurs ORDER BY id DESC LIMIT 1");
        $stmt->execute();
    }

    // ---------------------
    // AJOUT CENTRE
    // ---------------------
    if ($action == 'add_centre') {
        $stmt = $db->prepare("INSERT INTO centres_collecte (nom, adresse) VALUES (?, ?)");
        $stmt->execute(['Nouveau Centre', 'Adresse']);
    }

    // ---------------------
    // SUPPRESSION CENTRE
    // ---------------------
    if ($action == 'delete_centre') {
        $stmt = $db->prepare("DELETE FROM centres_collecte ORDER BY id DESC LIMIT 1");
        $stmt->execute();
    }

    // ---------------------
    // AJOUT DON
    // ---------------------
    if ($action == 'add_don') {
        $stmt = $db->prepare("INSERT INTO dons (id_donneur, date_don, statut) VALUES (1, NOW(), 'valide')");
        $stmt->execute();
    }

    // ---------------------
    // SUPPRESSION DON
    // ---------------------
    if ($action == 'delete_don') {
        $stmt = $db->prepare("DELETE FROM dons ORDER BY id DESC LIMIT 1");
        $stmt->execute();
    }

    // Retour au dashboard
    header("Location: dashboard.php");
    exit;
}
?>
