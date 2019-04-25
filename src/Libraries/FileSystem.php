<?php

namespace EWC\Commons\Libraries;

use EWC\Commons\Exceptions\FileSystemException;

/**
 * Class FileSystem
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 * 
 * @uses	FileSystemException To indicate errors.
 */
class FileSystem {
	
	/*
	 * @var	Octal The octal value for Read, Write, Execute attributes on files.
	 */
	const CHMOD_RWX = 0775;
	
	/*
	 * @var FileSystem The single instance of the Database.
	 */
	private static $ourInstance = NULL;
	
	/*
	 * @var	Array Special file names that are not actual files.
	 */
	protected static $notFiles = [ '.', ".." ];
	
	/**
	 * This object should be treated as a singleton instance.
	 * 
	 * @return	FileSystem The FileSystem object.
	 */
    public static function getInstance() {
		if(is_null(static::$ourInstance)) {
		// initialise the object
			static::$ourInstance = new static();
		}
		return static::$ourInstance;
	}
	
	/**
	 * Check to see if a path exists on the file system.
	 *
	 * @param	String $path The file system path to check.
	 * @return	Boolean TRUE if the path exists.
	 */
	public static function exists($path) {
		// assume it doesn't until verified and make sure it's not NULL
		$exists = FALSE;
		if(!is_null($path)) {
			// clear out any old cached details
			clearstatcache();
			// make sure the file is readable
			if (is_readable($path)) {
				$exists = TRUE;
			}
		}
		return $exists;
 	}
	
	/**
	 * Check to see if a file exists on the file system.
	 *
	 * @param	String $file The file path to check.
	 * @return	Boolean TRUE if the file exists.
	 */
	public static function fileExists($file) {
		// check to see if the file exists and is not a folder
		return (static::exists($file) && !is_dir($file));
 	}
	
	/**
	 * Check to see if a folder exists on the file system.
	 *
	 * @param	String $file The folder path to check.
	 * @return	Boolean TRUE if the folder exists.
	 */
	public static function folderExists($file) {
		// check to see if the file exists and is a folder
		return (static::exists($file) && is_dir($file));
 	}
	
	/**
	 * Calculate the age of a file since last modification.
	 *
	 * @param	String $file The file path to get the age of.
	 * @return	Integer The microtime since last modification.
	 * @throws	FileSystemException If the file does not exist.
	 */
	public static function fileAge($file) {
		if(!static::fileExists($file)) {
		// file did not exist
			throw FileSystemException::withFileNotFound($file);
		}
		// determine the age of the file based on the file change time attribute
		$file_age = time() - filectime($file);
		// return the calculated file age
		return $file_age;
	}
	
	/**
	 * Recursively make the path structure on the file system.
	 *
	 * @param	String $path The path to recursively make.
	 * @throws	FileSystemException If the path could not be made.
	 */
	public function makePath($path) {
		// remove any "/" at the start of the path
		$full_path = (substr($path, 0, 1) == '/') ? substr($path, 1) : $path;
		// get the path parts
		$path_parts = explode('/', $full_path);
		if(count($path_parts)) {
		// sub path structure to create
			// recursively make the path structure
			$this->makePathRecursive($path_parts);
		} else {
		// only one folder being created in the current directory
			// call again with the current directory set as the path
			$this->makePath("./{$full_path}");
		}
	}
	
	/**
	 * Write content to a new file on the file system.
	 *
	 * @param	String $filename The filename to write to.
	 * @param	String $content The file content to write.
	 * @param	Boolean $make_path Flag to indicate the file path should be created if not exists.
	 * @throws	FileSystemException If the file exists, can not be opened for writing or write content fails.
	 */
	public function writeFile($filename, $content, $make_path=FALSE) {
		if(static::fileExists($filename)) {
		// file did already exists
			throw FileSystemException::withOverwriteFileContentsDisallowed($filename);
		}
		// make sure the path to the file exists
		if(!static::folderExists(dirname($filename)) && $make_path) { 
			try {
				$this->makePath(dirname($filename));
			} catch (FileSystemException $ex) {
				// @todo make dire if the path exists failed
				var_dump($filename);
				var_dump(dirname($filename));
			}
		}
		if(file_put_contents($filename, $content) === FALSE) {
		// failed to write the contents of the file
			throw FileSystemException::withWriteFileContentsFailed($filename);
		}
	}
	
	/**
	 * Recursively make the path structure on the file system from an array of folder names.
	 *
	 * @param	String $path_parts The list of folder names in the path structure.
	 * @throws	FileSystemException If the path could not be made.
	 */
	protected function makePathRecursive($path_parts) {
		// set the working path to current
		$working_path = '';
		// loop through the path parts array
		foreach($path_parts as $path) {
			// determine the current working path to create
			$working_path .= (in_array($path,  static::$notFiles)) ? $path : "/{$path}";
			try {
				// make the directory path
				$this->makeDir($working_path);
			} catch (FileSystemException $ex) {
			// failed to create the working path
				// try to create the directory in the current folder path
				$this->makeDir("./{$path}");
			}
		}
	}

	/**
	 * Make a directory on the file system.
	 *
	 * @param	String $path The directory path to make.
	 * @throws	FileSystemException If the directory could not be made.
	 */
	protected function makeDir($path) {
		if(!static::folderExists($path)) {
		// the directory didn't already exist
			// get a copy of the current umask value
			$old_umask = umask(0);
			// make the directory with 0777 (rwx) attribute permissions
			mkdir($path, static::CHMOD_RWX);
			// reset the umask back to the original value
			umask($old_umask);
		} else {
		// the directory already exists
			throw FileSystemException::withMakeDirFailed($path);
		}
	}
	
}
