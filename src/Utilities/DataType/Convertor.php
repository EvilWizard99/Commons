<?php

namespace EWC\Commons\Utilities\DataType;

use EWC\Commons\Interfaces\IDataStructureDefinition;
use EWC\Commons\Utilities\DataType\Types;
use EWC\Commons\MetaData;
use DateTime;
use Exception;

/**
 * Static Class Convertor
 * 
 * Convert values to PHP data type objects.
 *
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2018 Evil Wizard Creation.
 * 
 * @uses	IDataStructureDefinition For the data structure parameter definition properties.
 * @uses	Types For constants value use.
 * @uses	MetaData As a viable inflatable named object value.
 * @uses	DateTime As a viable inflatable named object value.
 * @uses	Exception to catch general exceptions.
 */
class Convertor {
	
	/**
	 * Cast a raw value to certain specific types.
	 * 
	 * @param	String $value The value to cast.
	 * @return	Mixed The value type cast.
	 */
	final public static function cast($value) {
		// check what type the value is
		switch(TRUE):
			case static::isBooleanTrue($value):
			// value is equal to boolean TRUE
				return TRUE;
			case static::isBooleanFalse($value):
			// value is equal to boolean FALSE
				return FALSE;
			case is_int($value):
			// value is equal to an integer number
				return (int) $value;
			case is_float($value):
			// value is equal to a floating point number
				return (float) $value;
			case is_numeric($value):
			// value is numeric but a string
				if(filter_var($value, FILTER_VALIDATE_INT) !== FALSE) {
					return (int) $value;
				}
				return (float) $value;
			default:
			// no special case
				// just return the value supplied
				return (string) $value;
		endswitch;
	}
	
	/**
	 * Convert a value from raw to type cast value.
	 * 
	 * @param	IDataStructureDefinition $definition The data structure parameter definition.
	 * @param	Array $parameter_name The name of the parameter in the data structure.
	 * @param	Mixed $value The raw parameter value to convert to type.
	 * @return	Mixed The type cast value.
	 */
	final public static function to(IDataStructureDefinition $definition, $parameter_name, $value) {
		// process and cast the parameter value to the defined data type
		switch($definition->getParameterProperty($parameter_name, "data_type")) {
			case Types::OBJECT_DATETIME :
			// convert the parameter value to DateTime
				// check if the parameter value needs inflating to a DateTime object
				if(!$definition->hasParameterProperty($parameter_name, "not_if") || $value != $definition->getParameterProperty($parameter_name, "not_if")) {
				// parameter value is defined as inflatable to DateTime
					return static::valueDateTime($value);
				}
			case Types::COMPLEX_SERIAL :
			// unserialize the parameter value
				return static::valueUnserialise($value);
			case Types::COMPLEX_JSON :
			// JSON decode the parameter value
				return static::valueJSONDecode($value, $definition->getParameterProperty($parameter_name, "json_options.load", JSON_FORCE_OBJECT));					
			case Types::OBJECT_METADATA :
			// convert the parameter JSON string to MetaData
				return static::valueMetaData($value, $definition->getParameterProperty($parameter_name, "json_options.load", JSON_FORCE_OBJECT));
			case Types::BASIC_INTEGER :
			// type cast the parameter value to an integer
				return (int) $value;
			case Types::BASIC_FLOAT :
			// type cast the parameter value to an float
				return (float) $value;
			case Types::BASIC_STRING :
			// type cast the parameter value to an string
				return (string) $value;
			case Types::BASIC_BOOLEAN :
			// type cast the parameter  value to an string
				return (bool) $value;
			case Types::COMPLEX_ENUM :
			// type cast the parameter value to an string
				// get the valid ENUM options defined
				$enum_options = $definition->getParameterProperty($parameter_name, "options", []);
				if(!in_array($value, $enum_options)) {
				// the value is not a valid ENUM option
					// use the definition ENUM default option instead
					$value = $definition->getParameterProperty($parameter_name, "default_value");
				}
				return (string) $value;
		}
		// return the unchanged value as it did not match a conversion type or criteria
		return $value;
	}
	
	/**
	 * Convert a typecast value back to raw value.
	 * 
	 * @param	IDataStructureDefinition $definition The data structure parameter definition.
	 * @param	Array $parameter_name The name of the parameter in the data structure.
	 * @param	Mixed $value The raw parameter value to convert to type.
	 * @return	Mixed The type cast value.
	 */
	final public static function from(IDataStructureDefinition $definition, $parameter_name, $value) {
		switch($definition->getParameterProperty($parameter_name, "data_type")) {
			case Types::OBJECT_DATETIME :
			// convert the parameter value from DateTime
				$format = $definition->getParameterProperty($parameter_name, "format", "Y-m-d H:i:s");
				if($format == "TimeStamp") {
				// set the format to UTC timestamp
					$format = 'U';
				}
				if($value && ($value instanceof DateTime)) {
				// parameter only wants to be a DateTime if not a specific value
					return $value->format($format);
				}
			break;
			case Types::COMPLEX_SERIAL :
			// serialize the parameter value
				return static::valueSerialise($value);
			case Types::COMPLEX_JSON :
			// JSON encode the parameter value
				return static::valueJSONEncode($value, $definition->getParameterProperty($parameter_name, "json_options.save", JSON_FORCE_OBJECT));					
			case Types::OBJECT_METADATA :
			// type cast the MetaData object back to a JSON string
				return (string) $value->toJSON($definition->getParameterProperty($parameter_name, "json_options.save", JSON_FORCE_OBJECT));
			case Types::BASIC_INTEGER :
			// type cast the parameter value to an integer
				return (int) $value;
			case Types::BASIC_FLOAT :
			// type cast the parameter value to an float
				return (float) $value;
			case Types::BASIC_STRING :
			// type cast the parameter value to an string
				return (string) $value;
			case Types::COMPLEX_ENUM :
			// type cast the parameter value to an string
				// @todo ensure the value is in the field definition ENUM options
				return (string) $value;
		}
		// return the unchanged value as it did not match a conversion type or criteria
		return $value;
	}
	
	/**
	 * Get a DateTime version of the value.
	 * 
	 * @param	Mixed $value The value to convert to DateTime.
	 * @return	DateTime The value as a date time.
	 */
	public static function valueDateTime($value) {
		try {
			if(is_numeric($value)) {
			// time is a potential timestamp
				$var = new DateTime();
				$var->setTimestamp($value);
			} else {
			// time is likely to be a string time
				$var = new DateTime($value);
			}
			return $var;
		} catch (Exception $ex) {
		// the supplied value failed to create a timestamp
			return $value;
		}
	}
	
	/**
	 * Get the fields DateTime value to the fields definition format.
	 * 
	 * @param	DateTime $value The field DateTime value.
	 * @param	String $format The DateTime format pattern string.
	 * @return	String The formated date time value.
	 */
	public static function valueDateTimeFormat(DateTime $value, $format) { return $value->format($format); }
	
	/**
	 * Get MetaData version of the value.
	 * 
	 * @param	Mixed $value The value to convert to MetaData.
	 * @param	Integer $options Optional bitmask to pass to the json_decode method.
	 * @return	Mixed The JSON decoded version of the value.
	 * @throws	MetadataTraitException If the metadata has already been set, the source is not an array or a valid JSON String.
	 */
	public static function valueMetaData($value, $options=JSON_FORCE_OBJECT) {
		// convert the value to metadata
		return MetaData::create($value, $options);
	}
	
	/**
	 * Get the fields JSON encoded version of the value.
	 * 
	 * @param	Mixed $value The value to JSON encode.
	 * @param	Integer $options Optional bitmask to pass to the json_encode method.
	 * @return	Mixed The JSON encoded version of the value.
	 */
	public static function valueJSONEncode($value, $options=JSON_NUMERIC_CHECK) {
		// decode the RAW JSON string
		$encoded = json_encode($value, $options);
		// verify the encode process worked or return the RAW value
		return ($encoded !== FALSE) ? $encoded : $value;
	}
	
	/**
	 * Get JSON decoded version of the value.
	 * 
	 * @param	Mixed $value The value to JSON decode.
	 * @param	Integer $options Optional bitmask to pass to the json_decode method.
	 * @return	Mixed The JSON decoded version of the value.
	 */
	public static function valueJSONDecode($value, $options=JSON_FORCE_OBJECT) {
		// decode the RAW JSON string
		$decoded = json_decode($value, TRUE, 512, $options);
		// verify the decode process worked or return the RAW value
		return ($decoded !== FALSE) ? $decoded : $value;
	}
	
	/**
	 * Get the fields serialised value.
	 * 
	 * @param	Mixed $value The value to serialised.
	 * @return	Mixed The serialised version of the value.
	 */
	public static function valueSerialise($value) {
		$serialised = serialize($value);
		if($serialised) {
		// the value has been serialised
			$value = $serialised;
		}
		// return the processed value
		return $value;
	}
	
	/**
	 * Get an un serialised version of the value.
	 * 
	 * @param	Mixed $value The value to un serialise.
	 * @return	Mixed The un serialised version of the value.
	 */
	public static function valueUnserialise($value) {
		// make sure the value is a serialised string value
		if(is_string($value)) {
			$unserialised = unserialize($value);
			// check that the serialized value wasn't just FALSE serialized
			if($value == serialize(FALSE) || $unserialised !== FALSE) {
			// the value has been unserialised
				$value = $unserialised;
			}
		}
		// return the processed value
		return $value;
	}
	
	/**
	 * Test the value for boolean TRUE equal values.
	 * 
	 * @param	Mixed $value The value to tested for boolean TRUE.
	 * @return	Boolean TRUE if the value equates to a boolean TRUE value.
	 */
	public static function isBooleanTrue($value) { return (in_array(strtoupper($value), ["TRUE", "ON", "YES", "1"])); }
	
	/**
	 * Test the value for boolean FALSE equal values.
	 * 
	 * @param	Mixed $value The value to tested for boolean FALSE.
	 * @return	Boolean TRUE if the value equates to a boolean FALSE value.
	 */
	public static function isBooleanFalse($value) { return (in_array(strtoupper($value), ["FALSE", "OFF", "NO", "0"])); }
	
}
