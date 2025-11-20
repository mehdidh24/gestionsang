<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
checkAuth();
checkRole(['ADMIN','Médecin']); 

$db = (new Database())->connect();

$parms = [];
// Requête préparée
$stmt = $db->prepare("
    SELECT t.id_transfusion, t.id_don,t.date_transfusion, t.hopital_recepteur
    FROM transfusions t ORDER BY t.date_transfusion DESC
");
$stmt->execute($parms);
$transfusions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$donneurs = $db->query("SELECT id_donneur FROM donneurs ORDER BY
id_donneur")->fetchAll();
$dons = $db->query("SELECT * FROM dons ORDER BY id_don DESC")->fetchAll();


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
    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addTransfusion">+ Nouveau</button>
    <?php if(empty($transfusions)): ?>
        <div class="alert alert-info mt-3">Aucune transfusion trouvée.</div>
    <?php else: ?>
        <table class="table table-striped mt-3">
            <tbody>
            <thead>
                <tr>
                    <th>ID Transfusion</th>
                    <th>ID Don</th>
                    <th>Donneur (CIN)</th>
                    <th>Groupe Sanguin</th>
                    <th>Rhesus</th>
                    <th>Date Transfusion</th>
                    <th>Hôpital Receveur</th>
                    <th>Actions</th>

                </tr>
            </thead>
            
            <?php foreach($transfusions as $t): ?>
                <tr>
                    <td><?= $t['id_transfusion'] ?></td>
                    <td><?= $t['id_don'] ?></td>
                    <td><?= $t['cin'] ?></td>
                    <td><?= $t['groupe_sanguin'] ?></td>
                    <td><?= $t['rhesus'] ?></td>
                    <td><?= $t['date_transfusion'] ?></td>
                    <td><?= $t['hopital_recepteur'] ?></td>
                    <td>
                        <a href="supprimer_transfusion.php?id=<?= $t['id_transfusion'] ?>" 
                        class="btn btn-danger btn-sm"
                        onclick="return confirm('Supprimer cette transfusion ?');">
                        Supprimer
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    
        
    
</div>
<div class="modal fade" id="addTransfusion">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter Transfusion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="ajout_transfusion.php">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">ID Don</label>
                        <select name="id_don" class="form-control" required>
                            <option value="">-- Choisir --</option>
                            <?php foreach ($dons as $d): ?>
                                <option value="<?= $d['id_don'] ?>">
                                    <?= $d['id_don'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                       
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date Transfusion</label>
                        <input type="date" name="date_transfusion" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hôpital Receveur</label>
                        <input type="text" name="hopital_recepteur" class="form-control" required>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include '../includes/footer.php'; ?>
</body>
</html>
