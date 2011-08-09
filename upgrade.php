<?php

	require('../../../wp-load.php');
	require(ABSPATH.'wp-admin/includes/plugin.php');
	
	$switch_keys = array(
		'singular_name'=>'name-singular',
		'publicly_queryable'=>'publicly_queryable',
		'exclude_from_search'=>'exclude_from_search',
		'show_ui'=>'show_ui',
		'can_export'=>'can_export',
		'capability_type'=>'capability_type',
		'menu_position'=>'menu_position'
		);
	
	foreach((array) get_posts('post_type=content-type&numberposts=-1') as $k=>$type){
		$old_settings = maybe_unserialize(array_shift(get_post_custom_values('settings',$type->ID)));
		$old_fields = maybe_unserialize(array_shift(get_post_custom_values('fields',$type->ID)));
		$slug = sanitize_title($old_settings['singular_name']);
		$arguments = array(
			'slug' => $slug,
			'menu_type' => 1,
			'menu_icon' => 'letter-'.strtolower($old_settings['singular_name'][0]).'.png',
			'rewrite_type' => 1,
			'rewrite_slug' => $slug
		);
		if(is_array($old_fields)){
			$new_fields = array();
			foreach((array) $old_fields as $_rw_field){
				$_rw_slug = str_replace('-','_',sanitize_title($_rw_field['name']));
				$new_fields[$_rw_slug] = array_merge($_rw_field, array('slug'=>$_rw_slug));
			}
			update_post_meta($type->ID, 'fields', $new_fields);
		}
		
		foreach($old_settings as $key=>$setting)			
			if(substr($key, 0, 9) == 'supports-' && $key != 'supports-title'){					
				$arguments['supports'][] = str_replace('supports-','',$key);
			}
			else if(array_key_exists($key, $switch_keys)){
				$arguments[$switch_keys[$key]] = $setting;
			}
		$arguments['show_fields'] = true;
			
		$arguments['taxonomies'] = array();
		if($old_settings['use_category']){
			create_taxonomy($slug, $old_settings['category_name'], $old_settings['category_singular_name'], true);
			$arguments['taxonomies'][] = sanitize_title($old_settings['category_singular_name']);
		}
		if($old_settings['use_tags']){
			create_taxonomy($slug, $old_settings['tags_name'], $old_settings['tags_singular_name'], false);
			$arguments['taxonomies'][] = sanitize_title($old_settings['tags_singular_name']);
		}
		
		add_post_meta($type->ID, 'arguments', $arguments, true);
		delete_post_meta($type->ID, 'settings');
		$post = (array)$type;
		$post['post_type'] = 'rw_content_type';
		wp_update_post( $post );
	}
	
	function create_taxonomy($slug, $plural, $singular, $hierarchical){
		global $user_ID;
		$new_post = array(
			'post_title' => $plural,
			'post_content' => '',
			'post_status' => 'publish',
			'post_date' => date('Y-m-d H:i:s'),
			'post_author' => 1,
			'post_type' => 'rw_taxonomy'
		);		
		$arguments = array(
			'title-singular' => $singular,
			'slug' => sanitize_title($singular),
			'content-types' => array ( $slug ),
			'options' => array('public','show_in_nav_menus','show_ui','show_tagcloud'),
			'rewrite_type' => 1,
			'rewrite_slug' => sanitize_title($singular)
		);
		
		$arguments['options'] = array('public','show_in_nav_menus','show_ui','show_tagcloud');
		if($hierarchical)
			$arguments['options'][] = 'hierarchical';
		
		$post_id = wp_insert_post($new_post);
		add_post_meta($post_id, 'arguments', $arguments, true);
	}

deactivate_plugins(ABSPATH.'wp-content/plugins/content-types-wordpress-plugin/content-types-wordpress-plugin.php');
	
?>success