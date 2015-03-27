<?php
global $current_user;
get_currentuserinfo();
$cuser = $current_user->ID;
$user_id = $cuser;

$uri = $_SERVER['REQUEST_URI'];
$elms = explode('/', $uri) ;
$value1 = $elms[1] ; // for the first parameter
$value2 = $elms[2] ; //for the 2nd parameter and so on
$value3 = $elms[3] ; //for the 3rd parameter and so on
$value4 = $elms[4] ; //for the 4th parameter and so on
$value5 = $elms[5] ; //for the 5th parameter and so on
$value6 = $elms[6] ; //for the 6th parameter and so on

if(($_POST['delete'] != '') && ($user_id != '')) {
$wp_upload_dir = wp_upload_dir();
$myFile = $wp_upload_dir['basedir'] . '/wpgs/'. $user_id .'/ecover-designer/'. $_POST['file'] .'.png';
$myThumb = $wp_upload_dir['basedir'] . '/wpgs/'. $user_id .'/ecover-designer/thumbs/THUMB_'. $_POST['file'] .'.png';
delete_post_meta($_POST['file'], 'EmailSent', $user_id);
delete_post_meta($_POST['file'], 'ReSent', $user_id);
unlink($myFile);
unlink($myThumb);
$upload_path = WP_CONTENT_DIR . "/uploads/wpgs";
$fileurl = get_option( 'siteurl' );

?>
<style type="text/css">
.isa_info, .isa_success, .isa_warning, .isa_error {
    border: 1px solid;
    margin: 10px 0px;
    padding:15px 10px 15px 50px;
    background-repeat: no-repeat;
    background-position: 10px center;-moz-border-radius:.5em;
-webkit-border-radius:.5em;
border-radius:.5em;

}
.isa_success {
    color: #4F8A10;
    background-color: #DFF2BF;
    background-image:url('<?php echo $fileurl ?>/wp-content/uploads/wpgs/images/ecover-designer/success.png');
}
</style>
<div class="isa_success">Your Flat eCover Design Has Been Deleted From Your Gallery Below</div>
<?php }

if (($value2 == '') && ($value3 == '')) {
$location = $_POST['style'];

# Create folder named thumbs inside your gallery,
# Be sure to set the thumbs folder permissions to 777.
# You do not need to edit the thumbpath variable.
$fileurl = get_option( 'siteurl' );
$link_url = ''.$fileurl.'/';

$full_url = ''.$fileurl.'/wp-content/uploads/wpgs/images/gallery/';
$wp_upload_dir = wp_upload_dir();
$full_path = $wp_upload_dir['basedir'] . '/wpgs/images/gallery/';

$thumbpath = $full_url . "thumbs/";

# Set number of thumbs_per_page.

# Set number of thumbs_per_page.
$thumbs_per_page = get_option( 'wpgs_wpgraphicstudio_per_members' );

if ($num_thumbs != '') {
$thumbs_per_page = get_option( 'wpgs_wpgraphicstudio_per_members' );
}
else {
$thumbs_per_page = 10;
}

# Set the thumb_max height/width in pixels.

$thumb_max = 100;

# Set build_square_thumbs 1 = yes, 0 = no.

$build_square_thumbs = 1;

# STOP! Let Auto Gallery create your thumbs on the fly using a lower
# quality (faster) image redrawing. Once you have your page set up just
# how you like it, then change these next two variables to 1 = yes.

$build_permanent_thumbs = 0;
$use_better_quality = 0;

# Once you have created your permanent thumbs you will have to delete them,
# before you can rebuild them if you change either one of the ($thumb_max,
# $build_square_thumbs, or $use_better_quality) variables.
# Older thumbs will not be overwritten.

# END SETTING VARIABLES ###################

$query = (strlen($_SERVER['QUERY_STRING']) > 0) ? $_SERVER['QUERY_STRING'] : 1;

$pictureArray = array();
?>
<head>
<link rel="stylesheet" href="<?php echo $fileurl ?>/wp-content/plugins/wp-graphic-studio/assets/css/style.css" type="text/css" media="all" />
</head>

<?php
if($handle = opendir($full_path))
{
	while (false !== ($entry = readdir($handle)))
	{
		if($entry != "." && $entry != ".." && !is_dir($full_path.$entry)
		&& preg_match("/jpg|png/", end(explode(".", strtolower($entry)))))
		{
		array_push($pictureArray,$entry);
		}
	}

closedir($handle);
}

$num_of_pages = ceil(count($pictureArray)/$thumbs_per_page);

if($num_of_pages > 1)
{
print "<p>\n";

	for($i=1;$i<=$num_of_pages;$i++)
	{
		if($i == $query)
		{
		print "$i\n";
		}
		else
		{
		print "<a href=\"?$i\">$i</a>\n";
		}
	}

	if($query < $num_of_pages)
	{
	print "... <a href=\"?". ($query+1) ."\">next</a>\n";
	}
	else
	{
	print "... <a href=\"?1\">next</a>\n";
	}

print "</p>\n";
}

$count = 0;

$start = ($query-1)*$thumbs_per_page;

if(isset($pictureArray[0]))
{
sort($pictureArray);

print "<ul class=\"thumbs\">\n";

	foreach($pictureArray as $pA)
	{
		if($count >= $start && $count < ($start+$thumbs_per_page))
		{
		$path = "'.$dir.'/created/$user_id/$value2/$value3/$pA";

		$file = basename($path); // $file is set to "index"
		$file = basename($path, ".png");
		$file_name = $file;

$image_width = '460px';

			if(!file_exists($thumbpath."THUMB_".$pA))
			{
			print "<li><form action=\"/$file_name\" method=\"post\"><input type=\"hidden\" name=\"gallery\" value=\"yes\"><input type=\"image\" src=\"".$full_url.$pA."\" width=\"$image_width\" alt=\"$pA\" /></form>\n";
}
			else
			{
			print "<a href=\"".$full_url.$pA."\"><img src=\"".$full_url."thumbs/$pA\" alt=\"$pA\" /></a>\n";
			}
		}
	$count++;
	}

print "</ul>\n<div class=\"clear\">&nbsp;</div>\n";
}}
if ((isset($value2)) && ($value2 != 'templates')) {
# Create folder named thumbs inside your gallery,
# Be sure to set the thumbs folder permissions to 777.
# You do not need to edit the thumbpath variable.
$fileurl = get_option( 'siteurl' );
$link_url = ''.$fileurl.'/';

if ($value3 == '2') {
$full_url = ''.$fileurl.'/wp-content/uploads/wpgs/'.$user_id.'/'.$value2.'/';
$wp_upload_dir = wp_upload_dir();
$full_path = $wp_upload_dir['basedir'] . '/wpgs/'.$user_id.'/'.$value2.'/';
$location = $_POST['style'];
}
if ($value3 != '2') {
$full_url = ''.$fileurl.'/wp-content/uploads/wpgs/'.$user_id.'/'.$value2.'/'.$value3.'/';
$wp_upload_dir = wp_upload_dir();
$full_path = $wp_upload_dir['basedir'] . '/wpgs/'.$user_id.'/'.$value2.'/'.$value3.'/';
$location = $_POST['style'];
}

$thumbpath = $full_url . "thumbs/";

# Set number of thumbs_per_page.

$thumbs_per_page = 20;

# Set the thumb_max height/width in pixels.

$thumb_max = 300;

# Set build_square_thumbs 1 = yes, 0 = no.

$build_square_thumbs = 1;

# STOP! Let Auto Gallery create your thumbs on the fly using a lower
# quality (faster) image redrawing. Once you have your page set up just
# how you like it, then change these next two variables to 1 = yes.

$build_permanent_thumbs = 0;
$use_better_quality = 0;

# Once you have created your permanent thumbs you will have to delete them,
# before you can rebuild them if you change either one of the ($thumb_max,
# $build_square_thumbs, or $use_better_quality) variables.
# Older thumbs will not be overwritten.

# END SETTING VARIABLES ###################

$query = (strlen($_SERVER['QUERY_STRING']) > 0) ? $_SERVER['QUERY_STRING'] : 1;

$pictureArray = array();
?>
<head>
<link rel="stylesheet" href="<?php echo $fileurl ?>/wp-content/plugins/wp-graphic-studio/assets/css/style.css" type="text/css" media="all" />
</head>

<?php
if($handle = opendir($full_path))
{
	while (false !== ($entry = readdir($handle)))
	{
		if($entry != "." && $entry != ".." && !is_dir($full_path.$entry)
		&& preg_match("/jpg|png/", end(explode(".", strtolower($entry)))))
		{
		array_push($pictureArray,$entry);
		}
	}

closedir($handle);
}

$num_of_pages = ceil(count($pictureArray)/$thumbs_per_page);

if($num_of_pages > 1)
{
print "<p><b>Page:</b>\n";

	for($i=1;$i<=$num_of_pages;$i++)
	{
		if($i == $query)
		{
		print "$i\n";
		}
		else
		{
		print "<a href=\"?$i\">$i</a>\n";
		}
	}

print "</p>\n";
}

$count = 0;

$start = ($query-1)*$thumbs_per_page;

if(isset($pictureArray[0]))
{
sort($pictureArray);

print "<ul class=\"thumbs\">\n";

	foreach($pictureArray as $pA)
	{
		if($count >= $start && $count < ($start+$thumbs_per_page))
		{
		$path = "'.$dir.'/created/$user_id/$value2/$value3/$pA";

		$file = basename($path); // $file is set to "index"
		$file = basename($path, ".png");
		$file_name = $file;

$image_width = '460px';

			if(!file_exists($thumbpath."THUMB_".$pA))
			{
if ($value2 == 'ecover-designer') {
print "<li><a href=\"".$full_url.$pA."\"><img src=\"".$full_url."$pA\" width=\"300px\" alt=\"$pA\" /></a>\n<br>";
print "<div style=\"float:left;\" align=\"center\"><form action=\"/$value2\" id=\"Delete\" method=\"post\"><input type=\"hidden\" name=\"file\" value=\"$file_name\"><input type=\"hidden\" name=\"view\" value=\"gallery\">
<input type=\"hidden\" name=\"style\" value=\"$value3\"><input type=\"hidden\" name=\"delete\" value=\"1\"><a href=\"javascript:;\" onclick=\"javascript:
document.getElementById('Delete').submit()\" style=\"color:#494c4d; text-decoration:none;\"><strong>Delete</strong></a></form></div>";
print "<div style=\"float:left;\" align=\"center\">&nbsp;|&nbsp;<a href=\"".$full_url.$pA."\" download=\"$pA\" style=\"color:#494c4d; text-decoration:none;\"><strong>Download</strong></a>&nbsp;|&nbsp;</div>";
print "<div style=\"float:left;\" align=\"center\"><form action=\"/graphic-gallery\" id=\"eMail\" method=\"post\"><input type=\"hidden\" name=\"file\" value=\"$file_name\"><input type=\"hidden\" name=\"view\" value=\"gallery\">
<input type=\"hidden\" name=\"style\" value=\"$location\"><input type=\"hidden\" name=\"email\" value=\"1\"><a href=\"javascript:;\" onclick=\"javascript:
document.getElementById('eMail').submit()\" style=\"color:#494c4d; text-decoration:none;\"><strong>Email</strong></a></form></div>";
print "<br><div style=\"float:left;\" align=\"center\"><a href=\"/create-graphic\" style=\"color:#494c4d; text-decoration:none;\"><strong>Create New Graphic</strong></a></div>\n";
print "<br><div style=\"float:left;\" align=\"center\"><strong><form action=\"/ecover-designer\" id=\"Styles\" method=\"post\">
<input type=\"hidden\" name=\"file\" value=\"$file_name\"><input type=\"hidden\" name=\"view\" value=\"templates\"><input type=\"hidden\" name=\"direct\" value=\"uploads\">
<input type=\"hidden\" name=\"templates\" value=\"ecovers\"><a href=\"javascript:;\" onclick=\"javascript:
document.getElementById('Styles').submit()\" style=\"color:#494c4d; text-decoration:none;\"><strong>Generate eCover</strong></a></form></strong></div></li>\n";
} if ($value2 == 'standard-optin-pages') {
print "<li><a href=\"".$full_url.$pA."\"><img src=\"".$full_url."thumbs/THUMB_$file_name.png\" alt=\"$pA\" /></a>\n<br>";
print "<div style=\"float:left;\" align=\"center\"><form action=\"/standard-optin-pages\" id=\"Delete\" method=\"post\"><input type=\"hidden\" name=\"file\" value=\"$file_name\"><input type=\"hidden\" name=\"view\" value=\"gallery\"><input type=\"hidden\" name=\"delete\" value=\"1\"><a href=\"javascript:;\" onclick=\"javascript:
document.getElementById('Delete').submit()\" style=\"color:#494c4d; text-decoration:none;\"><strong>Delete</strong></a></form></div>";
print "<div style=\"float:left;\" align=\"center\">&nbsp;|&nbsp;<a href=\"".$full_url.$file_name."/$file_name.zip\" download=\"$file_name.zip\" style=\"color:#494c4d; text-decoration:none;\"><strong>Download</strong></a>&nbsp;|&nbsp;</div>";
print "<div style=\"float:left;\" align=\"center\"><form action=\"/standard-optin-pages\" id=\"eMail\" method=\"post\"><input type=\"hidden\" name=\"file\" value=\"$file_name\"><input type=\"hidden\" name=\"view\" value=\"gallery\"><input type=\"hidden\" name=\"email\" value=\"1\"><a href=\"javascript:;\" onclick=\"javascript:document.getElementById('eMail').submit()\" style=\"color:#494c4d; text-decoration:none;\"><strong>Email</strong></a></form></div><br>";
print "<br><div style=\"float:left;\" align=\"center\"><form action=\"/standard-optin-pages\" id=\"Edit\" method=\"post\"><input type=\"hidden\" name=\"file\" value=\"$file_name\"><input type=\"hidden\" name=\"edit\" value=\"template\"><a href=\"javascript:;\" onclick=\"javascript:document.getElementById('Edit').submit()\" style=\"color:#494c4d; text-decoration:none;\"><strong>Edit</strong></a>&nbsp;|&nbsp;</form></div>";
print "<div style=\"float:left;\" align=\"center\"><strong><a href=\"/create-graphic/\" style=\"color:#494c4d; text-decoration:none;\">Create New Graphic</a></strong></div></li>\n";
} if (($value2 != 'ecover-designer') && ($value2 != 'standard-optin-pages')) {
print "<li><a href=\"".$full_url.$pA."\"><img src=\"".$full_url."$pA\" width=\"300px\" alt=\"$pA\" /></a>\n<br>";
print "<div style=\"float:left;\" align=\"center\"><form action=\"/$value2\" id=\"Delete\" method=\"post\"><input type=\"hidden\" name=\"file\" value=\"$file_name\"><input type=\"hidden\" name=\"view\" value=\"gallery\">
<input type=\"hidden\" name=\"style\" value=\"$value3\"><input type=\"hidden\" name=\"delete\" value=\"1\"><a href=\"javascript:;\" onclick=\"javascript:
document.getElementById('Delete').submit()\" style=\"color:#494c4d; text-decoration:none;\"><strong>Delete</strong></a></form></div>";
print "<div style=\"float:left;\" align=\"center\">&nbsp;|&nbsp;<a href=\"".$full_url.$pA."\" download=\"$pA\" style=\"color:#494c4d; text-decoration:none;\"><strong>Download</strong></a>&nbsp;|&nbsp;</div>";
print "<div style=\"float:left;\" align=\"center\"><form action=\"/graphic-gallery\" id=\"eMail\" method=\"post\"><input type=\"hidden\" name=\"file\" value=\"$file_name\"><input type=\"hidden\" name=\"view\" value=\"gallery\">
<input type=\"hidden\" name=\"style\" value=\"$location\"><input type=\"hidden\" name=\"email\" value=\"1\"><a href=\"javascript:;\" onclick=\"javascript:
document.getElementById('eMail').submit()\" style=\"color:#494c4d; text-decoration:none;\"><strong>Email</strong></a></form></div>";
print "<br><div style=\"float:left;\" align=\"center\"><a href=\"/create-graphic\" style=\"color:#494c4d; text-decoration:none;\"><strong>Create New Graphic</strong></a></div></li>\n";
}}
			else
			{
			print "<a href=\"".$full_url.$pA."\"><img src=\"".$full_url."thumbs/$pA\" alt=\"$pA\" /></a>\n";
			}
		}
	$count++;
	}

print "</ul>\n<div class=\"clear\">&nbsp;</div>\n";
}
}

if ((isset($value2)) && ($value2 == 'templates')) {
# Create folder named thumbs inside your gallery,
# Be sure to set the thumbs folder permissions to 777.
# You do not need to edit the thumbpath variable.
$fileurl = get_option( 'siteurl' );
$link_url = ''.$fileurl.'/';

if (($value3 != '') && ($value2 != '') && ($value4 == '1')) {
$full_url = ''.$fileurl.'/wp-content/uploads/wpgs/images/'.$value3.'/'.$value2.'/';
$wp_upload_dir = wp_upload_dir();
$full_path = $wp_upload_dir['basedir'] . '/wpgs/images/'.$value3.'/'.$value2.'/';
$location = $_POST['style'];
}
if ($value5 == '3') {
$full_url = ''.$fileurl.'/wp-content/uploads/wpgs/images/'.$value3.'/'.$value4.'/'.$value2.'/';
$wp_upload_dir = wp_upload_dir();
$full_path = $wp_upload_dir['basedir'] . '/wpgs/images/'.$value3.'/'.$value4.'/'.$value2.'/';
$location = $_POST['style'];
}

if (($value4 == '1') && ($value2 != 'templates')) {
$width = '150px';
}
if ($value3 == 'standard-optin-pages') {
$width = '300px';
$height = '200px';
}
if ($value5 == '3') {
$width = '460px';
}

$thumbpath = $full_url . "thumbs/";

# Set number of thumbs_per_page.

if ($value4 == '1') {
$thumbs_per_page = '24';
}
if ($value5 == '3') {
$thumbs_per_page = '20';
}
if ($value3 == 'standard-optin-pages') {
$thumbs_per_page = '15';
}

# Set the thumb_max height/width in pixels.

$thumb_max = 300;

# Set build_square_thumbs 1 = yes, 0 = no.

$build_square_thumbs = 20;

# STOP! Let Auto Gallery create your thumbs on the fly using a lower
# quality (faster) image redrawing. Once you have your page set up just
# how you like it, then change these next two variables to 1 = yes.

$build_permanent_thumbs = 0;
$use_better_quality = 0;

# Once you have created your permanent thumbs you will have to delete them,
# before you can rebuild them if you change either one of the ($thumb_max,
# $build_square_thumbs, or $use_better_quality) variables.
# Older thumbs will not be overwritten.

# END SETTING VARIABLES ###################

$query = (strlen($_SERVER['QUERY_STRING']) > 0) ? $_SERVER['QUERY_STRING'] : 1;

$pictureArray = array();
?>
<head>
<link rel="stylesheet" href="<?php echo $fileurl ?>/wp-content/plugins/wp-graphic-studio/assets/css/style.css" type="text/css" media="all" />
</head>

<?php
if($handle = opendir($full_path))
{
	while (false !== ($entry = readdir($handle)))
	{
		if($entry != "." && $entry != ".." && !is_dir($full_path.$entry)
		&& preg_match("/jpg|png/", end(explode(".", strtolower($entry)))))
		{
		array_push($pictureArray,$entry);
		}
	}

closedir($handle);
}

$num_of_pages = ceil(count($pictureArray)/$thumbs_per_page);

if($num_of_pages > 1)
{
print "<p><b>Page:</b>\n";

	for($i=1;$i<=$num_of_pages;$i++)
	{
		if($i == $query)
		{
		print "$i\n";
		}
		else
		{
		print "<a href=\"?$i\">$i</a>\n";
		}
	}

print "</p>\n";
}

$count = 0;

$start = ($query-1)*$thumbs_per_page;

if(isset($pictureArray[0]))
{
sort($pictureArray);

print "<ul class=\"thumbs\">\n";

	foreach($pictureArray as $pA)
	{
		if($count >= $start && $count < ($start+$thumbs_per_page))
		{
		$path = "'.$dir.'/created/$user_id/$value2/$value3/$pA";

		$file = basename($path); // $file is set to "index"
		$file = basename($path, ".png");
		$file_name = $file;

$image_width = '460px';
			if ($value3 == 'standard-optin-pages') {
			$choice = 'template';
			}
			else {
			$choice = $value3;
			}

			if(!file_exists($thumbpath."THUMB_".$pA))
			{
			print "<li><form action=\"/$value3\" method=\"post\">
			<input type=\"hidden\" name=\"create\" value=\"$choice\">
			<input type=\"hidden\" name=\"template\" value=\"".$file_name."\">";
			if ($value3 == 'standard-optin-pages') {
			print "<input type=\"image\" src=\"".$full_url."$pA\" width=\"$width\" height=\"$height\" alt=\"$pA\" /></form>\n<br>";
}
else {
			print "<input type=\"image\" src=\"".$full_url."$pA\" width=\"$width\" alt=\"$pA\" /></form>\n<br>";
}
}
			else
			{
			print "<form action=\"/$value3\" method=\"post\">
			<input type=\"hidden\" name=\"create\" value=\"'.$choice.'\">
			<input type=\"hidden\" name=\"template\" value=\"".$file_name."\">
			<img src=\"".$full_url."thumbs/$pA\" alt=\"$pA\" /></a></form>\n";
			}
		}
	$count++;
	}

print "</ul>\n<div class=\"clear\">&nbsp;</div>\n";
}
else
{
print "<p>You currently have no graphics in this gallery </p>";
}}

?>