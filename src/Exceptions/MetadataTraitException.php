<?php

namespace EWC\Commons\Exceptions;

use Exception;

/**
 * Exception MetadataTraitException
 * 
 * Group trait metadata exceptions and errors.
 *
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2018 Evil Wizard Creation.
 * 
 * @uses	ArrayPathTraitException As a base for metadata related exception.
 * @uses	Exception To rethrow.
 */
class MetadataTraitException extends ArrayPathTraitException {
	
	/**
	 * @var	Integer Code for the data type is not of the expected type.
	 */
	const TYPE_MISMATCH = 71;
	
	/**
	 * @var	Integer Code for metadata section exists when adding.
	 */
	const METADATA_SECTION_ALREADY_SET = 702;
	
	/**
	 * @var	Integer Code for attempting to overwrite existing metadata.
	 */
	const METADATA_ALREADY_DEFINED = 700001;
	
	/**
	 * @var	Integer Code for metadata source string fails to JSON decode.
	 */
	const BAD_METADATA_SOURCE_JSON = 700002;
	
	/**
	 * @var	Integer Code for metadata source not being an Array.
	 */
	const BAD_METADATA_SOURCE_ARRAY = 700003;
	
	/**
	 * MetadataTraitException constructor.
	 * 
	 * @param	String $message An error message for the exception.
	 * @param	Integer $code An error code for the exception.
	 * @param	Exception $previous An optional previously thrown exception.
	 */
	public function __construct($message="",$code=0, Exception $previous=NULL) {
		parent::__construct($message, $code, $previous);
	}
	
	/**
	 * Generate a metadata already defined exception.
	 * 
	 * @return	MetadataTraitException
	 */
	public static function withMetadataAlreadyDefined() {
		return new static("Overwiting existing metadata is not permitted.", static::METADATA_ALREADY_DEFINED);
	}
	
	/**
	 * Generate bad metadata JSON source string exception.
	 * 
	 * @return	MetadataTraitException
	 */
	public static function withBadSourceJSON() {
		return new static("Failed to decode the JSON source string.", static::BAD_METADATA_SOURCE_JSON);
	}
	
	/**
	 * Generate a metadata section already set, use update instead exception.
	 * 
	 * @param	String $full_path The full metadata structure path.
	 * @param	String $path_section The path section that does not exist in the metadata structure.
	 * @return	MetadataTraitException
	 */
	public static function withMetadataSectionAlreadySet($full_path, $path_section) {
		return new static("The metadata section [{$path_section}] - [{$full_path}] already exists, use update instead.", static::METADATA_SECTION_ALREADY_SET);
	}
	
}