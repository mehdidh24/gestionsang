<?php
require_once '../includes/auth.php';
require_once '../config/database.php';

checkRole(['Admin','Sécrétaire']); 
$db = (new Database())->connect();

$params = [];
$where = [];

if (!empty($_GET['groupe_sanguin'])) {
    $where[] = "groupe_sanguin = ?";
    $params[] = $_GET['groupe_sanguin'];
}

if (!empty($_GET['ville'])) {
    $where[] = "ville LIKE ?";
    $params[] = "%".$_GET['ville']."%";
}

$whereSql = $where ? "WHERE " . implode(" AND ", $where) : "";

$limit = 7;
$page = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

$stmtCount = $db->prepare("SELECT COUNT(*) FROM donneurs $whereSql");
$stmtCount->execute($params);
$total = $stmtCount->fetchColumn();
$totalPages = ceil($total / $limit);

$stmt = $db->prepare("SELECT * FROM donneurs $whereSql ORDER BY id_donneur DESC LIMIT $limit OFFSET $offset");
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
    <h1>Liste des Donneurs</h1>

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
            <input type="text" name="ville" class="form-control" placeholder="Ville..." value="<?= htmlspecialchars($_GET['ville'] ?? '') ?>">
        </div>

        <div class="col-md-3 mt-4">
            <button class="btn btn-primary btn-sm mt-2">Filtrer</button>
            <a href="liste_donneurs.php" class="btn btn-secondary btn-sm mt-2">Réinitialiser</a>
        </div>
    </form>

    <a href="ajout_donneur.php" class="btn btn-success btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#addDonorModal">+ Nouveau</a>

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
        <tbody>
            <?php foreach($donneurs as $d): ?>
                <tr>
                    <td><?= $d['id_donneur'] ?></td>
                    <td><?= htmlspecialchars($d['cin']) ?></td>
                    <td><?= htmlspecialchars($d['groupe_sanguin']) ?></td>
                    <td><?= htmlspecialchars($d['rhesus']) ?></td>
                    <td><?= htmlspecialchars($d['ville']) ?></td>
                    <td>
                        <a href="supprimer_donneur.php?id_donneur=<?= $d['id_donneur'] ?>" 
                           class="btn btn-sm btn-danger"
                           >Supprimer</a>
                        <a href="modifier_donneurs.php?id_donneur=<?= $d['id_donneur'] ?>" class="btn btn-sm btn-primary">Modifier</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <nav>
      <ul class="pagination">
        <?php if ($page > 1): ?>
          <li class="page-item">
            <a class="page-link" href="?page=<?= $page - 1 ?>&amp;groupe_sanguin=<?= urlencode($_GET['groupe_sanguin'] ?? '') ?>&amp;ville=<?= urlencode($_GET['ville'] ?? '') ?>">Précédent</a>
          </li>
        <?php endif; ?>

        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
          <li class="page-item <?= $p == $page ? 'active' : '' ?>">
            <a class="page-link" href="?page=<?= $p ?>&amp;groupe_sanguin=<?= urlencode($_GET['groupe_sanguin'] ?? '') ?>&amp;ville=<?= urlencode($_GET['ville'] ?? '') ?>"><?= $p ?></a>
          </li>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
          <li class="page-item">
            <a class="page-link" href="?page=<?= $page + 1 ?>&amp;groupe_sanguin=<?= urlencode($_GET['groupe_sanguin'] ?? '') ?>&amp;ville=<?= urlencode($_GET['ville'] ?? '') ?>">Suivant</a>
          </li>
        <?php endif; ?>
      </ul>
    </nav>

</div>

<div class="modal fade" id="addDonorModal" tabindex="-1" aria-labelledby="addDonorLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="ajout_donneur.php">
        <div class="modal-header">
          <h5 class="modal-title" id="addDonorLabel">Ajouter un Donneur</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="cin" class="form-label">CIN</label>
            <input type="text" class="form-control" id="cin" name="cin" required>
          </div>
          <div class="mb-3">
            <label for="groupe_sanguin" class="form-label">Groupe Sanguin</label>
            <select class="form-select" id="groupe_sanguin" name="groupe_sanguin" required>
              <option value="">-- Choisir --</option>
              <option value="A">A</option>
              <option value="B">B</option>
              <option value="AB">AB</option>
              <option value="O">O</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="rhesus" class="form-label">Rhésus</label>
            <select class="form-select" id="rhesus" name="rhesus" required>
              <option value="">-- Choisir --</option>
              <option value="+">+</option>
              <option value="-">-</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="ville" class="form-label">Ville</label>
            <input type="text" class="form-control" id="ville" name="ville" required>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
