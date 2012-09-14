<?php
	require ( current(explode('wp-content', dirname(__FILE__))).'wp-load.php' );
	header("Content-type: text/css");
if(0){ ?>
<style>
.menu-icon-rw_content_type
<?php
}
global $_rw_content_types;
foreach($_rw_content_types as $type){ 
	$slug = $type['arguments']['slug'];
	echo "
/* $slug */
#adminmenu li.menu-icon-$slug div.wp-menu-image {overflow:hidden}
#adminmenu li.menu-icon-$slug div.wp-menu-image img {margin-top:-32px;opacity:1;filter:alpha(opacity=100)}
#adminmenu li.menu-icon-$slug.wp-has-current-submenu div.wp-menu-image img,
#adminmenu li.menu-icon-$slug:hover div.wp-menu-image img {margin-top:0}
"; } ?>

#icon-more_content_menu{background: transparent url(/wp-admin/images/icons32.png?ver=20100531) no-repeat -492px -5px;}
