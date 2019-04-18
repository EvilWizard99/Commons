<?php 

namespace EWC\Commons\Tests\Utilities;

use PHPUnit_Framework_TestCase;
use EWC\Commons\Utilities\ErrorLogData;
use stdClass;

/**
 * Corresponding Test Class for \EWC\Commons\Utilities\ErrorLogData
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 */
class ErrorLogDataTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * @var	stdClass Provides object to use.
	 */
	protected $o;
	
	/**
	 * Setup the test scenario.
	 */
	public function setUp() {
		parent::setUp();
		// create a standard class representation of an object
		$this->o = new stdClass;
		$this->o->test_property_int = 1;
		$this->o->test_property_string = "string";
		$this->o->test_property_array = [1, "string"];
	}
	
	/**
	 * Provides valid source to create ErrorLogData from.
	 */
	public function validConstructSourceProvider() {
		return [
			"valid string data"	=> [
					"name"			=> "string",
					"data string"	=> "three"
			],
			"valid array data"	=> [
					"name"			=> "array",
					"data array"	=> [1,2,"three"]
			],
			"valid object data"	=> [
					"name"			=> "object",
					"data object"	=> $this->o
			]
		];
	}
	
	/**
	 * Just check if the ErrorLogData has no syntax error.
	 * 
	 * @dataProvider validConstructSourceProvider
	 */
	public function testIsThereAnySyntaxError($name, $data) {
		$var = new ErrorLogData($name, $data);
		$this->assertTrue(is_object($var));
		unset($var);
	}
  
	/**
	 * Make sure the ErrorLogData can be converted to string.
	 * 
	 * @dataProvider validConstructSourceProvider
	 */
	public function testConvertsToString($name, $data) {
		$var = new ErrorLogData($name, $data);
		$this->assertInternalType("string", $var->__toString());
		unset($var);
	}
  
}