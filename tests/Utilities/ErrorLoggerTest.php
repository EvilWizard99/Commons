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
	 */
	public function testIsThereAnySyntaxError() {
		$this->assertTrue(is_object(ErrorLogger::getInstance()));
	}
	
	/**
	 * Just check if the ErrorLogger logs to the system and returns.
	 */
	public function testLogsToSystem() {
		$var = ErrorLogger::getInstance();
		$this->assertInstanceOf(ErrorLogger::class, $var->log("error log message", ErrorLogger::TYPE_GENERAL, ErrorLogger::LOG_TO_SYSTEM), "Expected an instance of ErrorLogger");
		unset($var);
	}
  
}