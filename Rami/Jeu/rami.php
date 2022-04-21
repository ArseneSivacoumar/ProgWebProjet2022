<!DOCTYPE html>
<html>
 <head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
   <link rel="stylesheet" type="text/css" href="styleRami.css" />
   <title> Rami </title>
   <style>
   	.button 
   	{
 			background-color: white;
    	color: black;
    	padding: 14px 20px;
    	margin: 8px 0;
    	border: none;
    	cursor: pointer;
    	width: 10%;
    	border-radius: 20px;
 		}
 		</style>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <button class="button "id="myButton">Log out</button>
  <script>
   	function placementDesCartesRetourner()
  	{
  		for (var j = 0; j < 14; j++) 
  		{
		  		let $imageRetournerH = $("<img src=\"images/cache" + ".png\" height=\"55\" width=\"35\"> ");
		     	$("#content1").append($imageRetournerH);
			  	let $imageRetournerV = $("<img name='vertical' src=\"images/cache" + ".png\" height=\"37\" width=\"55\"> ");
		      $("#content2").append($imageRetournerV);
		      let $imageRetournerV2 = $("<img name='vertical' src=\"images/cache" + ".png\" height=\"37\" width=\"55\"> ");
		      $("#content4").append($imageRetournerV2);
		  }
  	}

  	function deposer(i) {
		  let id = i;
                  $.ajax({
                      method: "GET",
	              dataType: "json",
		      url:"deposer.php",
		      data: {"id": id }
	           }).done(function(obj) {
				   console.log(obj);
				   for(let i = 0; i < obj.length - 1; i++) {
					   console.log( obj[i][0] + " " + obj[i][1]);
					   let $im = $("<span id = " + obj[i][0] + obj[i][1] + ">"
				   +" <img src=\"images/" + obj[i][0] + obj[i][1] + ".png\" height=\"55\" width=\"35\"> "
				    +"</span>");
				       $(".depot1").append($im);
				    
				       // Je supprime dans les cartes du joueur
				       let content = $("#content3");
                	   
					   let str = "" + obj[i][0] + obj[i][1];
					   let element = document.getElementsByName(str)[0];
					   element.remove();

					   // Je rééquilibre les id
					   let taille = document.getElementById("content3").children.length;
                	   childrenArray = content.children().toArray();
					   for(let i = 0; i < taille; i++) {
						   childrenArray[i].id = "" + (id-1) + "" + i;
					   }
					   
					   // Vérification win
					   if(taille == 0) { alert("VOUS AVEZ GAGNE");}
				   }
		   }).fail(function(e) {
	              console.log(e);   
		   });
	  }
	  
	  function envoiePioche(im, joueur) {
				 let id =  $(im).attr('id');
				 console.log(id);
			     $.ajax({
								 method: "GET",
		             dataType: "json",
		             url: "versPioche.php",
		             data: {"id": id}
		      }).done(function(obj) {
		      }).fail(function(e) {
		        console.log(e);
		      });
		      $(im).remove();
		      
		      let content = $("#content3");
		      
		      console.log(document.getElementById("content3").children.length);
		      let taille = document.getElementById("content3").children.length;
		      
				childrenArray = content.children().toArray();

		      for(let i = 0; i < taille; i++) {
			    		childrenArray[i].id = "" + joueur + "" + i;	  
			  	}
	  }
	  
	  
	  function piocher(i) {
		  let id = i;
		  console.log(id);
		  $.ajax({
			 method: "GET",
             dataType: "json",
             url: "piocher.php",
             data: {"id": id }
      }).done(function(obj) {
		  console.log(obj[0] + obj[1]);
		  
		  let taille = document.getElementById("content3").children.length;
		  let truc = id - 1;
		  let $im = $("<span id = " + truc + taille + "\"  name=\""+ obj[0] + obj[1]  + "\">"
				   +" <img src=\"images/" + obj[0] + obj[1] + ".png\" height=\"55\" width=\"35\"> "
				    +"</span>");

		  $im.click(function (){envoiePioche(this)});
		  $("#content3").append($im);
		 
      }).fail(function(e) {
        console.log(e);
      });
	  }
	  
    function ajaxCall(id){
    	$.ajax({
          method: "GET",
          dataType: "json",
          url: "debut.php",
          data: {"id": id}
     }).done(function(obj) {
	  	let s = "";
	  	placementDesCartesRetourner();
		      for (var i = 0; i < 4; i++) { 
		      	  for (var j = 0; j < 14; j++) {
						      if(i == Number(id)-1) {
										  let $im = $("<span name=\""+ obj[i][j][0] + obj[i][j][1]   + "\" id = " + i + j + ">"
										   +" <img src=\"images/" + obj[i][j][0] + obj[i][j][1] + ".png\" height=\"55\" width=\"35\"> "
										    +"</span>");
										  $im.click(function (){envoiePioche(this, id-1)});
										  $("#content3").append($im);
						      }
		      	  }
		     } 
		     
		     let $imageRetourner = $("<img src=\"images/cache" + ".png\" height=\"55\" width=\"35\"> ");
		     $imageRetourner.click(function (){piocher(id)});
				 $("#content5").append($imageRetourner);
		      }).fail(function(e) {
		        console.log(e);
		      });
  }

    // Creation d'une map qui garde paire de key value.
    let map = new Map();
    getParameters = () => {
		    // Address de l'actuel fenetre
		    address = window.location.search;
		  
		    // Parametres de recherche 
		    parameterList = new URLSearchParams(address);
		  
		    // Ajout a la map key et value(dans notre cas l'id du joueur et sa valeur)
		    parameterList.forEach((value, key) => {
		        map.set(key, value);
		    })
		  
		    return map;
		}
		getParameters();
		let id = map.get("id");
		let username = map.get("username");
		console.log(map);
		console.log(id);
		console.log(username);
		ajaxCall(id);
		document.getElementById("myButton").onclick = function () 
		{
				location.href = "../index.php?deconnexion="+username;
		}
    </script>
 </head>
 <body>
 	<div class="btn1" style="position:absolute; margin-left:75%; margin-top:3%;" >
		<input id="btn" type="button" value="Deposer" onclick = "deposer(map.get('id'));"/> 
	</div>
    <div id="content1">
    </div>
    <div id="content2">
    </div>
    <div id="content3">
    </div>
    <div id="content4">
    </div>
    
	<div id="content5">
	</div>
	<div class="depot1" style="position:absolute; margin-top:600px; margin-left:30%;" >
    </div>
 </body>
</html>

