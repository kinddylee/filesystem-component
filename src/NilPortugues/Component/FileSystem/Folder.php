<?php
namespace NilPortugues\Component\FileSystem;

class Folder extends Zip implements \NilPortugues\Component\FileSystem\Interfaces\FolderInterface
{
    //@todo:
    public function getModificationDate($path)
    {

    }

    //@todo:
    public function copy($filePath,$newFilePath,$overwrite=false)
    {

    }

    //@todo:
    public function move($filePath,$newFilePath)
    {

    }

    //@todo:
    public function delete($path)
    {

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

    //@todo:
    public function isReadable($path)
    {

    }

    //@todo:
    public function isWritable($path)
    {

    }

    //@todo:
    public function zip($filePath, $newFileName, $overwrite=false)
    {


    }

    //@todo:
    public function unzip($filePath, $newFileName, $overwrite=false)
    {

    }

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
