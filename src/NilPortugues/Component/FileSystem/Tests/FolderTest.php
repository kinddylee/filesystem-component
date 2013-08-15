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

        if(!file_exists('test'))
        {
            mkdir('test',0777);
        }
        $this->foldername = realpath('test');
    }

    public function testDeleteExistingDirectory()
    {
        $result = $this->folder->create($this->foldername.DIRECTORY_SEPARATOR.'hello');
        $this->assertTrue($result);

        file_put_contents($this->foldername.DIRECTORY_SEPARATOR.'hello'.DIRECTORY_SEPARATOR.'test.txt',rand(100,500));

        $result = $this->folder->delete($this->foldername);

        $this->assertFalse(file_exists($this->foldername.DIRECTORY_SEPARATOR.'hello'.DIRECTORY_SEPARATOR.'test.txt'));
        $this->assertFalse(file_exists($this->foldername.DIRECTORY_SEPARATOR.'hello'));
        $this->assertFalse(file_exists($this->foldername));
        $this->assertTrue($result);
    }


    public function testDeleteNonExistentDirectory()
    {
        $origin = '/THIS/DIRECTORY/DOES/NOT/EXIST/';
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FolderException');

        $this->folder->delete($origin);
    }

    public function testCopyExistingOriginDirectory()
    {
        mkdir('copy-test');
        $result = $this->folder->copy('src','copy-test');
        $result2 = scandir('src');
        $result3 = scandir('copy-test');

        $this->assertTrue($result);
        $this->assertEquals($result2,$result3);

        $this->folder->delete('copy-test');
    }

    public function testCopyNonExistentOriginDirectory()
    {
        $origin = '/THIS/DIRECTORY/DOES/NOT/EXIST/';
        $destination = '../';

        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FolderException');
        $this->folder->copy($origin,$destination);
    }

    public function testCopyExistingDirectoryToTheSameExistingDirectory()
    {
        $origin = '.';
        $destination = '.';

        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FolderException');
        $this->folder->copy($origin,$destination);
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
        //@todo
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
        //@todo
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
            $this->folder->delete('tmp');
        }

    }

    public function testUnzipToNonExistentDirectory()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\ZipException');
        $dir = '/THIS/DIRECTORY/DOES/NOT/EXIST/';

        $this->folder->zip('./src/','test.zip',true);
        $this->folder->unzip('test.zip',$dir,true);
    }

    public function tearDown()
    {
        if(file_exists($this->foldername))
        {
            rmdir($this->foldername);
        }

        if(file_exists('test.zip'))
        {
            unlink('test.zip');
        }

        $this->folder = NULL;
    }

}
