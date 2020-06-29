<?php
header('Content-Type:text/json;charset=utf-8');

include 'File.php';
$file = new File();

$data = $_POST;

switch ($data['act']) {
    case 'create_file':
        echo $file->createFile($data);
        break;

    default:
        # code...
        break;
}
