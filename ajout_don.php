<?php
require_once 'includes/auth.php';
require_once 'config/database.php';
checkRole(['SECRETAIRE', 'ADMIN','Médecin']);

$db = (new Database())->connect();

// Liste donneurs
$donneurs = $db->query("SELECT id_donneur, cin,groupe_sanguin,rhesus FROM donneurs ORDER BY nom")->fetchAll();
// Liste centres
$centres = $db->query("SELECT id_centres FROM centres_collecte")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "INSERT INTO dons (id_donneur, id_centre, type_don, date_don, statut)
            VALUES (:d, :c, :t, NOW(), 'EN STOCK')";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':d' => $_POST['id_donneur'],
        ':c' => $_POST['id_centre'],
        
    ]);
    header("Location: liste_dons.php?success=1");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ajouter Don</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 container">

<h3 class="mb-4">Ajouter un Don</h3>

<form method="POST" class="card p-4 shadow-lg">
    <div class="mb-3">
        <label class="form-label">Donneur</label>
        <select name="id_donneur" class="form-select" required>
            <option value="">-- Sélectionner --</option>
            <?php foreach ($donneurs as $d): ?>
                <option value="<?= $d['id_donneur'] ?>">
                    <?= $d['nom'] . " " . $d['prenom'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Centre</label>
        <select name="id_centre" class="form-select" required>
            <option value="">-- Sélectionner --</option>
            <?php foreach ($centres as $c): ?>
                <option value="<?= $c['id_centre'] ?>">
                    <?= $c['nom_centre'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Type de Don</label>
        <select name="type_don" class="form-select" required>
            <option>Sang Total</option>
            <option>Plasma</option>
            <option>Plaquettes</option>
        </select>
    </div>

    <button class="btn btn-primary w-100">Enregistrer</button>
</form>

</body>
</html>
