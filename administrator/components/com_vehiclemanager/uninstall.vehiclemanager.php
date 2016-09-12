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

include_once(JPATH_ROOT . "/components/com_vehiclemanager/compat.joomla1.5.php");

global $mosConfig_absolute_path, $database;
require_once ($mosConfig_absolute_path . "/administrator/components/com_vehiclemanager/admin.vehiclemanager.class.conf.php");

if (version_compare(JVERSION, '3.0', 'lt')){
  $db = $GLOBALS['database'];
}else{
  $db = JFactory::getDBO();
}
  $query = "DROP TABLE IF EXISTS `#__vehiclemanager_mime_types`,
                              `#__vehiclemanager_const_languages`,
                              `#__vehiclemanager_const`,
                              `#__vehiclemanager_languages`,
                              `#__vehiclemanager_feature`,
                              `#__vehiclemanager_feature_vehicles`,
                              `#__vehiclemanager_rent_sal`,
                              `#__vehiclemanager_rent`,
                              `#__vehiclemanager_rent_request`, 
                              `#__vehiclemanager_buying_request`,
                              `#__vehiclemanager_review`,
                              `#__vehiclemanager_categories`,
                              `#__vehiclemanager_photos`,
                              `#__vehiclemanager_version`,
                              `#__vehiclemanager_orders`,
                              `#__vehiclemanager_orders_details`,
                              `#__vehiclemanager_video_source`,
                              `#__vehiclemanager_track_source`,
                              `#__vehiclemanager_vehicles`,
                              `#__vehiclemanager_main_categories`";
  $database->setQuery($query);
  $db->setQuery($query);
  $db->query();


function com_uninstall(){
  echo "Uninstalled! ";
}

