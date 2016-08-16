var questItems;

var req = new XMLHttpRequest();
req.open("GET", "../questItemFiles/questItemsQuery.php?action=names", true);
req.addEventListener('load', function(){
   questItems = JSON.parse(req.responseText);
});
req.send();


var numQuestItems = document.getElementById("numQuestItems");

numQuestItems.addEventListener('change', function(){

   var numItems = numQuestItems.value;
   var itemDiv = document.getElementById("questItems");

   while(itemDiv.firstChild){
      itemDiv.removeChild(itemDiv.firstChild);
   }
   
   for(var i = 0; i < numItems; i++){
      var newS = document.createElement("select");
      newS.name = "questItemName" + i;
      for(var j = 0; j < questItems.length; j++){
	 var newO = document.createElement("option");
	 newO.value = questItems[j]['name'];
	 newO.textContent = questItems[j]['name'];
	 newS.appendChild(newO);
      }
      itemDiv.appendChild(newS);
   } 
   
});
