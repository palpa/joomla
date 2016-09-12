<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) {
    die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
}
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
/**
 * @package  VehicleManager
 * @copyright 2012 Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); Rob de Cleen(rob@decleen.com)
 * Homepage: http://www.ordasoft.com
 * @version: 3.5 Free
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * */

class com_VehicleManagerInstallerScript{
    /**
     * method to install the component
     *
     * @return void
     */
    function install($parent){
        // $parent is the class calling this method
    }
 
    /**
     * method to uninstall the component
     *
     * @return void
     */
    function uninstall($parent){
        // $parent is the class calling this method
        if(file_exists(JPATH_ROOT."/administrator/components/com_vehiclemanager/uninstall.vehiclemanager.php"))
            require_once(JPATH_ROOT."/administrator/components/com_vehiclemanager/uninstall.vehiclemanager.php");
    }
 
    /**
     * method to update the component
     *
     * @return void
     */
    function update($parent){
        // $parent is the class calling this method
    }

    /**
     * method to run before an install/update/uninstall method
     *
     * @return void
     */
    function preflight($type, $parent){
        // $parent is the class calling this method
        // $type is the type of change (install, update or discover_install)
    }

    /**
     * method to run after an install/update/uninstall method
     *
     * @return void
     */
    function postflight($type, $parent){
        // $parent is the class calling this method
        // $type is the type of change (install, update or discover_install)
        if(file_exists(JPATH_ROOT."/administrator/components/com_vehiclemanager/install.vehiclemanager.php")){    
            require_once(JPATH_ROOT."/administrator/components/com_vehiclemanager/install.vehiclemanager.php");
            com_install2();
        }
    }
}
