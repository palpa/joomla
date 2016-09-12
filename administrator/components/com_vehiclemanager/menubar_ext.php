<?php

if (!defined('_VALID_MOS') && !defined('_JEXEC'))
    die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');
/**
 * @package  VehicleManager
 * @copyright 2012 Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); Rob de Cleen(rob@decleen.com)
 * Homepage: http://www.ordasoft.com
 * @version: 3.5 Free
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * */
// Check to ensure this file is within the rest of the framework
//defined('JPATH_BASE') or die();
// Register legacy classes for autoloading
//JLoader::register('JToolbarHelper' , JPATH_ADMINISTRATOR.DS.'includes'.DS.'toolbar.php');

/**
 * Legacy class, use {@link JToolbarHelper} instead
 *
 * @deprecated	As of version 1.5
 * @package	Joomla.Legacy
 * @subpackage	1.5
 */
$mosConfig_absolute_path = $GLOBALS['mosConfig_absolute_path'];
require_once($mosConfig_absolute_path . "/administrator/components/com_vehiclemanager/toolbar_ext.php");
jimport('joomla.application.component.view');

class mosMenuBarVehicle_ext extends JToolbarHelperVehicle_ext
{

    /**
     * @deprecated As of Version 1.5
     */
    static function startTable()
    {
        return;
    }

    /**
     * @deprecated As of Version 1.5
     */
    static function endTable()
    {
        return;
    }

    /**
     * Default $task has been changed to edit instead of new
     *
     * @deprecated As of Version 1.5
     */
    /* 	static function addNew($task = 'new', $alt = 'New')
      {
      parent::addNew($task, $alt);
      } */

    /**
     * Default $task has been changed to edit instead of new
     *
     * @deprecated As of Version 1.5
     */
    /* 	static function addNewX($task = 'new', $alt = 'New')
      {
      parent::addNew($task, $alt);
      }
     */

    /**
     * Deprecated
     *
     * @deprecated As of Version 1.5
     */
    static function saveedit()
    {
        parent::save('saveedit');
    }

}
