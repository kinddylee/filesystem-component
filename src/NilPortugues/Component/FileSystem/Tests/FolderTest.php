<?php
namespace NilPortugues\Component\FileSystem\Test;

class FolderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \NilPortugues\Component\FileSystem\Folder
     */
    protected $folder;
    protected $foldername;

    public function setUp()
    {
        $this->folder = new \NilPortugues\Component\FileSystem\Folder();

        mkdir('test');
        $this->foldername = realpath('./test');
    }

    public function testDeleteExistingOriginDirectory()
    {

    }

    public function testDeleteNonExistentOriginDirectory()
    {
        $origin = '/THIS/DIRECTORY/DOES/NOT/EXIST/';
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FolderException');
        $this->folder->delete($origin);
    }


    public function testCopyExistingOriginDirectory()
    {

    }

    public function testCopyNonExistentOriginDirectory()
    {
        $origin = '/THIS/DIRECTORY/DOES/NOT/EXIST/';
        $destination = '../';

        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FolderException');
        $this->folder->copy($origin,$destination);
    }

    public function testCopyExistingDestinationDirectory()
    {

    }

    public function testCopyNonExistentDestinationDirectory()
    {
        $origin = '.';
        $destination = '/THIS/DIRECTORY/DOES/NOT/EXIST/';

        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FolderException');
        $this->folder->copy($origin,$destination);
    }





    public function testMoveExistingOriginDirectory()
    {

    }

    public function testMoveNonExistentOriginDirectory()
    {
        $origin = '/THIS/DIRECTORY/DOES/NOT/EXIST/';
        $destination = '../';

        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FolderException');
        $this->folder->move($origin,$destination);
    }

    public function testMoveExistingDestinationDirectory()
    {

    }

    public function testMoveNonExistentDestinationDirectory()
    {
        $origin = '.';
        $destination = '/THIS/DIRECTORY/DOES/NOT/EXIST/';

        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FolderException');
        $this->folder->move($origin,$destination);
    }



    public function testFolderExistsExistingDirectory()
    {
        $folder = $this->foldername;
        $this->assertTrue($this->folder->exists($folder));
    }

    public function testFolderExistsNonExistentDirectory()
    {
        $folder = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->foldername;
        $this->assertFalse($this->folder->exists($folder));
    }

    public function testFolderIsWritableExistingDirectory()
    {
        $folder = $this->foldername;
        $this->assertTrue($this->folder->isWritable($folder));
    }

    public function testFolderIsWritableNonExistentDirectory()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FolderException');
        $folder = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->foldername;
        $this->folder->isWritable($folder);
    }

    public function testFolderIsReadableExistingDirectory()
    {
        $folder = $this->foldername;
        $this->assertTrue($this->folder->isReadable($folder));
    }

    public function testFolderIsReadableNonExistentDirectory()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FolderException');
        $folder = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->foldername;
        $this->folder->isReadable($folder);
    }

    public function testZipExistingDirectory()
    {
        $file = './src/';
        $result = $this->folder->zip($file,'test.zip',true);

        $this->assertTrue( $result );
        if($result)
        {
            unlink('test.zip');
        }
    }

    public function testZipNonExistentDirectory()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\ZipException');
        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/';
        $this->folder->zip($file,'test.zip',true);
    }


    public function testUnzipExistingDirectory()
    {
        $file = './src/';
        $result = $this->folder->zip($file,'test.zip',true);

        if(!file_exists('tmp')){
            mkdir('tmp',0777);
        }

        $result2 =$this->folder->unzip('test.zip','./tmp',true);
        $this->assertTrue( $result2 );

        $this->assertTrue( $result );
        if($result)
        {
            unlink('test.zip');
        }

    }

    public function testUnzipToNonExistentDirectory()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\ZipException');
        $dir = '/THIS/DIRECTORY/DOES/NOT/EXIST/';

        $result = $this->folder->zip('./src/','test.zip',true);
        $this->folder->unzip('test.zip',$dir,true);

        $this->assertTrue( $result );
        if($result)
        {
            unlink('test.zip');
        }
    }

    public function tearDown()
    {
        $this->folder = NULL;

        if(file_exists($this->foldername))
        {
            rmdir($this->foldername);
        }
    }
}
