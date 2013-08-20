<?php
/*
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileSystemException');

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

        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileSystemException');
        $this->folder->copy($origin,$destination);
    }

    public function testCopyExistingDirectoryToTheSameExistingDirectory()
    {
        $origin = '.';
        $destination = '.';

        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileSystemException');
        $this->folder->copy($origin,$destination);
    }

    public function testCopyNonExistentDestinationDirectory()
    {
        $origin = '.';
        $destination = '/THIS/DIRECTORY/DOES/NOT/EXIST/';

        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileSystemException');
        $this->folder->copy($origin,$destination);
    }

    public function testMoveExistingOriginDirectory()
    {
        $result = $this->folder->create($this->foldername.DIRECTORY_SEPARATOR.'hello');
        $this->assertTrue($result);

        file_put_contents($this->foldername.DIRECTORY_SEPARATOR.'hello'.DIRECTORY_SEPARATOR.'test.txt',rand(100,500));

        $origin = $this->foldername;
        $destination = '../';

        $result = $this->folder->move($origin,$destination);
        $this->assertTrue($result);

        if($result)
        {
            $this->folder->delete('../hello');
        }
    }

    public function testMoveNonExistentOriginDirectory()
    {
        $origin = '/THIS/DIRECTORY/DOES/NOT/EXIST/';
        $destination = '../';

        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileSystemException');
        $this->folder->move($origin,$destination);
    }

    public function testMoveExistingDirectoryToTheSameExistingDirectory()
    {
        $origin = '.';
        $destination = '.';

        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileSystemException');
        $this->folder->move($origin,$destination);
    }

    public function testMoveNonExistentDestinationDirectory()
    {
        $origin = '.';
        $destination = '/THIS/DIRECTORY/DOES/NOT/EXIST/';

        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileSystemException');
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
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileSystemException');
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
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileSystemException');
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


    public function testChmodNonExistentDirectory()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileSystemException');

        $folder = '/THIS/DIRECTORY/DOES/NOT/EXIST/';
        $this->folder->chmod($folder, '0755');
    }

    public function testChmodExistingDirectory()
    {
        $folder = $this->foldername;
        $this->assertTrue($this->folder->chmod($folder, '0777'));
    }

    public function testChangeModificationDateExistingDirectory()
    {
        $folder = $this->foldername;
        $original = $this->folder->getModificationDate($folder);

        $time = time()-3600;
        $this->folder->touch($folder,$time);
        $result = $this->folder->getModificationDate($folder);

        $this->assertEquals($original-3600,$result);
    }

    public function testChangeModificationDateNonExistentDirectory()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileSystemException');
        $folder = '/THIS/DIRECTORY/DOES/NOT/EXIST/';
        $this->folder->touch($folder,time());
    }

    public function testRenameInvalidNewFilename()
    {
        $folder = $this->foldername;

        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileSystemException');
        $this->folder->rename($folder,'ok/a');
    }

    public function testRenameNonExistentDirectory()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileSystemException');
        $folder = '/THIS/DIRECTORY/DOES/NOT/EXIST/';
        $this->folder->rename($folder,'newName');
    }

    public function testRenameExistingDirectorySameName()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileSystemException');
        $folder = $this->foldername;
        $this->folder->rename($folder,$folder);
    }

    public function testRenameValidNewName()
    {
        $folder = $this->foldername;
        $result = $this->folder->rename($folder,'ok');

        $this->assertTrue($result);
        if($result)
        {
            $this->folder->delete('ok');
        }
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
