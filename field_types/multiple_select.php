<?php
/**
 * This field is the multiple select field type.
 *
 * @title Select multiple
 * @slug multiple_select
 * @options true
 */
 
	function _rw_field_edit_multiple_select($field){
	
		$field['value_range'] = _rw_setup_value_range_array($field);
	
		// echo '<pre>'.print_r($field, 1).'</pre>'; return '';
		// build select options
		//(($field['value']==$option_value) ? '' : "")
		
		$select_options	= '<option value="">Select '.$field['name'].'</option>';
		foreach($field['value_range'] as $option_value=>$option_label)				
			$select_options	 .= '<option value="'.$option_value.'">'.$option_label.'</option>';	
		$select_field = 
		'<div style="float: left; clear: none; padding: 5px;"><select>'.$select_options.'</select></div>';
		
		$select_fields = '';
		if(!$field['value']) $field['value'] = '';
		//var_dump(explode("|", $field['value'])); return;
		foreach(explode("|", $field['value']) as $k=>$val)
			$select_fields .= str_replace('value="'.$val.'"','value="'.$val.'" selected="selected"',$select_field);
		
		$title = 'Add a New ' . ($field['singular_name'] ? (strtoupper($field['singular_name'][0]).substr($field['singular_name'], 1)) : 'Item');
		
		?>

<div id="multiple-select-fields-<?php echo $field['slug'] ?>"><?php echo $select_fields; ?></div>
<p style="clear: both; padding: 10px 0 5px 0;">
	<input type="hidden" name="<?php echo $field['slug']; ?>" id="multiple-select-<?php echo $field['slug']; ?>" value="<?php echo $field['value']; ?>" class="<?php echo ($field['required'] ? ' required-meta-field' : '') ?>" />
	<a class="button" href="#" title="<?php echo $title; ?>" id="multiple-select-<?php echo $field['slug']; ?>-add"><?php echo $title; ?></a> </p>
<script type="text/javascript">
	jQuery(function($){
		$('#multiple-select-<?php echo $field['slug']; ?>-add').click(function(){
			$('#multiple-select-fields-<?php echo $field['slug'] ?>').append('<?php echo $select_field; ?>');
			return false;
		});
		$('#multiple-select-fields-<?php echo $field['slug'] ?> select').live('change',function(){
			var val = [];
			$('#multiple-select-fields-<?php echo $field['slug'] ?> select').each(function(){
				if($(this).val().length > 0)
				val[val.length] = $(this).val();
			});
			$('#multiple-select-<?php echo $field['slug']; ?>').val(
				val.join('|')
			);
		});
	});
</script>
<?php 			
	}

	function _rw_field_value_multiple_select($_rw_field, $_rw_post = false, $wrap = true){
		$_rw_post = $_rw_post ? $_rw_post : (array) $post;
		$custom_values = get_post_custom_values($_rw_field['slug'],$_rw_post['ID']);
		if($custom_values)
			$value = maybe_unserialize(array_shift($custom_values));
		$value = _rw_get_value_range_array($_rw_field, explode('|',$value));
		if(!$wrap) return $value;
		return (count($value) ? '<ul><li>'.implode('</li><li>',$value).'</li></ul>' : '<p><em>Nothing Selected</em></p>');
	}

?>