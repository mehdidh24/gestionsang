<?php
require_once 'includes/auth.php';
require_once 'config/database.php';
checkAuth();

$db = (new Database())->connect();

$id_don = isset($_GET['id_don']) ? intval($_GET['id_don']) : 0;

$tests = $db->prepare("
    SELECT t.id_test, t.id_don, t.est_conforme, t.date_test, d.id_donneur
    FROM tests_dons t
    JOIN dons d ON t.id_don = d.id_don
    WHERE t.id_don = :id_don
    ORDER BY t.date_test DESC
");
$tests->execute([':id_don' => $id_don]);
$tests = $tests->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tests des Dons</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">      
</head>
<body>


<?php include 'includes/header.php'; ?>
 <h3>Liste des Tests pour le Don #<?= $id_don ?></h3>
<?php if(empty($tests)): ?>
    <div class="alert alert-info">Aucun test trouvé pour ce don.</div>
<?php else: ?>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID Test</th>
            <th>Donneur</th>
            <th>Résultats</th>
            <th>Conforme</th>
            <th>Date Test</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($tests as $test): ?>
            <tr>
                <td><?= $test['id_test'] ?></td>
                <td><?= $test['id_donneur'] ?></td>
                <td><?= $test['est_conforme'] ? 'Oui' : 'Non' ?></td>
                <td><?= $test['date_test'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
<a href="liste_dons.php" class="btn btn-secondary mt-3">Retour à la Liste des Dons</a>
<?php include 'includes/footer.php'; ?>
</body>
</html>
