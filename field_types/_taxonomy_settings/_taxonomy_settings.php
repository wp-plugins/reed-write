<?php
	
	wp_register_style('_taxonomy_settings', plugins_url('/_taxonomy_settings.css', __FILE__));
	wp_enqueue_style('_taxonomy_settings');
	
    wp_enqueue_script('_taxonomy_settings', plugins_url('/_taxonomy_settings.js', __FILE__),array('jquery'));

	/* Do not alter this file in any way. */
	function _rw_field_edit__taxonomy_settings($field){

		global $post;

		if(is_array(maybe_unserialize($field['value'])))
			$arguments = (array) maybe_unserialize($field['value']);

		$arguments['content-types'] = (array) maybe_unserialize($arguments['content-types']);
		
		if(!$arguments['slug'])
		foreach(array('public','show_in_nav_menus','show_ui') as $key)
			$arguments[$key] = 1;

		$i = 3;
		global $wp_post_types;
		$content_types = array();
		foreach($wp_post_types as $slug=>$type){
			if(!in_array($slug, array('attachment','revision','nav_menu_item','content-type','taxonomy','view')))
			$content_types[$slug] = $type->labels->name;
			//echo '<pre>'.print_r($slug, 1).'</pre>';
		}		
		//foreach((array) get_posts('post_type=content-type&numberposts=-1') as $k=>$type){
//			$content_type = array_merge( (array) $type, array( 'arguments'=> maybe_unserialize(array_shift(get_post_custom_values('arguments',$type->ID)))));
//			echo '<pre>'.print_r($content_type, 1).'</pre>';
//		}
//		$taxonomies=get_taxonomies('','names'); 
//foreach ($taxonomies as $taxonomy ) {
//  echo '<p>'. $taxonomy. '</p>';
//}
		$arguments['options'] = (array) maybe_unserialize($arguments['options']);
		$options = array(
			array(0,'Hierarchical','hierarchical','Is this taxonomy hierarchical (have descendants) like categories or not hierarchical like tags'),
			array(1,'Public','public','Should this taxonomy be exposed in the admin UI'),
			array(1,'Show in Navigation','show_in_nav_menus','Make available for selection in navigation menus'),
			array(1,'Show User Interface','show_ui','Generate a default UI for managing this taxonomy'),
			array(1,'Tagcloud','show_tagcloud','Allow the Tag Cloud widget to use this taxonomy')
		);
	?>
<div id="taxonomy-settings">
<input type="hidden" id="wp_mce_fullscreen" />
<table class="form-table">
	<tbody class="label-slug">
		<tr class="title">
			<td colspan="2"><h2>Label Options</h2></td>
		</tr>
		<tr>
			<th>Plural Title</th>
			<td><input type="text" name="post_title" tabindex="<?php $i++; echo $i; ?>" value="<?php echo $post->post_title ?>" id="title">
				<p class="description">Name of Taxonomy</p></td>
		</tr>		
		<tr>
			<th>Sigular Title</th>
			<td><input type="text" name="arguments[title-singular]" tabindex="<?php $i++; echo $i; ?>" value="<?php echo $arguments['title-singular']; ?>" id="title-singular">
				<p class="description">Singular Name of Taxonomy</p></td>
		</tr>
		<tr>
			<th>Slug</th>
			<td><input type="text" name="arguments[slug]" tabindex="<?php $i++; echo $i; ?>" value="<?php echo $arguments['slug']; ?>" id="slug" readonly="readonly">
				<p class="description">URL friendly version of the Taxonomy</p></td>
		</tr>		
	</tbody>
	<tbody>
		<tr class="title">
			<td colspan="2"><h2>Content Types</h2></td>
		</tr>
		<?php foreach($content_types as $slug=>$title): ?>
		<tr>
			<th><?php echo $title; ?></th>
			<td><input type="checkbox" name="arguments[content-types][]" tabindex="<?php $i++; echo $i; ?>" value="<?php echo $slug; ?>"<?php if(in_array($slug, $arguments['content-types'])) echo ' checked="checked"'; ?>/>
				<span class="description">Add taxonomy to <?php echo $title; ?></span></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
	<tbody>
		<tr class="title">
			<td colspan="2"><h2>More Options</h2></td>
		</tr>
		<?php foreach($options as $v_t_n_d): ?>
		<tr>
			<th><label for="option-<?php echo $v_t_n_d[2]; ?>"><?php echo $v_t_n_d[1]; ?></label></th>
			<td><input type="checkbox" id="option-<?php echo $v_t_n_d[2]; ?>" name="arguments[options][]" tabindex="<?php $i++; echo $i; ?>" value="<?php echo $v_t_n_d[2]; ?>"<?php if(in_array($v_t_n_d[2], $arguments['options']) || (!$arguments['slug'] && $v_t_n_d[0])) echo ' checked="checked"'; ?>/> <span class="description"><?php echo $v_t_n_d[3]; ?></span></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
	<tbody>
			<tr class="title">
				<td colspan="2"><h2>Rewrite Options</h2></td>
			</tr>
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
			<tr>
				<th>Hierarchical</th>
				<td><input type="checkbox" class="hierarchical" name="arguments[rewrite_hierarchical]" <?php _rw_value_type('1', $arguments['rewrite_hierarchical'], 'checked'); ?>/> <span class="description">Allow hierarchical urls.</span></td>
			</tr>
			<tr>
				<th>Custom Structure</th>
				<td><input type="text" class="slug-sample" style="width: 400px" readonly="readonly" value="<?php echo trim(get_option('show_on_front')) && $arguments['rewrite_with_front'] ? get_option('show_on_front') : ''; echo $arguments['rewrite_slug'] ? $arguments['rewrite_slug'] : $arguments['slug']; ?>=%taxonomyname%" /><p class="description">%taxonomyname% is the slug of each taxonomy.</p></td>
			</tr>
		</tbody>
</table>
</div>
<?php
	}	
		
?>
