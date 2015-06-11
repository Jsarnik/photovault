
<?php

return;

$isUploaded = 'false';
$realPath = realpath('upload.php'); 
$path_parts = pathinfo($realPath);
$basedir = str_replace("\\", "/", $path_parts['dirname']);

if (isset($_SESSION['temp_upload'])) {
    session_unset(this);
}

$destFolder = $_POST['destFolder'];
$target_file = $basedir.$destFolder . basename($_FILES["fileToUpload"]["name"]);

if (!file_exists($basedir.$destFolder)) {
    mkdir($basedir.$destFolder, 0777, true);
}

if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    if(getimagesize($target_file))
        createThumb($target_file, $basedir.$destFolder.'/thumbnails');
    $isUploaded = 'true';
    $_SESSION['temp_upload'] = basename($target_file);
} else {
    echo "An error occured"; 
}

function createThumb($src, $dest){

    if (!file_exists($dest))
        mkdir($dest, 0777, true);

    $source_image = imagecreatefromjpeg($src);
    $width = imagesx($source_image);
    $height = imagesy($source_image);
    
    $virtual_image = imagecreatetruecolor(100, 100);
    
    imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, 100, 100, $width, $height);
    
    imagejpeg($virtual_image, $dest . '/' . basename($src, '.' . pathinfo($src, PATHINFO_EXTENSION)) .'_thumb.jpg');
}

?>