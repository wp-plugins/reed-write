<?php
/**
 * This field is the select field type.
 *
 * @title Select
 * @slug select
 * @options true
 */

	function _rw_field_edit_select($field){
		
		$field['value_range'] = _rw_setup_value_range_array($field);
		
		// build select options
		$select_options	= '<option value="">Select a '.$field['name'].'</option>';
		foreach($field['value_range'] as $option_value=>$option_label)				
			$select_options	 .= '<option value="'.$option_value.'"'.(($field['value']==$option_value) ? ' selected="selected"' : "").'>'.$option_label.'</option>';
	
		echo '<p><label class="screen-reader-text" for="'.$field['slug'].'">'.$field['name'].'</label>'.
		'<select name="'.$field['slug'].'" id="select-'.$field['slug'].'" class="'.($field['required'] ? 'required-meta-field' : '').'">'.$select_options.
		'</select></p>';
				
	}

	function _rw_field_value_select($_rw_field, $_rw_post = false, $wrap = true){
		$_rw_post = $_rw_post ? $_rw_post : (array) $post;
		$custom_values = get_post_custom_values($_rw_field['slug'],$_rw_post['ID']);
		if($custom_values)
			$value = maybe_unserialize(array_shift($custom_values));
		$value = _rw_get_value_range_array($_rw_field, explode('|',$value));
		if(!$wrap) return $value;
		return '<p>'.($value ? implode('',$value) : '<em>Nothing Selected</em>').'</p>';
	}

?>