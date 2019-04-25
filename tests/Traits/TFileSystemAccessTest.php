<?php

namespace EWC\Commons\Tests\Traits;

use PHPUnit_Framework_TestCase;

/**
 * Corresponding Test Class for \EWC\Commons\Traits\TFileSystemAccess
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 */
class TFileSystemAccessTest extends PHPUnit_Framework_TestCase {
	
	protected $trait_name = "\EWC\Commons\Traits\TFileSystemAccess";
	
	/**
	 * Just check if the TFileSystemAccess has no syntax error.
	 */
	public function testIsThereAnySyntaxError() {
		$trait = $this->getMockForTrait($this->trait_name);
		$this->assertTrue(is_object($trait));
		unset($trait);
	}
	
}
