<?php
	require_once 'functions.php';
	$card = new card();
	if('set' == $_GET['ready']) {
		print_r($card->get_issuers());
		unset($card);
	} else {
		unset($card);
		header('Location: index2.html');
	}
?>
