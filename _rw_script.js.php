<?php

	require ( current(explode('wp-content', dirname(__FILE__))).'wp-load.php' );
	header("Content-type: text/javascript");
	if(0){ ?><script type="text/javascript"><?php };
?>
var site_root = '<?php echo get_bloginfo('wpurl'); ?>';
var _rw_taxonomies = <?php echo json_encode($_rw_taxonomies); ?>;
var _rw_content_types = <?php echo json_encode($_rw_content_types); ?>;
var _rw_query = function(){ };
jQuery(function($){
	_rw_query = function(query, _function){
		var json = null;
		var query = query || {};
		$.ajax({
			type: 'POST',
			data: query,
			url:'<?php echo plugins_url('/_rw_query.php', __FILE__); ?>',
			success: function(data){ json = data; },
			//complete: function(data){ console.log(data.responseText); },
			//error: function(c, d, e){ e['query'] = query; console.log(e); },
			//async : false
		});
		
		if(typeof(_function) != 'function')
			return json;
		_function(json); return true;
	}
});