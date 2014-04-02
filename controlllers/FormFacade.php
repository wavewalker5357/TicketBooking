<?php namespace Controllers;

/**
 * Forms
 * 
 * Sort of simple facade to generate
 * form inputs.
 */
class FormFacade {

	public function __construct()
	{

	}

	public static function __callStatic( $function, $args ) 
	{
		$object = new $function($args[0]);

		return $object;
	}
}