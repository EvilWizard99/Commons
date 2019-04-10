<?php

namespace EWC\Commons\Traits;

use EWC\Commons\Exceptions\ArrayPathTraitException;

/**
 * Trait TArrayPath
 * 
 * Allow destructive and non destructive xPath style access to multidimensional arrays.
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
 *
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2018 Evil Wizard Creation.
 * 
 * @uses	ArrayPathTraitException Catches and throws named exceptions.
 */
trait TArrayPath {
	
	/**
	 * @var	String The character to use to separate array section path string.
	 */
	private $_trait_array_xpath_separator = '.';
	
	/**
	 * Check if a path string exists within the array structure.
	 * 
	 * @param	Array $source The array source to check the path string validity.
	 * @param	String $path The array structure path string.
	 * @return	Boolean TRUE if the path string exists within the structure.
	 */
	protected function traitArrayHasPath($source, $path=NULL) {
		try {
			// check to see if the array structure has the path section
			$this->traitArrayGetSectionFromPath($source, $path);
			// the array has the path section
			$has_metadata = TRUE;
		} catch (ArrayPathTraitException $ex) {
		// the path is not in the array structure
			$has_metadata = FALSE;
		} finally {
			// return if the array structure has the path or not
			return $has_metadata;
		}			
	}
	
	/**
	 * Get a value from the specified array path string.
	 * 
	 * @param	Array $source Reference to the source array to select the array path of.
	 * @param	String $path The array structure path string.
	 * @return	Mixed The array section from the specified path string.
	 * @throws	ArrayPathTraitException If the array path string does not exist.
	 */
	protected function traitArrayGetPathValue(&$source, $path) { return $this->traitArrayGetSectionFromPath($source, $path); }
	
	/**
	 * Get a value from the specified array path string.
	 * 
	 * @param	Array $source Reference to the source array to select the array path of.
	 * @param	String $path The array structure path string.
	 * @return	Mixed Reference to the array section from the specified path string.
	 * @throws	ArrayPathTraitException If the array path string does not exist.
	 */
	protected function &traitArrayGetPathReference(&$source, $path) { return $this->traitArrayGetSectionFromPathByReference($source, $path); }
	
	/**
	 * Set a custom array path string structure separator character sequence.
	 * 
	 * WARNING This can have unforseen consequences!! Use with caution.
	 * 
	 * @param	String $new_path_separator The new path string separator character sequence.
	 */
	protected function traitArraySetPathSeparator($new_path_separator) { $this->_trait_array_xpath_separator = (string) $new_path_separator; }
	
	/**
	 * Get the current array path string structure separator character sequence.
	 * 
	 * @return	String The current path string separator character sequence.
	 */
	protected function traitArrayGetPathSeparator() { return $this->_trait_array_xpath_separator; }
	
	/**
	 * Get the array value from the specified path string structure within the source array.
	 * 
	 * @param	Array $source The source array to select the array path string of.
	 * @param	String $path The array structure path.
	 * @return	Mixed Reference to the array section data from the specified path.
	 * @throws	ArrayPathTraitException If the array path string does not exist.
	 */
	protected function traitArrayGetSectionFromPath(&$source, $path) {
		// @todo look at doing ...
		//$current_pointer = $this->traitArrayGetSectionFromPathByReference($source, $path);
		// get the path strng array parts
		$path_parts = explode($this->traitArrayGetPathSeparator(), $path);
		// start at the begining of the array structure reference
		$current_pointer = &$source;
		// loop down the path string to get the specific array key value
		foreach($path_parts as $path_section) {
			if(is_array($current_pointer) && array_key_exists($path_section, $current_pointer)) {
			// the key section exists in the array subsection
				// set the current pointer reference to the subsection of the array
				$current_pointer = &$current_pointer[$path_section];
			} else {
			// the array key path section is not in the array structure
				throw ArrayPathTraitException::withPathNotFound($path, $path_section);
			}
		}
		// return the requested array section data
		return $current_pointer;
	}
	
	/**
	 * Add data to the array at the specified structure path string.
	 * 
	 * @param	Array $source Reference to the source array to select the array path string of.
	 * @param	String $path The array structure path string.
	 * @param	String $name The array key name to add to the array structure path string.
	 * @param	Mixed $data The actual array path string value.
	 * @param	Boolean $create Flag to indicate the entire path string section should be created in needed to add the value.
	 * @throws	ArrayPathTraitException If the array path string does not exist.
	 */
	protected function traitArrayAddSectionToPathByValue(&$source, $path, $name, $data, $create=FALSE) {
		// @todo look at doing ...
		//$current_pointer = $this->traitGetArraySectionFromPathByReference($source, $path);
		$path_parts = explode($this->traitArrayGetPathSeparator(), $path);
		// start at the begining of the array structure
		$current_pointer = &$source;
		// loop down the path to get the specific metadata key value
		foreach($path_parts as $path_section) {
			if(is_array($current_pointer) && array_key_exists($path_section, $current_pointer)) {
			// the key section exists in the array subsection
				// set the current pointer to the subsection of the metadata
				$current_pointer = &$current_pointer[$path_section];
			} else {
			// the array key path section is not in the array structure
				// either create an array at the path section or throw a path not found exception
				$this->sectionNotFound($current_pointer, $path, $path_section, $create);
			}
		}
		// set the array key value
		$current_pointer[$name] = $data;
	}
	
	/**
	 * Get the reference pointer to the specified structure path within the source array.
	 * 
	 * @param	Array $source Reference to the source array to select the array path of.
	 * @param	String $path The array structure path.
	 * @return	Mixed Reference to the array section data from the specified path.
	 * @throws	ArrayPathTraitException If the array structure path does not exist.
	 */
	protected function &traitArrayGetSectionFromPathByReference(&$source, $path) {
		$path_parts = explode($this->traitArrayGetPathSeparator(), $path);
		// start at the begining of the array structure
		$current_pointer = &$source;
		// loop throu the path string parts to get the specific array key value
		foreach($path_parts as $idx => $path_section) {
			if(is_array($current_pointer) && array_key_exists($path_section, $current_pointer)) {
			// the key section exists in the array subsection
				// set the current pointer to the subsection of the array
				$current_pointer = &$current_pointer[$path_section];
			} else {
			// the array key path is not in the array structure
				throw ArrayPathTraitException::withPathNotFound($path, $path_section);
			}
		}
		// return the requested array data
		return $current_pointer;
	}
	
	/**
	 * Add data to the array at the specified structure path.
	 * 
	 * @param	Array $source Reference to the source array to select the array path of.
	 * @param	String $path The array structure path string.
	 * @param	String $name The array key name to add to the array structure path string.
	 * @param	Mixed $data Reference to the actual array path string value.
	 * @param	Boolean $create Flag to indicate the entire path string section should be created in needed to add the value.
	 * @throws	ArrayPathTraitException If the array path does not exist.
	 */
	protected function traitArrayAddSectionToPathByReference(&$source, $path, $name, &$data, $create=FALSE) {
		// @todo look at doing ...
		//$current_pointer = $this->traitGetArraySectionFromPathByReference($source, $path);
		$path_parts = explode($this->traitArrayGetPathSeparator(), $path);
		// start at the begining of the metadata structure
		$current_pointer = &$source;
		$section_path = '';
		// loop down the path to get the specific metadata key value
		foreach($path_parts as $path_section) {
			$section_path .= (($section_path) ? $this->traitArrayGetPathSeparator() : '') . $path_section;
			if(is_array($current_pointer) && array_key_exists($path_section, $current_pointer)) {
			// the key section exists in the metadata subsection
				// set the current pointer to the subsection of the metadata
				$current_pointer = &$current_pointer[$path_section];
			} else {
			// the metadata key path is not in the metadata structure
				// either create an array at the path section or throw a path not found exception
				if($create) {
					$current_pointer[$path_section] = [];
					$current_pointer = &$current_pointer[$path_section];
				}
			}
		}
		// set the array key to a reference of the data value
		$current_pointer[$name] = &$data;
	}
	
	/**
	 * Add data to the array at the specified structure path string.
	 * 
	 * @param	Array $source Reference to the source parent section of the array path string.
	 * @param	String $path The full array structure path string.
	 * @param	String $path_section The section path string.
	 * @param	Mixed $data The actual array path string value.
	 * @param	Boolean $create Flag to indicate the entire path string section should be created in needed to add the value.
	 * @throws	ArrayPathTraitException If the array path string does not exist.
	 */
	private function &sectionNotFound(&$source, $path, $path_section, $create) {
		if($create) {
		// create the array key in the section
			// value has to be a refernce
			$value_holder = [];
			// add the current setion path to the current array pointer
			$this->traitArrayAddSectionToPathByReference($source, $path, $path_section, $value_holder);
		} else {
		// not creating and not found so throw the exception
			throw ArrayPathTraitException::withPathNotFound($path, $path_section);
		}		
	}
	
}
