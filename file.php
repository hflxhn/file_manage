<?php

include 'File.class.php';
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
