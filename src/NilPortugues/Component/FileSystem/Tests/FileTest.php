<?php
/*
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Component\FileSystem\Test;
use \NilPortugues\Component\FileSystem\File as File;

class FileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \NilPortugues\Component\FileSystem\File
     */
    protected $file;
    protected $filename = 'test.txt';
    protected $extension = 'txt';
    protected $fileData;

    public function setUp()
    {
        $this->fileData = rand(1,500);
        file_put_contents($this->filename,rand(1,$this->fileData));
        $this->filename = realpath($this->filename);
    }

    public function testMoveExistingFile()
    {
        $file = $this->filename;
        $result1 = File::move($file,"../",true);
        $result2 = File::exists('../test.txt');

        $this->assertTrue($result1);
        $this->assertTrue($result2);
    }

    public function testMoveNonExistentFile()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileSystemException');
        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        File::move($file,"../",true);
    }

    public function testCopyExistingFile()
    {
        $file = $this->filename;
        $result1 = File::copy($file,$file.'.copy.txt',true);
        $result2 = File::exists($file.'.copy.txt');

        $this->assertTrue($result1);
        $this->assertTrue($result2);

        if($result2)
        {
            File::delete($file.'.copy.txt');
        }
    }

    public function testCopyNonExistentFile()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileSystemException');
        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        File::copy($file,"../");
    }


    public function testGetExtensionExistingFile()
    {
        $file = $this->filename;
        $path = dirname("");

        $result = File::getExtension($path.DIRECTORY_SEPARATOR.$file);
        $this->assertEquals($this->extension,$result);
    }

    public function testGetExtensionNonExistentFile()
    {
        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        $result = File::getExtension($file);
        $this->assertFalse($result);
    }

    public function testGetModificationDateExistingFile()
    {
        $file = $this->filename;
        $result = File::getModificationDate($file);

        $this->assertNotEmpty($result);
    }

    public function testGetModificationDateNonExistentFile()
    {
        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        $result = File::getModificationDate($file);

        $this->assertFalse($result);
    }

    public function testChangeModificationDateExistingFile()
    {
        $file = $this->filename;
        $original = File::getModificationDate($file);

        $time = time()-3600;
        File::touch($file,$time);
        $result = File::getModificationDate($file);

        $this->assertEquals($original-3600,$result);
    }

    public function testChangeModificationDateNonExistentFile()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileSystemException');
        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        File::touch($file,time());
    }

    public function testFileExistsExistingFile()
    {
        $file = $this->filename;
        $this->assertTrue(File::exists($file));
    }

    public function testFileExistsNonExistentFile()
    {
        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        $this->assertFalse(File::exists($file));
    }

    public function testFileIsReadableExistingFile()
    {
        $file = $this->filename;
        $this->assertTrue(File::isWritable($file));
    }

    public function testFileIsReadableNonExistentFile()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileSystemException');
        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        File::isWritable($file);
    }

    public function testFileReadExistingFile()
    {
        $file = $this->filename;
        $this->assertNotEmpty(File::read($file));
    }

    public function testFileReadNonExistentFile()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileSystemException');
        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        File::read($file);
    }

    public function testFileWriteFileIsWritable()
    {
        $file = $this->filename;
        $data = 'Lorem ipsum blah blah blah';

        $this->assertNotEquals(File::write($file, $data, '0755'),false);
    }

    public function testFileWriteFileNotWritable()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileSystemException');
        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        $data = 'Lorem ipsum blah blah blah';

        File::write($file, $data, '0755');
    }

    public function testFileAppendExistingFile()
    {
        $file = $this->filename;
        $this->assertNotEquals(File::append($file, 'new data'),false);
    }

    public function testFileAppendNonExistentFile()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileSystemException');

        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        File::append($file, 'new data');
    }

    public function testFileChmodNonExistentFile()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileSystemException');

        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        File::chmod($file, '0755');
    }

    public function testFileChmodExistingFile()
    {
        $file = $this->filename;
        $this->assertTrue(File::chmod($file, '0755'));
    }

    public function testFileDeleteNonExistentFile()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileSystemException');

        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        File::delete($file);
    }

    public function testFileDeleteExistingFile()
    {
        file_put_contents('temp.txt','');
        $this->assertTrue(File::delete( 'temp.txt' ));
    }

    public function testFileRenameValidNewFilename()
    {
        $file = $this->filename;
        $overwrite=false;

        $result = File::rename($file,'ok.txt',$overwrite);

        $this->assertTrue($result);
        if($result)
        {
            File::delete('ok.txt');
        }
    }

    public function testFileRenameInvalidNewFilename()
    {
        $file = $this->filename;
        $overwrite=false;

        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileSystemException');
        File::rename($file,'ok/a.txt',$overwrite);
    }

    public function testFileRenameNonExistentFile()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileSystemException');

        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        File::rename($file,'newName.txt',false);
    }

    public function testFileRenameExistingFileNoOverwrite()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileSystemException');
        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        File::rename($file,'newName.txt',false);
    }

    public function testFileRenameExistingFileWithOverwrite()
    {
        file_put_contents('./temp.txt','');
        file_put_contents('./temp2.txt','');
        File::rename('./temp.txt','temp2.txt',true);

        $this->assertFalse(File::exists('./temp.txt'));
        $this->assertTrue(File::exists('./temp2.txt'));

        File::delete('./temp2.txt');
        $this->assertFalse(File::exists('./temp2.txt'));
    }

    public function testFileRenameExistingFileSameName()
    {
        file_put_contents('./temp.txt','');
        File::rename('./temp.txt','temp.txt',true);

        $this->assertTrue(File::exists('./temp.txt'));
        File::delete('./temp.txt');
        $this->assertFalse(File::exists('./temp.txt'));
    }

    public function testGZipExistingFile()
    {
        $file = $this->filename;

        File::gzip($file,$file.'.gz',true);

        $result = File::exists($file.'.gz');
        $this->assertTrue( $result );

        if($result)
        {
            File::delete($file.'.gz');
        }
    }

    public function testGZipNonExistentFile()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileSystemException');
        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        File::gzip($file,$file.'.gz',true);
    }

    public function testGunzipExistingFile()
    {
        $file = $this->filename;
        File::gzip($file,$file.'.gz',true);
        File::gunzip($file.'.gz',$file.'.extracted.txt',true);

        $result = File::gunzip($file.'.gz',$file.'.extracted.txt',true);
        $this->assertTrue( $result );
        if($result)
        {
            File::delete($file.'.gz');
            File::delete($file.'.extracted.txt');
        }
    }

    public function testGunzipNonExistentFile()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileSystemException');
        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;

        File::gunzip($file.'.gz',$file.'.extracted.txt',true);
    }

    public function testZipExistingFile()
    {
        file_put_contents('test.txt','');
        $file = 'test.txt';
        $result = File::zip($file,$file.'.zip',true);

        $this->assertTrue( $result );
        if($result)
        {
           File::delete($file.'.zip');
        }
    }

    public function testZipNonExistentFile()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\ZipException');
        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        File::zip($file,$file.'.zip',true);
    }

    public function testUnzipExistingFile()
    {
        file_put_contents('test2.txt','');
        $file = 'test2.txt';
        $result1 = File::zip($file,'test2.zip',true);

        $result2 =File::unzip($file,'.',true);
        $this->assertTrue( $result2 );

        if( $result1 )
        {
            File::delete('test2.txt');
            File::delete('test2.zip');
        }
    }

    public function testUnzipNonExistentFile()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\ZipException');
        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename.'.zip';
        File::unzip($file,'.',true);
    }


    public function testIsLinkExistingFile()
    {
        $this->assertFalse(File::isLink($this->filename));
    }

    public function testIsLinkNonExistentFile()
    {
        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;

        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileSystemException');
        File::isLink($file);
    }

    public function testSoftLinkNonExistentFile()
    {
        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;

        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileSystemException');
        File::softSymLink($file,'alias');
    }

    public function testSoftLinkExistentFile()
    {
        $file = $this->filename;

        $result = File::softSymLink($file,'alias');

        $this->assertTrue($result);
        $this->assertTrue(File::isLink('alias'));

        if($result)
        {
            File::delete('alias');
        }
    }

    public function testHardLinkExistentFile()
    {
        File::write('realFile.txt','testing...');
        $file = 'realFile.txt';

        $result = File::hardSymLink($file,'aliasHard');

        $this->assertTrue($result);
        $this->assertTrue(File::isLink('aliasHard'));

        if($result)
        {
            File::delete('aliasHard');
            File::delete('realFile.txt');
        }
    }

    public function testHardLinkNonExistentFile()
    {
        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;

        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileSystemException');
        File::hardSymLink($file,'alias');
    }

    public function testGetFileGroupOwner()
    {
        $file = $this->filename;

        $owner = File::groupOwner($file);
        $this->assertNotEquals(false,$owner);
        $this->assertNotEmpty($owner);

    }

    public function testGetFileUserOwner()
    {
        $file = $this->filename;
        $owner = File::userOwner($file);

        $this->assertNotEquals(false,$owner);
        $this->assertNotEmpty($owner);
    }

    public function testGetFileMimeType()
    {
        file_put_contents(dirname(__FILE__).'/image.png',file_get_contents('https://www.google.cat/images/srpr/logo11w.png'));
      
        $mime = File::mimeType(dirname(__FILE__).'/image.png');
        $this->assertEquals('image/png',$mime);

        unlink(dirname(__FILE__).'/image.png');
    }

  

    public function testisHiddenFileTrue()
    {
        $filename = dirname(__FILE__).'/.hidden_file';
        file_put_contents($filename,'');
      
        $result = File::isHidden($filename);
        $this->assertTrue($result);

        unlink($filename);
    }        

    public function testisHiddenFileFalse()
    {
        $filename = dirname(__FILE__).'/nothidden';
        file_put_contents($filename,'');
      
        $result = File::isHidden($filename);
        $this->assertFalse($result);

        unlink($filename);
    }  

    public function tearDown()
    {
        if(File::exists($this->filename))
        {
            File::delete($this->filename);
        }

        if(File::exists('test.zip'))
        {
            File::delete('test.zip');
        }

        $this->file = NULL;
    }
}
