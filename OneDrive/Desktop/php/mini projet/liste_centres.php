<?php
require_once 'includes/auth.php';
require_once 'config/database.php';
checkAuth();

$db = (new Database())->connect();
$stmt = $db->prepare("SELECT id_centres FROM centres_collecte ORDER BY id_centres");
$stmt->execute();
$centres = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Centres de Collecte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container mt-4">
        <h3>Centres de Collecte</h3>
        
        <?php if (empty($centres)): ?>
            <div class="alert alert-info">Aucun centre trouvé</div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($centres as $centre): ?>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            
                            <p><?= htmlspecialchars($centre['id_centres']) ?></p>
                            
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>