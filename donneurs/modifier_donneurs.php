<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
checkAuth();
checkRole(['Admin','Secretaire']); 
$db = (new Database())->connect();

$id = $_GET['id_donneur'] ?? null;
if (!$id) {
    header("Location: liste_donneurs.php");
    exit;
}

// Récupération des données existantes du donneur
$stmt = $db->prepare("SELECT * FROM donneurs WHERE id_donneur = ?");
$stmt->execute([$id]);
$donneur = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$donneur) {
    header("Location: liste_donneurs.php?msg=Donneur non trouvé");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cin = $_POST['cin'] ?? '';
    $groupe_sanguin = $_POST['groupe_sanguin'] ?? '';
    $rhesus = $_POST['rhesus'] ?? '';
    $ville = $_POST['ville'] ?? '';

    $sql = "UPDATE donneurs SET cin = ?, groupe_sanguin = ?, rhesus = ?, ville = ? WHERE id_donneur = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$cin, $groupe_sanguin, $rhesus, $ville, $id]);

    header("Location: liste_donneurs.php?msg=Donneur modifié avec succès");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Modifier Donneur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="container mt-4">
    <h3>Modifier Donneur</h3>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="cin" class="form-label">CIN</label>
            <input type="text" id="cin" name="cin" class="form-control" value="<?= htmlspecialchars($donneur['cin']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="groupe_sanguin" class="form-label">Groupe sanguin</label>
            <select id="groupe_sanguin" name="groupe_sanguin" class="form-control" required>
                <?php foreach (['A', 'B', 'AB', 'O'] as $g): ?>
                    <option value="<?= $g ?>" <?= ($donneur['groupe_sanguin'] == $g) ? 'selected' : '' ?>><?= $g ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="rhesus" class="form-label">Rhésus</label>
            <select id="rhesus" name="rhesus" class="form-control" required>
                <option value="+" <?= ($donneur['rhesus'] == '+') ? 'selected' : '' ?>>+</option>
                <option value="-" <?= ($donneur['rhesus'] == '-') ? 'selected' : '' ?>>-</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="ville" class="form-label">Ville</label>
            <input type="text" id="ville" name="ville" class="form-control" value="<?= htmlspecialchars($donneur['ville']) ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="liste_donneurs.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
