<?php

function sortByPredefinedOrder($leftItem, $rightItem){
  $order = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 'valet', 'dame', 'roi');

  $flipped = array_flip($order);

  $leftPos = $flipped[$leftItem];
  $rightPos = $flipped[$rightItem];
  return $leftPos >= $rightPos;   
}

function carteToNumber($carte) {
	if($carte == 'valet') {
		return 11;
	}
	else if($carte == 'dame') {
		return 12;
	}
	else if($carte == 'roi') {
		return 13;
	}
	else {
		return $carte;
	}
}

if(isset($_GET['id']) && !empty($_GET['id'])) {
$id = str_split($_GET['id']);


$jsonString = file_get_contents('./results.json');
$data = json_decode($jsonString, true);

$tab = $data[$id[0]-1];

$tab_value = array();
for($i = 0; $i < count($tab); $i++) {
    $tab_value[] = $tab[$i][0];
}


usort($tab_value, "sortByPredefinedOrder");

// Point du joueur
$jsonString = file_get_contents('./points.json');
$data_point = json_decode($jsonString, true);

$point = 0;
if(!empty($data_point)) {
	$point = $data_point[$id[0]-1];
}

$cartes_a_deposer = array();

$tab_sort = array();

for($i = 0; $i < count($tab_value); $i++) {
    $id_unset = array();
    for($j = 0; $j < count($tab); $j++) {
        if($tab_value[$i] == $tab[$j][0]) {
            $tab_sort[] = $tab[$j];
            $id_unset[] = $j;
        }
    }
    for($k = count($id_unset)-1; $k >= 0; $k--) {
        array_splice($tab, $id_unset[$k], 1);;
    }
}


//echo json_encode($tab_sort);
$array_id_brelan = array();
$array_id_carre = array();

// Verification carre et brelan
for($i = 0; $i < count($tab_sort); $i++) {
	$valeur_repete = 0;
	for($j = $i; $j < count($tab_sort); $j++) {
		if($tab_sort[$i][0] == $tab_sort[$j][0]) {
			$valeur_repete++;
		}
	}
	if($valeur_repete == 4) {
		if(strval($tab_sort[$i][0]) !== strval(intval($tab_sort[$i][0]))) {
			# not integer
			$point += 4*10;
		}
		else {
			$point += 4*$tab_sort[$i][0];
		}
		$array_id_carre[] = $i;
		$i+=3;	
	}
	else if($valeur_repete == 3) {
		if(strval($tab_sort[$i][0]) !== strval(intval($tab_sort[$i][0]))) {
			# not integer
			$point += 3*10;
		 }
		else {
		       	$point += 3*$tab_sort[$i][0];
		}
		$array_id_brelan[] = $i;
		$i+=2;
	}
}

$cptCarre = 0;
// Je supprime les carres
for($i = 0; $i < count($array_id_carre); $i++) {
	//	array_splice($tab_sort, $array_id_carre[$i], 4);
	array_push($cartes_a_deposer, $tab_sort[$array_id_carre[$i] - 4*$cptCarre], $tab_sort[$array_id_carre[$i] + 1  - 4*$cptCarre], $tab_sort[$array_id_carre[$i] + 2 - 4*$cptCarre], $tab_sort[$array_id_carre[$i] + 3 - 4*$cptCarre]);

	unset($tab_sort[$array_id_carre[$i] - 4*$cptCarre], $tab_sort[$array_id_carre[$i] + 1 - 4*$cptCarre ], $tab_sort[$array_id_carre[$i] + 2  - 4*$cptCarre], $tab_sort[$array_id_carre[$i] + 3  - 4*$cptCarre]);
	$tab_sort = array_values($tab_sort);
	$cptCarre++;
}

$cptBrelan = 0;
$cptCarre = 0;
// Je supprime les brelans
for($i = 0; $i < count($array_id_brelan); $i++) {
	for($j = 0; $j < count($array_id_carre); $j++) { if($array_id_carre[$j] < $array_id_brelan[$i]) {$cptCarre++; }   }
		//	array_splice($tab_sort, $array_id_brelan[$i], 3);
	array_push($cartes_a_deposer, $tab_sort[$array_id_brelan[$i]  - (4*$cptCarre+3*$cptBrelan)], $tab_sort[$array_id_brelan[$i] + 1 - (4*$cptCarre+3*$cptBrelan)], $tab_sort[$array_id_brelan[$i] + 2  - (4*$cptCarre+3*$cptBrelan)]);
	unset($tab_sort[$array_id_brelan[$i]  - (4*$cptCarre+3*$cptBrelan)], $tab_sort[$array_id_brelan[$i] + 1 - (4*$cptCarre+3*$cptBrelan)], $tab_sort[$array_id_brelan[$i] + 2  - (4*$cptCarre+3*$cptBrelan)]);
	$tab_sort = array_values($tab_sort);
	$cptBrelan++;
}

$tab_sort = array_values($tab_sort);




// Je regarde si il reste des suites

for($i = 0; $i < count($tab_sort); $i++) {
	$value = carteToNumber($tab_sort[$i][0]);
	$cons = 0;
	
	$array_temporaire = array($tab_sort[$i]); // Je regarde si la suite est valide puis j'envois vers la liste à renvoyer si c'est une suite
	for($j = $i+1; $j < count($tab_sort); $j++) {
		if(carteToNumber($tab_sort[$j][0]) == $value+1) {
			array_push($array_temporaire, $tab_sort[$j]);
			$value++;
		    $cons++;
		}
	}
		    
	if($cons >= 3) {
		$cartes_a_deposer = array_merge($cartes_a_deposer, $array_temporaire);
		for($k = $cons; $k >= 0; $k--) {
			if($value > 10) {
				$point+=10;
				$value--;
			}
			else {
				$point += $value;
				$value--;
			}
	    }
		$i+=$cons-1;
	}

}

if($point >= 51) {
	// J'enleve les cartes correspondates sur le serveur
        $jsonString = file_get_contents('./results.json');
	$data = json_decode($jsonString, true);
	$tab = $data[$id[0]-1];


	for($i = 0; $i < count($cartes_a_deposer); $i++) {
		for($j = 0; $j < count($tab); $j++) {
			if($cartes_a_deposer[$i] == $data[$id[0]-1][$j]) {
				unset($data[$id[0]-1][$j]);
				$data[$id[0]-1] = array_values($data[$id[0]-1]);
				break;
			}
		}	
	}	

	$newJsonString = json_encode($data);
	file_put_contents('./results.json', $newJsonString);
    
    $data_point[$id[0]-1] = $point;
    $newJsonString = json_encode($data_point);
	file_put_contents('./points.json', $newJsonString);


	array_push($cartes_a_deposer, $point);
	echo json_encode($cartes_a_deposer);
}


// renvoyer les cartes à poser devant le jeu du joueur ou liste vide si point inférieur à 51 points (mettre points en id du div ? ) 







}
?>

