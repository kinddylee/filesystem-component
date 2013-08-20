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
    public function copy($filePath,$newFilePath,$overwrite=false);

    public function move($filePath,$destinationFolder,$overwrite=false);

    public function getExtension($filePath);

    public function getModificationDate($filePath);

    public function exists($filePath);

    public function isReadable($filePath);

    public function isWritable($filePath);

    public function read($filePath);

    public function write($filePath, $data, $mode = null);

    public function append($filePath, $data);

    public function chmod($filePath, $mode);

    public function delete($filePath);

    public function rename($filePath,$newFileName,$overwrite=false);

    public function touch($filePath,$time='',$accessTime='');

    public function gzip($filePath, $newFileName, $overwrite=false, $param="1");

    public function gunzip ($filePath, $newFileName,$overwrite=false);

    public function zip($filePath, $newFileName, $overwrite=false);

    public function unzip($filePath, $newFileName, $overwrite=false);
}
