<?php
require_once 'includes/auth.php';
checkRole(['ADMIN','Médecin']); // Seul ADMIN et Médecin peuvent modifier
require_once 'config/database.php';

if (!isset($_GET['id'])) {
    header("Location:utilisateurs.php");
    exit;
}

$id = intval($_GET['id']);
$db = (new Database())->connect();

// Récupérer les infos actuelles
$stmt = $db->prepare("SELECT nom,role FROM utilisateurs WHERE id_utilisateur = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Utilisateur non trouvé !");
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    
    $role = $_POST['role'];

    $sql = "UPDATE utilisateurs SET nom = ?, email = ?, role = ?";

    // Si le mot de passe est renseigné, le hacher
    if (!empty($_POST['password'])) {
        $sql .= ", mot_de_passe = ?";
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $db->prepare($sql . " WHERE id_utilisateur = ?");
        $stmt->execute([$nom, $email, $role, $password, $id]);
    } else {
        $stmt = $db->prepare($sql . " WHERE id_utilisateur = ?");
        $stmt->execute([$nom, $email, $role, $id]);
    }

    header("Location: utilisateurs.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Modifier Utilisateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<h2>Modifier l'utilisateur</h2>
<form method="POST">
    <input type="text" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
    <input type="password" name="password" placeholder="Laisser vide pour garder le mot de passe">
    <select name="role" required>
        <option value="ADMIN" <?= $user['role'] == 'ADMIN' ? 'selected' : '' ?>>ADMIN</option>
        <option value="MEDECIN" <?= $user['role'] == 'MEDECIN' ? 'selected' : '' ?>>MEDECIN</option>
        <option value="SECRETAIRE" <?= $user['role'] == 'SECRETAIRE' ? 'selected' : '' ?>>SECRETAIRE</option>
    </select>
    <button type="submit">Modifier</button>
</form>
<?php include 'includes/footer.php'; ?>
</body>
</html>
