<?php
//including code for connecting to database
require_once 'dbconnect.php';
$db = getDB();

if ($_POST) {
    $add = $db->prepare('INSERT INTO test_user SET what=?, deadline=?, priority=?, genre=?');
    $add->execute(array($_POST['what'], $_POST['deadline'], $_POST['priority'], $_POST['genre']));

    header('Location: index.php');
}
?>

<?php
$today = date('Y-m-d');//this year
$_10 = date('Y-m-d', time()+60*60*24*10);//in 10 days
?>

<html>

<head>
    <title>To Do App - Create</title>
    <link rel="stylesheet" href="style.scss">
    <meta charset="UTF-8">
    <meta name="description" content="To Do List">
    <meta name="keywords" content="to do list, notes, what to do">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>


<body class="create_body">

<!-- form to create a new note -->
    <form action="" method="post" class="create_form">
        
    <!-- notes -->
        <label for="what" class="create_section">What</label>
        <input type="text" name="what" id="what" minlength="1" maxlength="80" required>

    <!-- deadline -->
        <p class="create_section">Should Be Done By</p>
        <input type="date" name="deadline" class="deadline" value="<?php echo $today;?>" 
        min="<?php echo $today;?>"  required="required">
        
    <!-- priority -->
        <p class="create_section">Priority</p>
        <input type="radio" name="priority" value="high" id="high"><label for="high" class="priority_label high">High</label>
        <input type="radio" name="priority" value="middle" id="middle"><label for="middle" class="priority_label middle">Middle</label>
        <input type="radio" name="priority" value="low" id="low"><label for="low" class="priority_label low">Low</label>

    <!-- genre -->
    <p class="create_section">Genre</p>
        <input type="radio" name="genre" value="work" id="work"><label for="work" class="priority_label work">Work</label>
        <input type="radio" name="genre" value="private" id="private"><label for="private" class="priority_label private">Private</label>
        <input type="radio" name="genre" value="others" id="others"><label for="others" class="priority_label others">Others</label>

    <!-- submit -->
        <input type="submit" value="Add" id="create_add">
    </form>

</body>
</html>