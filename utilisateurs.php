<?php
require_once 'includes/auth.php';
require_once 'config/database.php';

checkRole(['Admin']);
$db = (new Database())->connect();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_utilisateur'])) {
    $nom = $_POST['nom'];
    $role = strtoupper($_POST['role']);
    $password_hash = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
    $centre = $_POST['id_centre'] ?? null; 
    
   
    if (empty($centre)) {
        $error_message = "Veuillez sélectionner un centre.";
    } else {
        $centre = intval($centre);
        try {
            $stmt = $db->prepare("INSERT INTO utilisateurs (nom, role, mot_de_passe, id_centre) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nom, $role, $password_hash, $centre]);
            header("Location: utilisateurs.php?msg=ajoute");
            exit;
        } catch (PDOException $e) {
            $error_message = "Erreur lors de l'ajout de l'utilisateur : " . htmlspecialchars($e->getMessage());
        }
    }
}
$centreStmt = $db->prepare("SELECT id_centre, nom_centre FROM centres_collecte ORDER BY nom_centre");
$centreStmt->execute();
$centres = $centreStmt->fetchAll();


$stmt = $db->prepare("SELECT id_utilisateur, nom,role,id_centre FROM utilisateurs ORDER BY nom");
$stmt->execute();
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<title>Gestion des Utilisateurs</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="container mt-4">
    <h1>Gestion des Utilisateurs</h1>
    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">+ Nouveau</button>

    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger mt-3"><?= $error_message ?></div>
    <?php endif; ?>

    <?php if (empty($users)): ?>
        <div class="alert alert-info mt-3">Aucun utilisateur trouvé.</div>
    <?php else: ?>
        <table class="table table-striped mt-3">
            <thead>
                <tr><th>ID</th><th>Nom</th><th>Rôle</th><th>ID Centre</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id_utilisateur']) ?></td>
                    <td><?= htmlspecialchars($user['nom']) ?></td>
                    <td><?= htmlspecialchars($user['role']) ?></td>
                    <td><?= htmlspecialchars($user['id_centre'])?></td>
                    <td>
                        <a href="modifier_user.php?id_utilisateur=<?= htmlspecialchars($user['id_utilisateur']) ?>" class="btn btn-sm btn-primary">Modifier</a>
                        <a href="supprimer_user.php?id_utilisateur=<?= htmlspecialchars($user['id_utilisateur']) ?>" onclick="return confirm('Supprimer cet utilisateur ?')" class="btn btn-sm btn-danger">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserLabel">Ajouter un Utilisateur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" name="nom" id="nom" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="mot_de_passe" class="form-label">Mot de passe</label>
                        <input type="password" name="mot_de_passe" id="mot_de_passe" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Rôle</label>
                        <select name="role" id="role" class="form-select" required>
                            <option value="">-- Choisir Rôle --</option>
                            <option value="SECRETAIRE">SECRETAIRE</option>
                            <option value="MEDECIN">MEDECIN</option>
                            <option value="ADMIN">ADMIN</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="centres" class="form-label">Centre</label>
                        <select name="id_centre" id="centres" class="form-select" required>
                            <option value="">-- Choisir Centre --</option>
                            <?php foreach ($centres as $centre): ?>
                                <option value="<?= htmlspecialchars($centre['id_centre']) ?>">
                                    <?= htmlspecialchars($centre['nom_centre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" name="ajouter_utilisateur" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
