<?php
namespace NilPortugues\Component\FileSystem\Interfaces;

interface FileInterface
{
    public function getExtension($filePath);

    public function getFileModificationDate($filePath);

    public function fileExists($filePath);

    public function fileIsReadable($filePath);

    public function fileGetContents($filePath);

    public function filePutContents($filePath, $data, $mode = null);

    public function fileAppend($filePath, $data);

    public function fileChmod($filePath, $mode);

    public function fileDelete($filePath);

    //public function fileIsWriteable($filePath);

    public function fileRename($filePath,$newFileName,$overwrite=false);

    public function fileTouch($filePath,$time='',$accessTime='');
}
