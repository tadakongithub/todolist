<?php
function getDB () {
    $dsn = 'mysql:dbname=to_do_app; host=localhost; charset=utf8';
    $usr = 'mamp';
    $passwd = 'tadashi';
    try {
        $db = new PDO ($dsn, $usr, $passwd);
    } catch (PDOException $e){
        echo 'Connection Error:'.$e->getMessage();
    }
    return $db;
}
?>