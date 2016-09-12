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
$mosConfig_absolute_path = $GLOBALS['mosConfig_absolute_path'];
require_once ($mosConfig_absolute_path .
"/components/com_vehiclemanager/vehiclemanager.main.categories.class.php");
require_once ($mosConfig_absolute_path .
"/components/com_vehiclemanager/vehiclemanager.class.feature.php");

function print_vars($obj)
{
  $arr = get_object_vars($obj);
  while (list($prop, $val) = each($arr))
      if (class_exists($val))
          print_vars($val);
      else
          echo "\t $prop = $val\n<br />";
}

function print_methods($obj)
{
  $arr = get_class_methods(get_class($obj));
  foreach ($arr as $method)
      echo "\tfunction $method()\n <br />";
}

if (PHP_VERSION >= 5)
{

  // Emulate the old xslt library functions
  function xslt_create()
  {
      return new XsltProcessor();
  }

  function xslt_process($xsltproc, $xml_arg, $xsl_arg, $xslcontainer = null, $args = null, $params = null)
  {
      // Create instances of the DomDocument class
      $xml = new DomDocument;
      $xsl = new DomDocument;

      // Load the xml document and the xsl template
      $xml->load($xml_arg);
      $xsl->load($xsl_arg);

      // Load the xsl template
      $xsltproc->importStyleSheet($xsl);

      // Set parameters when defined
      if ($params)
          foreach ($params as $param => $value)
              $xsltproc->setParameter("", $param, $value);

      // Start the transformation
      $processed = $xsltproc->transformToXML($xml);

      // Put the result in a file when specified
      if ($xslcontainer)
          return @file_put_contents($xslcontainer, $processed); else
          return $processed;
  }

  function xslt_free($xsltproc)
  {
      unset($xsltproc);
  }

}

class mosVehicleManagerImportExport
{

  /**
   * Imports the lines given to this method into the database and writes a
   * table containing the information of the imported vehicles.
   * The imported vehicles will be set to [not published] 
   * Format: #;id;isbn;title;author;language
   * @param array lines - an array of lines read from the file
   * @param int catid - the id of the category the vehicles should be added to 
   */
  static function importVehiclesCSV($lines, $catid)
  { 
      
      
      global $database;
      $retVal = array();
      $i = 0;
      
      foreach ($lines as $line) {
          $tmp = array();
          if (trim($line) == "") continue;
          
          $line = explode('|', $line);
          $vehicle = new mosVehicleManager($database);
          
          $vehicle->asset_id = $line[0];
          $vehicle->vehicleid = trim($line[1]);
          //$vehicle->catid = trim($line[2]);
          $vehicle->sid = trim($line[3]);
          $vehicle->fk_rentid = trim($line[4]);
          $vehicle->description = $line[5];
          $vehicle->link = $line[6];
          $vehicle->listing_type = $line[7];
          if (($vehicle->listing_type) != ''){
              $listing_type[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
              $listing_type[_VEHICLE_MANAGER_OPTION_FOR_RENT] = 1;
              $listing_type[_VEHICLE_MANAGER_OPTION_FOR_SALE] = 2;
              $vehicle->listing_type = $listing_type[$vehicle->listing_type];
          }
          else{
              $vehicle->listing_type = 0;
          }
              
          $vehicle->price = $line[8];
          $vehicle->priceunit = $line[9];
          $vehicle->vtitle = $line[10];
          $vehicle->maker = $line[11];
          $vehicle->vmodel = $line[12];
          $vehicle->vtype = $line[13];
          if (($vehicle->vtype) != ''){
              $vtype[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
              $vtype1 = explode(',', _VEHICLE_MANAGER_OPTION_VEHICLE_TYPE);
              $k = 1;
              foreach ($vtype1 as $vtype2) {
                  $vtype[$vtype2] = $k;
                  $k++;
              }
              $vehicle->vtype = $vtype[$vehicle->vtype];
          } else{ $vehicle->vtype = 0; }
              
          $vehicle->vlocation = $line[14];
          $vehicle->vlatitude = $line[15];
          $vehicle->vlongitude = $line[16];
          $vehicle->map_zoom = $line[17];
          $vehicle->contacts = $line[18];
          $vehicle->year = $line[19];
          $vehicle->vcondition = $line[20];
          if (($vehicle->vcondition) != ''){
              $vcondition[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
              $vcondition1 = explode(',', _VEHICLE_MANAGER_OPTION_VEHICLE_CONDITION);
              $k = 1;
              foreach ($vcondition1 as $vcondition2) {
                  $vcondition[$vcondition2] = $k;
                  $k++;
              }
              $vehicle->vcondition = $vcondition[$vehicle->vcondition];
          } else{
              $vehicle->vcondition = 0;
          }
              
          $vehicle->mileage = $line[21];
          $vehicle->image_link = $line[22];
          $vehicle->listing_status = $line[23];
          if (($vehicle->listing_status) != ''){
              $listing_status[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
              $listing_status1 = explode(',', _VEHICLE_MANAGER_OPTION_LISTING_STATUS);
              $k = 1;
              foreach ($listing_status1 as $listing_status2) {
                  $listing_status[$listing_status2] = $k;
                  $k++;
              }
              $vehicle->listing_status = $listing_status[$vehicle->listing_status];
          } else{ $vehicle->listing_status = 0; }
              
          $vehicle->price_type = $line[24];
          if (($vehicle->price_type) != '')
          {
              $price_type[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
              $price_type1 = explode(',', _VEHICLE_MANAGER_OPTION_PRICE_TYPE);
              $k = 1;
              foreach ($price_type1 as $price_type2) {
                  $price_type[$price_type2] = $k;
                  $k++;
              }
              $vehicle->price_type = $price_type[$vehicle->price_type];
          }
          else{ $vehicle->price_type = 0; }
                  
          $vehicle->transmission = $line[25];
          if (($vehicle->transmission) != ''){
              $transmission[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
              $transmission1 = explode(',', _VEHICLE_MANAGER_OPTION_TRANSMISSION);
              $k = 1;
              foreach ($transmission1 as $transmission2) {
                  $transmission[$transmission2] = $k;
                  $k++;
              }
              $vehicle->transmission = $transmission[$vehicle->transmission];
          }
          else{ $vehicle->transmission = 0; }
              
          $vehicle->num_speed = $line[26];
          if (($vehicle->num_speed) != ''){
              $num_speed[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
              $num_speed1 = explode(',', _VEHICLE_MANAGER_OPTION_NUMBER_OF_SPEEDS);
              $k = 1;
              foreach ($num_speed1 as $num_speed2) {
                  $num_speed[$num_speed2] = $k;
                  $k++;
              }
              $vehicle->num_speed = $num_speed[$vehicle->num_speed];
          }
          else{ $vehicle->num_speed = 0; }
          
          $vehicle->interior_color = $line[27];
          $vehicle->exterior_color = $line[28];
          $vehicle->doors = $line[29];
          if (($vehicle->doors) != '')
          {
              $doors[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
              $doors1 = explode(',', _VEHICLE_MANAGER_OPTION_NUMBER_OF_DOORS);
              $k = 1;
              foreach ($doors1 as $doors2) {
                  $doors[$doors2] = $k;
                  $k++;
              }
              $vehicle->doors = $doors[$vehicle->doors];
          }
          else{
              $vehicle->doors = 0;
          }
          
          $vehicle->engine = $line[30];
          $vehicle->fuel_type = $line[31];
          if (($vehicle->fuel_type) != ''){
              $fuel_type[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
              $fuel_type1 = explode(',', _VEHICLE_MANAGER_OPTION_FUEL_TYPE);
              $k = 1;
              foreach ($fuel_type1 as $fuel_type2) {
                  $fuel_type[$fuel_type2] = $k;
                  $k++;
              }
              $vehicle->fuel_type = $fuel_type[$vehicle->fuel_type];
          }
          else{
              $vehicle->fuel_type = 0;
          }
          
          $vehicle->drive_type = $line[32];
          if (($vehicle->drive_type) != ''){
              $drive_type[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
              $drive_type1 = explode(',', _VEHICLE_MANAGER_OPTION_DRIVE_TYPE);
              $k = 1;
              foreach ($drive_type1 as $drive_type2) {
                  $drive_type[$drive_type2] = $k;
                  $k++;
              }
              $vehicle->drive_type = $drive_type[$vehicle->drive_type];
          }
          else{ $vehicle->drive_type = 0; }
              
          $vehicle->cylinder = $line[33];
          if (($vehicle->cylinder) != '')
          {
              $cylinder[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
              $cylinder1 = explode(',', _VEHICLE_MANAGER_OPTION_NUMBER_OF_CYLINDERS);
              $k = 1;
              foreach ($cylinder1 as $cylinder2) {
                  $cylinder[$cylinder2] = $k;
                  $k++;
              }
              $vehicle->cylinder = $cylinder[$vehicle->cylinder];
          }
          else {$vehicle->cylinder = 0;}
          
          $vehicle->wheelbase = $line[34];
          $vehicle->seating = $line[35];
          $vehicle->city_fuel_mpg = $line[36];
          $vehicle->highway_fuel_mpg = $line[37];
          $vehicle->wheeltype = $line[38];
          $vehicle->rear_axe_type = $line[39];
          $vehicle->brakes_type = $line[40];
          $vehicle->exterior_amenities = $line[41];
          $vehicle->dashboard_options = $line[42];
          $vehicle->interior_amenities = $line[43];
          $vehicle->safety_options = $line[44];
          $vehicle->w_basic = $line[45];
          $vehicle->w_drivetrain = $line[46];
          $vehicle->w_corrosion = $line[47];
          $vehicle->w_roadside_ass = $line[48];
          $vehicle->checked_out = $line[49];
          $vehicle->checked_out_time = $line[50];
          $vehicle->ordering = $line[51];
          $vehicle->date = $line[52];
          $vehicle->hits = $line[53];
          $vehicle->edok_link = $line[54];
          $vehicle->published = $line[55];
          $vehicle->approved = $line[56];
          $vehicle->country = $line[57];
          $vehicle->region = $line[58];
          $vehicle->city = $line[59];
          $vehicle->district = $line[60];
          $vehicle->zipcode = $line[61];
          $vehicle->owneremail = $line[62];
          $vehicle->language = $line[63];
          $vehicle->featured_clicks = $line[64];
          $vehicle->featured_shows = $line[65];
          $vehicle->extra1 = $line[66];
          $vehicle->extra2 = $line[67];
          $vehicle->extra3 = $line[68];
          $vehicle->extra4 = $line[69];
          $vehicle->extra5 = $line[70];
          $vehicle->extra6 = $line[71];
          $vehicle->extra7 = $line[72];
          $vehicle->extra8 = $line[73];
          $vehicle->extra9 = $line[74];
          $vehicle->extra10 = $line[75];
          $vehicle->video_link = $line[76];
          $vehicle->owner_id = $line[77];         
          
          $tmp[0] = $i;
          $tmp[1] = trim($vehicle->vehicleid);
          $tmp[2] = $vehicle->vtitle;
          $tmp[3] = $vehicle->vmodel;
          $tmp[4] = $vehicle->price . ' ' . $vehicle->priceunit;
          
          
          //print_r($catid); exit;
          
          if(!$vehicle->check() || !$vehicle->store()){
              $tmp[5] = $vehicle->getError();
          }
          else{
              $tmp[5] = "OK";
              $vehicle->saveCatIds($catid);
          }
          
          $retVal[$i] = $tmp;
          $i++;
      
          
      }
      return $retVal;
      
  }

  static function getXMLItemValue($item, $item_name)
  {
      $vehicle_items = $item->getElementsByTagname($item_name);
      $vehicle_item = $vehicle_items->item(0);
      if (NULL != $vehicle_item)
          return $vehicle_item->nodeValue; else
          return "";
  }

  static function findCategory(& $categories, $new_category)
  {
      global $database;
      foreach ($categories as $category)
          if ($category->old_id == $new_category->old_id)
              return $category;
      $new_parent_id = -1;
      if (isset($new_category->old_parent_id) && $new_category->old_parent_id != 0)
      {
          foreach ($categories as $category) {
              if ($category->old_id == $new_category->old_parent_id)
              {
                  $new_parent_id = $category->id;
                  break;
              }
          }
      } else
          $new_parent_id = 0;

      //sanity test
      if ($new_parent_id === -1)
      {
          echo "error in import !";
          exit;
      }
      $row = new mainVehiclemanagerCategories($database); // for 1.6
      $row->section = 'com_vehiclemanager';
      $row->parent_id = $new_parent_id;
      if(isset($new_category->name))
        $row->name = $new_category->name;
      if(isset($new_category->title))
        $row->title = $new_category->title;
      if(isset($new_category->published))
        $row->published = $new_category->published;
      if(isset($new_category->params))
        $row->params = $new_category->params;
      if(isset($new_category->params2))
        $row->params2 = $new_category->params2;
      if(isset($new_category->language))
        $row->language = $new_category->language;
      if(isset($new_category->associate_category))
        $row->associate_category = $new_category->associate_category;

      if (!$row->check())
      {
          echo "error in import2 !";
          exit;
          exit();
      }
      if (!$row->store())
      {
          echo "error in import3 !";
          exit;
          exit();
      }

      $row->updateOrder("section='com_vehiclemanager' AND parent_id='$row->parent_id'");

      $new_category->id = $row->id;
      $categories[] = $new_category;

      return $new_category;
  }

  static function updateAssociateCategories($infoArr){

      $dataToUpdate = array();
      global $database;
      for($i = 0; $i < count($infoArr); $i++){
          if(isset($infoArr[$i]['associate']) && $infoArr[$i]['associate']){
              $currentAssocId = array();
              $newObjAssociate = unserialize($infoArr[$i]['associate']);

              foreach ($newObjAssociate as $key=>$value){

                  if($value && $value != 0){
                      for($j = 0; $j < count($infoArr); $j++){
                          if(isset($infoArr[$j]['oldId']) && $infoArr[$j]['oldId'] == $value){
                              $newObjAssociate[$key] = $infoArr[$j]['newId'];
                              $currentAssocId[] = $infoArr[$j]['newId'];
                              break;
                          }
                      }
                  }
              }
              $newSerializAssoc = serialize($newObjAssociate);
              $currentAssocIdToString = implode(',', $currentAssocId);
              if(!isset($dataToUpdate[$newSerializAssoc])){
                  $dataToUpdate[$newSerializAssoc] = $currentAssocIdToString;
              }
          }
      }
      if(!empty($dataToUpdate)){
          foreach ($dataToUpdate as $key=>$value){
            if(isset($key) && isset($value) ){
              $query = "UPDATE #__vehiclemanager_main_categories
                        SET associate_category = '$key'
                        WHERE id in ($value) ";
              $database->setQuery($query);
              $database->query();
            }
          }
      }
  }
  
  static function updateAssociateVehicle($infoArr){

      $dataToUpdate = array();
      global $database;
      for($i = 0; $i < count($infoArr); $i++){
          if(isset($infoArr[$i]['associate']) && $infoArr[$i]['associate']){
              $currentAssocId = array();
              $newObjassociateVehicle = unserialize($infoArr[$i]['associate']);

              foreach ($newObjassociateVehicle as $key=>$value){
//                        print_r($key);exit;
                  if($value && $value != 0){
                      for($j = 0; $j < count($infoArr); $j++){
                          if(isset($infoArr[$j]['oldId']) && $infoArr[$j]['oldId'] == $value){
                              $newObjassociateVehicle[$key] = $infoArr[$j]['newId'];
                              $currentAssocId[] = $infoArr[$j]['newId'];
                          }
                      }
                  }
              }
              $newSerializAssoc = serialize($newObjassociateVehicle);
              $currentAssocIdToString = implode(',', $currentAssocId);
              if(!isset($dataToUpdate[$newSerializAssoc])){
                  $dataToUpdate[$newSerializAssoc] = $currentAssocIdToString;
              }
          }
      }
      if(!empty($dataToUpdate)){
          foreach ($dataToUpdate as $key=>$value){
            if(isset($key) && isset($value) ){
              $query = "UPDATE #__vehiclemanager_vehicles
                        SET associate_vehicle = '$key'
                        WHERE id in ($value) ";

              $database->setQuery($query);
              $database->query();
            }
          }
      }


  }

  //******************   begin add for import XML format   ****************************
  static function importVehiclesXML($files_name_pars, $catid){
    global $database;
    $retVal = array();
    $k = 0;
    $new_categories = array();
    $new_features = array();
    $new_relate_ids = array();
    $dom = new domDocument('1.0', 'utf-8');
    $dom->load($files_name_pars);
    if ($catid === null){
      mosVehicleManagerImportExport::clearDatabase();
      $cat_list = $dom->getElementsByTagname('category');
      $associateSaveArr = array();
      for ($i = 0; $i < $cat_list->length; $i++) {
        $category = $cat_list->item($i);
        $new_category = new stdClass();
        if (mosVehicleManagerImportExport::getXMLItemValue($category, 'category_section')
            == 'com_vehiclemanager'){
          $new_category->old_id =
              mosVehicleManagerImportExport::getXMLItemValue($category, 'category_id');
          $new_category->old_parent_id =
              mosVehicleManagerImportExport::getXMLItemValue($category, 'category_parent_id');
          $new_category->old_asset_id =
              mosVehicleManagerImportExport::getXMLItemValue($category, 'category_asset_id');
          $new_category->name =
              mosVehicleManagerImportExport::getXMLItemValue($category, 'category_name');
          $new_category->title =
              mosVehicleManagerImportExport::getXMLItemValue($category, 'category_title');
          $new_category->alias =
              mosVehicleManagerImportExport::getXMLItemValue($category, 'category_alias');
          $new_category->image =
              mosVehicleManagerImportExport::getXMLItemValue($category, 'category_image');
          $new_category->section =
              mosVehicleManagerImportExport::getXMLItemValue($category, 'category_section');
          $new_category->image_position =
              mosVehicleManagerImportExport::getXMLItemValue($category, 'category_image_position');
          $new_category->description =
              mosVehicleManagerImportExport::getXMLItemValue($category, 'category_description');
          $new_category->published =
              mosVehicleManagerImportExport::getXMLItemValue($category, 'category_published');
          $new_category->checked_out =
              mosVehicleManagerImportExport::getXMLItemValue($category, 'category_checked_out');
          $new_category->checked_out_time =
              mosVehicleManagerImportExport::getXMLItemValue($category, 'category_checked_out_time');
          $new_category->editor =
              mosVehicleManagerImportExport::getXMLItemValue($category, 'editor');
          $new_category->ordering =
              mosVehicleManagerImportExport::getXMLItemValue($category, 'category_ordering');
          $new_category->access =
              mosVehicleManagerImportExport::getXMLItemValue($category, 'category_access');
          $new_category->count =
              mosVehicleManagerImportExport::getXMLItemValue($category, 'category_count');
          $new_category->language =
              mosVehicleManagerImportExport::getXMLItemValue($category, 'category_language');
          $new_category->params =
              mosVehicleManagerImportExport::getXMLItemValue($category, 'category_params');
          if ($new_category->params == '')
            $new_category->params = '-2';
          $new_category->params2 =
              mosVehicleManagerImportExport::getXMLItemValue($category, 'category_params2');
          $new_category->associate_category =
                mosVehicleManagerImportExport::getXMLItemValue($category, 'category_associate_category');
          $new_category = 
              mosVehicleManagerImportExport::findCategory($new_categories, $new_category);
          $ussuesArray = array();
          $ussuesArray["associate"] =
              mosVehicleManagerImportExport::getXMLItemValue($category, 'category_associate_category');
          $ussuesArray["oldId"] =
              mosVehicleManagerImportExport::getXMLItemValue($category, 'category_id');
          $ussuesArray["newId"] = $new_category->id;
          $associateSaveArr[] = $ussuesArray;
        }
      }
      //update accosiate for categoris
      mosVehicleManagerImportExport::updateAssociateCategories($associateSaveArr);
    }

    $feature_list = $dom->getElementsByTagname('feature');
    for ($i = 0; $i < $feature_list->length; $i++) {
      $feature = $feature_list->item($i);
      $new_feature = new mosVehicleManager_feature($database);
      $old_id = mosVehicleManagerImportExport::getXMLItemValue($feature, 'feature_id');
      $new_feature->name = mosVehicleManagerImportExport::getXMLItemValue($feature, 'feature_name');
      $new_feature->categories = mosVehicleManagerImportExport::getXMLItemValue($feature, 'feature_categories');
      $new_feature->published = mosVehicleManagerImportExport::getXMLItemValue($feature, 'feature_published');
      $new_feature->image_link = mosVehicleManagerImportExport::getXMLItemValue($feature, 'feature_image_link');
      if (!$new_feature->check() || !$new_feature->store()){
        $tmp[5] = $new_feature->getError();
      }else{
        $database->setQuery("UPDATE #__vehiclemanager_feature SET id = $old_id WHERE id = " . $new_feature->id . "");
        $database->query();
        $tmp[5] = "OK";
      }
    }

    $vehicle_list = $dom->getElementsByTagname('vehicle');
    $associateSaveArr = array();
    
    for ($i = 0; $i < $vehicle_list->length; $i++) {
      $vehicle_class = new mosVehicleManager($database);
      $vehicle = $vehicle_list->item($i);
      //get VehicleID
      $old_vehicle_id =
          mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'id');
      //$vehicle_class->vehicleid = $vehicle_id = $vehicle_class->getUnusedVehicleId();        
      $vehicle_class->vehicleid = $vehicle_id =
          mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'vehicleid');
      // get description
      $vehicle_description = $vehicle_class->description =
          mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'description');
      //get link
      $vehicle_class->link = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'link');
      //get model
      $vehicle_model = $vehicle_class->vmodel =
          mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'vmodel');
      //get vehicle type
      $vehicle_class->vtype = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'vtype');
      if (($vehicle_class->vtype) != ''){
        $vtype[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
        $vtype1 = explode(',', _VEHICLE_MANAGER_OPTION_VEHICLE_TYPE);
        $k = 1;
        foreach ($vtype1 as $vtype2) {
            $vtype[$vtype2] = $k;
            $k++;
        }
        $vehicle_class->vtype = $vtype[$vehicle_class->vtype];
      } else
          $vehicle_class->vtype = 0;
      //get listing_type
      $vehicle_class->listing_type =
          mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'listing_type');
      if (($vehicle_class->listing_type) != '')
      {
          $listing_type[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
          $listing_type[_VEHICLE_MANAGER_OPTION_FOR_RENT] = 1;
          $listing_type[_VEHICLE_MANAGER_OPTION_FOR_SALE] = 2;
          $vehicle_class->listing_type = $listing_type[$vehicle_class->listing_type];
      } else
          $vehicle_class->listing_type = 0;
      //get price
      $vehicle_price = $vehicle_class->price =
          mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'price');
      $vehicle_priceunit = $vehicle_class->priceunit =
          mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'priceunit');
      //get price_type
      $vehicle_class->price_type = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'price_type');
      if (($vehicle_class->price_type) != ''){
        $price_type[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
        $price_type1 = explode(',', _VEHICLE_MANAGER_OPTION_PRICE_TYPE);
        $k = 1;
        foreach ($price_type1 as $price_type2) {
          $price_type[$price_type2] = $k;
          $k++;
        }
        $vehicle_class->price_type = $price_type[$vehicle_class->price_type];
      } else
        $vehicle_class->price_type = 0;
      //get title
      $vehicle_title = $vehicle_class->vtitle =
          mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'vtitle');
      //get location
      $vehicle_class->vlocation = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'vlocation');
      //get vlatitude
      $vehicle_class->vlatitude = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'vlatitude');
      //get vlongitude
      $vehicle_class->vlongitude = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'vlongitude');
      //get map_zoom
      $vehicle_class->map_zoom = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'map_zoom');
      //get year
      $vehicle_class->year = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'year');
      //get vcondition
      $vehicle_class->vcondition = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'vcondition');
      if (($vehicle_class->vcondition) != ''){
        $vcondition[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
        $vcondition1 = explode(',', _VEHICLE_MANAGER_OPTION_VEHICLE_CONDITION);
        $k = 1;
        foreach ($vcondition1 as $vcondition2) {
          $vcondition[$vcondition2] = $k;
          $k++;
        }
        $vehicle_class->vcondition = $vcondition[$vehicle_class->vcondition];
      } else
        $vehicle_class->vcondition = 0;
      //get mileage
      $vehicle_class->mileage = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'mileage');
      //get listing_status
      $vehicle_class->listing_status =
       mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'listing_status');
      if (($vehicle_class->listing_status) != ''){
        $listing_status[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
        $listing_status1 = explode(',', _VEHICLE_MANAGER_OPTION_LISTING_STATUS);
        $k = 1;
        foreach ($listing_status1 as $listing_status2) {
          $listing_status[$listing_status2] = $k;
          $k++;
        }
        $vehicle_class->listing_status = $listing_status[$vehicle_class->listing_status];
      } else
        $vehicle_class->listing_status = 0;
      //get engine
      $vehicle_class->engine = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'engine');
      //get transmission
      $vehicle_class->transmission = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'transmission');
      if (($vehicle_class->transmission) != ''){
        $transmission[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
        $transmission1 = explode(',', _VEHICLE_MANAGER_OPTION_TRANSMISSION);
        $k = 1;
        foreach ($transmission1 as $transmission2) {
          $transmission[$transmission2] = $k;
          $k++;
        }
        $vehicle_class->transmission = $transmission[$vehicle_class->transmission];
      } else
        $vehicle_class->transmission = 0;
      //get drive_type
      $vehicle_class->drive_type = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'drive_type');
      if (($vehicle_class->drive_type) != ''){
        $drive_type[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
        $drive_type1 = explode(',', _VEHICLE_MANAGER_OPTION_DRIVE_TYPE);
        $k = 1;
        foreach ($drive_type1 as $drive_type2) {
          $drive_type[$drive_type2] = $k;
          $k++;
        }
        $vehicle_class->drive_type = $drive_type[$vehicle_class->drive_type];
      } else
        $vehicle_class->drive_type = 0;
      //get cylinder
      $vehicle_class->cylinder = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'cylinder');
      if (($vehicle_class->cylinder) != ''){
        $numcylinder[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
        $numcylinder1 = explode(',', _VEHICLE_MANAGER_OPTION_NUMBER_OF_CYLINDERS);
        $k = 1;
        foreach ($numcylinder1 as $numcylinder2) {
          $numcylinder[$numcylinder2] = $k;
          $k++;
        }
        $vehicle_class->cylinder = $numcylinder[$vehicle_class->cylinder];
      } else
        $vehicle_class->cylinder = 0;
      //get num_speed
      $vehicle_class->num_speed = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'num_speed');
      if (($vehicle_class->num_speed) != ''){
        $numspeed[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
        $numspeed1 = explode(',', _VEHICLE_MANAGER_OPTION_NUMBER_OF_SPEEDS);
        $k = 1;
        foreach ($numspeed1 as $numspeed2) {
          $numspeed[$numspeed2] = $k;
          $k++;
        }
        $vehicle_class->num_speed = $numspeed[$vehicle_class->num_speed];
      } else
        $vehicle_class->num_speed = 0;
      //get fuel_type
      $vehicle_class->fuel_type = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'fuel_type');
      if (($vehicle_class->fuel_type) != ''){
        $fuel_type[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
        $fuel_type1 = explode(',', _VEHICLE_MANAGER_OPTION_FUEL_TYPE);
        $k = 1;
        foreach ($fuel_type1 as $fuel_type2) {
          $fuel_type[$fuel_type2] = $k;
          $k++;
        }
        $vehicle_class->fuel_type = $fuel_type[$vehicle_class->fuel_type];
      } else
        $vehicle_class->fuel_type = 0;
      //get city_fuel_mpg
      $vehicle_class->city_fuel_mpg =
          mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'city_fuel_mpg');
      //get highway_fuel_mpg
      $vehicle_class->highway_fuel_mpg =
          mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'highway_fuel_mpg');
      //get wheelbase
      $vehicle_class->wheelbase =
          mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'wheelbase');
      //get wheeltype
      $vehicle_class->wheeltype =
          mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'wheeltype');
      //get rear_axe_type
      $vehicle_class->rear_axe_type =
          mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'rear_axe_type');
      //get brakes_type
      $vehicle_class->brakes_type =
          mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'brakes_type');
      //get exterior_color
      $vehicle_class->exterior_color =
          mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'exterior_color');
      //get doors
      $vehicle_class->doors = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'doors');
      if (($vehicle_class->doors) != ''){
        $numdoors[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
        $numdoors1 = explode(',', _VEHICLE_MANAGER_OPTION_NUMBER_OF_DOORS);
        $k = 1;
        foreach ($numdoors1 as $numdoors2) {
          $numdoors[$numdoors2] = $k;
          $k++;
        }
        $vehicle_class->doors = $numdoors[$vehicle_class->doors];
      } else
        $vehicle_class->doors = 0;
      //get exterior_amenities
      $vehicle_class->exterior_amenities =
          mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'exterior_amenities');
      //get interior_color
      $vehicle_class->interior_color =
          mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'interior_color');
      //get seating
      $vehicle_class->seating =
          mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'seating');
      //get dashboard_options
      $vehicle_class->dashboard_options =
          mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'dashboard_options');
      //get interior_amenities
      $vehicle_class->interior_amenities =
          mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'interior_amenities');
      //get safety_options
      $vehicle_class->safety_options =
          mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'safety_options');
      //get w_basic
      $vehicle_class->w_basic = 
          mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'w_basic');
      //get w_drivetrain
      $vehicle_class->w_drivetrain =
          mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'w_drivetrain');
      //get w_corrosion
      $vehicle_class->w_corrosion =
          mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'w_corrosion');
      //get w_roadside_ass
      $vehicle_class->w_roadside_ass =
          mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'w_roadside_ass');
      //get featured_clicks
      $vehicle_class->featured_clicks =
          mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'featured_clicks');
      //get featured_shows
      $vehicle_class->featured_shows =
          mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'featured_shows');
      //get hits
      $vehicle_class->hits = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'hits');
      //get image_link
      $vehicle_class->image_link = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'image_link');
      //get edoc
      $vehicle_class->edok_link = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'edoc');
      //get date
      $vehicle_class->date = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'date');
      //get published
      $vehicle_class->published = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'published');
      //get contacts
      $vehicle_class->contacts = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'contacts');
      $vehicle_class->owneremail = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'owneremail');
      $vehicle_class->language = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'language');
      $vehicle_class->maker = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'maker');
      $vehicle_class->country = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'country');
      $vehicle_class->region = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'region');
      $vehicle_class->city = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'city');
      $vehicle_class->district = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'district');
      $vehicle_class->zipcode = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'zipcode');
      $vehicle_class->extra1 = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'extra1');
      $vehicle_class->extra2 = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'extra2');
      $vehicle_class->extra3 = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'extra3');
      $vehicle_class->extra4 = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'extra4');
      $vehicle_class->extra5 = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'extra5');
      $vehicle_class->extra6 = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'extra6');
      $vehicle_class->extra7 = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'extra7');
      $vehicle_class->extra8 = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'extra8');
      $vehicle_class->extra9 = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'extra9');
      $vehicle_class->extra10 = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'extra10');            
      if ($catid === null){
        //get associate_vehicle
        $vehicle_class->associate_vehicle =
            mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'associate_vehicle');
      }
      //get category
      if ($catid === null){
        $new_category = new stdClass();
        $catidsxml = $vehicle->getElementsByTagname('catid');
        $tempcat = array();
        for ($t = 0; $t < $catidsxml->length; $t++) {
          $tempxml[$t] = $catidsxml->item($t);
          $new_category = new stdClass();
          $new_category->old_id = $tempxml[$t]->nodeValue;
          $new_category = mosVehicleManagerImportExport::findCategory($new_categories, $new_category);
          $tempcat[] = $new_category->id;
        }
      }else{
        $tempcat = array();
        $tempcat = $catid;
      }
      //for output rezult in table
      $tmp[0] = $i;
      $tmp[1] = $vehicle_id;
      $tmp[2] = $vehicle_title;
      $tmp[3] = $vehicle_model;
      $tmp[4] = $vehicle_price . ' ' . $vehicle_priceunit;
      if (!$vehicle_class->check() || !$vehicle_class->store()){
        $tmp[5] = $vehicle_class->getError();
      }else{
        $vehicle_class->saveCatIds($tempcat);
        $tmp[5] = "OK";
      }
      if ($catid === null){
        $vehicle_class->checkin();
      }
      $retVal[$i] = $tmp;
      $ussuesArray = array();
      $ussuesArray["associate"] = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'associate_vehicle');
      $ussuesArray["oldId"] = mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'id');
      $ussuesArray["newId"] = $vehicle_class->id;
      $associateSaveArr[] = $ussuesArray;

      //get Reviews
      if ($tmp[5] == "OK" && mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'reviews') != ""){
        $review_list = $vehicle->getElementsByTagname('review');
        for ($j = 0; $j < $review_list->length; $j++) {
          $review = $review_list->item($j);
          $review_user_name = mosVehicleManagerImportExport::getXMLItemValue($review, 'user_name');
          $review_user_email = mosVehicleManagerImportExport::getXMLItemValue($review, 'user_email');
          $review_date = mosVehicleManagerImportExport::getXMLItemValue($review, 'date');
          $review_rating = mosVehicleManagerImportExport::getXMLItemValue($review, 'rating');
          $review_title = mosVehicleManagerImportExport::getXMLItemValue($review, 'title');
          $review_comment = mosVehicleManagerImportExport::getXMLItemValue($review, 'comment');
          $review_published = mosVehicleManagerImportExport::getXMLItemValue($review, 'published');
          //insert data in table review
          $database->setQuery("INSERT INTO #__vehiclemanager_review" .
                  "\n (fk_vehicleid, user_name,user_email, date, rating, title, comment, published)" .
                  "\n VALUES " .
                  "\n (" . $vehicle_class->id . ", '" . $review_user_name . "', '" . $review_user_email .
                  "', '" . $review_date . "'," . $review_rating . ",'" . $review_title .
                   "', '" . $review_comment . "', '" . $review_published . "');");
          $database->query();
        } //end for(...) - REVIEW
      } //end if(...) - REVIEW
      //get rents
      if ($tmp[5] == "OK" && mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'rents') != ""){
        $rent_list = $vehicle->getElementsByTagname('rent');
        for ($j = 0; $j < $rent_list->length; $j++) {
          $rent = $rent_list->item($j);
          $help = new mosVehicleManager_rent($database);
          $help->fk_vehicleid = $vehicle_class->id;
          $help->rent_from = mosVehicleManagerImportExport::getXMLItemValue($rent, 'rent_from');
          $help->rent_until = mosVehicleManagerImportExport::getXMLItemValue($rent, 'rent_until');
          $rent_return = mosVehicleManagerImportExport::getXMLItemValue($rent, 'rent_return');
          $help->user_name = mosVehicleManagerImportExport::getXMLItemValue($rent, 'user_name');
          $help->user_email = mosVehicleManagerImportExport::getXMLItemValue($rent, 'user_email');
          $help->user_mailing = mosVehicleManagerImportExport::getXMLItemValue($rent, 'user_mailing');
          if (empty($rent_return)){
            $help->rent_return = new stdClass();
          }else{
            $help->rent_return = $rent_return;
          }

          //insert data in table #__vehiclemanager_rent
          if (!$help->check() || !$help->store()){
            $tmp[5] = $help->getError();
          }else{
            $vehicle_class->fk_rentid = $help->id;
            if (!$vehicle_class->check() || !$vehicle_class->store()){
              $tmp[5] = $vehicle_class->getError();
            }else{
              $tmp[5] = "OK";
            }
          }
        } //end for(...) - rent
      } //end if(...) - rent
      //get rentrequests
      if ($tmp[5] == "OK" && mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'rentrequests') != ""){
        $rentrequests_list = $vehicle->getElementsByTagname('rentrequest');
        for ($j = 0; $j < $rentrequests_list->length; $j++) {
          $rentrequest = $rentrequests_list->item($j);
          $rr_rent_from = mosVehicleManagerImportExport::getXMLItemValue($rentrequest, 'rent_from');
          $rr_rent_until = mosVehicleManagerImportExport::getXMLItemValue($rentrequest, 'rent_until');
          $rr_rent_request = mosVehicleManagerImportExport::getXMLItemValue($rentrequest, 'rent_request');
          $rr_user_name = mosVehicleManagerImportExport::getXMLItemValue($rentrequest, 'user_name');
          $rr_user_email = mosVehicleManagerImportExport::getXMLItemValue($rentrequest, 'user_email');
          $rr_user_mailing = mosVehicleManagerImportExport::getXMLItemValue($rentrequest, 'user_mailing');
          $rr_status = mosVehicleManagerImportExport::getXMLItemValue($rentrequest, 'status');
          //insert data in table jos_vehiclemanager_rent_request
          $database->setQuery("INSERT INTO #__vehiclemanager_rent_request" .
            "\n (fk_vehicleid, rent_from,rent_until, rent_request, user_name, user_email, user_mailing,status)" .
            "\n VALUES " .
            "\n (" . $vehicle_class->id . ", '" . $rr_rent_from . "', '" . $rr_rent_until .
            "', '" . $rr_rent_request . "','" . $rr_user_name . "','" . $rr_user_email . "', '" . $rr_user_mailing .
            "', '" . $rr_status . "');");
          $database->query();
        } //end for(...) - rentrequest
      } //end if(...) - rentrequest
      
      //get buyingrequests
      if ($tmp[5] == "OK" && mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'buyingrequests') != ""){
        $buyingrequests_list = $vehicle->getElementsByTagname('buyingrequest');
        for ($j = 0; $j < $buyingrequests_list->length; $j++) {
          $buyingrequest = $buyingrequests_list->item($j);
          $br_buying_request = mosVehicleManagerImportExport::getXMLItemValue($buyingrequest, 'buying_request');
          $br_customer_name = mosVehicleManagerImportExport::getXMLItemValue($buyingrequest, 'customer_name');
          $br_customer_email = mosVehicleManagerImportExport::getXMLItemValue($buyingrequest, 'customer_email');
          $br_customer_phone = mosVehicleManagerImportExport::getXMLItemValue($buyingrequest, 'customer_phone');
          $br_status = mosVehicleManagerImportExport::getXMLItemValue($buyingrequest, 'status');
          //insert data in table jos_vehiclemanager_buying_request
          $database->setQuery("INSERT INTO #__vehiclemanager_buying_request" .
                  "\n (fk_vehicleid, buying_request, customer_name, customer_email, customer_phone,status)" .
                  "\n VALUES " .
                  "\n (" . $vehicle_class->id .
                  ", '" . $br_buying_request . "','" . $br_customer_name . "','" .
                   $br_customer_email . "', '" . $br_customer_phone .
                  "', '" . $br_status . "');");
          $database->query();
        } //end for(...) - $buyingrequest
      } //end if(...) - $buyingrequest
    
      //get images
      if ($tmp[5] == "OK" && mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'images') != ""){
        $images_list = $vehicle->getElementsByTagname('image');
        for ($j = 0; $j < $images_list->length; $j++) {
          $image = $images_list->item($j);
          $image_thumbnail_img = mosVehicleManagerImportExport::getXMLItemValue($image, 'thumbnail_img');
          $image_main_img = mosVehicleManagerImportExport::getXMLItemValue($image, 'main_img');
          //insert data in table jos_vehiclemanager_photos
          $database->setQuery("INSERT INTO #__vehiclemanager_photos" .
                  "\n (fk_vehicleid, thumbnail_img, main_img)" .
                  "\n VALUES " .
                  "\n (" . $vehicle_class->id .
                  ", '" . $image_thumbnail_img . "','" . $image_main_img . "');");
          $database->query();
        } //end for(...) - images
      } //end if(...) - images

      //get videos
      if ($tmp[5] == "OK" && mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'videos') != ""){
        $videos_list = $vehicle->getElementsByTagname('video');
        for ($j = 0; $j < $videos_list->length; $j++) {
          $video = $videos_list->item($j);
          $sequence_number = mosVehicleManagerImportExport::getXMLItemValue($video, 'sequence_number');
          $src = mosVehicleManagerImportExport::getXMLItemValue($video, 'src');
          $type = mosVehicleManagerImportExport::getXMLItemValue($video, 'type');
          $media = mosVehicleManagerImportExport::getXMLItemValue($video, 'media');
          $youtube = mosVehicleManagerImportExport::getXMLItemValue($video, 'youtube');
          //insert data in table jos_vehiclemanager_photos
          $database->setQuery("INSERT INTO #__vehiclemanager_video_source" .
                  "\n (fk_vehicle_id, sequence_number, src, type, media, youtube)" .
                  "\n VALUES " .
                  "\n (" . $vehicle_class->id .",
                  '" . $sequence_number . "',
                  '" . $src . "',
                  '" . $type . "',
                  '" . $media . "',
                  '" . $youtube . "');");
          $database->query();
        } //end for(...) - videos
      } //end if(...) - videos

      //get tracks
      if ($tmp[5] == "OK" && mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'tracks') != ""){
        $tracks_list = $vehicle->getElementsByTagname('track');
        for ($j = 0; $j < $tracks_list->length; $j++) {
          $track = $tracks_list->item($j);
          $sequence_number = mosVehicleManagerImportExport::getXMLItemValue($track, 'sequence_number');
          $src = mosVehicleManagerImportExport::getXMLItemValue($track, 'src');
          $kind = mosVehicleManagerImportExport::getXMLItemValue($track, 'kind');
          $scrlang = mosVehicleManagerImportExport::getXMLItemValue($track, 'scrlang');
          $label = mosVehicleManagerImportExport::getXMLItemValue($track, 'label');
          //insert data in table jos_vehiclemanager_photos
          $database->setQuery("INSERT INTO #__vehiclemanager_track_source" .
                  "\n (fk_vehicle_id, sequence_number, src, kind, scrlang, label)" .
                  "\n VALUES " .
                  "\n (" . $vehicle_class->id .", 
                  '" . $sequence_number . "',
                  '" . $src . "',
                  '" . $kind . "',
                  '" . $scrlang . "',
                  '" . $label . "');");
          $database->query();
        } //end for(...) - tracks
      } //end if(...) - tracks
      
      if ($tmp[5] == "OK" && mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'rent_sal') != "") {
        $rent_sal_list = $vehicle->getElementsByTagname('rent_sal');
        for ($j = 0; $j < $rent_sal_list->length; $j++) {
          $rent_sal = $rent_sal_list->item($j);
          $help_monthW = mosVehicleManagerImportExport::getXMLItemValue($rent_sal, 'monthW');                 
          $help_yearW = mosVehicleManagerImportExport::getXMLItemValue($rent_sal, 'yearW');         
          $help_week = mosVehicleManagerImportExport::getXMLItemValue($rent_sal, 'week');                 
          $help_weekend = mosVehicleManagerImportExport::getXMLItemValue($rent_sal, 'weekend');              
          $help_midweek = mosVehicleManagerImportExport::getXMLItemValue($rent_sal, 'midweek');
          $help_price_from = mosVehicleManagerImportExport::getXMLItemValue($rent_sal, 'price_from');
          $help_price_to = mosVehicleManagerImportExport::getXMLItemValue($rent_sal, 'price_to');
          $help_special_price = mosVehicleManagerImportExport::getXMLItemValue($rent_sal, 'special_price');
          $help_comment_price = mosVehicleManagerImportExport::getXMLItemValue($rent_sal, 'comment_price');
          $help_priceunit = mosVehicleManagerImportExport::getXMLItemValue($rent_sal, 'priceunit');
          //insert data in table #__rem_rent_sal
          $database->setQuery("INSERT INTO #__vehiclemanager_rent_sal" .
              "\n ( fk_vehiclesid, monthW, yearW, week, weekend, midweek, price_from," .
              " price_to, special_price, comment_price, priceunit)" .
              "\n VALUES " . " (" . $vehicle_class->id .
              ", '" . $help_monthW . "','" . $help_yearW .
              "', '" . $help_week . "','" . $help_weekend .
              "', '" . $help_midweek . "','" . $help_price_from .
              "', '" . $help_price_to . "','" . $help_special_price .
              "', " . $database->Quote($help_comment_price) . ",'" . $help_priceunit . "');");
          $database->query();
        } 
      } 

      if ($tmp[5] == "OK" && mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'feature_vehicle') != "") {
         $feature_vehicle_list = $vehicle->getElementsByTagname('feature_vehicle');
         for ($j = 0; $j < $feature_vehicle_list->length; $j++) {
          $features_vehicles = $feature_vehicle_list->item($j);
          $features_vehicles_id =
           mosVehicleManagerImportExport::getXMLItemValue($features_vehicles, 'fk_featureid');
          $database->setQuery("INSERT INTO #__vehiclemanager_feature_vehicles" .
              "\n (fk_vehicleid, fk_featureid)" .
              "\n VALUES " .
              "\n (" . $vehicle_class->id . ", " . $features_vehicles_id . ");");
          $database->query();
         }
      }
      $vehicleid_old[] = array('old_id' =>
        mosVehicleManagerImportExport::getXMLItemValue($vehicle, 'id'), 'id' => $vehicle_class->id);
    }//end for(...) - vehicle

     //get orders
    $orders_list = $dom->getElementsByTagname('order');
    $odrers_ids = array();
    for ($j = 0; $j < $orders_list->length; $j++) {
        $orders = $orders_list->item($j);
        $order_id = mosVehicleManagerImportExport::getXMLItemValue($orders, 'id');
        $order_userid = mosVehicleManagerImportExport::getXMLItemValue($orders, 'fk_user_id');
        $vtitle = mosVehicleManagerImportExport::getXMLItemValue($orders, 'fk_vehicle_vtitle');
        $order_email = mosVehicleManagerImportExport::getXMLItemValue($orders, 'usr_email');
        $order_name = mosVehicleManagerImportExport::getXMLItemValue($orders, 'usr_name');
        $order_status = mosVehicleManagerImportExport::getXMLItemValue($orders, 'status');
        $order_data = mosVehicleManagerImportExport::getXMLItemValue($orders, 'order_date');
        $order_payer = mosVehicleManagerImportExport::getXMLItemValue($orders, 'payer_id');
        $txn_type = mosVehicleManagerImportExport::getXMLItemValue($orders, 'txn_type');
        $txn_id = mosVehicleManagerImportExport::getXMLItemValue($orders, 'txn_id');
        $order_payer_status = mosVehicleManagerImportExport::getXMLItemValue($orders, 'payer_status');
        $order_calculated_price = mosVehicleManagerImportExport::getXMLItemValue($orders, 'order_calculated_price');
        $order_price = mosVehicleManagerImportExport::getXMLItemValue($orders, 'order_price');
        $order_currency_code = mosVehicleManagerImportExport::getXMLItemValue($orders, 'order_currency_code');

        //insert data in table #__vehiclemanager_orders
        $database->setQuery("INSERT INTO #__vehiclemanager_orders " .
                "\n (fk_user_id,fk_vehicle_vtitle, usr_email, usr_name, status, order_date, fk_vehicle_id,
                 payer_id, payer_status, order_calculated_price, order_price,txn_type,txn_id, order_currency_code)" .
                "\n VALUES " . " ('" . $order_userid . 
                "', '" . $vtitle .
                "', '" . $order_email . 
                "', '" . $order_name . 
                "', '" . $order_status .
                "', '" . $order_data .
                "', '" . $old_vehicle_id .
                "', '" . $order_payer .
                "', '" . $order_payer_status .
                "', '" . $order_calculated_price .
                "', '" . $order_price .
                "', '" . $txn_type .
                "', '" . $txn_id .
                "', '" . $order_currency_code . "');");
        $database->query();
        $odrers_ids[$order_id]=$database->insertid();
        $database->setQuery("UPDATE #__vehiclemanager_orders SET fk_vehicle_id =" . $vehicle_class->id . " WHERE fk_vehicle_id = " . $old_vehicle_id . "");
        $database->query();
       
    } 

    //get orders details
    $details_list = $dom->getElementsByTagname('orders_detail');
    for ($j = 0; $j < $details_list->length; $j++) {
      $detail= $details_list->item($j);
      $order_id = mosVehicleManagerImportExport::getXMLItemValue($detail, 'fk_order_id');
      $order_userid = mosVehicleManagerImportExport::getXMLItemValue($detail, 'fk_user_id');
      $vtitle = mosVehicleManagerImportExport::getXMLItemValue($detail, 'fk_vehicle_vtitle');
      $order_email = mosVehicleManagerImportExport::getXMLItemValue($detail, 'usr_email');
      $order_name = mosVehicleManagerImportExport::getXMLItemValue($detail, 'usr_name');
      $order_status = mosVehicleManagerImportExport::getXMLItemValue($detail, 'status');
      $order_data = mosVehicleManagerImportExport::getXMLItemValue($detail, 'order_date');
      $order_payer = mosVehicleManagerImportExport::getXMLItemValue($detail, 'payer_id');
      $txn_type = mosVehicleManagerImportExport::getXMLItemValue($detail, 'txn_type');
      $txn_id = mosVehicleManagerImportExport::getXMLItemValue($detail, 'txn_id');
      $order_payer_status = mosVehicleManagerImportExport::getXMLItemValue($detail, 'payer_status');
      $payment_details = mosVehicleManagerImportExport::getXMLItemValue($detail, 'payment_details');
      $order_calculated_price = mosVehicleManagerImportExport::getXMLItemValue($orders, 'order_calculated_price');
      $order_price = mosVehicleManagerImportExport::getXMLItemValue($orders, 'order_price');
      $order_currency_code = mosVehicleManagerImportExport::getXMLItemValue($orders, 'order_currency_code');
      $order_id = $odrers_ids[$order_id];

      //insert data in table #__vehiclemanager_orders_details
      $database->setQuery("INSERT INTO #__vehiclemanager_orders_details " .
              "\n (fk_order_id,fk_user_id,fk_vehicle_vtitle, usr_email, usr_name, status, order_date,
               fk_vehicle_id, payer_id, payer_status, order_calculated_price, order_price,txn_type,txn_id,
                order_currency_code,payment_details)" .
              "\n VALUES " . " (
              '" . $order_id .
              "', '" . $order_userid . 
              "', '" . $vtitle .
              "', '" . $order_email . 
              "', '" . $order_name . 
              "', '" . $order_status .
              "', '" . $order_data .
              "', '" . $old_vehicle_id .
              "', '" . $order_payer .
              "', '" . $order_payer_status .
              "',  '" . $order_calculated_price .
              "',  '" . $order_price .
              "', '" . $txn_type .
              "', '" . $txn_id .
              "', '" . $order_currency_code .
              "', " . $database->Quote($payment_details) . ");");
      $database->query();
      $database->setQuery("UPDATE #__vehiclemanager_orders_details SET fk_vehicle_id =" . $vehicle_class->id . " WHERE fk_vehicle_id = " . $old_vehicle_id . "");
      $database->query();
    } 

    if ($catid === null){
      mosVehicleManagerImportExport::updateAssociateVehicle($associateSaveArr); 
    }
    return $retVal;
  }

//***************************************************************************************************
//***********************   end add for import XML format   *****************************************
//***************************************************************************************************
static function exportVehicles($option) {
  global $database, $my, $mainframe, $vehiclemanager_configuration;

  $catid = mosGetParam($_POST, 'export_catid', 0);
  $type = mosGetParam($_POST, 'export_type', 0);
  $where = array();
  
  if (count($catid) > 0 && $type != 4){
          foreach ($catid as $id){
              array_push($where, "vc.idcat='$id'");
          }     
  }

  $selectstring = "SELECT distinct a.id, a.owner_id FROM #__vehiclemanager_vehicles AS a
          \nLEFT JOIN #__vehiclemanager_categories AS vc ON vc.iditem=a.id" .
          "\nLEFT JOIN #__vehiclemanager_main_categories AS c ON c.id=vc.idcat" .
          (count($where) ? " WHERE " . implode(' or ', $where) : "") .
          "\n GROUP BY a.id
           \n ORDER BY c.parent_id, a.ordering";
  
  $database->setQuery($selectstring);
  
  $vids = $database->loadResultArray();
  
  if (version_compare(JVERSION, '3.0', 'lt')) {
      $vids = $database->loadResultArray();
  } else {
      $vids = $database->loadColumn();
  }

  echo $database->getErrorMsg();

  if ($database->getErrorNum()) {
      echo $database->stderr();
      return;
  }
   if ($database->getErrorNum())
  {
      echo $database->stderr();
      return;
  }


  $order = array("\r\n", "\n", "\r");
  
  $vtype[0] = '';
  $vtype1 = explode(',', _VEHICLE_MANAGER_OPTION_VEHICLE_TYPE);
  $k = 1;
  
  foreach ($vtype1 as $vtype2) {
      $vtype[$k] = $vtype2;
      $k++;
  }
  
  $vcondition[0] = '';
  $vcondition1 = explode(',', _VEHICLE_MANAGER_OPTION_VEHICLE_CONDITION);
  $k = 1;
  
  foreach ($vcondition1 as $vcondition2) {
      $vcondition[$k] = $vcondition2;
      $k++;
  }
  
  $listing_type[0] = '';
  $listing_type[1] = _VEHICLE_MANAGER_OPTION_FOR_RENT;
  $listing_type[2] = _VEHICLE_MANAGER_OPTION_FOR_SALE;
  $listing_status[0] = '';
  $listing_status1 = explode(',', _VEHICLE_MANAGER_OPTION_LISTING_STATUS);
  $k = 1;
  
  foreach ($listing_status1 as $listing_status2) {
      $listing_status[$k] = $listing_status2;
      $k++;
  }
  
  $price_type[0] = '';
  $price_type1 = explode(',', _VEHICLE_MANAGER_OPTION_PRICE_TYPE);
  $k = 1;
  
  foreach ($price_type1 as $price_type2) {
      $price_type[$k] = $price_type2;
      $k++;
  }
  
  $transmission[0] = '';
  $transmission1 = explode(',', _VEHICLE_MANAGER_OPTION_TRANSMISSION);
  $k = 1;
  
  foreach ($transmission1 as $transmission2) {
      $transmission[$k] = $transmission2;
      $k++;
  }
  
  $drive_type[0] = '';
  $drive_type1 = explode(',', _VEHICLE_MANAGER_OPTION_DRIVE_TYPE);
  $k = 1;
  
  foreach ($drive_type1 as $drive_type2) {
      $drive_type[$k] = $drive_type2;
      $k++;
  }
  
  $numcylinder[0] = '';
  $numcylinder1 = explode(',', _VEHICLE_MANAGER_OPTION_NUMBER_OF_CYLINDERS);
  $k = 1;
  
  foreach ($numcylinder1 as $numcylinder2) {
      $numcylinder[$k] = $numcylinder2;
      $k++;
  }
  
  $numspeed[0] = '';
  $numspeed1 = explode(',', _VEHICLE_MANAGER_OPTION_NUMBER_OF_SPEEDS);
  $k = 1;
  
  foreach ($numspeed1 as $numspeed2) {
      $numspeed[$k] = $numspeed2;
      $k++;
  }
  
  $fuel_type[0] = '';
  $fuel_type1 = explode(',', _VEHICLE_MANAGER_OPTION_FUEL_TYPE);
  $k = 1;
  
  foreach ($fuel_type1 as $fuel_type2) {
      $fuel_type[$k] = $fuel_type2;
      $k++;
  }
  
  $numdoors[0] = '';
  $numdoors1 = explode(',', _VEHICLE_MANAGER_OPTION_NUMBER_OF_DOORS);
  $k = 1;
  
  foreach ($numdoors1 as $numdoors2) {
      $numdoors[$k] = $numdoors2;
      $k++;
  }     
  
  $categories = '';
  if ($type == '4') {
      $database->setQuery("SELECT * FROM #__vehiclemanager_main_categories " .
      "WHERE section='com_vehiclemanager' order by parent_id; ");
      $categories = $database->loadObjectList();    
      
      $database->setQuery("SELECT * FROM #__vehiclemanager_feature ");
      $features = $database->loadObjectList();
      
      $database->setQuery("SELECT * FROM #__vehiclemanager_orders ");
      $orders = $database->loadObjectList();

      $database->setQuery("SELECT * FROM #__vehiclemanager_orders_details ");
      $orders_details = $database->loadObjectList();

  }

  
  
  switch ($type) {
      case '0':break;
      case '1':
          $type2 = 'csv';
          //move to xml - all data
          $createFeatured = true;
          $all = false;
          break;
      case '2':
          $type2 = 'xml';
          //move to xml - some category
          $createFeatured = false;
          $all = false;
          break;
      default :
          $type2 = 'xml';
          //move to xml - all category
          $createFeatured = true;
          $all = true;
          break;

  }        
  
  $strXmlDoc = "";
  $strXmlDoc.= "<?xml version='1.0' encoding='utf-8' ?>\n";
  $strXmlDoc.= "<vechicles_data>\n";
  $strXmlDoc.= "<version>" . $vehiclemanager_configuration['release']['version'] . "</version>\n"; 
  
  $strXmlDocCategory = "";
  $strXmlDocCategory.= "<categories>\n";
  if(!empty($categories)){
      foreach($categories as $cat){
          $strXmlDocCategory.= "<category>\n";
          foreach($cat as $field => $value) {

              $strXmlDocCategory.= '<category_' . $field . '><![CDATA[' .
               $value . ']]></category_' . $field . ">\n";
          }                 
          $strXmlDocCategory.= "</category>\n";                
      }
  }
  $strXmlDocCategory.= "</categories>\n";
   
   
  $strXmlDocFeatures = "";
  $strXmlDocFeatures.= "<features>\n";
  if(!empty($features)){
      foreach($features as $feature1){
          $strXmlDocFeatures .= "<feature>\n";
          foreach ($feature1 as $field => $value) {
              $strXmlDocFeatures.= '<feature_' . $field . '><![CDATA[' .
               $value . ']]></feature_' . $field . ">\n";
          }
          $strXmlDocFeatures .= "</feature>\n";
      }    
  }   
  $strXmlDocFeatures.= "</features>\n";
  
  $strXmlDocOrders = "";
  $strXmlDocOrders.= "<orders>\n";
  if(!empty($orders)){
      foreach($orders as $order1){
          $strXmlDocOrders .= "<order>\n";
          foreach ($order1 as $field => $value) {
              $strXmlDocOrders.= '<' . $field . '><![CDATA[' . $value .
               ']]></' . $field . ">\n";
          }
          $strXmlDocOrders .= "</order>\n";
      }    
  }   
  $strXmlDocOrders.= "</orders>\n";
  
  $strXmlDocOrdersDet = "";
  $strXmlDocOrdersDet.= "<orders_details>\n";;
  if(!empty($orders_details)){
    foreach($orders_details as $order1){
      $strXmlDocOrdersDet .= "<orders_detail>\n";
      foreach ($order1 as $field => $value) {
        $strXmlDocOrdersDet.= '<' . $field . '><![CDATA[' . $value . ']]></' . $field . ">\n";   
      }
      $strXmlDocOrdersDet .= "</orders_detail>\n";
    }    
  }   
  $strXmlDocOrdersDet.= "</orders_details>\n";

  $strXmlDocVehiclesList = "";
  $strXmlDocVehiclesList.= "<vechicles_list>\n";
  $tmp = new mosVehicleManager($database);
  foreach($vids as $vid){  

      if($tmp->load(intval($vid))){

          $tmp->contacts = str_replace('|', '-', $tmp->contacts);
          $tmp->contacts = str_replace($order, ' ', $tmp->contacts);
          $tmp->vlocation = str_replace('|', '-', $tmp->vlocation);
          $tmp->vlocation = str_replace($order, ' ', $tmp->vlocation);
          $tmp->description = str_replace('|', '-', $tmp->description);
          $tmp->description = str_replace($order, '', $tmp->description);
          $tmp->vmodel = str_replace('|', '-', $tmp->vmodel);
          $tmp->vmodel = str_replace($order, ' ', $tmp->vmodel);
          $tmp->vtype = str_replace('|', '-', $vtype[$tmp->vtype]);
          $tmp->vtype = str_replace($order, ' ', $tmp->vtype);     
          $tmp->vtitle = str_replace('|', '-', $tmp->vtitle);
          $tmp->vtitle = str_replace($order, ' ', $tmp->vtitle);
          $tmp->engine = str_replace('|', '-', $tmp->engine);
          $tmp->engine = str_replace($order, ' ', $tmp->engine);
          $tmp->wheeltype = str_replace('|', '-', $tmp->wheeltype);
          $tmp->wheeltype = str_replace($order, ' ', $tmp->wheeltype);
          $tmp->rear_axe_type = str_replace('|', '-', $tmp->rear_axe_type);
          $tmp->rear_axe_type = str_replace($order, ' ', $tmp->rear_axe_type);
          $tmp->brakes_type = str_replace('|', '-', $tmp->brakes_type);
          $tmp->brakes_type = str_replace($order, ' ', $tmp->brakes_type);
          $tmp->exterior_color = str_replace('|', '-', $tmp->exterior_color);
          $tmp->exterior_color = str_replace($order, ' ', $tmp->exterior_color);
          $tmp->exterior_amenities = str_replace('|', '-', $tmp->exterior_amenities);
          $tmp->exterior_amenities = str_replace($order, ' ', $tmp->exterior_amenities);
          $tmp->interior_color = str_replace('|', '-', $tmp->interior_color);
          $tmp->interior_color = str_replace($order, ' ', $tmp->interior_color);
          $tmp->dashboard_options = str_replace('|', '-', $tmp->dashboard_options);
          $tmp->dashboard_options = str_replace($order, ' ', $tmp->dashboard_options);
          $tmp->interior_amenities = str_replace('|', '-', $tmp->interior_amenities);
          $tmp->interior_amenities = str_replace($order, ' ', $tmp->interior_amenities);
          $tmp->safety_options = str_replace('|', '-', $tmp->safety_options);
          $tmp->safety_options = str_replace($order, ' ', $tmp->safety_options);
          $tmp->maker = str_replace('|', '-', $tmp->maker);
          $tmp->maker = str_replace($order, ' ', $tmp->maker);
          $tmp->owneremail = str_replace('|', '-', $tmp->owneremail);
          $tmp->owneremail = str_replace($order, ' ', $tmp->owneremail);
          $tmp->city = str_replace('|', '-', $tmp->city);
          $tmp->city = str_replace($order, ' ', $tmp->city);
          $tmp->country = str_replace('|', '-', $tmp->country);
          $tmp->country = str_replace($order, ' ', $tmp->country);
          $tmp->priceunit = str_replace('|', '-', $tmp->priceunit);
          $tmp->priceunit = str_replace($order, ' ', $tmp->priceunit);   
          $tmp->featured_clicks = str_replace('|', '-', $tmp->featured_clicks);
          $tmp->featured_clicks = str_replace($order, ' ', $tmp->featured_clicks);
          $tmp->featured_shows = str_replace('|', '-', $tmp->featured_shows);
          $tmp->featured_shows = str_replace($order, ' ', $tmp->featured_shows);
          $tmp->zipcode = str_replace('|', '-', $tmp->zipcode);
          $tmp->zipcode = str_replace($order, ' ', $tmp->zipcode);
          $tmp->owner_id = str_replace('|', '-', $tmp->owner_id);
          $tmp->owner_id = str_replace($order, ' ', $tmp->owner_id);
          $tmp->vcondition = str_replace('|', '-', $vcondition[$tmp->vcondition]);
          $tmp->vcondition = str_replace($order, ' ', $tmp->vcondition);
          $tmp->listing_type = str_replace('|', '-', $listing_type[$tmp->listing_type]);
          $tmp->listing_type = str_replace($order, ' ', $tmp->listing_type);
          $tmp->listing_status = str_replace('|', '-', $listing_status[$tmp->listing_status]);
          $tmp->listing_status = str_replace($order, ' ', $tmp->listing_status);
          $tmp->price_type = str_replace('|', '-', $price_type[$tmp->price_type]);
          $tmp->price_type = str_replace($order, ' ', $tmp->price_type);       
          $tmp->transmission = str_replace('|', '-', $transmission[$tmp->transmission]);
          $tmp->transmission = str_replace($order, ' ', $tmp->transmission);
          $tmp->drive_type = str_replace('|', '-', $drive_type[$tmp->drive_type]);
          $tmp->drive_type = str_replace($order, ' ', $tmp->drive_type);
          $tmp->cylinder = str_replace('|', '-', $numcylinder[$tmp->cylinder]);
          $tmp->cylinder = str_replace($order, ' ', $tmp->cylinder);
          $tmp->num_speed = str_replace('|', '-', $numspeed[$tmp->num_speed]);
          $tmp->num_speed = str_replace($order, ' ', $tmp->num_speed);
          $tmp->fuel_type = str_replace('|', '-', $fuel_type[$tmp->fuel_type]);
          $tmp->fuel_type = str_replace($order, ' ', $tmp->fuel_type);        
          $tmp->doors = str_replace('|', '-', $tmp->doors);
          $tmp->doors = str_replace($order, ' ', $numdoors[$tmp->doors]);
          $tmp->associate_vehicle = str_replace('|', '-', $tmp->associate_vehicle);
          $tmp->associate_vehicle = str_replace($order, ' ', $tmp->associate_vehicle);
          $tmp->edok_link = str_replace('|', '-', $tmp->edok_link);
          $tmp->edok_link = str_replace($order, ' ', $tmp->edok_link);  

          $strXmlDocVehiclesList.= $tmp->toXML2($all);
        
          }
      }     
  
  $strXmlDocVehiclesList.= "</vechicles_list>\n";  
  
  if($createFeatured){    
      $strXmlDoc.= $strXmlDocCategory;
      $strXmlDoc.= $strXmlDocFeatures;
      $strXmlDoc.= $strXmlDocOrders;
      $strXmlDoc.= $strXmlDocOrdersDet;
  }
  
  $strXmlDoc.= $strXmlDocVehiclesList;
  $strXmlDoc.= "</vechicles_data>\n";
  
  $retVal = $strXmlDoc;
  
  $InformationArray = mosVehicleManagerImportExport :: storeExportFile($retVal, $type2);
  HTML_vehiclemanager :: showExportResult($InformationArray, $option);
}

  
  static function storeExportFile($data, $type)
  {

      global $mosConfig_live_site, $mosConfig_absolute_path, $vehiclemanager_configuration;
      $fileName = "vehiclemanager_" . date("Ymd_His");
      $fileBase = "/administrator/components/com_vehiclemanager/exports/";

      //write the xml file
      $fp = fopen($mosConfig_absolute_path . $fileBase . $fileName . ".xml", "w", 0); #open for writing

      fwrite($fp, $data); #write all of $data to our opened file
      fclose($fp); #close the file
      
      $InformationArray = array();
      $InformationArray['xml_file'] = $fileName . '.xml';
      $InformationArray['log_file'] = $fileName . '.log';
      $InformationArray['fileBase'] = "file://" . getcwd() . "/components/com_vehiclemanager/exports/";
      $InformationArray['urlBase'] = $mosConfig_live_site . $fileBase;
      $InformationArray['out_file'] = $InformationArray['xml_file'];
      $InformationArray['error'] = new stdClass();

      switch ($type) {
          case 'csv':
              $InformationArray['xslt_file'] = 'csv.xsl';
              $InformationArray['out_file'] = $fileName . '.csv';
              mosVehicleManagerImportExport :: transformPHP4($InformationArray);
              break;

          default:
              break;
      }

      return $InformationArray;
  }

  static function transformPHP4(&$InformationArray)
  {
      
      // create the XSLT processor^M
      $xh = xslt_create() or die("Could not create XSLT processor");
      // Process the document
      $result = xslt_process($xh, $InformationArray['fileBase'] .
         $InformationArray['xml_file'], $InformationArray['fileBase'] .
         $InformationArray['xslt_file'], $InformationArray['fileBase'] . $InformationArray['out_file']);
      if (!$result)
      {
          // Something croaked. Show the error
          $InformationArray['error'] = "Cannot process XSLT document: " .
                  /* xslt_errno($xh) . */ " " /* . xslt_error($xh) */;
      }

      // Destroy the XSLT processor
      xslt_free($xh);
  }


  static function clearDatabase()
  {
      global $database;
      ///$database->setQuery("DELETE FROM #__categories WHERE section='com_vehiclemanager'");
      $database->setQuery("DELETE FROM #__vehiclemanager_main_categories "); // for 1.6
      $database->query();
      $database->setQuery("DELETE FROM #__vehiclemanager_feature_vehicles");
      $database->query();
      $database->setQuery("DELETE FROM #__vehiclemanager_feature");
      $database->query();
      $database->setQuery("DELETE FROM #__vehiclemanager_categories");
      $database->query();
      $database->setQuery("DELETE FROM #__vehiclemanager_vehicles");
      $database->query();
      $database->setQuery("DELETE FROM #__vehiclemanager_photos");
      $database->query();
      $database->setQuery("DELETE FROM #__vehiclemanager_rent");
      $database->query();
      $database->setQuery("DELETE FROM #__vehiclemanager_rent_request");
      $database->query();
      $database->setQuery("DELETE FROM #__vehiclemanager_review");
      $database->query();
      $database->setQuery("DELETE FROM #__vehiclemanager_buying_request");
      $database->query();
      $database->setQuery("DELETE FROM #__vehiclemanager_orders");
      $database->query();
      $database->setQuery("DELETE FROM #__vehiclemanager_rent_sal");
      $database->query();
  }

}

