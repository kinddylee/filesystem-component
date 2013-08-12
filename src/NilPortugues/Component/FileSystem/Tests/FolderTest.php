<?php
namespace NilPortugues\Component\FileSystem\Test;

class FolderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \NilPortugues\Component\FileSystem\Folder
     */
    protected $folder;

    public function setUp()
    {
        $this->folder = new \NilPortugues\Component\FileSystem\Folder();
    }

    public function testPlaceholder()
    {

    }

    public function tearDown()
    {
        $this->folder = NULL;
    }
}
