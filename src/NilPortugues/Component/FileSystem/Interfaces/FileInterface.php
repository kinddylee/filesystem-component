<?php
/*
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Component\FileSystem\Interfaces;

interface FileInterface
{
    public static function copy($filePath,$newFilePath,$overwrite=false);

    public static function move($filePath,$destinationFolder,$overwrite=false);

    public static function getExtension($filePath);

    public static function getModificationDate($filePath);

    public static function exists($filePath);

    public static function isReadable($filePath);

    public static function isWritable($filePath);

    public static function read($filePath);

    public static function write($filePath, $data, $mode = null);

    public static function append($filePath, $data);

    public static function chmod($filePath, $mode);

    public static function delete($filePath);

    public static function rename($filePath,$newFileName,$overwrite=false);

    public static function touch($filePath,$time='',$accessTime='');

    public static function gzip($filePath, $newFileName, $overwrite=false, $param="1");

    public static function gunzip ($filePath, $newFileName,$overwrite=false);

    public static function zip($filePath, $newFileName, $overwrite=false);

    public static function unzip($filePath, $newFileName, $overwrite=false);
}
