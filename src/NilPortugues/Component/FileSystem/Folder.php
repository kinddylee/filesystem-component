<?php
namespace NilPortugues\Component\FileSystem;

use \NilPortugues\Component\FileSystem\Exceptions\FileSystemException;

class Folder extends FileSystem implements \NilPortugues\Component\FileSystem\Interfaces\FolderInterface
{
    /**
     * Gets last modification time of a folder.
     *
     * @param  string $filename
     * @return bool|int
     */
    public function getModificationDate($path)
    {
        clearstatcache();
        if ($this->exists($path)) {
            return filemtime($path);
        }

        return false;
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
        if(realpath($path) == realpath($destinationPath))
        {
            throw new FileSystemException("Origin folder and destination folder cannot be the same.");
        }

        if(!file_exists($path) || !is_dir($path) )
        {
            throw new FileSystemException("Origin folder {$path} does not exist.");
        }

        if(!file_exists($destinationPath) || !is_dir($destinationPath))
        {
            throw new FileSystemException("Destination folder {$destinationPath} does not exist.");
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
     * @throws Exceptions\FileSystemException
     */
    public function move($path, $destinationPath)
    {
        if(realpath($path) == realpath($destinationPath))
        {
            throw new FileSystemException("Origin folder and destination folder cannot be the same.");
        }

        if(!file_exists($path) || !is_dir($path) )
        {
            throw new FileSystemException("Origin folder {$path} does not exist.");
        }

        if(!file_exists($destinationPath) || !is_dir($destinationPath))
        {
            throw new FileSystemException("Destination folder {$destinationPath} does not exist.");
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

        $path = $path . DIRECTORY_SEPARATOR;

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

                if(is_file($f->getRealPath()) || is_link($f->getRealPath()))
                {
                    unlink($f->getRealPath());
                }

                if(is_dir($f->getRealPath()))
                {
                    rmdir($f->getRealPath());
                }

            }
        }

        return true;
    }

    /**
     * Removes a directory and all the files and directories within it.
     *
     * @param $path
     * @return bool
     * @throws Exceptions\FileSystemException
     */
    public function delete($path)
    {
        if(file_exists($path) && is_dir($path) )
        {
            return $this->recursivelyDelete($path);
        }
        throw new FileSystemException("Folder {$path} does not exist.");

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

    /**
     * Renames a folder. Throws exception if a folder with the new name already exists in $path directory.
     *
     * @param  string                   $path
     * @param  string                   $newName
     * @return bool
     * @throws Exceptions\FileSystemException
     */
    public function rename($path,$newName)
    {
        if(realpath($path) == realpath($newName))
        {
            throw new FileSystemException("Current folder name and new name are the same.");
        }

        if (!$this->exists($path)) {
            throw new FileSystemException("Folder {$path} does not exist.");
        }

        if ($this->exists($newName)) {
            throw new FileSystemException("Folder {$newName} already exists. Folder {$path} cannot renamed.");
        }

        if ( strpos( $newName,DIRECTORY_SEPARATOR )!==false ) {
            throw new FileSystemException("{$newName} has to be a valid folder name, and cannot contain the directory separator symbol ".DIRECTORY_SEPARATOR.".");
        }

        return rename( $path, $newName );
    }

    /**
     * @param string $path
     * @param string                   $time
     * @param string                   $accessTime
     * @return bool
     * @throws Exceptions\FileSystemException
     */
    public function touch($path,$time='',$accessTime='')
    {
        if (!$this->isWritable($path)) {
            throw new FileSystemException("Folder {$path} is not writable.");
        }

        if (empty($time)) {
            //change modification date
            return touch($path);
        } else {
            if (empty($accessTime)) {
                //change modification date with the specified date
                return touch($path,$time);
            } else {
                //change access time
                return touch($path,$time,$accessTime);
            }
        }
    }

    /**
     * Changes a folder access permissions.
     *
     * @param  string  $path
     * @param  string  $mode
     * @return boolean TRUE on success or FALSE on failure.
     * @throws Exceptions\FileException
     */
    public function chmod($path, $mode)
    {
        if (!$this->exists($path)) {
            throw new FileSystemException("File {$path} does not exist.");
        }

        return chmod($path, $mode);
    }
    /**
     * @param string $path
     * @return bool
     * @throws Exceptions\FileSystemException
     */
    public function isReadable($path)
    {
        if (!$this->exists($path)) {
            throw new FileSystemException("File {$path} does not exists.");
        }

        return is_readable($path);
    }

    /**
     * @param string $path
     * @return bool
     * @throws Exceptions\FileSystemException
     */
    public function isWritable($path)
    {
        if (!$this->exists($path)) {
            throw new FileSystemException("File {$path} does not exists.");
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
     * @throws Exceptions\FileSystemException
     */
    public function create($path)
    {
        if(file_exists($path) && is_file($path))
        {
            throw new \NilPortugues\Component\FileSystem\Exceptions\FileSystemException("Cannot create the {$path} folder because a file with the same name exists.");
        }

        if($this->exists($path))
        {
            throw new \NilPortugues\Component\FileSystem\Exceptions\FileSystemException("Cannot create the {$path} folder because it already exists.");
        }

        return mkdir($path,0755,true);
    }
}
