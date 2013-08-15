<?php
namespace NilPortugues\Component\FileSystem;

use \NilPortugues\Component\FileSystem\Exceptions\FolderException;

class Folder extends Zip implements \NilPortugues\Component\FileSystem\Interfaces\FolderInterface
{
    //@todo:
    public function getModificationDate($path)
    {

    }

     /**
     * Copies files from one directory to another
     *
     * @param $path
     * @param $destinationPath
     * @return bool
     */
    public function copy($path,$destinationPath)
    {
        if(!file_exists($path) || !is_dir($path) )
        {
            throw new FolderException("Origin folder {$path} does not exist.");
        }

        if(!file_exists($destinationPath) || !is_dir($destinationPath))
        {
            throw new FolderException("Destination folder {$destinationPath} does not exist.");
        }

        return $this->recursiveCopy($path,$destinationPath);
    }

    /**
     * Recursively copy files from one directory to another.
     *
     * @param $path
     * @param $destinationPath
     * @return bool
     */
    protected function recursiveCopy($path,$destinationPath)
    {
        // If source is not a directory stop processing
        if(!is_dir($path)) return false;

        // If the destination directory does not exist create it
        if(!is_dir($destinationPath))
        {
            if(!mkdir($destinationPath))
            {
                // If the destination directory could not be created stop processing
                return false;
            }
        }

        // Open the source directory to read in files
        $i = new \DirectoryIterator($path);
        foreach($i as $f)
        {
            if($f->isFile())
            {
                copy($f->getRealPath(), $destinationPath .DIRECTORY_SEPARATOR. $f->getFilename());
            }
            else if(!$f->isDot() && $f->isDir())
            {
                $this->recursiveCopy($f->getRealPath(), $destinationPath.DIRECTORY_SEPARATOR.$f);
            }
        }
        return true;
    }

    /**
     * Moves files from one directory to another
     *
     * @param string $path
     * @param string $destinationPath
     * @return bool
     * @throws Exceptions\FolderException
     */
    public function move($path, $destinationPath)
    {
        if(!file_exists($path) || !is_dir($path) )
        {
            throw new FolderException("Origin folder {$path} does not exist.");
        }

        if(!file_exists($destinationPath) || !is_dir($destinationPath))
        {
            throw new FolderException("Destination folder {$destinationPath} does not exist.");
        }

        return $this->recursiveMove($path,$destinationPath);
    }

    /**
     * Recursively move files from one directory to another
     *
     * @param string $path
     * @param string $destinationPath
     * @return bool
     */
    protected function recursiveMove($path, $destinationPath)
    {
        // If source is not a directory stop processing
        if(!is_dir($path))
        {
            return false;
        }

        // If the destination directory does not exist create it
        if(!is_dir($destinationPath))
        {
            if(!mkdir($destinationPath))
            {
                // If the destination directory could not be created stop processing
                return false;
            }
        }

        // Open the source directory to read in files
        $i = new \DirectoryIterator($path);
        foreach($i as $f)
        {
            if($f->isFile())
            {
                rename($f->getRealPath(), $destinationPath .DIRECTORY_SEPARATOR. $f->getFilename());
            }
            else if(!$f->isDot() && $f->isDir())
            {
                $this->recursiveMove($f->getRealPath(), $destinationPath.DIRECTORY_SEPARATOR.$f);
                unlink($f->getRealPath());
            }
        }
        unlink($path);

        return true;
    }

    /**
     * Removes a directory and all the files and directories within it.
     *
     * @param $path
     * @return bool
     * @throws Exceptions\FolderException
     */
    public function delete($path)
    {
        if(!file_exists($path) || !is_dir($path) )
        {
            throw new FolderException("Folder {$path} does not exist.");
        }

        return $this->recursivelyDelete($path);
    }

    /**
     * Recursively deletes files and directories
     *
     * @param string $path
     * @return bool
     */
    protected function recursivelyDelete($path)
    {
        if (!file_exists($path))
        {
            return true;
        }

        if (!is_dir($path) || is_link($path))
        {
            return unlink($path);
        }

        $dirFiles = scandir($path);
        foreach ($dirFiles as $item)
        {
            if ($item == '.' || $item == '..') continue;

            if (!$this->recursivelyDelete($path . DIRECTORY_SEPARATOR . $item))
            {
                chmod($path . DIRECTORY_SEPARATOR . $item, 0777);

                if (!$this->recursivelyDelete($path . DIRECTORY_SEPARATOR . $item))
                {
                    return false;
                }
            };
        }
        return rmdir($path);
    }


    //@todo:
    public function rename($path,$newName)
    {

    }

    //@todo:
    public function touch($path,$time='',$accessTime='')
    {

    }

    //@todo:
    public function chmod($path, $mode)
    {

    }

    /**
     * @param string $path
     * @return bool
     * @throws Exceptions\FolderException
     */
    public function isReadable($path)
    {
        if (!$this->exists($path)) {
            throw new FolderException("File {$path} does not exists.");
        }

        return is_readable($path);
    }

    /**
     * @param string $path
     * @return bool
     * @throws Exceptions\FolderException
     */
    public function isWritable($path)
    {
        if (!$this->exists($path)) {
            throw new FolderException("File {$path} does not exists.");
        }

        return is_writable($path);
    }

    /**
     * Determine if the folder exists.
     *
     * @param $filePath
     * @return bool
     */
    public function exists($path)
    {
        clearstatcache();

        return (is_dir($path) &&  file_exists($path));
    }

    /**
     * @param string $path
     * @return bool
     * @throws Exceptions\FolderException
     */
    public function create($path)
    {
        if(file_exists($path) && is_file($path))
        {
            throw new \NilPortugues\Component\FileSystem\Exceptions\FolderException("Cannot create the {$path} folder because a file with the same name exists.");
        }

        if($this->exists($path))
        {
            throw new \NilPortugues\Component\FileSystem\Exceptions\FolderException("Cannot create the {$path} folder because it already exists.");
        }

        //Create the directory recursively
        if (!is_dir($path) && !is_file($path) )
        {
            $directory_path = "";
            $directories = explode("/",$path);
            array_pop($directories);

            foreach($directories as $directory)
            {
                $directory_path .= $directory."/";
                if ( !is_dir($directory_path) && !is_file($directory_path) )
                {
                    if(!is_writable($directory_path))
                    {
                        throw new \NilPortugues\Component\FileSystem\Exceptions\FolderException("Cannot create {$path} folder because {$directory_path} is not writable.");
                    }
                    else
                    {
                        mkdir($directory_path,0777);
                    }
                }
            }
            return true;
        }
        return false;
    }
}
