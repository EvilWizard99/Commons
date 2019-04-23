<?php 

namespace EWC\Commons\Tests\Exceptions;

use PHPUnit_Framework_TestCase;
use EWC\Commons\Exceptions\FileSystemException;

/**
 * Corresponding Test Class for \EWC\Commons\Exceptions\FileSystemException
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 */
class FileSystemExceptionTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * Just check if the FileSystemException has no syntax error.
	 */
	public function testIsThereAnySyntaxError() {
		$var = new FileSystemException;
		$this->assertTrue(is_object($var));
		unset($var);
	}
	
	/**
	 * Make sure file not found throws expected exception.
	 * 
	 * @expectedException \EWC\Commons\Exceptions\FileSystemException
	 * @expectedExceptionMessageRegExp /File \[.+\] not found\./
	 * @expectedExceptionCode \EWC\Commons\Exceptions\FileSystemException::FILE_NOT_FOUND
	 */
	public function testFileNotFoundThrowsExceptionWithFileNotFound() {
		throw FileSystemException::withFileNotFound("/file/not/found.ext");
	}
	
	/**
	 * Make sure make dir failed throws expected exception.
	 * 
	 * @expectedException \EWC\Commons\Exceptions\FileSystemException
	 * @expectedExceptionMessageRegExp /Failed to make directory \[.+\]\./
	 * @expectedExceptionCode \EWC\Commons\Exceptions\FileSystemException::MAKE_DIR_FAILED
	 */
	public function testMakeDirFailedThrowsExceptionWithMakeDirFailed() {
		throw FileSystemException::withMakeDirFailed("/folder/to/fail");
	}
	
	/**
	 * Make sure open file for writing failed throws expected exception.
	 * 
	 * @expectedException \EWC\Commons\Exceptions\FileSystemException
	 * @expectedExceptionMessageRegExp /Unable to open \[.+\] for writing\./
	 * @expectedExceptionCode \EWC\Commons\Exceptions\FileSystemException::OPEN_FOR_WRITE
	 */
	public function testOpenFileForWritingThrowsExceptionWithWriteFileFailed() {
		throw FileSystemException::withWriteFileFailed("/file/to/fail.ext");
	}
	
	/**
	 * Make sure write file contents failed throws expected exception.
	 * 
	 * @expectedException \EWC\Commons\Exceptions\FileSystemException
	 * @expectedExceptionMessageRegExp /Unable to write file contents for \[.+\]\./
	 * @expectedExceptionCode \EWC\Commons\Exceptions\FileSystemException::WRITE_FILE
	 */
	public function testWriteFileContentFailedThrowsExceptionWithWriteFileContentsFailed() {
		throw FileSystemException::withWriteFileContentsFailed("/file/to/fail.ext");
	}
	
	/**
	 * Make sure write file contents failed throws expected exception.
	 * 
	 * @expectedException \EWC\Commons\Exceptions\FileSystemException
	 * @expectedExceptionMessageRegExp /Unable to overwrite file \[.+\]\./
	 * @expectedExceptionCode \EWC\Commons\Exceptions\FileSystemException::OVERWRITE_FILE_DISALLOWED
	 */
	public function testOverwriteFileContentDisallowedThrowsExceptionWithOverwriteFileContentsDisallowedd() {
		throw FileSystemException::withOverwriteFileContentsDisallowed("/file/to/fail.ext");
	}
  
}