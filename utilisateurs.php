<?php
require_once 'includes/auth.php'; 
require_once 'config/database.php';

checkRole(['ADMIN']); 

$db = (new Database())->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST['nom'],  $_POST['mot_de_passe'], $_POST['role'])) {
        $nom = $_POST['nom'];
       
        $role = $_POST['role'];
        $password_hash = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT); 

        try {
            $stmt = $db->prepare("
                INSERT INTO utilisateurs (nom,role,mot_de_passe) VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$nom, $role, $password_hash]);

            header("Location: utilisateurs.php"); 
            exit;

        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout de l'utilisateur : " . $e->getMessage();
        }
    }
}

$stmt = $db->prepare("
    SELECT id_utilisateur, nom,role,mot_de_passe FROM utilisateurs ORDER BY nom
");
$stmt->execute();
$users = $stmt->fetchAll();



?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Gestion des Utilisateurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="container mt-4">
    <h3>Gestion des Utilisateurs</h3>
    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addUser">+ Nouveau</button>

    <?php if (empty($users)): ?>
        <div class="alert alert-info mt-3">Aucun utilisateur trouvé.</div>
    <?php else: ?>

        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['id_utilisateur'] ?></td>
                    <td><?= htmlspecialchars($user['nom']) ?></td>
                    
                    <td><?= htmlspecialchars($user['role']) ?></td>
                    <td>
                        <a href="modifier_user.php?id=<?= $user['id_utilisateur'] ?>" 
                            class="btn btn-sm btn-primary">
                            Modifier
                        </a>
                        <a href="supprimer_user.php?id=<?= $user['id_utilisateur'] ?>"
                            class="btn btn-sm btn-danger"
                            onclick="return confirm('Supprimer cet utilisateur ?')">
                            Supprimer
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>
</div>


<div class="modal fade" id="addUser" tabindex="-1" aria-labelledby="addUserLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="addUserLabel">Ajouter un Utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form method="post" action="ajout_utilisateur.php">
                <input type="hidden" name="action" value="add_user">
                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" name="nom" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="mot_de_passe" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Rôle</label>
                        <select name="role" class="form-control" required>
                            <option value="">-- Choisir Rôle --</option>
                            <option value="secretaire">secretaire</option>
                            <option value="Médecin">Médecin</option>
                            <option value="ADMIN">ADMIN</option>
                            <?php foreach ($roles_disponibles as $role): ?>
                                <option value="<?= $role ?>">
                                    <?= $role ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>

        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
