var selectBox = document.getElementById("type");
var formField = document.getElementById("formField");

var passiveSkillList;

var req = new XMLHttpRequest();
req.open("POST", "../skillFiles/skillQuery.php", true);
req.addEventListener('load', function(){
   passiveSkillList = JSON.parse(req.responseText);
});

var payload = new FormData();
payload.append('action','ENCHANT');
payload.append('active','false');

req.send(payload);

selectBox.addEventListener('change', function(){

   if(formField.children.length > 1){
      formField.removeChild(formField.children[1]);
   }
   var value = selectBox.value;
   
   var newField = document.createElement("fieldset");
   var newLegend = document.createElement("legend");
   var label = document.createElement("label");
   var magnitude = document.createElement("input");
   var subtype = document.createElement("select");
   subtype.name = "subtype";
   
   if(value == "offense"){
      newLegend.textContent = "Offensive Item";

      label.textContent = " Offense ";

      magnitude.type = "number";
      magnitude.name = "offense";
      magnitude.id = "offense";

      newOption = document.createElement("option");
      newOption.name = "subtype";
      newOption.value = "weapon";
      newOption.textContent = "Weapon";
      subtype.appendChild(newOption);

      newOption = document.createElement("option");
      newOption.name = "subtype";
      newOption.value = "poison";
      newOption.textContent = "Poison";
      subtype.appendChild(newOption);
      
   } else if(value == "defense"){
      newLegend.textContent = "Defensive Item";

      label.textContent = " Defense ";

      magnitude.type = "number";
      magnitude.name = "defense";
      magnitude.id = "defense";

      newOption = document.createElement("option");
      newOption.name = "subtype";
      newOption.value = "armor";
      newOption.textContent = "Armor";
      subtype.appendChild(newOption);

      newOption = document.createElement("option");
      newOption.name = "subtype";
      newOption.value = "shield";
      newOption.textContent = "Shield";
      subtype.appendChild(newOption);

      newOption = document.createElement("option");
      newOption.name = "subtype";
      newOption.value = "boots";
      newOption.textContent = "Boots";
      subtype.appendChild(newOption);
 
   }else if(value == "healing"){
      newLegend.textContent = "Healing Item";

      label.textContent = " Magnitude ";

      magnitude.type = "number";
      magnitude.name = "healing";
      magnitude.id = "magnitude";

      newOption = document.createElement("option");
      newOption.name = "subtype";
      newOption.value = "potion";
      newOption.textContent = "Potion";
      subtype.appendChild(newOption);

      newOption = document.createElement("option");
      newOption.name = "subtype";
      newOption.value = "charm";
      newOption.textContent = "Charm";
      subtype.appendChild(newOption);
         
   }else if(value == "statBuffer"){
      newLegend.textContent = "Stat Buffing Item";

      label.textContent = " Magnitude ";

      magnitude.type = "number";
      magnitude.name = "magnitude";
      magnitude.id = "magnitude";
   
      newOption = document.createElement("option");
      newOption.name = "subtype";
      newOption.value = "potion";
      newOption.textContent = "Potion";
      subtype.appendChild(newOption);

      newOption = document.createElement("option");
      newOption.name = "subtype";
      newOption.value = "charm";
      newOption.textContent = "Charm";
      subtype.appendChild(newOption);
    
   }

   newField.appendChild(newLegend);
   newField.appendChild(label);
   newField.appendChild(document.createElement("nbsp"));
   newField.appendChild(magnitude);
   newField.appendChild(subtype);
       
   formField.insertBefore(newField, formField.children[1]);
});


var checkBox = document.getElementById("skillCheck");

checkBox.addEventListener('click', function(){
   if(checkBox.checked){
      while(formField.children.length > 2){
	 formField.removeChild(formField.children[2]);
      }

      var newField = document.createElement("fieldset");
      var newLegend = document.createElement("legend");
      newLegend.textContent = "Item's Skill";

      newField.appendChild(newLegend);

      var newSelect = document.createElement("select");
      newSelect.name = "i_skill";

      for(var i = 0; i < passiveSkillList.length; i++){
	 var newOption = document.createElement("option");
	 newOption.value = passiveSkillList[i]['name'];
	 newOption.textContent = passiveSkillList[i]['name'];
	 newSelect.appendChild(newOption);
      }

      newField.appendChild(newSelect);
      formField.appendChild(newField);
   } else {
      while(formField.children.length > 2){
	 formField.removeChild(formField.children[2]);
      }
   }
});

