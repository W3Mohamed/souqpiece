<?php
    require_once('../database.php');
    $id = $_GET['id'];
    $sql = $pdo->prepare('DELETE FROM produit WHERE id_produit=?');
    $sql->execute([$id]);
    header('location:../produit.php');
?>