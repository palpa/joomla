<?php
/**
 * @version 2.3
 * @package VehicleManager LocationMap
 * @copyright 2009 OrdaSoft
 * @author 2009 Sergey Brovko-OrdaSoft(brovinho@mail.ru)
 * @description Location map for VehicleManager
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
$url = JURI::base();
$mosConfig_live_site = $GLOBALS['mosConfig_live_site'] = substr_replace($url, '', -1, 1);
$mosConfig_absolute_path = $GLOBALS['mosConfig_absolute_path'] = JPATH_SITE;
if (!defined('DS'))
    define('DS', DIRECTORY_SEPARATOR);
if(version_compare(JVERSION, "3.0.0","lt"))
JHTML::_('behavior.mootools');
else {
  JHTML::_('behavior.framework', true); 
}
$pr = rand();

if( !function_exists( 'sefreltoabs')) {
  function sefRelToAbs( $value ) {
    // Replace all &amp; with & as the router doesn't understand &amp;
    $url = str_replace('&amp;', '&', $value);//replace chars &amp; on & in 
    if(substr(strtolower($url),0,9) != "index.php") 
      return $url;//cheking correct url
    $uri    = JURI::getInstance();
    $prefix = $uri->toString(array('scheme', 'host', 'port'));

    return $prefix.JRoute::_($url);
  }
}
//Common parameters
//Individual parameters
$count_vehicles = intval($params->def('vehicles', 0));
$cat_id = $params->get('cat_id');
$vehicle_id = $params->get('vehicle_id');
$new_target = $params->def('new_target', 1);
$ItemId_tmp_from_params = $params->get('ItemId');
$moduleclass_sfx = $params->get('moduleclass_sfx');
$database = JFactory::getDBO();
$my=JFactory::getUser();
$GLOBALS['database'] = &$database;
$GLOBALS['my']=&$my;
$acl =JFactory::getACL();
$GLOBALS['acl'] =$acl;
$mosConfig_absolute_path = $GLOBALS['mosConfig_absolute_path']  = JPATH_SITE;
$f_path = JPATH_BASE .DS.'components'.DS.'com_vehiclemanager'.DS.'functions.php';

if (!file_exists($f_path)){
  echo "To display this module You have to install VehicleManager first<br />"; exit;
}else 
  require_once ($f_path);

vmLittleThings::language_load_VM();
$s = vmLittleThings::getWhereUsergroupsCondition ();
$query = "SELECT language FROM #__modules WHERE id = '$module->id'";
$database->setQuery($query);
$langmodule = $database->loadResult();
$sql_published = " AND v.published=1";
$sql_approved = " AND v.approved=1";

if ($cat_id != null && $vehicle_id != null)
  echo ('<font color="#CC0000">You input IDs of categories and vehicles together! Correct this mistake.</font>');
else
{
  if($vehicle_id != null) $sql_where = " AND v.id IN(".$vehicle_id.")";
  if($cat_id != null) $sql_where = " AND c.id IN(".$cat_id.")";
  if($cat_id == null && $vehicle_id == null) $sql_where = "";
  if (isset($langContent)){
    $lang = $langContent;
    $query = "SELECT lang_code FROM #__languages WHERE sef = '$lang'";
    $database->setQuery($query);
    $lang = $database->loadResult();
    $lang = " and (v.language like 'all' or v.language like '' or v.language like '*' or v.language is null or v.language like '$lang')
             AND (c.language like 'all' or c.language like '' or c.language like '*' or c.language is null or c.language like '$lang') ";
  }else{
    $lang = "";
  }  
  if($langmodule != "" && $langmodule != "*"){
    $selectstring = "SELECT v.vtitle,v.map_zoom,v.image_link,v.vtype,v.price,v.priceunit,v.id,v.vehicleid,v.vlatitude,v.vlongitude,vc.idcat
              \nFROM #__vehiclemanager_vehicles AS v
              \nLEFT JOIN #__vehiclemanager_categories AS vc ON vc.iditem=v.id
              \nLEFT JOIN #__vehiclemanager_main_categories AS c ON c.id=vc.idcat
              \nWHERE ($s) $lang AND v.language = '".$langmodule."' AND v.vlatitude IS NOT NULL".$sql_where.$sql_published.$sql_approved.
             "\nLIMIT ".$count_vehicles;
  }else{
    $selectstring = "SELECT v.vtitle,v.vtype,v.map_zoom,v.image_link,v.price,v.priceunit,v.id,v.vehicleid,v.vlatitude,v.vlongitude,vc.idcat
              \nFROM #__vehiclemanager_vehicles AS v
              \nLEFT JOIN #__vehiclemanager_categories AS vc ON vc.iditem=v.id
              \nLEFT JOIN #__vehiclemanager_main_categories AS c ON c.id=vc.idcat
              \nWHERE ($s) $lang AND v.vlatitude IS NOT NULL".$sql_where.$sql_published.$sql_approved.
             "\nLIMIT ".$count_vehicles;
  }
  $database->setQuery($selectstring);
  $rows= $database->loadObjectList();

  $selectstring = "SELECT id  
                  FROM #__menu 
                  WHERE   link 
                  LIKE'%option=com_vehiclemanager%' 
                  AND params 
                  LIKE '%back_button%'  ";
  $database->setQuery($selectstring);
  $ItemId_tmp_from_db = $database->loadResult();
  if($ItemId_tmp_from_params==""){
    $Itemid=$ItemId_tmp_from_db;
  }else{
    $Itemid=$ItemId_tmp_from_params;
  }
}
require(JModuleHelper::getLayoutPath('mod_vehiclemanager_location_map_free', $params->get('layout', 'default')));
?>
<div style="text-align: center;"><a href="http://ordasoft.com" style="font-size: 10px;">Powered by OrdaSoft!</a></div>