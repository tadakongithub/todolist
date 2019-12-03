<?php
/*database as a new PHP Data Object*/
require_once 'dbconnect.php';
$db = getDB();

/*updating table with edited texts*/
$statement = $db->prepare('UPDATE test_user SET '.$_POST["column"].'=? WHERE id=?');
$statement->execute(array($_POST["editval"], $_POST["id"]));



$statement2 = $db->prepare('UPDATE test_user SET '.$_POST['name'].'=? WHERE id=?');
$statement2->execute(array($_POST['value'], $_POST['pk']));

?>