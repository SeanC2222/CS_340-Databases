<?php

ini_set('display_errors', 'On');

$sql = mysqli_connect("oniddb.cws.oregonstate.edu", "mulholls-db", "SUVJuqcb6v7wtq6k", "mulholls-db");

if($_SERVER['REQUEST_METHOD'] === 'POST'){

   if($_POST['action'] == "INSERT"){

      $keys = array_keys($_POST);
      $vals = array_values($_POST);

      $nameQuery = "SELECT * FROM quest_items";
      $nameRes = mysqli_query($sql, $nameQuery);
      $namesAndIds = mysqli_fetch_all($nameRes, MYSQLI_ASSOC);

      $query = "INSERT INTO quests (";

      for($i = 0; $i < 3; $i++){
	 if($keys[$i] != "action"){ 
	    $query = $query.$keys[$i];
	    if($i != 2){
	       $query = $query.", ";
	    }
	 }
      }

      $query = $query.") VALUES (";

      for($i = 0; $i < 3; $i++){
	 if($keys[$i] != "action"){
	    $query = $query."\"".$vals[$i];
	    if($i != 2){
	       $query = $query."\", ";
	    } else {
	       $query = $query."\"";
	    }
	 }
      }

      $query = $query.");";

      mysqli_autocommit($sql, false);
      echo $query."<br>";
      $res = mysqli_query($sql, $query);

      $questId = mysqli_insert_id($sql);

      if($res && $_POST['numQuestItems'] > 0){
	 $newQuestID = "SELECT id FROM quests WHERE name=\"".$_POST['name']."\"";
	 
      
	 $numItems = $_POST['numQuestItems'];
	 $index = "questItemName";

	 $items = array();
	 for($i = 0; $i < $numItems; $i++){
	    array_push($items, $_POST[$index.$i]);
	 }
   
	 for($i = 0; $i < $numItems; $i++){
	    for($j = 0; $j < count($namesAndIds); $j++){
	       if($items[$i] == $namesAndIds[$j]['name']){
		  $items[$i] = $namesAndIds[$j]['id'];
		  break;
	       }
	    }
	 }

	 $query = "INSERT INTO quest_quest_items (qid, qiid) VALUES (";
	 for($i = 0; $i < $numItems; $i++){
	    $inquery = $query."\"".$questId."\", \"".$items[$i]."\")";
	    $res = mysqli_query($sql, $inquery);
	    echo $inquery."<br>";
	 }
      }


      if($res){
	    echo "<h2>SUCCESS!</h2><br>";
	    echo "<form action=\"questCreate.html\" style=\"display:inline\"><input type=\"submit\" value=\"Make Another Quest\"></form>&nbsp&nbsp";
	    echo "<form action=\"../projectHome.html\" style=\"display:inline\"><input type=\"submit\" value=\"Home Screen\"></form>";
	    $res = mysqli_commit($sql);
	    mysqli_autocommit($sql, true);
      } else {
	    echo "<h2> FAILURE TO ADD QUEST/QUEST ITEM RELATION, QUEST CORRUPTED </h2><br>";
	    echo "<form action=\"questCreate.html\" style=\"display:inline\"><input type=\"submit\" value=\"Make Another Quest\"></form>&nbsp&nbsp";
	    echo "<form action=\"../projectHome.html\" style=\"display:inline\"><input type=\"submit\" value=\"Home Screen\"></form>";
	    mysqli_rollback($sql);
	    mysqli_autocommit($sql, true);
      } 
   } else if ($_POST['action'] == "UPDATE"){
      $keys = array_keys($_POST);
      $vals = array_values($_POST);

      $query = "UPDATE quests SET ";

      for($i = 0; $i < count($keys); $i++){
	 if($keys[$i] != "action"){
	    $query = $query.$keys[$i]."=";

	    if($vals[$i] == ""){
	       $query = $query."null";
	    } else {
	       $query = $query."\"".$vals[$i]."\"";
	    }

	    if($i != count($keys)-1){
	       $query = $query.", ";
	    }
	 }
      }

      $query = $query." WHERE id='";
      $query = $query.$vals[1]."'";

      if(mysqli_query($sql, $query)){
	 echo $query;
	 echo "SUCCESS!";
      } else {
	 echo $query;
	 http_response_code(499);
	 echo "Failure!";
      }
     
   }

} else {

   $keys = array_keys($_GET);
   $vals = array_values($_GET);

   $query = "SELECT * FROM quests WHERE ";

   for($i = 0; $i < count($vals); $i++){
      $query = $query.$keys[$i]."=\"".$vals[$i]."\"";
      if($i != count($vals)-1){
	 $query = $query." AND ";
      }
   }

   $query = $query.";";

   if($res = mysqli_query($sql, $query)){
      $item = mysqli_fetch_assoc($res);
      $headers = array_keys($item);
      $info = array_values($item);
      echo "<head><link rel=\"stylesheet\" type=\"text/css\" href=\"../mainStyle.css\"></head>";
      echo "<div id=\"navigation\"><a href=\"../projectHome.html\">Home Screen</a></div>";
      echo "<table id=\"result\"><tbody><tr>";
      for($i = 0; $i < count($headers); $i++){
	 echo "<th>".$headers[$i]."</th>";
      }
      echo "</tr>";
      echo "<tr>";
      for($i = 0; $i < count($headers)-1; $i++){
	 if($headers[$i] == "id"){ 
	       echo "<td><input type=\"hidden\" value=\"".$info[$i]."\">".$info[$i]."</td>";
	 } else {
	    echo "<td><input type=\"text\" value=\"".$info[$i]."\"></td>";
	 }
      }
      echo "<td><textarea name=\"description\" id=\"description\">".$info[2]."</textarea></td>";
      echo "</tr></tbody></table><br><br>";
      echo "<input type=\"button\" id=\"submitButton\" value=\"Update Quest\">";
      echo "<script src=\"./questFiles/questsUpdate.js\"></script>";

   } else {
      echo "Query: ".$query."<br>";
      echo "BAD QUERY";
   }
}
?>
