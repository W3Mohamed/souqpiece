<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js"></script>
    <title>Souq piece</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300&family=Oswald&family=Pacifico&family=Roboto&family=Roboto+Slab:wght@300&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/abbd21db44.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css" rel="stylesheet">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>

    <!--============================================================
                    header
    =============================================================-->
    <div class="hero-section">
        <?php 
            require_once('dashboard/database.php');
            include('partie/navbar.php');
            $sqlStates = $pdo->prepare('SELECT * FROM setting WHERE id=?');
            $sqlStates->execute([1]);
            $setting = $sqlStates->fetch(PDO::FETCH_ASSOC);
        ?>
        <div class="hero-content-wrapper">
        <div class="hero-content">
            <h1>Souqpièces Algérie | Votre spécialiste en pièces détachées automobiles</h1>
            <p class="hero-subtitle">Trouvez la pièce qu'il vous faut parmi notre large catalogue de <strong>pièces détachées voiture</strong> pour toutes marques en Algérie. Livraison rapide et prix compétitifs.</p>
            
            <div class="hero-search">
                <form action="product.php" method="GET" id="searchForm2">
                    <div class="search-container search-autocomplete">
                        <input 
                            type="text" 
                            name="query" 
                            id="searchInput2"
                            placeholder="Rechercher une référence, une pièce auto..." 
                            required 
                            class="search-input"
                            aria-label="Recherche de pièces détachées"
                            autocomplete="off"
                        />
                        <div class="suggestions-box"></div>
                        <button type="submit" class="search-button">
                            <span>Rechercher</span>
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="hero-keywords">
                <span>Pièces détachées Alger</span>
                <span>Pièces auto pas cher</span>
                <span>Réparation voiture</span>
                <span>Vente pièces automobiles</span>
            </div>
        </div>
        </div>
    </div>
    <style>
        .hero-section{
            /*background-image: linear-gradient(rgba(40, 40, 49, 0.5),rgba(58, 72, 73, 0.4),rgba(0, 0, 0, 0.6)), url(../img/<?=$setting['image']?>) ;*/
             background: 
                    /* Couche rouge en bas */
                    linear-gradient(to top, rgba(225, 41, 41, 0.4) 0%, transparent 30%),
                    /* Noir semi-transparent centré */
                    linear-gradient(rgba(20, 20, 20, 0.7), rgba(20, 20, 20, 0.7)),
                    /* Image originale */ 
                    url(../img/<?=$setting['image']?>) center/cover no-repeat;
        }
    </style>
    <!--============================================================
                    brand
    =============================================================-->
    <div class="brand" id="brand">
        <div class="titre"><h1>Quelle est la <span>marque</span> de votre voiture</h1></div>
    <!--    <div class="gallery-wrap">
            <i class="fa-solid fa-chevron-left backBtn" id="backBtn"></i>
            <i class="fa-solid fa-chevron-right nextBtn" id="nextBtn"></i>
            <div class="brand-img">
                <?php
                    $sql = 'SELECT * FROM marque';
                    $query = $pdo->query($sql);
                    $brands = $query->fetchAll();
                    foreach($brands as $brand){
                ?>
                <a href="voiture.php?id=<?=$brand['id_marque']?>"><img src="img/logo/<?=$brand['logo']?>" alt="<?=$brand['libelle']?>"></a>
                <?php } ?>
            </div>
        </div>-->
        <section class="splide splide2" aria-label="Splide Basic HTML Example">
            <div class="splide__track">
                    <ul class="splide__list">
                        <?php
                            $sql = 'SELECT * FROM marque';
                            $query = $pdo->query($sql);
                            $brands = $query->fetchAll();
                            foreach($brands as $brand){
                        ?>
                        <li class="splide__slide">
                            <a href="voiture.php?id=<?=$brand['id_marque']?>"><img src="img/logo/<?=$brand['logo']?>" loading="lazy"></a>
                        </li>

                        <?php } ?>
                    </ul>
            </div>
        </section>


    </div>
    <!--============================================================
                    Categorie
    =============================================================-->
    <div class="section-categorie" id="section-categories">
        <div class="titre"><h1>Découvré nos <span>categorie</span></h1></div>
        <section class="splide splide1" aria-label="Splide Basic HTML Example">
            <div class="splide__track">
                    <ul class="splide__list">
                        <?php
                            $sqlStates = $pdo->prepare('SELECT * FROM categorie');
                            $sqlStates->execute();
                            $categories = $sqlStates->fetchAll(PDO::FETCH_ASSOC);
                            foreach($categories as $categorie){
                        ?>
                        <li class="splide__slide">
                            <a href="product.php?id_categorie=<?=$categorie['id_categorie']?>">
                            <div class="categorie">
                                <img src="img/categories/<?= $categorie['img']?>" alt="<?=$categorie['libelle']?>" loading="lazy">
                                <h3><?= $categorie['libelle']?></h3>
                                <h4><?= $categorie['arabe']?></h4>
                                <!--    <a href="#">Voir plus</a> -->
                            </div>
                            </a>
                        </li>

                        <?php } ?>
                    </ul>
            </div>
        </section>
    </div>
    <!--============================================================
                  Produit
    =============================================================-->
    <div class="section-produit" id="section-produits">
        <div class="text">
            <h2>Decouvré nos produit</h2>
        </div>
        <?php
            foreach($categories as $categorie){
                $sqlProduit = $pdo->prepare('SELECT * FROM produit WHERE id_categorie=? AND trie > 0 ORDER BY trie LIMIT 6');
                $sqlProduit->execute([$categorie['id_categorie']]);
                $produits = $sqlProduit->fetchAll(PDO::FETCH_ASSOC);
                if(!empty($produits)){
        ?>
        <div class="produit-container">
            <h3><?=$categorie['libelle']?></h3>
            <h3 class="categorie-ar"><?= $categorie['arabe']?></h3>
            <div class="produits">
            <?php    
                foreach($produits as $produit){  
                    
                    $sqlVoit = $pdo->prepare('SELECT id_voiture FROM pvd WHERE id_produit=?');
                    $sqlVoit->execute([$produit['id_produit']]);
                    $id_voiture = $sqlVoit->fetchColumn();

                    $sqlModele = $pdo->prepare('SELECT libelle FROM marque WHERE id_marque= (SELECT id_marque FROM voiture WHERE id_voiture=?)');
                    $sqlModele->execute([$id_voiture]);
                    $marque = $sqlModele->fetchColumn();  
                    
                    $sqlMarque = $pdo->prepare('SELECT modele FROM voiture WHERE id_voiture=?');
                    $sqlMarque->execute([$id_voiture]);
                    $modele = $sqlMarque->fetchColumn(); 
                    
                    $sqlCate = $pdo->prepare('SELECT libelle FROM categorie WHERE id_categorie=?');
                    $sqlCate->execute([$produit['id_categorie']]);
                    $categorie = $sqlCate->fetchColumn();
            ?>
                <div class="produit">
                    <?php
                        if($produit['stock'] == 0){
                    ?>
                    <h3 class="stock">non disponible</h3><?php } ?>
                    <a href="produit.php?id=<?=$produit['id_produit']?>&id_voiture=<?=$id_voiture?>">
                        <img src="img/produit/<?= $produit['img1']?>" alt="<?=$produit['libelle']?> loading="lazy"">
                        <h2><?= $produit['libelle']?></h2>
                        <h4><?= $marque?></h4>
                        <h4 style="margin-bottom:10px;font-size:10px"><?= $modele?></h4>
                        <h4><?=$categorie?></h4>
                        <h2 id="prix"><?=$produit['prix']?> DA</h2>
                        <i class="fa-solid fa-cart-shopping" id="ajouter-panier"></i>
                    </a>
                </div>
                <?php } ?>
            </div>
        </div>
        <?php } 
        } ?>
    </div>
    <!--============================================================
                  Boutique
    =============================================================-->
    <div class="section-boutique">
        <div class="titre"><h1>A propos de notre <span>boutique</span></h1></div>
        <div class="container">
            <div class="item">
                <i class="fa-solid fa-truck"></i>
                <h3>Livraison 58 Wilayas</h3>
            </div>
            <div class="item">
                <i class="fa-solid fa-sack-dollar"></i>
                <h3>Paiement a la livraison</h3>
            </div>
            <div class="item">
                <i class="fa-solid fa-award"></i>
                <h3>Garantie sur nos produit</h3>
            </div>
        </div>
    </div>
    <!--====================================================
                        contect
    =======================================================-->
    <div class="contact" id="contact">
        <div class="info">
            <h3>Contactez nous</h3>
            <form method="POST">
                <input type="text" name="nom" placeholder="Nom" required>
                <input type="tel" name="tel" placeholder="N° Téléphone" required>
                <input type="email" name="email" placeholder="Email">
                <textarea name="message" id="message" placeholder="Message"></textarea>
                <input type="submit" name="envoyer" value="Envoyer">
            </form>
            <?php
                if(isset($_POST['envoyer'])){
                    $nom = $_POST['nom'];
                    $tel = $_POST['tel'];
                    $email = $_POST['email'];
                    $message = $_POST['message'];

                    $sqlContact = $pdo->prepare('INSERT INTO contact(nom,tel,email,message) VALUES (?,?,?,?)');
                    $sqlContact->execute([$nom,$tel,$email,$message]);
                    ?>
                    <script> swal("Merci pour votre message", "Merci pour votre intérêt. Nous vous répondrons rapidement.", "success"); </script> <?php
                }
            ?>
        </div>
        <div class="info">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d819257.418681153!2d2.3120521960949625!3d36.66910790419517!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x128e5320fbaba479%3A0xb98fd2a68cc6734b!2sEts%20Ben%20Amar%20Pieces%20Japonaise!5e0!3m2!1sfr!2sdz!4v1733172161383!5m2!1sfr!2sdz"
                width="100%" 
                height="100%" 
                style="border:0;"
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
    <!--=========================================================
                        footer
    ========================================================-->
    <?php include('partie/footer.php') ?>
    <script>
        document.addEventListener( 'DOMContentLoaded', function() {
            var splide = new Splide( '.splide1', {
                perPage: 3,
                focus  : 0,
                omitEnd: true,
                breakpoints: {
                    1000: {
                        perPage: 2, // 2 slides visibles sur les petits écrans
                    },
                    600: {
                        perPage: 1, // 1 slide visible sur les très petits écrans
                    },
                },
            } );
            splide.mount();
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var splide = new Splide('.splide2', {
                perPage: 6, // Par défaut, 6 éléments par page
                focus  : 0,
                omitEnd: true,
                breakpoints: { // Gestion responsive
                    900: { // Pour écrans <= 1200px
                        perPage: 4,
                    },
                    782: { // Pour écrans <= 992px
                        perPage: 3,
                    },
                    558: { // Pour écrans <= 768px
                        perPage: 2,
                    },
                },
            });
            splide.mount(); // Monter le Splide après configuration
        });
    </script>

</body>
</html>