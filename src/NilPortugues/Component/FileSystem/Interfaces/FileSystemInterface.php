<?php
/*
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Component\FileSystem\Interfaces;

interface FileSystemInterface
{
    public function softSymLink($original, $alias);

    public function hardSymLink($original, $alias);

    public function isLink($path);
}
