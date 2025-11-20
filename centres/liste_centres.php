<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
checkAuth();

$db = (new Database())->connect();
$stmt = $db->prepare("SELECT id_centre FROM centres_collecte ORDER BY id_centre");
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
    <?php include '../includes/header.php'; ?>
    
    <div class="container mt-4">
        <h3>Centres de Collecte</h3>
        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addcentre">+ Nouveau</button>
        
        <table class="table table-striped mt-3">

            <?php if (empty($centres)): ?>
            <div class="alert alert-info mt-3">Aucun centre trouvé.</div>
            
        
            <?php else: ?>
            <div class="row">
            <table class="table table-striped">
            <tr><th>ID</th><th>CIN</th><th>Groupe</th><th>Rhésus</th></tr>
                
                <?php foreach ($centres as $centre): ?>
                    <tr>
                        <td></td>
                    </tr>
                            
                        <td><?= htmlspecialchars($centre['id_centre']) ?></td>
                        
                        
                <?php endforeach; ?>

            </div>
            
        <?php endif; ?>
        </table>
    </div>
    <div class="modal fade" id="addcentre">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter Centre de Collecte</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="post" action="ajout_centre.php">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">ID Centre</label>
                            <input type="text" name="id_centre" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">ID donneur</label>
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
</body>
</html>