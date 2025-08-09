<?php
    require_once('../database.php');
    $id = $_GET['id'];

    $requete = $pdo->prepare('DELETE FROM sous_categorie WHERE id_categorie=?');
    $requete->execute([$id]);

    $sql = $pdo->prepare('DELETE FROM categorie WHERE id_categorie=?');
    $sql->execute([$id]);
    header('location:../categorie.php');
?>