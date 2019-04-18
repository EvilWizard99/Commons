<?php 

namespace EWC\Commons\Tests\Utilities;

use PHPUnit_Framework_TestCase;
use EWC\Commons\Utilities\Reflector;

/**
 * Corresponding Test Class for \EWC\Commons\Utilities\Reflector
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 */
class ReflectorTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * Just check if the Reflector has no syntax error.
	 */
	public function testIsThereAnySyntaxError() {
		$var = new Reflector(static::class);
		$this->assertTrue(is_object($var));
		unset($var);
	}
  
}