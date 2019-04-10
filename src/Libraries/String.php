<?php

namespace EWC\Commons\Libraries;

use Exception;

/**
 * Class String
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 * 
 * @uses	Exception Catches and throws named exceptions.
 */
class String {
	
	/** 
	* @var	String The standard english alpha character set.
	*/
	const ALPHA = "abcdefghijklmnopqrstuvwxyz";
	
	/** 
	* @var	String The standard numeric character set.
	*/
	const NUMERIC = "0123456789";
	
	/** 
	* @var	String The standard extended character set.
	*/
	const EXTENDED = "~!@#$%^&*()_+`-={}|][\?\":;'><,./";

	/**
	 * Split a multi-line string into an array of lines and optionally trim off extra white space.
	 *
	 * @param	String $string The multi-line string to convert to array of lines
	 * @param	Boolen $trim Flag to indicate trimming of white space from each line.
	 * @return	Array The array of lines in the string.
	 */
	public static function toArray($string, $trim=FALSE) {
		// get the lines in the string
		$string_lines = explode("\n", $string);
		if($trim) {
		// need to trim off the excess white space in each line
			foreach($string_lines as $idx => $line) {
				$string_lines[$idx] = trim($line);
			}
		}
		// return the lines from the string
		return $string_lines;
	}
	
	/**
	 * Generate a random string using a variety of character sets settings.
	 *
	 * @param	Integer $min_length minimum length of the generated code.
	 * @param	Integer $max_length maximum length of the generated code.
	 * @param	Boolen $alpha flag to use alpha characters.
	 * @param	Boolen $upper flag to use uppercase alpha characters.
	 * @param	Boolen $extended flag to use sepecial/extended characters.
	 * @param	Boolen $numeric flag to use numerical characters.
	 * @return	String The generated code string.
	 * @throws	Exception If min length is greater than the max length or there are no characters to generate from.
	 */
	public static function generateRandom($min_length, $max_length, $alpha=FALSE, $upper=FALSE, $extended=FALSE, $numeric=FALSE) {
		// ensure the min and max are correct
		if($min_length > $max_length) { throw new Exception("Minimun length allowed specified [{$min_length}] is greater the maxmimum allowed specified [{$max_length}]"); }
		// start off with an empty code
		$code = '';
		// determine the code generator character set available
		$charset = '';
		$charset .= ($alpha) ? static::ALPHA : '';
		$charset .= ($upper) ? strtoupper(static::ALPHA) : '';
		$charset .= ($numeric) ? static::NUMERIC : '';
		$charset .= ($extended) ? static::EXTENDED : '';
		$charset_length = strlen($charset);
		// ensure the min and max are correct
		if(!$charset_length) { throw new Exception("No character set to generate random characters from"); }
		// generate the code length
		$code_length = mt_rand($min_length, $max_length);
		// loop through and add random characters to the code
		for($loop = 0; $loop < $code_length; $loop++) {
			// randomly choose a character for the available character set
			$code .= $charset[(mt_rand(0, ($charset_length - 1)))];
		}
		//return the generated code
		return $code;
	}
	
	/**
	 * Generate a random code string using a variety of character set settings.
	 *
	 * @param	Integer $min_length minimum length of the generated code.
	 * @param	Integer $max_length maximum length of the generated code.
	 * @param	Boolen $upper flag to use uppercase characters.
	 * @param	Boolen $extended flag to use sepecial/extended characters.
	 * @param	Boolen $numeric flag to use numerical characters.
	 * @return	String The generated code string.
	 * @throws	Exception If min length is greater than the max length.
	 */
	public static function generateRandomCode($min_length, $max_length, $upper=FALSE, $extended=FALSE, $numeric=FALSE) {
		// generate the random code from the supplied settings
		return static::generateRandom($min_length, $max_length, TRUE, $upper, $extended, $numeric);
	}
	
	/**
	 * Generate a random numeric code string .
	 *
	 * @param	Integer $length The length of the generated numeric code.
	 * @return	String The randomly generated numeric code string.
	 * @throws	Exception If length is NaN or less than 1.
	 */
	public static function generateNumberCode($length) {
		// ensure the min and max are correct
		if((!is_numeric($length)) || ($length < 1)) { throw new Exception("length value [{$length}] is invalid"); }
		// generate the random numeric code of the specified length
		return static::generateRandom($length, $length, FALSE, FALSE, FALSE, TRUE);
	}
	
}
