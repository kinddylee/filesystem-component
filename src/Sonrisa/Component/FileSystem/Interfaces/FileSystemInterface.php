<?php
/*
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonrisa\Component\FileSystem\Interfaces;

interface FileSystemInterface
{
    public static function softSymLink($original, $alias);

    public static function hardSymLink($original, $alias);

    public static function isLink($path);
}
