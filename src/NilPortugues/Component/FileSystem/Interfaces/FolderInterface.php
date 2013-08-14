<?php
namespace NilPortugues\Component\FileSystem\Interfaces;

interface FolderInterface
{
    public function getModificationDate($path);

    public function copy($filePath,$newFilePath,$overwrite=false);

    public function move($filePath,$newFilePath);

    public function exists($path);

    public function create($path);

    public function delete($path);

    public function rename($path,$newName);

    public function touch($path,$time='',$accessTime='');

    public function chmod($path, $mode);

    public function isReadable($path);

    public function isWritable($path);

    public function zip($filePath, $newFileName, $overwrite=false);

    public function unzip($filePath, $newFileName, $overwrite=false);
}
