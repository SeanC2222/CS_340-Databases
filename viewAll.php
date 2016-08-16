<?php 
ini_set('display_errors', 'On');

$sql = mysqli_connect("oniddb.cws.oregonstate.edu", "mulholls-db", "SUVJuqcb6v7wtq6k", "mulholls-db");


$input = file_get_contents('php://input');
$json = json_decode($input, true);

if($json['action'] == "vie"){
   viewTable($json['table'], $sql);
} else {
   echo $input;
   echo ("Bad Query");
}

function viewTable($table, $sql){
   $query = "SELECT * FROM ".$table;
   $res = mysqli_query($sql, $query);
   $all = mysqli_fetch_all($res, MYSQLI_ASSOC);
   echo json_encode($all);
}

?>
