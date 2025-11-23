<?php
require_once '../includes/auth.php';
require_once '../config/database.php';

checkRole(['Admin']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = (new Database())->connect();

    $id_don = intval($_POST['id_don'] ?? 0);
    $date = $_POST['date_transfusion'] ?? '';
    $hopital = $_POST['hopital_recepteur'] ?? '';

    if (!$id_don || !$date || !$hopital) {
        header("Location: liste.php?msg=Tous les champs sont requis");
        exit;
    }

    $don = $db->query("SELECT statut FROM dons WHERE id_don = $id_don")->fetch(PDO::FETCH_ASSOC);

    if (!$don) {
        header("Location: liste.php?msg=Don non trouvé");
    } elseif ($don['statut'] !== 'utilisé') {
        header("Location: liste.php?msg=Don pas au statut utilisé");
    } else {
        $db->prepare("INSERT INTO transfusions (id_don, date_transfusion, hopital_recepteur) VALUES (?, ?, ?)")
           ->execute([$id_don, $date, $hopital]);
        header("Location: liste.php?msg=Transfusion ajoutée");
    }
    exit;
}

header("Location: liste.php");
exit;
?>