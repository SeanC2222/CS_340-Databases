var selectBox = document.getElementById("type");
var formField = document.getElementById("formField");

selectBox.addEventListener('change', function(){

   if(formField.children.length > 1){
      formField.removeChild(formField.children[1]);
   }
   var value = selectBox.value;
   
   var newField = document.createElement("fieldset");
   var newLegend = document.createElement("legend");
   var label = document.createElement("label");
   var magnitude = document.createElement("input");
   magnitude.name = "magnitude";
   magnitude.id = "magnitude";
   magnitude.type = "number";
   var activeLabel = document.createElement("label");
   activeLabel.textContent = " Skill is actively used? ";
   var activeCheck = document.createElement("input");
   activeCheck.type = 'checkbox';
   activeCheck.name = "activeCheck";
   
   if(value == "offense"){
      newLegend.textContent = "Offensive Skill";

      label.textContent = "Offense ";
      
   } else if(value == "defense"){
      newLegend.textContent = "Defensive Skill";

      label.textContent = "Defense ";

   }else if(value == "healing"){
      newLegend.textContent = "Healing Skill";

      label.textContent = "Magnitude ";

   }else if(value == "statBuffer"){
      newLegend.textContent = "Stat Buffing Skill";

      label.textContent = "Magnitude ";

   }

   newField.appendChild(newLegend);
   newField.appendChild(label);
   newField.appendChild(document.createElement("nbsp"));
   newField.appendChild(magnitude);
   newField.appendChild(activeLabel);
   newField.appendChild(activeCheck);
       
   formField.insertBefore(newField, formField.children[1]);
});


var checkBox = document.getElementById("elementCheck");

checkBox.addEventListener('click', function(){
   if(checkBox.checked){
      while(formField.children.length > 2){
	 formField.removeChild(formField.children[2]);
      }

      var newField = document.createElement("fieldset");
      var newLegend = document.createElement("legend");
      newLegend.textContent = "Element Selection";

      newField.appendChild(newLegend);

      var newSelect = document.createElement("select");
      newSelect.name = "element";

      var elements = ["fire", "ice", "electric", "holy", "dark"];
      var elementNames = ["Fire", "Ice", "Electric", "Holy", "Dark"];

      for(var i = 0; i < elements.length; i++){
	 var newOption = document.createElement("option");
	 newOption.value = elements[i];
	 newOption.textContent = elementNames[i];
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

