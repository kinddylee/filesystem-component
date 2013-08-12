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

    public function testGetModificationDateExistingFile()
    {
        $file = $this->filename;
        $result = $this->file->getModificationDate($file);

        $this->assertNotEmpty($result);
    }

    public function testGetModificationDateNonExistentFile()
    {
        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        $result = $this->file->getModificationDate($file);

        $this->assertFalse($result);
    }

    public function testChangeModificationDateExistingFile()
    {
        $file = $this->filename;
        $original = $this->file->getModificationDate($file);

        $time = time()-3600;
        $this->file->touch($file,$time);
        $result = $this->file->getModificationDate($file);

        $this->assertEquals($original-3600,$result);
    }

    public function testChangeModificationDateNonExistentFile()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileException');
        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        $this->file->touch($file,time());
    }

    public function testFileExistsExistingFile()
    {
        $file = $this->filename;
        $this->assertTrue($this->file->exists($file));
    }

    public function testFileExistsNonExistentFile()
    {
        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        $this->assertFalse($this->file->exists($file));
    }

    public function testFileIsReadableExistingFile()
    {
        $file = $this->filename;
        $this->assertTrue($this->file->isWritable($file));
    }

    public function testFileIsReadableNonExistentFile()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileException');
        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        $this->file->isWritable($file);
    }

    public function testFileReadExistingFile()
    {
        $file = $this->filename;
        $this->assertNotEmpty($this->file->read($file));
    }

    public function testFileReadNonExistentFile()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileException');
        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        $this->file->read($file);
    }

    public function testFileWriteFileIsWritable()
    {
        $file = $this->filename;
        $data = 'Lorem ipsum blah blah blah';

        $this->assertNotEquals($this->file->write($file, $data, '0755'),false);
    }

    public function testFileWriteFileNotWritable()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileException');
        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        $data = 'Lorem ipsum blah blah blah';

        $this->file->write($file, $data, '0755');
    }

    public function testFileAppendExistingFile()
    {
        $file = $this->filename;
        $this->assertNotEquals($this->file->append($file, 'new data'),false);
    }

    public function testFileAppendNonExistentFile()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileException');

        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        $this->file->append($file, 'new data');
    }

    public function testFileChmodNonExistentFile()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileException');

        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        $this->file->chmod($file, '0755');
    }

    public function testFileChmodExistingFile()
    {
        $file = $this->filename;
        $this->assertTrue($this->file->chmod($file, '0755'));
    }

    public function testFileDeleteNonExistentFile()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileException');

        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        $this->file->delete($file);
    }

    public function testFileDeleteExistingFile()
    {
        file_put_contents('temp.txt','');
        $this->assertTrue($this->file->delete( 'temp.txt' ));
    }

    public function testFileRenameInvalidNewFilename()
    {
        $file = $this->filename;
        $overwrite=false;

        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileException');
        $this->file->rename($file,'ok/a.txt',$overwrite);
    }

    public function testFileRenameNonExistentFile()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileException');

        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        $this->file->rename($file,'newName.txt',false);
    }

    public function testFileRenameExistingFileNoOverwrite()
    {
        $this->setExpectedException('NilPortugues\Component\FileSystem\Exceptions\FileException');
        $file = '/THIS/DIRECTORY/DOES/NOT/EXIST/'.$this->filename;
        $this->file->rename($file,'newName.txt',false);
    }

    public function testFileRenameExistingFileWithOverwrite()
    {
        file_put_contents('./temp.txt','');
        file_put_contents('./temp2.txt','');
        $this->file->rename('./temp.txt','temp2.txt',true);

        $this->assertFalse($this->file->exists('./temp.txt'));
        $this->assertTrue($this->file->exists('./temp2.txt'));

        $this->file->delete('./temp2.txt');
        $this->assertFalse($this->file->exists('./temp2.txt'));
    }

    public function testFileRenameExistingFileSameName()
    {
        file_put_contents('./temp.txt','');
        $this->file->rename('./temp.txt','temp.txt',true);

        $this->assertTrue($this->file->exists('./temp.txt'));
        $this->file->delete('./temp.txt');
        $this->assertFalse($this->file->exists('./temp.txt'));
    }

    public function testGZipExistingFile()
    {

    }

    public function testGZipNonExistentFile()
    {

    }

    public function testGunzipExistingFile()
    {

    }

    public function testGunzipNonExistentFile()
    {

    }


    public function testZipExistingFile()
    {

    }

    public function testZipNonExistentFile()
    {

    }

    public function testUnzipExistingFile()
    {

    }

    public function testUnzipNonExistentFile()
    {

    }

    public function tearDown()
    {
        unlink($this->filename);
        $this->file = NULL;
    }
}
