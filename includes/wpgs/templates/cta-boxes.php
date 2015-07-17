<?php
global $current_user;
get_currentuserinfo();
$cuser = $current_user->ID;
$user_id = $cuser;
$upload_dir = wp_upload_dir();
$upload_path = $upload_dir['basedir'];

$up_path1 = ''.$upload_path.'/'. $user_id .'/';

$up_path2 = ''.$upload_path.'/wpgs/'. $user_id .'/cta-boxes';
$up_path5 = ''.$upload_path.'/wpgs/'. $user_id .'/cta-boxes/thumbs';

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
	$emailFile = sanitize_file_name( $_POST['file'] );

			if ( !IsEmailSent($user_id,EmailSent,$emailFile) ){

        $user_obj = get_userdata( $user_id );
        $downemail = $user_obj->user_email;

	   $attachments = array( $upload_path.'/wpgs/'.$user_id.'/cta-boxes/'.$emailFile.'' );
	   $headers = 'From: ' . get_bloginfo( "name" ) . ' <' . get_bloginfo( "admin_email" ) . '>' . "\r\n";
add_filter( 'wp_mail_content_type', 'set_html_content_type' );
$eGraphic = @wp_mail($downemail, 'Your custom graphic from ' . get_bloginfo( "name" ) . '', 'Thank you for using ' . get_bloginfo("name") . ' for your graphic creation needs.<br>Your custom generated graphic is attached.', $headers, $attachments, 0);

if($eGraphic){
update_post_meta($user_id, 'EmailSent', $emailFile);
}
remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
}
}

function ResendGraphic($user_id) {
	$emailFile = sanitize_file_name( $_POST['file'] );
	global $wpdb;

			if (!IsReSent($user_id,'ReSent',$emailFile)) {

        $user_obj = get_userdata( $user_id );
        $downemail = $user_obj->user_email;

	   $attachments = array( $upload_path . '/wpgs/'.$user_id.'/cta-boxes/'. $emailFile .'' );
	   $headers = 'From: ' . get_bloginfo( "name" ) . ' <' . get_bloginfo( "admin_email" ) . '>' . "\r\n";
add_filter( 'wp_mail_content_type', 'set_html_content_type' );
$eGraphic = @wp_mail($downemail, 'Your custom graphic from ' . get_bloginfo( "name" ) . '', 'Thank you for using ' . get_bloginfo("name") . ' for your graphic creation needs.<br>Your custom generated graphic is attached.', $headers, $attachments, 0);

if($eGraphic){
update_post_meta($user_id, 'ReSent', $emailFile);
delete_post_meta($user_id, 'EmailSent', $emailFile);

}
remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
$fileurl = plugins_url( 'includes/wpgs/images', dirname(__FILE__) );
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
    background-image:url('<?php echo $fileurl ?>/success.png');
}
</style>
<div class="isa_success">Your Call to Action Graphic Has Been eMailed to the eMail address on file.</div>
<?php
}
}

if ( isset( $_POST['delete'] )!= '' ) {
	$deleteFile = sanitize_file_name( $_POST['file'] );

$wp_upload_dir = wp_upload_dir();
$myFile = $wp_upload_dir['basedir'] . '/wpgs/'. $user_id .'/cta-boxes/'. $deleteFile .'';
$myThumb = $wp_upload_dir['basedir'] . '/wpgs/'. $user_id .'/cta-boxes/thumbs/THUMB_'. $deleteFile .'';
delete_post_meta($deleteFile, 'EmailSent', $user_id);
delete_post_meta($deleteFile, 'ReSent', $user_id);
unlink($myFile);
unlink($myThumb);
$upload_path = $wp_upload_dir['basedir'] . '/wpgs';
$fileurl = plugins_url( 'includes/wpgs/images', dirname(__FILE__) );
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
    background-image:url('<?php echo $fileurl ?>/success.png');
}
</style>
<div class="isa_success">Your Call to Action Graphic Has Been Deleted From Your Gallery Below</div>
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
$wp_upload_dir = wp_upload_dir();
$upload_path = $wp_upload_dir['basedir'] . '/wpgs';

$up_path1 = ''.$upload_path.'/'. $user_id .'/';
if (!file_exists($up_path1)) mkdir($up_path1);

$up_path2 = ''.$upload_path.'/'. $user_id .'/cta-boxes';
$up_path5 = ''.$upload_path.'/'. $user_id .'/cta-boxes/thumbs';
if (!file_exists($up_path2)) mkdir($up_path2);
if (!file_exists($up_path5)) mkdir($up_path5);

$newName = rand(1, 1000000);

if((isset($_POST['format'])) && ($_POST['format'] == 'jpg')) {
	$format = sanitize_file_name( $_POST['format'] );

imagejpeg($im, ''.$upload_path.'/'. $user_id .'/cta-boxes/'. $newName .'.'. $format .'');
$file = ''.$upload_path.'/'. $user_id .'/cta-boxes/'. $newName .'.'. $format .''; // (physical path)
chmod(''.$upload_path.'/'. $user_id .'/cta-boxes/'. $newName .'.'. $format .'', 0777);
}
if((isset($_POST['format'])) && ($_POST['format'] == 'png')) {
	$format = sanitize_file_name( $_POST['format'] );
imagepng($im, ''.$upload_path.'/'. $user_id .'/cta-boxes/'. $newName .'.'. $format .'');
$file = ''.$upload_path.'/'. $user_id .'/cta-boxes/'. $newName .'.'. $format .''; // (physical path)
chmod(''.$upload_path.'/'. $user_id .'/cta-boxes/'. $newName .'.'. $format .'', 0777);
}

$wp_upload_dir = wp_upload_dir();
$fileurl = $wp_upload_dir['baseurl'] . '/wpgs';
$file = ''.$fileurl.'/'. $user_id .'/cta-boxes/'. $newName .'.'. $format .''; // (physical path)
$filePath = ''.$fileurl.'/'. $user_id .'/cta-boxes/'. $newName .'.'. $format .''; // (physical path)

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
if ($format == 'jpg') {
    imagejpeg($thumb, ''.$upload_path.'/'. $user_id .'/cta-boxes/thumbs/THUMB_'. $newName .'.'. $format .'');
}
if ($format == 'png') {
    imagepng($thumb, ''.$upload_path.'/'. $user_id .'/cta-boxes/thumbs/THUMB_'. $newName .'.'. $format .'');
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
    background-image:url('<?php echo $fileurl ?>/images/success.png');
}
</style>
<div class="isa_success">
Your created Call to Action Has Been Saved To Your Call to Actions Graphic Gallery For Future Use.
<br>Click on your created Call to Action below to save to your computer....<br>
<b>Close This Window When Download Has Completed To Return To The Call to Actions Module.</b>
</div>
<div width="100%" align="center">
<div align="center"><a href="<?php echo $filePath ?>" download="<?php echo $newName ?>"><img src="<?php echo $filePath ?>" width="300px"></a></div>
</div>
<?php
}
if((isset($_POST['view'])) && ($_POST['view'] == 'gallery') || ($_POST['gallery'] == 'yes')) {
$wp_upload_dir = wp_upload_dir();
$upload_path = $wp_upload_dir['basedir'] . '/wpgs';

$up_path1 = ''.$upload_path.'/'. $user_id .'/';
if (!file_exists($up_path1)) mkdir($up_path1);

$up_path2 = ''.$upload_path.'/'. $user_id .'/cta-boxes';
$up_path5 = ''.$upload_path.'/'. $user_id .'/cta-boxes/thumbs';
if (!file_exists($up_path2)) mkdir($up_path2);
if (!file_exists($up_path5)) mkdir($up_path5);

$full_url = $wp_upload_dir['baseurl'] . '/wpgs/'.$user_id.'/cta-boxes/';
$full_path = $wp_upload_dir['basedir'] . '/wpgs/'. $user_id .'/cta-boxes/';

$cssurl = $wp_upload_dir['baseurl'] . '/wpgs/assets/css/style.css';

$thumbpath = $full_url . "thumbs/";

# Set number of thumbs_per_page.

if ((get_option( 'wpgs_wpgraphicstudio_per_gallery' ) == '') || (get_option( 'wpgs_wpgraphicstudio_per_gallery' ) == '0')) {
$thumbs_per_page = 10;
} else {
$thumbs_per_page = $thumbs_per_page = get_option( 'wpgs_wpgraphicstudio_per_gallery' );
}

# Set the thumb_max height/width in pixels.

$thumb_max = 100;

# Set build_square_thumbs 1 = yes, 0 = no.

$build_square_thumbs = 1;

# STOP! Let Gallery create your thumbs on the fly using a lower
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
<link rel="stylesheet" href="<?php echo $cssurl ?>" type="text/css" media="all" />
<script>
function changeLocation(url){
   window.location.assign(url);
}
</script>
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
		print "<a href=\"/graphic-gallery/cta-boxes/2/?$i\">$i</a>\n";
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
			print "<li><div class=\"show-image\">
			<img src=\"".$full_url."thumbs/THUMB_$pA\" alt=\"$pA\" />
<form action=\"/cta-boxes\" id=\"Delete\" method=\"post\">
<input type=\"hidden\" name=\"file\" value=\"$file_name\">
<input type=\"hidden\" name=\"view\" value=\"gallery\">
<input type=\"hidden\" name=\"delete\" value=\"1\">
<input class=\"galleryDelete\" type=\"submit\" value=\"\" alt=\"Delete Graphic\" title=\"Delete Graphic\" /></form>

<a href=\"".$full_url.$pA."\" download=\"$pA\">
<input class=\"galleryDownload\" type=\"submit\" value=\"\" alt=\"Download Graphic\" title=\"Download Graphic\" /></a>";
if ((get_option( 'wpgs_wpgraphicstudio_email_graphics' ) == '') || (get_option( 'wpgs_wpgraphicstudio_email_graphics' ) == 'On')) {
print "<form action=\"/cta-boxes\" id=\"eMail\" method=\"post\">
<input type=\"hidden\" name=\"file\" value=\"$file_name\">
<input type=\"hidden\" name=\"view\" value=\"gallery\">
<input type=\"hidden\" name=\"email\" value=\"1\">
<input class=\"galleryEmail\" type=\"submit\" value=\"\" alt=\"Email Graphic\" title=\"Email Graphic\"/></form>";
}
print "</div></li>\n";
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
else {
print "<p>You currently have no graphics in this gallery </p>";
} ?>
<?php } if ($_POST['create'] != '') {
$logo = get_option( 'wpgs_wpgraphicstudio_logo_url' );
$nav_hex = get_option( 'wpgs_wpgraphicstudio_nav_hex' );
$decHex = ereg_replace("[^A-Za-z0-9]", "", $nav_hex );
?><div margin = "0" align = "center" height = "650px" width = "850px">
<object id="flashcontent" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="850px" height="650px">
<?php echo'<param name="movie" value="'.plugins_url( 'cta-boxes.swf', dirname(__FILE__) ).'?logoURL='.$logo.'&NavColor='.$decHex.'" />'; ?>
  <!--[if !IE]>-->
  <?php echo '<object type="application/x-shockwave-flash" data="'.plugins_url( 'cta-boxes.swf', dirname(__FILE__) ).'?logoURL='.$logo.'&NavColor='.$decHex.'" width="850px" height="650px">'; ?>
  <!--<![endif]-->
  </div>
  <!--[if !IE]>-->
  </object>
  <!--<![endif]-->
</object></div>
<?php } ?>