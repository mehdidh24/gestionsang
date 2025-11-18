<?php
require_once 'includes/auth.php';
require_once 'config/database.php';
checkAuth();
$db = (new Database())->connect();
$stmt = $db->prepare("SELECT * FROM dons ORDER BY id_don DESC");
$stmt->execute();
$dons = $stmt->fetchAll();

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
        
        <?php if (empty($dons)): ?>
            <div class="alert alert-info">Aucun don trouvé</div>
        <?php else: ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Donneur</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dons as $don): ?>
                    <tr>
                        <td><?= $don['id_don'] ?></td>
                        <td><?= $don['id_donneur'] ?></td>
                        
                        <td><?= $don['statut'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>