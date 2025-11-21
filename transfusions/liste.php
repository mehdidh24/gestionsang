<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
checkAuth();
checkRole(['Admin']); 

$db = (new Database())->connect();

$stmt = $db->prepare(
    "SELECT t.id_transfusion, d.id_don, t.date_transfusion, t.hopital_recepteur,
            dn.cin, dn.groupe_sanguin, dn.rhesus
     FROM transfusions t
     JOIN dons d ON t.id_don = d.id_don
     JOIN donneurs dn ON d.id_donneur = dn.id_donneur
     ORDER BY t.id_transfusion DESC"
);
$stmt->execute();
$transfusions = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Liste des Transfusions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="container mt-4">

    <h3>Liste des Transfusions</h3>
    <button class="btn btn-success btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#addTransfusion">+ Nouveau</button>

    <?php if (empty($transfusions)): ?>
        <div class="alert alert-info">Aucune transfusion trouvée.</div>
    <?php else: ?>
    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID Transfusion</th>
                <th>ID Don</th>
                <th>CIN Donneur</th>
                <th>Groupe</th>
                <th>Rhesus</th>
                <th>Date</th>
                <th>Hôpital</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($transfusions as $t): ?>
            <tr>
                <td><?= $t['id_transfusion'] ?></td>
                <td><?= $t['id_don'] ?></td>
                <td><?= htmlspecialchars($t['cin']) ?></td>
                <td><?= htmlspecialchars($t['groupe_sanguin']) ?></td>
                <td><?= htmlspecialchars($t['rhesus']) ?></td>
                <td><?= $t['date_transfusion'] ?></td>
                <td><?= htmlspecialchars($t['hopital_recepteur']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<!-- Modal d'ajout -->
<div class="modal fade" id="addTransfusion">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="ajout_transfusion.php">

                <div class="modal-header">
                    <h5 class="modal-title">Nouvelle Transfusion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label class="form-label">ID Don</label>
                    <select name="id_don" class="form-control" required>
                        <option value="">-- Choisir --</option>
                        <?php foreach ($dons as $d): ?>
                            <option value="<?= $d['id_don'] ?>">
                                
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label class="form-label mt-3">Date Transfusion</label>
                    <input type="date" name="date_transfusion" class="form-control" required>

                    <label class="form-label mt-3">Hôpital Receveur</label>
                    <input type="text" name="hopital_recepteur" class="form-control" required>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>

            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include '../includes/footer.php'; ?>
</body>
</html>
