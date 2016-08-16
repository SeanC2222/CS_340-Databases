<?php

ini_set('display_errors', 'On');

$sql = mysqli_connect("oniddb.cws.oregonstate.edu", "mulholls-db", "SUVJuqcb6v7wtq6k", "mulholls-db");

if($_SERVER['REQUEST_METHOD'] === 'POST'){

   if($_POST['action'] == "INSERT"){

      if(array_key_exists("skillCheck", $_POST) && $_POST['skillCheck'] == "on"){
	 $skillQuery = "SELECT id FROM skills WHERE name='".$_POST['i_skill']."'";
	 $res = mysqli_query($sql, $skillQuery);
	 $all = mysqli_fetch_all($res, MYSQLI_ASSOC);
	 $_POST['i_skill'] = $all[0]['id'];
      }
      $keys = array_keys($_POST);
      $vals = array_values($_POST);


      $query = "INSERT INTO items (";

      for($i = 0; $i < count($keys); $i++){
	 if($keys[$i] != "skillCheck" && $keys[$i] != "action"){
	    $query = $query.$keys[$i];
	    if($i != count($keys)-1){
	       $query = $query.", ";
	    }
	 }
      }

      $query = $query.") VALUES (";

      for($i = 0; $i < count($vals); $i++){
	 if($vals[$i] != "on" && $keys[$i] != "action"){
	    $query = $query."\"".$vals[$i];
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
	 echo "<form action=\"itemCreate.html\" style=\"display:inline\"><input type=\"submit\" value=\"Make Another Item\"></form>&nbsp&nbsp";
	 echo "<form action=\"../projectHome.html\" style=\"display:inline\"><input type=\"submit\" value=\"Home Screen\"></form>";
      } else {
	 echo "Failure to add item!";
	 echo "Query: <br>";
	 echo $query."<br>";
	 printf("ERROR %s\n", mysqli_error($sql));
      }
   } else if ($_POST['action'] == "UPDATE"){
      $keys = array_keys($_POST);
      $vals = array_values($_POST);

      $query = "UPDATE items SET ";

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

   $query = "SELECT * FROM items WHERE ";

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

      $skillRes = mysqli_query($sql, "SELECT id, name FROM skills WHERE active='0'");
      $passiveSkills = mysqli_fetch_all($skillRes, MYSQLI_ASSOC);

      echo "<head><link rel=\"stylesheet\" type=\"text/css\" href=\"../mainStyle.css\"></head>";
      echo "<div id=\"navigation\"><a href=\"../projectHome.html\">Home Screen</a></div>";
      echo "<table id=\"result\"><tbody><tr>";
      for($i = 0; $i < count($headers); $i++){
	 echo "<th>".$headers[$i]."</th>";
      }
      echo "</tr>";
      echo "<tr>";
      for($i = 0; $i < count($headers); $i++){
	 if($headers[$i] == "id" || $headers[$i] == "type" || $headers[$i] == "subtype"){
	       echo "<td><input type=\"hidden\" value=\"".$info[$i]."\">".$info[$i]."</td>";
	 } else if ($headers[$i] == "i_skill"){
	       echo "<td><select name=\"i_skill\">";
	       for($j = 0; $j < count($passiveSkills); $j++){
		  echo "<option value=\"".$passiveSkills[$j]['name']."\"";
		  if($info[$i] == $passiveSkills[$j]['id']){
		     echo " selected";
		  }
		  echo ">".$passiveSkills[$j]['name']."</option>";
	       }
	       echo "</select></td>";

	 } else {
	    echo "<td><input type=\"text\" value=\"".$info[$i]."\"></td>";
	 }
      }
      echo "</tr></tbody></table><br><br>";
      echo "<input type=\"button\" id=\"submitButton\" value=\"Update Item\">";
      echo "<script src=\"./itemUpdate.js\"></script>";

   } else {
      echo "Query: ".$query."<br>";
      echo "BAD QUERY";
   }
}
?>
