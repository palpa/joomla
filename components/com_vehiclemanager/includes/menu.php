<?php
/**
* This file provides compatibility for VehicleManager on Joomla! 1.0.x and Joomla! 1.5
*
*/


// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

// Register legacy classes for autoloading
JLoader::register('JTableMenu', JPATH_LIBRARIES.DS.'joomla'.DS.'database'.DS.'table'.DS.'menu.php');

/**
 * @package  VehicleManager
 * @copyright 2012 Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); Rob de Cleen(rob@decleen.com)
 * Homepage: http://www.ordasoft.com
 * @version: 3.5 Free
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * */
if ( !class_exists('mosMenu')) {
  class mosMenu extends JTableMenu
  {
	  /**
	  * Constructor
	  */
	  function __construct(&$db)
	  {
		  parent::__construct( $db );
	  }
  
	  function mosMenu(&$db)
	  {
		  parent::__construct( $db );
	  }
  
	  /**
	  * Legacy Method, use {@link JTable::reorder()} instead
	  * @deprecated As of 1.5
	  */
	  function updateOrder( $where='' )
	  {
		  return $this->reorder( $where );
	  }
  
	  /**
	  * Legacy Method, use {@link JTable::publish()} instead
	  * @deprecated As of 1.0.3
	  */
	  function publish_array( $cid=null, $publish=1, $user_id=0 )
	  {
		  $this->publish( $cid, $publish, $user_id );
	  }
  }
}