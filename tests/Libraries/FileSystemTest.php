<?php 

namespace EWC\Commons\Tests\Libraries;

use PHPUnit_Framework_TestCase;
use EWC\Commons\Libraries\FileSystem;
use EWC\Commons\Exceptions\FileSystemException;

require_once dirname(__FILE__) . "/FileSystemStubTestFunctions.php";

/**
 * Corresponding Test Class for \EWC\Commons\Libraries\FileSystem
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 */
class FileSystemTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * @var	String Provides an invalid file name.
	 */
	protected $invalid_file = NULL;
	
	/**
	 * @var	String Provides an actual real file name.
	 */
	protected $actual_file = NULL;
	
	/**
	 * @var	String Provides an actual real folder name.
	 */
	protected $actual_folder = NULL;
	
	/**
	 * Setup the test scenario.
	 */
	public function setUp() {
		parent::setUp();
		$this->invalid_file = "__FILE__";
		$this->actual_file = __FILE__;
		$this->actual_folder = dirname(__FILE__);
	}


	/**
	 * Just check if the FileSystem has no syntax error.
	 */
	public function testIsThereAnySyntaxError() {
		$this->assertTrue(is_object(FileSystem::getInstance()));
	}
  
	/**
	 * Make sure the exists method detects correctly.
	 */
	public function testExists() {
		$this->assertTrue(FileSystem::exists($this->actual_file));
		$this->assertTrue(FileSystem::exists($this->actual_folder));
		$this->assertFalse(FileSystem::exists($this->invalid_file));
	}
  
	/**
	 * Make sure the file exists method detects files.
	 */
	public function testFileExists() {
		$this->assertTrue(FileSystem::fileExists($this->actual_file));
		$this->assertFalse(FileSystem::fileExists($this->invalid_file));
	}
  
	/**
	 * Make sure the folder exists method detects files.
	 */
	public function testFolderExists() {
		$this->assertTrue(FileSystem::folderExists(dirname($this->actual_file)));
		$this->assertFalse(FileSystem::folderExists($this->invalid_file));
	}
  
	/**
	 * Make sure the file age returns a valid integer age value.
	 */
	public function testFileAgeReturnsInterger() {
		$this->assertGreaterThan(0, FileSystem::fileAge($this->actual_file), "File Age not right");
	}
  
	/**
	 * Make sure the file age throws an exception on invalid file.
	 * 
	 * @expectedException \EWC\Commons\Exceptions\FileSystemException
	 * @expectedExceptionMessageRegExp /File \[.+\] not found\./
	 * @expectedExceptionCode \EWC\Commons\Exceptions\FileSystemException::FILE_NOT_FOUND
	 */
	public function testFileAgeThrowsFileSystemExceptionWithFileNotFound() {
		$this->assertFalse(FileSystem::fileAge($this->invalid_file));
	}
	
	/**
	 * Make sure make path creates the folder structure.
	 */
	public function testMakePathMakesAFileSystemPath() {
		$var = FileSystem::getInstance();
		try {
			// uses a stub to mock the standard mkdir() function
			$result = $var->makePath("test/path/to/create");
			$this->assertNull($result, "Make path returned something and shouldn't");
		} catch (FileSystemException $ex) {
			$this->fail($ex->getMessage());
		}
		unset($var);
	}
	
	/**
	 * Make sure make dir failed throws expected exception if the folder to create already exists.
	 * 
	 * @expectedException \EWC\Commons\Exceptions\FileSystemException
	 * @expectedExceptionMessageRegExp /Failed to make directory \[.+\]\./
	 * @expectedExceptionCode \EWC\Commons\Exceptions\FileSystemException::MAKE_DIR_FAILED
	 */
	public function testMakePathThrowsFileSystemExceptionWithMakeDirFailedIfFolderAlreadyExists() {
		$var = FileSystem::getInstance();
		try {
			// uses a stub to mock the standard mkdir() function
			$result = $var->makePath($this->actual_folder);
			$this->assertNull($result, "Make path returned something and shouldn't");
			$this->fail("{$this->actual_folder} should have thrown exception");
		} catch (FileSystemException $ex) {
			throw new FileSystemException($ex->getMessage(), $ex->getCode(), $ex);
		}
		unset($var);
	}
	
	/**
	 * Make sure overwriting file contents fails.
	 * 
	 * @expectedException \EWC\Commons\Exceptions\FileSystemException
	 * @expectedExceptionMessageRegExp /Unable to overwrite file \[.+\]\./
	 * @expectedExceptionCode \EWC\Commons\Exceptions\FileSystemException::OVERWRITE_FILE_DISALLOWED
	 */
	public function testOverwriteFileContentsThrowsFileSystemExceptionWithOverwriteFileContentsDisallowed() {
		FileSystem::getInstance()->writeFile($this->actual_file, "this shouldn't replace the contents of this test file.");
	}
	
	/**
	 * Make sure make path creates the folder structure.
	 */
	public function testWriteFileActuallyWritesTheFileContents() {
		$this->markTestIncomplete("Write the test case");		
		$var = FileSystem::getInstance();
		
		
		unset($var);
	}
	
	/**
	 * Make sure make path creates the folder structure.
	 * 
	 * @expectedException \EWC\Commons\Exceptions\FileSystemException
	 */
	public function testWriteFileFailedThrowsFileSystemException() {
		$this->markTestIncomplete("Write the test case & check message & throw code");
		$var = FileSystem::getInstance();
		// @todo, check message & throw code
		unset($var);
	}
  
}