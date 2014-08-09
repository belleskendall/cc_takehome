<?php
	require_once 'functions.php';
	$card = new card();
	if(isset($_GET['id'])) {
		print_r($card->get_card($_GET['id']));
		unset($card);
	} else {
		unset($card);
		header('Location: index.html');
	}
?>
