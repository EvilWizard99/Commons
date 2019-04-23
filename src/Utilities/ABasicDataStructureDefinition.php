<?php

namespace EWC\Commons\Utilities;

use EWC\Commons\Interfaces\IDataStructureDefinition;
use EWC\Commons\Utilities\DataType\Types;
use EWC\Commons\Utilities\MetaData;
use EWC\Commons\Exceptions\MetadataTraitException;

/**
 * Abstract Class ABasicDataStructureDefinition
 * 
 * Define basic abstract base for defining array structures and ensuring the correct
 * typecasting of the value and default values if missing.
 *
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2018 Evil Wizard Creation.
 * 
 * @uses	IDataStructureDefinition For concrete implimentation of abstract interface.
 * @uses	Types For constants value use.
 * @uses	MetaData To model the parameters Definition structure.
 * @uses	MetadataTraitException Catches and throws named exceptions.
 */
abstract class ABasicDataStructureDefinition implements IDataStructureDefinition {
	
	/**
	 * @var	MetaData The data structure parameter names and definition properties.
	 */
	protected $definition;
	
	/**
	 * @var	Array The data structure parameter names and definition properties.
	 */
	protected $raw_definition;
	
	/**
	 * @var	Array The data structure parameter names.
	 */
	protected $parameters;
	
	/**
	 * @var	Array The optional data structure parameter names.
	 */
	protected $optional_parameters = [];
	
	/**
	 * @var	Array The common basic parameter definition values required for each parameter.
	 * 
	 * The array ensures the parameter definition has the basic requirements.
	 * <code>
	 *		$raw_definition["parameter_name"] = [
	 *			"data_type"			=> String, (the data type
	 *			"default_value"		=> Mixed (The parameter default value if not set, defaults to NULL),
	 *			"optional"			=> Boolean (The parameter value is optional, defaults to FALSE)
	 *		];
	 * </code>
	 */
	private $common_definition_parameters = [
		"data_type"		=> Types::COMPLEX_MIXED,
		"default_value"	=> NULL,
		"optional"		=> FALSE,
		
	];
	
	/**
	 * ABasicDataStructureDefinition constructor.
	 * 
	 * @param	Array $definition The data structure parameter names definition properties.
	 */
	protected function __construct($definition) {
		// make sure all definitions have the basic required definition parameters
		foreach($definition as $parameter_name => $parameter_definition) {
			$this->raw_definition[$parameter_name] = array_merge($this->common_definition_parameters, $parameter_definition);
		}
	}
	
	/**
	 * Get the data structure definition.
	 * 
	 * @return	ABasicDataStructureDefinition The data structure definition.
	 * @throws	MetadataTraitException If the data structure parameter Definition is malformed.
	 */
	public static function &getDefinition() {
		$o = new static();
		$o->initialise();
		return $o;
	}
	
	/**
	 * Check if a data structure parameter name is defined.
	 * 
	 * @param	String $parameter_name The parameter name to check.
	 * @return	Boolean True if the parameter name is defined.
	 */
	public function hasParameter($parameter_name) { return in_array($parameter_name, $this->parameters); }
	
	/**
	 * Check if a parameter has a definition property.
	 * 
	 * @param	String $parameter_name The parameter name to check.
	 * @param	String $property Additional parameter definition property to check.
	 * @return	Boolean True if the parameter name is defined.
	 */
	public function hasParameterProperty($parameter_name, $property) { return $this->definition->has("{$parameter_name}.{$property}"); }
	
	/**
	 * Check if a parameter name is defined as optional.
	 * 
	 * @param	String $parameter_name The parameter name to check.
	 * @return	Boolean True if the parameter name is defined as optional.
	 */
	public function isParameterOptional($parameter_name) { return in_array($parameter_name, $this->optional_parameters); }
	
	/**
	 * Get a parameter definition property.
	 * 
	 * @param	String $parameter_name The parameter name to get the property of.
	 * @param	String $property The parameter definition property to get.
	 * @param	Mixed $default The parameter definition property value to use if not set.
	 * @return	Mixed The parameter name definition property value.
	 */
	public function getParameterProperty($parameter_name, $property, $default=FALSE) { return $this->definition->get("{$parameter_name}.{$property}", $default); }
		
	/**
	 * Get the data structure definition as MetaData.
	 * 
	 * @return	MetaData The data structure definition.
	 */
	public function getStructureDefinition() { return $this->definition; }
	
	/**
	 * Get the data structure defined parameter names.
	 * 
	 * @return	Array The list of defined parameter names.
	 */
	public function getParameterNames() { return $this->parameters; }
	
	/**
	 * Perform addition parameter definition checks.
	 * 
	 * Allow the extending classes to hook into the definition initiate process.
	 * 
	 * @param	String $parameter_name The parameter name to check.
	 * @param	Array $definition The parameter name data structure definition.
	 */
	abstract protected function onParameterInitialise($parameter_name, $definition);
	
	/**
	 * Initiate the data structure parameter definitions.
	 * 
	 * @throws	MetadataTraitException If the data structure parameter Definition is malformed.
	 */
	final protected function initialise() {
		// set the parameter names
		$this->parameters = array_keys($this->raw_definition);
		foreach($this->raw_definition as $parameter => &$definition) {
			// keep track of optional parameter names
			if(array_key_exists("optional", $definition) && $definition["optional"]) { 
			// parameter is defined as optional
				$this->optional_parameters[] = $parameter;
			}
			// call the on listeners
			$this->onParameterInitialise($parameter, $definition);
		}
		// set the parameter definitions for metadata access
		$this->definition = MetaData::create($this->raw_definition);
		
	}
}
