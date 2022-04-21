<?php
	session_start();
	$_SESSION['arsene'] = 3;
	print_r($_SESSION);
	//session_reset();
	$_arsene['id'] = 3;
	if(isset($_SESSION['id'])){
		echo 'if';
		echo $_SESSION['id'];
	}
	echo $_REQUEST['id'];
	
?>