<?php
try {
    $host = "localhost";
    $dbname = "vente";
    $user = "root";
    $pwd = "";
    $connexion = new PDO("mysql:host=$host;dbname=$dbname", $user, $pwd);


    $user_email = isset($_POST['user_email']) ? $_POST['user_email'] : (isset($_GET['user_email']) ? $_GET['user_email'] : '');

    if (!empty($user_email)) {
        $requeteVerif = "SELECT COUNT(*) FROM user WHERE user_email = :user_email";
        $stmtVerif = $connexion->prepare($requeteVerif);
        $stmtVerif->execute(['user_email' => $user_email]);
        $emailExist = $stmtVerif->fetchColumn();

        if ($emailExist > 0) {
            echo json_encode(['error' => 'Cette adresse email est déjà utilisée.']);
            exit;
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $requete = "INSERT INTO user (user_nom, user_prenom, user_pwd, user_email) VALUES (:user_nom, :user_prenom, :user_pwd, :user_email)";
            $stmt = $connexion->prepare($requete);
            $stmt->execute([
                'user_nom' => $_POST['nom'],
                'user_prenom' => $_POST['prenom'],
                'user_pwd' => password_hash($_POST['password'], PASSWORD_DEFAULT), 
                'user_email' => $user_email
            ]);

            echo json_encode(['success' => $stmt->rowCount()]);
            exit;
        }
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erreur de connexion: ' . $e->getMessage()]);
    exit;
}
?>

