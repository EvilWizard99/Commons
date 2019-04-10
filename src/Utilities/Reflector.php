<?php
namespace EWC\Commons\Utilities;

use ReflectionClass;
use ReflectionException;
use EWC\Commons\Exceptions\ReflectorException;

/**
 * Class Reflector
 * 
 * Make working with reflected class objects easier.
 *
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2018 Evil Wizard Creation.
 * 
 * @uses	ReflectionClass To get the factory shape product.
 * @uses	ReflectionException Catches named exception.
 * @uses	ReflectorException Throws named exception.
 */
class Reflector {
	
	/**
	 * @var String The fully qualified class name of the object being reflected.
	 */
	protected $fully_qualified_classname = "";
	
	/**
	 * @var String An array of any constructor parameter arguments needed for new instances.
	 */
	protected $constructor_arguments = [];
	
	/**
	 * @var ReflectionClass A reflection of the object.
	 */
	protected $Reflection = NULL;
	
	/**
	 * @var Object A reflected instance of the object.
	 */
	protected $ReflectedInstance = NULL;
	
	/**
	 * Reflector constructor.
	 *
	 * @param 	String $fully_qualified_classname The fully qualified class name of the object being reflected.
	 * @param 	Array $constructor_arguments An array of any constructor parameter arguments needed for new instances.
	 * @param 	Object $ExistingInstance An optional existing reference to an instance of the object being reflected.
	 * @param 	ReflectionClass $ExistingReflection An optional existing reference to a reflection of the object being reflected.
	 */
	public function __construct($fully_qualified_classname, $constructor_arguments=[], &$ExistingInstance=NULL, &$ExistingReflection=NULL) {
		$this->fully_qualified_classname = $fully_qualified_classname;
		$this->constructor_arguments = $constructor_arguments;
		// Check to see if an existing instance of the object has been passed for use.
		if(!is_null($ExistingInstance)) {
			$this->ReflectedInstance &= $ExistingInstance;
		}
		// Check to see if an existing instance of the reflected object has been passed for use.
		if(!is_null($ExistingReflection)) {
			$this->Reflection &= $ExistingReflection;
		}
	}
	
	/**
	 * Get a reflection from the instance of an object.
	 *
	 * @param 	Object $object The to get a reflector interface on.
	 * @param 	Array $constructor_arguments An array of any constructor parameter arguments needed for new instances.
	 * @return	Reflector A new reflector of the object.
	 * @throws	ReflectorException If unable to get a reflection of the object.
	 */
	public static function newReflectorOf(&$object, $constructor_arguments=[]) {
		try {
			// Create a new reflector based of the object.
			$reflection = new ReflectionClass($object);
			return new static("\\" . addslashes($reflection->getName()), $constructor_arguments, $object, $reflection);
		} catch (ReflectionException $rex) {
		// something went wrong
			throw ReflectorException::withReflectionFailed($this->fully_qualified_classname, $rex);
		}
	}
	
	/**
	 * Get a reflection of the object.
	 *
	 * @return	ReflectionClass A reflection of a object.
	 * @throws	ReflectorException If unable to get a reflection of the object.
	 */
	public function getReflection() {
		// Check to see if a new reflection of the object needs to be created.
		if(is_null($this->Reflection)) {
			try {
				// Create a new reflection of the object.
				$this->Reflection = new ReflectionClass($this->fully_qualified_classname);
			} catch (ReflectionException $ex) {
			// something went wrong
				throw ReflectorException::withReflectionFailed($this->fully_qualified_classname, $ex);
			}
		}
		return $this->Reflection;
	}
	
	/**
	 * Verify a reflected class type.
	 *
	 * @param 	String $classname The fully qualified class name of the object type to verify as.
	 * @throws	ReflectorException If the reflected class does not match the type supplied.
	 */
	public function verifyType($classname) {
		if(!$this->getReflection()->isSubclassOf($classname)) {
		// pattern demo doesn't extend from the abstract pattern demo base
			throw ReflectorException::withTypeMismatch($classname, $this->Reflection->getNamespaceName());
		}		
	}
	
	/**
	 * Get a Singleton pattern instance of the reflected object.
	 *
	 * @return	Object A reference to the object.
	 * @throws	ReflectorException If unable to get a reflected instance of the object.
	 */
	public function getReflectedSingleton($accessor="getInstance") {
		// Check to see if a new instance of the object needs to be created.
		if(is_null($this->ReflectedInstance)) {
			$this->ReflectedInstance = $this->runReflectedMethod($accessor, $this->constructor_arguments, TRUE);
		}
		return $this->ReflectedInstance;		
	}
	
	/**
	 * Get a reference to an instance of the object.
	 *
	 * @return	Object A reference to the object.
	 * @throws	ReflectorException If unable to get a reflected instance of the object.
	 */
	public function getReflectedInstance() {
		// Check to see if a new instance of the object needs to be created.
		if(is_null($this->ReflectedInstance)) {
			try {
				// Create a new reflect instance of the object.
				$this->ReflectedInstance = $this->getReflection()
												->newInstanceArgs($this->constructor_arguments);
			} catch (ReflectionException $rex) {
			// something went wrong
				throw ReflectorException::withFailedToGetInstance($this->fully_qualified_classname, $rex);
			}
		}
		return $this->ReflectedInstance;		
	}
	
	/**
	 * Run a reflected method.
	 *
	 * @param 	String $method_name The mthod name to call.
	 * @param 	Array $args The reflected method parameter arguments.
	 * @return	Mixed The result of the reflected  method execution.
	 * @throws	ReflectorException If the method can not be run for vairous reasons.
	 */
	public function runReflectedMethod($method_name, $args, $static=FALSE) {
		// Using a try catch block removes the need for redundant failsafe checks to be performed.
		try {
			$reflectedMethod = $this->getReflection()
									->getMethod($method_name);
			if($reflectedMethod->isConstructor() || $reflectedMethod->isDestructor()) {
			// not allowed to be called directly
				throw ReflectorException::withIllegalMethodCall($method_name);
			}
			// Check that the method can be safely called with the number of parameter arguments supplied.
			$argument_count = count($args);
			$required_argument_count = $reflectedMethod->getNumberOfRequiredParameters();
			if($argument_count < $required_argument_count) {
				throw ReflectorException::withArgumentCountMismatch($this->getReflection()->getName(), $required_argument_count, $argument_count);
			}
			// Valiables no longer needed, just being tidy.
			unset($argument_count, $required_argument_count);
			// determin if the method is being run on instance os the object or statically
			$object = ($static) ? NULL : $this->getReflectedInstance();
			// Use reflections to call the method with the paramater arguments supplied.
			$result = $reflectedMethod->invokeArgs($object, $args);
		} catch (ReflectionException $rex) {
		// something went wrong
			throw new ReflectorException($rex->getMessage(), $rex->getCode(), $rex);
		}
		// return the result of calling the method
		return $result;
	}
}
