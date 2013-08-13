<?php
namespace NilPortugues\Component\FileSystem;

class Folder extends Zip implements \NilPortugues\Component\FileSystem\Interfaces\FolderInterface
{
    /**
     * @param string $path
     * @return bool
     */
    public function exists($path)
    {
        return file_exists($path) && is_dir($path);
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
        if (!is_dir($path))
        {
            $directory_path = "";
            $directories = explode("/",$path);
            array_pop($directories);

            foreach($directories as $directory)
            {
                $directory_path .= $directory."/";
                if (!is_dir($directory_path) )
                {
                    if(!is_writable($directory_path))
                    {
                        throw new \NilPortugues\Component\FileSystem\Exceptions\FolderException("Cannot create {$path} folder because {$directory_path} is not writable.");
                    }

                    mkdir($directory_path);
                    chmod($directory_path, 0777);
                }
            }
            return true;
        }
        return false;
    }
}
