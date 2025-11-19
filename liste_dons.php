<?php
require_once 'includes/auth.php';
require_once 'config/database.php';
checkAuth();

$db = (new Database())->connect();

// LISTE DES DONS
$stmt = $db->prepare("
    SELECT id_don, id_donneur, statut,id_centre FROM dons ORDER BY id_don DESC
");
$stmt->execute();
$dons = $stmt->fetchAll();


$donneurs = $db->query("SELECT id_donneur FROM donneurs ORDER BY id_donneur
")->fetchAll();
$centres = $db->query("SELECT id_centre FROM centres_collecte ORDER BY id_centre")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Liste des Dons</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="container mt-4">
    <h3>Liste des Dons</h3>
    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addDon">+ Nouveau</button>

    <?php if (empty($dons)): ?>
        <div class="alert alert-info mt-3">Aucun don trouvé.</div>
    <?php else: ?>

        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>ID Don</th>
                    <th>ID Donneur</th>
                    <th>Statut</th>
                    <th>Centre id</th>
                    <th>Transfusion</th>
                    <th>Actions</th>
                    
                </tr>
            </thead>
            <tbody>
            <?php foreach ($dons as $don): ?>
                <tr>
                    <td><?= $don['id_don'] ?></td>
                    <td><?= $don['id_donneur'] ?></td>
                    <td><?= $don['statut'] ?></td>
                    <td><?= $don['id_centre']?></td>
                    <td>
                        <?php if ($don['statut'] === 'utilisé'): ?>
                            <a href="liste.php?id_don=<?= $don['id_don'] ?>" 
                            class="btn btn-sm btn-primary">
                            Voir transfusion
                            </a>
                        <?php else: ?>
                            <span class="text-muted">—</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <a href="supprimer_don.php?id_don=<?= $don['id_don'] ?>"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Supprimer ce don ?')">
                            Supprimer
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>
</div>


<!-- MODAL AJOUT DON -->
<div class="modal fade" id="addDon">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Ajouter un Don</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form method="post" action="ajout_don.php">
                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">ID Donneur</label>
                        <select name="id_donneur" class="form-control" required>
                            <option value="">-- Choisir --</option>
                            <?php foreach ($donneurs as $d): ?>
                                <option value="<?= $d['id_donneur'] ?>">
                                    <?= $d['id_donneur'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Statut</label>
                        <select name="statut" class="form-control" required>
                            <option value="en stock">En stock</option>
                            
                            
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Centre</label>
                        <select name="id_centre" class="form-control" required>
                            <option value="">-- Choisir Centre --</option>
                            <?php foreach ($centres as $c): ?>
                                <option value="<?= $c['id_centre'] ?>">
                                    <?= $c['id_centre'] ?>
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
