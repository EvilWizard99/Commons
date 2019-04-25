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
	 * Provides valid Integer values to cast.
	 */
	public function validIntegerProvider() {
		return [
			"string 1234"	=> ["1234"],
			"integer 1234"	=> [1234]
		];
	}

	/**
	 * Provides valid Integer values to cast.
	 */
	public function validFloatProvider() {
		return [
			"string 12.34"	=> ["12.34"],
			"float 12.34"	=> [12.34]
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
	 * Just check that integer numbers cast correctly.
	 * 
	 * @dataProvider validIntegerProvider
	 */
	public function testCastToInteger($data) {
		$this->assertTrue(is_int(Convertor::cast($data)), "[{$data}] is not Integer");
		$this->assertGreaterThan(0, Convertor::cast($data), "[{$data}] is not greater than 0");
	}
	
	/**
	 * Just check that floating point numbers cast correctly.
	 * 
	 * @dataProvider validFloatProvider
	 */
	public function testCastToFloat($data) {
		$this->assertTrue(is_float(Convertor::cast($data)), "[{$data}] is not Float");
		$this->assertGreaterThan(0, Convertor::cast($data), "[{$data}] is not greater than 0");
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