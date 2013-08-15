<?php
namespace NilPortugues\Component\FileSystem\Interfaces;

interface FileSystemInterface
{
    public function softSymLink($original, $alias);

    public function hardSymLink($original, $alias);

    public function isLink($path);
}
