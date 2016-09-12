<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
 * @package  VehicleManager
 * @copyright 2012 Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); Rob de Cleen(rob@decleen.com)
 * Homepage: http://www.ordasoft.com
 * @version: 3.5 Free
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * */

include_once( JPATH_ROOT."/components/com_vehiclemanager/compat.joomla1.5.php" );
require_once ($mosConfig_absolute_path."/components/com_vehiclemanager/vehiclemanager.class.php");

if(!defined( '_JLEGACY' ))
{
 $GLOBALS['path'] = $mosConfig_live_site."/components/com_vehiclemanager/images/";
}
else 
{
$GLOBALS['path'] = $mosConfig_live_site."/administrator/components/com_vehiclemanager/images/";
}
$path = $GLOBALS['path'];


class DMInstallHelper{ 
    static function getComponentId(){
        global $database;
        if (version_compare(JVERSION, "1.6.0", "lt")){
            static $id;
            if(!$id){
                $database->setQuery("SELECT id FROM #__components WHERE `option`='com_vehiclemanager' AND `parent`=0 ");
                $id =$database->loadResult();
            }
            return $id;
        } else if (version_compare(JVERSION, "1.6.0", "ge")){
            $database->setQuery("SELECT extension_id FROM #__extensions WHERE `element`='com_vehiclemanager' ");
            $id =$database->loadResult();
            return $id;
        } else {echo "Sanity test. Error version check!"; exit;}
    }


    static function getParentId(){
        $id = DMInstallHelper::getComponentId();
        global $database;
        if (version_compare(JVERSION, "1.6.0", "lt")){
            //
        } else if (version_compare(JVERSION, "1.6.0", "ge")){
            $database->setQuery("SELECT id FROM #__menu WHERE title='VehicleManager' and level=1 and parent_id=1 and component_id=$id" );
            $parent_id =$database->loadResult();
            return $parent_id;
        } else {echo "Sanity test. Error version check!"; exit;}
    }


    static function setAdminMenuImages(){
        global $database, $path;
        $id = DMInstallHelper::getComponentId();
        if (version_compare(JVERSION, "1.6.0", "lt")){

            // Main menu
            $database->setQuery("UPDATE #__components SET admin_menu_img = '".$path."dm_component_16.png' WHERE id=$id");
            $database->query();

            // Submenus
            $submenus = array();
            $submenus[] = array('image' => $path.'dm_edit_16.png', 'name'=>'Vehicles');
            $submenus[] = array('image' => $path.'dm_component_16.png', 'name'=>'Categories');
            $submenus[] = array('image' => $path.'dm_component_16.png', 'name'=>'Rent Requests');
            $submenus[] = array('image' => $path.'dm_component_16.png', 'name'=>'Users Rent Hisory');
            $submenus[] = array('image' => $path.'dm_component_16.png', 'name'=>'Sale Manager');
            $submenus[] = array('image' => $path.'dm_component_16.png', 'name'=>'Features Manager');
            $submenus[] = array('image' => $path.'dm_component_16.png', 'name'=>'Settings');
            $submenus[] = array('image' => $path.'dm_credits_16.png', 'name'=>'About');

            foreach($submenus as $submenu){
                $database->setQuery("UPDATE #__components SET admin_menu_img = '".$submenu['image']."' WHERE parent=$id AND name = '".$submenu['name']."';");
                $database->query();
            }
        } else if (version_compare(JVERSION, "1.6.0", "ge")){
            $parent_id = DMInstallHelper::getParentId();

            // Main menu
            $database->setQuery("UPDATE #__menu SET img = 'class:component' WHERE title='VehicleManager' and level=1 and parent_id=1 and component_id=$id");
            $database->query();

            // Submenus
            $submenus = array();
            $submenus[] = array('img' => 'class:component', 'title' => 'VehicleManager','alias'=>'VehicleManager');
            $submenus[] = array('img' => 'class:module', 'title'=>'Vehicles', 'alias'=>'Vehicles');
            $submenus[] = array('img' => 'class:category', 'title'=>'Categories', 'alias'=>'Categories');
            $submenus[] = array('img' => 'class:writemess', 'title'=>'Rent Requests', 'alias'=>'Rent Requests');
            $submenus[] = array('img' => 'class:writemess', 'title'=>'Users Rent History', 'alias'=>'Users Rent History');
            $submenus[] = array('img' => 'class:writemess', 'title'=>'Sale Manager', 'alias'=>'Sale Manager');
            $submenus[] = array('img' => 'class:writemess', 'title'=>'Features Manager', 'alias'=>'Features Manager');
            $submenus[] = array('img' => 'class:writemess', 'title' => 'Language Manager','alias'=>'Language Manager');
            $submenus[] = array('img' => 'class:config', 'title'=>'Settings', 'alias'=>'Settings');
            $submenus[] = array('img' => 'class:info', 'title'=>'About', 'alias'=>'About');

            foreach($submenus as $submenu){
                $database->setQuery("UPDATE #__menu SET img = '".$submenu['img']."' WHERE component_id=$id AND parent_id = '".$parent_id."' and level=2  AND title = '".$submenu['title']."';");
                $database->query();
                $database->setQuery("UPDATE #__menu SET alias = '" . $submenu['alias'] . "'" . "\n WHERE component_id=$id AND title = '" . $submenu['title'] . "';");
                $database->query();
            }
        } else {echo "Sanity test. Error version check!"; exit;}
    }
}
