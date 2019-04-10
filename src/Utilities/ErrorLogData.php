<?php

namespace EWC\Commons\Utilities;

/**
 * Class ErrorLogData
 * 
 * Act as wrapper to log data to the log handler stream.
 *
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2017 Evil Wizard Creation.
 */
class ErrorLogData {
	
	/**
	 * @var	Array The data to be logged.
	 */
	protected $data = [];
	
	/**
	 * @var	Array The data to be logged.
	 */
	protected $data_name;
	
	/**
	 * @var	String The file path to be used for the data.
	 */
	protected $destination_file = NULL;
	
	/**
	 * ErrorLogData constructor.
	 * 
	 * @param	String $data_name A header name for the data being logged.
	 * @param	Splat $data List of data to be logged.
	 */
	public function __construct($data_name, $data) {
		$this->data = $data;
		$this->data_name = $data_name;
	}
	
	/**
	 * @return	String Details of the Payment Gateway Errors
	 */
	public function __toString() {
		$_ = '';
		$_ .= "{$this->data_name}\n";
		$_ .= $this->traverseDataArray($this->data, 0);
		return $_;
	}
	
	/**
	 * Recursively traverse the data array and build a string of the data structure and value.
	 * 
	 * @param	Array $a The array source to traverse and build the data structure from.
	 * @param	Integer $indent_level The level of indentation to use.
	 * @return	String $indent_level The data structure and values as a string.
	 */
	protected function traverseDataArray($a, $indent_level) {
		$_ = '';
		$indent = $this->getIndent($indent_level);
		foreach($a as $k => $v) {
			if(is_string($k)) {
			// array key is a string
				// add the array key name to the data structure
				$_ .= "{$indent}{$k} => ";
				$_ .= $this->extractDataSection($v, $indent_level, TRUE);
			} else {
			// array key is numeric
				$_ .= $this->extractDataSection($v, $indent_level);
			}
		}
		return $_;
	}
	
	/**
	 * Extract the string version of the data section.
	 * 
	 * @param	Mixed $v The data / data section.
	 * @param	Integer $indent_level The level of indentation to use.
	 * @param	Boolean $no_indent Flag to indicate the data line has already been indented.
	 * @return	String The data structure and values as a string.
	 */
	protected function extractDataSection($v, $indent_level, $no_indent=FALSE) {
		$_ = '';
		$indent = $this->getIndent($indent_level);
		if(is_array($v)) {
		// data section is an array
			// add the array identifier wrapper and traverse the data array
			$_ .= "Array (\n";
			$indent_level++;
			$_ .= $this->traverseDataArray($v, $indent_level);
			$_ .= "{$indent})\n";
		} else {
		// data section is not an array
			// @todo check for object, resource and other types to convert to string correctly
			if(is_resource($v)) {
			// data is a resource handle
				$_ .= get_resource_type($v) . "\n";
			} else if(is_object($v)) {
			// data is an object
				$_ .= get_class($v) . " {\n";
				if(!method_exists($v, "__toString")) {
				// object doesn't have a to string method
					// use json encode / decode to convert to an assoc array
					$v = json_decode(json_encode($v), TRUE);
					$indent_level++;
					$_ .= $this->traverseDataArray($v, $indent_level);
				} else {
				// object supports being converted to string
					// determine if the indentation is needed
					$_ .= ($no_indent) ? "{$v}\n" : "{$indent}{$v}\n";
				}
				$_ .= "{$indent}}\n";
			} else {
			// data does not need special handling
				// determine if the indentation is needed
				$_ .= ($no_indent) ? "{$v}\n" : "{$indent}{$v}\n";
			}
		}
		return $_;
	}
	
	/**
	 * Determine the indent level of spaces to use.
	 * 
	 * @param	Integer $level The level of indentation to use.
	 * @return	String The level of indentation using spaces.
	 */
	protected function getIndent($level) { return str_pad("", ($level * 4), "    ", STR_PAD_LEFT); }
}
