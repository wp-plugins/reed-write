<?php
/**
 * @package reed-write
 * @version 1.5.6
 */
/*
Plugin Name: Reed Write
Plugin URI: http://reedwrite.scottreeddesign.com/
Description: Reed Write is a WordPress plugin that helps you create custom content types in WordPress. It allows for custom categories, custom tags, and custom input fields.
Author: Brian S. Reed
Version: 1.5.6
Author URI: http://scottreeddesign.com/
*/
$_rw_version = '1.5.6';

# redirects {
	if($_GET['page'] == 'more_content_menu'){
		header('Location: '.get_bloginfo('url').'/wp-admin/edit.php?post_type=rw_content_type');
		exit;
	}
	if(substr_count($_SERVER["REQUEST_URI"],"admin.php?page=edit.php?post_type=") || substr_count($_SERVER["REQUEST_URI"],"admin.php?page=post-new.php?post_type=")){
		header("Location: ".str_replace('admin.php?page=', '', $_SERVER["REQUEST_URI"]));
		exit;
	}
#}

# set $_rw_post_type {
$_rw_post_type = is_object($post) ? $post->post_type : (isset($_GET['post']) ? get_post_type($_GET['post']) :
		(isset($_GET['post_type']) ? $_GET['post_type'] : '')
	);
#}

# set $_rw_post {
global $post;
$_rw_post = is_object($post) ? $post : 
	(isset($_GET['post']) ? get_post($_GET['post']) : (object) array());
#}

# set rw_content_types & rw_taxonomies {
	$_rw_content_types = array();
	$_rw_taxonomies = array();
	foreach((array) get_posts('post_type=rw_content_type&numberposts=-1') as $_rw_type){
		$_rw_args = get_post_custom_values('arguments',$_rw_type->ID) ? 
			maybe_unserialize(array_shift(get_post_custom_values('arguments',$_rw_type->ID))) : array();
		$_rw_fields_ = get_post_custom_values('fields',$_rw_type->ID) ? 
			maybe_unserialize(array_shift(get_post_custom_values('fields',$_rw_type->ID))) : array();
		$_rw_fields = array();
		if(is_array($_rw_fields_))
		foreach($_rw_fields_ as $_rw_field) $_rw_fields[$_rw_field['slug']] = $_rw_field;
		$_rw_content_types[$_rw_args['slug']] = array_merge((array) $_rw_type,
			array('arguments'=>$_rw_args,'fields'=>$_rw_fields) );}
	foreach((array) get_posts('post_type=rw_taxonomy&numberposts=-1') as $_rw_type){	
		$_rw_args = get_post_custom_values('arguments',$_rw_type->ID) ? 
			maybe_unserialize(array_shift(get_post_custom_values('arguments',$_rw_type->ID))) : array();
		$_rw_taxonomies[] = array_merge( (array) $_rw_type, array( 'arguments'=> $_rw_args));}
	
#}

# plugin activate {
	register_activation_hook( __FILE__, '_rw_plugin_activate' );
	function _rw_plugin_activate() {
		if(is_plugin_active('content-types-wordpress-plugin/content-types-wordpress-plugin.php')){
			$_rw_upgrade = str_replace('/wp-admin/plugins.php','/wp-content/plugins/reed-write/upgrade.php',$_SERVER['SCRIPT_NAME']);
			
			$_rw_script = str_replace('plugins.php','load-scripts.php?c=1&load=jquery',$_SERVER['SCRIPT_NAME']);		
			?>
	
	<span style="font-family: sans-serif; font-size: 12px;" id="output">You have the Content Types plugin installed. This plugin has been depreciated. You can convert the data built by that plugin and use it with this Reed Write plugin. To convert the old plugin data and uninstall the older plugin <a href="#" style="color: #21759B;">Click Here</a>.</span> 
	<script src="<?php echo $_rw_script; ?>" type="text/javascript"></script> 
	<script type="text/javascript">
	jQuery(function($){
		$('a').click(function(){
			$.get('<?php echo $_rw_upgrade; ?>', function(output){
				$('#output').html('Reloading...');
				if(output == 'success')
					window.parent.document.location = $(window.parent.document).find('#reed-write .activate a').attr('href');
			});	
			return false;
		});
	});
	</script>
	<?php exit; }
	}
#}

# admin init {
	add_action('add_meta_boxes', '_rw_add_meta_boxes_init');
	function _rw_add_meta_boxes_init(){
		wp_enqueue_script('jquery');
		global $_rw_post, $_rw_post_type, $_rw_content_types, $_rw_taxonomies;
		if(!$_rw_post_type) return;
		$_rw_plugin_dir = WP_PLUGIN_DIR.'/reed-write/field_types/';		
		$_rw_type = $_rw_content_types[$_rw_post_type];
		$_rw_current_post = array();
		$_rw_meta_fields = array();
		$_rw_required_fields = array();
		if(is_array($_rw_type['fields']))
			$_rw_fields = $_rw_type['fields'];
		elseif(get_post_custom_values('fields',$_rw_type["ID"]))
			$_rw_fields = maybe_unserialize(array_shift(get_post_custom_values('fields',$_rw_type["ID"])));
//echo '<pre>'.print_r(array('_rw_fields'=>$_rw_fields), 1).'</pre>';//exit;
		require_once($_rw_plugin_dir.'default.php');
		foreach((array) $_rw_fields as $_rw_key=>$_rw_field){

			if(file_exists($_rw_plugin_dir.$_rw_field['type'].'.php')){
				require_once($_rw_plugin_dir.$_rw_field['type'].'.php');
			}elseif(file_exists($_rw_plugin_dir.$_rw_field['type'].'/'.$_rw_field['type'].'.php')){
				require_once($_rw_plugin_dir.$_rw_field['type'].'/'.$_rw_field['type'].'.php');
			}

			$_rw_meta_fields[] = $_rw_field['slug'].'='.$_rw_field['type'];
			$_rw_field_title = $_rw_field['name'];
			if(array_key_exists('required',$_rw_field)) {
				$_rw_required_fields[] = "{title:'".str_replace("'","\'",$_rw_field['name'])."',slug:'".$_rw_field['slug']."',type:'".$_rw_field['type']."'}";
				$_rw_field_title = '<span title="This field is required."><span class="title">'.$_rw_field_title.'</span> <span style="color: red;">*</span></span>';
			}
			
			if(count($_rw_fields) == count($_rw_meta_fields)){
				$_rw_field['meta_fields'] = implode('&', $_rw_meta_fields);
				$_rw_field['required_fields'] = '['.implode(',', $_rw_required_fields).']';
			}
	//	echo '<pre>'.print_r(array('field'=>$_rw_field, $_rw_field_title, $_rw_type['arguments']['slug']), 1).'</pre>';
			add_meta_box(str_replace('_','-',$_rw_field['slug']).'-meta', $_rw_field_title, '_rw_meta_box_inside', $_rw_type['arguments']['slug'], "normal", "low", $_rw_field);
		}
	
		
		//	Input building function echoing actual html
		function _rw_meta_box_inside($_rw_post, $_rw_metabox){
			$_rw_field = $_rw_metabox['args'];
			$_rw_field_value = get_post_custom_values($_rw_field['slug'], $_rw_post->ID);			
			$_rw_field['value'] = $_rw_field_value[0];

			$_rw_test_function = function_exists('_rw_field_edit_'.$_rw_field['type']) ?
				'_rw_field_edit_'.$_rw_field['type'] : '_rw_field_edit_default';
			try {
				$_rw_test_function($_rw_field);
			}catch(Exception $_rw_e){
				_rw_field_edit_default($_rw_field);
			}
			if($_rw_field['description'])
				echo '<p>'.$_rw_field['description'].'</p>';

			if($_rw_field['meta_fields'])
				echo '<input type="hidden" style="display: none;" id="all_meta_fields" name="all_meta_fields" value="'.$_rw_field['meta_fields'].'"  />';
				
			if($_rw_field['required_fields'])
				echo '<input type="hidden" style="display: none;" id="required_meta_fields" name="required_meta_fields" value="'.$_rw_field['required_fields'].'"  /><script type="text/javascript" src="'.plugins_url('/_rw_post.js', __FILE__).'"></script>';
								
		}
	}
#}

# fix custom menu icons { 
	add_action('admin_head', '_rw_plugin_header');
	function _rw_plugin_header() { ?><link rel="stylesheet" href="<?php echo plugins_url('/_rw_admin.css.php', __FILE__); ?>" type="text/css" media="all" /><?php }
#}

# add dashboard script {
	add_action('wp_dashboard_setup', 'add_rw_dashboard_customs' );
	function add_rw_dashboard_customs() { global $_rw_version;
		wp_enqueue_script('add_rw_dashboard_custom_js', plugins_url('/_rw_dashboard.js.php', __FILE__),array('jquery'), $_rw_version);
	}
#}

# init {
	
	add_action('init', '_rw_init');
	function _rw_init(){
		global $_rw_content_types, $_rw_taxonomies;

		$_rw_plugin_dir = WP_PLUGIN_DIR.'/reed-write/';

		# default post types {
			if(current_user_can('activate_plugins'))
				$_rw_content_types = array_merge(
					!get_option( "_rw_option_admin_menu_on", true ) ? array() : 
					array(
					'rw_content_type' => array(
							'post_title' => 'Content Types',
							'arguments' => array(
								'slug' => 'rw_content_type',
								'name-singular' => 'Content Type',
								//'description' => 'Reed Write Description Hello',
								'public' => true,
								'publicly_queryable' => true,
								'exclude_from_search' => false,
								'show_ui' => true,
								'show_in_menu' => true,
								'menu_position' => 70,
								'menu_icon' => 'content.png',
								'capability_type' => 'post',
								'hierarchical' => false,
								'supports' => array('no-title'),
								'rewrite' => false,
								'query_var' => 'rw_content_type',
								'can_export' => false,
								'show_in_nav_menus' => false
							),
							'fields' => array(
								'arguments'=>array(
									'name' => 'Settings',
									'slug' => 'arguments',
									'type' => '_content_type_settings'
								),
								'fields' =>	array(
									'name' => 'Fields',
									'slug' => 'fields',
									'type' => '_content_type_fields'
								)
							)
						),
					'rw_taxonomy' => array(
							'post_title' => 'Taxonomies',
							'arguments' => array(
								'slug' => 'rw_taxonomy',
								'name-singular' => 'Taxonomy',
								'public' => true,
								'publicly_queryable' => true,
								'exclude_from_search' => false,
								'show_ui' => true,
								'show_in_menu' => true,
								'menu_position' => 70,
								'menu_icon' => 'taxonomy.png',
								'capability_type' => 'post',
								'hierarchical' => false,
								'supports' => array('no-title'),
								'rewrite' => false,
								'query_var' => 'rw_taxonomy',
								'can_export' => false,
								'show_in_nav_menus' => false
							),
							'fields' => array(
								'arguments' => array(
									'name' => 'Settings',
									'slug' => 'arguments',
									'type' => '_taxonomy_settings'
								)
							)
						)
					),
					$_rw_content_types
				);
		#}

		# register post types {
			$_rw_post_updated_messages = array();
			foreach($_rw_content_types as $_rw_type)
			{		
				if(in_array($_rw_type['arguments']['slug'], array('post','page','attachment','revision','nav_menu')))
					continue;
						
				$_rw_arguments = array_merge(
				$_rw_type['arguments'] ? $_rw_type['arguments'] : array(), array(
					'label' => $_rw_type['post_title'],
					'labels' => array(
						'name' => _x($_rw_type['post_title'], 'post type general name'),
						'singular_name' => _x($_rw_type['arguments']['name-singular'], 'post type singular name'),
						'add_new' => _x('Add New', strtolower($_rw_type['arguments']['name-singular'])),
						'add_new_item' => __('Add New '.$_rw_type['arguments']['name-singular']),
						'edit_item' => __('Edit '.$_rw_type['arguments']['name-singular']),
						'new_item' => __('New '.$_rw_type['arguments']['name-singular']),
						'view_item' => __('View '.$_rw_type['arguments']['name-singular']),
						'search_items' => __('Search '.$_rw_type['post_title']),
						'not_found' => __('No '.strtolower($_rw_type['post_title']).' found'),
						'not_found_in_trash' => __('No '.strtolower($_rw_type['post_title']).' found in Trash'),
						'parent_item_colon' => '',
						'menu_name' => $_rw_type['post_title']
					),
					'description' => $_rw_type['arguments']['description'],
					'public' => (bool) $_rw_type['arguments']['public'],
					'publicly_queryable' => (bool) $_rw_type['arguments']['publicly_queryable'],
					'exclude_from_search' => (bool) $_rw_type['arguments']['exclude_from_search'],
					'show_ui' => (bool) $_rw_type['arguments']['show_ui'],
					'show_in_menu' => isset($_rw_type['arguments']['show_in_menu']) ? (bool) $_rw_type['arguments']['show_in_menu'] : true,
					'menu_position' => trim($_rw_type['arguments']['menu_position']) ? (int) $_rw_type['arguments']['menu_position'] : 6,
					'menu_icon' => 
					file_exists($_rw_plugin_dir.'icons/'.$_rw_type['arguments']['menu_icon']) && trim($_rw_type['arguments']['menu_icon'])?
						plugins_url('/icons/'.$_rw_type['arguments']['menu_icon'], __FILE__) :
							plugins_url('/icons/letter-'.$_rw_type['arguments']['slug'][0].'.png', __FILE__),
					'capability_type' => $_rw_type['arguments']['capability_type'],
					'hierarchical' => (bool) $_rw_type['arguments']['hierarchical'],
					'supports' => (array) $_rw_type['arguments']['supports'],
					'register_meta_box_cb' => $_rw_type['arguments']['register_meta_box_cb'],
					'has_archive' => $_rw_type['arguments']['slug'],
					'query_var' => $_rw_type['arguments']['slug'],
					'can_export' => (bool) $_rw_type['arguments']['can_export'],
					'show_in_nav_menus' => (bool) $_rw_type['arguments']['show_in_nav_menus'],
				));

				$_rw_arguments['rewrite'] = false;
				if($_rw_arguments['rewrite_type'] == '1')
					$_rw_arguments['rewrite'] = 
						array('slug'=>$_rw_type['arguments']['slug'],'with_front'=>false);
				elseif($_rw_arguments['rewrite_type'] == '2')
					$_rw_arguments['rewrite'] = 
						array('slug'=>$_rw_type['arguments']['rewrite_slug'],'with_front'=>(bool) $_rw_type['arguments']['rewrite_with_front']);

				if(!in_array('no-title',$_rw_arguments['supports']))
					$_rw_arguments['supports'][] = 'title';

				if($_rw_type['arguments']['labels-custom'])
					foreach(array('add_new','add_new_item','edit_item','new_item','view_item','search_items','not_found','not_found_in_trash','parent_item_colon','menu_name') as $_rw_label)
						$_rw_arguments['labels'][$_rw_label] = __($_rw_type['arguments']['labels-'.$_rw_label]);
			
				register_post_type($_rw_type['arguments']['slug'], $_rw_arguments);

			}
			
			add_filter('post_updated_messages', '_rw_post_updated_messages');
			function _rw_post_updated_messages($messages){
				global $post, $post_ID, $_rw_content_types;
				foreach($_rw_content_types as $_rw_type){
					$name = strtolower($_rw_type['arguments']['name-singular']);				
					$view = ' <a href="%s">View '.$name.'</a>';
					$preview = ' <a target="_blank" href="%s">Preview '.$name.'</a>';
					if(substr($_rw_type['arguments']['slug'], 0, 3) == 'rw_'){
						$view = '<!-- %s -->';
						$preview = '<!-- %s -->';
					}
					$messages[$_rw_type['arguments']['slug']] = array(
						0 => '', // Unused. Messages start at index 1.
						1 => sprintf( __(ucwords($name).' updated.'.$view), esc_url( get_permalink($post_ID) ) ),
						2 => __('Custom field updated.'),
						3 => __('Custom field deleted.'),
						4 => __(ucwords($name).' updated.'),
						/* translators: %s: date and time of the revision */
						5 => isset($_GET['revision']) ? sprintf( __(ucwords($name).' restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
						6 => sprintf( __(ucwords($name).' published. <a href="%s">View '.$name.'</a>'), esc_url( get_permalink($post_ID) ) ),
						7 => __(ucwords($name).' saved.'),
						8 => sprintf( __(ucwords($name).' submitted. <a target="_blank" href="%s">Preview '.$name.'</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
						9 => sprintf( __(ucwords($name).' scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview '.$name.'</a>'),
						// translators: Publish box date format, see http://php.net/date
						date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
						10 => sprintf( __(ucwords($name).' draft updated.'.$preview), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
					);
				}				
				return $messages;
			}
		#}

		# register taxonomies {			
			foreach($_rw_taxonomies as $k=>$_rw_tax)
			{
				$_rw_name = $_rw_tax['post_title'];
				$_rw_name_singular = $_rw_tax['arguments']['title-singular'];
				$_rw_slug = $_rw_tax['arguments']['slug'];
				$_rw_arguments = array(
					'labels' => array(
						'name' => _x( $_rw_name, 'taxonomy general name' ),
						'singular_name' => _x( $_rw_name_singular, 'taxonomy singular name' ),
						'search_items' =>  __( 'Search '.$_rw_name ),
						'popular_items' => __( 'Popular '.$_rw_name ),
						'all_items' => __( 'All '.$_rw_name ),
						'parent_item' => null,
						'parent_item_colon' => null,
						'edit_item' => __( 'Edit '.$_rw_name_singular ), 
						'update_item' => __( 'Update '.$_rw_name_singular ),
						'add_new_item' => __( 'Add New '.$_rw_name_singular ),
						'new_item_name' => __( 'New '.$_rw_name_singular.' Name' ),
						'separate_items_with_commas' => __( 'Separate '.strtolower($_rw_name).' with commas' ),
						'add_or_remove_items' => __( 'Add or remove '.strtolower($_rw_name) ),
						'choose_from_most_used' => __( 'Choose from the most used '.strtolower($_rw_name) ),
						'menu_name' => __( $_rw_name ),
					)		
				);

				foreach(array('public','show_in_nav_menus','show_ui','show_tagcloud','hierarchical') as $_rw_key){
					$_rw_arguments[$_rw_key] = 
						in_array($_rw_key, $_rw_tax['arguments']['options']) ? true : false;
				}

				$_rw_arguments['rewrite'] = false;
				if($_rw_tax['arguments']['rewrite_type'] == '1')
					$_rw_arguments['rewrite'] = array(
						'slug'=>$_rw_tax['arguments']['slug'],
						'with_front'=>false,
						'hierarchical'=>false
					);
				elseif($_rw_tax['arguments']['rewrite_type'] == '2')
					$_rw_arguments['rewrite'] = array(
						'slug'=>$_rw_tax['arguments']['rewrite_slug'],
						'with_front'=>(bool) $_rw_tax['arguments']['rewrite_with_front'],
						'hierarchical'=>(bool) $_rw_tax['arguments']['rewrite_hierarchical']
					);
				register_taxonomy(
					$_rw_slug,
					$_rw_tax['arguments']['content-types'],
					$_rw_arguments
				);
			}
		
			foreach($_rw_content_types as $_rw_type)
				foreach((array)$_rw_type['arguments']['taxonomies'] as $_rw_tax_slug)
					register_taxonomy_for_object_type($_rw_tax_slug, $_rw_type['arguments']['slug']);
		#}
		
		if ( is_admin() ){ // admin actions
		
			add_action('admin_menu', 'register_reed_write_settings_page');

			function register_reed_write_settings_page() {
				add_submenu_page( 'options-general.php', 'ReedWrite Settings', 'ReedWrite', 'edit_themes', 'reedwrite-settings', 'reedwrite_settings_callback' ); 
			}
			
			function reedwrite_settings_callback() {
				require('settings.php');
			}
			
		}
		
		
		
	}
#}

# admin_menu {
	
	add_action('admin_menu', '_rw_admin_menu');
	function _rw_admin_menu(){
	
		global $submenu,$menu,$_rw_content_types,$_rw_more_contents;
	
		foreach($_rw_content_types as $_rw_type)
			if($_rw_type['arguments']['menu_type'] == '2')
				$_rw_more_contents[] = $_rw_type;
	
		if(count($_rw_more_contents) == 0) return false;
	
		add_menu_page(__('More Content'), __('More Content'), 'edit_themes', 'more_content_menu', '_rw_more_content_menu_render',
			plugins_url('/icons/settings.png', __FILE__) , 25);
	
		foreach($_rw_more_contents as $_rw_type){
			_rw_remove_menu(&$menu, $_rw_type['post_title']);
			add_submenu_page('more_content_menu', __($_rw_type['post_title']), __($_rw_type['post_title']), 'edit_themes',
				'edit.php?post_type='.$_rw_type['arguments']['slug'], '_rw_more_content_redirect');
			add_submenu_page('more_content_menu', __('Add '.$_rw_type['arguments']['name-singular']), __('Add '.$_rw_type['arguments']['name-singular']), 'edit_themes',
				'post-new.php?post_type='.$_rw_type['arguments']['slug'], '_rw_more_content_redirect');
			unset($submenu['edit.php?post_type='.$_rw_type['arguments']['slug']]);
		}
	
		function _rw_more_content_menu_render(){
			global $title;
			//require_once(WP_PLUGIN_DIR.'/reed-write/more-content.php');
		}
	
		function _rw_more_content_redirect(){
		}
		//echo '<pre>'.print_r($menu, 1).'</pre>';
	}
	function _rw_remove_menu($_rw_menu, $_rw_title){
	foreach($_rw_menu as $_rw_k=>$_rw_item){
		if($_rw_item[0] == $_rw_title)
			unset($_rw_menu[$_rw_k]);
	}
}

	
#}

# save post {
	add_action('save_post', '_rw_save_fields');
	function _rw_save_fields(){
		global $post;
#echo '<pre>'.print_r($_POST, 1).'</pre>';exit;
		$_rw_plugin_dir = WP_PLUGIN_DIR.'/reed-write/field_types/';
		foreach(explode('&', $_POST['all_meta_fields']) as $_rw_slug_type){
			list($_rw_slug, $_rw_type) = explode('=',$_rw_slug_type);
			$_rw__rw_field_save_function = '_rw_field_save_'.$_rw_type;
#echo '<pre>'.print_r($_POST[$name], 1).'</pre>';exit;
			if(file_exists("$_rw_plugin_dir$_rw_type.php"))
				require_once("$_rw_plugin_dir$_rw_type.php");
			elseif(file_exists("$_rw_plugin_dir$_rw_type/$_rw_type.php"))
				require_once("$_rw_plugin_dir$_rw_type/$_rw_type.php");

			if(function_exists($_rw__rw_field_save_function) and !array_key_exists($_rw_slug.'_save_posted', $_POST)){
				$_POST[$_rw_slug.'_save_posted'] = true;
				$_POST[$_rw_slug] = $_rw__rw_field_save_function($_POST[$_rw_slug]);
			}
			update_post_meta($post->ID, $_rw_slug, $_POST[$_rw_slug]);
		}
	}
#}

# content filter {
	
	add_filter( 'the_content', '_rw_content_filter', 20 );
	function _rw_content_filter( $content ) {
		global $post, $_rw_content_types;	
		
		if(in_array($post->post_type, array('post','page','attachment','revision','nav_menu')))
			return $content;

		if ( is_single() && array_key_exists($post->post_type, $_rw_content_types) && $_rw_content_types[$post->post_type]['arguments']['show_fields']){		
			$_rw_type = $_rw_content_types[$post->post_type];
			foreach($_rw_type['fields'] as $_rw_field)
				$_rw_content .= '<h3>'.$_rw_field['name'].'</h3>'._rw_get_field_value($_rw_field, (array) $post, true);				
			$content = "$content<!-- .entry-meta -->\r".'<div class="entry-meta">'.$_rw_content.'</div>';
		}
		
		//$content = $content.'<pre>'.print_r($_rw_content_types, 1).'</pre>';
		
		return $content;
	}

#}

# rw functions {

	function _rw_query_posts($query){
		global $post, $_rw_content_types;
		$old_post = $post;
		$_rw_query = new WP_Query($query);
		$_rw_posts = array();
		if(property_exists($_rw_query, 'posts') && is_array($_rw_query->posts))
		if ( $_rw_query->have_posts() ) { while ( $_rw_query->have_posts() ) : $_rw_query->the_post();
			$_rw_post = (array) $post;
			$_rw_post['post_content_formatted'] = _rw_get_the_content_with_formatting();
			
			foreach((array)$_rw_content_types[$_rw_post['post_type']]['fields'] as $_rw_field)
				$_rw_post[$_rw_field['slug']] = _rw_get_field_value($_rw_field['slug'], $_rw_post);	
									
			$_rw_posts[] = $_rw_post;
			endwhile;
		}else{
			return false;
		}
		$post = $old_post;
		wp_reset_postdata();
		return $_rw_posts;
	}

	function _rw_get_post($_rw_post_id = false){
		global $_rw_content_types;
		//the_post();
		$_rw_post = (array) get_post($_rw_post_id);
		foreach((array)$_rw_content_types[$_rw_post['post_type']]['fields'] as $_rw_field)
			$_rw_post[$_rw_field['slug']] = _rw_get_field_value($_rw_field['slug'], $_rw_post);
		$_rw_post['post_content_formatted'] = _rw_get_the_content_with_formatting();
		return $_rw_post;
	}
	
	function _rw_get_post_raw($_rw_post_id = false, $object = false){
		global $_rw_content_types;
		//the_post();
		$_rw_post = (array) get_post($_rw_post_id);
		foreach((array)$_rw_content_types[$_rw_post['post_type']]['fields'] as $_rw_field)
			$_rw_post[$_rw_field['slug']] = get_post_meta($_rw_post_id, $_rw_field['slug'], 1);
		return $object ? (object)$_rw_post : $_rw_post;
	}

	function _rw_setup_value_range_array($_rw_field){
		$_rw_options = array();
		if(!isset($_rw_field['type_option_data']))
			return $_rw_options;
		if($_rw_field['type_option'] == 'custom'){
			foreach(explode("\r", str_replace("\n","\r",$_rw_field['type_option_data'])) as $_rw_v)
				if(trim($_rw_v)) $_rw_options[sanitize_title($_rw_v)] = trim($_rw_v);
			return $_rw_options;
		}
		$_rw_arguments = $_rw_field['type_option_data'].((substr_count($_rw_field['type_option_data'],'numberposts')) ? "" : '&numberposts=-1');
		$_rw_get_posts = get_posts($_rw_arguments);
		if(is_array($_rw_get_posts))
		foreach($_rw_get_posts as $_rw__post)
			$_rw_options[$_rw__post->ID] =  $_rw__post->post_title;
		return $_rw_options;
	}

	function _rw_get_value_range_array($_rw_field, $values){
		$_rw_options = _rw_setup_value_range_array($_rw_field);
		$_rw_values = array();
		if(is_array($values))
		foreach($values as $value)
			if(array_key_exists($value, $_rw_options))
				$_rw_values[] = $_rw_options[$value];
		return $_rw_values;
	}
	
	function _rw_get_field_value($_rw_field, $_rw_post = false, $wrap = false){
		global $post, $_rw_content_types;
		$_rw_post = $_rw_post ? (array) $_rw_post : (array) $post;
		if(is_string($_rw_field))
			$_rw_field = $_rw_content_types[$_rw_post['post_type']]['fields'][$_rw_field];
		$_rw_plugin_dir = WP_PLUGIN_DIR.'/reed-write/field_types/';
		require_once($_rw_plugin_dir.'default.php');
		if(file_exists($_rw_plugin_dir.$_rw_field['type'].'.php')){
			require_once($_rw_plugin_dir.$_rw_field['type'].'.php');
		}elseif(file_exists($_rw_plugin_dir.$_rw_field['type'].'/'.$_rw_field['type'].'.php')){
			require_once($_rw_plugin_dir.$_rw_field['type'].'/'.$_rw_field['type'].'.php');
		}		
		$_rw_test_function = function_exists('_rw_field_value_'.$_rw_field['type']) ?
		'_rw_field_value_'.$_rw_field['type'] : '_rw_field_value_default';
		
		return $_rw_test_function($_rw_field, $_rw_post, $wrap);
	}

	function _rw_value_type($_rw_v,$_rw_tv,$_rw_t){
		echo " value=\"$_rw_v\" ".($_rw_v==$_rw_tv ? "$_rw_t=\"$_rw_t\" " : "");
	}
	
	function _rw_get_the_content_with_formatting($more_text = '(more...)', $stripteaser = 0, $more_file = ''){
		$content = get_the_content($more_text, $stripteaser, $more_file);
		$content = apply_filters('the_content', $content);
		$content = str_replace(']]>', ']]&gt;', $content);
		return $content;
	}
	
	$_rw_console_log = array();
	function _rw_log($val, $key = false){
		global $_rw_console_log;
		echo '<!-- _rw_log add -->';
		$key = $key ? $key : '_rw_log_' . (count($_rw_console_log)+1);
		$_rw_console_log[$key] = $val;
	}
	
	add_action('wp_footer', '_rw_console_log_footer' );
	function _rw_console_log_footer() {		
		global $_rw_console_log;
		echo '<!-- _rw_log -->';
		foreach($_rw_console_log as $key=>$x)
			echo '<script type="text/javascript">console.log("'.$key.' ->");console.log('.json_encode($x).');</script>';
	}
	
	if(is_admin() || get_option( "_rw_option_load_script", true ))
	wp_enqueue_script('add_rw_javascript_js', plugins_url('/_rw_script.js.php', __FILE__),array('jquery'), $_rw_version);

# }

# fix meta searching {

	add_filter( 'posts_join', '_rw_search_join' );
	function _rw_search_join( $join ) {
		global $wpdb;
		if(!is_search()) return $join;
		$join .= 
		' RIGHT JOIN '.$wpdb->postmeta.' ON '.$wpdb->posts.'.ID = '.$wpdb->postmeta.'.post_id ';
		return $join;
	}
	
	add_filter( 'posts_groupby', '_rw_search_groupby' );
	function _rw_search_groupby( $groupby ){
		global $wpdb;	
		if( !is_search() ) return $groupby;	
		$mygroupby = "{$wpdb->posts}.ID";		
		if( preg_match( "/$mygroupby/", $groupby )) return $groupby; 		
		if( !strlen(trim($groupby))) return $mygroupby;
		return $groupby . ", " . $mygroupby;
	}
	
	add_filter( 'posts_where', '_rw_search_where' );
	function _rw_search_where( $where ) {
		global $wpdb;
		if(!is_search()) return $where;
		$where = preg_replace(
			"/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
			"(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)",
			$where
		);
		return $where;
	}
	
# }
?>