<?php
session_start();
require_once 'config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!empty($_POST['nom']) && !empty($_POST['mot_de_passe'])) {
        $database = new Database();
        $db = $database->connect();

        $nom = htmlspecialchars(trim($_POST['nom']));
        $password = $_POST['mot_de_passe'];

        $stmt = $db->prepare("SELECT id_utilisateur, nom, mot_de_passe, role 
                              FROM utilisateurs 
                              WHERE nom = :nom
                              LIMIT 1");
        $stmt->execute([':nom' => $nom]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($password, $user['mot_de_passe'])) {

                $_SESSION['user_id'] = $user['id_utilisateur'];
                $_SESSION['nom'] = $user['nom'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['logged_in'] = true;

                header("Location: dashboard.php");
                exit;

            } else {
                $error = "Mot de passe incorrect";
            }
        } else {
            $error = "Utilisateur introuvable";
        }

    } else {
        $error = "Veuillez remplir tous les champs.";
    }
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
            background: #f2f5f9;
            font-family: "Poppins", sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .login-box {
            width: 420px;
            background: #ffffff;
            border-radius: 12px;
            padding: 35px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .login-box h2 {
            color: #2c3e50;
            font-weight: 700;
        }

        .login-box p {
            color: #6c757d;
        }

        label {
            color: #34495e;
            font-weight: 500;
        }

        input.form-control {
            background: #f8f9fa;
            border: 1px solid #ced4da;
            height: 48px;
        }

        input.form-control:focus {
            border-color: #5c8df6;
            box-shadow: 0 0 0 3px rgba(92, 141, 246, 0.25);
        }

        .btn-primary-custom {
            background-color: #5c8df6;
            border: none;
            padding: 12px;
            font-size: 17px;
            border-radius: 8px;
            transition: background 0.2s ease;
        }

        .btn-primary-custom:hover {
            background-color: #3b6de0;
        }

        small {
            color: #7f8c8d;
        }
    </style>
</head>

<body>

    <div class="login-box">
        <div class="text-center mb-4">
            <i class="fas fa-tint fa-3x" style="color:#d63031"></i>
            <h2 class="mt-2">Gestion Sang</h2>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger py-2">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="mb-3">
                <label for="nom"><i class="fas fa-user"></i> Nom d'utilisateur</label>
                <input type="text" id="nom" name="nom" class="form-control" required autofocus>
            </div>

            <div class="mb-4">
                <label for="mot_de_passe"><i class="fas fa-lock"></i> Mot de passe</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" class="form-control" required>
            </div>

            <button type="submit" class="btn-primary-custom w-100">
                <i class="fas fa-sign-in-alt"></i> Se connecter
            </button>
        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>
