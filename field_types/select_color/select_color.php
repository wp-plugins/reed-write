<?php
/**
 * This field is the select a color field type.
 *
 * @title Select a color
 * @slug select_color
 * @options false
 */
		
	function _rw_field_edit_select_color($field){
			
		echo
		'<a href="#" style="display: block; height: 80px; width: 80px; border: #ccc solid 1px; margin: 15px 0 0 5px; background: #'.$field['value'].';" id="'.$field['slug'].'-select-color"></a><input type="hidden" id="'.$field['slug'].'-color-input" name="'.$field['slug'].'" value="'.$field['value'].'" class="'.($field['required'] ? 'required-meta-field' : '').'" />'.
		'<script type="text/javascript">'.
		"jQuery(function($){
			$('#".$field['slug']."-select-color').ColorPicker({
				//flat: true,
				onShow: function (colpkr) {
					$(colpkr).show();
					return false;
				},
				onSubmit: function(hsb, hex, rgb, el){
					$('#".$field['slug']."-color-input').val(hex);
					$('#".$field['slug']."-select-color').css({'background-color':'#'+hex});
					$('#".$field['slug']."-select-color').ColorPickerHide();
				}
			});/*
			.bind('keyup', function(){
				$(this).ColorPickerSetColor(this.value);
			});*/
		});".
		'</script>';
	}
	
	function _rw_field_value_select_color($_rw_field, $_rw_post = false, $wrap = true){
		if(!$_rw_post) $_rw_post = (array) $post;
		$custom_values = get_post_custom_values($_rw_field['slug'],$_rw_post['ID']);
		if($custom_values)
			$value = maybe_unserialize(array_shift($custom_values));
		if(!$wrap) return $value;		
		return '<p>'.($value ? '<span style="width: 50px; height: 50px; display: inline-block; background-color:#'.$value.'"></span>' : '<p><em>No Color Selected</em></p>').'</p>';
	}
	
	wp_enqueue_script('content_type_settings', WP_PLUGIN_URL . 
		'/reed-write/field_types/select_color/js/colorpicker.js', array('jquery'));
	wp_register_style('add_colorpicker_style', WP_PLUGIN_URL .	'/reed-write/field_types/select_color/css/colorpicker.css');
	wp_enqueue_style('add_colorpicker_style');

?>