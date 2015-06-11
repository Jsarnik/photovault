
<?php

$realPath = realpath('delete.php'); 
$path_parts = pathinfo($realPath);
$basedir = str_replace("\\", "/", $path_parts['dirname']);

if (isset($_SESSION['temp_upload'])) {
    $filePath = $_SESSION['temp_upload'];
}
else{
	$postdata = file_get_contents("php://input");
	$request = json_decode($postdata);
	$filePath = $request->filePath;
}

$fullPath = $basedir.$filePath;


// See if it exists before attempting deletion on it
if (file_exists($fullPath)) {
	unlink($fullPath); //delete it
}

?>