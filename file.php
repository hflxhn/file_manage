<?php

include 'File.class.php';
$file = new File();

$data = $_POST ? $_POST : $_GET;
if (!$data) {
    $data = json_decode(file_get_contents('php://input'), true);
}

// echo "<pre>";
// print_r($data);
switch ($data['act']) {
    case 'create_file':
        echo $file->createFile($data);
        break;
    case 'show_content':
        $content = $file->showContent($data);
        echo $content;
        break;
    case 'edit_content':
        $content = $file->editContent($data);
        echo $content;
        break;
    case 'save_file':
        $content = $file->saveContent($data);
        echo $content;
        break;
    case 'rename_file':
        $content = $file->renameFile($data);
        echo $content;
        break;

    default:
        # code...
        break;
}
