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
	 * Just check if the Image has no syntax error.
	 */
	public function testIsThereAnySyntaxError() {
		$var = new Convertor;
		$this->assertTrue(is_object($var));
		unset($var);
	}
  
}