<?php
namespace NilPortugues\Component\FileSystem;

abstract class Zip
{
    /**
     * @param $filePath
     * @param $newFileName
     * @param bool $overwrite
     * @return bool
     * @throws Exceptions\ZipException
     */
    public function zip($filePath, $newFileName, $overwrite=false)
    {
        $filePath = str_replace('\\', '/', $filePath);

        if(!file_exists($filePath))
        {
            throw new \NilPortugues\Component\FileSystem\Exceptions\ZipException("File {$filePath} does not exist therefore it cannot be zipped.");
        }

        if(file_exists($newFileName) && $overwrite == false)
        {
            throw new \NilPortugues\Component\FileSystem\Exceptions\ZipException("Cannot create {$newFileName} zip file because it already exists a file with the same name.");
        }

        $zip = new \ZipArchive();
        if( $overwrite == true )
        {
            $open = $zip->open($newFileName, \ZipArchive::OVERWRITE);
        }
        else
        {
            $open = $zip->open($newFileName, \ZipArchive::CREATE);
        }

        if( $open )
        {
            if(is_dir($filePath))
            {
                //remove . or / from the beginning of $filePath
                if($filePath[0]=='.' || $filePath[0]=='/')
                {
                    $filePath = substr($filePath, 1);
                    if( $filePath[0]=='/' )
                    {
                        $filePath = substr($filePath, 1);
                    }
                }

                //create a Zip from a directory RECURSIVELY
                if (false !== ($dir = opendir($filePath)))
                {
                    $files = new \RecursiveIteratorIterator
                    (
                        new \RecursiveDirectoryIterator($filePath),
                        \RecursiveIteratorIterator::SELF_FIRST
                    );

                    foreach ($files as $file)
                    {
                        $f = explode(DIRECTORY_SEPARATOR,$file);
                        $last = trim(end($f));

                        if($last !== '.' && $last !== '..')
                        {
                            $file = str_replace('\\', '/', $file);

                            //Add the files and directories
                            if (is_dir($file) === true)
                            {
                                $zip->addEmptyDir( $file );
                            }
                            else
                            {
                                $zip->addFile($file);
                            }
                        }
                    }
                }
            }
            else
            {
                //remove . or / from the beginning of $filePath
                if($filePath[0]=='.' || $filePath[0]=='/')
                {
                    $filePath = substr($filePath, 1);
                    if( $filePath[0]=='/' )
                    {
                        $filePath = substr($filePath, 1);
                    }
                }

                //Add the file.
                $zip->addFile($filePath);
            }
            $zip->close();
        }
        else
        {
            return false;
        }
        return file_exists($newFileName);
    }


    /**
     * @param string $filePath
     * @param string $destinationPath
     * @param bool $overwrite
     * @return bool
     * @throws Exceptions\ZipException
     */
    public function unzip($filePath, $destinationPath, $overwrite=false)
    {
        $destinationPath = $destinationPath.DIRECTORY_SEPARATOR;

        if(!file_exists($filePath) || !is_file($filePath))
        {
            throw new \NilPortugues\Component\FileSystem\Exceptions\ZipException("File {$filePath} does not exist therefore it cannot be unzipped.");
        }

        $unzipPath = $filePath;
        if(file_exists($unzipPath) && $overwrite==false)
        {
            throw new \NilPortugues\Component\FileSystem\Exceptions\ZipException("Cannot unzip {$unzipPath} because it already exists a file or folder with the same name.");
        }


        $open = zip_open($unzipPath);
        if( is_resource($open) )
        {
            //create the Folder object because we'll be using it a couple of times.
            $folder = new \NilPortugues\Component\FileSystem\Folder();

            //If output directory does not exist, try creating it.
            if(!file_exists($destinationPath))
            {
                try
                {
                    $folder->create($destinationPath);
                }
                catch(\NilPortugues\Component\FileSystem\Exceptions\FolderException $e)
                {
                    throw new \NilPortugues\Component\FileSystem\Exceptions\ZipException($e->getMessage());
                }
            }

            //Now extract files in the destinationPath
            $_tmp = array();
            while ($zip_entry = zip_read($open))
            {
                //Read all data for each file
                $_tmp["filename"] = $destinationPath.DIRECTORY_SEPARATOR.zip_entry_name($zip_entry);
                $_tmp["stored_filename"] = $destinationPath.DIRECTORY_SEPARATOR.zip_entry_name($zip_entry);
                $_tmp["folder"] = $destinationPath.DIRECTORY_SEPARATOR.dirname(zip_entry_name($zip_entry));
                $_tmp["size"] = zip_entry_filesize($zip_entry);

                //Fetch entry
                if(zip_entry_open($open, $zip_entry, "r"))
                {
                    if( substr($_tmp["filename"],-1) == DIRECTORY_SEPARATOR )
                    {
                        //is dir
                        if(!file_exists($_tmp["filename"]))
                        {
                            $result = mkdir($_tmp["filename"],0777,true);
                            if($result == false)
                            {
                                throw new \NilPortugues\Component\FileSystem\Exceptions\ZipException("Could not create directory {$_tmp["filename"]}.");
                            }
                        }
                    }
                    else
                    {
                        //Read the file
                        $buffer = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
                        file_put_contents($_tmp["stored_filename"],$buffer);
                    }

                    //Fetch the next zip entry.
                    zip_entry_close($zip_entry);
                }
                else
                {
                    throw new \NilPortugues\Component\FileSystem\Exceptions\ZipException("An error occurred while extracting {$_tmp["stored_filename"]}.");
                }

            }
            return true;
        }
        else
        {
            return false;
        }

    }
}