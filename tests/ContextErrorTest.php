<?php 

/**
 * Corresponding Test Class for \EWC\Commons\Errors\ContextError
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 */
class ContextErrorTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * Just check if the ContextError has no syntax error 
	 */
	public function testIsThereAnySyntaxError() {
		$var = new \EWC\Commons\Errors\ContextError(new Exception("test error exception"));
		$this->assertTrue(is_object($var));
		unset($var);
	}
  
	/**
	 * Just check if the ContextError returns the exception 
	 */
	public function testGetException() {
		$ex = new Exception("test error exception");
		$var = new \EWC\Commons\Errors\ContextError($ex);
		$this->assertTrue($var->getException() == $ex);
		unset($var, $ex);
	}
  
}