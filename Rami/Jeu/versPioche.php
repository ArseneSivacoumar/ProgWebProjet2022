<?php

if(isset($_GET['id']) && !empty($_GET['id'])) {
  $id = $_GET['id'];
  $array = str_split($id);

  $joueur = 0;
  $carte = 0;
  if(count($array)  < 3 ) {
     $joueur = $array[0];
     $carte = $array[1];
  }
  else {
     $joueur = $array[0];
     $carte = $array[1].$array[2];
  }

  $jsonString = file_get_contents('./results.json');
  $data = json_decode($jsonString, true);
  $dataJoueur = $data[$joueur];
  
/*  $i=0;
  foreach($dataJoueur as $element) {
   //check the property of every element
   if($i == $carte){
	   echo $element[$i];
      unset($data[$joueur][$i]);
   }
   $i++;
 } */

 $addPioche = $data[$joueur][$carte]; //test

 unset($data[$joueur][$carte]);

 
 $data[$joueur] = array_values($data[$joueur]);

 $data[4][] = $addPioche;  // test
 $newJsonString = json_encode($data);
 file_put_contents('./results.json', $newJsonString);

  
}
?>
