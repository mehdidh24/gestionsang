<?php
require_once 'includes/auth.php';
require_once 'config/database.php';


checkRole(['Admin']);

$db = (new Database())->connect();


$id = $_GET['id_utilisateur'] ?? null;
if (!$id) {
    header("Location: utilisateurs.php?msg=id_manquant");
    exit;
}

// Récupération de l'utilisateur pour pré-remplir le formulaire
$stmt = $db->prepare("SELECT * FROM utilisateurs WHERE id_utilisateur = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: utilisateurs.php?msg=utilisateur_introuvable");
    exit;
}

// Traitement formulaire après soumission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $nom = trim($_POST['nom']);
    $role = trim($_POST['role']);

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $query = "UPDATE utilisateurs SET nom = ?, role = ?, mot_de_passe = ? WHERE id_utilisateur = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$nom, $role, $password, $id]);
    } else {
        $query = "UPDATE utilisateurs SET nom = ?, role = ? WHERE id_utilisateur = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$nom, $role, $id]);
    }

    header("Location: utilisateurs.php?msg=modifie");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Utilisateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="container mt-4">
    <div class="card shadow-sm p-4">
        <h3 class="mb-3">Modifier l'utilisateur</h3>

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">Nom</label>
                <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($user['nom']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Mot de passe</label>
                <input type="password" name="password" class="form-control" placeholder="Laisser vide pour garder le mot de passe actuel">
            </div>

            <div class="mb-3">
                <label class="form-label">Rôle</label>
                <select name="role" class="form-select" required>
                    <option value="ADMIN" <?= $user['role'] == 'ADMIN' ? 'selected' : '' ?>>ADMIN</option>
                    <option value="MEDECIN" <?= $user['role'] == 'MEDECIN' ? 'selected' : '' ?>>MEDECIN</option>
                    <option value="SECRETAIRE" <?= $user['role'] == 'SECRETAIRE' ? 'selected' : '' ?>>SECRETAIRE</option>
                </select>
            </div>

            <button class="btn btn-primary">Enregistrer</button>
            <a href="utilisateurs.php" class="btn btn-secondary">Annuler</a>

        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
