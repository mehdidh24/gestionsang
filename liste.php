<?php
require_once 'includes/auth.php';
require_once 'config/database.php';
checkAuth();
checkRole(['ADMIN','Médecin']); 

$db = (new Database())->connect();

$where = "";
$params = [];

// Filtrer si ?id_don=XX est présent
if (!empty($_GET['id_don'])) {
    $where = " AND t.id_don = :id_don ";
    $params[':id_don'] = intval($_GET['id_don']);
}

// Requête préparée
$stmt = $db->prepare("
    SELECT t.id_transfusion, t.id_don, d.cin, d.groupe_sanguin, d.rhesus, t.date_transfusion, t.hopital_recepteur,don.statut
    FROM transfusions t
    JOIN dons don ON t.id_don = don.id_don
    JOIN donneurs d ON don.id_donneur = d.id_donneur
    WHERE don.statut='utilisé' $where
    ORDER BY t.date_transfusion DESC
");
$stmt->execute($params);
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

<?php include 'includes/header.php'; ?>

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
            <form method="post"">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">ID Don</label>
                        <input type="number" name="id_don" class="form-control" required>
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
<?php include 'includes/footer.php'; ?>
</body>
</html>
