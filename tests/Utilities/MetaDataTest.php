<?php 

namespace EWC\Commons\Tests\Utilities;

use PHPUnit_Framework_TestCase;
use EWC\Commons\Utilities\MetaData;
use stdClass;

/**
 * Corresponding Test Class for \EWC\Commons\Utilities\MetaData
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 */
class MetaDataTest extends PHPUnit_Framework_TestCase {
	
	public function setUp() {
		parent::setUp();
	}

	/**
	 * Just check if the MetaData has no syntax error.
	 */
	public function testIsThereAnySyntaxError() {
		$var = MetaData::create();
		$this->assertTrue(is_object($var));
		unset($var);
	}
	
	/**
	 * Provides valid source to create MetaData from.
	 */
	public function validCreateSourceProvider() {
		return [
			"valid array"	=> [
				[
					"string"	=> "string",
					"number"	=> 20,
					"array"		=> [1,2,"three"]
				]
			],
			"valid json"	=> [
				[
					"{string;string,number:20,array:[1,2,\"three\"]}"
				]
			],
			"valid null"	=> [
				NULL
			]
		];
	}
	
	/**
	 * Provides invalid source to create MetaData from.
	 */
	public function invalidCreateSourceProvider() {
		return [
			"garbage"				=> ["bla"],
			"invalid object source"	=> [new stdClass]
		];
	}
  
	/**
	 * Make sure the MetaData can be created using valid source data.
	 * 
	 * @dataProvider validCreateSourceProvider
	 */
	public function testCreatesFromValidSource($data) {
		return MetaData::create($data);
	}
  
	/**
	 * Just check if the MetaData throws invalid construct.
	 * 
	 * @dataProvider invalidCreateSourceProvider
	 * @expectedException \EWC\Commons\Exceptions\MetadataTraitException
	 */
	public function testThrowsInvalidCreatesFromSource($data) {
		return MetaData::create($data);
	}
  
}