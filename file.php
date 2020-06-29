<?php

include 'File.class.php';
$file = new File();

$data = $_POST ? $_POST : $_GET;
if (!$data) {
    $data = json_decode(file_get_contents('php://input'), true);
}

switch ($data['act']) {
    case 'create_file':
        echo $file->createFile($data);
        break;
    case 'show_content':
        $content = $file->showContent($data);
        echo $content;
        break;

    default:
        # code...
        break;
}
