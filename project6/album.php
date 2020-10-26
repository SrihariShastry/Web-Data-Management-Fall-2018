<?php
error_reporting(E_ALL);
ini_set('display_errors','off');
/**
 * DropPHP Demo
 *
 * http://fabi.me/en/php-projects/dropphp-dropbox-api-client/
 *
 * @author     Fabian Schlieper <fabian@fabi.me>
 * @copyright  Fabian Schlieper 2012
 * @version    1.1
 * @license    See license.txt
 *
 */


require_once 'demo-lib.php';
demo_init(); // this just enables nicer output

// if there are many files in your Dropbox it can take some time, so disable the max. execution time
set_time_limit( 0 );

require_once 'DropboxClient.php';

/** you have to create an app at @see https://www.dropbox.com/developers/apps and enter details below: */
/** @noinspection SpellCheckingInspection */
$dropbox = new DropboxClient( array(
	'app_key' => "w9pkv3o5wx18v3s",      // Put your Dropbox API key here
	'app_secret' => "nq2f3gxtvvnvx83",   // Put your Dropbox API secret here
	'app_full_access' => false,
) );


/**
 * Dropbox will redirect the user here
 * @var string $return_url
 */
$return_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . "?auth_redirect=1";

// first, try to load existing access token
$bearer_token = demo_token_load( "bearer" );
if ( $bearer_token ) {
	$dropbox->SetBearerToken( $bearer_token );
} elseif ( ! empty( $_GET['auth_redirect'] ) ) // are we coming from dropbox's auth page?
{
	// get & store bearer token
	$bearer_token = $dropbox->GetBearerToken( null, $return_url );
	demo_store_token( $bearer_token, "bearer" );
} elseif ( ! $dropbox->IsAuthorized() ) {
	// redirect user to Dropbox auth page
	$auth_url = $dropbox->BuildAuthorizeUrl( $return_url );
	die( "Authentication required. <a href='$auth_url'>Continue.</a>" );
}

?>

<form method="POST" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="userfile" id="userfile">
    <input type="submit" value="submit" name="submit">
</form>
<form id="download" type="hidden" action = "album.php" method ="GET"/>
<form id="delete" type="hidden" action = "album.php" method ="GET"/>

<?php

if (isset($_POST['submit'])) {
    $filename = $_FILES['userfile']['name'];
    if (!empty( $_FILES['userfile'] && empty($_FILES['userfile']['error']))) {
    	$ext = end ((explode(".", $_FILES["userfile"]["name"])));
    	if(in_array($ext, array('gif','jpg','jepg','png','GIF','JPG','JPEG','PNG'))){
    	$dropbox->UploadFile($_FILES["userfile"]["tmp_name"], $filename);
	    }
	    else{
	    	echo '<span> Not an Image file!! Cannot upload</span>';
	    }
    }
    else if(empty($_FILES['userfile']['name']))
    {
    	echo '<span> Select an Image to upload</span>';
    	
    }
    else{
    	echo '<span> File too large!!. Select File less than 2MB</span>';
    }
}

if(!empty($_GET['download'])){
	$jpg_files = $dropbox->Search( "/", $_GET['download'], 10 );
	$jpg_file = reset( $jpg_files );
	// $file = 'download_' . basename( $jpg_file->path );

	echo "\n\n<b>Image: </b>\n";
	$img_data = base64_encode( $dropbox->GetThumbnail( $jpg_file->path,'l') );
	// $img = $dropbox->DownloadFile( $jpg_file, $file );
	echo "<div><img src=\"data:image/jpeg;base64,$img_data\" alt=\"Generating PDF thumbnail failed!\" style=\"border: 1px solid black;\" /></div>";
}

if(isset($_GET['delete'])){
		$jpg_files = $dropbox->Search( "/", $_GET['delete'], 10 );
		if ( empty( $jpg_files ) ) {
		echo "Nothing found.";
	}
	else {
		$jpg_file = reset( $jpg_files );
		$dropbox->Delete($jpg_file->path);
	}
}

echo "\n\n<b>Files:</b>\n";

$files = $dropbox->GetFiles( "", false );
$file = array_keys($files);
echo '<table style = "float:left; width:30%; text-align:justify;"></tbody><tr><th>Name</th><th>Delete</th></tr>';


for($i=0;$i<count($file);$i++){
	echo '<tr><td><a href="album.php?download='.$file[$i].'"/>'.$file[$i].'</td>';
	echo '<td><a href="album.php?delete='.$file[$i].'">delete</a></td>';
}
echo '</tbody></table>';
?>
