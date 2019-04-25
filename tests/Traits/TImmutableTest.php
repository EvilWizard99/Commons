<?php

namespace EWC\Commons\Tests\Traits;

use PHPUnit_Framework_TestCase;
use EWC\Commons\Traits\TImmutable;
use EWC\Commons\Exceptions\ImmutableTraitException;

/**
 * Corresponding Test Class for \EWC\Commons\Traits\TImmutable
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 */
class TImmutableTest extends PHPUnit_Framework_TestCase {
	
	protected $trait_name = "\EWC\Commons\Traits\TImmutable";
	
	/**
	 * Just check if the Image has no syntax error.
	 */
	public function testIsThereAnySyntaxError() {
		$trait = $this->getMockForTrait($this->trait_name);
		$this->assertTrue(is_object($trait));
		unset($trait);
	}
	
	public function testCanSetTraitImmutableValue() {
		$this->markTestIncomplete("Might have to use the train in the test class to access protected methods");
		$trait = $this->getMockForTrait($this->trait_name);
		$trait->traitSetImmutable("set value");
		$this->assertEquals("set value", $trait->traitGetImmutable(), "Immutable value has changed");
		unset($trait);
	}
	
}
