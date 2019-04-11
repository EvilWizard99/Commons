<?php 

/**
 * Corresponding Test Class for \EWC\Commons\Libraries\FileSystem
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 */
class FileSystemTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * Just check if the Parser has no syntax error 
	 */
	public function testIsThereAnySyntaxError(){
		$var = \EWC\Commons\Libraries\FileSystem::getInstance();
		$this->assertTrue(is_object($var));
		unset($var);
	}
  
	/**
	 * Just check if the Parser has no syntax error 
	 */
	public function testExists(){
		$this->assertTrue(\EWC\Commons\Libraries\FileSystem::exists(__FILE__));
	}
  
}