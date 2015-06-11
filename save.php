<?php
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$data = $request->data;
$data_path = $request->path;
$data_path = $_SERVER["DOCUMENT_ROOT"] . $data_path;

$fh = fopen($data_path, 'w')
      or die("Error opening output file");
fwrite($fh, json_encode($data,JSON_UNESCAPED_UNICODE));
fclose($fh);

?>