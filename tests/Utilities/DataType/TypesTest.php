<?php 

namespace EWC\Commons\Tests\Utilities\DataType;

use PHPUnit_Framework_TestCase;
use EWC\Commons\Utilities\DataType\Types;

/**
 * Corresponding Test Class for \EWC\Commons\Utilities\DataType\Types
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 */
class ImageTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * Just check if the Types has no syntax error.
	 */
	public function testIsThereAnySyntaxError() {
		$var = new Types;
		$this->assertTrue(is_object($var));
		unset($var);
	}
  
	/**
	 * Make sure the Types constants are as expected.
	 */
	public function testTypeConstantsValues() {
		$this->assertEquals("String", Types::BASIC_STRING, "Types::BASIC_STRING doesn't match 'String'");
		$this->assertEquals("Integer", Types::BASIC_INTEGER, "Types::BASIC_INTEGER doesn't match 'Integer'");
		$this->assertEquals("Float", Types::BASIC_FLOAT, "Types::BASIC_FLOAT doesn't match 'Float'");
		$this->assertEquals("Boolean", Types::BASIC_BOOLEAN, "Types::BASIC_BOOLEAN doesn't match 'Boolean'");
		$this->assertEquals("DateTime", Types::OBJECT_DATETIME, "Types::OBJECT_DATETIME doesn't match 'DateTime'");
		$this->assertEquals("MetaData", Types::OBJECT_METADATA, "Types::OBJECT_METADATA doesn't match 'MetaData'");
		$this->assertEquals("Mixed", Types::COMPLEX_MIXED, "Types::COMPLEX_MIXED doesn't match 'Mixed'");
		$this->assertEquals("Serial", Types::COMPLEX_SERIAL, "Types::COMPLEX_SERIAL doesn't match 'Serial'");
		$this->assertEquals("JSON", Types::COMPLEX_JSON, "Types::COMPLEX_JSON doesn't match 'JSON'");
		$this->assertEquals("ENUM", Types::COMPLEX_ENUM, "Types::COMPLEX_ENUM doesn't match 'ENUM'");
		$this->assertEquals("Array", Types::FILTER_ARRAY, "Types::FILTER_ARRAY doesn't match 'Array'");
		$this->assertEquals("Email", Types::FILTER_EMAIL, "Types::FILTER_EMAIL doesn't match 'Email'");
		$this->assertEquals("AutoIncrement", Types::DB_AUTO_INC, "Types::DB_AUTO_INC doesn't match 'AutoIncrement'");
	}
  
}