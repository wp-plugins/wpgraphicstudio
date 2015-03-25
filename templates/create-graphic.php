<?php

# Create folder named thumbs inside your gallery,
# Be sure to set the thumbs folder permissions to 777.
# You do not need to edit the thumbpath variable.
$fileurl = get_option( 'siteurl' );
$link_url = ''.$fileurl.'/';

$full_url = ''.$fileurl.'/wp-content/uploads/wpgs/images/create/';
$wp_upload_dir = wp_upload_dir();
$full_path = $wp_upload_dir['basedir'] . '/wpgs/images/create/';

$thumbpath = $full_url . "thumbs/";

# Set number of thumbs_per_page.
$num_thumbs = get_option( 'wpgs_wpgraphicstudio_per_members' );

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

			if(!file_exists($thumbpath."THUMB_".$pA))
			{
			print "<li><form action=\"/$file_name\" method=\"post\"><input type=\"hidden\" name=\"create\" value=\"yes\"><input type=\"image\" src=\"".$full_url.$pA."\" alt=\"$pA\" /><br></form>\n";
}
			else
			{
			print "<a href=\"".$full_url.$pA."\"><img src=\"".$full_url."thumbs/$pA\" alt=\"$pA\" /></a>\n";
			}
		}
	$count++;
	}

print "</ul>\n<div class=\"clear\">&nbsp;</div>\n";
}
else
{
print "<p>You currently have no graphics in this gallery </p>";
}
?>