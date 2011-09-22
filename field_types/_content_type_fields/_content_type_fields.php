<?php

	/* Do not alter this file in any way. This means you! */
	
	wp_register_style('_content_type_fields', plugins_url('/_content_type_fields.css', __FILE__));
	wp_enqueue_style('_content_type_fields');

    wp_enqueue_script('_content_type_fields', plugins_url('/_content_type_fields.js', __FILE__),array('jquery'));

	function _rw_field_edit__content_type_fields($field){
		global $field_type_edit;
	
		$field_type_edit = array();
		$field_type_dir = WP_PLUGIN_DIR.'/reed-write/field_types';
		$dir = opendir($field_type_dir);	
		while($file = readdir($dir)){
		
		if($file == "." or $file == ".." or substr($file, 0, 1) == '_') continue;
		if(substr_count($file, '.')) $field_file = file_get_contents("$field_type_dir/$file");
		else $field_file = file_get_contents("$field_type_dir/$file/$file.php");
					
		preg_match("/(@title )(.*?)(\s)(.*?)(@slug )(.*?)(\s)(.*?)(@options )(.*?)(\s)/", substr($field_file,3), $matches);
		$field_type = array();
		foreach($matches as $k=>$match){
			if($last_match == '@slug ')
				$field_type[0] = str_replace(array("\r", "\n"," "), '', $match);
			if($last_match == '@title ')
				$field_type[1] = str_replace(array("\r", "\n"), '', $match);
			if($last_match == '@options ')
				$field_type[2] = ($match == 'true') ? true : false;
			$last_match = $match;
		}
		ksort($field_type);
		if(count($field_type)!=3) continue;
		$field_type_edit[] = $field_type;
	}
	
		$fields = maybe_unserialize($field['value']);
		$fields = is_array($fields) ? $fields : array(array());		
		
		function field_row($i, $v) {
			global $field_type_edit;
			$v['slug'] = !$v['slug'] ? sanitize_title($v['name']) : $v['slug'];
			?>
<div class="field">
	<table>
		<tr class="entry-row">
			<!-- td class="pad"></td -->
			<td class="name"><label for="fields-<?php echo $i; ?>-name">Name</label>
				<div class="input">
					<input type="text" name="fields[<?php echo $i; ?>][name]" class="name name-title" id="fields-<?php echo $i; ?>-name" value="<?php echo $v['name']; ?>"/>
				</div></td>
			<td><label for="fields-<?php echo $i; ?>-type">Type</label>
				<div class="input">
					<select class="type" name="fields[<?php echo $i; ?>][type]" id="fields-<?php echo $i; ?>-type">
						<?php 
						$v['type'] = isset($v['type']) ? $v['type'] : 'default';
						foreach($field_type_edit as $slug_title_option) {
						$req = ($v['type'] == $slug_title_option[0]) ? ' selected="selected"' : '';
						if($req and $slug_title_option[2]) { $show_options = true; }
						?>
						<option value="<?php echo $slug_title_option[0]; ?>"<?php echo $req; ?>><?php echo $slug_title_option[1] ?></option>
						<?php } ?>
					</select>
				</div></td>
			<td><label for="fields-<?php echo $i; ?>-required">Required</label>
				<div class="input">
					<input name="fields[<?php echo $i; ?>][required]" id="fields-<?php echo $i; ?>-required" value="1" type="checkbox"<?php echo $v['required'] ? ' checked="checked"' : ''; ?>/>
				</div>
			</td>
			<td class="actions"><label>&nbsp;</label>
				<div class="input"><a href="#" class="delete" title="Delete this field."> <span class="times">&times;</span><span>Delete</span></a></div></td>
			<!-- td class="pad"></td -->
		</tr>
		<tr class="entry-row options2">
			<td>
			<label for="fields-<?php echo $i; ?>-slug">Slug</label>
			<div class="input">
				<input type="text" name="fields[<?php echo $i; ?>][slug]" id="fields-<?php echo $i; ?>-slug" class="name name-slug" value="<?php echo $v['slug']; ?>" readonly="readonly" />
			</div>
			</td>
			<td colspan="3">
				<label for="fields-<?php echo $i; ?>-description">Description</label>
				<div class="input">
					<input type="text" name="fields[<?php echo $i; ?>][description]" id="fields-<?php echo $i; ?>-description" class="description" value="<?php echo $v['description']; ?>"/>
				</div></td>
		</tr>
		<tr class="entry-row type-options"<?php echo (array_key_exists('type_option', $v)) ?  '' : 'style="display: none;"'; ?>>
			<!-- td class="pad"></td -->
			<?php if($show_options) field_type_options($i, $v); else echo '<td colspan="4"></td>'; ?>
			<!-- td class="pad"></td -->
		</tr>	
	</table>
</div>
<?php 

		}
		
		function field_type_options($i, $v){ ?>
<td><label>Select Method Options</label>
	<div class="input"><select name="fields[<?php echo $i ?>][type_option]">
		<?php
			foreach(array('custom'=>'Custom Choices','query'=>'Query') as $k=>$text)
				echo '<option value="'.$k.'"'.( $k == $v['type_option'] ? ' selected="selected"' : '' ).'>'.$text.'</option>';
		?>
		</select>&nbsp;&nbsp;<a href="#" onclick="return false;" title="Enter choices on each line." style="cursor: help;">?</a>
	</div></td>
<td colspan="3"><label for="fields-<?php echo $i ?>-type_option_data">Option Data</label>
	<div class="input">
		<textarea name="fields[<?php echo $i ?>][type_option_data]" id="fields-<?php echo $i ?>-type_option_data"><?php echo $v['type_option_data'] ?></textarea>
	</div></td>
<?php }		
		ob_start();
			field_row(99, array());
			$field_row = ob_get_contents();
		ob_clean();
			field_type_options(99, array());
			$field_type_options = ob_get_contents();		
		ob_end_clean();		
?>
<script type="text/javascript">
	var field_type_options = '<?php echo str_replace(array("\r","\n","\t"), '', $field_type_options); ?>';
	var field_row = '<?php echo str_replace(array("\r","\n","\t"), '', $field_row); ?>';
	var field_type_edit = <?php echo json_encode($field_type_edit); ?>;
</script>
<div id="field-builder">
	<div class="add-field"><a href="#" class="button">Add Field</a></div>
	<div class="fields">
		<?php $i = 0; foreach($fields as $v){ field_row($i, $v);  $i++; } ?>
	</div>
</div>
<?php 
	}
	
	function _rw_field_save__content_type_fields($value){
		foreach($value as $k=>$field){
			if($field['type_option'] == 'query' && strlen($field['type_option_data']) == 0 )
				$field['type_option_data'] = 'post_type=post';			
			$value[$k] = $field;
		}
		return $value;
	}

?>
