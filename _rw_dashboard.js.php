<?php

	require ( current(explode('wp-content', dirname(__FILE__))).'wp-load.php' );
	header("Content-type: text/javascript");
	$rows = "";

	$rw_content_types = array();
	$rw_taxonomies = array();
	/* create rw_content_types & rw_taxonomies { */
foreach((array) get_posts('post_type=rw_content_type&numberposts=-1') as $k=>$type){	
	$rw_content_types[] = array_merge( (array) $type, 
	get_post_custom_values('arguments',$type->ID) ? array( 'arguments'=> maybe_unserialize(array_shift(get_post_custom_values('arguments',$type->ID)))) : array());
}
foreach((array) get_posts('post_type=rw_taxonomy&numberposts=-1') as $k=>$type){	
	$rw_taxonomies[] = array_merge( (array) $type,
	get_post_custom_values('arguments',$type->ID) ? 
	array( 'arguments'=> maybe_unserialize(array_shift(get_post_custom_values('arguments',$type->ID)))) : array());
}
/* } */

	// Content Types	
	foreach($rw_content_types as $content_type){
		if(in_array($content_type["arguments"]["name-singular"],array("Content Type","Taxonomy"))) continue;
		
		$num_types = wp_count_posts( $content_type["arguments"]["slug"] );
		$num = number_format_i18n( $num_types->publish );
		$text = _n( $content_type["arguments"]["name-singular"] , $content_type["post_title"], intval($num_types->publish) );
		if ( current_user_can( "edit_posts" ) ) {
			$num = '<a href="edit.php?post_type='.$content_type["arguments"]["slug"]."\">$num</a>";
			$text = '<a href="edit.php?post_type='.$content_type["arguments"]["slug"]."\">$text</a>";
		}
		$rows .= '<tr><td class="first b b_pages">' . $num . '</td><td class="t pages">' . $text . "</td></tr>";
	}

	// Taxonomies
	foreach($rw_taxonomies as $taxonomy){
		
		if(in_array($taxonomy["arguments"]["title-singular"],array("Content Type","Taxonomy"))) continue;
		
		$num_taxs = wp_count_terms($taxonomy["arguments"]["slug"]);
		$num = number_format_i18n( $num_taxs );
		$text = _n( $taxonomy["arguments"]["title-singular"] , $taxonomy["post_title"], $num_taxs );
		if ( current_user_can( "edit_posts" ) ) {
			$num = '<a href="edit-tags.php?taxonomy='.$taxonomy["arguments"]["slug"]."\">$num</a>";
			$text = '<a href="edit-tags.php?taxonomy='.$taxonomy["arguments"]["slug"]."\">$text</a>";
		}
		$rows .= '<tr><td class="first b b_pages">' . $num . '</td><td class="t pages">' . $text . "</td></tr>";
	}
	
	if(0){ ?><script type="text/javascript"><?php };
	
?>
jQuery(function($){
	$('#dashboard_right_now .table_content table').append('<?php echo $rows; ?>');
		
	$('#wp-version-message').append('<span> and <strong>Reed Write <?php echo $_GET['ver']; ?></strong>.<span>').html( $('#wp-version-message').html().replace('.<span>','<span>') );
	
	$('<span> with Reed Write</span>').css({'font-style':'italic','font-size':'1em','color':'#999'}).insertAfter('#dashboard_right_now .hndle span');
});