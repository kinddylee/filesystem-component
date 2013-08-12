<?php
namespace NilPortugues\Component\FileSystem;

use NilPortugues\Component\FileSystem\Exceptions\FileException;

class File implements \NilPortugues\Component\FileSystem\Interfaces\FileInterface
{
    /**
     * @param $filePath
     * @return bool|string
     */
    public function getExtension($filePath)
    {
        if ($this->fileExists($filePath)) {
            $ext = pathinfo($filePath, PATHINFO_EXTENSION);

            return strtolower($ext);
        }

        return false;
    }

    /**
     * Gets last modification time of the file.
     *
     * @param  string $filename
     * @return int
     */
    public function getFileModificationDate($filePath)
    {
        clearstatcache();
        if ($this->fileExists($filePath)) {
            return filemtime($filePath);
        }

        return false;
    }

    /**
     * @param $filePath
     * @param  string                   $time
     * @param  string                   $accessTime
     * @return bool
     * @throws Exceptions\FileException
     */
    public function fileTouch($filePath,$time='',$accessTime='')
    {
        clearstatcache();
        if (!$this->fileExists($filePath)) {
            throw new FileException("File {$filePath} does not exists.");
        }

        if (!$this->fileIsWritable($filePath)) {
            throw new FileException("File {$filePath} is not writable.");
        }

        if (empty($time)) {
            //change modification date
            return touch($filePath);
        } else {
            if (empty($accessTime)) {
                //change modification date with the specified date
                return touch($filePath,$time);
            } else {
                //change access time
                return touch($filePath,$time,$accessTime);
            }
        }
    }

    /**
     * Determine if the file exists.
     *
     * @param  string  $filename Filename with optional path
     * @return boolean
     */
    public function fileExists($filePath)
    {
        clearstatcache();

        return (is_file($filePath) &&  file_exists($filePath));
    }

    /**
     * Determine if the file can be opened for reading
     *
     * @param  string                   $filePath
     * @return bool
     * @throws Exceptions\FileException
     */
    public function fileIsReadable($filePath)
    {
        if (!$this->fileExists($filePath)) {
            throw new FileException("File {$filePath} does not exists.");
        }

        return is_readable($filePath);
    }

    /**
     * @param  string                   $filePath Filename with optional path
     * @return bool
     * @throws Exceptions\FileException
     */
    public function fileIsWritable($filePath)
    {
        if (!$this->fileExists($filePath)) {
            throw new FileException("File {$filePath} does not exists.");
        }

        return is_writable($filePath);
    }

    /**
     * Return the file contents in a variable.
     *
     * @param  string                   $filePath
     * @return string
     * @throws Exceptions\FileException
     */
    public function fileGetContents($filePath)
    {
        if (!$this->fileExists($filePath)) {
            throw new FileException("File {$filePath} does not exist.");
        }

        if ( $this->fileIsReadable($filePath) ) {
            return file_get_contents($filePath);
        } else {
            throw new FileException("File {$filePath} is not readable.");
        }
    }

    /**
     * Writes data in the file. If the $mode parameter is set chmod will be made ONLY if
     * the file did not exists before the operation.
     *
     * @param  string $filePath
     * @param  string $data
     * @param  string $mode
     * @return int    The number of bytes (not chars!) that were written to the file, or FALSE on failure.
     */
    public function filePutContents($filePath, $data, $mode = null)
    {
        if (!$this->fileIsWritable($filePath) || !is_file($filePath)) {
            throw new FileException("File {$filePath} is not writable.");
        }

        $chmod = !is_null($mode) && !is_file($filePath);
        $res = file_put_contents($filePath, $data, LOCK_EX);
        $chmod && $this->fileChmod($filePath, $mode);

        return $res;
    }

    /**
     * Append given data to the file.
     *
     * @param  string  $filePath
     * @param  string  $data
     * @return integer The number of bytes that were written to the file, or FALSE on failure.
     */
    public function fileAppend($filePath, $data)
    {
        if (!$this->fileIsWritable($filePath) || !is_file($filePath)) {
            throw new FileException("File {$filePath} is not writable.");
        }

        return file_put_contents($filePath, $data, LOCK_EX | FILE_APPEND);
    }

    /**
     * Changes file mode
     *
     * @param  string  $filePath
     * @param  string  $mode
     * @return boolean TRUE on success or FALSE on failure.
     */
    public function fileChmod($filePath, $mode)
    {
        if (!$this->fileExists($filePath)) {
            throw new FileException("File {$filePath} does not exist.");
        }

        return chmod($filePath, $mode);
    }

    /**
     * Deletes a file. If the file has been deleted, returns TRUE, otherwise, FALSE.
     *
     * @param  string                   $filePath
     * @return bool
     * @throws Exceptions\FileException
     */
    public function fileDelete($filePath)
    {
        if ( $this->fileExists($filePath) ) {
            return unlink($filePath);
        } else {
            throw new FileException("File {$filePath} does not exist.");
        }
    }

    /**
     * Renames a file. Throws exception if a file with the new name already exists in the directory.
     *
     * @param  string                   $filePath
     * @param  string                   $newFileName
     * @param  bool                     $overwrite
     * @return bool
     * @throws Exceptions\FileException
     */
    public function fileRename($filePath,$newFileName,$overwrite=false)
    {
        if (!$this->fileExists($filePath)) {
            throw new FileException("File {$filePath} does not exist.");
        }

        if ( strpos( $newFileName,DIRECTORY_SEPARATOR )!==false ) {
            throw new FileException("{$newFileName} has to be a valid file name, and cannot contain the directory separator symbol ".DIRECTORY_SEPARATOR.".");
        }

        $pathWithoutFileName = pathinfo($filePath,PATHINFO_DIRNAME);
        $newFilePath = $pathWithoutFileName.DIRECTORY_SEPARATOR.$newFileName;

        if ( $overwrite==false && $this->fileExists($newFilePath) ) {
            throw new FileException("Cannot rename file {$filePath} to {$newFileName}. A file with he same name already exists at {$pathWithoutFileName}.");
        }

        return rename( $filePath, $newFilePath );
    }
}
