<?php
require_once 'includes/auth.php';
require_once 'config/database.php';
checkAuth();
checkRole(['ADMIN','Médecin']); // seulement admin peut voir les transfusions

$db = (new Database())->connect();

$where = "";
$params = [];

// Filtrer si ?id_don=XX est présent
if (!empty($_GET['id_don'])) {
    $where = " AND t.id_don = :id_don ";
    $params[':id_don'] = intval($_GET['id_don']);
}

// Requête préparée
$sql = "
    SELECT t.id_transfusion, t.id_don, t.date_transfusion, t.hopital_recepteur,
           do.cin, do.groupe_sanguin, do.rhesus
    FROM transfusions t
    JOIN dons d ON t.id_don = d.id_don
    JOIN donneurs do ON d.id_donneur = do.id_donneur
    WHERE 1=1 $where
    ORDER BY t.date_transfusion DESC
";

$stmt = $db->prepare($sql);
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

    <?php if(empty($transfusions)): ?>
        <div class="alert alert-info mt-3">Aucune transfusion trouvée.</div>
    <?php else: ?>
        <table class="table table-striped mt-3">
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
            <tbody>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'includes/footer.php'; ?>
</body>
</html>
