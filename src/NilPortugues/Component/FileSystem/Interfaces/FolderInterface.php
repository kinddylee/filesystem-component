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
    public function getModificationDate($path);

    /**
     * @todo: new functionality
     * Adding an overwrite flag means, first, read all files in path, read all files in destination file
     * If there are coincidences, and overwrite == false, throw exception.
     */
    public function copy($path,$destinationPath); 

    /**
     * @todo: new functionality
     * Adding an overwrite flag means, first, read all files in path, read all files in destination file
     * If there are coincidences, and overwrite == false, throw exception.
     */
    public function move($path,$destinationPath);

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
