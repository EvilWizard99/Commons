<?php

namespace EWC\Commons\CLI\Arguments;

/**
 * Class OptionsParser
 * 
 * Parse Command Line Interface named pair arguments.
 *
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 * 
 * @uses	Reflector To get the factory shape product.
 * @uses	ReflectorException Catches named exception.
 */
class OptionsParser {
	
	protected $parsed_args;
	
	/**
	 * Process all the supplied command line switches/argument into an associative array.
	 * 
	 * @param	Array $args The command line argument pairs.
	 */
     protected function parseArguments($args) {
		// check that the script was invoked with arguments
        if(is_array($args)) {
			// loop through the arguments and extract the values
            foreach ($args as $argument) {
                if(preg_match("#--[a-zA-Z0-9]*=.*#", $argument)) {
				// argument has a value assigned to be used
					// split the argument into "name" = "value" pairs
    				$argument_key_pair = preg_split("/[=]{1}/", $argument);
                    $argument_value = '';
					// remove the switch string characters from the argument name
                    $argument_name = preg_replace("#--#", '', $argument_key_pair[0]);
                    for($i = 1; $i < count($argument_key_pair); $i++ ) {
                        $argument_value .= $argument_key_pair[$i];
                    }
                    $this->parsed_args[$argument_name] = $argument_value;
                } elseif(preg_match("#-[a-zA-Z0-9]#", $argument)) {
				// argument is a boolean flag switch
                    $argument_name = preg_replace("#-#", '', $argument);
                    $this->parsed_args[$argument_name] = "true";
                }
            }
        }
    }
	
}
