<?php
// Affiche toutes les erreurs PHP (pour le débogage)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Changez ce chemin pour pointer vers votre fichier database.php
require_once('dashboard/database.php');

if (isset($_GET['wilaya_id']) && isset($_GET['type_livraison'])) {
    $wilaya_id = $_GET['wilaya_id'];
    $type_livraison = $_GET['type_livraison'];

    $stmt = $pdo->prepare('SELECT domicile, bureau FROM delivery WHERE id = ?');
    $stmt->execute([$wilaya_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $prix = ($type_livraison === 'domicile') ? $result['domicile'] : $result['bureau'];
        echo json_encode(['prix' => $prix]);
    } else {
        echo json_encode(['error' => 'Wilaya not found']);
    }
} else {
    echo json_encode(['error' => 'Invalid parameters']);
}
?>
