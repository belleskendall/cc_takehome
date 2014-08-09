<?php
	require_once 'functions.php';
	$card = new card();
	if(isset($_GET['issuer'])) {
		print_r($card->get_customers($_GET['issuer']));
		unset($card);
	} else {
		unset($card);
		header('Location: index2.html');
	}
?>
