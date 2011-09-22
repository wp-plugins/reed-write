<?php

require_once('../../../../../wp-admin/admin.php');
/**/

// add the tab
define( 'IFRAME_REQUEST' , true );

$body_id = 'media-upload';

wp_enqueue_style('media');

function media_header_tabs($tab){ 
echo str_replace("tab=$tab","tab=$tab".'" class="current"','<div id="media-upload-header">
	<ul id="sidemenu">
		<li id="tab-type"><a href="'.get_bloginfo('url').'/wp-content/plugins/reed-write/field_types/select_image/select_image_popup.php?slug=logo&amp;tab=_rw_from_computer">From Computer</a></li>
		<li id="tab-_rw_select_images"><a href="'.get_bloginfo('url').'/wp-content/plugins/reed-write/field_types/select_image/select_image_popup.php?slug=logo&amp;tab=_rw_from_library">From Library</a></li>
	</ul>
</div>');
}

function _rw_from_library(){	
	
	$query = 'post_status=inherit&post_mime_type=image&post_type=attachment';
	$query .= (isset($_GET['s'])) ? '&s='.$_GET['s'] : '';
	$query .= (isset($_GET['paged'])) ? '&paged='.$_GET['paged'] : '';
	query_posts($query);
	
	global $wpdb, $wp_query, $wp_locale, $type, $tab, $post_mime_types, $media_header_tabs;

	$_GET['paged'] = ($_GET['paged']) ? $_GET['paged'] : 1;
	
	media_header_tabs('_rw_from_library');
?>
<div class="image-selector" style="padding: 25px;">
	<form id="filter" action="" method="get">
		<input type="hidden" name="slug" value="<?php echo $_GET['slug']; ?>" />
		<p id="media-search" class="search-box">
			<label class="screen-reader-text" for="media-search-input">
				<?php _e('Search Media');?>
				:</label>
			<input type="text" id="media-search-input" name="s" value="<?php the_search_query(); ?>" />
			<input type="submit" value="<?php esc_attr_e( 'Search Media' ); ?>" class="button" />
		</p>
		<?php
$all_images = new WP_Query();
$all_images->query('posts_per_page=-1&post_status=inherit&post_mime_type=image&post_type=attachment');
?>
		<ul class="subsubsub">
			<li> <a href="#" class="current">Images <span class="count">(<span id="image-counter"><?php echo $all_images->post_count; ?></span>)</span></a> </li>
		</ul>
		<div class="tablenav">
			<?php
$page_links = paginate_links( array(
	'base' => add_query_arg( 'paged', '%#%' ),
	'format' => '',
	'prev_text' => __('&laquo;'),
	'next_text' => __('&raquo;'),
	'total' => ceil($wp_query->found_posts / 10),
	'current' => $_GET['paged']
));
if ( $page_links )
	echo "<div class='tablenav-pages'>$page_links</div>";
?>
			<div class="alignleft actions">
				<?php
$arc_query = "SELECT DISTINCT YEAR(post_date) AS yyear, MONTH(post_date) AS mmonth FROM $wpdb->posts WHERE post_type = 'attachment' ORDER BY post_date DESC";
$arc_result = $wpdb->get_results( $arc_query );
$month_count = count($arc_result);
if ( $month_count && !( 1 == $month_count && 0 == $arc_result[0]->mmonth ) ) { ?>
				<select name='m'>
					<option<?php selected( @$_GET['m'], 0 ); ?> value='0'>
					<?php _e('Show all dates'); ?>
					</option>
					<?php
foreach ($arc_result as $arc_row) {
	if ( $arc_row->yyear == 0 )
		continue;
	$arc_row->mmonth = zeroise( $arc_row->mmonth, 2 );
	if ( isset($_GET['m']) && ( $arc_row->yyear . $arc_row->mmonth == $_GET['m'] ) )
		$default = ' selected="selected"';
	else
		$default = '';
	echo "<option$default value='" . esc_attr( $arc_row->yyear . $arc_row->mmonth ) . "'>";
	echo esc_html( $wp_locale->get_month($arc_row->mmonth) . " $arc_row->yyear" );
	echo "</option>\n";
}
?>
				</select>
				<?php } ?>
				<input type="submit" id="post-query-submit" value="<?php echo esc_attr( __( 'Filter &#187;' ) ); ?>" class="button-secondary" />
			</div>
			<br class="clear" />
		</div>
		<input id="selected_images_value" type="hidden">
	</form>
	<div style="margin-top: 20px; float: left; border-bottom: #ccc solid 1px;">
		<?php
if(!count($wp_query->posts))
echo '<p>No Results Found.</p>';
else
foreach($wp_query->posts as $p){
//echo '<pre>'; var_dump(wp_get_attachment_image_src($p->ID, array(32,32))); exit;
$image = wp_get_attachment_image_src($p->ID, array(100,100));
 ?>
		<div class="media-item preloaded" style="width: 613px; border: #ccc solid 1px; border-bottom: none; float: left; clear: both; padding: 5px 0 5px 5px;">
		<img src="<?php echo wp_get_attachment_thumb_url($p->ID); ?>" style="max-width: 100px;max-height: 100px; vertical-align: middle; padding-right: 20px;" /><span class="title"><?php echo $p->post_title; ?></span> <a class="insert-select-image describe-toggle-on" href="#<?php echo $p->ID; ?>?<?php echo $image[0]; ?>" id="image-<?php echo $p->ID; ?>">Select</a> </div>
		<?php } ?>
	</div>
</div>
<script type="text/javascript">
jQuery(document).ready(function($){
var slug = '<?php echo $_GET['slug']; ?>';
	$('a.insert-select-image').click(function(){
	
		var id_src = $(this).attr('href').split('#')[1].split('?');
		if($(this).hasClass('remove')){		
			top.select_image_remove(id_src[0], slug);		
			$(this).removeClass('remove');
			$(this).text('Select');	
		}else{
			top.select_image_add(id_src[1], id_src[0], slug);
			$(this).addClass('remove');
			$(this).text('Remove');	
		}
		return false;
	
	});
	var val = $('#'+slug, top.document).val().split('|');
	
	$('a.insert-select-image').each(function(){
		
		var id_src = $(this).attr('href').split('#')[1].split('?');
		for(var i = 0; i < val.length; i++){
			if(val[i]!=id_src[0]) continue;			
			$(this).addClass('remove');
			$(this).text('Remove');
		}
	
	});
});
</script>
<div style="display:none;">
<?php //do_action('media_upload_image'); ?>
</div>
<?php 
}

function _rw_from_computer(){
	global $media_header_tabs;
	media_header_tabs('_rw_from_computer');
/*	
echo '<pre>'.print_r($_FILES, 1).'</pre>';
echo '<pre>'.print_r($_POST, 1).'</pre>';
*/
	$upload_error = ''; $upload_sucess = '';
	if($_FILES['upload_image']){
		if(!$_FILES['upload_image']['error']){		
			
			$wp_upload_dir = wp_upload_dir();
			$uploads_dir = $wp_upload_dir['path'];
			$tmp_name = $_FILES["upload_image"]["tmp_name"];
			$name = $_FILES["upload_image"]["name"];
			move_uploaded_file($tmp_name, "$uploads_dir/$name");	
			$filename = "$uploads_dir/$name";		
			$wp_filetype = wp_check_filetype($_FILES['upload_image']['name'], null );
			$attachment = array(
			 'post_mime_type' => $wp_filetype['type'],
			 'post_title' => $_FILES['upload_image']['name'],
			 'post_content' => '',
			 'post_status' => 'inherit'
			);
			list($width, $height, $type, $attr) = getimagesize($filename);
			$attach_id = wp_insert_attachment( $attachment, $filename);
			$return = wp_create_thumbnail( $attach_id, max($width, $height), max($width, $height) );
			//echo '<pre>'.print_r(getimagesize($filename), 1).'</pre>';
			if(is_numeric($attach_id) && $attach_id > 0)
				$upload_sucess = 'Your image was successfully loaded.';	
		}	
		if($upload_sucess == '')
			$upload_error = 'There was an error upploading your file. Please try again.';
	}
	?>
	<div class="image-selector" style="padding: 25px;">
		<form enctype="multipart/form-data" method="post" action="<?php bloginfo('url') ?>/wp-content/plugins/reed-write/field_types/select_image/select_image_popup.php?slug=logo&tab=_rw_from_computer" class="media-upload-form type-form validate" id="image-form">
<!-- input type="submit" name="save" id="save" class="hidden" value="Save Changes"><input type="hidden" name="post_id" id="post_id" value="182" -->
<!-- input type="hidden" id="_wpnonce" name="_wpnonce" value="60681af029"><input type="hidden" name="_wp_http_referer" value="/wp-admin/media-upload.php?post_id=0&amp;type=image&amp;" -->
<h3 class="media-title">Add media files from your computer</h3>
<div id="media-upload-notice">
	<?php echo $upload_sucess; ?>
</div>
<div id="media-upload-error">
	<?php echo $upload_error; ?>
</div>
<div id="html-upload-ui" class="hide-if-js" style="display: block; ">
	<p id="async-upload-wrap">
		<label class="screen-reader-text" for="upload_image">Upload</label>
		<input type="file" name="upload_image" id="upload_image">
		<input type="submit" name="html-upload" id="html-upload" class="button" value="Upload">		<a href="#" onclick="try{top.tb_remove();}catch(e){}; return false;">Cancel</a>
	</p>
	<div class="clear"></div>
	<p class="media-upload-size">Maximum upload file size: 2MB</p>
	<p class="upload-html-bypass hide-if-no-js">You are using the Browser uploader. Currently the Flash uploader is not supported for the Reed Write add images field. You may upload images using the Flash uploader on the <a href="<?php bloginfo('url'); ?>/wp-admin/media-new.php">Upload New Media</a> page.</p>
</div>
<div id="media-items">
</div>
<p class="savebutton ml-submit" style="display: none; "></p>
</form>
	</div>
<?php
}

if( !isset($_GET['tab']) || !in_array($_GET['tab'], array('_rw_from_library','_rw_from_computer')))
	$_GET['tab'] = '_rw_from_library';

wp_iframe( $_GET['tab'], array() );

exit;

?>
