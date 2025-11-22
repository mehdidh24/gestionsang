<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
checkAuth();

$database = new Database();
$db = $database->connect();

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: liste_besoins.php');
    exit;
}

$stmt = $db->prepare("SELECT * FROM besoins WHERE id_besoin = ?");
$stmt->execute([$id]);
$besoin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$besoin) {
    header('Location: liste_besoins.php?msg=Besoin non trouvé');
    exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $groupe_sanguin = $_POST['groupe_sanguin'] ?? '';
    $niveau_alerte = strtolower($_POST['niveau_alerte'] ?? '');

    if (empty($groupe_sanguin)) {
        $errors[] = "Le groupe sanguin est requis.";
    }
    if (!in_array($niveau_alerte, ['urgent', 'critique', 'normal'])) {
        $errors[] = "Le niveau d'alerte est invalide.";
    }

    if (empty($errors)) {
        $stmt = $db->prepare("UPDATE besoins SET groupe_sanguin = ?, niveau_alerte = ? WHERE id_besoin = ?");
        $stmt->execute([$groupe_sanguin, $niveau_alerte, $id]);
        header("Location: liste_besoins.php?msg=Besoin modifié avec succès");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Besoin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h3>Modifier un Besoin en Sang</h3>

    <?php if ($errors): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="groupe_sanguin" class="form-label">Groupe Sanguin</label>
            <select id="groupe_sanguin" name="groupe_sanguin" class="form-control" required>
                <?php foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $gs): ?>
                    <option value="<?= $gs ?>" <?= ($besoin['groupe_sanguin'] == $gs) ? 'selected' : '' ?>><?= $gs ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="niveau_alerte" class="form-label">Niveau d'Alerte</label>
            <select id="niveau_alerte" name="niveau_alerte" class="form-control" required>
                <?php foreach(['urgent', 'critique', 'normal'] as $niveau): ?>
                    <option value="<?= $niveau ?>" <?= ($besoin['niveau_alerte'] == $niveau) ? 'selected' : '' ?>><?= ucfirst($niveau) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="liste_besoins.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>
</body>
</html>
