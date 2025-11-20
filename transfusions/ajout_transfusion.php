<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
checkRole(['ADMIN','MEDECIN']); // seuls ADMIN & MEDECIN peuvent ajouter une transfusion

$db = (new Database())->connect();

// Récupération des dons encore disponibles
$dons = $db->query("
    SELECT d.id_don, d.date_don, d.quantite, dn.nom, dn.groupe_sanguin, dn.rhesus
    FROM dons d
    JOIN donneurs dn ON dn.id_donneur = d.id_donneur
    WHERE d.statut = 'en stock'
")->fetchAll();

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $id_don = $_POST['id_don'];
    $hopital = $_POST['hopital_recepteur'];
    $quantite = $_POST['quantite'];

    // Insérer la transfusion
    $stmt = $db->prepare("
        INSERT INTO transfusions (id_don, hopital_recepteur, quantite, date_transfusion)
        VALUES (?, ?, ?, NOW())
    ");
    $stmt->execute([$id_don, $hopital, $quantite]);

    // Mettre à jour le statut du don (utilisé)
    $update = $db->prepare("UPDATE dons SET statut = 'utilisé' WHERE id_don = ?");
    $update->execute([$id_don]);

    $message = "Transfusion enregistrée avec succès.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ajouter Transfusion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="container mt-4">

    <h2>Ajouter une Transfusion</h2>

    <?php if (!empty($message)): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" class="card p-3">

        <div class="mb-3">
            <label class="form-label">Sélectionner un Don disponible</label>
            <select name="id_don" class="form-select" required>
                <option value="">-- Choisir un don --</option>
                <?php foreach ($dons as $don): ?>
                    <option value="<?= $don['id_don'] ?>">
                        Don #<?= $don['id_don'] ?> - 
                        <?= $don['nom'] ?> (<?= $don['groupe_sanguin'] . $don['rhesus'] ?>) - 
                        <?= $don['quantite'] ?> ml
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Hôpital receveur</label>
            <input type="text" name="hopital_recepteur" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Quantité transfusée (ml)</label>
            <input type="number" name="quantite" class="form-control" min="1" required>
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer la Transfusion</button>

    </form>

</div>

<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
