<?php

namespace EWC\Commons\Utilities;

use ArrayAccess;
use EWC\Commons\Traits\TMetadata;
use Exception;
use EWC\Commons\Exceptions\MetadataTraitException;
use EWC\Commons\Exceptions\ArrayPathTraitException;

/**
 * Class MetaData
 * 
 * Create an interactive metadata array object.
 *
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2018 Evil Wizard Creation.
 * 
 * @uses	ArrayAccess To allow Array style access to the metadata.
 * @uses	TMetadata The JSON metadata Traits functionality.
 * @uses	ErrorLogger To log errors and exceptions encountered.
 * @uses	Exception For catching exceptions.
 * @uses	MetadataTraitException Catches and throws named exceptions.
 * @uses	ArrayPathTraitException Catches and throws named exceptions.
 */
class MetaData implements ArrayAccess {
	
	/**
	 * Includes the following TMetadata trait methods for use.
	 * 
	 * Provides no public access methods.
	 * 
	 * Provides the following methods for protected access.
	 * 
	 * traitMetadataSet($source)
	 * traitMetadataUnset($path)
	 * traitMetadataHas($path)
	 * traitMetadataGetValue($path)
	 * traitMetadataGetAsArray()
	 * &traitMetadataGetAsReference()
	 * traitMetadataAddValue($name, $data, $path, $update)
	 * traitMetadataSetPathSeparator($new_path_separator)
	 * traitMetadataGetPathSeparator()
	 * traitMetadataDebug()
	 * traitMetadataGetAsJsonString($json_options)
	 * 
	 * Provides the following inherited protected access methods from TArrayPath trait.
	 * 
	 * traitArrayHasPath($source, $path=NULL)
	 * traitArrayGetPathValue($source, $path)
	 * &traitArrayGetPathReference(&$source, $path)
	 * traitArraySetPathSeparator($new_path_separator)
	 * traitArrayGetPathSeparator()
	 * traitArrayGetSectionFromPath($source, $path)
	 * traitArrayAddSectionToPathByValue(&$source, $path, $name, $data, $create=FALSE)
	 * &traitArrayGetSectionFromPathByReference(&$source, $path)
	 * traitAddArraySectionToPathByReference(&$source, $path, $name, &$data, $create=FALSE)
	 */
	use TMetadata;
	
	/**
	 * MetaData constructor.
	 * 
	 * @param	Mixed $source Optional either a JSON encoded source string or n source Array to convert.
	 * @throws	MetadataTraitException If the metadata has already been set or the JSON fails.
	 */
	protected function __construct($source=NULL) {
		// make sure the source is set to something it can use
		if(is_null($source)) { $source = []; }
		// create the metadata from the source
		$this->traitMetadataSet($source);
	}
	/**
	 * Magic access method because the metadata is held in a trait private array.
	 * 
	 * @param	String $name The name of the metadata key being accessed.
	 * @return	Mixed The requested metadata key value.
	 */
	public function __get($name) {
		// check to see if the property name is a metadta path string
		if($this->traitMetadataHas($name)) {
			// return metadata path string value
			return $this->$this->traitArrayGetPathValue($this->traitMetadataGetAsReference(), $name);
		}
		// undefined request for a metadata property
		$error_message = "Call to undefined metadata property {$name}";
		ErrorLogger::getInstance()->log($error_message, ErrorLogger::TYPE_ERROR);
		// trigger error as normal
		trigger_error($error_message, E_USER_ERROR);
		// metadata property not defined, return null
		return NULL;
	}	
	
	/**
	 * Magic access method because the record fields are actually in the metadata,
	 * and some others are dependant object models.
	 * 
	 * @param	String $name The name of the metadata key being accessed.
	 * @param	Mixed $value The value to set the metadata key to.
	 */
	public function __set($name, $value) {
		try {
			// set the new metadata key value
			$this->traitMetadataAddValue($name, $value, NULL, TRUE);
		} catch (Exception $ex) {
		// unable to set the metadata key value
			ErrorLogger::getInstance()->logException($ex, TRUE);			
		}
	}
	
	/**
	 * Check if the array accessed name is a field name and set the new value if not a restricted field.
	 * 
	 * @param	String $name The name of the property being accessed via array style.
	 * @param	Mixed $value The value to set the field name to.
	 */
	public function offsetSet($name, $value) {
		if(is_null($name)) {
		// metadata doesn't support top level numeric indexing yet.
			// @todo throw unlawful add metadata attempt
			ErrorLogger::getInstance()->log("Error attempting Metadata direct array set [{$name}]", TRUE);
		}
		try {
			// set the new metadata value
			$this->traitMetadataAddValue($name, $value, NULL, TRUE);
		} catch (Exception $ex) {
		// unable to set the field value
			ErrorLogger::getInstance()->logException($ex, TRUE);			
		}
	}
	
	/**
	 * Check if the array accessed name is a field name exists.
	 * 
	 * @param	String $name The name of the property being accessed via array style.
	 */
	public function offsetExists($name) { return $this->has($name); }
	
	/**
	 * Unset the array accessed field name.
	 * 
	 * @param	String $name The name of the property being accessed via array style.
	 */
	public function offsetUnset($name) { 
		try {
			// unset the metadata, this allows for deep unset via the path string,
			// e.g. unset($metadata["path.to.deep.section"]) is the same as
			// unset($metadata["path"]["to"]["deep"]["section"]) with the undefined index error
			$this->traitMetadataUnset($name);
		} catch (MetadataTraitException $ex) {
			// @todo throw unlawful add field attempt
			ErrorLogger::getInstance()->logException($ex, TRUE);
			ErrorLogger::getInstance()->log("Error attempting direct array set Metadata [{$name}]", TRUE);

		}
	}
	
	/**
	 * Get the array accessed field name.
	 * 
	 * @param	String $name The name of the property being accessed via array style.
	 */
	public function offsetGet($name) { return $this->get($name, NULL); }
	
	/**
	 * Create a metadata object from a JSON encoded source string.
	 * 
	 * @param	Mixed $source Optional either a JSON encoded source string or n source Array to convert.
	 * @return	MetaData The source as interactive metadata.
	 * @throws	MetadataTraitException If the metadata has already been set, the source is not an array or a valid JSON String.
	 */
	public static function create($source=NULL) { return new static($source); }

	/**
	 * Add a metadata key value pair to the root of the metadata structure.
	 * 
	 * @param	String $name The metadata section name.
	 * @param	Mixed $data The metadata data value.
	 * @param	Boolean $update Flag to indicate the value should be updated is exists.
	 * @param	String $path Optional metadata structure path to add the key to.
	 * @throws	MetadataTraitException If the metadata section key exist but not updating.
	 */
	public function add($name, $data, $update, $path=NULL) { $this->traitMetadataAddValue($name, $data, $path, $update); }
	
	/**
	 * Check if a metadata path exists within the metadata structure.
	 * 
	 * @param	String $path The metadata structure path.
	 * @return	Boolean TRUE if the path exists within the structure.
	 */
	public function has($path=NULL) { return $this->traitMetadataHas($path); }
	
	/**
	 * Get a value from the specified metadata path.
	 * 
	 * @param	String $path The metadata structure path.
	 * @param	Mixed $default The metadata value to use if the path does not exist.
	 * @return	Mixed The metadata section from the specified path.
	 */
	public function get($path=NULL, $default=FALSE) {
		if(is_null($path)) {
		// no path, just get the entire metadata array
			$metadata = $this->traitMetadataGetAsReference();
		} else {
		// look at the specified path for the value
			try {
				$metadata = $this->traitArrayGetPathValue($this->traitMetadataGetAsReference(), $path);
			} catch (ArrayPathTraitException $ex) {
			// the path didn't exist so return the default
				$metadata = $default;
			}
		}
		return $metadata;
	}
	
	/**
	 * Increment a metadata value of the path of the metadata structure.
	 * 
	 * @param	String $name The metadata section name.
	 * @param	Number $value The value to increment by.
	 * @param	String $path Optional metadata structure path.
	 * @throws	MetadataTraitException If the metadata section key or the increment value is not numeric.
	 */
	public function increment($name, $value, $path=NULL) { 
		// get the metadata value
		$current_value = $this->getNumeric($name, $path);
		if(!is_numeric($value)) {
		// cant increment non numerics
			throw new MetadataTraitException("Increment value is not numeric", MetadataTraitException::TYPE_MISMATCH);
		}
		// increment the metadata value
		$current_value += $value;
		// increment the metadata value
		$this->add($name, $current_value, TRUE, $path);
	}
	
	/**
	 * Decrement a metadata value of the path of the metadata structure.
	 * 
	 * @param	String $name The metadata section name.
	 * @param	Number $value The value to decrement by.
	 * @param	String $path Optional metadata structure path.
	 * @throws	MetadataTraitException If the metadata section key or the decrement value is not numeric.
	 */
	public function decrement($name, $value, $path=NULL) { 
		// get the metadata value
		$current_value = $this->getNumeric($name, $path);
		if(!is_numeric($value)) {
		// cant decrement non numerics
			throw new MetadataTraitException("Increment value is not numeric", MetadataTraitException::TYPE_MISMATCH);
		}
		// decrement the metadata value
		$current_value -= $value;
		// increment the metadata value
		$this->add($name, $current_value, TRUE, $path);
	}
	
	/**
	 * Get a numeric metadata value of the path of the metadata structure.
	 * 
	 * @param	String $name The metadata section name.
	 * @param	String $path Optional metadata structure path to add the key to.
	 * @return	Number The numeric metadata value from the specified path.
	 * @throws	MetadataTraitException If the metadata section key is not numeric.
	 */
	public function getNumeric($name, $path=NULL) {
		// work out the metadata full path
		$full_path = (is_null($path)) ? $name : "{$path}{$this->traitArrayGetPathSeparator()}{$name}";
		// get the metadata value, or use 0
		$current_value = $this->get($full_path, 0);
		if(!is_numeric($current_value)) {
		// metadata value is not numeric
			throw new MetadataTraitException("Metadata value is not numeric", MetadataTraitException::TYPE_MISMATCH);
		}
		// return the current metadata numeric value
		return $current_value;
	}
	
	/**
	 * Calculate the total value from a metadata section array.
	 * 
	 * @param	String $path Optional metadata structure path to add the key to.
	 * @param	String $name The metadata section name.
	 * @param	Array $exclude Optional list of metadata sub section names to eclude from the total calculation.
	 * @return	Number The numeric total value from the specified metadata path.
	 * @throws	MetadataTraitException If the metadata section is not an Array or the value is not numeric.
	 */
	public function totalise($path=NULL, $name=FALSE, $exclude=FALSE) {
		if(!$exclude) { $exclude = []; }
		if(!is_array($exclude)) {
		// convert the exclude into a list of section key name
			$exclude = [ (string) $exclude ];
		}
		// get the section to calculate the totals from
		$section = $this->get($path);
		if(!$section || !is_array($section)) {
		// can't loop over the section
			throw new MetadataTraitException("Metadata [{$path}] is not Itterable", MetadataTraitException::TYPE_MISMATCH);
		}
		$total = 0;
		// loop the metadata sub section names
		foreach(array_keys($section) as $key) {
			// skip any excluded names
			if(in_array($key, $exclude)) { continue; }
			// get a copy of the root path befor it changes for the specific 
			// numeric metadata value to total
			$_path = $path;
			if($name) {
			// we are looking for a value from the sub array group
			// e.g. "path.key.name"
				// add the key section to the path
				$_path .= "{$this->traitArrayGetPathSeparator()}{$key}";
				// swap the key for the name
				$key = $name;
			} else {
			// we are calculating the total of the section
			// e.g. "path.key"
			// nothing to actually do, just left so the comments help more
			}
			// increment the total with the value from the required metadata
			$total += $this->getNumeric($key, $_path);
		}
		// return the calculated total of the metadata
		return $total;
	}
	
	/**
	 * Get the metadata as a JSON encode string.
	 * 
	 * @param	Integer $json_options The json_encode options.
	 * @return	String The metadata as a JSON encode string.
	 */
	public function toJSON($json_options=JSON_NUMERIC_CHECK) { return $this->traitMetadataGetAsJsonString($json_options); }
		
	/**
	 * Get the metadata as an associative array.
	 * 
	 * @return	Array The metadata as an associative array.
	 */
	public function toArray() { return $this->traitMetadataGetAsArray(); }
}
