<?php 

namespace EWC\Commons\Tests\Utilities;

use PHPUnit_Framework_TestCase;
use EWC\Commons\Utilities\ErrorLogger;

/**
 * Corresponding Test Class for \EWC\Commons\Utilities\ErrorLogger
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 */
class ErrorLoggerTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * Just check if the ErrorLogger has no syntax error.
	 * 
	 * @todo	Write the real tests
	 */
	public function testIsThereAnySyntaxError() {
		$this->assertTrue(is_object(ErrorLogger::getInstance()));
	}
  
}