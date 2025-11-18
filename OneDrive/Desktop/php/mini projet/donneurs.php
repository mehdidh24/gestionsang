<?php
require_once 'includes/auth.php';
require_once 'config/database.php';
require_once 'models/Donor.php';

checkAuth();
checkRole(['Sécretaire', 'ADMIN']);

$database = new Database();
$db = $database->connect();
$donor = new $donneur($db);

$message = '';

// AJOUT d'un donneur
if($_POST && isset($_POST['ajouter'])) {
    $donor->cin = htmlspecialchars($_POST['cin']);
    $donor->nom = htmlspecialchars($_POST['nom']);
    $donor->prenom = htmlspecialchars($_POST['prenom']);
    $donor->groupe_sanguin = $_POST['groupe_sanguin'];
    $donor->rhesus = $_POST['rhesus'];
    $donor->telephone = htmlspecialchars($_POST['telephone']);
    $donor->adresse = htmlspecialchars($_POST['adresse']);

    if($donor->create()) {
        $message = '<div class="alert alert-success">Donneur ajouté avec succès!</div>';
    } else {
        $message = '<div class="alert alert-danger">Erreur lors de l\'ajout du donneur</div>';
    }
}

// RECHERCHE
$search = $_GET['search'] ?? '';
$groupe_filter = $_GET['groupe_sanguin'] ?? '';
$donors = $donor->read($search, $groupe_filter);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Donneurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-user-plus"></i> Ajouter un Donneur</h5>
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        <form method="POST">
                            <div class="mb-2">
                                <label class="form-label">CIN *</label>
                                <input type="text" name="cin" class="form-control" required maxlength="8">
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Nom *</label>
                                    <input type="text" name="nom" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Prénom *</label>
                                    <input type="text" name="prenom" class="form-control" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Groupe Sanguin *</label>
                                    <select name="groupe_sanguin" class="form-control" required>
                                        <option value="">Choisir...</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="AB">AB</option>
                                        <option value="O">O</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Rhésus *</label>
                                    <select name="rhesus" class="form-control" required>
                                        <option value="">Choisir...</option>
                                        <option value="+">Positif (+)</option>
                                        <option value="-">Négatif (-)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Téléphone</label>
                                <input type="tel" name="telephone" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Adresse</label>
                                <textarea name="adresse" class="form-control" rows="2"></textarea>
                            </div>
                            <button type="submit" name="ajouter" class="btn btn-primary w-100">
                                <i class="fas fa-save"></i> Ajouter le Donneur
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-list"></i> Liste des Donneurs</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" class="row g-3 mb-4">
                            <div class="col-md-6">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Rechercher par nom, prénom ou CIN..."
                                       value="<?php echo htmlspecialchars($search); ?>">
                            </div>
                            <div class="col-md-4">
                                <select name="groupe_sanguin" class="form-control">
                                    <option value="">Tous les groupes</option>
                                    <option value="A" <?php echo $groupe_filter == 'A' ? 'selected' : ''; ?>>A</option>
                                    <option value="B" <?php echo $groupe_filter == 'B' ? 'selected' : ''; ?>>B</option>
                                    <option value="AB" <?php echo $groupe_filter == 'AB' ? 'selected' : ''; ?>>AB</option>
                                    <option value="O" <?php echo $groupe_filter == 'O' ? 'selected' : ''; ?>>O</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>CIN</th>
                                        <th>Nom & Prénom</th>
                                        <th>Groupe Sanguin</th>
                                        <th>Téléphone</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = $donors->fetch(PDO::FETCH_ASSOC)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['cin']); ?></td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($row['nom'] . ' ' . $row['prenom']); ?></strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-danger">
                                                <?php echo htmlspecialchars($row['groupe_sanguin'] . $row['rhesus']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['telephone'] ?? 'N/A'); ?></td>
                                        <td>
                                            <a href="donor_detail.php?id=<?php echo $row['id_donneur']; ?>" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>