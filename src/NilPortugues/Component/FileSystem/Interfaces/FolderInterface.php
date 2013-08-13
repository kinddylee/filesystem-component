<?php
namespace NilPortugues\Component\FileSystem\Interfaces;

interface FolderInterface
{
    public function create($path);

    public function zip($filePath, $newFileName, $overwrite=false);

    public function unzip($filePath, $newFileName, $overwrite=false);
}
