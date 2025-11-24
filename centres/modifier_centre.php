<?php
require_once '../includes/auth.php';
require_once '../config/database.php';


$db = (new Database())->connect();

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: liste_centres.php");
    exit;
}


$stmt = $db->prepare("SELECT * FROM centres_collecte WHERE id_centre = ?");
$stmt->execute([$id]);
$centre = $stmt->fetch();

if (!$centre) {
    header("Location: liste_centres.php?msg=Centre non trouvé");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $adresse = $_POST['adresse'];

    $stmt = $db->prepare("UPDATE centres_collecte SET nom_centre = ?, adresse = ? WHERE id_centre = ?");
    $stmt->execute([$nom, $adresse, $id]);

    header("Location: liste_centres.php?msg=Centre modifié avec succès");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Modifier Centre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h3>Modifier Centre de Collecte</h3>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Nom du Centre</label>
            <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($centre['nom_centre']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Adresse</label>
            <input type="text" name="adresse" class="form-control" value="<?= htmlspecialchars($centre['adresse']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="centres_collecte.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>
</body>
</html>
