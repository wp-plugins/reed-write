<?php
/**
 * @package reed-write
 * @version 0.2.5
 */
/*
Plugin Name: Reed Write
Plugin URI: http://scottreeddesign.com/project/reed-write-wordpress-plugin/
Description: Reed Write is a WordPress plugin that helps you create custom content types in WordPress. It allows for custom categories, custom tags, and custom input fields.
Author: Brian S. Reed
Version: 0.2.5
Author URI: http://scottreeddesign.com/
*/

if($_GET['page'] == 'more_content_menu'){
	header('Location: '.get_bloginfo('url').'/wp-admin/edit.php?post_type=content-type');
	exit;
}

if(substr_count($_SERVER["REQUEST_URI"],"admin.php?page=edit.php?post_type=") || substr_count($_SERVER["REQUEST_URI"],"admin.php?page=post-new.php?post_type=")){
	header("Location: ".str_replace('admin.php?page=', '', $_SERVER["REQUEST_URI"]));
	exit;
}

$post_type = is_object($post) ? $post->post_type :
	(isset($_GET['post']) ? get_post_type($_GET['post']) :
		(isset($_GET['post_type']) ? $_GET['post_type'] : '')
	);

add_action('admin_head', 'plugin_header');
function plugin_header() { ?><style type="text/css">#adminmenu li div.wp-menu-image{overflow:hidden}#adminmenu li div.wp-menu-image img{margin-top:-32px;opacity:1;filter:alpha(opacity=100)}#adminmenu li.wp-has-current-submenu div.wp-menu-image img,#adminmenu li:hover div.wp-menu-image img{margin-top:0}#icon-more_content_menu{background: transparent url(/wp-admin/images/icons32.png?ver=20100531) no-repeat -492px -5px;
}</style>
<?php }

foreach((array) get_posts('post_type=content-type&numberposts=-1') as $k=>$type)
	$content_types[$k] = array_merge( (array) $type, array( 'arguments'=> maybe_unserialize(array_shift(get_post_custom_values('arguments',$type->ID)))));

	# echo '<pre>'.print_r($content_types, 1).'</pre>';

foreach((array) get_posts('post_type=taxonomy&numberposts=-1') as $k=>$type)
	$taxonomies[$k] = array_merge( (array) $type, array( 'arguments'=> maybe_unserialize(array_shift(get_post_custom_values('arguments',$type->ID)))));

# init {
	add_action('init', 'init_reed_write');

	function init_reed_write(){
		global $content_types, $taxonomies;

		$plugin_dir = WP_PLUGIN_DIR.'/reed-write/';

		# default post types {
			if(current_user_can('activate_plugins'))
				$content_types = array_merge(
					array(
						array(
							'post_title' => 'Reed Write',
							'arguments' => array(
								'slug' => 'content-type',
								'name-singular' => 'Content Type',
								//'description' => 'Reed Write Description Hello',
								'public' => true,
								'publicly_queryable' => true,
								'exclude_from_search' => true,
								'show_ui' => true,
								'show_in_menu' => true,
								'menu_position' => 62,
								'menu_icon' => 'content.png',
								'capability_type' => 'post',
								'hierarchical' => false,
								'supports' => array('no-title'),
								//'register_meta_box_cb' => $type['arguments']['register_meta_box_cb'],
								//'taxonomies' => $type['arguments']['taxonomies'],
								//'has_archive' => $type['arguments']['has_archive'],
								'rewrite' => false,
								'query_var' => 'content-type',
								'can_export' => false,
								'show_in_nav_menus' => false
							),
							'fields' => array(
								array(
									'name' => 'Settings',
									'slug' => 'arguments',
									'type' => '_content_type_settings'
								),
								array(
									'name' => 'Fields',
									'type' => '_content_type_fields'
								)
							)
						),
						array(
							'post_title' => 'Taxonomies',
							'arguments' => array(
								'slug' => 'taxonomy',
								'name-singular' => 'Taxonomy',
								//'description' => 'Taxonomies Description Hello',
								'public' => true,
								'publicly_queryable' => true,
								'exclude_from_search' => true,
								'show_ui' => true,
								'show_in_menu' => true,
								'menu_position' => 63,
								'menu_icon' => 'taxonomy.png',
								'capability_type' => 'post',
								'hierarchical' => false,
								'supports' => array('no-title'),
								//'register_meta_box_cb' => $type['arguments']['register_meta_box_cb'],
								//'taxonomies' => $type['arguments']['taxonomies'],
								//'has_archive' => $type['arguments']['has_archive'],
								'rewrite' => false,
								'query_var' => 'content-type',
								'can_export' => false,
								'show_in_nav_menus' => false
							),
							'fields' => array(
								array(
									'name' => 'Settings',
									'slug' => 'arguments',
									'type' => '_taxonomy_settings'
								)
							)
						)
						//,array(
//							'post_title' => 'Views',
//							'arguments' => array(
//								'slug' => 'view',
//								'name-singular' => 'View',
//								//'description' => 'Taxonomies Description Hello',
//								'public' => true,
//								'publicly_queryable' => true,
//								'exclude_from_search' => true,
//								'show_ui' => true,
//								'show_in_menu' => true,
//								'menu_position' => 64,
//								'menu_icon' => 'view.png',
//								'capability_type' => 'post',
//								'hierarchical' => false,
//								'supports' => array('no-title'),
//								//'register_meta_box_cb' => $type['arguments']['register_meta_box_cb'],
//								//'taxonomies' => $type['arguments']['taxonomies'],
//								//'has_archive' => $type['arguments']['has_archive'],
//								'rewrite' => false,
//								'query_var' => 'view',
//								'can_export' => false,
//								'show_in_nav_menus' => false
//							),
//							'fields' => array()
//						)
					),
					(is_array($content_types) ? $content_types : array())
				);
		#}
			$content_types = is_array($content_types) ? $content_types : array();

		# register post types {
			$post_updated_messages = array();
			foreach($content_types as $type)
			{
				$arguments = array_merge($type['arguments'], array(
					'label' => $type['post_title'],
					'labels' => array(
						'name' => _x($type['post_title'], 'post type general name'),
						'singular_name' => _x($type['arguments']['name-singular'], 'post type singular name'),
						'add_new' => _x('Add New', strtolower($type['arguments']['name-singular'])),
						'add_new_item' => __('Add New '.$type['arguments']['name-singular']),
						'edit_item' => __('Edit '.$type['arguments']['name-singular']),
						'new_item' => __('New '.$type['arguments']['name-singular']),
						'view_item' => __('View '.$type['arguments']['name-singular']),
						'search_items' => __('Search '.$type['post_title']),
						'not_found' => __('No '.strtolower($type['post_title']).' found'),
						'not_found_in_trash' => __('No '.strtolower($type['post_title']).' found in Trash'),
						'parent_item_colon' => '',
						'menu_name' => $type['post_title']
					),
					'description' => $type['arguments']['description'],
					'public' => (bool) $type['arguments']['public'],
					'publicly_queryable' => (bool) $type['arguments']['publicly_queryable'],
					'exclude_from_search' => (bool) $type['arguments']['exclude_from_search'],
					'show_ui' => (bool) $type['arguments']['show_ui'],
					'show_in_menu' => isset($type['arguments']['show_in_menu']) ? (bool) $type['arguments']['show_in_menu'] : true,
					'menu_position' => trim($type['arguments']['menu_position']) ? (int) $type['arguments']['menu_position'] : 6,
					'menu_icon' => 
					file_exists($plugin_dir.'icons/'.$type['arguments']['menu_icon']) && trim($type['arguments']['menu_icon'])?
						plugins_url('/icons/'.$type['arguments']['menu_icon'], __FILE__) :
							plugins_url('/icons/letter-'.$type['arguments']['slug'][0].'.png', __FILE__),
					'capability_type' => $type['arguments']['capability_type'],
					'hierarchical' => (bool) $type['arguments']['hierarchical'],
					'supports' => (array) $type['arguments']['supports'],
					'register_meta_box_cb' => $type['arguments']['register_meta_box_cb'],
					'taxonomies' => (array) $type['arguments']['taxonomies'],
					'has_archive' => $type['arguments']['slug'],
					'query_var' => $type['arguments']['slug'],
					'can_export' => (bool) $type['arguments']['can_export'],
					'show_in_nav_menus' => (bool) $type['arguments']['show_in_nav_menus'],
				));

				$arguments['rewrite'] = false;
				if($arguments['rewrite_type'] == '1')
					$arguments['rewrite'] = 
						array('slug'=>$type['arguments']['slug'],'with_front'=>false);
				elseif($arguments['rewrite_type'] == '2')
					$arguments['rewrite'] = 
						array('slug'=>$type['arguments']['rewrite_slug'],'with_front'=>(bool) $type['arguments']['rewrite_with_front']);

				if(!in_array('no-title',$arguments['supports']))
					$arguments['supports'][] = 'title';

				if($type['arguments']['labels-custom'])
					foreach(array('add_new','add_new_item','edit_item','new_item','view_item','search_items','not_found','not_found_in_trash','parent_item_colon','menu_name') as $label)
						$arguments['labels'][$label] = __($type['arguments']['labels-'.$label]);

				//echo '<pre>'.print_r($type['arguments']['taxonomies'], 1).'</pre>';
				
				$post_updated_messages[$type['arguments']['slug']] = array(
					'', // Unused. Messages start at index 1.
					sprintf( __($type['arguments']['name-singular'].' updated. <a href="%s">View '.strtolower($type['arguments']['name-singular']).'</a>'), esc_url( get_permalink($post_ID) ) ),
					__('Custom field updated.'),
					__('Custom field deleted.'),
					__($type['arguments']['name-singular'].' updated.'),
					/* translators: %s: date and time of the revision */
					isset($_GET['revision']) ? sprintf( __('Book restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
					sprintf( __($type['arguments']['name-singular'].' published. <a href="%s">View book</a>'), esc_url( get_permalink($post_ID) ) ),
					__($type['arguments']['name-singular'].' saved.'),
					sprintf( __($type['arguments']['name-singular'].' submitted. <a target="_blank" href="%s">Preview book</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
					sprintf( __($type['arguments']['name-singular'].' scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview book</a>'),
					// translators: Publish box date format, see http://php.net/date
					date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
					sprintf( __($type['arguments']['name-singular'].' draft updated. <a target="_blank" href="%s">Preview book</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
				);
					
				register_post_type($type['arguments']['slug'], $arguments);

			}
		#}

		# register taxonomies {
			foreach($taxonomies as $tax)
			{
				$name_singular = $tax['arguments']['name-singular'];
				$slug = $tax['arguments']['slug'];
				$arguments = array(
					'labels' => array(
						'name' => _x( $tax['post_title'], 'taxonomy general name' ),
						'singular_name' => _x( $name_singular, 'taxonomy singular name' ),
						'search_items' =>  __( 'Search '.$tax['post_title'] ),
						'popular_items' => __( 'Popular '.$tax['post_title'] ),
						'all_items' => __( 'All '.$tax['post_title'] ),
						'parent_item' => null,
						'parent_item_colon' => null,
						'edit_item' => __( 'Edit '.$name_singular ), 
						'update_item' => __( 'Update '.$name_singular ),
						'add_new_item' => __( 'Add New '.$name_singular ),
						'new_item_name' => __( 'New '.$name_singular.' Name' ),
						'separate_items_with_commas' => __( 'Separate '.strtolower($tax['post_title']).' with commas' ),
						'add_or_remove_items' => __( 'Add or remove '.strtolower($tax['post_title']) ),
						'choose_from_most_used' => __( 'Choose from the most used '.strtolower($tax['post_title']) ),
						'menu_name' => __( $tax['post_title'] ),
					)		
				);

				foreach(array('public','show_in_nav_menus','show_ui','show_tagcloud','hierarchical')
					as $key){
					$arguments[$key] = in_array($key, $tax['arguments']['options']) ? true : false;
				}

				$arguments['rewrite'] = false;
				if($tax['arguments']['rewrite_type'] == '1')
					$arguments['rewrite'] = array(
						'slug'=>$tax['arguments']['slug'],
						'with_front'=>false,
						'hierarchical'=>false
					);
				elseif($tax['arguments']['rewrite_type'] == '2')
					$arguments['rewrite'] = array(
						'slug'=>$tax['arguments']['rewrite_slug'],
						'with_front'=>(bool) $tax['arguments']['rewrite_with_front'],
						'hierarchical'=>(bool) $tax['arguments']['rewrite_hierarchical']
					);

				//				echo '<pre>'.print_r($tax['arguments'], 1).'</pre>';

				register_taxonomy(
					str_replace('-','_',sanitize_title_with_dashes($name_singular)),
					$tax['arguments']['content-types'],
					$arguments
				);
			}
		#}
		flush_rewrite_rules();
	}
#}
add_action('admin_menu', 'admin_menu_reed_write');
function admin_menu_reed_write(){

	global $submenu,$menu,$content_types,$more_contents;

	foreach($content_types as $type)
		if($type['arguments']['menu_type'] == '2')
			$more_contents[] = $type;

	if(count($more_contents) == 0) return false;

	add_menu_page(__('More Content'), __('More Content'), 'edit_themes', 'more_content_menu', 'more_content_menu_render',
		plugins_url('/icons/settings.png', __FILE__) , 25);

	foreach($more_contents as $type){
		remove_menu(&$menu, $type['post_title']);
		add_submenu_page('more_content_menu', __($type['post_title']), __($type['post_title']), 'edit_themes',
			'edit.php?post_type='.$type['arguments']['slug'], 'more_content_redirect');
		add_submenu_page('more_content_menu', __('Add '.$type['arguments']['name-singular']), __('Add '.$type['arguments']['name-singular']), 'edit_themes',
			'post-new.php?post_type='.$type['arguments']['slug'], 'more_content_redirect');
		unset($submenu['edit.php?post_type='.$type['arguments']['slug']]);
	}

	function more_content_menu_render(){
		global $title;

		//require_once(WP_PLUGIN_DIR.'/reed-write/more-content.php');
	}

	function more_content_redirect(){
	}
	//echo '<pre>'.print_r($menu, 1).'</pre>';
}
# admin init {
	add_action('admin_init', 'admin_init_reed_write');
	function admin_init_reed_write(){
		wp_enqueue_script('jquery');
		global $post_type, $content_types, $taxonomies;

		$current_post = array();
		$plugin_dir = WP_PLUGIN_DIR.'/reed-write/field_types/';
		foreach((array) $content_types as $type)
		if($post_type == $type['arguments']['slug']){
			$meta_fields = array();
			$required_fields = false;
			$fields = is_array($type['fields']) ? $type['fields'] : 
			(array) maybe_unserialize(array_shift(get_post_custom_values('fields',$type["ID"])));
			//echo '<pre>'.print_r($fields, 1).'</pre>';
			require_once($plugin_dir.'default.php');
			foreach((array) $fields as $key=>$field){
				$field['slug'] = array_key_exists('slug', (array) $field) ?
					$field['slug'] : sanitize_title_with_dashes($field['name']);

				if(file_exists($plugin_dir.$field['type'].'.php')){
					require_once($plugin_dir.$field['type'].'.php');
				}elseif(file_exists($plugin_dir.$field['type'].'/'.$field['type'].'.php')){
					require_once($plugin_dir.$field['type'].'/'.$field['type'].'.php');
				}

				$meta_fields[] = $field['slug'].'='.$field['type'];
				$field_title = $field['name'];
				if(array_key_exists('required',$field)) {
					$required_fields = true;
					$field_title = '<span title="This field is required."><span class="title">'.$field_title.'</span><span style="color: red;">*</span></span>';
				}
				if(count($fields) == count($meta_fields)){
					$field['meta_fields'] = implode('&', $meta_fields);
					$field['required_fields'] = $required_fields;
				}
				add_meta_box($field['slug'].'-meta', $field_title, 'meta_box_inside', $type['arguments']['slug'], "normal", "low", $field);
			}
			break;
		}
		//	Input building function echoing actual html
		function meta_box_inside($post, $metabox){
			$field = $metabox['args'];
			$field_value = get_post_custom_values($field['slug'], $post->ID);
			$field['value'] = $field_value[0];

			$test_function = function_exists('field_type_edit_'.$field['type']) ?
				'field_type_edit_'.$field['type'] : 'field_type_edit_default';
			try {
				$test_function($field);
			}catch(Exception $e){
				field_type_edit_default($field);
			}
			if($field['description'])
				echo '<p>'.$field['description'].'</p>';

			if($field['meta_fields'])
				echo '<input type="hidden" style="display: none;" name="all_meta_fields" value="'.$field['meta_fields'].'"  />';

			if($field['required_fields'])
				echo '<script type="text/javascript" src="'.plugins_url('/field-verification.js', __FILE__).'"></script>';

		}
		//	Get Value Range
		function setup_value_range_array($field){
			$options = array();
			if(!isset($field['type_option_data']))
				return $options;
			if(substr_count($field['type_option_data'], "\r")){
				foreach(explode("\r", $field['type_option_data']) as $v)
					if(trim($v)) $options[sanitize_title_with_dashes($v)] = trim($v);
				return $options;
			}
			$arguments = $field['type_option_data'].((substr_count($field['type_option_data'],'numberposts')) ? "" : '&numberposts=-1');
			$get_posts = get_posts($arguments);
			if(is_array($get_posts))
			foreach($get_posts as $_post)
				$options[$_post->ID] =  $_post->post_title;
			return $options;
		}
	}
#}
# save post {
	add_action('save_post', 'save_fields');
	function save_fields(){
		global $post;
		#echo '<pre>'.print_r($_POST, 1).'</pre>';exit;
		$plugin_dir = WP_PLUGIN_DIR.'/reed-write/field_types/';
		foreach(explode('&', $_POST['all_meta_fields']) as $name_type){
			list($name, $type) = explode('=',$name_type);
			$field_type_save_function = 'field_type_save_'.$type;

			#echo '<pre>'.print_r("$plugin_dir$type.php", 1).'</pre>';exit;
			if(file_exists("$plugin_dir$type.php"))
				require_once("$plugin_dir$type.php");
			elseif(file_exists("$plugin_dir$type/$type.php"))
				require_once("$plugin_dir$type/$type.php");

			if(function_exists($field_type_save_function) and !array_key_exists($name.'_save_posted', $_POST)){
				$_POST[$name.'_save_posted'] = true;
				$_POST[$name] = $field_type_save_function($_POST[$name]);
			}
			update_post_meta($post->ID, $name, $_POST[$name]);
		}
	}
#}

function remove_menu($menu, $title){
	foreach($menu as $k=>$item){
		if($item[0] == $title)
			unset($menu[$k]);
	}
}
function value_type($v,$tv,$t){
	echo " value=\"$v\" ".($v==$tv ? "$t=\"$t\" " : "");
}
?>