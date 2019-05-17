<?php

namespace EWC\Commons\CLI\Arguments;

use EWC\Commons\Utilities\Reflector;
use EWC\Commons\Exceptions\ReflectorException;

/**
 * Class OptionsFactory
 * 
 * Generate CLI options for application use.
 *
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 * 
 * @uses	Reflector To get the factory shape product.
 * @uses	ReflectorException Catches named exception.
 */
class OptionsFactory {
	
	/**
	 * Get the application CLI options available.
	 *
	 * @param 	String $options_app_name The application name to get the options for.
	 * @param 	String $classname_suffix An additional options classname suffix.
	 * @param 	Array $default_options A list of default options to use if the options builder is not available.
	 * @return	Array List of CLI options switches.
	 */
	public static function getOptions($options_app_name, $classname_suffix="CLIOptions", $default_options=[]) {
		// construct the options builder class name based on the application name & optional suffix
		$options_builder_name = "{$options_app_name}{$classname_suffix}";
		try {
			// try to get a reflection of the applications options builder
			$options_builder_reflection = new Reflector($options_builder_name);
			// verify the options builder is of a recognised type and will have the get options static method
			$options_builder_reflection->verifyType(OptionsBuilder::class);
			$options = $options_builder_reflection->runReflectedMethod("getOptions", [], TRUE);
		} catch (ReflectorException $ex) {
		// something went wrong with the options, just use the default options provided
			$options = $default_options;
		}
		return $options;
	}
}
