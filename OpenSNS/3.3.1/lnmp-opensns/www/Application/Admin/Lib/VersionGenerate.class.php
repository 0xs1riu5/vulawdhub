<?php

class VersionGenerate
{
    public $appFolder = "";
    public $ignoreFilePaths = array(
        './.git',
        './Runtime',
        './Uploads',
        './.idea',
        '.gitignore'
    );

    public function start($version)
    {
        $AppPath = ".";
        $this->appFolder = $AppPath;
        $md5 = $this->traverse($AppPath);
        return $md5;
    }

    /**
     * 遍历应用根目录下的文件，并生成对应的文件长度及md5信息
     *
     * @param unknown $AppPath
     *          应用根目录，如：xxx/xxx/analytics
     * @param string $destManifestPath
     *          生成的manifest文件存放位置的文件句柄
     */
    public function traverse($AppPath)
    {
        if (in_array(str_replace('\\', '/', $AppPath), $this->ignoreFilePaths)) {
            return null;
        }
        if (!file_exists($AppPath)) {
            return null;
        }
        if (!is_dir($AppPath)) {
            return null;
        }
        if (!($dh = opendir($AppPath))) {
            return null;
        }
        $md5=array();
        // read files
        while (($file = readdir($dh)) != false) {
            $subDir = $AppPath . DIRECTORY_SEPARATOR . $file;

            if ($file == "." || $file == "..") {
                continue;
            } else if (is_dir($subDir)) {
                // rescure
                $md=$this->traverse($subDir);
                if($md){
                    $md5[$file]= $md;
                }

            } else {
                // Sub is a file.
                $md=$this->getOneFileMd5($subDir);
                if($md!=null)
                $md5[$file]= $md;
            }
        }
        return $md5;

    }

    /**
     * 写一个文件的md5信息到文件中
     *
     * @param unknown $filePath
     * @param unknown $fileHandle
     */
    public function getOneFileMd5($filePath)
    {
        if (!file_exists($filePath)) {
            return;
        }

        $relativePath = str_replace($this->appFolder . DIRECTORY_SEPARATOR, '', $filePath);
        $relativePath = str_replace("\\", "/", $relativePath);

        // ignore tmp directory
        if (strpos($relativePath, "tmp/") === 0) {
            return;
        }

        $fileSize = filesize($filePath);
        $fileMd5 = @md5_file($filePath);
        return $fileMd5;
    }

}