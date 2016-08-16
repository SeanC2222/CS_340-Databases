/**
 * Function written by Chris Ferdinandi 3/2/2015
 * Sourced from: http://gomakethings.com/how-to-get-the-value-of-a-querystring-with-native-javascript
 */

var getQueryString = function( field, url){
   var href = url ? url : window.location.href;
   var reg = new RegExp( '[?&]' + field + '=([^&#]*)', 'i');
   var string = reg.exec(href)
   return string ? string[1] : null;
}

var name = getQueryString("charName");

var req = new XMLHttpRequest();
req.open("GET", "./charDetails.php?name=" + name, true);
req.addEventListener('load', function(){
   var charInfo = JSON.parse(req.responseText);

   console.log(charInfo);
   addBasicInfo(charInfo[0]);
   addItems(charInfo[1]);
   addSkills(charInfo[2]);
   addQuestItems(charInfo[3]);
   addQuests(charInfo[4], charInfo[5]);

});
   
req.send();

function addBasicInfo(basicinfo){

   document.getElementById("charname").textContent = basicinfo['name'];
   document.getElementById("charclass").textContent = basicinfo['class'];
   document.getElementById("charallegiance").textContent = basicinfo['allegiance'];
   document.getElementById("chargender").textContent = basicinfo['gender'];
   document.getElementById("charstrength").textContent = basicinfo['strength'];
   document.getElementById("charintelligence").textContent = basicinfo['intelligence'];
   document.getElementById("charendurance").textContent = basicinfo['endurance'];
};

function addItems(iteminfo){
   var items = document.getElementById("charitemsequipped");
   for(var i = 0; i < iteminfo.length; i++){
      var container = document.createElement("div");
      container.className = "items";
      var list = document.createElement("ul");

      for(var j in iteminfo[i]){
	 if(iteminfo[i][j]){
	    var temp;
	    if(j == 'name'){
	       temp = document.createElement("h4");
	    } else {
	       temp = document.createElement("li");
	    }
	    temp.textContent = iteminfo[i][j];
	    list.appendChild(temp);
	 }
      }
      container.appendChild(list);
      items.appendChild(container);
   } 
   if (items.children.length == 1){
      var h4 = document.createElement("h4");
      h4.textContent = "None";
      items.appendChild(h4);
   }
};

function addSkills(skillinfo){
   var skills = document.getElementById("charSkills");
   for(var i = 0; i < skillinfo.length; i++){
      var container = document.createElement("div");
      container.className = "skills";
      var list = document.createElement("ul");

      for(var j in skillinfo[i]){
	 if(skillinfo[i][j]){
	    var temp;
	    if(j == 'name'){
	       temp = document.createElement("h4");
	    } else {
	       temp = document.createElement("li");
	    }
	    temp.textContent = skillinfo[i][j];
	    list.appendChild(temp);
	 }
      }
      container.appendChild(list);
      skills.appendChild(container);
   } 
   if (skills.children.length == 1){
      var h4 = document.createElement("h4");
      h4.textContent = "None";
      skills.appendChild(h4);
   }
};

function addQuestItems(qiteminfo){
   var qitems = document.getElementById("charQuestItems");
   for(var i = 0; i < qiteminfo.length; i++){
      var container = document.createElement("div");
      container.className = "questitems";
      var list = document.createElement("ul");

      for(var j in qiteminfo[i]){
	 if(qiteminfo[i][j]){
	    var temp;
	    if(j == 'name'){
	       temp = document.createElement("h4");
	    } else {
	       temp = document.createElement("li");
	    }
	    temp.textContent = qiteminfo[i][j];
	    list.appendChild(temp);
	 }
      }
      container.appendChild(list);
      qitems.appendChild(container);
   } 
   if (qitems.children.length == 1){
      var h4 = document.createElement("h4");
      h4.textContent = "None";
      qitems.appendChild(h4);
   }
};

function addQuests(questinfo, questcompleteinfo){
   var quests = document.getElementById("charQuests");

   console.log(questinfo, questcompleteinfo);
   for(var i = 0; i < questinfo.length; i++){
      for(var j = 0; j < questcompleteinfo.length; j++){
	 if(questinfo[i]['name'] == questcompleteinfo[j]['qname']){
	    questinfo[i]['complete'] = true;
	    console.log("FOUND IT");
	    break;
	 } else {
	    questinfo[i]['complete'] = false;
	 }
      }

   }

   for(var i = 0; i < questinfo.length; i++){
      var container = document.createElement("div");
      container.className = "quests";
      var list = document.createElement("ul");
      for(var j in questinfo[i]){
	 if(questinfo[i][j]){
	    var temp;
	    if(j == 'name'){
	       temp = document.createElement("h4");
	       temp.textContent = questinfo[i][j];
	       console.log(questinfo[i]['complete']);
	       if(questinfo[i]['complete'] == true){
		  temp.textContent += " [ALL ITEMS]";
	       }
	    } else if (j == 'complete'){
	       //do nothing
	    } else {
	       temp = document.createElement("li");
	       temp.textContent = questinfo[i][j];
	    }
	    list.appendChild(temp);
	 }
      }
      container.appendChild(list);
      quests.appendChild(container);
   } 
   if (quests.children.length == 1){
      var h4 = document.createElement("h4");
      h4.textContent = "None";
      quests.appendChild(h4);
   }

};
