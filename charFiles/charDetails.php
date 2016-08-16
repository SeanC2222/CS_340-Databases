<?php

ini_set('display_errors', 'On');

$sql = mysqli_connect("oniddb.cws.oregonstate.edu", "mulholls-db", "SUVJuqcb6v7wtq6k", "mulholls-db");

$keys = array_keys($_GET);
$vals = array_values($_GET);

$finalResponse = [];

$query = "SELECT name, class, allegiance, gender, strength, intelligence, endurance FROM characters WHERE name=\"".$vals[0]."\";";

$res = mysqli_query($sql, $query);
$all = mysqli_fetch_all($res, MYSQLI_ASSOC);
array_push($finalResponse, $all[0]);

$query = "SELECT i.name, i.type, i.skill_name FROM characters c INNER JOIN char_equip ci ON c.id = ci.cid INNER JOIN (SELECT i.id AS id, i.name AS name, i.type AS type, s.name AS skill_name FROM items i LEFT JOIN skills s ON i.i_skill = s.id) AS i ON i.id = ci.iid  WHERE c.name=\"".$vals[0]."\";";

$res = mysqli_query($sql, $query);
$all = mysqli_fetch_all($res, MYSQLI_ASSOC);
array_push($finalResponse, $all);

$query = "SELECT s.name, s.type, s.element FROM characters c INNER JOIN char_skills cs ON c.id = cs.cid INNER JOIN skills s ON s.id = cs.sid WHERE c.name=\"".$vals[0]."\";";

$res = mysqli_query($sql, $query);
$all = mysqli_fetch_all($res, MYSQLI_ASSOC);
array_push($finalResponse, $all);

$query = "SELECT qi.name FROM characters c INNER JOIN quest_items qi ON c.c_quest_item1 = qi.id OR c.c_quest_item2 = qi.id OR c.c_quest_item3 = qi.id WHERE c.name=\"".$vals[0]."\";";

$res = mysqli_query($sql, $query);
$all = mysqli_fetch_all($res, MYSQLI_ASSOC);
array_push($finalResponse, $all);

$query = "SELECT q.name, q.description FROM characters c INNER JOIN char_quest cq ON c.id = cq.cid INNER JOIN quests q ON q.id = cq.qid WHERE c.name=\"".$vals[0]."\";";

$res = mysqli_query($sql, $query);
$all = mysqli_fetch_all($res, MYSQLI_ASSOC);
array_push($finalResponse, $all);

$query = "SELECT charItemInv.qname FROM (SELECT q.name AS qname, COUNT(DISTINCT qqi.qiid) AS qiCount FROM quests q INNER JOIN quest_quest_items qqi ON q.id = qqi.qid GROUP BY q.name) AS questItemCount INNER JOIN (SELECT q.name as qname, COUNT(DISTINCT qi.id) AS qiCount FROM characters c INNER JOIN char_quest cq ON c.id = cq.cid INNER JOIN quests q ON q.id = cq.qid INNER JOIN quest_quest_items qqi ON qqi.qid = q.id INNER JOIN quest_items qi on qqi.qiid = qi.id WHERE c.name=\"".$vals[0]."\" AND qi.id IN (c.c_quest_item1, c.c_quest_item2, c.c_quest_item3) GROUP BY q.name) AS charItemInv ON questItemCount.qname = charItemInv.qname WHERE questItemCount.qiCount = charItemInv.qiCount";

$res = mysqli_query($sql, $query);
$all = mysqli_fetch_all($res, MYSQLI_ASSOC);
array_push($finalResponse, $all);



echo json_encode($finalResponse);



?>
