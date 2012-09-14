<?php
/**
 * This field is the select a PDF field type.
 *
 * @title Select a PDF
 * @slug select_pdf
 * @options false
 */

function _rw_field_edit_select_pdf($field){

	if($field['type']=='select_pdf')
		$field['value_range'] = 'post_type=attachment';

	$field['value_range'] = _rw_setup_value_range_array($field);

	$selected_pdfs = "";

	$selected_pdf_layout = '<div style="height: 75px; width: 75px; padding: 3px; border: #ccc solid 1px; margin: 5px 5px 10px 5px; position: relative; clear: none; float: left;" id="pdf-#slug#-#id#"><table cellpadding="0" cellspacing="0" style="width: 75px; height: 75px; vertical-align: middle; text-align: center;"><tr><td>#src#</td></tr></table><a href="#" onclick="select_pdf_remove(\'#id#\',\'#slug#\');return false;" style="position: absolute; top: 0; right: 0; display: block; text-decoration: none; text-align: right; color: #000; font-size: 13px; line-height: 7px; width: 10px; height: 10px; background: #ccc; padding: 2px; border-left: #fff solid 1px; border-bottom: #fff solid 1px;" title="Remove this pdf">&times;</a></div>';
	#slug#,#id#,#src#

	$pdf_ids = array();
	
	if($field['value'])
	foreach(explode('|', $field['value']) as $id){
		$src = get_the_title($id);
		if(!strlen($src)) continue;
		$pdf_ids[] = $id;
		$selected_pdfs .= 
		str_replace(array('#slug#','#id#','#src#'), array($field['slug'],$id,$src), $selected_pdf_layout);
	}
	echo '<p><label class="screen-reader-text" for="'.$field['slug'].'">'.$field['name'].'</label>
	<input class="select_pdf_value'.($field['required'] ? ' required-meta-field' : '').'" type="hidden" id="'.$field['slug'].'" name="'.$field['slug'].'" value="'.implode('|',$pdf_ids).'" /></p>'.
'<div id="selected_pdfs_'.$field['slug'].'" style="float: left; clear: both; width: 100%; margin: 0; padding: 0 0 10px 0;">'.$selected_pdfs.'</div>'.

'<p style="clear: both;">
<a class="thickbox button" href="'.get_bloginfo('url').'/wp-content/plugins/reed-write/field_types/select_pdf/select_pdf_popup.php?slug='.$field['slug'].'&tab=_rw_from_library&TB_iframe=true" title="Add a PDF">Add a PDF</a></p>';

	if(!$_GET['select_pdf_shown'])
		echo
	"<script type=\"text/javascript\">
		var select_pdf_ids = select_pdf_ids || {};
		select_pdf_ids['".$field['slug']."'] = ".json_encode($pdf_ids).";		
		var select_pdf_add = select_pdf_add || function(src,id,slug){
			var val = jQuery('#'+slug).val();
			val += (val.length > 0) ? '|'+id : id;
			jQuery('#'+slug).val(val);	
			jQuery('#selected_pdfs_'+slug).append(('".str_replace("'","\'", $selected_pdf_layout)."').replace(/#slug#/g,slug).replace(/#id#/g,id).replace(/#src#/g,src));
		
		}
		var select_pdf_remove = select_pdf_remove || function(id,slug){		
			var val = jQuery('#'+slug).val();
			val = val.replace('|'+id, '').replace(id+'|','').replace(id,'');
			jQuery('#'+slug).val(val);
			jQuery('#pdf-'+slug+'-'+id).remove();
		
		}
	</script>";
	$_GET['select_pdf_shown'] = true;	
}

function _rw_field_value_select_pdf($_rw_field, $_rw_post = false, $wrap = true){
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
		$value_wrap[] = '<a href="'.$v->guid.'"><img src="'.home_url('/').'/wp-includes/images/crystal/document.png" alt="'.$v->post_title.'" title="'.$v->post_title.'" /> '.$v->post_title.'</a>';
	return (count($ids) ? '<ul><li>'.implode('</li><li>',$value_wrap).'</li></ul>' : '<p><em>No pdf Selected</em></p>');
}

wp_enqueue_style('thickbox');
wp_enqueue_script('jquery');
wp_enqueue_script('thickbox');

?>