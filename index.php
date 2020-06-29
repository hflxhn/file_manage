<?php

include 'File.php';

$file = new File();

$path  = 'file';
$files = $file->getAllFiles($path);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>在线文件管理系统</title>
    <link rel="stylesheet" href="public/bs/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class='page-header'>在线文件管理系统</h1>

        <div class="btn-group" role="group">
            <button type="button" class="btn btn-default" title="返回上一级">
                <span class="glyphicon glyphicon-triangle-left" aria-hidden="true"></span>
            </button>
            <button type="button" class="btn btn-default" title="新建文件" data-toggle="modal" data-target="#myModal">
                <span class="glyphicon glyphicon-file" aria-hidden="true"></span>
            </button>
            <button type="button" class="btn btn-default" title="新建文件夹">
                <span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>
            </button>
            <button type="button" class="btn btn-default" title="上传文件">
                <span class="glyphicon glyphicon-cloud-upload" aria-hidden="true"></span>
            </button>
            <button type="button" class="btn btn-default" title="上传文件">
                <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
            </button>
        </div>
        <hr>

        <table class='table table-striped table-bordered table-hover table-condensed'>
            <tr>
                <th>文件名</th>
                <th>类型</th>
                <th>大小</th>
                <th>读/写/执行</th>
                <th>创建时间</th>
                <th>修改时间</th>
                <th>访问时间</th>
                <th>操作</th>
            </tr>
<?php
if ($files['file']) {
    foreach ($files['file'] as $key => $value) {
        $file_path = $path . '/' . $value;

        $r = is_readable($file_path) ? 'r' : '-';
        $w = is_writable($file_path) ? 'w' : '-';
        $x = is_executable($file_path) ? 'x' : '-';

        echo "<tr>";

        echo "<td>" . $value . "</td>";
        echo "<td>" . filetype($file_path) . "</td>";
        echo "<td>" . $file->transByte(filesize($file_path)) . "</td>";
        echo "<td>" . $r . $w . $x . "</td>";
        echo "<td>" . date("Y-m-d H:i:s", filectime($path)) . "</td>";
        echo "<td>" . date("Y-m-d H:i:s", filemtime($path)) . "</td>";
        echo "<td>" . date("Y-m-d H:i:s", fileatime($path)) . "</td>";
        echo "<td>";
        echo '
        <div class="btn-group btn-group-xs" role="group">
            <button type="button" class="btn btn-info" title="查看">
                <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
            </button>
            <button type="button" class="btn btn-primary" title="修改">
                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
            </button>
            <button type="button" class="btn btn-warning" title="重命名">
                <span class="glyphicon glyphicon-object-align-bottom" aria-hidden="true"></span>
            </button>
            <button type="button" class="btn btn-info" title="复制">
                <span class="glyphicon glyphicon-copy" aria-hidden="true"></span>
            </button>
            <button type="button" class="btn btn-warning" title="剪贴">
                <span class="glyphicon glyphicon-cutlery" aria-hidden="true"></span>
            </button>
            <button type="button" class="btn btn-danger" title="删除">
                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
            </button>
        </div>';
        echo "</td>";

        echo "</tr>";
    }
}
?>
        </table>
    </div>

<!-- create file -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">创建文件</h4>
            </div>
            <form action="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">文件名称</label>
                        <input type="text" name="file_name" class="form-control" id="exampleInputEmail1" placeholder="文件名称">
                        <input type="hidden" name="path" value="<?php echo $path; ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary">保存</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
<script src="/public/bs/js/jquery.min.js"></script>
<script src="/public/bs/js/bootstrap.js"></script>
</html>
