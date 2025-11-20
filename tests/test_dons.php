<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
checkAuth();
checkRole(['Médecin', 'ADMIN']);

$db = (new Database())->connect();

$stmt = $db->prepare("
    SELECT d.id_don, d.id_donneur
    FROM dons d
    JOIN donneurs dn ON dn.id_donneur = d.id_donneur
    WHERE d.statut = 'EN STOCK'
    ORDER BY d.id_don DESC
");
$stmt->execute();
$dons = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Validation des Tests</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<?php include '../includes/header.php'; ?>
<div>
<h2>Dons en Attente de Validation</h2>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID Don</th>
            <th>Donneur</th>
            <th>Action</th>
            
        </tr>
    </thead>
    <tbody>
    <?php foreach ($dons as $don): ?>
        <tr>
            <td><?= $don['id_don'] ?></td>
            <td><?= $don['id_donneur']?></td>
            
            <td>
                <a href="../tests/valider_test.php?id_don=<?= $don['id_don'] ?>" class="btn btn-primary btn-sm">
                    Valider
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<a href="../dons/liste_dons.php" class="btn">Retour à la Liste des Dons</a>
<?php include '../includes/footer.php'; ?>
</body>
</html>
