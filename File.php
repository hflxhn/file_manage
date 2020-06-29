<?php

/**
 * File
 */
class File
{

    public function __construct()
    {
        # code...
    }

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
        $handle = opendir($path);

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
}
