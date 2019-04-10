<?php

namespace EWC\Commons\Traits;

use EWC\Commons\Exceptions\ImmutableTraitException;

/**
 * Trait TImmutable
 * 
 * Provide immutable state for an object preventing change after creation.
 * 
 * Provides the following public access methods.
 * 
 * Provides the following protected methods for use.
 * 
 * traitSetImmutable($value)
 * traitGetImmutable()
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 * 
 * @uses	ImmutableTraitException Catches and throws named exceptions.
 */
trait TImmutable {
	
	/*
	 * @var	Mixed $value The value to be immutable.
	 */
	private $trait_imumutable = NULL;
	
	/**
	 * Set the value to the immutable state.
	 * 
	 * @param	Mixed $value The value to be immutable.
	 * @throws	ImmutableTraitException If the object immutable property has already been set.
	 */
	protected function traitSetImmutable($value) { 
		if(!is_null($this->trait_imumutable)) {
			throw new ImmutableTraitException("Unable to reset [" . static::class . "] from [{$this->trait_imumutable}] to [{$value}]");
		}
		$this->trait_imumutable = $value;
	}
	
	/**
	 * Get the immutable value.
	 * 
	 * @return	Mixed The immutable value.
	 */
	protected function traitGetImmutable() { return $this->trait_imumutable; }
	
}
