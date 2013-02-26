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
			<li id="tab-type"><a target="_self" href="'.get_bloginfo('url').'/wp-content/plugins/reed-write/field_types/select_pdf/select_pdf_popup.php?slug='.$_GET['slug'].'&amp;tab=_rw_from_computer">From Computer</a></li>
			<li id="tab-_rw_select_pdfs"><a target="_self" href="'.get_bloginfo('url').'/wp-content/plugins/reed-write/field_types/select_pdf/select_pdf_popup.php?slug='.$_GET['slug'].'&amp;tab=_rw_from_library">From Library</a></li>
		</ul>
	</div>');
}

function _rw_from_library(){	
	
	$query = 'post_status=inherit&post_mime_type=application/pdf&post_type=attachment';
	$query .= (isset($_GET['s'])) ? '&s='.$_GET['s'] : '';
	$query .= (isset($_GET['paged'])) ? '&paged='.$_GET['paged'] : '';
	query_posts($query);
	
	global $wpdb, $wp_query, $wp_locale, $type, $tab, $post_mime_types, $media_header_tabs;

	$_GET['paged'] = ($_GET['paged']) ? $_GET['paged'] : 1;
	
	media_header_tabs('_rw_from_library');
?>
<div class="pdf-selector" style="padding: 25px;">
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
$all_pdfs = new WP_Query();
$all_pdfs->query('posts_per_page=-1&post_status=inherit&post_mime_type=application/pdf&post_type=attachment');
?>
		<ul class="subsubsub">
			<li> <a href="#" class="current">PDFs <span class="count">(<span id="pdf-counter"><?php echo $all_pdfs->post_count; ?></span>)</span></a> </li>
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
		<input id="selected_pdfs_value" type="hidden">
	</form>
	<div style="margin-top: 20px; float: left; border-bottom: #ccc solid 1px;">
		<?php
if(!count($wp_query->posts))
echo '<p>No Results Found.</p>';
else
foreach($wp_query->posts as $p){
//echo '<pre>'; var_dump(wp_get_attachment_pdf_src($p->ID, array(32,32))); exit;
$pdf = get_the_title($p->ID);
 ?>
		<div class="media-item preloaded" style="width: 613px; border: #ccc solid 1px; border-bottom: none; float: left; clear: both; padding: 5px 0 5px 5px;">
		<img src="<?php echo home_url('/'); ?>/wp-includes/images/crystal/document.png" style="max-width: 100px;max-height: 100px; vertical-align: middle; padding-right: 20px;" /><span class="title"><?php echo $p->post_title; ?></span> <a class="insert-select-pdf describe-toggle-on" href="#<?php echo $p->ID; ?>?<?php echo $pdf; ?>" id="pdf-<?php echo $p->ID; ?>">Select</a> </div>
		<?php } ?>
	</div>
</div>
<script type="text/javascript">
jQuery(window).ready(function($){
	var slug = '<?php echo $_GET['slug']; ?>';
	$('a.insert-select-pdf').click(function(){
	
		var id_src = $(this).attr('href').split('#')[1].split('?');
		
		if($(this).hasClass('remove')){		
			top.select_pdf_remove(id_src[0], slug);		
			$(this).removeClass('remove');
			$(this).text('Select');	
		}else{
			top.select_pdf_add(id_src[1], id_src[0], slug);
			$(this).addClass('remove');
			$(this).text('Remove');	
		}
		return false;
	});
	var select_pdf_ids = $(window.parent.document).find('#<?php echo $_GET['slug']; ?>').val().split('|');
	$('a.insert-select-pdf').each(function(){		
		var id_src = $(this).attr('href').split('#')[1].split('?');
		for(var i = 0; i < select_pdf_ids.length; i++){
			if(select_pdf_ids[i]!=id_src[0]) continue;			
			$(this).addClass('remove');
			$(this).text('Remove');
		}	
	});
});
</script>
<div style="display:none;">
<?php //do_action('media_upload_pdf'); ?>
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
	if($_FILES['upload_pdf'] && $_GET['uploading']){
		_rw_log($_FILES);
		if(!$_FILES['upload_pdf']['error']){		
			
			$wp_upload_dir = wp_upload_dir();
			$uploads_dir = $wp_upload_dir['path'];
			$tmp_name = $_FILES["upload_pdf"]["tmp_name"];
			$name = $_FILES["upload_pdf"]["name"];
			move_uploaded_file($tmp_name, "$uploads_dir/$name");	
			$filename = "$uploads_dir/$name";		
			$wp_filetype = wp_check_filetype($_FILES['upload_pdf']['name'], null );
			$attachment = array(
			 'post_mime_type' => $wp_filetype['type'],
			 'post_title' => $_FILES['upload_pdf']['name'],
			 'post_content' => '',
			 'post_status' => 'inherit'
			);
			list($width, $height, $type, $attr) = getpdfsize($filename);
			$attach_id = wp_insert_attachment( $attachment, $filename);
			$return = wp_create_thumbnail( $attach_id, max($width, $height), max($width, $height) );
			//echo '<pre>'.print_r(getpdfsize($filename), 1).'</pre>';
			if(is_numeric($attach_id) && $attach_id > 0)
				$upload_sucess = 'Your pdf was successfully loaded.';	
		}	
		if($upload_sucess == '')
			$upload_error = 'There was an error uploading your file. Please try again.';
	}
	?>
	<div class="pdf-selector" style="padding: 25px;">
		<form enctype="multipart/form-data" method="post" action="<?php bloginfo('url'); ?>/wp-content/plugins/reed-write/field_types/select_pdf/select_pdf_popup.php?slug=<?php echo $_GET['slug']; ?>&amp;tab=_rw_from_computer&amp;uploading=pdf" class="media-upload-form type-form validate" id="pdf-form">
<!-- input type="submit" name="save" id="save" class="hidden" value="Save Changes"><input type="hidden" name="post_id" id="post_id" value="182" -->
<!-- input type="hidden" id="_wpnonce" name="_wpnonce" value="60681af029"><input type="hidden" name="_wp_http_referer" value="/wp-admin/media-upload.php?post_id=0&amp;type=pdf&amp;" -->
<h3 class="media-title">Add media files from your computer</h3>
<div id="media-upload-notice">
	<?php echo $upload_sucess; ?>
</div>
<div id="media-upload-error">
	<?php echo $upload_error; ?>
</div>
<div id="html-upload-ui" class="hide-if-js" style="display: block; ">
	<p id="async-upload-wrap">
		<label class="screen-reader-text" for="upload_pdf">Upload</label>
		<input type="file" name="upload_pdf" id="upload_pdf">
		<input type="submit" name="html-upload" id="html-upload" class="button" value="Upload">		<a href="#" onclick="try{top.tb_remove();}catch(e){}; return false;">Cancel</a>
	</p>
	<div class="clear"></div>
	<p class="media-upload-size">Maximum upload file size: 2MB</p>
	<p class="upload-html-bypass hide-if-no-js">You are using the Browser uploader. Currently the Flash uploader is not supported for the Reed Write add pdfs field. You may upload pdfs using the Flash uploader on the <a href="<?php bloginfo('url'); ?>/wp-admin/media-new.php">Upload New Media</a> page.</p>
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
