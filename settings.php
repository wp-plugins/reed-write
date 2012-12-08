<?php


$_rw_options = array( 'load_script' => true);

foreach( $_rw_options as $option=>$default )
		$_rw_options[$option] = get_option( "_rw_option_$option", $default );

if($_POST && wp_verify_nonce($_POST['_reedwrite_settings_update_nonce'],'_reedwrite_settings_update') ){
	
	foreach( $_rw_options as $option=>$default ){
		update_option( "_rw_option_$option", $_POST[$option] );
		$_rw_options[$option] = $_POST[$option];
	}
	
}

?>

<div class="wrap">
	<div id="icon-options-general" class="icon32"><br>
	</div>
	<h2>ReedWrite Settings</h2>
	<form name="form" action="options-general.php?page=reedwrite-settings" method="post">
	 	<?php wp_nonce_field('_reedwrite_settings_update','_reedwrite_settings_update_nonce'); ?>
		<p><strong>ReedWrite</strong> allows administrators to make completely custom content types, and custom taxonomies (categories and tags).</p>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">Basic Settings </th>
					<td><fieldset>
							<legend class="screen-reader-text"><span>Basic Settings </span></legend>
							<input id="load_script" type="checkbox" <?php checked( $_rw_options[$option] ); ?> name="load_script" value="1">
							<label for="load_script">Load JavaScript Helper</label>							
							<p class="description">Note: The JavaScript Helper file makes WP_Query requests available through AJAX.</p>
						</fieldset></td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button-primary" value="Save Changes">
		</p>
	</form>
<?php /*?><pre><?php print_r($_POST); ?></pre><?php */?>
</div>
