<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
checkAuth();

$db = (new Database())->connect();


$where = "WHERE 1 ";
$params = [];

if (!empty($_GET['groupe_sanguin'])) {
    $where .= " AND groupe_sanguin = ? ";
    $params[] = $_GET['groupe_sanguin'];
}

if (!empty($_GET['ville'])) {
    $where .= " AND ville LIKE ? ";
    $params[] = "%".$_GET['ville']."%";
}

$limit = 5;
$page  = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$stmtCount = $db->prepare("SELECT COUNT(*) FROM donneurs $where");
$stmtCount->execute($params);
$total = $stmtCount->fetchColumn();
$totalPages = ceil($total / $limit);

$sql = "SELECT * FROM donneurs $where ORDER BY id_donneur DESC LIMIT $limit OFFSET $offset";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$donneurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Liste Donneurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="container mt-4">
    <h3>Liste des Donneurs</h3>

    <form method="GET" class="row g-3 mt-3 mb-3">

        <div class="col-md-3">
            <label>Groupe sanguin</label>
            <select name="groupe_sanguin" class="form-select">
                <option value="">-- Tous --</option>
                <?php foreach(['A','B','AB','O'] as $g): ?>
                    <option value="<?= $g ?>" <?= (($_GET['groupe_sanguin'] ?? '') == $g) ? 'selected' : '' ?>>
                        <?= $g ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-3">
            <label>Ville</label>
            <input type="text" name="ville" class="form-control" placeholder="Ville..."
                   value="<?= $_GET['ville'] ?? '' ?>">
        </div>

        <div class="col-md-3 mt-4">
            <button class="btn btn-primary btn-sm mt-2">Filtrer</button>
            <a href="liste_donneurs.php" class="btn btn-secondary btn-sm mt-2">Réinitialiser</a>
        </div>
    </form>

    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addDonneur">+ Nouveau</button>

    <table class="table table-striped mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>CIN</th>
                <th>Groupe</th>
                <th>Rhésus</th>
                <th>Ville</th>
                <th>Actions</th>
            </tr>
        </thead>

        <?php foreach($donneurs as $d): ?>
        <tr>
            <td><?= $d['id_donneur'] ?></td>
            <td><?= $d['cin'] ?></td>
            <td><?= $d['groupe_sanguin'] ?></td>
            <td><?= $d['rhesus'] ?></td>
            <td><?= htmlspecialchars($d['ville']) ?></td>
            <td>
                <a href="supprimer_donneur.php?id_donneur=<?= $d['id_donneur'] ?>"
                    class="btn btn-sm btn-danger"
                    onclick="return confirm('Supprimer ce donneur ?')">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    

</div>

<div class="modal fade" id="addDonneur">
    <div class="modal-dialog">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title">Ajouter Donneur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form method="post" action="ajout_donneur.php">
                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">CIN</label>
                        <input type="text" name="cin" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Groupe sanguin</label>
                        <select name="groupe_sanguin" class="form-control" required>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="AB">AB</option>
                            <option value="O">O</option>
                        </select>
                    </div>  

                    <div class="mb-3">
                        <label class="form-label">Rhésus</label>
                        <select name="rhesus" class="form-control" required>
                            <option value="+">+</option>
                            <option value="-">-</option>
                        </select>
                    </div>   

                    <div class="mb-3">
                        <label class="form-label">Ville</label>
                        <input type="text" name="ville" class="form-control" required>
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



<?php include '../includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
