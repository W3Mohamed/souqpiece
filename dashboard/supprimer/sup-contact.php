<?php
    require_once('../database.php');
    $id = $_GET['id'];
    $sql = $pdo->prepare('DELETE FROM contact WHERE id_contact = ?');
    $sql->execute([$id]);
    header('location:../index.php');
?>