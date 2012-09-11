<?php
/**
 * This field is a single line of text field type. This field will be loaded if no other field can be found.
 *
 * @title Single line of text
 * @slug default
 * @options false
 */

	function _rw_field_edit_default($field){
	
		echo '<p><label class="screen-reader-text" for="'.$field['slug'].'">'.$field['name'].'</label><input type="text" name="'.$field['slug'].'" value="'.$field['value'].'" id="input-'.$field['slug'].'" style="width: 100%;" class="'.($field['required'] ? 'required-meta-field' : '').'" /></p>';
	
	}

	function _rw_field_value_default($_rw_field, $_rw_post = false, $wrap = true){
		$_rw_post = $_rw_post ? $_rw_post : (array) $post;
		$custom_values = get_post_custom_values($_rw_field['slug'],$_rw_post['ID']);
		if($custom_values)
			$value = maybe_unserialize(array_shift($custom_values));
		if(!$wrap) return $value;
		return '<p>'.($value ? $value :  '<em>Nothing Entered</em>').'</p>';
	}

?>