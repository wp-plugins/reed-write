<?php
function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    $start  = $length * -1; //negative
    return (substr($haystack, $start) === $needle);
}

$results = array();
$handler = opendir(".");
$files = array();
while ($file = readdir($handler)) {
	if ($file != "." && $file != ".." && endsWith($file, "png")) {
		$files[] = $file;
	}
}
closedir($handler);
$rows = '';
foreach($files as $file){

	$rows .= '
	<tr>
		<td style="border-bottom: #999 solid 1px; text-align: right;">'.$file.'</td>
		<td style="border-bottom: #999 solid 1px;">&nbsp;</td>
		<td style="border-bottom: #999 solid 1px; padding: 10px;">
			<img src="/wp-content/plugins/reed-write/icons/'.$file.'" />
		</td>
		<td style="border-bottom: #999 solid 1px; padding: 10px; background: #777;">
			<img src="/wp-content/plugins/reed-write/icons/'.$file.'" />
		</td>
	</tr>
	';
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Icons</title>
</head>
<body style="color:#333; font-size: 12px; padding: 0 0 0 50px; font-family: 'Lucida Sans Unicode', 'Lucida Grande', sans-serif;">
<h1 style="color:#000;">Icons</h1>
<table cellpadding="0" cellspacing="0">
	<?php echo $rows; ?>
</table>
</body>
</html>