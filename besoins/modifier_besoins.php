<?php
require_once 'config/database.php';
require_once 'includes/auth.php';

checkAuth();
checkRole(['SECRETAIRE', 'ADMIN', 'Médecin']);

$database = new Database();
$db = $database->connect();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id     = $_POST['id_besoin'];
    $groupe = $_POST['groupe_sanguin'];
    $niveau = $_POST['niveau_alerte'];

    $stmt = $db->prepare("UPDATE besoins 
                          SET groupe_sanguin = :g, niveau_alerte = :n
                          WHERE id_besoin = :id");

    $stmt->execute([
        ':g' => $groupe,
        ':n' => strtolower($niveau),
        ':id' => $id
    ]);

    header("Location:besoins/liste_besoins.php?update=success");
    exit;
}

if (!isset($_GET['id'])) {
    die("ID manquant");
}

$id = $_GET['id'];

$stmt = $db->prepare("SELECT * FROM besoins WHERE id_besoin = ?");
$stmt->execute([$id]);
$besoin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$besoin) {
    die("Besoin introuvable");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Besoin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0"><i class="fas fa-edit"></i> Modifier Besoin</h4>
        </div>

        <div class="card-body">

            <form method="POST" action="./besoins/modifier_besoins.php">

                <input type="hidden" name="id_besoin" value="<?= $besoin['id_besoin'] ?>">

                <label class="mt-2">Groupe sanguin</label>
                <select name="groupe_sanguin" class="form-control" required>
                    <?php
                    $groupes = ["A+","A-","B+","B-","AB+","AB-","O+","O-"];
                    foreach ($groupes as $g):
                    ?>
                        <option value="<?= $g ?>" 
                            <?= ($besoin['groupe_sanguin'] == $g) ? 'selected' : '' ?>>
                            <?= $g ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label class="mt-3">Niveau d'alerte</label>
                <select name="niveau_alerte" class="form-control" required>
                    <option <?= $besoin['niveau_alerte']=="urgent"?'selected':'' ?>>urgent</option>
                    <option <?= $besoin['niveau_alerte']=="critique"?'selected':'' ?>>critique</option>
                    <option <?= $besoin['niveau_alerte']=="normal"?'selected':'' ?>>normal</option>
                </select>

                <button type="submit" class="btn btn-primary mt-3">
                    Enregistrer les modifications
                </button>

                <a href="liste_besoins.php" class="btn btn-secondary mt-3">
                    Annuler
                </a>

                <a href="liste_besoins.php#ajoutBesoin" class="btn btn-success mt-3">
                    + Nouveau Besoin
                </a>

            </form>
        </div>
    </div>
</div>

</body>
</html>



