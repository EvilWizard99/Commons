<?php

namespace EWC\Commons\Libraries;

/**
 * Mock the standard mkdir() function for testing purposes.
 */
function mkdir($pathname, $mode = 0777, $recursive = false, $context = null) {
	return "Stubbed mkdir of [{$pathname}] with mode [{$mode}]";
}

/**
 * Mock the standard file_put_contents() function for testing purposes.
 */
function file_put_contents($filename, $data, $flags = 0, $context = null) {
	
}