var submit = document.getElementById("submitButton");

var tableBody = document.getElementById("result").children[0];

var row1 = tableBody.children[0];
var row2 = tableBody.children[1];

submit.addEventListener('click', function(){

   var data = new FormData();
   data.append('action', 'UPDATE');
   for(var i = 0; i < row1.children.length; i++){
      data.append(row1.children[i].textContent, row2.children[i].children[0].value);
   }

   var req = new XMLHttpRequest();

   req.open("POST", "./skillQuery.php", true);

   req.addEventListener('load', function(){
      if(req.status >=200 && req.status <= 400){
	 console.log(req.responseText);
	 alert("Successfully applied update!");
	 window.location.href = "../projectHome.html";

      } else {
	 alert("Error during update.");
	 window.location.href = "../projectHome.html";
      }
   });
   req.send(data);
});
