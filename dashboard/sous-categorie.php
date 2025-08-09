<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300&family=Oswald&family=Pacifico&family=Roboto&family=Roboto+Slab:wght@300&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/abbd21db44.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php 
        session_start();
        if(!isset( $_SESSION['utilisateur'])){
            header('location:connexion.php');
            exit;
        }
        require_once('database.php');
        include('include/menu.php');
     ?>

    
    <div class="site">
        <div class="barre">Sous categorie</div>
        <!--=============Produit=======================-->
        <div class="page-voiture">
        <?php
            if(isset($_GET['id'])){ 
                $id = $_GET['id'];
                $sqlCategorie = $pdo->prepare('SELECT * FROM sous_categorie WHERE id_sous_categorie=?');
                $sqlCategorie->execute([$id]);
                $categories = $sqlCategorie->fetch(PDO::FETCH_ASSOC); 
            ?>
                <h2>Modifier une sous categorie</h2>
                <form method="POST" enctype="multipart/form-data" >
                    <label for="sous_categorie">Sous categorie</label>
                    <input type="text" name="sous_categorie" placeholder="plaquette de frein ...ext" value="<?=$categories['libelle']?>">
                    <select name="categorie">
                        <?php
                            $sqlCategorie = 'SELECT * FROM categorie';
                            $query = $pdo->query($sqlCategorie);
                            $rows = $query->fetchAll();
                            foreach($rows as $row){
                                $selected = ($row['id_categorie'] == $categories['id_categorie']) ? 'selected' : '';
                        ?>
                        <option value="<?= $row['id_categorie'] ?>"<?=$selected?> ><?= $row['libelle'] ?></option>
                        <?php } ?>
                    </select>
                    <label for="image">image</label>
                    <input type="file" name="img">
                    <input type="submit" value="modifier" name="modifier">
                </form> <?php
            }else{
        ?>
        <h2>Ajouter une sous categorie</h2>
            <form method="POST" enctype="multipart/form-data" >
                <label for="sous_categorie">Sous categorie</label>
                <input type="text" name="sous_categorie" placeholder="plaquette de frein ...ext">
                <select name="categorie">
                    <?php
                        $sqlCategorie = 'SELECT * FROM categorie';
                        $query = $pdo->query($sqlCategorie);
                        $rows = $query->fetchAll();
                        foreach($rows as $row){
                    ?>
                    <option value="<?= $row['libelle'] ?>"><?= $row['libelle'] ?></option>
                    <?php } ?>
                </select>
                <label for="image">image</label>
                <input type="file" name="img">
                <input type="submit" value="ajouter" name="ajouter">
            </form>
            
            <?php }
                if(isset($_POST['ajouter'])){
                    $sous = $_POST['sous_categorie'];
                    $categorie = $_POST['categorie'];
                    $requete = $pdo->prepare('SELECT id_categorie FROM categorie WHERE libelle=?');
                    $requete->execute([$categorie]);
                    $idCategorie = $requete->fetchColumn();
                    /*========upload img=========*/
                    function uploadImage($inputName) {
                        $file = $_FILES[$inputName];
                        $fileName = $file['name'];
                        $fileTmpName = $file['tmp_name'];
                        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                        $uniqueName = uniqid('', true) . '.' . $fileExt;
                        $fileDestination = '../img/categories/sous_categorie/' . $uniqueName;
                        move_uploaded_file($fileTmpName, $fileDestination);
                        return $uniqueName;
                    }
                    $img = uploadImage('img');
                    if(!empty($sous)){
                        $sqlState = $pdo->prepare('INSERT INTO sous_categorie VALUES(null,?,?,?)');
                        $sqlState->execute([$idCategorie,$sous,$img]);
                        echo 'insertion avec succes !';
                    }
                    else{
                        ?>
                        <div class="erreur">
                                <p>veuillez saiser les informations</p>
                            </div>   
                        <?php
                    }   
                }
                if(isset($_POST['modifier'])){
                    $sous = $_POST['sous_categorie'];
                    $categorie = $_POST['categorie'];
                    // ===========image php========
                    function uploadImage($inputName) {
                        $file = $_FILES[$inputName];
                        $fileName = $file['name'];
                        $fileTmpName = $file['tmp_name'];
                        // Si le fichier est vide, retourne une chaîne vide
                        if (empty($fileName)) {
                            return '';
                        }
                        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                        $uniqueName = uniqid('', true) . '.' . $fileExt;
                        $fileDestination = '../img/categories/sous_categorie/' . $uniqueName;
                        move_uploaded_file($fileTmpName, $fileDestination);
                        return $uniqueName;
                    }
                    $img = uploadImage('img');
                    $sqlModifie = 'UPDATE sous_categorie SET libelle=?, id_categorie=?';
                    $params = [$sous,$categorie];
                    
                    if(!empty($img)){
                        $sqlModifie .= ', img=?';
                        $params[] = $img;
                    }
                    // Ajouter la clause WHERE
                    $sqlModifie .= ' WHERE id_sous_categorie=?';
                    $params[] = $id;

                    $Modifie = $pdo->prepare($sqlModifie);
                    $updated = $Modifie->execute($params);
                    if($updated){
                        header('location:sous-categorie.php');
                    }
                    else{
                        echo "ERROR";
                    }
                }
            ?>
            <h2>Liste des sous categorie</h2>
            <div class="filtre">
                <h4>filtrer selon la categorie</h4>
                <form method="POST">
                    <select name="libCategorie">
                        <option value="tous">Tous</option>
                    <?php
                        foreach($rows as $row){
                            $selected = ($_POST['categorie'] == $row['libelle']) ? 'selected' : '';
                    ?>
                        <option value="<?= $row['libelle'] ?>"<?= $selected ?>><?= $row['libelle'] ?></option>
                    <?php } ?>
                    </select>
                    <input type="submit" value="filtrer" name="filtrer">
                </form>
            </div>
            <table class="afficher-marque">
                <thead>
                    <tr>
                        <th>image</th>
                        <th>Sous categorie</th>
                        <th>Categorie</th>
                        <th>Modifier</th>
                        <th>Supprimer</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        
                        if(isset($_POST['filtrer'])){
                            $cate = $_POST['libCategorie'];
                            if($cate === 'tous'){
                                $sqlSous = $pdo->prepare('SELECT * FROM sous_categorie');
                                $sqlSous->execute();
                                $sous = $sqlSous->fetchAll(PDO::FETCH_ASSOC);
                            }
                            else{
                                $idCate = $pdo->prepare('SELECT id_categorie FROM categorie WHERE libelle=?');
                                $idCate->execute([$cate]);
                                $idC = $idCate->fetchColumn();

                                $sqlSous = $pdo->prepare('SELECT * FROM sous_categorie WHERE id_categorie = ?');
                                $sqlSous->execute([$idC]);
                                $sous = $sqlSous->fetchAll(PDO::FETCH_ASSOC);
                            }
                        }else{
                            $sqlSous = $pdo->prepare('SELECT * FROM sous_categorie');
                            $sqlSous->execute();
                            $sous = $sqlSous->fetchAll(PDO::FETCH_ASSOC);
                        }
                        foreach($sous as $sou){
                    ?>
                    <tr>
                        <td><img src="../img/categories/sous_categorie/<?= $sou['img'] ?>"></td>
                        <td><?= $sou['libelle'] ?></td>
                        <?php
                            $requeteCate = $pdo->prepare('SELECT categorie.libelle FROM categorie JOIN sous_categorie ON categorie.id_categorie = ? ');
                            $requeteCate->execute([$sou['id_categorie']]);
                            $libelle = $requeteCate->fetchColumn();
                        ?>
                        <td><span class="spanBlack"><?= $libelle ?></span></td>
                        <td><a href="sous-categorie.php?id=<?=$sou['id_sous_categorie']?>" class="btn-mod">Mod</a></td>
                        <td><a href="supprimer/sup-sous.php?id=<?=$sou['id_sous_categorie']?>" class="btn-sup">Sup</a></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html> 