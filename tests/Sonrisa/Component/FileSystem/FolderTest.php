<?php
/*
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonrisa\Component\FileSystem\Test;
use \Sonrisa\Component\FileSystem\Folder as Folder;

class FolderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Sonrisa\Component\FileSystem\Folder
     */
    protected $folder;
    protected $foldername;

    public function setUp()
    {
        if(!file_exists('test'))
        {
            mkdir('test',0777);
        }
        $this->foldername = realpath('test');
    }

    public function testDeleteExistingDirectory()
    {
        $result = Folder::create($this->foldername.DIRECTORY_SEPARATOR.'hello');
        $this->assertTrue($result);

        file_put_contents($this->foldername.DIRECTORY_SEPARATOR.'hello'.DIRECTORY_SEPARATOR.'test.txt',rand(100,500));

        $result = Folder::delete($this->foldername);

        $this->assertFalse(file_exists($this->foldername.DIRECTORY_SEPARATOR.'hello'.DIRECTORY_SEPARATOR.'test.txt'));
        $this->assertFalse(file_exists($this->foldername.DIRECTORY_SEPARATOR.'hello'));
        $this->assertFalse(file_exists($this->foldername));
        $this->assertTrue($result);
    }


    public function testDeleteNonExistentDirectory()
    {
        $origin = '/THIS/DIRECTORY/DOES/NOT/EXIST/';
        $this->setExpectedException('Sonrisa\Component\FileSystem\Exceptions\FileSystemException');

        Folder::delete($origin);
    }

    public function testCopyExistingOriginDirectory()
    {
        mkdir('copy-test');
        $result = Folder::copy('src','copy-test');
        $result2 = scandir('src');
        $result3 = scandir('copy-test');

        $this->assertTrue($result);
        $this->assertEquals($result2,$result3);

        Folder::delete('copy-test');
    }

    public function testCopyNonExistentOriginDirectory()
    {
        $origin = '/THIS/DIRECTORY/DOES/NOT/EXIST/';
        $destination = '../';

        $this->setExpectedException('Sonrisa\Component\FileSystem\Exceptions\FileSystemException');
        Folder::copy($origin,$destination);
    }

    public function testCopyExistingDirectoryToTheSameExistingDirectory()
    {
        $origin = '.';
        $destination = '.';

        $this->setExpectedException('Sonrisa\Component\FileSystem\Exceptions\FileSystemException');
        Folder::copy($origin,$destination);
    }

    public function testCopyNonExistentDestinationDirectory()
    {
        $origin = '.';
        $destination = '/THIS/DIRECTORY/DOES/NOT/EXIST/';

        $this->setExpectedException('Sonrisa\Component\FileSystem\Exceptions\FileSystemException');
        Folder::copy($origin,$destination);
    }

    public function testMoveExistingOriginDirectory()
    {
        $result = Folder::create($this->foldername.DIRECTORY_SEPARATOR.'hello');
        $this->assertTrue($result);

        file_put_contents($this->foldername.DIRECTORY_SEPARATOR.'hello'.DIRECTORY_SEPARATOR.'test.txt',rand(100,500));

        $origin = $this->foldername;
        $destination = '../';

        $result = Folder::move($origin,$destination);
        $this->assertTrue($result);

        if($result)
        {
            Folder::delete('../hello');
        }
    }

    public function testMoveNonExistentOriginDirectory()
    {
        $origin = '/THIS/DIRECTORY/DOES/NOT/EXIST/';
        $destination = '../';

        $this->setExpectedException('Sonrisa\Component\FileSystem\Exceptions\FileSystemException');
        Folder::move($origin,$destination);
    }

    public function testMoveExistingDirectoryToTheSameExistingDirectory()
    {
        $origin = '.';
        $destination = '.';

        $this->setExpectedException('Sonrisa\Component\FileSystem\Exceptions\FileSystemException');
        Folder::move($origin,$destination);
    }

    public function testMoveNonExistentDestinationDirectory()
    {
        $origin = '.';
        $destination = '/THIS/DIRECTORY/DOES/NOT/EXIST/';

        $this->setExpectedException('Sonrisa\Component\FileSystem\Exceptions\FileSystemException');
        Folder::move($origin,$destination);
    }

    public function testFolderExistsExistingDirectory()
    {
        $folder = $this->foldername;
        $this->assertTrue(Folder::exists($folder));
    }

    public function testFolderExistsNonExistentDirectory()
    {
        $folder = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->foldername;
        $this->assertFalse(Folder::exists($folder));
    }

    public function testFolderIsWritableExistingDirectory()
    {
        $folder = $this->foldername;
        $this->assertTrue(Folder::isWritable($folder));
    }

    public function testFolderIsWritableNonExistentDirectory()
    {
        $this->setExpectedException('Sonrisa\Component\FileSystem\Exceptions\FileSystemException');
        $folder = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->foldername;
        Folder::isWritable($folder);
    }

    public function testFolderIsReadableExistingDirectory()
    {
        $folder = $this->foldername;
        $this->assertTrue(Folder::isReadable($folder));
    }

    public function testFolderIsReadableNonExistentDirectory()
    {
        $this->setExpectedException('Sonrisa\Component\FileSystem\Exceptions\FileSystemException');
        $folder = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->foldername;
        Folder::isReadable($folder);
    }

    public function testZipExistingDirectory()
    {
        $file = './src/';
        $result = Folder::zip($file,'test.zip',true);

        $this->assertTrue( $result );
        if($result)
        {
            unlink('test.zip');
        }
    }

    public function testZipNonExistentDirectory()
    {
        $this->setExpectedException('Sonrisa\Component\FileSystem\Exceptions\ZipException');
        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/';
        Folder::zip($file,'test.zip',true);
    }


    public function testUnzipExistingDirectory()
    {
        $file = './src/';
        $result = Folder::zip($file,'test.zip',true);

        if(!file_exists('tmp')){
            mkdir('tmp',0777);
        }

        $result2 =Folder::unzip('test.zip','./tmp',true);
        $this->assertTrue( $result2 );

        $this->assertTrue( $result );
        if($result)
        {
            unlink('test.zip');
            Folder::delete('tmp');
        }

    }

    public function testUnzipToNonExistentDirectory()
    {
        $this->setExpectedException('Sonrisa\Component\FileSystem\Exceptions\ZipException');
        $dir = '/THIS/DIRECTORY/DOES/NOT/EXIST/';

        Folder::zip('./src/','test.zip',true);
        Folder::unzip('test.zip',$dir,true);
    }


    public function testChmodNonExistentDirectory()
    {
        $this->setExpectedException('Sonrisa\Component\FileSystem\Exceptions\FileSystemException');

        $folder = '/THIS/DIRECTORY/DOES/NOT/EXIST/';
        Folder::chmod($folder, '0755');
    }

    public function testChmodExistingDirectory()
    {
        $folder = $this->foldername;
        $this->assertTrue(Folder::chmod($folder, '0777'));
    }

    public function testChangeModificationDateExistingDirectory()
    {
        $folder = $this->foldername;
        $original = Folder::getModificationDate($folder);

        $time = time()-3600;
        Folder::touch($folder,$time);
        $result = Folder::getModificationDate($folder);

        $this->assertEquals($original-3600,$result);
    }

    public function testChangeModificationDateNonExistentDirectory()
    {
        $this->setExpectedException('Sonrisa\Component\FileSystem\Exceptions\FileSystemException');
        $folder = '/THIS/DIRECTORY/DOES/NOT/EXIST/';
        Folder::touch($folder,time());
    }

    public function testRenameInvalidNewFilename()
    {
        $folder = $this->foldername;

        $this->setExpectedException('Sonrisa\Component\FileSystem\Exceptions\FileSystemException');
        Folder::rename($folder,'ok/a');
    }

    public function testRenameNonExistentDirectory()
    {
        $this->setExpectedException('Sonrisa\Component\FileSystem\Exceptions\FileSystemException');
        $folder = '/THIS/DIRECTORY/DOES/NOT/EXIST/';
        Folder::rename($folder,'newName');
    }

    public function testRenameExistingDirectorySameName()
    {
        $this->setExpectedException('Sonrisa\Component\FileSystem\Exceptions\FileSystemException');
        $folder = $this->foldername;
        Folder::rename($folder,$folder);
    }

    public function testRenameValidNewName()
    {
        $folder = $this->foldername;
        $result = Folder::rename($folder,'ok');

        $this->assertTrue($result);
        if($result)
        {
            Folder::delete('ok');
        }
    }


    public function testisHiddenFolderTrue()
    {
        $folder = dirname(__FILE__).'/.hidden_folder';
        mkdir($folder);

        $result = Folder::isHidden($folder);
        $this->assertTrue($result);

         $this->foldername = $folder;

    }        

    public function testisHiddenFolderFalse()
    {
        $folder = $this->foldername;

        $result = Folder::isHidden($folder);
        $this->assertFalse($result);
    } 


    public function testgetFolderSize()
    {
        $filename = dirname(__FILE__);
      
        $result = Folder::size($filename);
        $this->assertNotEquals('0',$result);
    }

    public function testgetHumanReadableFolderSize()
    {
        $newDir = dirname(__FILE__).'/new';
        mkdir($newDir,0777);
      
        $result = Folder::size($newDir,true,2);
        $this->assertEquals('0 B',$result);
        rmdir($newDir);
       
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
