<?php
namespace NilPortugues\Component\FileSystem;

use NilPortugues\Component\FileSystem\Exceptions\FileException;

class FileSystem
{
    /**
     * Checks if the path is an absolute path.
     *
     * @param $path
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
     * Returns the canonical path for the given path.
     *
     * @param $path
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
     * This allows us to create symlinks that are moved into place atomically. If an existing symlink exists, it will be replaced.
     * If a file exists at the symlink location, it will be overwritten.
     *
     * @param string $sOriginal Path to the original file
     * @param string $sAlias    Name of the alias file
     */
    public function setSymlink($sOriginal, $sAlias)
    {

    }

    /**
     * Gzip a file
     *
     * @param  string        $sSourceFile
     * @param  string|null   $sDestinationFile
     * @return null|string
     * @throws FileException
     */
    public function createZip($sSourceFile, $sDestinationFile=null)
    {
        if($sDestinationFile === null)
            $sDestinationFile = $this->getZipDestinationFilename($sSourceFile);

        if (! ($fh = fopen($sSourceFile, 'rb'))) {
            throw new FileException("File {$sSourceFile} could not be opened.");
        }


        if (! ($zp = gzopen($sDestinationFile, 'wb9'))) {
            throw new FileException("File {$sDestinationFile} could not be opened .");
        }

        while (! feof($fh)) {
            $data = fread($fh, 16384);
            if (false === $data) {
                throw new FileException("File {$sSourceFile} could not be read.");
            }

            $sz = strlen($data);
            if ($sz !== gzwrite($zp, $data, $sz)) {
                throw new FileException("File {$sDestinationFile} could not be written.");
            }
        }

        gzclose($zp);
        fclose($fh);

        return $sDestinationFile;
    }

    /**
     * Get the default destination file for this source file
     *
     * @param  string $sSourceFile Path to the source file
     * @return string Path to the default output file for this source file
     */
    public function getZipDestinationFilename($sSourceFile)
    {
        $sDestinationFile = $sSourceFile . '.gz';

        return $sDestinationFile;
    }

}
