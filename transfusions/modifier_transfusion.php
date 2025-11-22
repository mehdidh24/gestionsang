<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
checkAuth();
checkRole(['Admin']);

$db = (new Database())->connect();

// Récupération de l'id_transfusion depuis l'URL
$id_transfusion = $_GET['id_transfusion'] ?? null;
if (!$id_transfusion) {
    header("Location: liste.php?msg=id_manquant");
    exit;
}

// Récupérer la transfusion
$stmt = $db->prepare("
    SELECT t.id_transfusion, t.id_don, t.date_transfusion, t.hopital_recepteur,
           dn.cin, dn.groupe_sanguin, dn.rhesus
    FROM transfusions t
    JOIN dons d ON t.id_don = d.id_don
    JOIN donneurs dn ON d.id_donneur = dn.id_donneur
    WHERE t.id_transfusion = ?
");
$stmt->execute([$id_transfusion]);
$transfusion = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$transfusion) {
    header("Location: liste.php?msg=transfusion_introuvable");
    exit;
}

// Liste des dons utilisés pour le select
$dons_stmt = $db->query("
    SELECT d.id_don, dn.cin, dn.groupe_sanguin, dn.rhesus
    FROM dons d
    JOIN donneurs dn ON d.id_donneur = dn.id_donneur
    WHERE d.statut = 'utilisé'
    ORDER BY d.id_don DESC
");
$dons = $dons_stmt->fetchAll(PDO::FETCH_ASSOC);

// Traitement modification formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_don = $_POST['id_don'];
    $date_transfusion = $_POST['date_transfusion'];
    $hopital_recepteur = trim($_POST['hopital_recepteur']);

    $update = $db->prepare("UPDATE transfusions SET id_don = ?, date_transfusion = ?, hopital_recepteur = ? WHERE id_transfusion = ?");
    $update->execute([$id_don, $date_transfusion, $hopital_recepteur, $id_transfusion]);

    header("Location: liste.php?msg=modifie");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Modifier Transfusion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<?php include '../includes/header.php'; ?>
<div class="container mt-4">
    <h3>Modifier Transfusion</h3>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">ID Don (statut utilisé)</label>
            <select name="id_don" class="form-select" required>
                <?php foreach ($dons as $d): ?>
                <option value="<?= $d['id_don'] ?>" <?= ($d['id_don'] == $transfusion['id_don']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($d['id_don']) ?> | CIN: <?= htmlspecialchars($d['cin']) ?> | Groupe: <?= htmlspecialchars($d['groupe_sanguin']) ?><?= htmlspecialchars($d['rhesus']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Date Transfusion</label>
            <input type="date" name="date_transfusion" class="form-control" value="<?= htmlspecialchars($transfusion['date_transfusion']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Hôpital Receveur</label>
            <input type="text" name="hopital_recepteur" class="form-control" value="<?= htmlspecialchars($transfusion['hopital_recepteur']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="liste.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>
<?php include '../includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
