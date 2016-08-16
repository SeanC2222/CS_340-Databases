<?php

ini_set('display_errors', 'On');

$sql = mysqli_connect("oniddb.cws.oregonstate.edu", "mulholls-db", "SUVJuqcb6v7wtq6k", "mulholls-db");

if($_SERVER['REQUEST_METHOD'] === 'POST'){

   if($_POST['action'] == "INSERT"){

      $keys = array_keys($_POST);
      $vals = array_values($_POST);

      $query = "INSERT INTO characters (";

      for($i = 1; $i < count($keys); $i++){
	 if($keys[$i] != "action"){
	    $query = $query.$keys[$i];
	    if($i != count($keys)-1){
	       $query = $query.", ";
	    }
	 }
      }

      $query = $query.") VALUES (";

      for($i = 1; $i < count($vals); $i++){
	 if($keys[$i] != "action"){
	    $query = $query."\"".$vals[$i];
	    if($i != count($vals)-1){
	       $query = $query."\", ";
	    } 
	 }
      }

      $query = $query."\");";

      if(mysqli_query($sql, $query)){
	 echo "<h2>SUCCESS!</h2><br>";
	 echo "<form action=\"../charFiles/charCreate.html\" style=\"display:inline\"><input type=\"submit\" value=\"Make Another character\"></form>&nbsp&nbsp";
	 echo "<form action=\"../projectHome.html\" style=\"display:inline\"><input type=\"submit\" value=\"Home Screen\"></form>";
      } else {
	 echo "Failure to add character!";
	 echo "Query: <br>";
	 echo $query."<br>";
	 printf("ERROR %s\n", mysqli_error($sql));
      }
   } else if ($_POST['action'] == "UPDATE"){
      $keys = array_keys($_POST);
      $vals = array_values($_POST);

      $query = "UPDATE characters SET ";

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
	 http_response_code(499);
	 echo $query;
	 echo "Failure!";
      }
     
   }

} else {

   $keys = array_keys($_GET);
   $vals = array_values($_GET);

   if(array_key_exists("action", $_GET)){
      $query = "SELECT name FROM characters";
      if($res = mysqli_query($sql, $query)){
	 $all = mysqli_fetch_all($res, MYSQLI_ASSOC);
	 echo json_encode($all);
      } else {
	 echo "Query: ".$query."<br>";
	 echo "BAD QUERY";
      }

   } else {
      $query = "SELECT * FROM characters WHERE ";

      for($i = 0; $i < count($vals); $i++){
	 $query = $query.$keys[$i]."=\"".$vals[$i]."\"";
	 if($i != count($vals)-1){
	    $query = $query." AND ";
	 }
      }

      $query = $query.";";

      if($res = mysqli_query($sql, $query)){
	 $character = mysqli_fetch_assoc($res);
	 $headers = array_keys($character);
	 $info = array_values($character);
	 echo "<head><link rel=\"stylesheet\" type=\"text/css\" href=\"../mainStyle.css\"></head>";
	 echo "<div id=\"navigation\"><a href=\"../projectHome.html\">Home Screen</a></div>";
	 echo "<table id=\"result\"><tbody><tr>";
	 for($i = 0; $i < count($headers); $i++){
	    echo "<th>".$headers[$i]."</th>";
	 }
	 echo "</tr>";
	 echo "<tr>";
	 for($i = 0; $i < count($headers); $i++){
	    if($headers[$i] == "id"){
		  echo "<td><input type=\"hidden\" value=\"".$info[$i]."\">".$info[$i]."</td>";
	    } else {
	       echo "<td><input type=\"text\" value=\"".$info[$i]."\"></td>";
	    }
	 }
	 echo "</tr></tbody></table><br><br>";
	 echo "<input type=\"button\" id=\"submitButton\" value=\"Update Character\">";
	 echo "<script src=\"charUpdate.js\"></script>";

      } else {
	 echo "Query: ".$query."<br>";
	 echo "BAD QUERY";
      }
   }
}
?>
