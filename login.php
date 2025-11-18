<?php
session_start();
require_once 'config/database.php';
require_once 'models/User.php';

$error = '';

if($_POST && !empty($_POST['nom']) && !empty($_POST['mot_de_passe'])) {
    $database = new Database();
    $db = $database->connect();

    $user = new User($db);
    $user->nom = htmlspecialchars($_POST['nom']);
    $user->mot_de_passe = $_POST['mot_de_passe'];

    if($user->login()) {
        // Création de la session avec toutes les données nécessaires
        $_SESSION['user_id'] = $user->id_utilisateur;
        $_SESSION['nom'] = $user->nom;
        $_SESSION['role'] = $user->role;
        $_SESSION['logged_in'] = true;
        
        // Redirection vers le dashboard
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Nom d'utilisateur ou mot de passe incorrect";
    }
} elseif($_POST) {
    $error = "Veuillez remplir tous les champs";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Gestion Sang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="login-container p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-tint fa-3x text-danger mb-3"></i>
                        <h2 class="text-primary">Gestion Sang</h2>
                        <p class="text-muted">Système de gestion des dons de sang</p>
                    </div>

                    <?php if(!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="login.php">
                        <div class="mb-3">
                            <label for="nom" class="form-label">
                                <i class="fas fa-user"></i> Nom d'utilisateur
                            </label>
                            <input type="text" class="form-control form-control-lg" id="nom" name="nom" placeholder="Entrez votre nom d'utilisateur"required autofocus>
                        </div>
                        
                        <div class="mb-4">
                            <label for="mot_de_passe" class="form-label">
                                <i class="fas fa-lock"></i> Mot de passe
                            </label>
                            <input type="password" class="form-control form-control-lg" id="mot_de_passe"  name="mot_de_passe" placeholder="Entrez votre mot de passe" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-sign-in-alt"></i> Se connecter
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <small class="text-muted">
                            © 2025 Gestion Sang - Tous droits réservés
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>