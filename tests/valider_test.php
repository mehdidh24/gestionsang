<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
checkAuth();
checkRole(['Médecin','Admin']);

$db = (new Database())->connect();

// Récupérer l'id du don à valider
$id_don = !empty($_GET['id_don']) ? (int)$_GET['id_don'] : 0;
if (!$id_don) {
    header("Location: liste_validation_tests.php?msg=Don invalide");
    exit();
}

// Vérifier que le don est bien EN STOCK
$stmt = $db->prepare("SELECT d.*, dn.cin, dn.groupe_sanguin, dn.rhesus
                      FROM dons d
                      JOIN donneurs dn ON d.id_donneur = dn.id_donneur
                      WHERE d.id_don = ? AND d.statut='EN STOCK'");
$stmt->execute([$id_don]);
$don = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$don) {
    header("Location: test_dons.php?msg=Don non trouvé ou déjà traité");
    exit();
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tests_don = trim($_POST['tests_don']);
    $est_conforme = $_POST['est_conforme'] == "1" ? 1 : 0;
    $statut = $est_conforme ? 'VALIDE' : 'REJETÉ';

    try {
        $db->beginTransaction();

        // Ajouter les résultats dans tests_don
        $stmt = $db->prepare("INSERT INTO tests_don (id_don, resultat, est_conforme) VALUES (?, ?, ?)");
        $stmt->execute([$id_don, $tests_don, $est_conforme]);

        // Mettre à jour le statut du don
        $stmt = $db->prepare("UPDATE dons SET statut = ? WHERE id_don = ?");
        $stmt->execute([$statut, $id_don]);

        $db->commit();

        header("Location: test_dons.php?msg=Don traité avec succès");
        exit();
    } catch (PDOException $e) {
        $db->rollBack();
        header("Location: test_dons.php?msg=Erreur lors du traitement");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Validation Test Don #<?= $don['id_don'] ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="container mt-4">
    <h2>Validation du Don #<?= $don['id_don'] ?></h2>
    <p>Donneur CIN: <?= htmlspecialchars($don['cin']) ?> | Groupe: <?= htmlspecialchars($don['groupe_sanguin']) ?><?= htmlspecialchars($don['rhesus']) ?></p>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">Résultats des tests</label>
            <textarea name="tests_don" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Conforme ?</label>
            <select name="est_conforme" class="form-control" required>
                <option value="">-- Choisir --</option>
                <option value="1">Oui (VALIDE)</option>
                <option value="0">Non (REJETÉ)</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="test_dons.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
