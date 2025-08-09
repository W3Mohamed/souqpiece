<?php
require_once('database.php');
header('Content-Type: application/json');

if (!isset($_GET['query']) || strlen(trim($_GET['query'])) < 2) {
    exit(json_encode([]));
}

$searchTerm = trim($_GET['query']);

try {
    // Remplacer les espaces par des * pour unifier le traitement
    $normalizedSearchTerm = str_replace(' ', '*', $searchTerm);
    
    // Vérifie si la recherche contient des * (y compris ceux remplacés depuis les espaces)
    if (strpos($normalizedSearchTerm, '*') !== false) {
        $terms = explode('*', $normalizedSearchTerm);
        $terms = array_map('trim', $terms);
        $terms = array_filter($terms);
        
        // Si on a plusieurs termes, on cherche les références qui contiennent tous les termes
        if (count($terms) > 1) {
            // Construction de la requête pour les références
            $refConditions = [];
            $refParams = [];
            foreach ($terms as $term) {
                $refConditions[] = 'reference LIKE ?';
                $refParams[] = '%' . $term . '%';
            }
            $refWhere = implode(' AND ', $refConditions);
            
            // Construction de la requête pour les descriptions
            $descConditions = [];
            $descParams = [];
            foreach ($terms as $term) {
                $descConditions[] = 'description LIKE ?';
                $descParams[] = '%' . $term . '%';
            }
            $descWhere = implode(' AND ', $descConditions);
            
            $sql = "(SELECT reference as result FROM reference WHERE $refWhere)
                    UNION
                    (SELECT 
                        CASE 
                            WHEN description LIKE '%//%' THEN SUBSTRING(description, 1, LOCATE('//', description) - 1)
                            ELSE description
                        END as result
                     FROM pvd WHERE $descWhere)
                    ";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array_merge($refParams, $descParams));
            $results = $stmt->fetchAll(PDO::FETCH_COLUMN);
        } else {
            // Un seul terme après le * (cas improbable mais à gérer)
            $sql = "(SELECT reference as result FROM reference WHERE reference LIKE ?)
                    UNION
                    (SELECT 
                        CASE 
                            WHEN description LIKE '%//%' THEN SUBSTRING(description, 1, LOCATE('//', description) - 1)
                            ELSE description
                        END as result
                     FROM pvd WHERE description LIKE ?)
                    ";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute(["%$terms[0]%", "%$terms[0]%"]);
            $results = $stmt->fetchAll(PDO::FETCH_COLUMN);
        }
    } else {
        // Recherche normale (sans étoiles et sans espaces significatifs)
        $sql = "(SELECT reference as result FROM reference WHERE reference LIKE ?)
                UNION
                (SELECT 
                    CASE 
                        WHEN description LIKE '%//%' THEN SUBSTRING(description, 1, LOCATE('//', description) - 1)
                        ELSE description
                    END as result
                 FROM pvd WHERE description LIKE ?)
                ";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["%$searchTerm%", "%$searchTerm%"]);
        $results = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    // Recherche dans le fichier JSON si pas de résultats ou pour compléter
    $jsonFile = 'data/stock.json';
    if (file_exists($jsonFile)) {
        $jsonData = json_decode(file_get_contents($jsonFile), true);
        
        if (strpos($normalizedSearchTerm, '*') !== false) {
            $terms = explode('*', $normalizedSearchTerm);
            $terms = array_map('trim', $terms);
            $terms = array_filter($terms);
            
            $filtered = array_filter($jsonData, function($item) use ($terms) {
                foreach ($terms as $term) {
                    if (stripos($item['reference'], $term) === false && 
                        stripos($item['libelle'], $term) === false) {
                        return false;
                    }
                }
                return true;
            });
        } else {
            $filtered = array_filter($jsonData, function($item) use ($searchTerm) {
                return stripos($item['reference'], $searchTerm) !== false || 
                       stripos($item['libelle'], $searchTerm) !== false;
            });
        }
        
        // Création des suggestions à partir des références et libellés
        $jsonResults = [];
        foreach ($filtered as $item) {
            // Vérifier si la référence correspond
            if (stripos($item['reference'], $searchTerm) !== false) {
                $jsonResults[] = $item['reference'];
            }
            
            // Vérifier si le libellé correspond
            if (stripos($item['libelle'], $searchTerm) !== false) {
                // On ajoute le libellé seulement s'il est différent de la référence
                if ($item['libelle'] !== $item['reference']) {
                    $jsonResults[] = $item['libelle'];
                }
            }
        }
        
        // Fusionner avec les résultats existants
        $results = array_merge($results, $jsonResults);
    }
    
    // Nettoyage supplémentaire des espaces et suppression des doublons
    $results = array_map('trim', $results);
    $results = array_unique($results);
    $results = array_filter($results);
    
    // Limiter à 10 suggestions maximum
    $results = array_slice($results, 0, 10);
    
    exit(json_encode(array_values($results)));

} catch (PDOException $e) {
    error_log("Erreur recherche: " . $e->getMessage());
    exit(json_encode([]));
}