<?php
/**
* This file provides compatibility for VehicleManager on Joomla! 1.0.x and Joomla! 1.5
*
*/

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

// Register legacy classes for autoloading
JLoader::register('JParameter' , JPATH_LIBRARIES.DS.'joomla'.DS.'html'.DS.'parameter.php');

/**
 * @package  VehicleManager
 * @copyright 2012 Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); Rob de Cleen(rob@decleen.com)
 * Homepage: http://www.ordasoft.com
 * @version: 3.5 Free
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * */
if(version_compare(JVERSION, '3.0', 'lt')) {
    if ( !class_exists('mosParameters')) {
      class mosParameters extends JParameter
      {
	      /**
	      * @param string The raw parms text
	      * @param string Path to the xml setup file
	      * @param string The type of setup file
	      */
	      function __construct($text, $path = '', $type = 'component')
	      {
		      parent::__construct($text, $path);
	      }
      
	      /**
	      * Legacy function, use {@link JParameter::toObject() JParameter->toObject()} instead
	      *
	      * @deprecated As of version 1.5
	      */
	      function toObject()
	      {
		      parent::toObject();
	      }
      
	      /**
	      * Legacy function, use {@link JParameter::toArray() JParameter->toArray()} instead
	      *
	      * @deprecated As of version 1.5
	      */
	      function toArray()
	      {
		      parent::toArray();
	      }
      
	      /**
	      * Parse an .ini string, based on phpDocumentor phpDocumentor_parse_ini_file function
	      *
	      * @access public
	      * @param mixed The ini string or array of lines
	      * @param boolean add an associative index for each section [in brackets]
	      * @return object
	      */
	      function parse($txt, $process_sections = false, $asArray = false)
	      {
		      $this->loadINI($txt);
      
		      if($asArray) {
			      return $this->toArray();
		      }
      
		      return $this->toObject( );
	      }
      
	      /**
	      * Special handling for textarea param
	      */
	      function textareaHandling( &$txt )
	      {
		      $total = count( $txt );
		      for( $i=0; $i < $total; $i++ ) {
			      if ( strstr( $txt[$i], "\n" ) ) {
				      $txt[$i] = str_replace( "\n", '<br />', $txt[$i] );
			      }
		      }
		      $txt = implode( "\n", $txt );
      
		      return $txt;
	      }
      }
    }
}