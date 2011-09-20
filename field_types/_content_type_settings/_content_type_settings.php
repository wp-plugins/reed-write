<?php
	
	wp_register_style('_content_type_settings', plugins_url('/_content_type_settings.css', __FILE__));
	wp_enqueue_style('_content_type_settings');
	
    wp_enqueue_script('_content_type_settings', plugins_url('/_content_type_settings.js', __FILE__),array('jquery'));

	/* Do not alter this file in any way. */
	function _rw_field_edit__content_type_settings($field){

		global $post;		
		$arguments = array(
			'description' => $type['arguments']['description'],
			'show_fields' => true,
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => trim($type['arguments']['menu_position']) ? (int) $type['arguments']['menu_position'] : 6,
			'menu_icon' => 'posts.png',
			'capability_type' => 'post',
			'hierarchical' => true,
			'supports' => array('editor'),
			'register_meta_box_cb' => $type['arguments']['register_meta_box_cb'],
			'taxonomies' => array(),
			'rewrite' => true,
			'menu_type' => 1,
//			'rewrite' => $type['arguments']['rewrite-custom'] ? array(
//				'slug' => $type['arguments']['rewrite_slug'],
//				'with_front' => $type['arguments']['rewrite_with_front'],
//				'feed' => $type['arguments']['rewrite_feed'],
//				'pages' => $type['arguments']['rewrite_pages']
//			) : false,
			'query_var' => $type['arguments']['slug'],
			'can_export' => true,
			'show_in_nav_menus' => true,
		);

		if(is_array(maybe_unserialize($field['value'])))
			$arguments = (array) maybe_unserialize($field['value']);

		$arguments['supports'] = (array) maybe_unserialize($arguments['supports']);
		$supports = array(
			array(1,'Editor','editor','Adds the content editor meta box'),
			array(1,'Author','author','Adds the author meta box'),
			array(0,'Thumbnail','thumbnail','Adds the featured image meta box'),
			array(0,'Excerpt','excerpt','Adds the excerpt meta box'),
			array(0,'Trackbacks','trackbacks','Adds the trackbacks meta box'),
			//array(0,'Custom Fields','custom-fields','Adds the custom fields meta box'),
			array(0,'Comments','comments','Adds the comments meta box'),
			array(0,'Revisions','revisions','Adds the revisions meta box'),
			array(1,'Page Attributes','page-attributes','Adds the page attribute meta box')
		);
		if(!is_array($arguments)){ $arguments = array();
			foreach($supports as $def_title_name_desc)
				$arguments['supports-'.$def_title_name_desc[2]] = $def_title_name_desc[0];
			foreach($advanced as $def_title_name_desc)
				$arguments[$def_title_name_desc[2]] = $def_title_name_desc[0];
		}
		$i = 3;
		$icon_options = 
			'<option value="posts.png">Select Icon</option>'; $dir = opendir(WP_PLUGIN_DIR.'/reed-write/icons');
		$arguments['menu_icon'] = $arguments['menu_icon'] ? $arguments['menu_icon'] : 'posts.png';
		
		$files = array();
		while($file = readdir($dir)){
			if($file == "." or $file == ".." or substr($file, 0, 1) == '_') continue;
			$files[] = $file;
		}
		asort($files);
		foreach($files as $file){		
			$icon_options .= '<option value="'.$file.'"'.($file == $arguments['menu_icon']?' selected="selected"':'').'>'.array_shift(explode('.',$file)).'</option>';
		}		
		$arguments['taxonomies'] = (array) maybe_unserialize($arguments['taxonomies']);
		$taxonomies = get_taxonomies(array('show_ui'=>1),'objects');
//echo '<pre>'.print_r($taxonomies, 1).'</pre>';
		
		ob_start();
		foreach($taxonomies as $slug=>$tax): 
//echo '<pre>'.$slug.' = '.print_r($tax, 1).'</pre>'; endforeach; /* ?>
		<tr>
			<th><?php echo $tax->label; ?></th>
			<td><input type="checkbox" name="arguments[taxonomies][]" tabindex="<?php $i++; echo $i; ?>" value="<?php echo $slug; ?>"<?php if(in_array($slug, $arguments['taxonomies'])) echo ' checked="checked"'; ?>/>
				<span class="description">Add the <?php echo $tax->label; ?> taxonomy</span></td>
		</tr>
		<?php endforeach; $taxonomy_rows = ob_get_contents(); ob_end_clean(); //*/
	?>
<div id="content-type-settings">
	<input type="hidden" id="wp_mce_fullscreen" />
	<table>
		<tbody class="title">
			<tr class="top">
				<th>Label Options</th><td></td>
			</tr>
			<tr class="bottom">
				<td colspan="2"><div></div></td>
			</tr>
		</tbody>
		<tbody class="label-slug">
			<tr>
				<th>Plural Title</th>
				<td><input type="text" name="post_title" tabindex="<?php $i++; echo $i; ?>" value="<?php echo $post->post_title ?>" id="title" />
					<p class="description">Plural Title of Post Type</p></td>
			</tr>
			<tr>
				<th>Sigular Title</th>
				<td><input type="text" name="arguments[name-singular]" tabindex="<?php $i++; echo $i; ?>" value="<?php echo $arguments['name-singular']; ?>" id="title-singular" />
					<p class="description">Singular Title of Post Type</p></td>
			</tr>
			<tr>
				<th>Slug</th>
				<td><input type="text" name="arguments[slug]" tabindex="<?php $i++; echo $i; ?>" value="<?php echo $arguments['slug']; ?>" id="slug" readonly="readonly" />
					<p class="description">URL friendly version of the Post Type</p></td>
			</tr>
		</tbody>
		<tbody class="title">
			<tr class="top">
				<th>Menu Options</th><td class="show-hide"><a href="#">Show</a></td>
			</tr>
			<tr class="bottom">
				<td colspan="2"><div></div></td>
			</tr>
		</tbody>
		<tbody style="display:none;">
			<tr>
				<td colspan="2">
					<table>		
						<tbody>
							<tr>
								<th>Presentation</th>
								<td>
									<select id="show_menu_select" name="arguments[menu_type]">
										<option<?php _rw_value_type('1', $arguments['menu_type'], 'selected'); ?>>Display standard menu item</option>
										<option<?php _rw_value_type('2', $arguments['menu_type'], 'selected'); ?>>Display under More Content</option>
										<option<?php _rw_value_type('', $arguments['menu_type'], 'selected'); ?>>Do not display</option>
									</select>
								</td>
							</tr>
						</tbody>
						<tbody id="show_menu_options"<?php echo $arguments['menu_type'] != '1' ? ' style="display: none;"' : ''; ?>>
							<tr>
								<th>Menu Position</th>
								<td><input type="hidden" name="arguments[show_ui]" value="<?php echo in_array($arguments['menu_type'], array(1,2)) ? 1 : ''; ?>" /><input type="text" name="arguments[menu_position]" size="15" value="<?php echo $arguments['menu_position'] ? $arguments['menu_position'] : 6; ?>" />
									<p class="description">The position in the menu order the post type should appear. <a href="#" onclick="return false;" title="5 - below Posts
				10 - below Media
				15 - below Links
				20 - below Pages
				25 - below comments
				60 - below first separator
				65 - below Plugins
				70 - below Users
				75 - below Tools
				80 - below Settings
				100 - below second separator" style="cursor: help;">?</a></p></td>
							</tr>
							<tr id="menu-icon-row">
								<th>Menu Icon</th>
								<td><select id="menu_icon" name="arguments[menu_icon]"><?php echo $icon_options; ?></select> <input type="hidden" value="<?php echo plugins_url('/icons/', dirname(dirname(__FILE__))); ?>" /><div><img src="<?php echo plugins_url('/icons/'.$arguments['menu_icon'], dirname(dirname(__FILE__))); ?>" width="16" height="48" /></div>
									<p class="description">The icon to be used for this menu. <a href="#" onclick="return false;" title="Add more to the icons folder located in the Reed Write plugin folder." style="cursor: help;">?</a></p></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
		<tbody class="title">
			<tr class="top">
				<th>Rewrite Options</th><td class="show-hide"><a href="#">Show</a></td>
			</tr>
			<tr class="bottom">
				<td colspan="2"><div></div></td>
			</tr>
		</tbody>
		<tbody style="display:none;">
			<tr>
				<td colspan="2">
					<table>
						<tbody>
							<tr>
								<th>Permalink Type</th>
								<td>
									<select id="rewrite_type_select" name="arguments[rewrite_type]">
										<option<?php _rw_value_type('1', $arguments['rewrite_type'], 'selected'); ?>>Standard rewrite</option>
										<option<?php _rw_value_type('2', $arguments['rewrite_type'], 'selected'); ?>>Custom rewrite</option>
										<option<?php _rw_value_type('', $arguments['rewrite_type'], 'selected'); ?>>No rewrite</option>
									</select>
								</td>
							</tr>			
						</tbody>
						<tbody id="rewrite_options"<?php echo $arguments['rewrite_type'] != '2' ? ' style="display: none;"' : ''; ?>>
							<tr>
								<th>Slug</th>
								<td><input type="text" class="rewrite_slug" name="arguments[rewrite_slug]" value="<?php echo $arguments['rewrite_slug'] ? $arguments['rewrite_slug'] : $arguments['slug']; ?>" /></td>
							</tr>
							<tr>
								<th>With Front</th>
								<td><input type="checkbox" class="rewrite_with_front" name="arguments[rewrite_with_front]" <?php _rw_value_type('1', $arguments['rewrite_with_front'], 'checked'); ?>/><input class="front" type="hidden" value="<?php echo get_option('show_on_front'); ?>" /> <span class="description">Allow permalinks to be prepended with front base.</span></td>
							</tr>
				<?php /*?>			<tr>
								<th>Custom Structure</th>
								<td><input type="text" class="slug-sample" style="width: 400px" readonly="readonly" value="<?php echo trim(get_option('show_on_front')) && $arguments['rewrite_with_front'] ? get_option('show_on_front').'/' : ''; echo $arguments['rewrite_slug'] ? $arguments['rewrite_slug'].'/' : $arguments['slug'].'/'; ?>%postname%" /><p class="description">%postname% is the slug of each item.</p></td>
							</tr><?php */?>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
		<tbody class="title">
			<tr class="top">
				<th>Support Options</th><td class="show-hide"><a href="#">Show</a></td>
			</tr>
			<tr class="bottom">
				<td colspan="2"><div></div></td>
			</tr>
		</tbody>
		<tbody style="display:none;">
			<?php foreach($supports as $def_title_name_desc): ?>
			<tr>
				<th><?php echo $def_title_name_desc[1]; ?></th>
				<td><input type="checkbox" name="arguments[supports][]" tabindex="<?php $i++; echo $i; ?>" value="<?php echo $def_title_name_desc[2]; ?>"<?php if(in_array($def_title_name_desc[2], $arguments['supports'])) echo ' checked="checked"'; ?>/> <span class="description"><?php echo $def_title_name_desc[3]; ?></span></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
		<tbody class="title">
			<tr class="top">
				<th>Taxonomy Types</th><td class="show-hide"><a href="#">Show</a></td>
			</tr>	
			<tr class="bottom">
				<td colspan="2"><div></div></td>
			</tr>		
		</tbody>
		<tbody style="display:none;">
			<?php echo $taxonomy_rows; ?>
		</tbody>
		<tbody class="title">
			<tr class="top">
				<th>Advanced Options</th><td class="show-hide"><a href="#">Show</a></td>
			</tr>
			<tr class="bottom">
				<td colspan="2"><div></div></td>
			</tr>
		</tbody>
		<tbody style="display:none;">
			<tr>
				<th>Show Fields</th>
				<td><input type="checkbox" name="arguments[show_fields]" value="1" <?php echo ($arguments['show_fields'] ?'checked="checked"':'')?>/> <span class="description">Whether field data is shown under the content.</span></td>
			</tr>
			<tr>
				<th>Public</th>
				<td><input type="checkbox" name="arguments[public]" value="1" <?php echo ($arguments['public'] ?'checked="checked"':'')?>/> <span class="description">Whether post_type queries can be performed from the front end.</span></td>
			</tr>
			<tr>
				<th>Publicy Queriable</th>
				<td><input type="checkbox" name="arguments[publicly_queryable]" value="1" <?php echo ($arguments['publicly_queryable'] ?'checked="checked"':'')?>/> <span class="description">Whether post_type queries can be performed from the front end.</span></td>
			</tr>
			<tr>
				<th>Exclude from Search</th>
				<td><input type="checkbox" name="arguments[exclude_from_search]" value="1" <?php echo ($arguments['exclude_from_search'] ?'checked="checked"':'')?>/> <span class="description">Whether to exclude posts with this post type from search results.</span></td>
			</tr>
			<tr>
				<th>Exportable</th>
				<td><input type="checkbox" name="arguments[can_export]" value="1" <?php echo ($arguments['can_export'] ?'checked="checked"':'')?>/> <span class="description">This post type can be exported.</span></td>
			</tr>
			<tr>
				<th>Hierarchical</th>
				<td><input type="checkbox" name="arguments[hierarchical]" value="1" <?php echo ($arguments['hierarchical'] ?'checked="checked"':'')?>/> <span class="description">Whether the post type is hierarchical. Allows Parent to be specified.</span></td>
			</tr>
			<tr>
				<th>Capability Type</th>
				<td><input type="text" name="arguments[capability_type]" size="15" value="<?php echo $arguments['capability_type']; ?>" />
					<p class="description">The post type to use for checking read, edit, and delete capabilities.</p></td>
			</tr>
		</tbody>
	</table>
</div>
<?php
	}
?>
