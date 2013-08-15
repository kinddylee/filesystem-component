<?php
namespace NilPortugues\Component\FileSystem\Interfaces;

interface FolderInterface
{
    public function getModificationDate($path);

    //Adding an overwrite flag means, first, read all files in path, read all files in destination file
    //If there are coincidences, and overwrite == false, throw exception.
    public function copy($path,$destinationPath);   //ok

    //Adding an overwrite flag means, first, read all files in path, read all files in destination file
    //If there are coincidences, and overwrite == false, throw exception.
    public function move($path,$destinationPath);

    public function exists($path);  //OK

    public function create($path); // ok

    public function delete($path); // ok

    public function rename($path,$newName);

    public function touch($path,$time='',$accessTime='');

    public function chmod($path, $mode);

    public function isReadable($path);  //OK

    public function isWritable($path);  //OK

    public function zip($filePath, $newFileName, $overwrite=false);  //OK

    public function unzip($filePath, $newFileName, $overwrite=false);  //OK
}
