<?php
    require_once('dashboard/database.php');
    $id = $_GET['id'];
    $sql = $pdo->prepare('DELETE FROM panier WHERE id_produit=?');
    $sql->execute([$id]);
    header('location:panier.php');
?>