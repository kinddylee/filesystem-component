<?php
namespace NilPortugues\Component\FileSystem\Test;

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

        $this->file = new \NilPortugues\Component\FileSystem\File();
    }

    public function testGetExtensionExistingFile()
    {
        $file = $this->filename;
        $path = dirname("");

        $result = $this->file->getExtension($path.DIRECTORY_SEPARATOR.$file);
        $this->assertEquals($this->extension,$result);
    }

    public function testGetExtensionNonExistentFile()
    {
        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        $result = $this->file->getExtension($file);
        $this->assertFalse($result);
    }

    public function testGetFileModificationDateExistingFile()
    {
        $file = $this->filename;
        $result = $this->file->getFileModificationDate($file);

        $this->assertNotEmpty($result);
    }

    public function testGetFileModificationDateNonExistentFile()
    {
        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        $result = $this->file->getFileModificationDate($file);

        $this->assertFalse($result);
    }

    public function testChangeModificationDateExistingFile()
    {
        $file = $this->filename;
        $original = $this->file->getFileModificationDate($file);

        $time = time()-3600;
        $this->file->fileTouch($file,$time);
        $result = $this->file->getFileModificationDate($file);

        $this->assertEquals($original-3600,$result);
    }

    public function testChangeModificationDateNonExistentFile()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileException');
        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        $this->file->fileTouch($file,time());
    }

    public function testFileExistsExistingFile()
    {
        $file = $this->filename;
        $this->assertTrue($this->file->fileExists($file));
    }

    public function testFileExistsNonExistentFile()
    {
        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        $this->assertFalse($this->file->fileExists($file));
    }

    public function testFileIsReadableExistingFile()
    {
        $file = $this->filename;
        $this->assertTrue($this->file->fileIsWritable($file));
    }

    public function testFileIsReadableNonExistentFile()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileException');
        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        $this->file->fileIsWritable($file);
    }

    public function testFileGetContentsExistingFile()
    {
        $file = $this->filename;
        $this->assertNotEmpty($this->file->fileGetContents($file));
    }

    public function testFileGetContentsNonExistentFile()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileException');
        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        $this->file->fileGetContents($file);
    }

    public function testFilePutContentsFileIsWritable()
    {
        $file = $this->filename;
        $data = 'Lorem ipsum blah blah blah';

        $this->assertNotEquals($this->file->filePutContents($file, $data, '0755'),false);
    }

    public function testFilePutContentsFileNotWritable()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileException');
        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        $data = 'Lorem ipsum blah blah blah';

        $this->file->filePutContents($file, $data, '0755');
    }

    public function testFileAppendExistingFile()
    {
        $file = $this->filename;
        $this->assertNotEquals($this->file->fileAppend($file, 'new data'),false);
    }

    public function testFileAppendNonExistentFile()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileException');

        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        $this->file->fileAppend($file, 'new data');
    }

    public function testFileChmodNonExistentFile()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileException');

        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        $this->file->fileChmod($file, '0755');
    }

    public function testFileChmodExistingFile()
    {
        $file = $this->filename;
        $this->assertTrue($this->file->fileChmod($file, '0755'));
    }

    public function testFileDeleteNonExistentFile()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileException');

        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        $this->file->fileDelete($file);
    }

    public function testFileDeleteExistingFile()
    {
        file_put_contents('temp.txt','');
        $this->assertTrue($this->file->fileDelete( 'temp.txt' ));
    }

    public function testFileRenameInvalidNewFilename()
    {
        $file = $this->filename;
        $overwrite=false;

        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileException');
        $this->file->fileRename($file,'ok/a.txt',$overwrite);
    }

    public function testFileRenameNonExistentFile()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileException');

        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        $this->file->fileRename($file,'newName.txt',false);
    }

    public function testFileRenameExistingFileNoOverwrite()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileException');
        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        $this->file->fileRename($file,'newName.txt',false);
    }

    public function testFileRenameExistingFileWithOverwrite()
    {
        file_put_contents('./temp.txt','');
        file_put_contents('./temp2.txt','');
        $this->file->fileRename('./temp.txt','temp2.txt',true);

        $this->assertFalse($this->file->fileExists('./temp.txt'));
        $this->assertTrue($this->file->fileExists('./temp2.txt'));

        $this->file->fileDelete('./temp2.txt');
        $this->assertFalse($this->file->fileExists('./temp2.txt'));
    }

    public function testFileRenameExistingFileSameName()
    {
        file_put_contents('./temp.txt','');
        $this->file->fileRename('./temp.txt','temp.txt',true);

        $this->assertTrue($this->file->fileExists('./temp.txt'));
        $this->file->fileDelete('./temp.txt');
        $this->assertFalse($this->file->fileExists('./temp.txt'));
    }

    public function tearDown()
    {
        unlink($this->filename);
        $this->file = NULL;
    }
}
