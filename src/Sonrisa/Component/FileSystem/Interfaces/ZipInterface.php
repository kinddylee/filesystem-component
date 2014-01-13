<?php
namespace Sonrisa\Component\FileSystem\Interfaces;

interface ZipInterface
{
    /**
     * Gzip a file
     *
     * @param  string             $sSourceFile
     * @param  string|null        $sDestinationFile
     * @return null|string
     * @throws FileOpenException
     * @throws FileReadException
     * @throws FileWriteException
     */
    public static function createZip($sSourceFile, $sDestinationFile=null);

    /**
     * Get the default destination file for this source file
     *
     * @param  string $sSourceFile Path to the source file
     * @return string Path to the default output file for this source file
     */
    public static function getZipDestinationFilename($sSourceFile);
}
