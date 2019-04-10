<?php

namespace EWC\Commons\Libraries;

use Exception;
use EWC\Commons\Libraries\FileSystem;
use EWC\Commons\Exceptions\FileSystemException;

/**
 * Class Image
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 * 
 * @uses	Exception Catches and throws named exceptions.
 * @uses	FileSystem To access source image files.
 * @uses	FileSystemException Catches and throws named exceptions.
 */
class Image {
	
	/** 
	* @var	String Internal flag to indicate SVG image file.
	*/
	const IMAGE_SVG = 'IMAGE_SVG';
	
	/**
	 * Serve the image and set the X-Powered-By: and Content-type: headers, 
	 * destroy the resources and exit the script.
	 *
	 * @param	Mixed $image A reference to the image resource to serve or the filename of the image to serve.
	 * @param	Integer $image_mimetype The integer value of the mime type for the image.
	 * @throws	FileSystemException If image source file does not exist.
	 */
	public static function serve(&$image, $image_mimetype=IMAGETYPE_PNG) {
		// set the additional headers
		header("X-Powered-By: Evil Wizard Creations", TRUE);
		// don't replace the previous header
		header("X-Powered-By: Dynamic Image Generation v2", FALSE);
		// determine the appropiate image mime type actions
		switch ($image_mimetype){
			case IMAGETYPE_GIF:
				header("Content-type: image/gif");
				$gd_image_function = 'imagegif';
			break;
			case IMAGETYPE_JPEG:
			case IMAGETYPE_JPEG2000:
				header("Content-type: image/jpeg");
				$gd_image_function = 'imagejpeg';
			break;
			case static::IMAGE_SVG:
			// doesn't have a gd image function and the resource should be NULL because the source image is SVG XHTML
				header("Content-type: image/svg+xml");
			break;
			case IMAGETYPE_PNG:
			default:
				header("Content-type: image/png");
				$gd_image_function = 'imagepng';
			break;
		}
		if((is_resource($image))) {
		// use the GD image function to serve the image resource then destroy it
			$gd_image_function($image);
			imagedestroy($image);
		} else {
		// just echo out the source image file contents
			// make sure the image source file exists
			if(!FileSystem::fileExists($image)) { throw new FileSystemException("Image source file [{$image}] not found"); }
			echo file_get_contents($image);
		}
		// all finished
		exit;
	}
	
	/**
	 * Create a new image resource from an original source image.
	 *
	 * @param	String $image_filename The full path to the image source file.
	 * @param	Integer $image_mimetype The integer value of the mime type for the image.
	 * @return	Resource A resource handle to the created image.
	 * @throws	FileSystemException If image source file does not exist.
	 */
	public static function createFromFile($image_filename, $image_mimetype) {
		// make sure the image source file exists
		if(!FileSystem::fileExists($image_filename)) { throw new FileSystemException("Image source file not found"); }
		// determine the appropiate image creation actions
		switch ($image_mimetype):
			case IMAGETYPE_JPEG:
			case IMAGETYPE_JPEG2000:
				$image = imagecreatefromjpeg($image_filename);
				imageinterlace($image, 1);
				// transparancy not supported by the image format
				$transparent_color = '';
			break;
			case IMAGETYPE_PNG:
				$image = imagecreatefrompng($image_filename);
				// get the image transparancy colour
				$transparent_color = imagecolortransparent($image);
				imagecolortransparent($image, $transparent_color);
			break;
			case IMAGETYPE_GIF:
				$image = imagecreatefromgif($image_filename);
				// get the image transparancy colour
				$transparent_color = imagecolortransparent($image);
				imagecolortransparent($image, $transparent_color);
			break;
			default:
			// unknown mime type
				throw new Exception("Invalid Image Type [{$image_mimetype}]:");
		endswitch;
		// return the created image resource
		return $image;
	}
	
	/**
	 * Save an image resource to file.
	 *
	 * @param	Resource $image A reference to the image resource to save to file.
	 * @param	String $image_save_filename The full path to the image source file.
	 * @param	Integer $image_mimetype The integer value of the mime type for the image.
	 * @throws	FileSystemException If unable to save the image to the file specified.
	 */
	public static function save(&$image, $image_save_filename, $image_mimetype=IMAGETYPE_PNG) {
		// determine which GD function to use to save the image to file
		switch ($image_mimetype){
			case IMAGETYPE_GIF:
				$saved = imagegif($image, $image_save_filename);
			break;
			case IMAGETYPE_JPEG:
			case IMAGETYPE_JPEG2000:
				// use a quite high quality value for the jpeg
				$saved = imagejpeg($image, $image_save_filename, 90);
			break;
			case IMAGETYPE_PNG:
			default:
				$saved = imagepng($image, $image_save_filename);
			break;
		}
		imagedestroy($image);
		// make sure the image was saved to file
		if(!$saved) { throw new FileSystemException("Unable to save image file to [{$image_save_filename}]"); }
	}
	
	/**
	 * Create a new thumbnail image resource from an original source image resource.
	 * 
	 * The $display_canvas_size optionally accepts the following array structure
	 * <code>
	 *		$display_canvas_size = [
	 *			Integer (display_width),
	 *			Integer (display_height)
	 *		];
	 * </code>
	 * 
	 * @param	Resource $image A resource handle to the source image.
	 * @param	Float $thumbnail_width The thumbnail image width value.
	 * @param	Float $thumbnail_height The thumbnail image width value.
	 * @param	Float $source_width The source image width value.
	 * @param	Float $source_height The source image width value.
	 * @param	Mixed $display_canvas_size The resized image display width in pixels.
	 * @param	String $offset An offset to focus the image resize around.
	 * @return	Resource A resource handle to the thumbnail image.
	 */
	public static function thumbnail($image, $thumbnail_width, $thumbnail_height, $source_width, $source_height, $display_canvas_size, $offset="center") {
		if(is_array($display_canvas_size)) {
		// seperate specific height and width specified
			list($display_width, $display_height) = $display_canvas_size;
		} else {
		// resized image size is dynamic
			// get the display width
			$display_width = $display_canvas_size;
			// calculate the aspect ratio of the thumbnail
			$aspect_ratio = $thumbnail_height / $thumbnail_width;
			// calculate the display height from the width & aspect
			$display_height = $display_width * $aspect_ratio;
		}
		// create a blank image resource of the thumbnail dimensions
		$thumbnail_image = imagecreatetruecolor($display_width, $display_height);
		// set the background colour to white and fill the image
		$thumbnail_bg_colour = imagecolorallocate($thumbnail_image, 255, 255, 255 );
		imagefill($thumbnail_image, 0, 0, $thumbnail_bg_colour);
		// determine where on the image to generate the thumbnail from
		switch($offset) {
			case "center":
			// center the thumbnail in the middle of the source image
				$thumbnail_X = round(($display_width - $thumbnail_width) / 2);
				$thumbnail_Y = round(($display_height - $thumbnail_height) / 2);
			break;
			default:
			// use the top left
				$thumbnail_X = 0;
				$thumbnail_Y = 0;
			break;
		}
		// create a resampled and resized thumbnail image resource
		imagecopyresampled(	
					$thumbnail_image, 
					$image, 
					$thumbnail_X, 
					$thumbnail_Y, 
					0, 
					0, 
					$thumbnail_width, 
					$thumbnail_height, 
					$source_width, 
					$source_height
				);
		return $thumbnail_image;
	}
	
	/**
	 * Recursive function to calculate the dimensions for resizing the image.
	 *
	 * @param	Float $current_width Reference to the resized image width value.
	 * @param	Float $current_height Reference to the resized image width value.
	 * @param	Float $target_width The resized target image width value.
	 * @param	Float $target_height The resized target image width value.
	 * @param	Float $ratio The aspect ratio of the resized image.
	 * @param	Boolean $scale_up Flag to indicate image can scale up.
	 */
	public static function scale(&$current_width, &$current_height, $target_width, $target_height, $ratio=NULL, $scale_up=FALSE)  {
		// ensure an aspect ratio value
		if(is_null($ratio)) { $ratio = $current_height / $current_width; }
		if($current_width > $target_width || $current_height > $target_height) {
		// need to scale down
			if($current_width == $current_height) {
			// Square
				if($target_height > $target_width) {
					$current_width = $current_height = $target_width;
				} else {
					$current_width = $current_height = $target_height;					
				}
			} elseif($current_width > $target_width) {
			// Wide
				$current_height = ($current_width * $ratio);
				$current_width = $target_width;
			} else {
			// Tall
				$current_height = $target_height;
				$current_width = ($current_height / $ratio);
			}
			// recursively call until the correct dimensions are reached
			static::scale($current_width, $current_height, $target_width, $target_height, $ratio, $scale_up);
		} elseif($scale_up == TRUE && $current_height != $target_height && $current_width != $target_width) {
		// need to scale up
			if($current_width == $current_height) {
			// Square
				$current_width = $current_height = $target_width;
			} elseif($current_width > $current_height) {
			// Wide
				$current_width = $target_width;
				$current_height = ($current_width * $ratio);
			} else {
			// Tall
				$current_height = $target_height;
				$current_width = ($current_height / $ratio);
			}
			// recursively call until the correct dimensions are reached
			static::scale($current_width, $current_height, $target_width, $target_height, $ratio, $scale_up);
		}
	}
	
	/**
	 * Convert a hexidecimal colour value to RGB colour values.
	 *
	 * @param	String $hex_string The full path to the image source file.
	 * @param	Boolean $return_as_string Flag to indicate the RGB colour should be returned as a string value.
	 * @return	Mixed An array of RGB colour values or the string representation of it.
	 */
	public static function hex2rgb($hex_string, $return_as_string=FALSE) {
		//the default identifier is '<b>#</b>' and the alternative is '<b>&H</b>'.
		if(0 === strpos($hex_string, '#')) {
		// default identifier
			// remove the # identifier character
			$hex_string = substr($hex_string, 1);
		} elseif(0 === strpos($hex_string, "&H")) {
		// alternative identifier
			// remove the &H identifier string
			$hex_string = substr($hex_string, 2);
		}
		// determine the hex code sections
		$cut_point = ceil(strlen($hex_string) / 2) - 1;
		$hex_parts = explode(':', wordwrap($hex_string, $cut_point, ':', $cut_point), 3);
		// set the Red, Green and Blue aspects respectively
		$rgb[0] = (isset($hex_parts[0]) ? hexdec($hex_parts[0]) : 0);
		$rgb[1] = (isset($hex_parts[1]) ? hexdec($hex_parts[1]) : 0);
		$rgb[2] = (isset($hex_parts[2]) ? hexdec($hex_parts[2]) : 0);
		// determine how the RGB colour needs to be returned
		return ($return_as_string) ? implode(' ', $rgb) : $rgb;
	}
	
	/**
	 * Generate a random hexidecimal colour code.
	 *
	 * @return	String A randomly generated hexidecimal colour code.
	 */
	public static function generateRandomHexColour() {
		// define the available characters for a valid hexidecimal colour value
		$hex_parts = ['A','B','C','D','E','F','0','1','2','3','4','5','6','7','8','9'];
		// get a count of the characters to save doing it every time in the loop
		$hex_parts_length = count($hex_parts);
		// start the hex colour string off
		$hex_colour = '#';
		for($loop_count = 0; $loop_count < 6; $loop_count++) {
		// loop 6 times to generate a random value from the hex parts available
			$hex_colour .= $hex_parts[rand(0, $hex_parts_length - 1)];
		}
		// return the generated colour code
		return $hex_colour;
	}
	
	/**
	 * Generate a random RGB colour code.
	 *
	 * @param	Boolean $return_as_string Flag to indicate the RGB colour should be returned as a string value.
	 * @return	String A randomly generated RGB colour code.
	 */
	public static function generateRandomRGBColour($return_as_string=FALSE) { return static::hex2rgb(static::generateRandomHexColour(), $return_as_string); }	
}
