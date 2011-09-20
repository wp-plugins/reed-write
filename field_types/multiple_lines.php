<?php
/**
 * This field is the multiple lines of text field type.
 *
 * @title Multiple lines of text
 * @slug multiple_lines
 * @options false
 */
 
	function _rw_field_edit_multiple_lines($field){
		
		echo '<p><label class="screen-reader-text" for="'.$field['slug'].'">'.$field['name'].'</label><textarea rows="3" cols="40" name="'.$field['slug'].'" tabindex="6" id="textarea-'.$field['slug'].'" style="width: 100%;" class="'.($field['required'] ? 'required-meta-field' : '').'">'.$field['value'].'</textarea></p>';
	
	}

	function _rw_field_value_multiple_lines($_rw_field, $_rw_post = false, $wrap = true){
		$_rw_post = $_rw_post ? $_rw_post : (array) $post;		
		$custom_values = get_post_custom_values($_rw_field['slug'],$_rw_post['ID']);
		if($custom_values)
			$value = maybe_unserialize(array_shift($custom_values));
		if(!$wrap) return $value;
		return '<p>'.($value ? htmlentities(str_replace(array("\r\r","\r\n","\n\r","\n\n","\r","\n"), '<br />',$value)) : '<em>Nothing Entered</em>').'</p>';
	}

?>