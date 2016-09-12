<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
/**
*
* @package VehicleManager
* @copyright 2012 Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); Rob de Cleen(rob@decleen.com)
* Homepage: http://www.ordasoft.com
* @version: 2.3 Pro
*
**/

$path = JPATH_BASE.DS.'components'.DS.'com_vehiclemanager'.DS.'functions.php';
if (!file_exists($path)){
  echo "To display the featured books You have to install VehicleManager first<br />"; exit;
} else{
  require_once($path);
}
$database = JFactory::getDBO();
$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::base(true).DS.'components'.DS.'com_vehiclemanager'.DS.'includes'.DS.
'vehiclemanager.css');
$menu = new JSite;
$menu->getMenu();
require_once ( JPATH_BASE .DS.'components'.DS.'com_vehiclemanager'.DS.'functions.php' );
$mosConfig_absolute_path = $GLOBALS['mosConfig_absolute_path'] = JPATH_SITE;
global $vehiclemanager_configuration;

// load language
$languagelocale = "";
$query = "SELECT l.title, l.lang_code, l.sef ";
$query .= "FROM #__vehiclemanager_const_languages as cl ";
$query .= "LEFT JOIN #__vehiclemanager_languages AS l ON cl.fk_languagesid=l.id ";
$query .= "LEFT JOIN #__vehiclemanager_const AS c ON cl.fk_constid=c.id ";
$query .= "GROUP BY  l.title";
$database->setQuery($query);
$languages = $database->loadObjectList();

$lang = JFactory::getLanguage();
foreach ($lang->getLocale() as $locale) {
    foreach ($languages as $language) {
        if (strtolower($locale) == strtolower($language->title)
            || strtolower($locale) == strtolower($language->lang_code)
            || strtolower($locale) == strtolower($language->sef) ) {
            $mosConfig_lang = $locale;
            $languagelocale = $language->lang_code;
            break;
        }
    }
}

if ($languagelocale == '')
    $languagelocale = "en-GB";

global $langContent;
$langContent = substr($languagelocale, 0, 2);

$query = "SELECT c.const, cl.value_const ";
$query .= "FROM #__vehiclemanager_const_languages as cl ";
$query .= "LEFT JOIN #__vehiclemanager_languages AS l ON cl.fk_languagesid=l.id ";
$query .= "LEFT JOIN #__vehiclemanager_const AS c ON cl.fk_constid=c.id ";
$query .= "WHERE l.lang_code = '$languagelocale'";
$database->setQuery($query);
$langConst = $database->loadObjectList();

foreach ($langConst as $item) {
   if(!defined($item->const) )  define($item->const, $item->value_const); // $database->quote()
}

//Common parameters
$show_image = $params->get('image');
$image_height = $params->get('image_height');
$image_width = $params->get('image_width');
$show_hits = $params->get('hits');
$price = $params->get('price', 0);
$status = $params->get('status', 0);
$location = $params->get('location', 0 );
$featured_clicks = $params->get('featured_clicks', 0);
$features = $params->get('features', 0);
$description = $params->get('description', 0);
$view_listing = $params->get('view_listing', 0);
$categories = $params->get('categories', 0);
//Individual parameters
$count = intval($params->get('count',1));
$cat_id = $params->get('cat_id',0);
$vehicle_id = $params->get('vehicle_id',0);
//Display type
$displaytype = $params->get('displaytype', 0);
//Advanced parameters
$class_suffix = $params->get('moduleclass_sfx', 1);
$Itemid_from_params = $params->get('ItemId');
$g_words = $params->get('words','');
$sortnewby  = $params->get ('sortnewby', 0);
$image_source_type = $params->get('image_source_type');
//realestate

if (!function_exists('searchPicture_vehiclemanager')){
function searchPicture_vehiclemanager ($image_source_type,$imageURL){
			
	global $vehiclemanager_configuration;
	
            switch ($image_source_type) {
                case "0": $img_height = $vehiclemanager_configuration['fotomain']['high'];
                    $img_width = $vehiclemanager_configuration['fotomain']['width'];
                    break;
                case "1": $img_height = $vehiclemanager_configuration['foto']['high'];
                    $img_width = $vehiclemanager_configuration['foto']['width'];
                    break;
                case "2": $img_height = $vehiclemanager_configuration['fotogallery']['high'];
                    $img_width = $vehiclemanager_configuration['fotogallery']['width'];
                    break;					
                default:$img_height = $vehiclemanager_configuration['fotomain']['high'];
                    $img_width = $vehiclemanager_configuration['fotomain']['width'];
                    break;
            }
			 
            $imageURL1 = vm_picture_thumbnail($imageURL, $img_height, $img_width);			
												
            $imageURL = "/components/com_vehiclemanager/photos/" . $imageURL1;
            return $imageURL;
            
    }  
}

switch($sortnewby) {
case 0:
    $sql_orderby_query = "id"; 
    $sql_where_query =  "";  // Last Added
    break;
case 1:
    $sql_orderby_query  = "hits";     // By features
    $sql_where_query = "and (v.featured_clicks > 0 or v.featured_shows > 0)";
    break;
case 2:
    $sql_orderby_query  = "hits";     // Top (most popular)
    $sql_where_query = "";
    break;
case 3:
    $sql_orderby_query  = "rand()";     // Random (most popular)
    $sql_where_query = "";
    break;
}

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

$database->SetQuery("SELECT id 
                    FROM #__menu 
                    WHERE link 
                    LIKE'%option=com_vehiclemanager%' 
                    AND params 
                    LIKE '%back_button%'");
$Itemid_from_db = $database->loadResult();
if ($Itemid_from_params!=''){
    $Itemid = $Itemid_from_params;
} else{
    $Itemid = $Itemid_from_db;
}
$sql_published = "published = 1";

$s = vmLittleThings::getWhereUsergroupsCondition("c");

$cat_sel = "";
$vehicle_ids = "";
if ($cat_id != 0){
    $cat_sel = " AND c.id IN (".$cat_id.")";
} else {
    if ($vehicle_id != 0){
        $vehicle_ids = " AND v.id IN (".$vehicle_id.")";
    } 
}

if ($cat_id != 0 && $vehicle_id != 0){
  echo ('<font color="#CC0000">You input IDs of categories and houses together! Correct this mistake.</font>');
}

$query = "SELECT v.vtitle, v.id, v.image_link, v.hits,  c.id AS catid, c.title AS cattitle, v.price, v.published, v.priceunit,
v.vlocation,v.city,v.country, v.featured_clicks, v.featured_shows, v.maker, v.vmodel, v.year, v.mileage,v.description, v.listing_type
        \n FROM #__vehiclemanager_vehicles AS v
        \n LEFT JOIN #__vehiclemanager_categories AS hc ON hc.iditem=v.id
        \n LEFT JOIN #__vehiclemanager_main_categories AS c ON c.id=hc.idcat
        \n WHERE (".$s.") $lang ".$sql_where_query."";
$query_flag = true;
if ((isset($count) AND $count > 0) AND $cat_sel == "" AND $sql_published == "" AND $vehicle_ids != ""){
    $vehicle_ids = " AND " . $vehicle_ids;
} elseif ((isset($count) AND $count > 0) AND $cat_sel == "" AND $sql_published != "" AND $vehicle_ids != ""){
    $query .= " AND c.".$sql_published." AND v.".$sql_published;
} elseif ((isset($count) AND $count > 0) AND $cat_sel != "" AND $sql_published == "" AND $vehicle_ids == ""){
    $cat_sel = " AND ".$cat_sel; 
} elseif ((isset($count) AND $count > 0) AND $cat_sel != "" AND $sql_published != "" AND $vehicle_ids == ""){
    $query .= " AND c.".$sql_published." AND v.".$sql_published;
} elseif ((isset($count) AND $count > 0) AND $cat_sel == "" AND $sql_published != "" AND $vehicle_ids == ""){
    $query .= " AND c.".$sql_published." AND v.".$sql_published; 
} 
$query .= $cat_sel.$vehicle_ids." AND v.approved=1 GROUP BY v.id ORDER BY ".$sql_orderby_query." DESC LIMIT 0 , ".$count.";";  
if ($query_flag){
    $database->setQuery($query);
    $vehicles= $database->loadObjectList();
}

if($vehicles!=="" && $vehicles!==Null && count($vehicles)>0){
  require JModuleHelper::getLayoutPath('mod_vehiclemanager_featured_free_j3', $params->get('layout'));
} ?>
<div style="text-align: center;"><a href="http://ordasoft.com" style="font-size: 10px;">Powered by OrdaSoft!</a></div>