<?php
//$cartes=array(2,3,4,5,6,7,8,9,10,'valet','dame','roi','1');
$cartes=array(1,2,3,4,5,6,7,8,9,10,'valet','dame','roi');
//$jokers=array(array("joker", "joker"), array("joker","joker"));
$couleurs=array('carreau','coeur','pic','trefle');
$jeu=array();
for($i=0;$i<count($couleurs);$i++){
	for($j=0;$j<count($cartes);$j++){
		$jeu[$i*count($cartes)+$j]= array($cartes[$j],$couleurs[$i]);
	}
}

//$jeu = array_merge($jeu, $jokers);//concaténer 2 tableaux
$jeu2 = $jeu;
$jeu = array_merge($jeu, $jeu2);
shuffle($jeu);//pour mélanger

$distrib1 = array();
$distrib2 = array();
$distrib3 = array();
$distrib4 = array();

if($_GET['id']==1){
	for($i=0;$i<4;$i++){
		for($j = 0; $j < 14; $j++) {
			if($i == 0) {
				$a = array_shift($jeu);
				array_push($distrib1, $a);
			}
			if($i == 1) {
				$a = array_shift($jeu);
				array_push($distrib2, $a);
			}
			if($i == 2) {
				$a = array_shift($jeu);
				array_push($distrib3, $a);
			}
			if($i == 3) {
				$a = array_shift($jeu);
				array_push($distrib4, $a);
			}
		}
	}

	$result = array($distrib1, $distrib2, $distrib3, $distrib4, $jeu);

	$fp = fopen('results.json', 'w');
	fwrite($fp, json_encode($result));
	fclose($fp);

	echo json_encode($result);
}
else 
{
	$result = file_get_contents('./results.json');
	$data = json_decode($result, true);
	echo json_encode($data);
}
?>
