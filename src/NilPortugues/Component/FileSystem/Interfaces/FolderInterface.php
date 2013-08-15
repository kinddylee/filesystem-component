<?php
namespace NilPortugues\Component\FileSystem\Interfaces;

interface FolderInterface
{
    public function getModificationDate($path);

    public function copy($path,$destinationPath);

    public function move($path,$destinationPath);

    public function exists($path);  //OK

    public function create($path);

    public function delete($path);

    public function rename($path,$newName);

    public function touch($path,$time='',$accessTime='');

    public function chmod($path, $mode);

    public function isReadable($path);  //OK

    public function isWritable($path);  //OK

    public function zip($filePath, $newFileName, $overwrite=false);  //OK

    public function unzip($filePath, $newFileName, $overwrite=false);  //OK
}
