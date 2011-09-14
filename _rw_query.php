<?php

	require ( current(explode('wp-content', dirname(__FILE__))).'wp-load.php' );
	
	header('Cache-Control: no-cache, must-revalidate');
	header('Content-type: application/json');
	//header('Content-type: text/html');
	
	$_rw_query = _rw_query_posts($_POST);//$_GET);
	//echo '<pre>'.print_r($_rw_query, 1).'</pre>'; exit;
	
	echo json_encode( $_rw_query );
	
?>