<?php
global $current_user;
get_currentuserinfo();
$cuser = $current_user->ID;
$user_id = $cuser;

$upload_path = WP_CONTENT_DIR . "/uploads/wpgs";

$up_path1 = ''.$upload_path.'/'. $user_id .'/';
if (!file_exists($up_path1)) mkdir($up_path1);

$up_path2 = ''.$upload_path.'/'. $user_id .'/buttons';
$up_path5 = ''.$upload_path.'/'. $user_id .'/buttons/thumbs';
if (!file_exists($up_path2)) mkdir($up_path2);
if (!file_exists($up_path5)) mkdir($up_path5);

function set_html_content_type () {

	return 'text/html';
}

	function IsEmailSent($id,$user_id){
		return get_post_meta($id, 'EmailSent', $user_id);
	}

	function IsReSent($id,$user_id){
		return get_post_meta($id, 'ReSent', $user_id);
	}

function emailGraphic($user_id) {

	global $wpdb;
			if ( !IsEmailSent($user_id,EmailSent,$_POST['file']) ){

        $user_obj = get_userdata( $user_id );
        $downemail = $user_obj->user_email;

	   $attachments = array( WP_CONTENT_DIR . '/uploads/wpgs/'.$user_id.'/buttons/'. $_POST['file'] .'' );
	   $headers = 'From: ' . get_bloginfo( "name" ) . ' <' . get_bloginfo( "admin_email" ) . '>' . "\r\n";
add_filter( 'wp_mail_content_type', 'set_html_content_type' );
$eGraphic = @wp_mail($downemail, 'Your custom graphic from ' . get_bloginfo( "name" ) . '', 'Thank you for using ' . get_bloginfo("name") . ' for your graphic creation needs.<br>Your custom generated graphic is attached.', $headers, $attachments, 0);

if($eGraphic){
update_post_meta($user_id, 'EmailSent', $_POST['file']);
}
remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
}
}

function ResendGraphic($user_id) {

	global $wpdb;

			if (!IsReSent($user_id,'ReSent',$_POST['file'])) {

        $user_obj = get_userdata( $user_id );
        $downemail = $user_obj->user_email;

	   $attachments = array( WP_CONTENT_DIR . '/uploads/wpgs/'.$user_id.'/buttons/'. $_POST['file'] .'' );
	   $headers = 'From: ' . get_bloginfo( "name" ) . ' <' . get_bloginfo( "admin_email" ) . '>' . "\r\n";
add_filter( 'wp_mail_content_type', 'set_html_content_type' );
$eGraphic = @wp_mail($downemail, 'Your custom graphic from ' . get_bloginfo( "name" ) . '', 'Thank you for using ' . get_bloginfo("name") . ' for your graphic creation needs.<br>Your custom generated graphic is attached.', $headers, $attachments, 0);

if($eGraphic){
update_post_meta($user_id, 'ReSent', $_POST['file']);
delete_post_meta($user_id, 'EmailSent', $_POST['file']);

}
remove_filter( 'wp_mail_content_type', 'set_html_content_type' ); ?>
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
    background-image:url('<?php echo $fileurl ?>/wp-content/uploads/wpgs/images/buttons/success.png');
}
</style>
<div class="isa_success">Your Buttons Graphic Has Been eMailed to the eMail address on file.</div>
<?php
}
}

if(($_POST['delete'] != '') && ($user_id != '')) {
$wp_upload_dir = wp_upload_dir();
$myFile = $wp_upload_dir['basedir'] . '/wpgs/'. $user_id .'/buttons/'. $_POST['file'] .'';
$myThumb = $wp_upload_dir['basedir'] . '/wpgs/'. $user_id .'/buttons/thumbs/THUMB_'. $_POST['file'] .'';
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
    background-image:url('<?php echo $fileurl ?>/wp-content/uploads/wpgs/images/buttons/success.png');
}
</style>
<div class="isa_success">Your Buttons Graphic Has Been Deleted From Your Gallery Below</div>
<?php }

if((isset($_POST['email'])) && ($_POST['email'] != '') && ($user_id != '')) {
emailGraphic("$user_id");
}

if ($_POST['image'] != '') {
$data = base64_decode($_POST["image"]);
$im = imagecreatefromstring($data);
//false for image not made with truecolors
imagealphablending($im, false);
//forces to make alpha channel
imagesavealpha($im, true);

$image = $im;

$upload_path = WP_CONTENT_DIR . "/uploads/wpgs";

$up_path1 = ''.$upload_path.'/'. $user_id .'/';
if (!file_exists($up_path1)) mkdir($up_path1);

$up_path2 = ''.$upload_path.'/'. $user_id .'/buttons';
$up_path5 = ''.$upload_path.'/'. $user_id .'/buttons/thumbs';
if (!file_exists($up_path2)) mkdir($up_path2);
if (!file_exists($up_path5)) mkdir($up_path5);

$newName = rand(1, 1000000);

if (($_POST['format'] == 'jpg') && ($_POST['format'] != '')) {
imagejpeg($im, ''.$upload_path.'/'. $user_id .'/buttons/'. $newName .'.'. $_POST['format'] .'');
$file = ''.$upload_path.'/'. $user_id .'/buttons/'. $newName .'.'. $_POST['format'] .''; // (physical path)
chmod(''.$upload_path.'/'. $user_id .'/buttons/'. $newName .'.'. $_POST['format'] .'', 0777);
}
if (($_POST['format'] == 'png') && ($_POST['format'] != '')) {
imagepng($im, ''.$upload_path.'/'. $user_id .'/buttons/'. $newName .'.'. $_POST['format'] .'');
$file = ''.$upload_path.'/'. $user_id .'/buttons/'. $newName .'.'. $_POST['format'] .''; // (physical path)
chmod(''.$upload_path.'/'. $user_id .'/buttons/'. $newName .'.'. $_POST['format'] .'', 0777);
}

$fileurl = get_option( 'siteurl' );
$file = ''.$fileurl.'/wp-content/uploads/wpgs/'. $user_id .'/buttons/'. $newName .'.'. $_POST['format'] .''; // (physical path)
$filePath = ''.$fileurl.'/wp-content/uploads/wpgs/'. $user_id .'/buttons/'. $newName .'.'. $_POST['format'] .''; // (physical path)

$width = imagesx($im);
$height = imagesy($im);

if ($width <= 300) {
	$new_width = $width;
	$new_height = floor($height*($width/$width));
}

if ($width > 300) {
	$new_width = 300;
	$new_height = floor($height*(300/$width));
}

	$thumb = imagecreatetruecolor( $new_width, $new_height );

imagealphablending($thumb, false);
imagesavealpha($thumb, true);

    imagecopyresampled($thumb, $im, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
if ($_POST['format'] == 'jpg') {
    imagejpeg($thumb, ''.$upload_path.'/'. $user_id .'/buttons/thumbs/THUMB_'. $newName .'.'. $_POST['format'] .'');
}
if ($_POST['format'] == 'png') {
    imagepng($thumb, ''.$upload_path.'/'. $user_id .'/buttons/thumbs/THUMB_'. $newName .'.'. $_POST['format'] .'');
    }
imagedestroy($im);
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
    background-image:url('<?php echo $fileurl ?>/wp-content/uploads/wpgs/images/buttons/success.png');
}
</style>
<div class="isa_success">
Your created button Has Been Saved To Your buttons Graphic Gallery For Future Use.
<br>Click on your created button below to save to your computer....<br>
<b>Close This Window When Download Has Completed To Return To The buttons Module.</b>
</div>
<div width="100%" align="center">
<div align="center"><a href="<?php echo $filePath ?>" download="<?php echo $newName ?>"><img src="<?php echo $filePath ?>" width="300px"></a></div>
</div>

<?php
} if ($_POST['create'] != '') {
$logo = get_option( 'wpgs_wpgraphicstudio_logo_url' );
$nav_hex = get_option( 'wpgs_wpgraphicstudio_nav_hex' );
?><div width="100%" align="center">
<object id="flashcontent" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="850px" height="450px">
<?php echo'<param name="movie" value="'.plugins_url( '' ).'/buttons/buttons.swf?logoURL='.$logo.'&NavColor='.$nav_hex.'" />'; ?>
  <!--[if !IE]>-->
  <?php echo '<object type="application/x-shockwave-flash" data="'.plugins_url( '' ).'/buttons/buttons.swf?logoURL='.$logo.'&NavColor='.$nav_hex.'" width="850px" height="450px">'; ?>
  <!--<![endif]-->
  </div>
    <p>
      No graphic creation selection has been made or that module is not available.<br>
      Pleae contact site support to request that module be added.</p>

  <!--[if !IE]>-->
  </object>
  <!--<![endif]-->
</object></div>
<?php }
if (($_POST['view'] == 'gallery') || ($_POST['gallery'] == 'yes')) {

$upload_path = WP_CONTENT_DIR . "/uploads/wpgs";

$up_path1 = ''.$upload_path.'/'. $user_id .'/';
if (!file_exists($up_path1)) mkdir($up_path1);

$up_path2 = ''.$upload_path.'/'. $user_id .'/buttons';
$up_path5 = ''.$upload_path.'/'. $user_id .'/buttons/thumbs';
if (!file_exists($up_path2)) mkdir($up_path2);
if (!file_exists($up_path5)) mkdir($up_path5);

$full_url = ''.$fileurl.'/wp-content/uploads/wpgs/'.$user_id.'/buttons/';
$wp_upload_dir = wp_upload_dir();
$full_path = $wp_upload_dir['basedir'] . '/wpgs/'. $user_id .'/buttons/';

$thumbpath = $full_url . "thumbs/";

# Set number of thumbs_per_page.

$thumbs_per_page = $thumbs_per_page = get_option( 'wpgs_wpgraphicstudio_per_gallery' );

# Set the thumb_max height/width in pixels.

$thumb_max = 200;

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
<div align="center" style="width:100%; align:center;">
<style type="text/css"> body { background-color: #FFF; } object { outline:none; }</style>

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
		print "<a href=\"/graphic-gallery/buttons/2/?$i\">$i</a>\n";
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
		$path = "$pA";

		$file = basename($path); // $file is set to "index"
		$file_name = $file;

			if(!file_exists($thumbpath."THUMB_".$pA))
			{

			print "<li><a href=\"".$full_url.$pA."\" download=\"$pA\"><img src=\"".$full_url."thumbs/THUMB_$pA\" alt=\"$pA\" /></a>\n<br>";

print "<div style=\"float:left;\" align=\"center\"><form action=\"/buttons\" id=\"Delete\" method=\"post\"><input type=\"hidden\" name=\"file\" value=\"$file_name\"><input type=\"hidden\" name=\"view\" value=\"gallery\"><input type=\"hidden\" name=\"delete\" value=\"1\"><a href=\"javascript:;\" onclick=\"javascript:
document.getElementById('Delete').submit()\" style=\"color:#494c4d; text-decoration:none;\"><strong>Delete</strong></a></form></div>";
print "<div style=\"float:left;\" align=\"center\">&nbsp;|&nbsp;<a href=\"".$full_url.$pA."\" download=\"$pA\" style=\"color:#494c4d; text-decoration:none;\"><strong>Download</strong></a>&nbsp;|&nbsp;</div>";
print "<div style=\"float:left;\" align=\"center\"><form action=\"/buttons\" id=\"eMail\" method=\"post\"><input type=\"hidden\" name=\"file\" value=\"$file_name\"><input type=\"hidden\" name=\"view\" value=\"gallery\"><input type=\"hidden\" name=\"email\" value=\"1\"><a href=\"javascript:;\" onclick=\"javascript:
document.getElementById('eMail').submit()\" style=\"color:#494c4d; text-decoration:none;\"><strong>Email</strong></a></form></div>";

print "<br><div style=\"float:left;\" align=\"center\"><strong><a href=\"/create-graphic/\" style=\"color:#494c4d; text-decoration:none;\">Create New Graphic</a></strong></div></li>\n";
			}
			else
			{
			print "<a href=\"".$full_url.$pA."\"><img src=\"".$full_url."thumbs/THUMB_$pA\" alt=\"$pA\" /></a>\n";
			}
		}
	$count++;
	}

print "</ul>\n<div class=\"clear\">&nbsp;</div>\n";
}
else
{
print "<p>You currently have no graphics in this gallery </p>";
} ?>
</div>
<?php }
?>