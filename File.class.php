<?php

/**
 * File
 */
class File
{

    public $file_size = 0;
    public function __construct()
    {}

    /**
     * get all file
     * @Author   hflxhn.com
     * @DateTime 2020-06-28T22:23:17+0800
     * @param    string                   $path [file path]
     * @return   [type]                         [file]
     */
    public function getAllFiles($path = '/')
    {
        // open fils
        @$handle = opendir($path);

        if (!$handle) {
            $arr['dir']  = [];
            $arr['file'] = [];
            return $arr;
        }

        $arr = [];
        while (($item = readdir($handle)) !== false) {
            // filter . and ..
            if ($item != '.' && $item != '..') {
                if (is_file($path . '/' . $item)) {
                    $arr['file'][] = $item;
                }
                if (is_dir($path . '/' . $item)) {
                    $arr['dir'][] = $item;
                }
            }
        }

        // close files
        closedir($handle);

        if (!array_key_exists('file', $arr)) {
            $arr['file'] = [];
        }
        if (!array_key_exists('dir', $arr)) {
            $arr['dir'] = [];
        }

        return $arr;
    }

    /**
     * trans byte
     * @Author   hflxhn.com
     * @DateTime 2020-06-28T22:30:38+0800
     * @param    integer                  $size [byte]
     */
    public function transByte($size = 0)
    {
        $arr = [
            "Byte",
            "KB",
            "MB",
            "GB",
            "TB",
            "EB",
        ];

        $tmp = 0;
        while ($size >= 1024) {
            $size /= 1024;
            $tmp++;
        }

        return round($size, 2) . " " . $arr[$tmp];
    }

    // 获取文件大小
    public function dirSize($path)
    {
        $files = $this->getAllFiles($path);

        $this->file_size += $this->totFileSize($path, $files['file']);

        foreach ($files['dir'] as $key => $value) {
            $this->dirSize($path . $value . '/');
        }

        return $this->transByte($this->file_size);
    }

    // 统计文件大小
    protected function totFileSize($path, $files = [])
    {
        $file_size = 0;

        if ($files) {
            foreach ($files as $key => $value) {
                $file_size += filesize($path . $value);
            }
        }
        return $file_size;
    }

    // create file
    public function createFile($data = [])
    {
        // 验证文件名是否合法
        if ($this->checkFileName($data['file_name']) != 0) {
            return $this->result(["error", 1, "非法文件名"]);
        }

        // 检测当前目录是否存在同名文件
        if (file_exists($data['path'] . $data['file_name'])) {
            return $this->result(["error", 1, "文件名已存在,请重命名后上传"]);
        }

        // 开始创建文件
        if (!touch($data['path'] . $data['file_name'])) {
            return $this->result(["error", 1, "文件名创建失败"]);
        }
        return $this->result(["/index.php?path=" . $data['path'], 0, "文件名创建成功"]);
    }

    // 查看文件
    public function showContent($data = [])
    {
        $path_file_name = $data['path'] . $data['file_name'];
        $content        = file_get_contents($path_file_name);

        if (!strlen($content)) {
            return $this->result(["error", 1, "该文件内容为空"]);
        }
        return $this->result([$content, 0, "success"]);
    }

    // 编辑文件内容数据
    public function editContent($data = [])
    {
        $path_file_name = $data['path'] . $data['file_name'];
        $content        = file_get_contents($path_file_name);

        return $this->result([$content, 0, "success"]);
    }

    // 保存编辑内容
    public function saveContent($data = [])
    {
        $path_file_name = $data['path'] . $data['file_name'];

        $result = file_put_contents($path_file_name, $data['content']);

        if (!$result) {
            return $this->result(["error", 1, "文件修改失败"]);
        }
        return $this->result(["/index.php?path=" . $data['path'], 0, "文件修改成功"]);
    }

    // 重命名文件名
    public function renameFile($data = [])
    {
        if ($this->checkFileName($data['new_file_name']) != 0) {
            return $this->result(["error", 1, "非法文件名"]);
        }

        if (file_exists($data['path'] . $data['new_file_name'])) {
            return $this->result(["error", 1, "文件名已存在,请重命名后上传"]);
        }

        $rename = rename($data['path'] . $data['file_name'], $data['path'] . $data['new_file_name']);
        if (!$rename) {
            return $this->result(["error", 1, "重命名失败"]);
        }
        return $this->result(["/index.php?path=" . $data['path'], 0, "重命名成功"]);
    }

    // 删除文件名
    public function delFile($data = [])
    {
        $del_file = unlink($data['path'] . $data['file_name']);
        if (!$del_file) {
            return $this->result(["error", 1, "文件删除失败"]);
        }
        return $this->result(["/index.php?path=" . $data['path'], 0, "文件删除成功"]);
    }

    // 下载文件
    public function downFile($data = [])
    {
        // //文件类型是二进制流。设置为utf8编码（支持中文文件名称）
        // header('Content-type:application/octet-stream; charset=utf-8');
        // header("Content-Transfer-Encoding: binary");
        // header("Accept-Ranges: bytes");

        // //文件大小
        // header("Content-Length: " . filesize($data['path'] . $data['file_name']));
        // //触发浏览器文件下载功能
        // header('Content-Disposition:attachment;filename="' . $data['file_name'] . '"');

        // ob_clean();
        // ob_end_flush();
        // $handle = fopen($data['path'] . $data['file_name'], "rb");
        // while (!feof($handle)) {
        //     echo fread($handle, 1000);
        // }

        header("content-disposition: attachment;filename=" . $data['file_name']);
        header("content-length:" . filesize($data['path'] . $data['file_name']));

        readfile($data['path'] . $data['file_name']);
    }

    // 验证文件名是否合法
    protected function checkFileName($filename = '')
    {
        $pattern = "/[\/,\*,<>,\?,\|]/";
        if (preg_match($pattern, $filename)) {
            return 1;
        }
    }

    // 返回函数
    protected function result($data = [])
    {
        $data = [
            'data' => $data[0],
            'code' => $data[1],
            'msg'  => $data[2],
            'time' => time(),
        ];
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}
