<?php
// Connexion à la base de données
require_once('database.php');

// Vérification si la variable marque_id est définie et non vide
if(isset($_GET['marque_id']) && !empty($_GET['marque_id'])) {
    // Récupération de l'ID de la marque depuis la requête GET
    $marque_id = $_GET['marque_id'];

    // Requête SQL pour récupérer toutes les voitures correspondant à la marque
    $stmt = $pdo->prepare('SELECT * FROM voiture WHERE id_marque =?');
    $stmt->execute([$marque_id]);
    $voitures = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Si aucune voiture n'est trouvée, renvoie un message d'erreur
    if(empty($voitures)) {
        echo json_encode(['message' => 'Aucune voiture trouvée pour cette marque']);
    } else {
        // Renvoyer les voitures au format JSON
        echo json_encode($voitures);
    }
} else {
    // Si marque_id n'est pas défini ou vide, renvoyer une erreur
    http_response_code(400);
    echo json_encode(['message' => 'L\'ID de la marque n\'a pas été fourni']);
}
?>