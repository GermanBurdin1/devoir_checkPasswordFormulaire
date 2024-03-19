<?php
header('Content-Type: application/json');

try {
    $host = "localhost";
    $dbname = "vente";
    $user = "root";
    $pwd = "";
    $connexion = new PDO("mysql:host=$host;dbname=$dbname", $user, $pwd);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_email = isset($_POST['user_email']) ? $_POST['user_email'] : '';

        if (!empty($user_email)) {
            $requeteVerif = "SELECT COUNT(*) FROM user WHERE user_email = :user_email";
            $stmtVerif = $connexion->prepare($requeteVerif);
            $stmtVerif->execute(['user_email' => $user_email]);
            $emailExist = $stmtVerif->fetchColumn();

            if ($emailExist > 0) {
                echo json_encode(['error' => true, 'message' => 'Cette adresse email est déjà utilisée.']);
                exit;
            } else {
                $requete = "INSERT INTO user (user_nom, user_prenom, user_pwd, user_email) VALUES (:user_nom, :user_prenom, :user_pwd, :user_email)";
                $stmt = $connexion->prepare($requete);
                $stmt->execute([
                    'user_nom' => $_POST['nom'], 
                    'user_prenom' => $_POST['prenom'],
                    'user_pwd' => password_hash($_POST['password'], PASSWORD_DEFAULT), 
                    'user_email' => $user_email
                ]);

                echo json_encode(['success' => true, 'message' => 'User ajouté avec succès.']);
                exit;
            }
        }
    }
} catch (PDOException $e) {
    echo json_encode(['error' => true, 'message' => 'Erreur de connexion: ' . $e->getMessage()]);
    exit;
}
?>

