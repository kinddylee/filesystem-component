<?php
namespace NilPortugues\Component\FileSystem\Test;

class FileSystemTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \NilPortugues\Component\FileSystem\FileSystem
     */
    protected $fs;

    public function setUp()
    {
        $this->fs = new \NilPortugues\Component\FileSystem\FileSystem();
    }

    public function testPlaceholder()
    {

    }

    public function tearDown()
    {
        $this->fs = NULL;
    }
}
