<?php
/**
 * This field is the select an image field type.
 *
 * @title Select an image
 * @slug select_image
 * @options false
 */

function _rw_field_edit_select_image($field){

	if($field['type']=='select_image')
		$field['value_range'] = 'post_type=attachment&post_mime_type=image/';

	$field['value_range'] = _rw_setup_value_range_array($field);

	$selected_images = "";

	$selected_image_layout = '<div style="height: 75px; width: 75px; padding: 3px; border: #ccc solid 1px; margin: 5px 5px 10px 5px; position: relative; clear: none; float: left;" id="image-#slug#-#id#"><table cellpadding="0" cellspacing="0" style="width: 75px; height: 75px; vertical-align: middle; text-align: center;"><tr><td><img src="#src#" alt="" style="max-height: 75px; max-width: 75px;" /></td></tr></table><a href="#" onclick="select_image_remove(\'#id#\',\'#slug#\');return false;" style="position: absolute; top: 0; right: 0; display: block; text-decoration: none; text-align: right; color: #000; font-size: 13px; line-height: 7px; width: 10px; height: 10px; background: #ccc; padding: 2px; border-left: #fff solid 1px; border-bottom: #fff solid 1px;" title="Remove this Image">&times;</a></div>';
	#slug#,#id#,#src#

	$image_ids = array();
	if($field['value'])
	foreach(explode('|', $field['value']) as $id){
		$src = current((array)wp_get_attachment_image_src($id, array(150,150), false));
		if(!strlen($src)) continue;
		$image_ids[] = $id;
		$selected_images .= 
		str_replace(array('#slug#','#id#','#src#'), array($field['slug'],$id,$src), $selected_image_layout);
	}
	echo '<p><label class="screen-reader-text" for="'.$field['slug'].'">'.$field['name'].'</label>
	<input class="select_image_value'.($field['required'] ? ' required-meta-field' : '').'" type="hidden" id="'.$field['slug'].'" name="'.$field['slug'].'" value="'.implode('|',$image_ids).'" /></p>'.
'<div id="selected_images_'.$field['slug'].'" style="float: left; clear: both; width: 100%; margin: 0; padding: 0 0 10px 0;">'.$selected_images.'</div>'.

'<p style="clear: both;">
<a class="thickbox button" href="'.get_bloginfo('url').'/wp-content/plugins/reed-write/field_types/select_image/select_image_popup.php?slug='.$field['slug'].'&tab=_rw_from_library&TB_iframe=true" title="Add an Image">Add an Image</a></p>';

	if(!$_GET['select_image_shown'])
		echo
	"<script type=\"text/javascript\">
		var select_image_ids = select_image_ids || {};
		select_image_ids['".$field['slug']."'] = ".json_encode($image_ids).";		
		var select_image_add = select_image_add || function(src,id,slug){
			var val = jQuery('#'+slug).val();
			val += (val.length > 0) ? '|'+id : id;
			jQuery('#'+slug).val(val);	
			jQuery('#selected_images_'+slug).append(('".str_replace("'","\'", $selected_image_layout)."').replace(/#slug#/g,slug).replace(/#id#/g,id).replace(/#src#/g,src));
		
		}
		var select_image_remove = select_image_remove || function(id,slug){		
			var val = jQuery('#'+slug).val();
			val = val.replace('|'+id, '').replace(id+'|','').replace(id,'');
			jQuery('#'+slug).val(val);
			jQuery('#image-'+slug+'-'+id).remove();
		
		}
	</script>";
	$_GET['select_image_shown'] = true;	
}

function _rw_field_value_select_image($_rw_field, $_rw_post = false, $wrap = true){
	$_rw_post = $_rw_post ? $_rw_post : (array) $post;
	
	$custom_values = get_post_custom_values($_rw_field['slug'],$_rw_post['ID']);
	
	$ids = array();
	if($custom_values)
		$ids = maybe_unserialize(array_shift($custom_values));
		
	$value = array();
	if($ids)
	foreach(explode( '|', $ids ) as $id)
		$value[] = get_post($id);
	
	if(!$wrap) return $value;
	$value_wrap = array();
	foreach($value as $v)
		$value_wrap[] = '<img src="'.$v->guid.'" alt="'.$v->post_title.'" title="'.$v->post_title.'" />';
	return (count($ids) ? '<ul><li>'.implode('</li><li>',$value_wrap).'</li></ul>' : '<p><em>No Image Selected</em></p>');
}

wp_enqueue_style('thickbox');
wp_enqueue_script('jquery');
wp_enqueue_script('thickbox');

?>