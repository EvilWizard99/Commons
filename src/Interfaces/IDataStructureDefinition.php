<?php

namespace EWC\Commons\Interfaces;

/**
 * Interface IDataStructureDefinition
 * 
 * Define the abtraction of the data structure provider.
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 */
interface IDataStructureDefinition {
	
	/**
	 * Get the data structure definition.
	 * 
	 * @return	IDataStructureDefinition The data structure definition.
	 * @throws	MetadataTraitException If the data structure parameter Definition is malformed.
	 */
	public static function &getDefinition();
	
	/**
	 * Check if a data structure parameter name is defined.
	 * 
	 * @param	String $parameter_name The parameter name to check.
	 * @return	Boolean True if the parameter name is defined.
	 */
	public function hasParameter($parameter_name);
	
	/**
	 * Check if a parameter has a sub definition property.
	 * 
	 * @param	String $parameter_name The parameter name to check.
	 * @param	String $property Additional parameter definition property to check.
	 * @return	Boolean True if the sub parameter name is defined within the property.
	 */
	public function hasParameterProperty($parameter_name, $property);
	
	/**
	 * Check if a parameter name is defined as optional.
	 * 
	 * @param	String $parameter_name The parameter name to check.
	 * @return	Boolean True if the parameter name is defined as optional.
	 */
	public function isParameterOptional($parameter_name);
	
	/**
	 * Get a parameter definition property.
	 * 
	 * @param	String $parameter_name The parameter name to get the property of.
	 * @param	String $property The parameter definition property to get.
	 * @param	Mixed $default The parameter definition property value to use if not set.
	 * @return	Mixed The parameter name definition property value.
	 */
	public function getParameterProperty($parameter_name, $property, $default=FALSE);
		
	/**
	 * Get the data structure definition as MetaData.
	 * 
	 * @return	MetaData The data structure definition.
	 */
	public function getStructureDefinition();
	
	/**
	 * Get the data structure defined parameter names.
	 * 
	 * @return	Array The list of defined parameter names.
	 */
	public function getParameterNames();
	
}
