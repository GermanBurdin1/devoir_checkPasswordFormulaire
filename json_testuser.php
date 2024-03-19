<?php
try {
    $host = "localhost";
    $dbname = "vente";
    $user = "root";
    $pwd = "";

    $user_email = isset($_POST['user_email']) ? $_POST['user_email'] : '';

    $connexion = new PDO("mysql:host=$host;dbname=$dbname", $user, $pwd);
    $requete = "SELECT count(*) as NB FROM user WHERE user_email = :user_email";
    $resultat = $connexion->prepare($requete);

    $resultat->bindParam(':user_email', $user_email);
    $resultat->execute();
    $data = $resultat->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($data);
} catch (PDOException $e) {
    echo json_encode(array('error' => 'Erreur de connexion: ' . $e->getMessage()));
    die;
}
?>