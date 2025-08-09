<?php
    require_once('../database.php');
    $id = $_GET['id'];
    $sql = $pdo->prepare('DELETE FROM voiture WHERE id_voiture=?');
    $sql->execute([$id]);
    header('location:../voiture.php');
?>