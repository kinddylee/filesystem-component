<?php
/*
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Component\FileSystem\Interfaces;

interface FolderInterface
{
    public static function getModificationDate($path);

    /**
     * @todo: new functionality
     * Adding an overwrite flag means, first, read all files in path, read all files in destination file
     * If there are coincidences, and overwrite == false, throw exception.
     */
    public static function copy($path,$destinationPath); 

    /**
     * @todo: new functionality
     * Adding an overwrite flag means, first, read all files in path, read all files in destination file
     * If there are coincidences, and overwrite == false, throw exception.
     */
    public static function move($path,$destinationPath);

    public static function exists($path);

    public static function create($path);

    public static function delete($path);

    public static function rename($path,$newName);

    public static function touch($path,$time='',$accessTime='');

    public static function chmod($path, $mode);

    public static function isReadable($path);

    public static function isWritable($path);

    public static function zip($filePath, $newFileName, $overwrite=false);

    public static function unzip($filePath, $newFileName, $overwrite=false);
}
