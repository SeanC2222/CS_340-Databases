<?php

ini_set('display_errors', 'On');

$sql = mysqli_connect("oniddb.cws.oregonstate.edu", "mulholls-db", "SUVJuqcb6v7wtq6k", "mulholls-db");

if($_SERVER['REQUEST_METHOD'] === 'POST'){

   //Item Creation Enchantment Choices
   if($_POST["action"] == 'ENCHANT' && $_POST["active"] == 'false'){

      $query = "SELECT * FROM skills WHERE active=false";
      if($res = mysqli_query($sql, $query)){
	 $all = mysqli_fetch_all($res, MYSQLI_ASSOC);
	 echo json_encode($all);

      } else {
	 echo "Failure to SELECT <br>";
	 echo "Query: <br>";
	 echo $query."<br>";
	 printf("ERROR %s\n", mysqli_error($sql));
      }
   }


   //Skill INSERT
   if($_POST['action'] == "INSERT"){

      $keys = array_keys($_POST);
      $vals = array_values($_POST);

      $query = "INSERT INTO skills (";

      for($i = 0; $i < count($keys); $i++){
	 if($keys[$i] != "elementCheck" && $keys[$i] != "action"){

	    if($keys[$i] == "activeCheck"){
	       $query = $query."active";
	    } else {
	       $query = $query.$keys[$i];
	    }

	    if($i != count($keys)-1){
	       $query = $query.", ";
	    }
	 }
      }

      $query = $query.") VALUES (";

      for($i = 0; $i < count($vals); $i++){
	 if($keys[$i] != "elementCheck" && $keys[$i] != "action"){

	    if($keys[$i] == "activeCheck" && $vals[$i] == "on"){
	       $query = $query."\"1";
	    } else if ($keys[$i] == "activeCheck" && $vals[$i] == "off") {
	       $query = $query."\"0";
	    } else {
	       $query = $query."\"".$vals[$i];
	    }

	    if($i != count($vals)-1){
	       $query = $query."\", ";
	    } else {
	       $query = $query."\"";
	    }
	 }
      }

      $query = $query.");";

      if(mysqli_query($sql, $query)){
	 echo "<h2>SUCCESS!</h2><br>";
	 echo "<form action=\"../skillCreate.html\" style=\"display:inline\"><input type=\"submit\" value=\"Make Another Skill\"></form>&nbsp&nbsp";
	 echo "<form action=\"../projectHome.html\" style=\"display:inline\"><input type=\"submit\" value=\"Home Screen\"></form>";
      } else {
	 echo "Failure to add skill!";
	 echo "Query: <br>";
	 echo $query."<br>";
	 printf("ERROR %s\n", mysqli_error($sql));
      }

   //Skill Update
   } else if ($_POST['action'] == "UPDATE"){
      $keys = array_keys($_POST);
      $vals = array_values($_POST);

      $query = "UPDATE skills SET ";

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

//Get Request for Single Skill
} else {

   $keys = array_keys($_GET);
   $vals = array_values($_GET);

   $query = "SELECT * FROM skills WHERE ";

   for($i = 0; $i < count($vals); $i++){
      $query = $query.$keys[$i]."=\"".$vals[$i]."\"";
      if($i != count($vals)-1){
	 $query = $query." AND ";
      }
   }

   $query = $query.";";

   if($res = mysqli_query($sql, $query)){
      $skill = mysqli_fetch_assoc($res);
      $headers = array_keys($skill);
      $info = array_values($skill);
      echo "<head><link rel=\"stylesheet\" type=\"text/css\" href=\"../mainStyle.css\"></head>";
      echo "<div id=\"navigation\"><a href=\"../projectHome.html\">Home Screen</a></div>";
      echo "<table id=\"result\"><tbody><tr>";
      for($i = 0; $i < count($headers); $i++){
	 echo "<th>".$headers[$i]."</th>";
      }
      echo "</tr>";
      echo "<tr>";
      for($i = 0; $i < count($headers); $i++){
	 if($headers[$i] == "id" || $headers[$i] == "type"){
	       echo "<td><input type=\"hidden\" value=\"".$info[$i]."\">".$info[$i]."</td>";
	 } else if ($headers[$i] == "element"){
	       $elements = array("", "fire", "ice", "electric", "holy", "dark"); 
	       echo "<td><select name=\"element\">";
	       for($j = 0; $j < count($elements); $j++){
		  echo "<option value=\"".$elements[$j]."\"";
		  if($info[$i] == $elements[$j]){
		     echo " selected";
		  }
		  echo ">".$elements[$j]."</option>";
	       }
	       echo "</select></td>";
	 } else {
	    echo "<td><input type=\"text\" value=\"".$info[$i]."\"></td>";
	 }
      }
      echo "</tr></tbody></table><br><br>";
      echo "<input type=\"button\" id=\"submitButton\" value=\"Update Skill\">";
      echo "<script src=\"./skillUpdate.js\"></script>";

   } else {
      echo "Query: ".$query."<br>";
      echo "BAD QUERY";
   }
}
?>
