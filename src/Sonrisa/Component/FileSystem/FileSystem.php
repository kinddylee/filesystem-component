<?php
/*
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonrisa\Component\FileSystem;

use \Sonrisa\Component\FileSystem\Exceptions\FileSystemException as FileSystemException;
use Sonrisa\Component\FileSystem\Interfaces\FileSystemInterface as FileSystemInterface;

abstract class FileSystem extends Zip implements FileSystemInterface
{
    /**
     * @param string $original
     * @param string $alias
     * @return bool
     * @throws Exceptions\FileSystemException
     */
    public static function softSymLink($original, $alias)
    {
        if(!file_exists($original))
        {
            throw new \Sonrisa\Component\FileSystem\Exceptions\FileSystemException("Cannot link {$original} because it does not exist.");
        }

        if(file_exists($alias))
        {
            throw new \Sonrisa\Component\FileSystem\Exceptions\FileSystemException("Cannot create link {$alias} because a file, directory or link with this name already exists.");
        }

        return symlink($original,$alias);
    }

    /**
     * @param string $original
     * @param string $alias
     * @return bool
     * @throws Exceptions\FileSystemException
     */
    public static function hardSymLink($original, $alias)
    {
        if(!file_exists($original))
        {
            throw new \Sonrisa\Component\FileSystem\Exceptions\FileSystemException("Cannot link {$original} because it does not exist.");
        }

        if(file_exists($alias))
        {
            throw new \Sonrisa\Component\FileSystem\Exceptions\FileSystemException("Cannot create link {$alias} because a file, directory or link with this name already exists.");
        }

        return link($original,$alias);
    }


    /**
     * @param string $path
     * @return bool
     * @throws FileSystemException
     */
    public static function isLink($path)
    {
        if (!file_exists($path))
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

    /**
     * @param  int         $size
     * @param  int         $precision
     * @return int|string
     */
    protected static function getSize($size,$precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        $bytes = max($size, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        return round($bytes, $precision) . ' ' . $units[$pow];
        
    }       
}
