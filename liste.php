<?php
require_once 'config/database.php';
require_once 'includes/auth.php';
checkAuth();

$database = new Database();
$db = $database->connect();

$transfusions = $db->query("
    SELECT t.id_don,t.id_transfusion, t.date_transfusion, t.hopital_recepteur,do.cin, do.groupe_sanguin, do.rhesus
    FROM transfusions t
    JOIN dons d ON t.id_don = d.id_don
    JOIN donneurs do ON d.id_donneur = do.id_donneur
    ORDER BY t.date_transfusion DESC
");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Transfusions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container mt-4">
        <h2>🩸 Liste des Transfusions</h2>
        
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Donneur</th>
                        <th>Groupe</th>
                        <th>Hôpital</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php while($trans = $transfusions->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($trans['date_transfusion'])) ?></td>
                        
                        <td>
                            <span class="badge bg-danger">
                                <?= $trans['groupe_sanguin'] ?><?= $trans['rhesus'] ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($trans['hopital_recepteur']) ?></td>
                        
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>