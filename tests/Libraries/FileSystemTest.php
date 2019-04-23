<?php 

namespace EWC\Commons\Tests\Libraries;

use PHPUnit_Framework_TestCase;
use EWC\Commons\Libraries\FileSystem;

/**
 * Corresponding Test Class for \EWC\Commons\Libraries\FileSystem
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 */
class FileSystemTest extends PHPUnit_Framework_TestCase {
	
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
		$this->assertTrue(FileSystem::exists(__FILE__));
		$this->assertTrue(FileSystem::exists(dirname(__FILE__)));
		$this->assertFalse(FileSystem::exists("__FILE__"));
	}
  
	/**
	 * Make sure the file exists method detects files.
	 */
	public function testFileExists() {
		$this->assertTrue(FileSystem::fileExists(__FILE__));
		$this->assertFalse(FileSystem::fileExists("__FILE__"));
	}
  
	/**
	 * Make sure the folder exists method detects files.
	 */
	public function testFolderExists() {
		$this->assertTrue(FileSystem::folderExists(dirname(__FILE__)));
		$this->assertFalse(FileSystem::folderExists("__FILE__"));
	}
  
	/**
	 * Make sure the folder exists method detects files.
	 */
	public function testFileAgeReturnsInterger() {
		$this->assertGreaterThan(0, FileSystem::fileAge(__FILE__), "File Age not right");
	}
  
	/**
	 * Make sure the folder exists method detects files.
	 * 
	 * @expectedException \EWC\Commons\Exceptions\FileSystemException
	 */
	public function testFileAgeThrowsFileSystemExceptionWithFileNotFound() {
		$this->markTestIncomplete("check message & throw code");		
		$this->assertFalse(FileSystem::fileAge("__FILE__"));
		// @todo, check message & throw code
	}
	
	/**
	 * Make sure make path creates the folder structure.
	 */
	public function testMakePathMakesAFileSystemPath() {
		$this->markTestIncomplete("Write the test case");		
		$var = FileSystem::getInstance();
		
		
		unset($var);
	}
	
	/**
	 * Make sure make dir failed throws expected exception.
	 * 
	 * @expectedException \EWC\Commons\Exceptions\FileSystemException
	 * @expectedExceptionMessageRegExp /Failed to make directory \[.+\]\./
	 * @expectedExceptionCode \EWC\Commons\Exceptions\FileSystemException::MAKE_DIR_FAILED
	 */
	public function testMakePathThrowsFileSystemExceptionWithMakeDirFailed() {
		$this->markTestIncomplete("Write the test case & check message & throw code");		
		$var = FileSystem::getInstance();
		// @todo, check message & throw code
		unset($var);
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
	 * Make sure overwriting file contents fails.
	 * 
	 * @expectedException \EWC\Commons\Exceptions\FileSystemException
	 * @expectedExceptionMessageRegExp /Unable to overwrite file \[.+\]\./
	 * @expectedExceptionCode \EWC\Commons\Exceptions\FileSystemException::OVERWRITE_FILE_DISALLOWED
	 */
	public function testOverwriteFileContentsThrowsFileSystemExceptionWithOverwriteFileContentsDisallowed() {
		FileSystem::getInstance()->writeFile(__FILE__, "this shouldn't replace the contents of this test file.");
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