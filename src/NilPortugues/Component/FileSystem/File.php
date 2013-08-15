<?php
namespace NilPortugues\Component\FileSystem;

use NilPortugues\Component\FileSystem\Exceptions\FileSystemException;

class File extends FileSystem implements \NilPortugues\Component\FileSystem\Interfaces\FileInterface
{

    /**
     * @param string $filePath
     * @param string $newFilePath
     * @param bool $overwrite
     * @return bool
     * @throws Exceptions\FileSystemException
     */
    public function copy($filePath,$newFilePath,$overwrite=false)
    {
        $pathWithoutFileName = pathinfo($newFilePath,PATHINFO_DIRNAME);

        if (!$this->exists($filePath)) {
            throw new FileSystemException("File {$filePath} does not exist.");
        }

        if (!is_dir($pathWithoutFileName)) {
            throw new FileSystemException("Destination folder {$pathWithoutFileName} does not exist.");
        }

        if ( $overwrite==false && $this->exists($newFilePath) )
        {

            throw new FileSystemException("Cannot rename file {$filePath} to {$newFilePath}. A file with he same name already exists at {$pathWithoutFileName}.");
        }

        return copy($filePath, $newFilePath);
    }

    /**
     * @param string $filePath
     * @param string $newFilePath
     * @param bool $overwrite
     * @return bool
     * @throws Exceptions\FileSystemException
     */
    public function move($filePath,$destinationFolder,$overwrite=false)
    {
        if (!$this->exists($filePath)) {
            throw new FileSystemException("File {$filePath} does not exist.");
        }

        if (!is_dir($destinationFolder)) {
            throw new FileSystemException("Destination folder {$destinationFolder} does not exist.");
        }

        $newFilePath = $destinationFolder.DIRECTORY_SEPARATOR.basename($filePath);
        if ( $overwrite==false && $this->exists($newFilePath) )
        {

            throw new FileSystemException("Cannot rename file {$filePath} to {$newFilePath}. A file with he same name already exists at {$destinationFolder}.");
        }

        return rename( $filePath, $newFilePath );
    }

    /**
     * @param $filePath
     * @return bool|string
     */
    public function getExtension($filePath)
    {
        if ($this->exists($filePath)) {
            $ext = pathinfo($filePath, PATHINFO_EXTENSION);

            return strtolower($ext);
        }

        return false;
    }

    /**
     * Gets last modification time of the file.
     *
     * @param  string $filename
     * @return bool|int
     */
    public function getModificationDate($filePath)
    {
        clearstatcache();
        if ($this->exists($filePath)) {
            return filemtime($filePath);
        }

        return false;
    }

    /**
     * @param $filePath
     * @param  string                   $time
     * @param  string                   $accessTime
     * @return bool
     * @throws Exceptions\FileSystemException
     */
    public function touch($filePath,$time='',$accessTime='')
    {
        if (!$this->isWritable($filePath)) {
            throw new FileSystemException("File {$filePath} is not writable.");
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
     * @param $filePath
     * @return bool
     */
    public function exists($filePath)
    {
        clearstatcache();

        return (is_file($filePath) &&  file_exists($filePath));
    }

    /**
     * Determine if the file can be opened for reading
     *
     * @param  string                   $filePath
     * @return bool
     * @throws Exceptions\FileSystemException
     */
    public function isReadable($filePath)
    {
        if (!$this->exists($filePath)) {
            throw new FileSystemException("File {$filePath} does not exists.");
        }

        return is_readable($filePath);
    }

    /**
     * @param $filePath
     * @return bool
     * @throws Exceptions\FileSystemException
     */
    public function isWritable($filePath)
    {
        if (!$this->exists($filePath)) {
            throw new FileSystemException("File {$filePath} does not exists.");
        }

        return is_writable($filePath);
    }

    /**
     * Return the file contents in a variable.
     *
     * @param  string                   $filePath
     * @return string
     * @throws Exceptions\FileSystemException
     */
    public function read($filePath)
    {
        if (!$this->exists($filePath)) {
            throw new FileSystemException("File {$filePath} does not exist.");
        }

        if ( $this->isReadable($filePath) ) {
            return file_get_contents($filePath);
        } else {
            throw new FileSystemException("File {$filePath} is not readable.");
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
     * @throws Exceptions\FileSystemException
     */
    public function write($filePath, $data, $mode = 0644)
    {
        $dir = pathinfo($filePath,PATHINFO_DIRNAME);
        if(!file_exists($dir) && !is_dir($dir))
        {
            throw new FileSystemException("Folder {$dir} does not exist.");
        }

        if(!file_exists($filePath))
        {
            $res = file_put_contents($filePath, $data);
        }
        else
        {
            if (!$this->isWritable($filePath) || !is_file($filePath)) {
                throw new FileSystemException("File {$filePath} is not writable.");
            }
            $res = file_put_contents($filePath, $data);
        }

        $this->chmod($filePath, $mode);

        return $res;
    }

    /**
     * Append given data to the file.
     *
     * @param  string  $filePath
     * @param  string  $data
     * @return integer The number of bytes that were written to the file, or FALSE on failure.
     * @throws Exceptions\FileSystemException
     */
    public function append($filePath, $data)
    {
        if (!$this->isWritable($filePath) || !is_file($filePath)) {
            throw new FileSystemException("File {$filePath} is not writable.");
        }

        return file_put_contents($filePath, $data, LOCK_EX | FILE_APPEND);
    }

    /**
     * Changes file mode
     *
     * @param  string  $filePath
     * @param  string  $mode
     * @return boolean TRUE on success or FALSE on failure.
     * @throws Exceptions\FileSystemException
     */
    public function chmod($filePath, $mode)
    {
        if (!$this->exists($filePath)) {
            throw new FileSystemException("File {$filePath} does not exist.");
        }

        return chmod($filePath, $mode);
    }

    /**
     * Deletes a file. If the file has been deleted, returns TRUE, otherwise, FALSE.
     *
     * @param  string                   $filePath
     * @return bool
     * @throws Exceptions\FileSystemException
     */
    public function delete($filePath)
    {
        if ( $this->exists($filePath) ) {
            return unlink($filePath);
        } else {
            throw new FileSystemException("File {$filePath} does not exist.");
        }
    }

    /**
     * Renames a file. Throws exception if a file with the new name already exists in the directory.
     *
     * @param  string                   $filePath
     * @param  string                   $newFileName
     * @param  bool                     $overwrite
     * @return bool
     * @throws Exceptions\FileSystemException
     */
    public function rename($filePath,$newFileName,$overwrite=false)
    {
        if (!$this->exists($filePath)) {
            throw new FileSystemException("File {$filePath} does not exist.");
        }

        if ( strpos( $newFileName,DIRECTORY_SEPARATOR )!==false ) {
            throw new FileSystemException("{$newFileName} has to be a valid file name, and cannot contain the directory separator symbol ".DIRECTORY_SEPARATOR.".");
        }

        $pathWithoutFileName = pathinfo($filePath,PATHINFO_DIRNAME);
        $newFilePath = $pathWithoutFileName.DIRECTORY_SEPARATOR.$newFileName;

        if ( $overwrite==false && $this->exists($newFilePath) ) {
            throw new FileSystemException("Cannot rename file {$filePath} to {$newFileName}. A file with he same name already exists at {$pathWithoutFileName}.");
        }

        return rename( $filePath, $newFilePath );
    }


    /**
     * Compress a file with the zlib-extension.
     *
     * @param string $filePath
     * @param string $newFileName
     * @param bool $overwrite
     * @param string $param
     * @return bool
     * @throws Exceptions\FileSystemException
     */
    public function gzip($filePath, $newFileName, $overwrite=false, $param="1")
    {
        if (!$this->exists($filePath)) {
            throw new FileSystemException("File {$filePath} does not exist.");
        }

        if ($overwrite==false && $this->exists($newFileName)) {
            throw new FileSystemException("File {$newFileName} cannot be created because it already exists.");
        }

        if ( !is_writable(dirname($newFileName)) ) {
            throw new FileSystemException("Cannot write {$newFileName} in the file system.");
        }

        $in_file = fopen ($filePath, "rb");
        if (!$out_file = gzopen ($newFileName, "wb".$param)) {
            return false;
        }

        while (!feof ($in_file)) {
            $buffer = fgets ($in_file, 4096);
            gzwrite ($out_file, $buffer, 4096);
        }

        fclose ($in_file);
        gzclose ($out_file);

        return true;
    }

    /**
     * Uncompress a file with the zlib-extension.
     *
     * @param $filePath
     * @param $newFileName
     * @param bool $overwrite
     * @return bool
     * @throws Exceptions\FileSystemException
     */
    public function gunzip($filePath,$newFileName,$overwrite=false)
    {
        if (!$this->exists($filePath)) {
            throw new FileSystemException("File {$filePath} does not exist.");
        }

        if ($overwrite==false && $this->exists($newFileName)) {
            throw new FileSystemException("File {$newFileName} cannot be created because it already exists.");
        }

        if ( !is_writable(dirname($newFileName)) ) {
            throw new FileSystemException("Cannot write {$newFileName} in the file system.");
        }

        $in_file = gzopen ($filePath, "rb");
        $out_file = fopen ($newFileName, "wb");

        while (!gzeof($in_file))
        {
            fwrite($out_file, gzread($in_file, 4096));
        }

        gzclose($in_file);
        fclose($out_file);
        return true;
    }
}
