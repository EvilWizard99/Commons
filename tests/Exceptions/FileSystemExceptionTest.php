<?php 

namespace EWC\Commons\Tests\Exceptions;

use PHPUnit_Framework_TestCase;
use EWC\Commons\Exceptions\FileSystemException;

/**
 * Corresponding Test Class for \EWC\Commons\Exceptions\FileSystemException
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 */
class FileSystemExceptionTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * Just check if the ImmutableTraitException has no syntax error.
	 */
	public function testIsThereAnySyntaxError() {
		$var = new FileSystemException;
		$this->assertTrue(is_object($var));
		unset($var);
	}
  
}