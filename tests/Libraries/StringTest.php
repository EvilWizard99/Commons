<?php 

namespace EWC\Commons\Tests\Libraries;

use PHPUnit_Framework_TestCase;
use EWC\Commons\Libraries\String;

/**
 * Corresponding Test Class for \EWC\Commons\Libraries\String
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 */
class StringTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * Just check if the String library has no syntax error 
	 */
	public function testIsThereAnySyntaxError() {
		$var = new String;
		$this->assertTrue(is_object($var));
		unset($var);
	}
	
	/**
	 * Provides invalid string length limits.
	 */
	public function validStringLengthProvider() {
		return [
			"length is integer"			=> [11],
			"length is hex"				=> [0xf1],
			"length is octal"			=> [012]
		];
	}
	
	/**
	 * Provides invalid string length limits.
	 */
	public function invalidStringLengthProvider() {
		return [
			"length is string"			=> ["bla"],
			"length is negative number"	=> [-11],
			"length is zero"			=> [0],
			"length is NULL"			=> [NULL]
		];
	}
  
	/**
	 * Make sure it generates only a numeric code.
	 * 
	 * @dataProvider validStringLengthProvider
	 */
	public function testGeneratesNumericCodeReturnsOnlyNumbers($data) {
		$this->assertTrue(is_numeric(String::generateNumberCode($data)));
	}
  
	/**
	 * Make sure it throws invalid length paramater argument exception.
	 * 
	 * @dataProvider invalidStringLengthProvider
	 * @expectedException \InvalidArgumentException
	 */
	public function testGenerateNumericCodeThrowsInvalidArgumentException($data) {
		$this->assertTrue(is_numeric(String::generateNumberCode($data)));
	}
  
}