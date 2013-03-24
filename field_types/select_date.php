<?php
/**
 * This field is the select date field type.
 *
 * @title Date
 * @slug select_date
 * @options false
 */

	function _rw_field_edit_select_date($field){
	
		$dt = array();
	
		$field_value = time();
		if(is_numeric($field['value']))
		if(checkdate(date('m',$stamp),date('d',$stamp),date('y',$stamp)))	
			$field_value = $field['value'];
	
		$month_select = '';
		foreach(array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec') as $mon)
			$month_options .= '<option value="'.$mon.'" '.($mon == date('M', $field_value) ? ' selected="selected"' : '').'>'.$mon.'</option>'; ?>
		<label class="screen-reader-text" for="<?php echo $field['slug']; ?>"><?php echo $field['name']; ?></label><select name="<?php echo $field['slug']; ?>[m]" tabindex="4"><?php echo $month_options; ?></select>
		<input type="text" name="<?php echo $field['slug']; ?>[d]" value="<?php echo date('d', $field_value); ?>" size="2" maxlength="2" style="width: 28px;">, <input type="text" name="<?php echo $field['slug']; ?>[y]" value="<?php echo date('Y', $field_value); ?>" size="4" maxlength="4"  style="width: 45px;">
		<?php
	}

	function _rw_field_value_select_date($_rw_field, $_rw_post = false, $wrap = true, $date_format = 'F j, Y'){
		$_rw_post = $_rw_post ? $_rw_post : (array) $post;
		$custom_values = get_post_custom_values($_rw_field['slug'],$_rw_post['ID']);
		if($custom_values)
			$value = maybe_unserialize(array_shift($custom_values));
		if(!$wrap) return $value;
		return '<p>'.($value ? date($date_format, $value) : '<em>No Date Selected</em>').'</p>';
	}
	
	function _rw_field_save_select_date($value){
		return strtotime($value['d'].' '.$value['m'].' '.$value['y']);
	}

?>