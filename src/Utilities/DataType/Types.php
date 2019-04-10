<?php

namespace EWC\Commons\Utilities\DataType;

/**
 * Static Class Types
 * 
 * Define data types for casting.
 *
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2018 Evil Wizard Creation.
 */
class Types {
	
	/**
	 * @var	String The string representation of the basic PHP STRING data type.
	 */
	const BASIC_STRING = "String";
	
	/**
	 * @var	String The string representation of the basic PHP INTEGER data type.
	 */
	const BASIC_INTEGER = "Integer";
	
	/**
	 * @var	String The string representation of the basic PHP FLOAT data type.
	 */
	const BASIC_FLOAT = "Float";
	
	/**
	 * @var	String The string representation of the basic PHP BOOLEAN data type.
	 */
	const BASIC_BOOLEAN = "Boolean";
	
	/**
	 * @var	String The string representation of the data type DateTime PHP class object.
	 */
	const OBJECT_DATETIME = "DateTime";
	
	/**
	 * @var	String The string representation of the data type MetaData PHP class object.
	 */
	const OBJECT_METADATA = "MetaData";
	
	/**
	 * @var	String The string representation of a complex MIXED data type.
	 */
	const COMPLEX_MIXED = "Mixed";
	
	/**
	 * @var	String The string representation of a complex SERIALIZED data type.
	 */
	const COMPLEX_SERIAL = "Serial";
	
	/**
	 * @var	String The string representation of a complex JSON data type.
	 */
	const COMPLEX_JSON = "JSON";
	
	/**
	 * @var	String The string representation of a complex ENUM data type.
	 */
	const COMPLEX_ENUM = "ENUM";
	
	/**
	 * @var	String The string representation of an array input filter data type.
	 */
	const FILTER_ARRAY = "Array";
	
	/**
	 * @var	String The string representation of an email input filter data type.
	 */
	const FILTER_EMAIL = "Email";
	
	/**
	 * @var	String The string representation of a database auto increment data type.
	 */
	const DB_AUTO_INC = "AutoIncrement";
	
}
