<?php
// Connexion à la base de données
require_once('database.php');

// Vérification si la variable id est définie et non vide
if(isset($_GET['categorie_id']) && !empty($_GET['categorie_id'])) {
    // Récupération de l'ID de la categorie depuis la requête GET
    $categorie_id = $_GET['categorie_id'];

    // Requête SQL pour récupérer toutes les  correspondant à la categorie
    $stmt = $pdo->prepare('SELECT * FROM sous_categorie WHERE id_categorie =?');
    $stmt->execute([$categorie_id]);
    $sous = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Si aucune  n'est trouvée, renvoie un message d'erreur
    if(empty($sous)) {
        echo json_encode(['message' => 'Aucune sous categorie trouvée pour cette marque']);
    } else {
        // Renvoyer les sous au format JSON
        echo json_encode($sous);
    }
} else {
    // Si marque_id n'est pas défini ou vide, renvoyer une erreur
    http_response_code(400);
    echo json_encode(['message' => 'L\'ID de la categorie n\'a pas été fourni']);
}
?>