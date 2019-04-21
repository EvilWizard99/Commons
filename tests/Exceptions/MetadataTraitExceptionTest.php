<?php 

namespace EWC\Commons\Tests\Exceptions;

use PHPUnit_Framework_TestCase;
use EWC\Commons\Exceptions\MetadataTraitException;

/**
 * Corresponding Test Class for \EWC\Commons\Exceptions\MetadataTraitException
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 */
class MetadataTraitExceptionTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * Just check if the MetadataTraitException has no syntax error.
	 */
	public function testIsThereAnySyntaxError() {
		$var = new MetadataTraitException;
		$this->assertTrue(is_object($var));
		unset($var);
	}
	
	/**
	 * Make sure create with bad source JSON throws expected exception.
	 * 
	 * @expectedException \EWC\Commons\Exceptions\MetadataTraitException
	 * @expectedExceptionMessage Failed to decode the JSON source string.
	 * @expectedExceptionCode \EWC\Commons\Exceptions\MetadataTraitException::BAD_METADATA_SOURCE_JSON
	 */
	public function testCreateTraitArrayPathThrowsExceptionWithBadSourceJSON() {
		throw MetadataTraitException::withBadSourceJSON();
	}
	
	/**
	 * Make sure create with bad source JSON throws expected exception.
	 * 
	 * @expectedException \EWC\Commons\Exceptions\MetadataTraitException
	 * @expectedExceptionMessage Overwiting existing metadata is not permitted.
	 * @expectedExceptionCode \EWC\Commons\Exceptions\MetadataTraitException::METADATA_ALREADY_DEFINED
	 */
	public function testMutateImmutableThrowsExceptionWithMetadataAlreadyDefined() {
		throw MetadataTraitException::withMetadataAlreadyDefined();
	}
	
	/**
	 * Make sure array path not found throws expected exception.
	 * 
	 * @expectedException \EWC\Commons\Exceptions\MetadataTraitException
	 * @expectedExceptionMessageRegExp /The metadata section \[.+\] \- \[.+\] already exists, use update instead\./
	 * @expectedExceptionCode \EWC\Commons\Exceptions\MetadataTraitException::METADATA_SECTION_ALREADY_SET
	 */
	public function testDisallowOverriteWithoutUpdateThrowsExceptionWithMetadataSectionAlreadySet() {
		throw MetadataTraitException::withMetadataSectionAlreadySet("full/path/to/key", "this key");
	}
  
}