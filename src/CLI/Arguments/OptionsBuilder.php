<?php

namespace EWC\Commons\CLI\Arguments;

/**
 * Class OptionsBuilder
 * 
 * Generate CLI options for application use.
 *
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 */
class OptionsBuilder {
	
	/**
	 * @var	OptionsBuilder The single instance of the options builder.
	 */
	protected static $myInstance = NULL;
	
	/** 
	 * @var	Array Holds command line switches available for use in the script.
	 */
	private $options;
	
	/**
	 * OptionsBuilder constructor.
	 * 
	 * @param	Array $options Optional list of options for use
	 */
	protected function __construct($options=[]) { $this->options = $options; }
	
	/**
	 * Get the application CLI options available.
	 *
	 * @return	Array List of CLI options switches.
	 */
	public static function getOptions() {
		// get the options
		if(is_null(static::$myInstance)) { static::$myInstance = new static([]); }
		return static::$myInstance->options;
	}
}
