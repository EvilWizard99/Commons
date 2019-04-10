<?php

namespace EWC\Commons\Utilities;

use EWC\Commons\Libraries\Image as ImageLib;
use EWC\Commons\Libraries\FileSystem;
use EWC\Commons\Exceptions\FileSystemException;

/**
 * Class Image
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 * 
 * @uses	ImageLib For base image functionality.
 * @uses	FileSystem To access source image files.
 * @uses	FileSystemException Catches and throws named exceptions.
 */
class Image {
		
	/*
	 * @var	Mixed The image resource or the absolute path and filename of the original source image.
	 */
	protected $source_image;
		
	/*
	 * @var	String The absolute path and filename of the original source image.
	 */
	protected $source_image_filename;
	
	/*
	 * @var	Integer The integer value of the mime type for the source image.
	 */
	protected $source_image_mimetype;
	
	/*
	 * @var	Array The list of image attributes.
	 */
	protected $source_image_attributes;
	
	/*
	 * @var	Integer The height of the source image in pixels.
	 */
	protected $source_image_height;
	
	/*
	 * @var	Integer The width of the source image in pixels.
	 */
	protected $source_image_width;
	
	/*
	 * @var	Integer The resized height of the generated image in pixels.
	 */
	protected $resized_image_height;
	
	/*
	 * @var	Integer The resized width of the generated image in pixels.
	 */
	protected $resized_image_width;
	
	/*
	 * @var	Integer The original aspect ratio of the source image.
	 */
	protected $aspect_ratio;
	
	/**
	 * Image constructor.
	 *
	 * @param	String $image_filename The full path to the image source file.
	 * @param	Boolean $image_SVG Flag to indicate the image source type is SVG.
	 * @throws	FileSystemException If image source file does not exist.
	 */
	public function __construct($image_filename, $image_SVG=FALSE) {
		if($image_SVG) {
		// image source is an SVG file
			$this->source_image_filename = $this->source_image = $image_filename;
			$this->source_image_mimetype = ImageLib::IMAGE_SVG;
		} else {
		// normal image type file to display
			// set the image source details from the filename
			$this->setImageSource($image_filename);
		}
	}	
	
	/**
	 * Output the image with appropriate headers.
	 */
	public function display() { ImageLib::serve($this->source_image, $this->source_image_mimetype); }
	
	/**
	 * Output a thumbnail image with appropriate headers.
	 *
	 * @param	Integer $thumbnail_width The thumbnail width in pixels.
	 */
	public function displayThumbnail($thumbnail_width=200) {
		// get the resized thumbnail image
		$thumbnail_image = $this->resize($thumbnail_width);
		// serve the resized thumbnail image
		ImageLib::serve($thumbnail_image);
	}
	
	/**
	 * Generate a resized image from a source image file and optionally save the resized image.
	 *
	 * @param	Integer $resized_image_width The thumbnail width in pixels.
	 * @param	String $save_to_file Optional file to save the resized image as.
	 * @param	Integer $output_mimetype The integer value of the mime type for the image.
	 * @param	Boolean $scale_up Flag to indicate image can scale up.
	 * @param	String $offset An offset to focus the image resize around.
	 * @return	Resource A resource handle to the resized image.
	 */
	public function resize($resized_image_width, $save_to_file=NULL, $output_mimetype=NULL, $scale_up=FALSE, $offset="center") {
		// set the resized image display size
		$this->setDisplaySize($resized_image_width, $scale_up);
		// create a resized image resource from the source image
		$resized_image = ImageLib::thumbnail(
									$this->source_image, 
									$this->resized_image_width, 
									$this->resized_image_height, 
									$this->source_image_width, 
									$this->source_image_height,
									$resized_image_width,
									$offset
								);
							
		if(!is_null($save_to_file)) {
		// save the resized image to file
			$output_image_mimetype = $output_mimetype ? $output_mimetype :  $this->source_image_mimetype;
			ImageLib::save($resized_image, $save_to_file, $output_image_mimetype);
		}
		return $resized_image;
	}
	
	/**
	 * Create a cropped version of the image.
	 * 
	 * @param	Float $target_width The target image width value.
	 * @param	Float $target_height The target image width value.
	 * @param	Float $crop_X The X coordinate for the crop.
	 * @param	Float $crop_Y The X coordinate for the crop.
	 * @param	Float $crop_width The cropped image width value.
	 * @param	Float $crop_height The cropped image width value.
	 * @param	String $save_to_file Optional file to save the resized image as.
	 * @param	Integer $output_mimetype The integer value of the mime type for the image.
	 * @return	Image The cropped image.
	 */
	public function crop($target_width, $target_height, $crop_X, $crop_Y, $crop_width, $crop_height, $save_to_file, $output_mimetype=NULL) {
		// create a blank image of the target dimensions
		$cropped_image = imagecreatetruecolor($target_width, $target_height);
		// resample the image to the cropped constraints
		imagecopyresampled(
				$cropped_image,
				$this->source_image,
				0,
				0,
				$crop_X,
				$crop_Y,
				$target_width,
				$target_height,
				$crop_width,
				$crop_height
		);
		// save the cropped image and create a new Image 
		$output_image_mimetype = $output_mimetype ? $output_mimetype :  $this->source_image_mimetype;
		ImageLib::save($cropped_image, $save_to_file, $output_image_mimetype);
		return new static($save_to_file);
	}
	
	/**
	 * Set the source image filename and set the source image height, width, mime type and aspect ratio.
	 *
	 * @param	String $image_filename The full path to the image source file.
	 * @throws	FileSystemException If image source file does not exist.
	 */
	protected function setImageSource($image_filename) {
		// make sure the image source file exists
		if(!FileSystem::fileExists($image_filename)) { throw new FileSystemException("Image source [{$image_filename}] not found"); }
		// set the source filename
		$this->source_image_filename = $image_filename;
		// get the image details 
		$image_details = getimagesize($this->source_image_filename);
		list($this->source_image_width, $this->source_image_height, $this->source_image_mimetype, $this->source_image_attributes) = $image_details;
		// determine the source image aspect ratio
		$this->aspect_ratio = $this->source_image_height / $this->source_image_width;
		// get the image resource handle
		$this->source_image = ImageLib::createFromFile($this->source_image_filename, $this->source_image_mimetype);
	}
	
	/**
	 * Set the resized image dimensions based on the aspect ratio of the original source image.
	 *
	 * The $display_canvas_size optionally accepts the following array structure
	 * <code>
	 *		$display_canvas_size = [
	 *			Integer (display_width),
	 *			Integer (display_height)
	 *		];
	 * </code>
	 * 
	 * @param	Mixed $display_canvas_size The resized image display width in pixels.
	 * @param	Boolean $scale_up Flag to indicate image can scale up.
	 */
	protected function setDisplaySize($display_canvas_size, $scale_up=FALSE) {
		// Set the width and height depending on the value sent in.
		if(is_array($display_canvas_size)) {
		// seperate specific height and width specified
			list($display_width, $display_height) = $display_canvas_size;
		} else {
		// resized image size is square
			$display_width = $display_canvas_size;
			$display_height = $display_width * $this->aspect_ratio;
		}
		// set the resized image dimesions to the source image dimensions
		$this->resized_image_width = $display_width;
		$this->resized_image_height = $display_height;

		// scale the image to fit the display canvas dimensions
		ImageLib::scale($this->resized_image_width, $this->resized_image_height, $display_width, $display_height, NULL, $scale_up);
	}

}
