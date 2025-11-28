<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
checkAuth();

checkRole(['Admin','Sécrétaire']); 
$db = (new Database())->connect();

$id_don = $_GET['id_don'] ?? null;
if (!$id_don) {
    header("Location: liste_dons.php");
    exit;
}

// Récupérer le don existant
$stmt = $db->prepare("SELECT * FROM dons WHERE id_don = ?");
$stmt->execute([$id_don]);
$don = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$don) {
    header("Location: liste_dons.php");
    exit;
}

$donneurs = $db->query("SELECT id_donneur FROM donneurs ORDER BY id_donneur")->fetchAll(PDO::FETCH_ASSOC);
$centres = $db->query("SELECT id_centre FROM centres_collecte ORDER BY id_centre")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_donneur = $_POST['id_donneur'];
    $statut = $_POST['statut'];
    $id_centre = $_POST['id_centre'];

    $sql = "UPDATE dons SET id_donneur = ?, statut = ?, id_centre = ? WHERE id_don = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$id_donneur, $statut, $id_centre, $id_don]);

    header("Location: liste_dons.php?msg=Don modifié avec succès");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Modifier Don</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="container mt-4">
    <h3>Modifier Don</h3>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="id_donneur" class="form-label">Donneur</label>
            <select name="id_donneur" id="id_donneur" class="form-select" required>
                <?php foreach($donneurs as $d): ?>
                    <option value="<?= $d['id_donneur'] ?>" <?= ($don['id_donneur'] == $d['id_donneur']) ? 'selected' : '' ?>>
                        <?= $d['id_donneur'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="statut" class="form-label">Statut</label>
            <select name="statut" id="statut" class="form-select" required>
                <?php foreach (['en stock', 'utilisé', 'expiré', 'rejeté', 'valide'] as $status): ?>
                    <option value="<?= $status ?>" <?= ($don['statut'] == $status) ? 'selected' : '' ?>>
                        <?= ucfirst($status) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="id_centre" class="form-label">Centre</label>
            <select name="id_centre" id="id_centre" class="form-select" required>
                <?php foreach ($centres as $c): ?>
                    <option value="<?= $c['id_centre'] ?>" <?= ($don['id_centre'] == $c['id_centre']) ? 'selected' : '' ?>>
                        <?= $c['id_centre'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="liste_dons.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
