<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
checkAuth();
checkRole(['Admin']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = (new Database())->connect();
    
    $id_don = $_POST['id_don'];
    $date_transfusion = $_POST['date_transfusion'];
    $hopital_recepteur = $_POST['hopital_recepteur'];
    
    try {
        // Vérifier que le don existe et a le statut "utilisé"
        $stmt = $db->prepare("SELECT statut FROM dons WHERE id_don = ?");
        $stmt->execute([$id_don]);
        $don = $stmt->fetch();
        
        if (!$don) {
            header("Location: liste.php?msg=Don+non+trouvé");
            exit;
        }
        
        if ($don['statut'] !== 'utilisé') {
            header("Location: liste.php?msg=Le+don+n+est+pas+au+statut+utilisé");
            exit;
        }
        
        // Insérer la transfusion
        $stmt = $db->prepare("
            INSERT INTO transfusions (id_don, date_transfusion, hopital_recepteur) 
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$id_don, $date_transfusion, $hopital_recepteur]);
        
        header("Location: liste_transfusions.php?msg=Transfusion+ajoutée+avec+succès");
        exit;
        
    } catch (PDOException $e) {
        header("Location: liste.php?msg=Erreur+de+base+de+données");
        exit;
    }
} else {
    header("Location: liste.php");
    exit;
}
?>