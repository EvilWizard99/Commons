<?php 

namespace EWC\Commons\Tests\Exceptions;

use PHPUnit_Framework_TestCase;
use EWC\Commons\Exceptions\ArrayPathTraitException;

/**
 * Corresponding Test Class for \EWC\Commons\Exceptions\ArrayPathTraitException
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 */
class ArrayPathTraitExceptionTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * Just check if the ArrayPathTraitException has no syntax error.
	 */
	public function testIsThereAnySyntaxError() {
		$var = new ArrayPathTraitException;
		$this->assertTrue(is_object($var));
		unset($var);
	}
	
	/**
	 * Make sure create with bad source array throws expected exception.
	 * 
	 * @expectedException \EWC\Commons\Exceptions\ArrayPathTraitException
	 * @expectedExceptionMessage Source input was not an Array.
	 * @expectedExceptionCode \EWC\Commons\Exceptions\ArrayPathTraitException::BAD_SOURCE_ARRAY
	 */
	public function testCreateTraitArrayPathThrowsExceptionWithBadSourceArray() {
		throw ArrayPathTraitException::withBadSourceArray();
	}
	
	/**
	 * Make sure array path not found throws expected exception.
	 * 
	 * @expectedException \EWC\Commons\Exceptions\ArrayPathTraitException
	 * @expectedExceptionMessageRegExp /The array structure does not contain the following path section \[.+\] \- \[.+\]\./
	 * @expectedExceptionCode \EWC\Commons\Exceptions\ArrayPathTraitException::ARRAY_PATH_NOT_FOUND
	 */
	public function testInvalidTraitArrayPathThrowsExceptionWithPathNotFound() {
		throw ArrayPathTraitException::withPathNotFound("full/path/to/key", "this key");
	}
  
}