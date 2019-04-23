<?php 

namespace EWC\Commons\Tests\Errors;

use PHPUnit_Framework_TestCase;
use EWC\Commons\Errors\ContextError;
use stdClass;
use Exception;

/**
 * Corresponding Test Class for \EWC\Commons\Errors\ContextError
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 */
class ContextErrorTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * @var	Exception Provides valid exception source to create ContextError from.
	 */
	protected $ex = NULL;
	
	/**
	 * Setup the test fixture.
	 */
	public function setUp() {
		parent::setUp();
		$this->ex = new Exception("test error exception");
	}
	
	/**
	 * Clean up the test fixture.
	 */
	public function tearDown() {
		parent::tearDown();
		$this->ex = NULL;
	}

	/**
	 * Provides valid source to create ContextError from.
	 */
	public function validConstructSourceProvider() {
		return [
			"valid exception"		=> [$this->ex],
			"valid no exception"	=> [NULL]
		];
	}
	
	/**
	 * Provides invalid source to create ContextError from.
	 */
	public function invalidConstructSourceProvider() {
		return [
			"invalid string construct"	=> ["bla"],
			"invalid object construct"	=> [new stdClass],
			"invalid numeric construct"	=> [1],
			"invalid array construct"	=> [[$this->ex]],
			"invalid empty construct"	=> []
		];
	}
	
	/**
	 * Just check if the ContextError has no syntax error.
	 */
	public function testIsThereAnySyntaxError() {
		$var = new ContextError;
		$this->assertTrue(is_object($var));
		unset($var);
	}
  
	/**
	 * Just check if the ContextError returns the exception.
	 * 
	 * @dataProvider validConstructSourceProvider
	 */
	public function testGetExceptionReturnsAnException($data) {
		$var = new ContextError($data);
		$this->assertInstanceOf("Exception", $var->getException());
		unset($var);
	}
  
	/**
	 * Just check if the ContextError throws invalid construct.
	 * 
	 * @dataProvider invalidConstructSourceProvider
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testThrowsInvalidConstructSource($data) {
		$var = new ContextError($data);
		$this->assertInstanceOf("Exception", $var->getException());
		unset($var);
	}
  
}