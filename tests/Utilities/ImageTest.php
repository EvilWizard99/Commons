<?php 

namespace EWC\Commons\Tests\Utilities;

use PHPUnit_Framework_TestCase;
use EWC\Commons\Utilities\Image;

/**
 * Corresponding Test Class for \EWC\Commons\Utilities\Image
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 */
class ImageTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * Just check if the Image has no syntax error.
	 */
	public function testIsThereAnySyntaxError() {
		$var = new Image;
		$this->assertTrue(is_object($var));
		unset($var);
	}
  
}