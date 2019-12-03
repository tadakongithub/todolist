<?php

    require 'dbconnect.php';
    $db = getDB();

    if(isset($_POST['dl_change']) && isset($_POST['dl_id'])){
        $dl_change = $_POST['dl_change'];
        $dl_id = $_POST['dl_id'];
        $stmt = $db->prepare('UPDATE test_user SET deadline = :dl_change, id = :dl_id');
        $stmt->bindParam(':dl_change', $dl_change, PDO::PARAM_STR);
        $stmt->bindParam(':dl_id', $dl_id, PDO::PARAM_INT);
        $stmt->execute();
    }
?>
