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
	 * Make sure the file exists method detects files.
	 */
	public function testFileExists() {
		$this->assertTrue(FileSystem::exists(__FILE__));
		$this->assertFalse(FileSystem::exists("__FILE__"));
	}
  
}