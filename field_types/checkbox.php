<?php
/**
 * This field is the checkbox field type.
 *
 * @title Checkbox(es)
 * @slug checkbox
 * @options true
 */
 
	function _rw_field_edit_checkbox($field){
	
		$field['value_range'] = _rw_setup_value_range_array($field);
		
		// make value and array
		$field['value'] = maybe_unserialize($field['value']);
		$field['value'] = (!is_array($field['value'])) ? array() : $field['value'];
		
		// build checkbox(es)
		$checkboxes	= ""; $checkbox_count = 0;
		foreach($field['value_range'] as $checkbox_value=>$checkbox_label){
			$checkbox_count++;
			$checkboxes	.= '<label for="'.$field['slug'].'-'.$checkbox_count.'">'.
			'<input name="'.$field['slug'].'[]" type="checkbox" id="checkbox-'.$field['slug'].'-'.$checkbox_count.'" value="'.$checkbox_value.'"'.((in_array($checkbox_value, $field['value'])) ? ' checked="checked"' : "").' class="'.($field['required'] ? 'required-meta-field' : '').'"> '.$checkbox_label.'</label><br />';
		}			
		
		echo '<p class="meta-options">'.$checkboxes.'</p>';
	}

	function _rw_field_value_checkbox($_rw_field, $_rw_post = false, $wrap = true){
		$_rw_post = $_rw_post ? $_rw_post : (array) $post;
		$custom_values = get_post_custom_values($_rw_field['slug'],$_rw_post['ID']);
		if($custom_values)
			$value = maybe_unserialize(array_shift($custom_values));
		$value = _rw_get_value_range_array($_rw_field, $value);
		if($wrap) return '<p>'.($value ? implode(', ',$value) :  '<em>Nothing Checked</em>').'</p>';
		return $value;
	}

?>