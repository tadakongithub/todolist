<?php
//including code for connecting to database
require_once 'dbconnect.php';
$db = getDB();

//pulling all records (all the notes) from user's table depending on genre
$notes_work = $db->query('SELECT * FROM test_user WHERE genre="work"');//I need to change the name of the table according to each user
$notes_private = $db->query('SELECT * FROM test_user WHERE genre="private"');
$notes_others = $db->query('SELECT * FROM test_user WHERE genre="others"');

//getting how many notes there are in each genre
$counts_work = $db->query('SELECT COUNT(*) AS count FROM test_user WHERE genre="work"');
$count_work = $counts_work->fetch();
$total_work = $count_work['count'];
$counts_private = $db->query('SELECT COUNT(*) AS count FROM test_user WHERE genre="private"');
$count_private = $counts_private->fetch();
$total_private = $count_private['count'];
$counts_others = $db->query('SELECT COUNT(*) AS count FROM test_user WHERE genre="others"');
$count_others = $counts_others->fetch();
$total_others = $count_others['count'];

//the number of genre that has the most notes
$max = max($total_work, $total_private, $total_others);

//if submitted
if ($_POST['delete']) {

    //$deletesには消去したくて選んだメモたちのidが入っている（配列）。
    $deletes = $_POST['delete'];
    
    //消去したくて選んだメモを１つずつ取り出して、キー（数字）と値（実際のメモのid）に分ける。→一個ずつSQLのDELETE文で消去
    while (list($key, $val) = @each($deletes)) {
        $statement = $db->prepare('DELETE FROM test_user WHERE id=?');
        $statement->bindParam(1, $val, PDO::PARAM_INT);
        $statement->execute();
    }

    header('Location: index.php');
}


if ($_POST['pri_change']) {

    $statement = $db->prepare('UPDATE test_user SET priority=:priority WHERE id=:value');
    $statement->bindParam(':priority', $_POST['pri_change'], PDO::PARAM_STR);
    $statement->bindValue(':value', $_POST['pri_id'], PDO::PARAM_INT);
    $statement->execute();

    header('Location: index.php');
}
?>

<html>

<head>
    <title>To Do App - Note</title>
    <meta charset="UTF-8">
    <meta name="description" content="To Do List">
    <meta name="keywords" content="to do list, notes, what to do">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="js/main.js"></script>

<!-- editable links -->


<link href="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/js/bootstrap-editable.js"></script>

    <link rel="stylesheet" href="style.css">
</head>

<body class="note_body">

<!-- three genres at the top-->
<div id="genre_container">
    <a class="each_genre genre_work">Work</a>
    <a class="each_genre genre_private">Private</a>
    <a class="each_genre genre_others">Others</a>
</div>


<!-- plus and minus mark -->
<div id="plus_minus_container">
    <a href="#" id="minus_wrapper"><img src="img/minus.png" alt=""><span id="back">Back</span></a>
    <a href="create.php" id="plus_wrapper"><img src="img/plus.png" alt=""></a>
</div>


<!-- table-->
<form class="note_form" action="" method="post">

<!-- setting the height of the table container based on how many notes the genre 
that has the most notes has-->
<div class="table_container" style="height:<?php echo 60*$max.'px';?>">

<div class="table_wrap" style="height:<?php echo 60*$max.'px';?>">
<?php for ($i=1;$i<=3;$i++) :?>
<?php switch($i){
    case 1:
    $table_id = "table_work";
    $notes = $notes_work;
    break;

    case 2:
    $table_id = "table_private";
    $notes = $notes_private;
    break;

    case 3:
    $table_id = "table_others";
    $notes = $notes_others;
    break;
}
?>
<div id="<?php echo $table_id ;?>"><!-- each time table is repeated, the id of the table differs -->
<table>
<tbody id="note_data">
<?php while ($note = $notes->fetch()):?>
<tr>
    <td class="table_delete">
        <input type="checkbox" name="delete[]" value="<?php echo $note['id'];?>">
    </td>

    <td class="table_what" contenteditable="true" onBlur="saveToDatabase(this, 'what', '<?php echo $note['id'];?>')">
        <?php echo $note['what'];?>
    </td>

    <script>
    function saveToDatabase(editableObj, column, id) {
        $.ajax({
            url: "saveedit.php",
            type: "POST",
            data: 'editval='+editableObj.innerText+'&column='+column+'&id='+id	
        });
    }
    </script>

    <td class="table_deadline" data-toggle="modal" data-target="#deadlineModal">
        <span id="<?php echo 'gauge_'.$note['id'];?>" class="gauge" style="background-position: 
        <?php 
        //change the color of the bar depending on how close it is to each note's deadline

        $timestamp_note = strtotime($note['deadline']);//timestamp of the note
        $timestamp_today = time();//timestamp of today
        $difference = $timestamp_note - $timestamp_today;//difference between deadline and now
        $oneday = 60*60*24;

        
        if ($difference > $oneday*10) {
            echo '100% bottom';
        } elseif ($difference <= $oneday*10 && $difference > $oneday*9) {
            echo '90% bottom';
        } elseif ($difference <= $oneday*9 && $difference > $oneday*8) {
            echo '80% bottom';
        } elseif ($difference <= $oneday*8 && $difference > $oneday*7) {
            echo '70% bottom';
        } elseif ($difference <= $oneday*7 && $difference > $oneday*6) {
            echo '60% bottom';
        } elseif ($difference <= $oneday*6 && $difference > $oneday*5) {
            echo '50% bottom';
        } elseif ($difference <= $oneday*5 && $difference > $oneday*4) {
            echo '40% bottom';
        } elseif ($difference <= $oneday*4 && $difference > $oneday*3) {
            echo '30% bottom';
        } elseif ($difference <= $oneday*3 && $difference > $oneday*2) {
            echo '20% bottom';
        } elseif ($difference <= $oneday*2 && $difference > $oneday) {
            echo '10% bottom';
        } else {
            echo '0% bottom';
        }
        ?>
        "></span>

    </td>

    <div class="modal" tabindex="-1" role="dialog" id="deadlineModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="post">
            <label for="deadline">Change Deadline to:</label>
            <input id="deadline" type="date" name="deadline" value="<?php echo date('Y-m-d');?>">
            <input id="dl_id" type="hidden" name="dl_id" value="<?php echo $note['id'];?>">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>



    <td class="table_priority" data-name="priority" 
    data-type="select" data-pk="<?php echo $note['id'];?>"
    style="background-color:
    <?php
    //change color depending on the priority
    switch ($note['priority']) {
        case 'high':
        echo 'rgb(255, 68, 93)';
        break;

        case 'middle':
        echo 'rgb(255, 171, 74)';
        break;

        case 'low':
        echo 'rgb(70, 247, 70)';
        break;
    }
    ?>
    ">
        <?php echo $note['priority'];?>

    <form action="" method="post" class="pri_form">
        <select name="pri_change" class="pri_change">
            <option value="high">High</option>
            <option value="middle">Middle</option>
            <option value="low">Low</option>
        </select>
        <input type="hidden" name="pri_id" value="<?php echo $note['id'];?>">
        <input type="submit" value="Go" class="pri_submit">
        <button class="pri_btn">&#10005;</button>
    </form>
    </td>

    

</tr>

<?php endwhile ;?>

</tbody>

</table>

</div>

<?php endfor ;?>

</div>

</div>

<!-- delete button -->
<input class="delete_selected" type="submit" value="Delete">

</form>

<script>
    $(document).ready(function(){
        $(".btn-primary").on("click", function(){
            var deadline = $("#deadline").val();
            var id = $("#dl_id").val();
            $.ajax({
                url: "update_deadline.php",
                type: "POST",
                data: {
                    'dl_change': deadline,
                    'dl_id': id
                }
            });
            var new_deadline_timestamp = new Date(deadline).getTime() / 1000;
            var today = Date.now();
            var difference = new_deadline_timestamp - today;
            var oneday = 60*60*24;
            var backPos;
            if(difference >= oneday*10){
                backPos = '100%';
            } else if(difference < oneday*10 && difference >=oneday*9){
                backPos = '90%';
            } else if(difference < oneday*9 && difference >=oneday*8){
                backPos = '80%';
                    } else if(difference < oneday*8 && difference >= oneday*7){
                        backPos = '70%';
                    } else if(difference < oneday*7 && difference >= oneday*6){
                        backPos = '60%';
                    } else if(difference < oneday*6 && difference >= oneday*5){
                        backPos = '50%';
                    } else if(difference < oneday*5 && difference >= oneday*4){
                        backPos = '40%';
                    } else if(difference < oneday*4 && difference >= oneday*3){
                        backPos = '30%';
                    } else if(difference < oneday*3 && difference >= oneday*2){
                        backPos = '20%';
                    } else if(difference < oneday*2 && difference >= oneday){
                        backPos = '10%';
                    } else if(difference < oneday) {
backPos = '0%';
                    }
                    $("#gauge_"+id).css({
                        "backgroundPosition": backPos + ' bottom'
                    });
                    $("#deadlineModal").modal('toggle');
        });
    });
</script>

</body>

</html>