<?php
require_once '../includes/auth.php';
require_once '../config/database.php';

checkRole(['Admin']);
$db = (new Database())->connect();
$stmt = $db->prepare("SELECT id_centre, nom_centre, adresse FROM centres_collecte ORDER BY id_centre DESC");
$stmt->execute();
$centres = $stmt->fetchAll();


$message = "";
if (!empty($_GET['msg'])) {
    $message = htmlspecialchars($_GET['msg']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Centres de Collecte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="container mt-4">

    <h1 class="mb-3">Centres de Collecte</h1>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>

    <button class="btn btn-success btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#addCentre">
        + Nouveau Centre
    </button>

    <?php if (empty($centres)): ?>
        <div class="alert alert-info">Aucun centre trouvé.</div>
    <?php else: ?>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID Centre</th>
                    <th>Nom du centre</th>
                    <th>Adresse</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($centres as $centre): ?>
                    <tr>
                        <td><?= htmlspecialchars($centre['id_centre']) ?></td>
                        <td><?= htmlspecialchars($centre['nom_centre']) ?></td>
                        <td><?= htmlspecialchars($centre['adresse']) ?></td>
                        <td>
                            <a href="supprimer_centre.php?id=<?= $centre['id_centre'] ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Supprimer ce centre ?');">
                                Supprimer
                            </a>
                            <a href="modifier_centre.php?id=<?= htmlspecialchars($centre['id_centre']) ?>" 
                                class="btn btn-primary btn-sm"
                                onclick="return confirm('Modifier ce centre ?');">
                                Modifier
                            </a>

                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</div>


<div class="modal fade" id="addCentre">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="post" action="ajout_centre.php">

                <div class="modal-header">
                    <h5 class="modal-title">Ajouter Centre de Collecte</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nom du Centre</label>
                        <input type="text" name="nom" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Adresse</label>
                        <input type="text" name="adresse" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>

            </form>

        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body> 
</html>