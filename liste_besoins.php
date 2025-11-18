<?php
require_once 'config/database.php';
require_once 'includes/auth.php';

// Vérifier authentification et rôle
checkAuth();
checkRole(['SECRETAIRE', 'ADMIN', 'Médecin']);

// Connexion BD
$database = new Database();
$db = $database->connect();

// Récupérer tous les besoins
$stmt = $db->prepare("SELECT * FROM besoins ORDER BY niveau_alerte DESC, groupe_sanguin ASC");
$stmt->execute();
$besoins = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Besoins en Sang</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        .card-stat { transition: 0.2s; }
        .card-stat:hover { transform: translateY(-5px); }

        .urgence-urgent    { border-left: 4px solid #dc3545; }
        .urgence-critique  { border-left: 4px solid #ffc107; }
        .urgence-normal    { border-left: 4px solid #198754; }
    </style>
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="container mt-4">

    <!-- Titre + bouton -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-heartbeat text-danger me-2"></i>Besoins en Sang</h1>

        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ajoutBesoinModal">
            <i class="fas fa-plus me-1"></i> Nouveau Besoin
        </button>
    </div>

    <!-- Cartes Statistiques -->
    <div class="row mb-4">

        <div class="col-md-3">
            <div class="card card-stat bg-danger text-white">
                <div class="card-body">
                    <?php
                    $urgent_count = array_reduce($besoins, fn($n,$b) => $n + ($b['niveau_alerte'] === 'urgent'), 0);
                    ?>
                    <h3><?= $urgent_count ?></h3>
                    <p class="mb-0">Besoins Urgents</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-stat bg-warning text-dark">
                <div class="card-body">
                    <?php
                    $critique_count = array_reduce($besoins, fn($n,$b) => $n + ($b['niveau_alerte'] === 'critique'), 0);
                    ?>
                    <h3><?= $critique_count ?></h3>
                    <p class="mb-0">Besoins Critiques</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-stat bg-success text-white">
                <div class="card-body">
                    <?php
                    $normal_count = array_reduce($besoins, fn($n,$b) => $n + ($b['niveau_alerte'] === 'normal'), 0);
                    ?>
                    <h3><?= $normal_count ?></h3>
                    <p class="mb-0">Besoins Normaux</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-stat bg-info text-white">
                <div class="card-body">
                    <h3><?= count($besoins) ?></h3>
                    <p class="mb-0">Total Besoins</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Liste des Besoins</h5>
        </div>

        <div class="card-body">

            <?php if (empty($besoins)): ?>
                <div class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5>Aucun besoin enregistré</h5>
                    <p class="text-muted">Ajoutez un nouveau besoin.</p>
                </div>

            <?php else: ?>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Groupe Sanguin</th>
                                <th>Niveau d'Alerte</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php foreach ($besoins as $b): ?>
                            <?php
                            $badge = [
                                'urgent'   => 'danger',
                                'critique' => 'warning',
                                'normal'   => 'success'
                            ][$b['niveau_alerte']] ?? 'secondary';
                            ?>

                            <tr class="urgence-<?= strtolower($b['niveau_alerte']) ?>">
                                <td><span class="badge bg-danger fs-6"><?= $b['groupe_sanguin'] ?></span></td>

                                <td><span class="badge bg-<?= $badge ?>"><?= strtoupper($b['niveau_alerte']) ?></span></td>

                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="modifier.php?id=<?= $b['id_besoin'] ?>" class="btn btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="supprimer.php?id=<?= $b['id_besoin'] ?>" 
                                           class="btn btn-outline-danger"
                                           onclick="return confirm('Supprimer ?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>


<!-- MODAL AJOUT BESOIN -->
<div class="modal fade" id="ajoutBesoinModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="POST" action="enregistrement.php">

                <div class="modal-header">
                    <h5 class="modal-title">➕ Ajouter un Besoin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <label>Groupe Sanguin</label>
                    <select name="groupe_sanguin" class="form-control" required>
                        <option>A+</option><option>A-</option>
                        <option>B+</option><option>B-</option>
                        <option>AB+</option><option>AB-</option>
                        <option>O+</option><option>O-</option>
                    </select>

                    <label class="mt-2">Niveau d'Alerte</label>
                    <select name="niveau_alerte" class="form-control" required>
                        <option>URGENT</option>
                        <option>CRITIQUE</option>
                        <option>NORMAL</option>
                    </select>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>

            </form>

        </div>
    </div>
</div>


<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
