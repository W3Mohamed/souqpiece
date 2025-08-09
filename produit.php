<!DOCTYPE html>
<html lang="en">
    <?php
        require_once('dashboard/database.php');

        $id = $_GET['id'];
        $from_json = isset($_GET['from_json']) ? $_GET['from_json'] : 0;

        if ($from_json) {
            // Charger les données depuis le fichier JSON
            $jsonFile = 'dashboard/data/stock.json'; 
            if (file_exists($jsonFile)) {
                $jsonData = json_decode(file_get_contents($jsonFile), true);
                $produit = null;
                
                // Rechercher le produit dans le JSON
                foreach ($jsonData as $item) {
                    if ($item['reference'] == $id) {
                        $produit = $item;
                        break;
                    }
                }
                
                if (!$produit) {
                    die("Produit non trouvé");
                }
                // Formater les données comme votre structure de base de données
                // $produit['libelle'] = $produit['reference'];
                $produit['img1'] = 'aucune.png';
                $produit['stock'] = $produit['stock'] ?? 0;
                $produit['prix'] = $produit['prix'];
            } else {
                die("Fichier JSON non trouvé");
            }
        } else {
            // Charger depuis la base de données comme avant
            $sqlStates = $pdo->prepare('SELECT * FROM produit WHERE id_produit=?');
            $sqlStates->execute([$id]);
            $produit = $sqlStates->fetch(PDO::FETCH_ASSOC);
        }
        
        if (!$produit) {
            die("Produit non trouvé");
        }
    ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$produit['libelle']?></title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js"></script>
    <script src="js/produit.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300&family=Oswald&family=Pacifico&family=Roboto&family=Roboto+Slab:wght@300&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/abbd21db44.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css" rel="stylesheet">
</head>

<body>
    <!--=============== navbar=================-->
    <?php 
        include('partie/navbar.php');
        $id_voiture = $_GET['id_voiture'] ?? null;
        
        if ($from_json) {
            // Pour les produits JSON, on affiche des informations simplifiées
            $aff = [
                'sous' => 'Pièce détachée',
                'modele' => htmlspecialchars($produit['marque'])
            ];
            $desc = ['description' => 'Produit de référence externe'];
        } else {
            // Pour les produits de la base de données, on garde la logique existante
            $sqlAff = $pdo->prepare('SELECT s.libelle as sous , v.modele as modele
             FROM categorie as c , sous_categorie as s , marque as m , voiture as v 
             WHERE s.id_sous_categorie=? AND c.id_categorie=s.id_categorie AND v.id_voiture=? AND m.id_marque=v.id_marque');
            $sqlAff->execute([$produit['id_sous_categorie'],$id_voiture]);
            $aff = $sqlAff->fetch();
            
            $sqlDesc = $pdo->prepare('SELECT description FROM pvd WHERE id_produit=? AND id_voiture=?');
            $sqlDesc->execute([$id,$id_voiture]);
            $desc = $sqlDesc->fetch();
        }
     ?>  

    <!--=====================================
            detail
    ==========================================--> 
    <div class="detail">
        <div class="container">
            <div class="item">
                <div class="img-principale">
                    <img src="img/produit/<?=$produit['img1']?>" alt="<?=$produit['libelle']?>" loading="lazy">
                </div>
                <div class="gellery">
                    <i class="fa-solid fa-chevron-left" id="leftBtn"></i>
                    <i class="fa-solid fa-chevron-right" id="rightBtn"></i>
                    <div class="second-img">
                        <img src="img/produit/<?=$produit['img1']?>" alt="<?=$produit['libelle']?>">
                        <?php if (!$from_json): ?>
                            <?php for($i = 2; $i <= 10 ; $i++): ?>
                                <?php if(!empty($produit['img' .$i])): ?>
                                    <img src="img/produit/<?=$produit['img'. $i]?>" alt="<?=$produit['libelle']?>" loading="lazy">
                                <?php endif; ?>
                            <?php endfor; ?>
                        <?php endif; ?>
                    </div>                
                </div>
            </div>
            <div class="item">
                <h1><?=$produit['libelle']?></h1>
                <?php if($produit['stock'] == 0): ?>
                <h3 id="dispo">Non disponible</h3><?php endif; ?>
                <h4><?=$aff['sous']?></h4>
                <h5><?=$aff['modele']?></h5>
                
                <?php if ($from_json): ?>
                    <!--<h4 style="margin-bottom:10px;font-size:10px"><?=$produit['reference']?></h4>-->
                <?php endif; ?>

                <div class="rating">
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                </div>
                <h3 class="prix"><?=$produit['prix']?> DA</h3>
                <h3>Information sur le produit :</h3>
                <p><?=$desc['description']?></p>

                 <form method="POST" action="panier.php">
                    <?php if ($from_json): ?>
                        <!-- Pour les produits JSON, on utilise la référence comme identifiant -->
                        <input type="hidden" name="produit_json" value="<?=htmlspecialchars($produit['reference'])?>">
                        <input type="hidden" name="from_json" value="1">
                    <?php else: ?>
                        <!-- Pour les produits de la BDD, on garde l'ID normal -->
                        <input type="hidden" name="produit" value="<?=htmlspecialchars($id)?>">
                        <input type="hidden" name="id_voiture" value="<?=htmlspecialchars($id_voiture)?>">
                    <?php endif; ?>
                    
                    <input type="number" name="quantite" value="1" min="1" <?= $produit['stock'] == 0 ? 'disabled' : '' ?>>
                    <input type="submit" name="add_cart" value="Ajouter au panier" <?= $produit['stock'] == 0 ? 'disabled' : '' ?>>
                    <input type="submit" name="acheter" value="Acheter" id="acheter" <?= $produit['stock'] == 0 ? 'disabled' : '' ?>>
                </form>

                <?php if(isset($_GET['added'])): ?>
                    <script>
                        swal({
                            title: "Insertion avec succes!",
                            text: "Le produit <?=$produit['libelle']?> a ete bien ajoute au panier!",
                            icon: "success",
                        });
                    </script>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!--=====================================
            similaires
    ==========================================--> 
    <?php if (!$from_json): ?>
        <?php
            $id_sous_categorie = $produit['id_sous_categorie'];
            $sqlSimilaires = $pdo->prepare('
                SELECT p.*
                FROM produit AS p
                JOIN pvd AS pv ON p.id_produit = pv.id_produit
                WHERE pv.id_voiture = :id_voiture
                  AND p.id_sous_categorie = :id_sous_categorie
                  AND p.id_produit != :id_produit
                LIMIT 10
            ');
            $sqlSimilaires->execute([
                ':id_voiture' => $id_voiture,
                ':id_sous_categorie' => $id_sous_categorie,
                ':id_produit' => $id,
            ]);
            
            $produitsSimilaires = $sqlSimilaires->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <div class="similaire">
            <h3>Des produits similaires</h3>
            <section class="splide splideSem" aria-label="Splide Basic HTML Example">
                <div class="splide__track">
                        <ul class="splide__list">
                            <?php foreach($produitsSimilaires as $produitsSimilaire): ?>
                            <li class="splide__slide">
                                <div class="produit" style="width:100%">
                                    <?php if($produitsSimilaire['stock'] == 0): ?>
                                    <h3 class="stock">non disponible</h3><?php endif; ?>
                                    <a href="produit.php?id=<?=$produitsSimilaire['id_produit']?>&id_voiture=<?=$id_voiture?>">
                                        <img src="img/produit/<?= $produitsSimilaire['img1']?>" loading="lazy" alt="$produitsSimilaire['libelle']?>">
                                        <h2><?= $produitsSimilaire['libelle']?></h2>
                                        <h4><?= $aff['modele']?></h4>
                                        <h4><?=$aff['sous']?></h4>
                                        <h2 id="prix"><?=$produitsSimilaire['prix']?> DA</h2>
                                        <i class="fa-solid fa-cart-shopping" id="ajouter-panier"></i>
                                    </a>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                </div>
            </section>
        </div>
    <?php endif; ?>

    <!--=========================================================
                        footer
    ========================================================-->
    <?php include('partie/footer.php') ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var splide = new Splide('.splideSem', {
                perPage: 6,
                focus  : 0,
                omitEnd: true,
                gap : '20px',
                breakpoints: {
                    1200: {
                        perPage: 4,
                    },
                    992: {
                        perPage: 3,
                    },
                    768: {
                        perPage: 2,
                    },
                },
            });
            splide.mount();
        });
    </script>    
</body>
</html>