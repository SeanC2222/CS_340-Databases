var entities = document.getElementsByClassName("entity");

for(var i = 0; i < entities.length; i++){

   for(var j = 3; j < entities[i].children.length; j++){
      var addButtonAction = generateButtonAction(entities[i].children[j]);
      addButtonAction();
   }
}

getCharNames();

function generateButtonAction(button){
   return function(){
      var tempButton = button;
      var tBAct = tempButton.name.substring(0,3);

      if(tBAct){
	 tempButton.addEventListener('click', function(){genAndSendXMLHttpRequest(tempButton);});
      } else {
	 document.getElementById("resultBody").textContent = "Button Event Generator Error.";
      }
   };
};

//Returns PHP page response in Results Table
function genAndSendXMLHttpRequest(button, vals){

   var tempButton = button;
   var result = document.getElementById("resultBody");
   result.innerHTML = "";
   var data = {}; data['action'] = tempButton.name.substring(0,3);
   data['table'] = tempButton.className;

   var req = new XMLHttpRequest();
   req.open("POST", "./viewAll.php", true);

   //When response is received load into table body id="resultBody" 
   req.addEventListener('load', function(){
      buildResultsTable(JSON.parse(req.responseText), tempButton);
   });

   req.send(JSON.stringify(data));
};

function buildResultsTable(response, button){

   var reqType = button.className;
   var keys = Object.keys(response[0]);
   var result = document.getElementById("resultBody");

   switch(reqType){
      case "characters":
	 reqType = "./charFiles/charQuery.php";
	 break;
      case "items":
	 reqType = "./itemFiles/itemQuery.php";
	 break;
      case "skills":
	 reqType = "./skillFiles/skillQuery.php";
	 break;
      case "quest_items":
	 reqType = "./questItemFiles/questItemsQuery.php";
	 break;
      case "quests":
	 reqType = "./questFiles/questsQuery.php";
	 break;
      default:
	 reqType = "error.php";
	 break;

   }

   while(result.firstChild){
      result.removeChild(result.firstChild);
   }

   var newRow = document.createElement("tr");

   //Headers Row 
   for(var j = 0; j < keys.length; j++){
      var newTh = document.createElement("th");
      newTh.textContent = keys[j];
      newRow.appendChild(newTh);
   }
   result.appendChild(newRow);

   //Information Rows
   for(var i = 0; i < response.length; i++){
      var newRow = document.createElement("tr");
      for(var j in response[i]){
	    var newTd = document.createElement("td");
	    var query = response[i][j];
	    if(j == "name"){
	       newTd.innerHTML = "<a href=\"" + reqType + "?id="+ response[i]['id'] + "&" + j + "=" + query + "\" >" + response[i][j] + "</a>";
	    } else {
	       newTd.textContent = response[i][j];
	    }
	    newRow.appendChild(newTd);
      }
      result.appendChild(newRow);
   }
};

function getCharNames(){
   var req = new XMLHttpRequest();
   req.open("GET", "./charFiles/charQuery.php?action=charNames", true);
   req.addEventListener('load', function(){
      var response = JSON.parse(req.responseText);
      var select = document.getElementById("charName");
      for(var i = 0; i < response.length; i++){
	 var option = document.createElement("option");
	 option.value = response[i]['name'];
	 option.textContent = response[i]['name'];
	 select.appendChild(option);
      }
   });
   req.send();
};
