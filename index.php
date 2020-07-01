<?php

include 'File.class.php';

$file = new File();

// $data = json_decode(file_get_contents('php://input'), true);
// if (@array_key_exists('act', $data)) {
//     $path = $data['path'] . $data['file_name'] . '/';
// } else {
//     $path = 'file/';
// }
$path = $_GET;
$path = !array_key_exists('path', $path) ? 'file/' : $path['path'];

$files = $file->getAllFiles($path);

// back
$back = dirname($path) . '/';
if ($back == './') {
    $back = "file/";
}
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
            <a href="/index.php?path=<?php echo $back; ?>" class="btn btn-default" title="返回上一级">
                <span class="glyphicon glyphicon-triangle-left" aria-hidden="true"></span>
            </a>
            <button type="button" class="btn btn-default" title="新建文件" data-toggle="modal" data-target="#create-file">
                <span class="glyphicon glyphicon-file" aria-hidden="true"></span>
            </button>
            <button type="button" class="btn btn-default" title="新建文件夹">
                <span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>
            </button>
            <button type="button" class="btn btn-default" title="上传文件">
                <span class="glyphicon glyphicon-cloud-upload" aria-hidden="true"></span>
            </button>
            <a href="/" class="btn btn-default" title="首页">
                <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
            </a>
        </div>
        <hr>

        <table class='table table-striped table-bordered table-hover table-condensed file-btn'>
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
if ($files['dir']) {
    foreach ($files['dir'] as $key => $value) {
        $file_path = $path . '/' . $value;

        $r = is_readable($file_path) ? 'r' : '-';
        $w = is_writable($file_path) ? 'w' : '-';
        $x = is_executable($file_path) ? 'x' : '-';

        echo "<tr>";
        echo "<td>" . $value . "</td>";
        // echo "<td>" . filetype($file_path) . "</td>";
        echo '<td><span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span></td>';
        echo "<td>" . $file->dirSize($path . $value . '/') . "</td>";
        $file->file_size = 0;
        echo "<td>" . $r . $w . $x . "</td>";
        echo "<td>" . date("Y-m-d H:i:s", filectime($path)) . "</td>";
        echo "<td>" . date("Y-m-d H:i:s", filemtime($path)) . "</td>";
        echo "<td>" . date("Y-m-d H:i:s", fileatime($path)) . "</td>";
        echo "<td>";
        //
        echo '
            <div class="btn-group btn-group-xs" role="group">
                <a href="index.php?path=' . $path . $value . '/' . '" data-act="file_path" data-path="' . $path . '" data-file-name="' . $value . '" class="btn btn-info" title="查看">
                    <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                </a>
                <button type="button" data-act="rename_file" data-path="' . $path . '" data-file-name="' . $value . '" class="btn btn-warning" title="重命名">
                    <span class="glyphicon glyphicon-object-align-bottom" aria-hidden="true"></span>
                </button>
                <!-- <button type="button" class="btn btn-info" title="复制">
                    <span class="glyphicon glyphicon-copy" aria-hidden="true"></span>
                </button>
                <button type="button" class="btn btn-warning" title="剪贴">
                    <span class="glyphicon glyphicon-cutlery" aria-hidden="true"></span>
                </button>-->
                <button type="button" data-act="del_file" data-path="' . $path . '" data-file-name="' . $value . '" class="btn btn-danger" title="删除">
                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                </button>
                <a href="file.php?act=down_file&path=' . $path . '&file_name=' . $value . '" class="btn btn-success" title="下载">
                    <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span>
                </a>
            </div>';
        echo "</td>";

        echo "</tr>";
    }
}

if ($files['file']) {
    foreach ($files['file'] as $key => $value) {
        $file_path = $path . '/' . $value;

        $r = is_readable($file_path) ? 'r' : '-';
        $w = is_writable($file_path) ? 'w' : '-';
        $x = is_executable($file_path) ? 'x' : '-';

        echo "<tr>";

        echo "<td>" . $value . "</td>";
        // echo "<td>" . filetype($file_path) . "</td>";
        echo '<td><span class="glyphicon glyphicon-file" aria-hidden="true"></span></td>';
        echo "<td>" . $file->transByte(filesize($file_path)) . "</td>";
        echo "<td>" . $r . $w . $x . "</td>";
        echo "<td>" . date("Y-m-d H:i:s", filectime($path)) . "</td>";
        echo "<td>" . date("Y-m-d H:i:s", filemtime($path)) . "</td>";
        echo "<td>" . date("Y-m-d H:i:s", fileatime($path)) . "</td>";
        echo "<td>";
        echo '
        <div class="btn-group btn-group-xs" role="group">
            <button type="button" data-act="show_content" data-path="' . $path . '" data-file-name="' . $value . '" class="btn btn-info" title="查看">
                <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
            </button>
            <button type="button" data-act="edit_content" data-path="' . $path . '" data-file-name="' . $value . '" class="btn btn-primary" title="修改">
                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
            </button>
            <button type="button" data-act="rename_file" data-path="' . $path . '" data-file-name="' . $value . '" class="btn btn-warning" title="重命名">
                <span class="glyphicon glyphicon-object-align-bottom" aria-hidden="true"></span>
            </button>
            <!-- <button type="button" class="btn btn-info" title="复制">
                <span class="glyphicon glyphicon-copy" aria-hidden="true"></span>
            </button>
            <button type="button" class="btn btn-warning" title="剪贴">
                <span class="glyphicon glyphicon-cutlery" aria-hidden="true"></span>
            </button>-->
            <button type="button" data-act="del_file" data-path="' . $path . '" data-file-name="' . $value . '" class="btn btn-danger" title="删除">
                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
            </button>
            <a href="file.php?act=down_file&path=' . $path . '&file_name=' . $value . '" class="btn btn-success" title="下载">
                <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span>
            </a>
        </div>';
        echo "</td>";

        echo "</tr>";
    }
}
?>
        </table>
    </div>

<!-- create file -->
<div class="modal fade" id="create-file" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">创建文件</h4>
            </div>
            <form  action="javascript:;" ajax-url='/file.php' class="form-validate" autocomplete="off">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">文件名称</label>
                        <input type="text" name="file_name" class="form-control" id="exampleInputEmail1" placeholder="文件名称">
                        <input type="hidden" name="path" value="<?php echo $path; ?>">
                        <input type="hidden" name="act" value="create_file">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary">保存</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- show content -->
<div class="modal fade" id="show-content" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">查看文件内容</h4>
            </div>
            <div class="modal-body">
                <div class="alert-danger file-content">&lt;p&gt;Sample text here...&lt;/p&gt;</div>
            </div>
        </div>
    </div>
</div>

<!-- edit content -->
<div class="modal fade" id="edit-content" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">编辑文件内容</h4>
            </div>
            <form  action="javascript:;" ajax-url='/file.php' class="form-validate" autocomplete="off">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">文件内容</label>
                        <textarea class="form-control file-edit" name="content" rows="3"></textarea>
                        <input type="hidden" name="path" value="<?php echo $path; ?>">
                        <input type="hidden" name="file_name">
                        <input type="hidden" name="act" value="save_file">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary">保存</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- rename file -->
<div class="modal fade" id="rename-file" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">重命名文件</h4>
            </div>
            <form  action="javascript:;" ajax-url='/file.php' class="form-validate" autocomplete="off">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">新的文件名</label>
                        <input type="text" name="new_file_name" class="form-control" id="exampleInputEmail1" placeholder="文件名称">
                        <input type="hidden" name="file_name">
                        <input type="hidden" name="path" value="<?php echo $path; ?>">
                        <input type="hidden" name="act" value="rename_file">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary">保存</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- del file -->
<div class="modal fade" id="del-file" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">确认要删除文件吗? 删除之后无法恢复呦</h4>
            </div>
            <form  action="javascript:;" ajax-url='/file.php' class="form-validate" autocomplete="off">
                <div class="modal-body">
                    <input type="hidden" name="file_name">
                    <input type="hidden" name="path" value="<?php echo $path; ?>">
                    <input type="hidden" name="act" value="del_file">
                    <button type="submit" class="btn btn-danger">确认删除</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
<script src="/public/bs/js/jquery.min.js"></script>
<script src="/public/layer/layer.js"></script>
<script src="/public/bs/js/bootstrap.js"></script>
<script src="/public/js/tools.js"></script>
<script src="/public/js/common.js"></script>
</html>
