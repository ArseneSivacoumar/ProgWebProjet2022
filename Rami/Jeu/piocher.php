<?php
  //  echo "test ".isset($_GET['id'])." ".empty($_GET['id']);
if(isset($_GET['id']) && !empty($_GET['id'])) {
	
	$id = $_GET['id'] - 1;
	
	
    $jsonString = file_get_contents('./results.json');
    $data = json_decode($jsonString, true);
    
    $carte = $data[4][0];
    $data[$id][] = $carte;
    
    unset($data[4][0]);
    
    $data[4] = array_values($data[4]);
	
	
    $newJsonString = json_encode($data);
    file_put_contents('./results.json', $newJsonString);
    
    echo json_encode($carte);
}
?>
