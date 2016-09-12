<?php

/**
 * @version 3.0 pro
 * @package VehicleManager search
 * @copyright 2013 OrdaSoft
 * @description Vehicle search for VehicleManager Component
 */

defined('_JEXEC') or die;


if (!defined('DS'))
    define('DS', DIRECTORY_SEPARATOR);

$f_path = JPATH_BASE .'/components/com_vehiclemanager/functions.php';
if (!file_exists($f_path)){
    echo "To display this module You have to install VehicleManager first<br />"; exit;
} else require_once ($f_path);

vmLittleThings::language_load_VM();

require_once('./administrator/components/com_vehiclemanager/admin.vehiclemanager.class.others.php');
require_once ( JPATH_BASE .DS.'administrator'.DS.'components'.DS.'com_vehiclemanager'.DS.'admin.vehiclemanager.class.others.php' );
$database = JFactory::getDbo();


$doc =JFactory::getDocument(); 
$doc->addStyleSheet( JURI::base(true) .'/'. 'components'.'/'.'com_vehiclemanager'.'/'.'includes'.'/'.'vehiclemanager.css' );
$doc->addStyleSheet( JURI::base(true) .DS. 'components'.DS.'com_vehiclemanager'.DS.'includes'.DS.'jquery-ui.css');
$doc->addScript(JURI::base(true) .'/components/com_vehiclemanager/lightbox/js/jQuerVEH-1.9.0.js');
$doc->addScript(JURI::base(true) .'/components/com_vehiclemanager/includes/jquery-ui.js');


if ( !isset($mosConfig_absolute_path) )  $mosConfig_absolute_path = JPATH_SITE;



if( !function_exists( 'sefRelToAbs')) {
  function sefRelToAbs( $value ) {
    //Need check!!!
    // Replace all &amp; with & as the router doesn't understand &amp;
    $url = str_replace('&amp;', '&', $value);
    if(substr(strtolower($url),0,9) != "index.php") return $url;
    $uri    = JURI::getInstance();
    $prefix = $uri->toString(array('scheme', 'host', 'port'));
    return $prefix.JRoute::_($url);
  }
}



global $mosConfig_absolute_path, $mosConfig_allowUserRegistration, $mosConfig_lang, $database;
require_once('./components/com_vehiclemanager/compat.joomla1.5.php');

if( !function_exists( 'filterDateVmSearchMod')) {
  function filterDateVmSearchMod() {
      global $vehiclemanager_configuration;
      $DateToFormat = str_replace("d", 'dd', (str_replace("m", 'mm', (str_replace("Y", 'yy', (str_replace('%', '', $vehiclemanager_configuration['date_format'])))))));
      return $DateToFormat;
  }
}

$VID = $params->get('showVID', 0);
$showDescription  = $params->get('showDescription', 0);
$showTitle = $params->get('showTitle', 0);
$showCountry = $params->get('showCountry', 0);
$showCategory_options = $params->get('showCategory', 0);
$showRegion = $params->get('showRegion', 0);
$showCity = $params->get('showCity', 0);
$showDistrict = $params->get('showDistrict', 0);
$showAddress = $params->get('showAddress', 0);
$showMileage = $params->get('showMileage', 0);
$showEngine_type = $params->get('showEngine_type', 0);
$showWheeltype = $params->get('showWheeltype', 0);
$showExterior_colors = $params->get('showExterior_colors', 0);
$showExterior_extras = $params->get('showExterior_extras', 0);
$showInterior_colors = $params->get('showInterior_colors', 0);
$showDashboard_options = $params->get('showDashboard_options', 0);
$showInterior_extras = $params->get('showInterior_extras', 0);
$showSafety_options = $params->get('showSafety_options', 0);
$showWarranty_options = $params->get('showWarranty_options', 0);
$showOwner = $params->get('showOwner', 0);
$showAdvanceSearch = $params->get('showAdvanceSearch', 0);
$showzipcode = $params->get('showzipcode', 0);
$ContactInformation = $params->get('ContactInformation', 0);
$CityMPGKPL = $params->get('CityMPGKPL', 0);
$HighwayMPGKPL = $params->get('HighwayMPGKPL', 0);
$Wheelbase = $params->get('Wheelbase', 0);
$Rear_axel_type = $params->get('Rear_axel_type', 0);
$Brakes_type = $params->get('Brakes_type', 0);


$moduleclass_sfx = $params->get('moduleclass_sfx', '');
$ItemId_tmp_from_params=$params->get('ItemId');

$categories[] = mosHTML::makeOption(_VEHICLE_MANAGER_LABEL_ALL, _VEHICLE_MANAGER_LABEL_ALL);
$database->setQuery("SELECT id FROM #__menu WHERE link LIKE'%option=com_vehiclemanager%' AND params LIKE '%back_button%'");
$ItemId_tmp_from_db = $database->loadResult();  
if($ItemId_tmp_from_params=="") $ItemId=$ItemId_tmp_from_db; else $ItemId=$ItemId_tmp_from_params;
$clist = vmLittleThings::com_veh_categoryTreeList(0,'',true,$categories);

  $makers[]=mosHtml::makeOption(_VEHICLE_MANAGER_LABEL_ALL,_VEHICLE_MANAGER_LABEL_ALL);
  $temp=mosVehicleManagerOthers::getMakersArray();
  $cars=$temp[0];
  foreach($cars as $car){
        if(trim($car)!=''){
           $makers[]=mosHtml::makeOption(trim($car),trim($car));
        }
  }
  $maker=mosHTML :: selectList($makers, 'maker', 'class="inputbox" size="1" style="width: 100px" onchange=changedMaker2(this)','value', 'text');
  $params->def('maker',$maker);  

  $models[]=mosHtml::makeOption(_VEHICLE_MANAGER_LABEL_ALL,_VEHICLE_MANAGER_LABEL_ALL);
  $model=mosHTML :: selectList($models, 'vm_model', 'class="inputbox" size="1" style="width: 100px"', 'value', 'text');
  $params->def('model',$model);

  //Select list for vehicle type
  $vehicletype[] = mosHtml::makeOption(_VEHICLE_MANAGER_LABEL_ALL,_VEHICLE_MANAGER_LABEL_ALL);
  $vehicletype1=explode(',',_VEHICLE_MANAGER_OPTION_VEHICLE_TYPE);
  $i=1; foreach($vehicletype1 as $vehicletype2) {$vehicletype[]=mosHtml::makeOption($i,$vehicletype2);$i++;}
  $vehicle_type_list = mosHTML :: selectList($vehicletype, 'vtype', 'class="inputbox" size="1" style="width: 100px"', 'value', 'text', _VEHICLE_MANAGER_LABEL_ALL);
  $params->def('vehicle_type_list', $vehicle_type_list );

  //Select list for drive type
  $drivetype[] = mosHtml::makeOption(_VEHICLE_MANAGER_LABEL_ALL,_VEHICLE_MANAGER_LABEL_ALL);
  $drivetype1=explode(',',_VEHICLE_MANAGER_OPTION_DRIVE_TYPE);
  $i=1; foreach($drivetype1 as $drivetype2) {$drivetype[]=mosHtml::makeOption($i,$drivetype2);$i++;}
  $drive_type_list = mosHTML :: selectList($drivetype, 'dtype', 'class="inputbox" size="1" style="width: 100px"', 'value', 'text', _VEHICLE_MANAGER_LABEL_ALL);
  $params->def('drive_type_list', $drive_type_list );
  
  //Select list for listing type
  $listing_type[]=mosHtml::makeOption(_VEHICLE_MANAGER_LABEL_ALL,_VEHICLE_MANAGER_LABEL_ALL);
  $listing_type[]=mosHtml::makeOption(1,_VEHICLE_MANAGER_OPTION_FOR_RENT);
  $listing_type[]=mosHtml::makeOption(2,_VEHICLE_MANAGER_OPTION_FOR_SALE);
  $listing_type_list = mosHTML :: selectList($listing_type, 'listing_type', 'class="inputbox" size="1" style="width: 100px"', 'value', 'text', _VEHICLE_MANAGER_LABEL_ALL);
  $params->def('listing_type_list',$listing_type_list);

  //Select list for condition status
  $condition[] = mosHtml::makeOption(_VEHICLE_MANAGER_LABEL_ALL,_VEHICLE_MANAGER_LABEL_ALL);
  $condition1=explode(',',_VEHICLE_MANAGER_OPTION_VEHICLE_CONDITION);
  $i=1; foreach($condition1 as $condition2) {$condition[]=mosHtml::makeOption($i,$condition2);$i++;}
  $condition_status_list = mosHTML :: selectList($condition, 'vcondition', 'class="inputbox" size="1" style="width: 100px"', 'value', 'text',_VEHICLE_MANAGER_LABEL_ALL);
  $params->def('condition_status_list', $condition_status_list);

  //Select list for vehicle transmission
  $transmission[] = mosHtml::makeOption(_VEHICLE_MANAGER_LABEL_ALL,_VEHICLE_MANAGER_LABEL_ALL);
  $transmission1=explode(',',_VEHICLE_MANAGER_OPTION_TRANSMISSION);
  $i=1; foreach($transmission1 as $transmission2) {$transmission[]=mosHtml::makeOption($i,$transmission2);$i++;}
  $transmission_type_list = mosHTML :: selectList($transmission, 'transmission', 'class="inputbox" size="1" style="width: 100px"', 'value', 'text', _VEHICLE_MANAGER_LABEL_ALL);
  $params->def('transmission_type_list',  $transmission_type_list );

  //Select list for fuel type
  $fueltype[]=mosHtml::makeOption(_VEHICLE_MANAGER_LABEL_ALL,_VEHICLE_MANAGER_LABEL_ALL);
  $fueltype1=explode(',',_VEHICLE_MANAGER_OPTION_FUEL_TYPE);
  $i=1; foreach($fueltype1 as $fueltype2) {$fueltype[]=mosHtml::makeOption($i,$fueltype2);$i++;}
  $fuel_type_list = mosHTML :: selectList($fueltype, 'fuel_type', 'class="inputbox" size="1" style="width: 100px"', 'value', 'text', _VEHICLE_MANAGER_LABEL_ALL);
  $params->def('fuel_type_list',$fuel_type_list);
  
  //Select list for cylinder
  $cylinder[] = mosHtml::makeOption(_VEHICLE_MANAGER_LABEL_ALL, _VEHICLE_MANAGER_LABEL_ALL);
  $cylinder1 = explode(',', _VEHICLE_MANAGER_OPTION_NUMBER_OF_CYLINDERS);
  $i = 1;
        foreach ($cylinder1 as $cylinder2) {
            $cylinder[] = mosHtml::makeOption($i, $cylinder2);
            $i++;
        }
        $cylinder_list = mosHTML :: selectList($cylinder, 'cylinder', 'class="inputbox" size="1" style="width: 100px"', 'value', 'text', _VEHICLE_MANAGER_LABEL_ALL);
        $params->def('cylinder_list', $cylinder_list);
        
        //number of speeds
        $num_speed[] = mosHtml::makeOption(_VEHICLE_MANAGER_LABEL_ALL, _VEHICLE_MANAGER_LABEL_ALL);
        $num_speed1 = explode(',', _VEHICLE_MANAGER_OPTION_NUMBER_OF_SPEEDS);
        $i = 1;
        foreach ($num_speed1 as $num_speed2) {
            $num_speed[] = mosHtml::makeOption($i, $num_speed2);
            $i++;
        }
        $num_speed_list = mosHTML :: selectList($num_speed, 'num_speed', 'class="inputbox" size="1" style="width: 100px"', 'value', 'text', _VEHICLE_MANAGER_LABEL_ALL);
        $params->def('num_speed_list', $num_speed_list);
        
        //listing status
        $listing_status[] = mosHtml::makeOption(_VEHICLE_MANAGER_LABEL_ALL, _VEHICLE_MANAGER_LABEL_ALL);
        $listing_status1 = explode(',', _VEHICLE_MANAGER_OPTION_LISTING_STATUS);
        $i = 1;
        foreach ($listing_status1 as $listing_status2) {
            $listing_status[] = mosHtml::makeOption($i, $listing_status2);
            $i++;
        }
        $listing_status_list = mosHTML :: selectList($listing_status, 'listing_status', 'class="inputbox" size="1" style="width: 100px"', 'value', 'text', _VEHICLE_MANAGER_LABEL_ALL);
        $params->def('listing_status_list', $listing_status_list);
        
        //number of doors
        $doors[] = mosHtml::makeOption(_VEHICLE_MANAGER_LABEL_ALL, _VEHICLE_MANAGER_LABEL_ALL);
        $doors1 = explode(',', _VEHICLE_MANAGER_OPTION_NUMBER_OF_DOORS);
        $i = 1;
        foreach ($doors1 as $doors2) {
            $doors[] = mosHtml::makeOption($i, $doors2);
            $i++;
        }
        $doors_list = mosHTML :: selectList($doors, 'doors', 'class="inputbox" size="1" style="width: 100px"', 'value', 'text', _VEHICLE_MANAGER_LABEL_ALL);
        $params->def('doors_list', $doors_list);
        
        //price type
        $price_type[] = mosHtml::makeOption(_VEHICLE_MANAGER_LABEL_ALL, _VEHICLE_MANAGER_LABEL_ALL);
        $price_type1 = explode(',', _VEHICLE_MANAGER_OPTION_PRICE_TYPE);
        $i = 1;
        foreach ($price_type1 as $price_type2) {
            $price_type[] = mosHtml::makeOption($i, $price_type2);
            $i++;
        }
        $price_type_list = mosHTML :: selectList($price_type, 'price_type', 'class="inputbox" size="1" style="width: 100px"', 'value', 'text', _VEHICLE_MANAGER_LABEL_ALL);
        $params->def('price_type_list', $price_type_list);             
//extra6,extra7,extra8,extra9,extra10
        for($i=6;$i<=10;$i++){
        $extraOption='';
        $extraOption[] = mosHtml::makeOption(_VEHICLE_MANAGER_LABEL_ALL, _VEHICLE_MANAGER_LABEL_ALL);    
        $name = "_VEHICLE_MANAGER_EXTRA".$i."_SELECTLIST";
        $extra = explode(',', constant($name));	
        $j = 1;
        foreach($extra as $extr){
            $extraOption[] = mosHTML::makeOption($j, $extr);	 
        $j++;    
        }
        $extra_list[$i] = mosHTML :: selectList($extraOption, 'extra'.$i, 'class="inputbox" size="1" style="width: 100px"', 'value', 'text', '');
        $params->def('extrafield'.$i, $extra_list[$i]);
        }
        
//price
$query = "SELECT price FROM #__vehiclemanager_vehicles ";
$database->setQuery($query);
if (version_compare(JVERSION, "1.6.0", "lt")){
$prices = $database->loadResultArray();   
}else{
$prices = $database->loadColumn();
}
rsort($prices,SORT_NUMERIC);
$max_price = $prices[0];
?>

<div class="vehiclemanager_<?php if($moduleclass_sfx!='') echo $moduleclass_sfx;?>">
    <form action="<?php echo sefRelToAbs("index.php?option=com_vehiclemanager&amp;task=search_vehicle&amp;catid=0&amp;Itemid=".$ItemId); ?>" method="get" name="mod_vehiclelibsearchForm">
<?php 
    require(JModuleHelper::getLayoutPath('mod_vehicle_manager_search_free', $params->get('layout', 'default')));
    ?>		
    </form>
</div>
<div style="text-align: center;"><a href="http://ordasoft.com" style="font-size: 10px;">Powered by OrdaSoft!</a></div>