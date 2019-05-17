<?php 

namespace EWC\Commons\Tests\Exceptions;

use PHPUnit_Framework_TestCase;
use EWC\Commons\Exceptions\ReflectorException;
use ReflectionException;

/**
 * Corresponding Test Class for \EWC\Commons\Exceptions\ReflectorException
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 */
class ReflectorExceptionTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * @var	ReflectionException Provides valid exception to rethrow in the named exception methods.
	 */
	protected $ref_ex = NULL;
	
	/**
	 * Setup the test fixture.
	 */
	public function setUp() {
		parent::setUp();
		$this->ref_ex = new ReflectionException("test reflection reflector exception", ReflectorException::REFLECTION_FAILED);
	}
	
	/**
	 * Just check if the ReflectorException has no syntax error.
	 */
	public function testIsThereAnySyntaxError() {
		$var = new ReflectorException;
		$this->assertTrue(is_object($var));
		unset($var);
	}
	
	/**
	 * Make sure reflection failed throws expected exception.
	 * 
	 * @expectedException \EWC\Commons\Exceptions\ReflectorException
	 * @expectedExceptionMessageRegExp /Unable to get reflection of object \[.+\]\./
	 * @expectedExceptionCode \EWC\Commons\Exceptions\ReflectorException::REFLECTION_FAILED
	 */
	public function testReflectionFailedThrowsExceptionWithReflectionFailed() {
		throw ReflectorException::withReflectionFailed("\\Namespaced\\Missing\\Classname", $this->ref_ex);
	}
	
	/**
	 * Make sure reflection instance failed throws expected exception.
	 * 
	 * @expectedException \EWC\Commons\Exceptions\ReflectorException
	 * @expectedExceptionMessageRegExp /Unable to get reflected instance of object \[.+\]\./
	 * @expectedExceptionCode \EWC\Commons\Exceptions\ReflectorException::REFLECTIED_INSTANCE_FAILED
	 */
	public function testReflectionInstanceFailedThrowsExceptionWithFailedToGetInstance() {
		throw ReflectorException::withFailedToGetInstance("\\Namespaced\\Instance\\Fails\\Classname", $this->ref_ex);
	}
	
	/**
	 * Make sure calling a method out of scope throws expected exception.
	 * 
	 * @expectedException \EWC\Commons\Exceptions\ReflectorException
	 * @expectedExceptionMessageRegExp /Calling method \[.+\] directly is not permitted\./
	 * @expectedExceptionCode \EWC\Commons\Exceptions\ReflectorException::ILLEGAL_METHOD_CALL
	 */
	public function testCallingIllegalMethodThrowsExceptionWithIllegalMethodCall() {
		throw ReflectorException::withIllegalMethodCall("out_of_scope_method");
	}
	
	/**
	 * Make sure reflected method argument count check failed throws expected exception.
	 * 
	 * @expectedException \EWC\Commons\Exceptions\ReflectorException
	 * @expectedExceptionMessageRegExp /.+::.+\(\) expects \d+ parameters, \d+ given\./
	 * @expectedExceptionCode \EWC\Commons\Exceptions\ReflectorException::ARGUMENT_COUNT_MISMATCH
	 */
	public function testArgumentCountMismatchThrowsExceptionWithArgumentCountMismatch() {
		throw ReflectorException::withArgumentCountMismatch("\\Namespaced\\Classname", "method_name", 2, 1);
	}
	
	/**
	 * Make sure reflected class object type mismatch throws expected exception.
	 * 
	 * @expectedException \EWC\Commons\Exceptions\ReflectorException
	 * @expectedExceptionMessageRegExp /\[.+\] does not match expected type \[.+\]\./
	 * @expectedExceptionCode \EWC\Commons\Exceptions\ReflectorException::REFLECTION_TYPE_MISMATCH
	 */
	public function testReflectionClassTypeFailedThrowsExceptionWithTypeMismatch() {
		throw ReflectorException::withTypeMismatch("\\Namespaced\\Classname", "\\Expected\\Namespaced\\Classname");
	}
  
}