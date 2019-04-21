<?php 

namespace EWC\Commons\Tests\Exceptions;

use PHPUnit_Framework_TestCase;
use EWC\Commons\Exceptions\ReflectorException;

/**
 * Corresponding Test Class for \EWC\Commons\Exceptions\ReflectorException
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 */
class ReflectorExceptionTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * Just check if the ImmutableTraitException has no syntax error.
	 */
	public function testIsThereAnySyntaxError() {
		$var = new ReflectorException;
		$this->assertTrue(is_object($var));
		unset($var);
	}
  
}