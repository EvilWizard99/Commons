<?php 

namespace EWC\Commons\Tests\Utilities\DataType;

use PHPUnit_Framework_TestCase;
use EWC\Commons\Utilities\DataType\Convertor;

/**
 * Corresponding Test Class for \EWC\Commons\Utilities\DataType\Convertor
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 */
class ConvertorTest extends PHPUnit_Framework_TestCase {

	/**
	 * Provides valid Boolean TRUE substitues.
	 */
	public function validBooleanTrueProvider() {
		return [
			"string TRUE"	=> ["TRUE"],
			"string ON"		=> ["ON"],
			"string YES"	=> ["YES"],
			"string 1"		=> ["1"],
			"integer 1"		=> [1]
		];
	}

	/**
	 * Provides valid Boolean FALSE substitues.
	 */
	public function validBooleanFalseProvider() {
		return [
			"string TRUE"	=> ["FALSE"],
			"string ON"		=> ["OFF"],
			"string YES"	=> ["NO"],
			"string 1"		=> ["0"],
			"integer 1"		=> [0]
		];
	}
	
	/**
	 * Just check if the Image has no syntax error.
	 */
	public function testIsThereAnySyntaxError() {
		$var = new Convertor;
		$this->assertTrue(is_object($var));
		unset($var);
	}
	
	/**
	 * Make sure variables are cast to their specific type.
	 */
	public function testCastToSpecificTypeReturnsSpecificType() {		
		$this->assertTrue(is_int(Convertor::cast(1234)), "integer [1234] is not Integer");
		$this->assertTrue(is_int(Convertor::cast("1234")), "string [1234] is not Integer");
		$this->assertTrue(is_float(Convertor::cast(12.34)), "float [12.34] is not Float");
		$this->assertTrue(is_float(Convertor::cast("12.34")), "string [12.34] is not float");
		$this->assertGreaterThan(0, Convertor::cast(1234), "integer [1234] is not greater than 0");
		$this->assertGreaterThan(0, Convertor::cast(12.34), "integer [12.34] is not greater than 0");		
		$this->assertGreaterThan(0, Convertor::cast("1234"), "string [1234] is not greater than 0");
		$this->assertGreaterThan(0, Convertor::cast("12.34"), "string [12.34] is not greater than 0");
	}
	
	/**
	 * Just check that Boolean TRUE substitues cast correctly.
	 * 
	 * @dataProvider validBooleanTrueProvider
	 */
	public function testCastToBooleanTrue($data) {
		$this->assertTrue(Convertor::cast($data), "[{$data}] is not Boolean TRUE");
	}
	
	/**
	 * Just check that Boolean FALSE substitues cast correctly.
	 * 
	 * @dataProvider validBooleanFalseProvider
	 */
	public function testCastToBooleanFalse($data) {
		$this->assertFalse(Convertor::cast($data), "[{$data}] is not Boolean FALSE");
	}
  
}