<?php
    require_once('../database.php');
    $id = $_GET['id'];

    $requete = $pdo->prepare('DELETE FROM voiture WHERE id_marque=?');
    $requete->execute([$id]);

    $sql = $pdo->prepare('DELETE FROM marque WHERE id_marque=?');
    $sql->execute([$id]);
    header('location:../marque.php');
?>