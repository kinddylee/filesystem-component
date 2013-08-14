<?php
namespace NilPortugues\Component\FileSystem;

use NilPortugues\Component\FileSystem\Exceptions\FileException;

class FileSystem
{
    /**
     * @param string $path
     * @return string
     * @throws Exceptions\FileSystemException
     */
    public function getAbsolutePath($path)
    {
        if(!file_exists($path))
        {
            throw new \NilPortugues\Component\FileSystem\Exceptions\FileSystemException();
        }

        return realpath($path);
    }

    /**
     * Returns the canonical path for the given path.
     *
     * @param string $path
     * @return string The canonical path.
     */
    public function getCanonicalPath($path)
    {
        $canonical = array();
        $path = preg_split('/[\\\\\/]+/', $path);

        foreach ($path as $segment) {
            if ('.' == $segment) {
                continue;
            }

            if ('..' == $segment) {
                array_pop($canonical);
            } else {
                $canonical[] = $segment;
            }
        }

        return join(DIRECTORY_SEPARATOR, $canonical);
    }

    /**
     * Checks if the path is an absolute path.
     *
     * @param string $path
     * @return bool
     */
    public function isAbsolutePath($path)
    {
        if (preg_match('/^([\\\\\/]|[a-zA-Z]\:[\\\\\/])/', $path) || (false !== filter_var($path, FILTER_VALIDATE_URL))) {
            return true;
        }

        return false;
    }


    /**
     * @param string $original
     * @param string $alias
     * @return bool
     * @throws Exceptions\FileSystemException
     */
    public function softSymLink($original, $alias)
    {
        if((!is_file($original) || !is_dir($original)))
        {
            throw new \NilPortugues\Component\FileSystem\Exceptions\FileSystemException();
        }

        if(is_link($original))
        {
            //@todo: check if we can do a link of a system links, if not, remove IF statement.
            throw new \NilPortugues\Component\FileSystem\Exceptions\FileSystemException();
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
        if((!is_file($original) || !is_dir($original)))
        {
            throw new \NilPortugues\Component\FileSystem\Exceptions\FileSystemException();
        }

        if(is_link($original))
        {
            //@todo: check if we can do a link of a system links, if not, remove IF statement.
            throw new \NilPortugues\Component\FileSystem\Exceptions\FileSystemException();
        }

        return link($original,$alias);
    }


}
