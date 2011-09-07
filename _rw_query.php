<?php

	require( str_replace('wp-content\plugins\reed-write', '', dirname(__FILE__)).'wp-load.php' );
	
	$query = array(
		'showposts' => 5,
		'post_type' => 'project',
		'meta_query' => array(
			array(
				'key' => 'featured',
				'value' => '',
				'compare' => '!='
			)
		)
	);
	
	$_rw_query = _rw_query_posts($query);
	
/*
$posts = query_posts($query);

echo '<pre>'.print_r($posts, 1).'</pre>';

*/

	header('Cache-Control: no-cache, must-revalidate');
	header('Content-type: application/json');
	
	$rows = array();
	
	$posts = query_posts($_POST);
	
	if ( !count($posts) ) { echo '[]'; exit; }
	unset($post);
	foreach($posts as $post){
		$post->post_content = _rw_get_the_content_with_formatting();
		$rows[] = _rw_get_post($post->ID);
	}
	
	echo json_encode( $rows );
	
?>