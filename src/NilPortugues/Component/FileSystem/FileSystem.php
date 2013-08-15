<?php
namespace NilPortugues\Component\FileSystem;

use \NilPortugues\Component\FileSystem\Exceptions\FileSystemException as FileSystemException;
use NilPortugues\Component\FileSystem\Interfaces\FileSystemInterface as FileSystemInterface;

abstract class FileSystem extends Zip implements FileSystemInterface
{
    /**
     * @param string $original
     * @param string $alias
     * @return bool
     * @throws Exceptions\FileSystemException
     */
    public function softSymLink($original, $alias)
    {
        if(!file_exists($original))
        {
            throw new \NilPortugues\Component\FileSystem\Exceptions\FileSystemException("Cannot link {$original} because it does not exist.");
        }

        if(file_exists($alias))
        {
            throw new \NilPortugues\Component\FileSystem\Exceptions\FileSystemException("Cannot create link {$alias} because a file, directory or link with this name already exists.");
        }

        return symlink($original,$alias);
    }

    /**
     * @param string $original
     * @param string $alias
     * @return bool
     * @throws Exceptions\FileSystemException
     */
    public function hardSymLink($original, $alias)
    {
        if(!file_exists($original))
        {
            throw new \NilPortugues\Component\FileSystem\Exceptions\FileSystemException("Cannot link {$original} because it does not exist.");
        }

        if(file_exists($alias))
        {
            throw new \NilPortugues\Component\FileSystem\Exceptions\FileSystemException("Cannot create link {$alias} because a file, directory or link with this name already exists.");
        }

        return link($original,$alias);
    }


    /**
     * @param string $path
     * @return bool
     * @throws FileSystemException
     */
    public function isLink($path)
    {
        if (!$this->exists($path))
        {
            throw new FileSystemException("{$path} does not exist.");
        }

        if( true == is_link($path))
        {
            return true;
        }
        else
        {
            $filestat = stat($path);
            return ($filestat['nlink']>1);
        }
    }


}
