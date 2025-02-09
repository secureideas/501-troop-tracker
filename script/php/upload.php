<?php

/**
 * This file is used for processing file uploads and storing the information in the database.
 * 
 * This should be every two minutes by a cronjob.
 *
 * @author  Matthew Drennan
 *
 */

// Include config file
include "../../config.php";

// Set directory seperator
$ds = DIRECTORY_SEPARATOR;

// Set directory for storing files
$storeFolder = '../../images/uploads/';

// If file exists
if (!empty($_FILES))
{
	/**
	 * Verify this is an allowed image
	 * 
	 * https://www.geeksforgeeks.org/php-exif_imagetype-function/
	 * 
	 * @var array $filesAllowed
	*/
	$filesAllowed = array(1, 2, 3);

	/**
	 * @var int $uploadedType Returns an int based on the file type
	*/
	$uploadedType = exif_imagetype($_FILES['file']['tmp_name']);

	// Check if this is an allowed image
	$error = !in_array($uploadedType, $filesAllowed);

	// This file is not allowed, stop the script
	if($error)
	{
		die("Uploaded File is not allowed!");
	}

	// Set up file and move to folder
	$fileName = date("Y-m-d-H-i-s-") . $_FILES['file']['name'];
	$tempFile = $_FILES['file']['tmp_name'];         
	$targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;
	$targetFile =  $targetPath . $fileName;
	move_uploaded_file($tempFile, $targetFile);

	/**
	 * Add a smaller image for faster page loads
	 */

	// Get path info
	$info = pathinfo($targetFile);

	// Get image size
	list($width, $height) = getimagesize($targetFile);

	// Limit size
	if($width > 500 || $height > 500) { $width = 500; $height = 500; }

	// Get file type
	$fileType = mime_content_type($targetFile);

	// Check file type to convert image
	if($fileType == "image/png")
	{
		$image = imagecreatefrompng($targetFile);
	}
	else if($fileType == "image/jpeg")
	{
		$image = imagecreatefromjpeg($targetFile);
	}
	else if($fileType == "image/gif")
	{
		$image = imagecreatefromgif($targetFile);
	}

	// Resize image
	$imgResized = imagescale($image , $width, $height);

	// Make new image
	imagejpeg($imgResized, $storeFolder . "resize/" . $info['filename'] . ".jpg");
	
	// Check if troop instruction image
	if($_POST['admin'] == 0)
	{
		// Insert file into database
		$conn->query("INSERT INTO uploads (troopid, trooperid, filename) VALUES ('".cleanInput($_POST['troopid'])."', '".cleanInput($_POST['trooperid'])."', '".addslashes($fileName)."')");
	}
	else
	{
		// Insert file into database - admin
		$conn->query("INSERT INTO uploads (troopid, trooperid, filename, admin) VALUES ('".cleanInput($_POST['troopid'])."', '".cleanInput($_POST['trooperid'])."', '".addslashes($fileName)."', '1')");
	}
}

?> 