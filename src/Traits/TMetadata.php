<?php

namespace EWC\Commons\Traits;

use EWC\Commons\Exceptions\ArrayPathTraitException;
use EWC\Commons\Exceptions\MetadataTraitException;

/**
 * Trait TMetadata
 * 
 * Allow classes to utilise metadata mechanics.
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
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2018 Evil Wizard Creation.
 * 
 * @uses	TArrayPath To allow ArrayPath access to the metadata.
 * @uses	EWC\Core\Exceptions\ArrayPathTraitException Catches and throws named exceptions.
 * @uses	EWC\Core\Exceptions\MetadataTraitException Catches and throws named exceptions.
 */
trait TMetadata {
	
	/**
	 * Includes the following TArrayPath trait methods for use.
	 * 
	 * Provides no public access methods.
	 * 
	 * Provides the following protected methods for use.
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
	use TArrayPath;
	
	/**
	 * @var	Array The metadata.
	 */
	private $_trait_metadata = NULL;
	
	/**
	 * Set metadata from a JSON encoded source string.
	 * 
	 * @param	Mixed $source Either a JSON encoded source string or an Array.
	 * @throws	MetadataTraitException If the metadata has already been set, the source is not an array or a valid JSON String.
	 */
	protected function traitMetadataSet($source) {
		if(!is_null($this->_trait_metadata)) {
		// metadata has already been set
			throw MetadataTraitException::withMetadataAlreadyDefined();
		}
		if(is_string($source)) {
		// source should be a JSON encoded string
			$source = json_decode($source, TRUE);
			if(!$source) {
			// the JSON string was invalid
				throw MetadataTraitException::withBadSourceJSON();
			}
		}
		if(!is_array($source)) {
		// source is not an array
			throw MetadataTraitException::withBadSourceArray();
		}
		$this->_trait_metadata = $source;
	}
	
	/**
	 * Unset trait metadata.
	 * 
	 * @param	String $path Optional metadata section path to unset.
	 * @throws	MetadataTraitException If section path is not in the metadata structure.
	 */
	protected function traitMetadataUnset($path=NULL) {
		if(is_null($path)) {
		// unset the whole metadata array
			$this->_trait_metadata = NULL;
		} else {
		// unset the specific metadata section
			$this->traitMetadataUnsetSection($path);
		}
	}
	
	/**
	 * Check if a metadata path exists within the metadata structure.
	 * 
	 * @param	String $path The metadata structure path.
	 * @return	Boolean TRUE if the path exists within the structure.
	 */
	protected function traitMetadataHas($path=NULL) { return $this->traitArrayHasPath($this->_trait_metadata, $path); }
	
	/**
	 * Get a value from the specified metadata path.
	 * 
	 * @param	String $path The metadata structure path.
	 * @return	Mixed The metadata section from the specified path.
	 * @throws	ArrayPathTraitException If the metadata path does not exist.
	 */
	protected function traitMetadataGetValue($path) { return $this->traitArrayGetPathValue($this->_trait_metadata, $path); }
	
	/**
	 * Get the metadata as a JSON encode string.
	 * 
	 * @param	Integer $json_options The json_encode options.
	 * @return	String The metadata as a JSON encode string.
	 */
	protected function traitMetadataGetAsJsonString($json_options) { return json_encode($this->_trait_metadata, $json_options); }
	
	/**
	 * Get the metadata as an associative array.
	 * 
	 * @return	Array The metadata as an associative array.
	 */
	protected function traitMetadataGetAsArray() { return $this->_trait_metadata; }
	
	/**
	 * Get the metadata as an associative array.
	 * 
	 * @return	Array The metadata as an associative array.
	 */
	protected function &traitMetadataGetAsReference() { return $this->_trait_metadata; }
	
	/**
	 * Add a metadata key value pair.
	 * 
	 * @param	String $name The metadata section name.
	 * @param	Mixed $data The metadata data value.
	 * @param	String $path The metadata structure path to add the key to.
	 * @param	String $update Flag to indicate the value should be updated is exists.
	 * @throws	MetadataTraitException If the metadata path exist but not updating.
	 */
	protected function traitMetadataAddValue($name, $data, $path=NULL, $update=FALSE) {
		if(is_null($path)) {
		// just add the data the root metadata structure
			if($this->traitMetadataHas($name) && !$update) {
			// the metadata section already exists but not updating
				throw MetadataTraitException::withMetadataSectionAlreadySet($path, $name);
			}
			$this->traitMetadataAddToRoot($name, $data, $update);
		} else {
		// add the metatdata the specified path section
			if($this->traitMetadataHas("{$path}{$this->traitArrayGetPathSeparator()}{$name}") && !$update) {
			// the metadata section already exists but not updating
				throw MetadataTraitException::withMetadataSectionAlreadySet($path, $name);
			}
			try {
				// add the metadata value to the requested metadata path string
				$this->traitArrayAddSectionToPathByValue($this->_trait_metadata, $path, $name, $data, TRUE);
			} catch (ArrayPathTraitException $ex) {
			// metadata section doesn't exist
				throw new MetadataTraitException("No such metadata at [{$path}]", MetadataTraitException::ARRAY_PATH_NOT_FOUND, $ex);
			}
		}
	}
	
	/**
	 * Set a custom metadata path structure separator character sequence.
	 * 
	 * WARNING This can have unforseen consequences!! Use with caution.
	 * 
	 * @param	String $new_path_separator The new path separator character sequesnce.
	 */
	protected function traitMetadataSetPathSeparator($new_path_separator) { $this->traitArraySetPathSeparator($new_path_separator); }
	
	/**
	 * Get the current metadata path structure separator character sequence.
	 * 
	 * @return	String The current path separator character sequesnce.
	 */
	protected function traitMetadataGetPathSeparator() { $this->traitArrayGetPathSeparator(); }
	
	/**
	 * Dump out the trait metadata array for inspection.
	 */
	protected function traitMetadataDebug() {
		print_pre($this->_trait_metadata);
	}
	
	/**
	 * Add a metadata key value pair to the root of the metadata structure.
	 * 
	 * @param	String $name The metadata section name.
	 * @param	Mixed $data The metadata data value.
	 * @param	String $update Flag to indicate the value should be updated is exists.
	 * @throws	MetadataTraitException If the metadata section key exist but not updating.
	 */
	private function traitMetadataAddToRoot($name, $data, $update) {
		// make sure the metadata is an array before trying to add data to it
		if(!is_array($this->_trait_metadata)) { $this->_trait_metadata = []; }
		if(array_key_exists($name, $this->_trait_metadata) && !$update) {
		// the metadata section already exists but not updating
			throw MetadataTraitException::withMetadataSectionAlreadySet('', $name);
		}
		// set the metadata key value
		$this->_trait_metadata[$name] = $data;
	}
	
	/**
	 * Add a reference to the value data to root of the metadata section.
	 * 
	 * @param	String $name The metadata section name.
	 * @param	Mixed $data Reference to the metadata data value.
	 * @param	String $update Flag to indicate the value should be updated is exists.
	 * @throws	MetadataTraitException If the metadata section key exist but not updating.
	 */
	private function traitAddMetadataToRootByReference($name, &$data, $update) {
		// make sure the metadata is an array before trying to add data to it
		if(!is_array($this->_trait_metadata)) { $this->_trait_metadata = []; }
		if(array_key_exists($name, $this->_trait_metadata) && !$update) {
		// the metadata section already exists but not updating
			throw MetadataTraitException::withMetadataSectionAlreadySet('', $name);
		}
		// set the metadata key value
		$this->_trait_metadata[$name] = &$data;
	}
	
	/**
	 * Unset a metadata section.
	 * 
	 * @param	String $path The metadata section path to unset.
	 * @throws	MetadataTraitException If section path is not in the metadata structure.
	 */
	private function traitMetadataUnsetSection($path) {
		// get the metadata section parent array
		$parent_section = $this->traitMetadataGetParentSection($path);
		// unset the metadata path section
		unset($parent_section["data"][$parent_section["name"]]);
	}
	
	/**
	 * Get the parent section of the metadata.
	 * 
	 * The return array provides the following keys.
	 * <code>
	 *		return [
	 *			"path"		=> String,
	 *			"name"		=> String,
	 *			"data"		=> Mixed
	 *		];
	 * </code>
	 * 
	 * @param	String $path The metadata section path to get the parent section for.
	 * @throws	MetadataTraitException If section path is not in the metadata structure.
	 */
	private function traitMetadataGetParentSection($path) {
		// get the list of array key sections
		$path_parts = explode($this->_trait_metadata_path_separator, $path);
		// get the last part of the path
		$section = array_pop($path_parts);
		// rebuild the parent path
		$parent_path = implode($this->_trait_metadata_path_separator, $path_parts);
		try {
			// check to see if the metadata structure has the parent path section
			$parent_section = $this->traitArrayGetSectionFromPath($this->_trait_metadata, $parent_path);
			if(!is_array($parent_section) && !array_key_exists($section, $parent_section)) {
			// the section to unset doesn't exist
				throw MetadataTraitException::withPathNotFound($parent_path, $section);
			}
		} catch (MetadataTraitException $ex) {
		// the parent path is not in the metadata structure
			throw new MetadataTraitException($ex->getMessage(), $ex->getCode(), $ex);
		}
		// return details about the parent section and the specific section
		return [
			"path"	=> $parent_path,
			"name"	=> $section,
			"data"	=> &$parent_section
		];
	}
	
}
