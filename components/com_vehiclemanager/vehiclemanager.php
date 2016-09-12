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
if (!defined('DS'))
  define('DS', DIRECTORY_SEPARATOR);
$mosConfig_absolute_path = $GLOBALS['mosConfig_absolute_path'] = JPATH_SITE;
$mainframe = JFactory::getApplication(); // for 1.6
$GLOBALS['mainframe'] = $mainframe;





if (get_magic_quotes_gpc())
{

  function vm_stripslashes_gpc(&$value)
  {
      $value = stripslashes($value);
  }

  array_walk_recursive($_GET, 'vm_stripslashes_gpc');
  array_walk_recursive($_POST, 'vm_stripslashes_gpc');
  array_walk_recursive($_COOKIE, 'vm_stripslashes_gpc');
  array_walk_recursive($_REQUEST, 'vm_stripslashes_gpc');
}

require_once($mosConfig_absolute_path . "/components/com_vehiclemanager/compat.joomla1.5.php");
if (version_compare(JVERSION, "3.0.0", "lt")){
  include_once($mosConfig_absolute_path . '/libraries/joomla/application/pathway.php'); // for 1.6
}

include_once($mosConfig_absolute_path . '/components/com_vehiclemanager/vehiclemanager.main.categories.class.php');
jimport('joomla.application.pathway');
jimport('joomla.html.pagination');
jimport('joomla.filesystem.folder');

/** load the html drawing class */
require_once($mosConfig_absolute_path . "/components/com_vehiclemanager/vehiclemanager.class.rent.php");
require_once($mosConfig_absolute_path . "/components/com_vehiclemanager/vehiclemanager.html.php");
require_once($mosConfig_absolute_path . "/components/com_vehiclemanager/vehiclemanager.class.php");
require_once($mosConfig_absolute_path . "/components/com_vehiclemanager/vehiclemanager.class.rent_request.php");
require_once($mosConfig_absolute_path . "/components/com_vehiclemanager/vehiclemanager.class.buying_request.php");
require_once($mosConfig_absolute_path . "/components/com_vehiclemanager/vehiclemanager.class.rent.php");
require_once($mosConfig_absolute_path . "/components/com_vehiclemanager/vehiclemanager.class.review.php");
require_once($mosConfig_absolute_path . "/administrator/components/com_vehiclemanager/admin.vehiclemanager.class.others.php");
require_once($mosConfig_absolute_path . "/components/com_vehiclemanager/functions.php");

vmLittleThings::language_load_VM();

if (!array_key_exists('vehiclemanager_configuration', $GLOBALS))
{
  require_once ($mosConfig_absolute_path . "/administrator/components/com_vehiclemanager/admin.vehiclemanager.class.conf.php");
  $GLOBALS['vehiclemanager_configuration'] = $vehiclemanager_configuration;
} else
  global $vehiclemanager_configuration;


if (!isset($option)) $GLOBALS['option'] = $option = mosGetParam($_REQUEST, 'option', 'com_vehiclemanager');
else $GLOBALS['option'] = $option ;


if ($option == "com_simplemembership")
{
  if (!array_key_exists('user_configuration', $GLOBALS))
  {
      require_once (JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_simplemembership' . DS . 'admin.simplemembership.class.conf.php');
      $GLOBALS['user_configuration'] = $user_configuration;
  } else
  {
      global $user_configuration;
  }
}
if (!isset($task))
  $GLOBALS['task'] = $task = protectInjectionWithoutQuote('task', ''); else
  $GLOBALS['task'] = $task;

//$GLOBALS['image_link']=$vehiclemanager_configuration['image_link']['name']
$GLOBALS['reviews_show'] = $vehiclemanager_configuration['reviews']['show'];
$GLOBALS['reviews_registrationlevel'] = $vehiclemanager_configuration['reviews']['registrationlevel'];
$GLOBALS['edocs_show'] = $vehiclemanager_configuration['edocs']['show'];
$GLOBALS['edocs_registrationlevel'] = $vehiclemanager_configuration['edocs']['registrationlevel'];
$GLOBALS['mail_to_show'] = $vehiclemanager_configuration['mail_to']['show'];
$GLOBALS['mail_to_registrationlevel'] = $vehiclemanager_configuration['mail_to']['registrationlevel'];



$GLOBALS['cat_pic_show'] = $vehiclemanager_configuration['cat_pic']['show'];
$GLOBALS['debug'] = $vehiclemanager_configuration['debug'];
$GLOBALS['edocs_location'] = $vehiclemanager_configuration['edocs']['location'];

$GLOBALS['review_added_email_registrationlevel'] = $vehiclemanager_configuration['review_added_email']['registrationlevel'];


$GLOBALS['subcategory_show'] = $vehiclemanager_configuration['subcategory']['show'];
$GLOBALS['foto_high'] = $vehiclemanager_configuration['foto']['high'];
$GLOBALS['foto_width'] = $vehiclemanager_configuration['foto']['width'];

$GLOBALS['Reviews_vehicle_show'] = $vehiclemanager_configuration['Reviews_vehicle']['show'];
$GLOBALS['Reviews_vehicle_registrationlevel'] = $vehiclemanager_configuration['Reviews_vehicle']['registrationlevel'];
$GLOBALS['contacts_show'] = $vehiclemanager_configuration['Contacts']['show'];
$GLOBALS['contacts_registrationlevel'] = $vehiclemanager_configuration ['contacts']['registrationlevel'];
$GLOBALS['Location_vehicle_show'] = $vehiclemanager_configuration['Location_vehicle']['show'];
$GLOBALS['Location_vehicle_registrationlevel'] = $vehiclemanager_configuration ['Location_vehicle']['registrationlevel'];

$GLOBALS['vehiclemanager_configuration'] = $vehiclemanager_configuration;

$GLOBALS['my'] = $my = JFactory::getUser();
$GLOBALS['acl'] = $acl = JFactory::getACL();
$GLOBALS['database'] = $database = JFactory::getDBO();

$id = intval(mosGetParam($_REQUEST, 'id', 0));
$catid = intval(mosGetParam($_REQUEST, 'catid', 0));
$vids = mosGetParam($_REQUEST, 'vid', array());
$printItem = trim(protectInjectionWithoutQuote('printItem'));


$GLOBALS['option'] = $option = trim(mosGetParam($_REQUEST, 'option', "com_vehiclemanager"));

if (!isset($GLOBALS['Itemid']))
  $GLOBALS['Itemid'] = $Itemid = intval(mosGetParam($_REQUEST, 'Itemid', 0));

// paginations
$intro = $vehiclemanager_configuration['page']['items']; // page length

if ($intro)
{
  $paginations = 1;
  $limit = intval(mosGetParam($_REQUEST, 'limit', $intro));
  $GLOBALS['limit'] = $limit;

  $limitstart = intval(mosGetParam($_REQUEST, 'limitstart', 0));

  $GLOBALS['limitstart'] = $limitstart;

  $total = 0;
  $LIMIT = 'LIMIT ' . $limitstart . ',' . $limit;
} else
{
  $paginations = 0;
  $LIMIT = '';
}

$session = JFactory::getSession();
$session->set("array", $paginations);
$vehiclemanager_configuration['debug'] = 0;

if (isset($_REQUEST['submit']) && $_REQUEST['submit'] == "[ Rent Request Vehicle ]")
  $task = trim(protectInjectionWithoutQuote("rent_request_vehicle"));
  
if ($vehiclemanager_configuration['debug'] == '1')
{
  echo "Task: " . $task . "<br />";
  print_r($_REQUEST);
  echo "<hr /><br />";
}

// for 1.6
if (isset($_REQUEST['view']))
  $view = protectInjectionWithoutQuote('view');
if ((!isset($task) OR $task == '' ) AND isset($view))
  $GLOBALS['task'] = $task = $view;
// --

$vid = mosGetParam($_REQUEST, 'vid', array(0));
if(isset ($_REQUEST["vid"]) AND isset ($_REQUEST["rent_from"]) AND isset($_REQUEST["rent_until"])){
  
  $vid_ajax_rent = mosGetParam($_REQUEST, 'vid', array());
  if(count($vid_ajax_rent) > 0 ) $vid_ajax_rent = $vid_ajax_rent[0] ;
  else { echo _VEHICLE_MANAGER_NOT_AUTHORIZED; exit; }

  if( count($vid_ajax_rent) == 0  || $vid_ajax_rent == 0 ) { echo _VEHICLE_MANAGER_NOT_AUTHORIZED; exit; }
  

  $rent_from = protectInjectionWithoutQuote("rent_from"); // $_REQUEST["rent_from"];
  $rent_until = protectInjectionWithoutQuote("rent_until"); // $_REQUEST["rent_until"];
  
  if(isset($_REQUEST["special_price"])){
     $special_price = protectInjectionWithoutQuote("special_price"); // $_REQUEST["special_price"]; 
  }
  if(isset($_REQUEST["currency_spacial_price"])){
     $currency_spacial_price = protectInjectionWithoutQuote("currency_spacial_price"); // $_REQUEST["currency_spacial_price"];
  }  
  
  if(isset($_REQUEST["comment_price"])){
      $comment_price = protectInjectionWithoutQuote("comment_price"); // $_REQUEST["comment_price"];
  } else {
      $comment_price = '';
  }
  
}

if(isset ($_REQUEST["bid"]) AND isset ($_REQUEST["rent_from"]) AND isset($_REQUEST["rent_until"])){
  //$vid_ajax_rent = protectInjectionWithoutQuote("bid"); // $_REQUEST["bid"];
  $vid_ajax_rent = mosGetParam($_REQUEST, 'bid', array());

  if(count($vid_ajax_rent) > 0 ) $vid_ajax_rent = $vid_ajax_rent[0] ;
  else { echo _VEHICLE_MANAGER_NOT_AUTHORIZED; exit; }
  if( count($vid_ajax_rent) == 0  || $vid_ajax_rent == 0 ) { echo _VEHICLE_MANAGER_NOT_AUTHORIZED; exit; }
  
  $rent_from = protectInjectionWithoutQuote("rent_from"); // $_REQUEST["rent_from"];
  $rent_until = protectInjectionWithoutQuote("rent_until"); // $_REQUEST["rent_until"];
  
  if(isset($_REQUEST["special_price"])){
   $special_price = protectInjectionWithoutQuote("special_price"); // $_REQUEST["special_price"]; 
  }
  if(isset($_REQUEST["currency_spacial_price"])){
   $currency_spacial_price = protectInjectionWithoutQuote("currency_spacial_price"); // $_REQUEST["currency_spacial_price"];
  }  
  
  if(isset($_REQUEST["comment_price"])){
    $comment_price = protectInjectionWithoutQuote("comment_price"); // $_REQUEST["comment_price"];
  } else {
    $comment_price = '';
  }
}

//print_r($task); print_r(":ssssssssssssssssssssss:"); exit;

switch ($task) {

  case 'ajax_rent_calcualete':        
      PHP_vehiclemanager::ajax_rent_calcualete($vid_ajax_rent,$rent_from,$rent_until);
      break;
  case 'secret_image':
      PHP_vehiclemanager::secretImage();
      break;

  case 'show_search_vehicle':
      if (version_compare(JVERSION, '3.0', 'ge')){
        $menu = new JTableMenu($database);
        $menu->load($GLOBALS['Itemid']);
        $params = new JRegistry;
        $params->loadString($menu->params);
      } else{
        $menu = new mosMenu($database);
        $menu->load($GLOBALS['Itemid']);
        $params = new mosParameters($menu->params);
      }
      $layout = $params->get('showsearchvehiclelayout', '');
      if ($layout == '')
          $layout = $vehiclemanager_configuration['show_search_vehicle'];
      PHP_vehiclemanager::showSearchVehicles($option, $catid, $option, $layout);
      break;

  case 'search_vehicle':
      PHP_vehiclemanager::searchVehicles($option, $catid, $option);
      break;

  case 'view_vehicle': 
      case 'view_vehicle': 
      if (version_compare(JVERSION, '3.0', 'ge')){
        $menu = new JTableMenu($database);
        $menu->load($GLOBALS['Itemid']);
        $params = new JRegistry;
        $params->loadString($menu->params);
      }else{
        $menu = new mosMenu($database);
        $menu->load($GLOBALS['Itemid']);
        $params = new mosParameters($menu->params);
      }
      $layout = $params->get('viewvehiclelayout', '');
      if ($layout == '' && isset($catid) && $catid != 0){
        $query = "SELECT params2 FROM #__vehiclemanager_main_categories WHERE id =".$catid;
        $database->setQuery($query);
        $params2 = $database->loadResult();
        $object_params = unserialize($params2);
        if($object_params && $object_params->view_vehicle!='')
        $layout = $object_params->view_vehicle;    
      }
      if ($layout == '')
          $layout = $vehiclemanager_configuration['view_vehicle'];
      if ($id){
        $query = "SELECT idcat AS catid FROM #__vehiclemanager_categories WHERE iditem=" . $id;
        $database->setQuery($query);
        $catid = $database->loadObjectList();
        $catid = $catid[0]->catid;
        PHP_vehiclemanager::showItem_VM($id, $catid, $printItem, $layout);
      }else{
        if (version_compare(JVERSION, '3.0', 'ge')){
          $menu = new JTableMenu($database);
          $menu->load($Itemid);
          $params = new JRegistry;
          $params->loadString($menu->params);
        }else{
          $menu = new mosMenu($database);
          $menu->load($GLOBALS['Itemid']);
          $params = new mosParameters($menu->params);
        }
        if (version_compare(JVERSION, "1.6.0", "lt")){
          $id = $params->get('vehicle');
        }else if (version_compare(JVERSION, "1.6.0", "ge")){
          $view_vehicle_id = ''; // for 1.6 
          $view_vehicle_id = $params->get('vehicle');
          if ($view_vehicle_id > 0){
            $id = $view_vehicle_id;
          }
        }
        $query = "SELECT idcat AS catid FROM #__vehiclemanager_categories WHERE iditem=" . $id;
        $database->setQuery($query);
        $catid = $database->loadObject();
        if($catid)
          $catid = $catid->catid;

        PHP_vehiclemanager::showItem_VM($id, $catid, $printItem, $layout);
      }
      break;

  case 'review':
  case 'review_veh':
      PHP_vehiclemanager::reviewVehicle($option);
      break;
  case 'all_vehicle':
      if (version_compare(JVERSION, '3.0', 'ge')){
        $menu = new JTableMenu($database);
        $menu->load($GLOBALS['Itemid']);
        $params = new JRegistry;
        $params->loadString($menu->params);
      }else{
        $menu = new mosMenu($database);
        $menu->load($GLOBALS['Itemid']);
        $params = new mosParameters($menu->params);
      }
      $layout = $params->get('allvehiclelayout', '');
      if ($layout == '')
        $layout = $vehiclemanager_configuration['all_vehicle_layout'];
      PHP_vehiclemanager::ShowAllVechicle($layout, $printItem);
      break;
  case 'alone_category':
  case 'showCategory':
      if (version_compare(JVERSION, '3.0', 'ge')){
        $menu = new JTableMenu($database);
        $menu->load($Itemid);
        $params = new JRegistry;
        $params->loadString($menu->params);
      } else{
        $menu = new mosMenu($database);
        $menu->load($GLOBALS['Itemid']);
        $params = new mosParameters($menu->params);
      }
      $layout = $params->get('categorylayout', '');
      if ($layout == '' && isset($catid) && $catid != 0){
        $query = "SELECT params2 FROM #__vehiclemanager_main_categories WHERE id =".$catid;
        $database->setQuery($query);
        $params2 = $database->loadResult();
        $object_params = unserialize($params2);
        if($object_params && $object_params->alone_category!='')
        $layout = $object_params->alone_category;    
      }
      if ($layout == '')
        $layout = $vehiclemanager_configuration['view_type'];
        if(empty($layout))$layout = 'default';
      if ($catid){
        PHP_vehiclemanager::showCategory($catid, $printItem, $layout);
      }else{
        if (version_compare(JVERSION, "1.6.0", "lt")){
          $catid = $params->get('catid');
        } else if (version_compare(JVERSION, "1.6.0", "ge")){
          $single_category_id = ''; // for 1.6 
          $single_category_id = $params->get('single_category');
          if ($single_category_id > 0)
              $catid = $single_category_id;
        }
        PHP_vehiclemanager::showCategory($catid, $printItem, $layout);
      }
      break;


  case 'rent_request_vehicle':
      if(count($vids) == 0 ) echo _VEHICLE_MANAGER_NOT_AUTHORIZED; return;
      PHP_vehiclemanager::showRentRequest($option, $vids);
      break;

  case 'rent_requests_vehicle':
      PHP_vehiclemanager::rent_requests($option, $vids);
      break;

  case 'rent_vehicle':
      if (mosGetParam($_REQUEST, 'save') == 1)
          PHP_vehiclemanager::saveRent($option, $vid);
      else
          PHP_vehiclemanager::rent($option, $vid);
      break;



  case 'buying_requests_vehicle':
      PHP_vehiclemanager::buying_requests($option, $vids);
      break;

  case 'rent_history_vehicle':
      PHP_vehiclemanager::rent_history($option);
      break;

  case 'save_rent_request_vehicle':
      PHP_vehiclemanager::saveRentRequest($option, $catid, $vids);
      break;

  case 'buying_request_vehicle':
      PHP_vehiclemanager::saveBuyingRequest($option, $catid, $vids);
      break;

  case 'mdownload':
      PHP_vehiclemanager::mydownload($id);
      break;

  case 'downitsf':
      PHP_vehiclemanager::downloaditself($id);
      break;

  case 'new_url':
      PHP_vehiclemanager::new_direct_url($id);
      break;

  case 'ajax_rent_price': 
      rentPriceVM($vid_ajax_rent,$rent_from,$rent_until,$special_price,$comment_price,$currency_spacial_price);
      break;
  
  case 'all_categories':
      if (version_compare(JVERSION, '3.0', 'ge'))
      {
          $menu = new JTableMenu($database);
          $menu->load($GLOBALS['Itemid']);
          $params = new JRegistry;
          $params->loadString($menu->params);
      } else
      {
          $menu = new mosMenu($database);
          $menu->load($GLOBALS['Itemid']);
          $params = new mosParameters($menu->params);
      }
      $layout = $params->get('allvehiclelayout', '');
      if ($layout == '')
          $layout = $vehiclemanager_configuration['all_categories'];
      PHP_vehiclemanager::listCategories($catid, $layout);
      break;

  default:
      if (version_compare(JVERSION, '3.0', 'ge'))
      {
          $menu = new JTableMenu($database);
          $menu->load($GLOBALS['Itemid']);
          $params = new JRegistry;
          $params->loadString($menu->params);
      } else
      {
          $menu = new mosMenu($database);
          $menu->load($GLOBALS['Itemid']);
          $params = new mosParameters($menu->params);
      }
      $layout = $params->get('allvehiclelayout', '');
      if ($layout == '')
          $layout = $vehiclemanager_configuration['all_vehicle_layout'];
      PHP_vehiclemanager::ShowAllVechicle($layout, $printItem);
      break;
}

class PHP_vehiclemanager
{

  static function mylenStr($str, $lenght)
  {
      if (strlen($str) > $lenght)
      {
          $str = substr($str, 0, $lenght);
          $str = substr($str, 0, strrpos($str, " "));
      }
      return $str;
  }

  static function addTitleAndMetaTags($idVechicle=0)
  {
      global $database, $doc, $mainframe,$Itemid;

      $view = protectInjection('view'); // JREQUEST::getCmd('view', null);
      $catid = JREQUEST::getInt('catid', null);
      $id = JREQUEST::getInt('id', null);
      $lang = protectInjection('lang'); // JREQUEST::getString('lang', null);
      $title = array();
      $sitename = htmlspecialchars($mainframe->getCfg('sitename'));

      if (isset($view))
      {
          $view = str_replace("_", " ", $view);
          $view = ucfirst($view);
          $title[] = $view;
      }

      $s = vmLittleThings::getWhereUsergroupsCondition();

      if (!isset($catid))
      {

           // Parameters
          if (version_compare(JVERSION, '3.0', 'ge'))
          {
              $menu = new JTableMenu($database);
              $menu->load($Itemid);
              $params = new JRegistry;
              $params->loadString($menu->params);
          } else
          {
              $menu = new mosMenu($database);
              $menu->load($Itemid);
              $params = new mosParameters($menu->params);
          }
          if (version_compare(JVERSION, "1.6.0", "lt"))
          {
              $catid = $params->get('catid');
          } else if (version_compare(JVERSION, "1.6.0", "ge"))
          {
              $single_category_id = ''; // for 1.6 
              $single_category_id = $params->get('single_category');
              if ($single_category_id > 0)
                  $catid = $single_category_id;
          }
      }
      
      //To get name of category
      if (isset($catid))
      {
          $query = "SELECT  c.name, c.id AS catid, c.parent_id
                  FROM #__vehiclemanager_main_categories AS c
                  WHERE ($s) AND c.id = " . intval($catid);
          $database->setQuery($query);
          $row = null;
          $row = $database->loadObject();
          if (isset($row))
          {
              $cattitle = array();
              $cattitle[] = $row->name;
              while (isset($row) && $row->parent_id > 0) {
                  $query = "SELECT  name, c.id AS catid, parent_id 
                      FROM #__vehiclemanager_main_categories AS c
                      WHERE ($s) AND c.id = " . intval($row->parent_id);
                  $database->setQuery($query);
                  $row = $database->loadObject();
                  if (isset($row) && $row->name != '')
                  {
                      $cattitle[] = $row->name;
                  }
              }
              $title = array_merge($title, array_reverse($cattitle));
          }
      }

      //To get Name of the vehicle
      if (isset($id))
      {
          $query = "SELECT v.vtitle, c.id AS catid 
                  FROM #__vehiclemanager_vehicles AS v
                  LEFT JOIN #__vehiclemanager_categories AS vc ON v.id=vc.iditem
                  LEFT JOIN #__vehiclemanager_main_categories AS c ON c.id=vc.idcat 
                  WHERE ({$s}) AND v.id=" . intval($id) . "
                  GROUP BY v.id";
          $database->setQuery($query);
          $row = null;
          $row = $database->loadObject();
          if (isset($row))
          {
              $idtitle = array();
              $idtitle[] = $row->vtitle;
              $title = array_merge($title, $idtitle);
          }
      }
     
      if(empty($title)&& $idVechicle!=0){            
          $query = "SELECT v.vtitle 
                  FROM #__vehiclemanager_vehicles AS v
                  WHERE  v.id=" . $idVechicle;
          $database->setQuery($query);
          $row = null;
          $row = $database->loadObject();
          if (isset($row))
          {
              $idtitle = array();
              $idtitle[] = $row->vtitle;
              $title = array_merge($title, $idtitle);
          }
        
      }
      
      $tagtitle = "";
      for ($i = 0; $i < count($title); $i++) {
          $tagtitle = trim($tagtitle) . " | " . trim($title[$i]);
      }

      $vm = "Vehicle Manager ";
      //To set Title
      $title_tag = PHP_vehiclemanager::mylenStr($vm . $tagtitle, 75);
      //To set meta Description
      $metadata_description_tag = PHP_vehiclemanager::mylenStr($vm . $tagtitle, 200);
      //To set meta KeywordsTag
      $metadata_keywords_tag = PHP_vehiclemanager::mylenStr($vm . $tagtitle, 250);

      $doc->setTitle($title_tag);
      $doc->setMetaData('description', $metadata_description_tag);
      $doc->setMetaData('keywords', $metadata_keywords_tag);
  }

  static function output_file($file, $name, $mime_type = '')
  {
      /*
        This function takes a path to a file to output ($file),
        the filename that the browser will see ($name) and
        the MIME type of the file ($mime_type, optional).
        If you want to do something on download abort/finish,
        register_shutdown_function('function_name');
       */
      if (!is_readable($file))
          die('File not found or inaccessible!');
      $size = filesize($file);
      $name = rawurldecode($name);

      /* Figure out the MIME type (if not specified) */
      $known_mime_types = array(
          "pdf" => "application/pdf",
          "txt" => "text/plain",
          "html" => "text/html",
          "htm" => "text/html",
          "exe" => "application/octet-stream",
          "zip" => "application/zip",
          "doc" => "application/msword",
          "xls" => "application/vnd.ms-excel",
          "ppt" => "application/vnd.ms-powerpoint",
          "gif" => "image/gif",
          "png" => "image/png",
          "jpeg" => "image/jpg",
          "jpg" => "image/jpg",
          "php" => "text/plain"
      );

      if ($mime_type == '')
      {
          $file_extension = strtolower(substr(strrchr($file, "."), 1));
          if (array_key_exists($file_extension, $known_mime_types))
          {
              $mime_type = $known_mime_types[$file_extension];
          } else
              $mime_type = "application/force-download";
      };

      $name = str_replace(" ", "", $name);
      ob_end_clean(); //turn off output buffering to decrease cpu usage
      // required for IE, otherwise Content-Disposition may be ignored
      if (ini_get('zlib.output_compression'))
          ini_set('zlib.output_compression', 'Off');

      header('Content-Type: application/force-download');
      header("Content-Disposition: inline; filename=$name");
      header("Content-Transfer-Encoding: binary");
      header('Accept-Ranges: bytes');

      /* The three lines below basically make the download non-cacheable */
      header("Cache-control: private");
      header('Pragma: private');
      header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

      // multipart-download and download resuming support
      if (isset($_SERVER['HTTP_RANGE']))
      {
          list($a, $range) = explode("=", $_SERVER['HTTP_RANGE'], 2);
          list($range) = explode(",", $range, 2);
          list($range, $range_end) = explode("-", $range);
          $range = intval($range);
          if (!$range_end)
              $range_end = $size - 1; else
              $range_end = intval($range_end);
          $new_length = $range_end - $range + 1;
          header("HTTP/1.1 206 Partial Content");
          header("Content-Length: $new_length");
      } else
      {
          $new_length = $size;
          header("Content-Length: " . $size);
      }

      $chunksize = 1 * (1024 * 1024); //you may want to change this
      $bytes_send = 0;
      if ($file = fopen($file, 'r'))
      {
          if (isset($_SERVER['HTTP_RANGE']))
              fseek($file, $range);
          while (!feof($file) && (!connection_aborted()) && ($bytes_send < $new_length)) {
              $buffer = fread($file, $chunksize);
              print($buffer); // is also possible
              flush();
              $bytes_send += strlen($buffer);
          }
          fclose($file);
      } else
          die('Error - can not open file.');
      die();
  }

  static function mydownload($id)
  {
      global $vehiclemanager_configuration;
      global $mosConfig_absolute_path;

      $session = JFactory::getSession();
      $pas = $session->get("ssmid", "default");
      $sid_1 = $session->getId();

      if (!($session->get("ssmid", "default")) || $pas == "" || $pas != $sid_1 || $_COOKIE['ssd'] != $sid_1 ||
              !array_key_exists("HTTP_REFERER", $_SERVER) || $_SERVER["HTTP_REFERER"] == "" ||
              strpos($_SERVER["HTTP_REFERER"], $_SERVER['SERVER_NAME']) === false)
      {
          echo '<H3 align="center">Link failure</H3>';
          exit;
      }
      if ($GLOBALS['license_show'])
      {
          $fd = fopen($mosConfig_absolute_path . "/components/com_vehiclemanager/mylicense.php", "w") or die("Config license file is failure");
          fwrite($fd, _VEHICLE_MANAGER_ADMIN_CONFIG_LICENSE_TEXT);
          fclose($fd);
          HTML_vehiclemanager :: displayLicense($id);
      } else
          PHP_vehiclemanager::downloaditself($id);
  }

  static function downloaditself($idt)
  {
      global $database, $my;
      global $vehiclemanager_configuration;
      global $mosConfig_absolute_path;

      $session = JFactory::getSession();
      $pas = $session->get("ssmid", "default");
      $sid_1 = $session->getId();

      if (!($session->get("ssmid", "default")) ||
              $pas == "" ||
              $pas != $sid_1 ||
              $_COOKIE['ssd'] != $sid_1 ||
              !array_key_exists("HTTP_REFERER", $_SERVER) ||
              $_SERVER["HTTP_REFERER"] == "" ||
              strpos($_SERVER["HTTP_REFERER"], $_SERVER['SERVER_NAME']) === false)
      {
          echo '<H3 align="center">Link failure</H3>';
          exit;
      }
      $session->set("ssmid", "default");

      if (array_key_exists("id", $_POST))
          $id = intval($_POST['id']);
      else
          $id = $idt;

      $query = "SELECT * from #__vehiclemanager_vehicles where id='$id'";
      $database->setQuery($query);
      $vehicle = $database->loadObjectList();

      if (strpos($vehicle[0]->edok_link, $_SERVER['SERVER_NAME']) !== false)
      {
          $name = explode('/', $vehicle[0]->edok_link);
          $file_path = $mosConfig_absolute_path . $GLOBALS['edocs_location'] . $name[count($name) - 1];
          set_time_limit(0);
          PHP_vehiclemanager::output_file($file_path, $name[count($name) - 1]);
          exit;
      } else
      {
          header("Cache-control: private");
          header('Pragma: private');
          header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
          header("HTTP/1.1 301 Moved Permanently");
          header('Content-Type: application/force-download');
          header("Location: " . $vehicle[0]->edok_link);
          exit;
      }
  }

  static function saveRentRequest($option, $catid, $vids){
    global $mainframe, $database, $my, $acl, $vehiclemanager_configuration, $mosConfig_mailfrom, $Itemid;
    $pathway = sefRelToAbs('index.php?option=' . $option . '&amp;task=rent_request_vehicle&amp;Itemid=' . $Itemid);
    $transform_from = data_transform_vm(protectInjectionWithoutQuote('rent_from'));
    $transform_until = data_transform_vm(protectInjectionWithoutQuote('rent_until'));
    if($transform_from > $transform_until){
      echo "<script> alert('date from mast be less date until'); window.history.go(-1); </script>\n";
      exit;
    }
    PHP_vehiclemanager::addTitleAndMetaTags();
    
    if(isset($_POST['user_email']) && $_POST['user_email'] != '') {
      $email = protectInjectionWithoutQuote('user_email'); // JRequest::getVar('user_email');
      $veh_id = JRequest::getVar('vehicleid');
      $name = protectInjectionWithoutQuote('user_name'); // JRequest::getVar('user_name');
      $calculated_price = protectInjection('calculated_price');///with currency//akosha
      $sql = "SELECT u.id as userID, u.email, u.name  FROM #__users AS u  WHERE u.email =".
      $database->Quote($email);
      $database->setQuery($sql);
      $result = $database->loadObjectList();
      if($result == '0' || $result == null) {
          $name = $name;
          $email = $email;
          $user = '';
      } else {
          $email = $result[0]->email;
          $user = $result[0]->userID;
          $name = $result[0]->name;
      }
      $_REQUEST['userId'] = $user;
      $_REQUEST['id'] = $veh_id;
      $_REQUEST['name_bayer'] = $name;
      $calculated_price = protectInjectionWithoutQuote('calculated_price');
      $sql = "SELECT vtitle FROM #__vehiclemanager_vehicles WHERE id='".$veh_id."'";
      $database->setQuery($sql);
      $vtitle = $database->loadResult();
    }
    // order in #__vehiclemanager_orders STOP benja

    // for 1.6
    $path_way = $mainframe->getPathway();
    $path_way->addItem(_VEHICLE_MANAGER_LABEL_TITLE_RENT_REQUEST, $pathway);
    // --

    if (!($vehiclemanager_configuration['rentstatus']['show']) || !checkAccess_VM($vehiclemanager_configuration['rentrequest']['registrationlevel'], 'RECURSE', userGID_VM($my->id), $acl)){
      echo _VEHICLE_MANAGER_NOT_AUTHORIZED;
      return;
    }
    $help = array();
    $rent_request = new mosVehicleManager_rent_request($database);
    $post = JRequest::get('post'); 
    if (!$rent_request->bind($post)){
      echo "<script> alert('" . $rent_request->getError() . "'); window.history.go(-1); </script>\n";
      exit;
    }
    $date_format = $vehiclemanager_configuration['date_format'];
    if(phpversion() >= '5.3.0') {
      $date_format = str_replace('%', '', $date_format);
      $d_from = DateTime::createFromFormat($date_format, $post['rent_from']);
      $d_until = DateTime::createFromFormat($date_format, $post['rent_until']);
      if($d_from === FALSE or $d_until === FALSE){
        echo "<script> alert('Bad date format'); window.history.go(-1); </script>\n";
        exit;           
      }
      $rent_request->rent_from = $d_from->format('Y-m-d');
      $rent_request->rent_until = $d_until->format('Y-m-d');            
    } else {
      $rent_request->rent_from = data_transform_vm($post['rent_from'],'to');
      $rent_request->rent_until = data_transform_vm($post['rent_until'],'to');
    }

    $rent_request->user_email = $rent_request->user_email;
    $rent_request->rent_request = date("Y-m-d H:i:s");
    $rent_request->fk_vehicleid = intval($_REQUEST["vehicleid"]);

    if ($rent_request->rent_from > $rent_request->rent_until){
      echo "<script> alert('" . $rent_request->rent_from . " is more than " . $rent_request->rent_until .
      "'); window.history.go(-1); </script>\n";
      exit;
    }
    $data = JFactory::getDBO();
    $query = "SELECT * FROM #__vehiclemanager_vehicles where id = " . intval(protectInjectionWithoutQuote("vehicleid")) . " ";
    $data->setQuery($query);
    $vehicleid = $data->loadObject();


    $query = "SELECT * FROM #__vehiclemanager_rent where fk_vehicleid = " . $vehicleid->id . " AND rent_return is NULL ";
    $data->setQuery($query);
    $rentTerm = $data->loadObjectList();

    $rent_from = substr($rent_request->rent_from, 0, 10);

    $rent_until = substr($rent_request->rent_until, 0, 10);

    foreach ($rentTerm as $oneTerm){
      $oneTerm->rent_from = substr($oneTerm->rent_from, 0, 10);
      $oneTerm->rent_until = substr($oneTerm->rent_until, 0, 10);
      $returnMessage = checkRentDayNightVM (($oneTerm->rent_from),($oneTerm->rent_until), $rent_from, $rent_until, $vehiclemanager_configuration);         

      if(strlen($returnMessage) > 0){                 
        echo "<script> alert('$returnMessage'); window.history.go(-1); </script>\n";          
        exit;
      }       
    } 

    if ($my->id != 0)
      $rent_request->fk_userid = $my->id;
    if (!$rent_request->check()){
      echo "<script> alert('" . $rent_request->getError() . "'); window.history.go(-1); </script>\n";
      exit;
    }
    if (!$rent_request->store()){
      echo "<script> alert('" . $rent_request->getError() . "'); window.history.go(-1); </script>\n";
      exit;
    }
    $time_difference = calculatePriceVM($vehicleid->id,$rent_from,$rent_until,$vehiclemanager_configuration,$database);            
    $session = JFactory::getSession();
    $password = $session->get('captcha_keystring', 'default');
    $session->set('captcha_keystring', 'default');
    $rent_request->checkin();
    array_push($help, $rent_request);
    $currentcat = new stdClass();
    // Parameters
    if (version_compare(JVERSION, '3.0', 'ge')){
      $menu = new JTableMenu($database);
      $menu->load($Itemid);
      $params = new JRegistry;
      $params->loadString($menu->params);
    } else{
      $menu = new mosMenu($database);
      $menu->load($Itemid);
      $params = new mosParameters($menu->params);
    }
    $menu_name = set_header_name_vm($menu, $Itemid);
    $params->def('header', $menu_name);
    $params->def('pageclass_sfx', '');
    $params->def('show_search', '1');
    $params->def('back_button', $mainframe->getCfg('back_button'));
    // --
    $currentcat->descrip = _VEHICLE_MANAGER_LABEL_RENT_REQUEST_THANKS;
    $currentcat->img = "./components/com_vehiclemanager/images/vm_logo.png";
    $currentcat->header = $params->get('header');
    // used to show table rows in alternating colours
    $tabclass = array('sectiontableentry1', 'sectiontableentry2');
    //********************   end add send mail for admin   ****************                     
    HTML_vehiclemanager :: showRentRequestThanks($params, $catid, $currentcat,$vehicleid,$time_difference);
  }

  static function saveBuyingRequest($option, $catid, $vids){
    global $mainframe, $database, $my, $Itemid, $acl;
    global $vehiclemanager_configuration, $mosConfig_mailfrom;
    if (!($vehiclemanager_configuration['buystatus']['show']) ||
      !checkAccess_VM($vehiclemanager_configuration['buyrequest']['registrationlevel'], 'RECURSE', userGID_VM($my->id), $acl)){
      echo _VEHICLE_MANAGER_NOT_AUTHORIZED;
      return;
    }
    if(isset($_POST['customer_email']) && $_POST['customer_email'] != '') {
      $email = protectInjectionWithoutQuote('customer_email'); // JRequest::getVar('customer_email');
      $vId = JRequest::getVar('vid'); 
      $name = protectInjectionWithoutQuote('customer_namecustomer_name'); // JRequest::getVar('customer_name');
      $time_difference = null;
      $sql = "SELECT u.id as userID, u.email, u.name  FROM #__users AS u  WHERE u.email ='". $email."'";
      $database->setQuery($sql);
      $result = $database->loadObjectList();
      if($result == '0' || $result == null) {
        $name = $name;
        $email = $email;
        $user = '';
      } else {
        $email = $result[0]->email;
        $user = $result[0]->userID;
        $name = $result[0]->name;
      }
      $_REQUEST['userId'] = $user;
      $_REQUEST['user_email'] = $email;
      $_REQUEST['name_bayer'] = $name;
      $_REQUEST['id'] = $veh_Id = $vId[0];
      if($vehiclemanager_configuration['special_price']['show']){
          $rent_from = data_transform_vm(date('Y-m-d'));
          $rent_until = data_transform_vm(date('Y-m-d'));
          $query = "SELECT special_price as price,priceunit FROM #__vehiclemanager_rent_sal WHERE fk_vehiclesid = ".$veh_Id .
              " AND (price_from <= ('" .$rent_until. "') AND price_to >= ('" .$rent_from. "'))";
          $database->setQuery($query);
          $res = $database->loadObjectList();
          if($res){
              $time_difference['0'] = $res['0']->price;
              $time_difference['1'] = $res['0']->priceunit;
              $sql = "SELECT vtitle FROM #__vehiclemanager_vehicles WHERE id='".$veh_Id."'";
              $database->setQuery($sql);
              $vtitle = $database->loadResult();
          }else{
              $sql = "SELECT price,priceunit,vtitle FROM #__vehiclemanager_vehicles WHERE id='".$veh_Id."'";
              $database->setQuery($sql);
              $res = $database->loadObjectList();
              $vtitle = $res[0]->vtitle;
          }
      }else{
          $sql = "SELECT price,priceunit,vtitle FROM #__vehiclemanager_vehicles WHERE id='".$veh_Id."'";
          $database->setQuery($sql);
          $res = $database->loadObjectList();
          $vtitle = $res[0]->vtitle;
      }
      $calculated_price = $res['0']->price.' '.$res['0']->priceunit;
    }
    ///////////end special price for buy
    $buying_request = new mosVehicleManager_buying_request($database);
    $post = JRequest::get('post');
    if (!$buying_request->bind($post)){
      echo $buying_request->getError();
      exit();
    }
    $buying_request->customer_email = $buying_request->customer_email;
    $buying_request->buying_request = date("Y-m-d H:i:s");
    $buying_request->fk_vehicleid = $vids[0];
    if (!$buying_request->store()){
      echo "error:" . $buying_request->getError();
    }
    $currentcat = new stdClass();
    // Parameters
    if (version_compare(JVERSION, '3.0', 'ge')){
      $menu = new JTableMenu($database);
      $menu->load($Itemid);
      $params = new JRegistry;
      $params->loadString($menu->params);
    } else{
      $menu = new mosMenu($database);
      $menu->load($Itemid);
      $params = new mosParameters($menu->params);
    }
    // for 1.6 
    $menu_name = set_header_name_vm($menu, $Itemid);
    $params->def('header', $menu_name);
    // --
    $params->def('pageclass_sfx', '');
    //
    $params->def('show_search', '1');
    $params->def('back_button', $mainframe->getCfg('back_button'));
    $currentcat->descrip = _VEHICLE_MANAGER_LABEL_BUYING_REQUEST_THANKS;
    // page image
    $currentcat->img = "./components/com_vehiclemanager/images/vm_logo.png";
    $currentcat->header = $params->get('header');
    $query = "SELECT * FROM #__vehiclemanager_vehicles where id = " . $buying_request->fk_vehicleid. " ";
    $database->setQuery($query);
    $vehicleid = $database->loadObject();
    HTML_vehiclemanager :: showRentRequestThanks($params, $catid, $currentcat,$vehicleid,$time_difference);
  }

  static function showRentRequest($option, $vid)
  {
      global $mainframe, $database, $my, $Itemid, $acl, $vehiclemanager_configuration;
    
      PHP_vehiclemanager::addTitleAndMetaTags();
      $pathway = sefRelToAbs('index.php?option=' . $option . '&amp;task=rent_request_vehicle&amp;Itemid=' . $Itemid);

      // for 1.6
      $path_way = $mainframe->getPathway();
      $path_way->addItem(_VEHICLE_MANAGER_LABEL_TITLE_RENT_REQUEST, $pathway);
      // --

      if (!($vehiclemanager_configuration['rentstatus']['show']) || !checkAccess_VM($vehiclemanager_configuration['rentrequest']['registrationlevel'], 'RECURSE', userGID_VM($my->id), $acl))
      {
          echo _VEHICLE_MANAGER_NOT_AUTHORIZED;
          return;
      }

      $vids = implode(',', $vid);
      $catid =   JRequest::getVar('catid');
// getting all vehicles for this category
      $query = "SELECT v.*, c.title AS category_titel, c.id AS catid 
            FROM `#__vehiclemanager_vehicles` as v
            LEFT JOIN `#__vehiclemanager_categories` AS vc ON v.id=vc.iditem
            LEFT JOIN `#__vehiclemanager_main_categories` AS c ON c.id=vc.idcat
            WHERE v.id IN (" . $vids . ") and c.id = " . $catid . "
            ORDER BY v.catid, v.ordering";
      $database->setQuery($query);
      $vehicles = $database->loadObjectList();
// print_r($vehicles);exit;
      $currentcat = new stdClass();

      // Parameters
      if (version_compare(JVERSION, '3.0', 'ge'))
      {
          $menu = new JTableMenu($database);
          $menu->load($Itemid);
          $params = new JRegistry;
          $params->loadString($menu->params);
      } else
      {
          $menu = new mosMenu($database);
          $menu->load($Itemid);
          $params = new mosParameters($menu->params);
      }
      $menu_name = set_header_name_vm($menu, $Itemid);
      $params->def('header', $menu_name);
      

      $params->def('pageclass_sfx', '');
      if (($vehiclemanager_configuration['rentstatus']['show']))
      {
          if (checkAccess_VM($vehiclemanager_configuration['rentrequest']['registrationlevel'], 'RECURSE', userGID_VM($my->id), $acl))
          {
              $params->def('show_rentstatus', 1);
              $params->def('show_rentrequest', 1);
          }
      }
      if (($vehiclemanager_configuration['buystatus']['show']))
      {
          if (checkAccess_VM($vehiclemanager_configuration['buyrequest']['registrationlevel'], 'RECURSE', userGID_VM($my->id), $acl))
          {
              $params->def('show_buystatus', 1);
              $params->def('show_buyrequest', 1);
          }
      }

      $params->def('rent_save', 1);
      $params->def('back_button', $mainframe->getCfg('back_button'));

      // page description
      $currentcat->descrip = _VEHICLE_MANAGER_DESC_RENT;

      // page image
      $currentcat->img = './components/com_vehiclemanager/images/vm_logo.png';
      $currentcat->align = 'right';

      $currentcat->header = $params->get('header');

      // used to show table rows in alternating colours
      $tabclass = array('sectiontableentry1', 'sectiontableentry2');

          
      HTML_vehiclemanager::showRentRequest($vehicles, $currentcat, $params, $tabclass, $catid, $sub_categories, false, $option);
  }

  /**
   * comments for registered users
   */
  static function reviewVehicle()
  {
      global $mainframe, $database, $my, $Itemid, $acl, $vehiclemanager_configuration, $mosConfig_absolute_path, $catid;
      global $mosConfig_mailfrom, $session, $option;

      if (!($GLOBALS['reviews_show']) || !checkAccess_VM($GLOBALS['reviews_registrationlevel'], 'RECURSE', userGID_VM($my->id), $acl))
      {
          echo _VEHICLE_MANAGER_NOT_AUTHORIZED;
          return;
      }

      $review = new mosVehicleManager_review($database);
      $review->date = date("Y-m-d H:i:s");
      $review->getReviewFrom($my->id);
      
      
     

      //*********************   begin compare to key   ***************************
      $session = & JFactory::getSession();
      $password = $session->get('captcha_keystring', 'default');
      $session->set('captcha_keystring', 'default');

      if (array_key_exists('keyguest', $_POST) && ($_POST['keyguest'] != $password) && (userGID_VM($my->id) <= 0))
      {
          mosRedirect("index.php?option=com_vehiclemanager&task=view_vehicle&catid=" . $_POST["catid"] . "&id=" .
                  $_POST["fk_vehicleid"] . "&Itemid=$Itemid&title=" . $_POST['title'] . "&comment=" .
                  $_POST['comment'] . "&rating=" . $_POST['rating'], "You typed bad characters from picture!");
          exit();
      }
      //**********************   end compare to key   *****************************
      $post = JRequest::get('post');
      if (!$review->bind($post))
      {
          echo "<script> alert('" . $review->getError() . "'); window.history.go(-1); </script>\n";
          exit();
      }
      
      if ($vehiclemanager_configuration['approve_review']['show'] == '1')
      {
          $review->published = 1;
      } else
      {
          $review->published = 0;
          
      }
      
      if ($vehiclemanager_configuration['approve_review']['show'])
      {
          if (checkAccess_VM($vehiclemanager_configuration['approve_review']['registrationlevel'], 'RECURSE', userGID_VM($my->id), $acl))
          {
              $review->published = 1;
          }
          else
              $review->published = 0;
      }
      else
          $review->published = 0;
      
      if (version_compare(JVERSION, "3.0", "ge")){
          $review->rating *= 2;
      }   
              
      
      if (!$review->check())
      {
          echo "<script> alert('" . $review->getError() . "'); window.history.go(-1); </script>\n";
          exit();
      }
      if (!$review->store())
      {
          echo "<script> alert('" . $review->getError() . "'); window.history.go(-1); </script>\n";
          exit();
      }

      //***************   begin add send mail for admin   ******************
      if (version_compare(JVERSION, '3.0', 'ge'))
      {
          $menu = new JTableMenu($database);
          $menu->load($Itemid);
          $params = new JRegistry;
          $params->loadString($menu->params);
      } else
      {
          $menu = new mosMenu($database);
          $menu->load($Itemid);
          $params = new mosParameters($menu->params);
      }
      //********************   end add send mail for admin ************
      
      if ($option != 'com_vehiclemanager') {
          $link = JRoute::_("index.php?option=" . $option . "&amp;task=view_vehicle&amp;tab=getmyvehiclesTab&amp;id=" . $review->fk_vehicleid . "&catid=" . intval($_POST['catid']) . "&Itemid=" . $Itemid . "#tabs-2");
      } else {
          $link = JRoute::_("index.php?option=com_vehiclemanager&task=view_vehicle&catid=" . $_POST['catid'] . "&id=$review->fk_vehicleid&Itemid=" . $Itemid, false);
      }
      mosRedirect($link, _VEHICLE_MANAGER_LABEL_REVIEW_ADDED);
      
  }

//*****************************************************************************
//this function check - is exist folders under this category 
  static function is_exist_subcategory_vehicles($catid)
  {
      global $database, $my;

      $query = "SELECT *, COUNT(a.id) AS numlinks FROM #__vehiclemanager_main_categories AS cc"
              . "\n LEFT JOIN #__vehiclemanager_categories AS vc ON vc.idcat = cc.id"
              . "\n LEFT JOIN #__vehiclemanager_vehicles AS a ON a.id = vc.iditem"
              . "\n WHERE a.published='1' AND a.approved='1' AND section='com_vehiclemanager' AND parent_id='$catid' AND cc.published='1' "
              . "\n GROUP BY cc.id"
              . "\n ORDER BY cc.ordering"; // Removed for 1.6: AND cc.access <= '$my->gid'
      $database->setQuery($query);
      $categories = $database->loadObjectList();
      if (count($categories) != 0)
          return true;

      $query = "SELECT id "
              . "FROM #__vehiclemanager_main_categories AS cc "
              . " WHERE section='com_vehiclemanager' AND parent_id='$catid' AND published='1' "; // Removed for 1.6: AND access<='$my->gid'

      $database->setQuery($query);
      $categories = $database->loadObjectList();

      if (count($categories) == 0)
          return false;

      foreach ($categories as $k) {
          if (PHP_vehiclemanager::is_exist_subcategory_vehicles($k->id))
              return true;
      }
      return false;
  }

//end function

  /**
   * This function is used to show a list of all vehicles
   */
   static function listCategories($catid)
  {
      global $mainframe, $database, $my, $acl, $langContent; 
      global $mosConfig_shownoauth, $mosConfig_live_site, $mosConfig_absolute_path;
      global $cur_template, $Itemid, $vehiclemanager_configuration;

      PHP_vehiclemanager::addTitleAndMetaTags();

      $s = vmLittleThings::getWhereUsergroupsCondition();

      if (isset($langContent))
      {   
          $lang = $langContent;
          $query = "SELECT lang_code FROM #__languages WHERE sef = '$lang'";
          $database->setQuery($query);
          $lang = $database->loadResult();
          $lang = " and (v.language like 'all' or v.language like '' or " .
           " v.language like '*' or v.language is null or v.language like '$lang') " .
           "AND (c.language like 'all' or c.language like '' or c.language like '*' " .
           " or c.language is null or c.language like '$lang') ";
      } else
      {
          $lang = "";
      }

      $query = "SELECT v.*,c.id, c.parent_id, c.title, c.published, c.image, COUNT(vc.iditem) as vehicles,'1' as display " .
            " FROM  #__vehiclemanager_main_categories as c " .
            " LEFT JOIN #__vehiclemanager_categories AS vc ON vc.idcat=c.id " .
            " LEFT JOIN #__vehiclemanager_vehicles AS v ON v.id=vc.iditem AND " .
            "( v.published || isnull(v.published) ) AND ( v.approved || isnull(v.approved) ) " .
            " WHERE c.section='com_vehiclemanager' AND c.published=1  $lang  " .
            " AND ({$s}) GROUP BY c.id ORDER BY c.parent_id DESC, c.ordering ";
  //
      $database->setQuery($query);
      
      $cat_all = $database->loadObjectList();
      
      $cat_all_temp = array();  


      foreach ($cat_all as $k1 => $cat_item1) {
        $cat_all[$k1]->display = is_exist_curr_and_subcategory_vehicles($cat_all[$k1]->id);
      } 
      
      // Parameters
      if (version_compare(JVERSION, '3.0', 'lt'))
      {
          $menu = new JTableMenu($database); // for 1.6
          $menu->load($Itemid);
          $params = new mosParameters($menu->params);
      } else
      {
          $menu = new JTableMenu($database);
          $menu->load($Itemid);
          $params = new JRegistry;
          $params->loadString($menu->params);
      }

      // for 1.6 
      $menu1 = $mainframe->getMenu();
      if (!isset($Itemid) OR $Itemid == 0)
          if (isset($_REQUEST['itemid']))
              $Itemid = $_REQUEST['itemid'];

      $menu_name = set_header_name_vm($menu, $Itemid);
      $params->def('header', $menu_name);
      $params->def('pageclass_sfx', '');
      $params->def('show_search', '1');
      $params->def('back_button', $mainframe->getCfg('back_button'));
//***********************************begin  for  Reviews vehicle tab*******************/
      if (($GLOBALS['Reviews_vehicle_show']))
      {
          $params->def('show_reviews_vehicle', 1);
          if (checkAccess_VM($GLOBALS['Reviews_vehicle_registrationlevel'],
           'RECURSE', userGID_VM($my->id), $acl))
          {
              $params->def('show_reviews_registrationlevel', 1);
          }
      }
//***********************************begin  for show contacts vehicle**********************/

      if (($GLOBALS['contacts_show']))
      {
          $params->def('show_contacts', 1);
          if (checkAccess_VM($GLOBALS['contacts_registrationlevel'],
           'RECURSE', userGID_VM($my->id), $acl))
          {
              $params->def('show_contacts_registrationlevel', 1);
          }
      }

//***********************************begin  for show location vehicle tab*********************/


      if ($GLOBALS['Location_vehicle_show'])
      {
          $params->def('show_location_vehicle', 1);
          if (checkAccess_VM($GLOBALS['Location_vehicle_registrationlevel'],
           'RECURSE', userGID_VM($my->id), $acl))
          {
              $params->def('show_location_registrationlevel', 1);
          }
      }

      //add for show in category picture
      if (($GLOBALS['cat_pic_show']))
          $params->def('show_cat_pic', 1);

      $currentcat = new stdClass();
      // page header
      $currentcat->header = $params->get('header');
      // page image
      $currentcat->img = "./components/com_vehiclemanager/images/vm_logo.png";

      // page description
      $currentcat->descrip = _VEHICLE_MANAGER_DESC;

      // used to show table rows in alternating colours
      $tabclass = array('sectiontableentry1', 'sectiontableentry2');

      $params->def('allcategories01', "{loadposition com_vehiclemanager_all_categories_01,xhtml}");
      $params->def('allcategories02', "{loadposition com_vehiclemanager_all_categories_02,xhtml}");
      $params->def('allcategories03', "{loadposition com_vehiclemanager_all_categories_03,xhtml}");
      $params->def('allcategories04', "{loadposition com_vehiclemanager_all_categories_04,xhtml}");
      $params->def('allcategories05', "{loadposition com_vehiclemanager_all_categories_05,xhtml}");
      $params->def('allcategories06', "{loadposition com_vehiclemanager_all_categories_06,xhtml}");
      $params->def('allcategories07', "{loadposition com_vehiclemanager_all_categories_07,xhtml}");
      $params->def('allcategories08', "{loadposition com_vehiclemanager_all_categories_08,xhtml}");
      $params->def('allcategories09', "{loadposition com_vehiclemanager_all_categories_09,xhtml}");
      $params->def('allcategories10', "{loadposition com_vehiclemanager_all_categories_10,xhtml}");

      $layout = $params->get('allcategorylayout','default');

      HTML_vehiclemanager::showCategories($params, $cat_all, $catid, $tabclass, $currentcat, $layout);

  }

  static function constructPathway($cat)
  {
      global $mainframe, $database, $option, $Itemid, $mosConfig_absolute_path;

      $query = "SELECT * FROM #__vehiclemanager_main_categories WHERE section = 'com_vehiclemanager' AND published = 1";
      $database->setQuery($query);
      $rows = $database->loadObjectlist('id');
      $pid = $cat->id;
      $pathway = array();
      $pathway_name = array();
      while ($pid != 0) {

          $cat = @$rows[$pid];

          $pathway[] = sefRelToAbs('index.php?option=' . $option . '&task=alone_category&catid=' . @$cat->id . '&Itemid=' . $Itemid);
          $pathway_name[] = @$cat->title;
          $pid = @$cat->parent_id;
      }

      $pathway = array_reverse($pathway);
      $pathway_name = array_reverse($pathway_name);

      $path_way = $mainframe->getPathway(); // for 1.6

      for ($i = 0, $n = count($pathway); $i < $n; $i++) {
          $path_way->addItem($pathway_name[$i], $pathway[$i]);
      }
  }

  /**
   * This function is used to show a list of all vehicles
   */
static function showCategory($catid, $printItem, $layout)
  {
      global $mainframe, $database, $acl, $my, $langContent;
      global $mosConfig_shownoauth, $mosConfig_live_site, $mosConfig_absolute_path;
      global $cur_template, $Itemid, $vehiclemanager_configuration, $mosConfig_list_limit, $limit, $total, $limitstart;

      PHP_vehiclemanager::addTitleAndMetaTags();

      //getting the current category informations
      $database->setQuery("SELECT * FROM #__vehiclemanager_main_categories WHERE id='$catid'");
      $category = $database->loadObjectList();
      if (isset($category[0]))
          $category = $category[0];
      else
      {
          echo _VEHICLE_MANAGER_ERROR_ACCESS_PAGE;
          return;
      }

      if ($category->params == '')
          $category->params = '-2';
      if (!checkAccess_VM($category->params, 'RECURSE', userGID_VM($my->id), $acl))
      {
          echo _VEHICLE_MANAGER_ERROR_ACCESS_PAGE;
          return;
      }
      $params2 = unserialize($category->params2);
      if ($layout == '')
      {
          if ($params2->alone_category == '')
          {
              $layout = $vehiclemanager_configuration['view_type'];
              if(empty($layout))$layout = 'default';
          } else
          {
              $layout = $params2->alone_category;
          }
      }
      //sorting
      $item_session = JFactory::getSession();
      $sort_arr = $item_session->get('vm_vehiclesort', '');
      if (is_array($sort_arr))
      {
          $tmp1 = mosGetParam($_POST, 'order_direction');
          if ($tmp1 != '')
              $sort_arr['order_direction'] = $tmp1;
          $tmp1 = mosGetParam($_POST, 'order_field');
          if ($tmp1 != '')
              $sort_arr['order_field'] = $tmp1;
          $item_session->set('vm_vehiclesort', $sort_arr);
      } else
      {
          $sort_arr = array();
          $sort_arr['order_field'] = 'vtitle';
          $sort_arr['order_direction'] = 'asc';
          $item_session->set('vm_vehiclesort', $sort_arr);
      }
      if ($sort_arr['order_field'] == "price")
          $sort_string = "CAST( " . $sort_arr['order_field'] . " AS SIGNED)" . " " . $sort_arr['order_direction'];
      else
          $sort_string = $sort_arr['order_field'] . " " . $sort_arr['order_direction'];

      //getting groups of user
      $s = vmLittleThings::getWhereUsergroupsCondition();

      
      if (isset($langContent) ){
          
          $lang = $langContent;
          $query = "SELECT lang_code FROM #__languages WHERE sef = '$lang'";
          $database->setQuery($query);
          $lang = $database->loadResult();
          $lang = " and (v.language like 'all' or v.language like '' or v.language like '*' or v.language is null or v.language like '$lang') 
                   AND (c.language like 'all' or c.language like '' or c.language like '*' or c.language is null or c.language like '$lang') ";
      } else
      {
          $lang = "";
      }

      $query = "SELECT COUNT(DISTINCT v.id)
          \nFROM #__vehiclemanager_vehicles AS v"
              . "\nLEFT JOIN #__vehiclemanager_categories AS vc ON vc.iditem=v.id"
              . "\nLEFT JOIN #__vehiclemanager_main_categories AS c ON c.id=vc.idcat"
              . "\nWHERE c.id = '$catid' AND v.published='1' AND v.approved='1' AND c.published='1'
            AND ($s) $lang ";

      $database->setQuery($query);
      $total = $database->loadResult();

      $pageNav = new JPagination($total, $limitstart, $limit);

      // getting all vehicles for this category
      $query = "SELECT v.*,vc.idcat AS catid,vc.idcat AS idcat, c.title as category_titel
     \nFROM #__vehiclemanager_vehicles AS v"
              . "\nLEFT JOIN #__vehiclemanager_categories AS vc ON vc.iditem=v.id"
              . "\nLEFT JOIN #__vehiclemanager_main_categories AS c ON c.id=vc.idcat"
              . "\nWHERE vc.idcat = '$catid' AND v.published='1' AND v.approved='1' "
              . "\n    AND c.published='1' $lang AND ($s)"
              . "\nGROUP BY v.id"
              . "\nORDER BY " . $sort_string
              . "\nLIMIT $pageNav->limitstart,$pageNav->limit;";
      $database->setQuery($query);
// print_r($query); exit;
      $vehicles = $database->loadObjectList();

       $query = "SELECT v.*,c.id, c.parent_id, c.title, c.published, c.image,COUNT(vc.iditem) as vehicles, '1' as display" .
              " \n FROM  #__vehiclemanager_main_categories as c
            \n LEFT JOIN #__vehiclemanager_categories AS vc ON vc.idcat=c.id
            \n LEFT JOIN #__vehiclemanager_vehicles AS v ON v.id=vc.iditem
            \n WHERE c.section='com_vehiclemanager' 
            AND c.published=1  $lang AND ({$s})
            \n GROUP BY c.id
            \n ORDER BY c.parent_id DESC, c.ordering ";

      $database->setQuery($query);
      $cat_all = $database->loadObjectList();

      foreach ($cat_all as $k1 => $cat_item1) {
          if (is_exist_curr_and_subcategory_vehicles($cat_all[$k1]->id))
          {                
                
                  $query = "SELECT COUNT(vc.iditem) as vehicles " .
                          " \n FROM  #__vehiclemanager_main_categories as c
                        \n LEFT JOIN #__vehiclemanager_categories AS vc ON vc.idcat=c.id
                        \n LEFT JOIN #__vehiclemanager_vehicles AS v ON v.id=vc.iditem 
                        \n WHERE c.section='com_vehiclemanager' AND c.published=1  $lang
                        \n AND ( v.published || isnull(v.published) ) AND ( v.approved || isnull(v.approved) ) AND ({$s})
                        \n AND c.id = " . $cat_all[$k1]->id . "    
                        \n GROUP BY c.id
                        \n ORDER BY c.parent_id DESC, c.ordering ";              
              
                  $database->setQuery($query);

                  $vehicles_count = $database->loadObjectList();
      
                  if($vehicles_count)
                      $cat_all[$k1]->vehicles = $vehicles_count[0]->vehicles;
                  else
                      $cat_all[$k1]->vehicles = 0;
          } else
              $cat_all[$k1]->display = 0;
      }  

      if (version_compare(JVERSION, '3.0', 'ge'))
      {
          $menu = new JTableMenu($database);
          $menu->load($Itemid);
          $params = new JRegistry;
          $params->loadString($menu->params);
      } else
      {
          $menu = new mosMenu($database);
          $menu->load($Itemid);
          $params = new mosParameters($menu->params);
      }

      $menu_name = set_header_name_vm($menu, $Itemid);
      $params->def('header', $menu_name);
      $params->def('pageclass_sfx', '');
      $params->def('category_name', $category->title);
      $params->def('show_search', '1');
      
  if( $vehiclemanager_configuration['price']['show']){
    $params->def( 'show_pricestatus', 1 );
    if (checkAccess_VM($vehiclemanager_configuration['price']['registrationlevel'],'NORECURSE', userGID_VM($my->id), $acl)) {
      $params->def( 'show_pricerequest', 1);
    }
  }
          
      if (($vehiclemanager_configuration['rentstatus']['show']))
      {
          if (checkAccess_VM($vehiclemanager_configuration['rentrequest']['registrationlevel'], 'RECURSE', userGID_VM($my->id), $acl))
          {
              $params->def('show_rentstatus', 1);
              $params->def('show_rentrequest', 1);
          }
      }

      //add to path category name
      PHP_vehiclemanager::constructPathway($category);

      if (($GLOBALS['Reviews_vehicle_show']))
      {
          $params->def('show_reviews_vehicle', 1);
          if (checkAccess_VM($GLOBALS['Reviews_vehicle_registrationlevel'], 'RECURSE', userGID_VM($my->id), $acl))
          {
              $params->def('show_reviews_registrationlevel', 1);
          }
      }

      $params->def('sort_arr_order_direction', $sort_arr['order_direction']);
      $params->def('sort_arr_order_field', $sort_arr['order_field']);

      //add for show in category picture
      if (($GLOBALS['cat_pic_show']))
          $params->def('show_cat_pic', 1);

      $params->def('show_rating', 1);
      $params->def('hits', 1);
      $params->def('back_button', $mainframe->getCfg('back_button'));

      $currentcat = new stdClass();
      $currentcat->descrip = $category->description;

      // page image
      $currentcat->img = null;
      $path = $mosConfig_live_site . '/images/stories/';
      if ($category->image != null && count($category->image) > 0)
      {
          $currentcat->img = $path . $category->image;
          $currentcat->align = $category->image_position;
      }

      // $currentcat->header = $params->get( 'header' );
      // $currentcat->header = ((trim($currentcat->header))?$currentcat->header.":":""). $category->title;
      $currentcat->header = $category->title;

      // used to show table rows in alternating colours
      $tabclass = array('sectiontableentry1', 'sectiontableentry2');

      //view type
      $params->def('view_type', $vehiclemanager_configuration['view_type']);
      $params->def('minifotohigh', $vehiclemanager_configuration['foto']['high']);
      $params->def('minifotowidth', $vehiclemanager_configuration['foto']['width']);

      foreach ($vehicles as $vehicle) {
          if ($vehicle->language != '*')
          {
              $query = "SELECT sef FROM #__languages WHERE lang_code = '$vehicle->language'";
              $database->setQuery($query);
              $vehicle->language = $database->loadResult();
          }
      }



      $params->def('singlecategory01', "{loadposition com_vehiclemanager_single_category_01,xhtml}");
      $params->def('singlecategory02', "{loadposition com_vehiclemanager_single_category_02,xhtml}");
      $params->def('singlecategory03', "{loadposition com_vehiclemanager_single_category_03,xhtml}");
      $params->def('singlecategory04', "{loadposition com_vehiclemanager_single_category_04,xhtml}");
      $params->def('singlecategory05', "{loadposition com_vehiclemanager_single_category_05,xhtml}");
      $params->def('singlecategory06', "{loadposition com_vehiclemanager_single_category_06,xhtml}");
      $params->def('singlecategory07', "{loadposition com_vehiclemanager_single_category_07,xhtml}");
      $params->def('singlecategory08', "{loadposition com_vehiclemanager_single_category_08,xhtml}");
      $params->def('singlecategory09', "{loadposition com_vehiclemanager_single_category_09,xhtml}");
      $params->def('singlecategory10', "{loadposition com_vehiclemanager_single_category_10,xhtml}");
      $params->def('singlecategory11', "{loadposition com_vehiclemanager_single_category_11,xhtml}");
     
      HTML_vehiclemanager::displayVehicles($vehicles, $currentcat, $params, $tabclass, $catid, $cat_all, PHP_vehiclemanager::is_exist_subcategory_vehicles($catid), $pageNav, $layout);
      
  }

  static function showItem_VM($id, $catid, $printItem, $layout){
      global $mainframe, $database, $my, $acl, $option;
      global $mosConfig_shownoauth, $mosConfig_live_site, $mosConfig_absolute_path;
      global $cur_template, $Itemid, $vehiclemanager_configuration;
      PHP_vehiclemanager::addTitleAndMetaTags($id);
      $database->setQuery("SELECT id FROM #__vehiclemanager_vehicles");
      if (version_compare(JVERSION, '3.0', 'lt')){
        $trueid = $database->loadResultArray();
      }else{
        $trueid = $database->loadColumn();
      }
      if (!in_array($id, $trueid)){
        echo _VEHICLE_MANAGER_ERROR_NO_FIND_ID;
        return;
      }
      //add to path category name
      //getting the current category informations
      $query = "SELECT * FROM #__vehiclemanager_main_categories WHERE id='$catid'";
      $database->setQuery($query);
      $category = $database->loadObjectList();
      if (isset($category[0]))
        $category = $category[0];
      else{
        echo _VEHICLE_MANAGER_ERROR_ACCESS_PAGE;
        return;
      }
      PHP_vehiclemanager::constructPathway($category);
      $pathway = sefRelToAbs('index.php?option=' . $option .
              '&amp;task=alone_category&amp;catid=' . $category->id . '&amp;Itemid=' . $Itemid);
      $pathway_name = "vehicle";
      // for  1.6  
      $path_way = $mainframe->getPathway();
      $path_way->addItem($pathway_name, $pathway);
      //Record the hit
      $sql = "UPDATE #__vehiclemanager_vehicles SET hits = hits + 1 WHERE id = " . $id . "";
      $database->setQuery($sql);
      $database->query();

      $sql2 = "UPDATE #__vehiclemanager_vehicles SET featured_clicks = featured_clicks - 1 WHERE featured_clicks > 0 and id = " . $id . "";
      $database->setQuery($sql2);
      $database->query();

      $sql3 = "UPDATE #__vehiclemanager_vehicles SET featured_shows = featured_shows - 1 WHERE featured_shows > 0 and id = " . $id . "";
      $database->setQuery($sql3);
      $database->query();

      //load the vehicle
      $vehicle = new mosVehicleManager($database);
      $vehicle->load($id);
      $vehicle->setOwnerName();
      $access = $vehicle->getAccess_VM();

      $selectstring = "SELECT a.* FROM #__vehiclemanager_vehicles AS a";
      $database->setQuery($selectstring);
      $rows = $database->loadObjectList();

      if (version_compare(JVERSION, '3.0', 'lt')){
        $curdate = strtotime(JFactory::getDate()->toMySQL());
      } else{
        $curdate = strtotime(JFactory::getDate()->toSQL());
      }

      foreach ($rows as $row) {
        $check = strtotime($row->checked_out_time);
        $remain = 7200 - ($curdate - $check);
        if (($remain <= 0) && ($row->checked_out != 0)){
          $item = new mosVehicleManager($database);
          $item->checkin($row->id);
        }
      }

      if (!checkAccess_VM($access, 'RECURSE', userGID_VM($my->id), $acl)){
        echo _VEHICLE_MANAGER_ERROR_ACCESS_PAGE;
        return;
      }
      if ($vehicle->owner_id != $my->id){
        if ($vehicle->published == 0){
          echo _VEHICLE_MANAGER_ERROR_VEHICLE_NOT_PUBLISHED;
          return;
        }
        if ($vehicle->approved == 0){
          echo _VEHICLE_MANAGER_ERROR_VEHICLE_NOT_APPROVED;
          return;
        }
      }
      // for 1.6    
      $path_way->addItem(substr($vehicle->vtitle, 0, 32) . "");
      // --
////////////////////////////////////////////////////////////////////////////////
      //Select list for vehicle type
      $vtype[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
      $vtype1 = explode(',', _VEHICLE_MANAGER_OPTION_VEHICLE_TYPE);
      $i = 1;
      foreach ($vtype1 as $vtype2) {
          $vtype[$vtype2] = $i;
          $i++;
      }

      //Select list for listing type
      $listing_type[0] = _VEHICLE_MANAGER_OPTION_SELECT;
      $listing_type[1] = _VEHICLE_MANAGER_OPTION_FOR_RENT;
      $listing_type[2] = _VEHICLE_MANAGER_OPTION_FOR_SALE;

      //Select list for price type
      $price_type[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
      $price_type1 = explode(',', _VEHICLE_MANAGER_OPTION_PRICE_TYPE);
      $i = 1;
      foreach ($price_type1 as $price_type2) {
          $price_type[$price_type2] = $i;
          $i++;
      }

      //Select list for condition
      $vcondition[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
      $vcondition1 = explode(',', _VEHICLE_MANAGER_OPTION_VEHICLE_CONDITION);
      $i = 1;
      foreach ($vcondition1 as $vcondition2) {
          $vcondition[$vcondition2] = $i;
          $i++;
      }

      //Select list for listing status
      $listing_status[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
      $listing_status1 = explode(',', _VEHICLE_MANAGER_OPTION_LISTING_STATUS);
      $i = 1;
      foreach ($listing_status1 as $listing_status2) {
          $listing_status[$listing_status2] = $i;
          $i++;
      }

      //Select list for transmission
      $transmission[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
      $transmission1 = explode(',', _VEHICLE_MANAGER_OPTION_TRANSMISSION);
      $i = 1;
      foreach ($transmission1 as $transmission2) {
          $transmission[$transmission2] = $i;
          $i++;
      }

      //Select list for drive type
      $drive_type[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
      $drive_type1 = explode(',', _VEHICLE_MANAGER_OPTION_DRIVE_TYPE);
      $i = 1;
      foreach ($drive_type1 as $drive_type2) {
          $drive_type[$drive_type2] = $i;
          $i++;
      }

      //Select list for number of cylinder
      $numcylinder[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
      $numcylinder1 = explode(',', _VEHICLE_MANAGER_OPTION_NUMBER_OF_CYLINDERS);
      $i = 1;
      foreach ($numcylinder1 as $numcylinder2) {
          $numcylinder[$numcylinder2] = $i;
          $i++;
      }

      //Select list for number of speed
      $numspeed[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
      $numspeed1 = explode(',', _VEHICLE_MANAGER_OPTION_NUMBER_OF_SPEEDS);
      $i = 1;
      foreach ($numspeed1 as $numspeed2) {
          $numspeed[$numspeed2] = $i;
          $i++;
      }

      //Select list for fuel type
      $fuel_type[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
      $fuel_type1 = explode(',', _VEHICLE_MANAGER_OPTION_FUEL_TYPE);
      $i = 1;
      foreach ($fuel_type1 as $fuel_type2) {
          $fuel_type[$fuel_type2] = $i;
          $i++;
      }

      //Select list for number of doors
      $numdoors[_VEHICLE_MANAGER_OPTION_SELECT] = 0;
      $numdoors1 = explode(',', _VEHICLE_MANAGER_OPTION_NUMBER_OF_DOORS);
      $i = 1;
      foreach ($numdoors1 as $numdoors2) {
          $numdoors[$numdoors2] = $i;
          $i++;
      }
      ////////////////////////////////////////////////////////////////////////////////

      $session = JFactory::getSession();
      $session->get("obj_vehicle", $vehicle);

      // Parameters
      if (version_compare(JVERSION, '3.0', 'ge'))
      {
          $menu = new JTableMenu($database);
          $menu->load($Itemid);
          $params = new JRegistry;
          $params->loadString($menu->params);
      } else
      {
          $menu = new mosMenu($database);
          $menu->load($Itemid);
          $params = new mosParameters($menu->params);
      }

      // for 1.6 
      //$app = JFactory::getApplication();
      //$menu1 = $app->getMenu();
      $Itemid = protectInjection('Itemid'); // $_REQUEST['Itemid'];
      $menu_name = set_header_name_vm($menu, $Itemid);
//   $menu_name = $menu1->getItem($Itemid)->title ;
      $params->def('header', $menu_name);
      // --
      $params->def('pageclass_sfx', '');

      if ($vehiclemanager_configuration['calendar']['show'])
      {
          $params->def('calendar_show', 1);
          if (checkAccess_VM($vehiclemanager_configuration['calendarlist']['registrationlevel'], 'NORECURSE', userGID_VM($my->id), $acl))
          {
              $params->def('calendarlist_show', 1);
          }
      }

      if (($GLOBALS['reviews_show']))
      {
          $params->def('show_reviews', 1);
          if (checkAccess_VM($GLOBALS['reviews_registrationlevel'], 'RECURSE', userGID_VM($my->id), $acl))
          {
              $params->def('show_inputreviews', 1);
          }
      }
      if (($vehiclemanager_configuration['buystatus']['show']))
      {
          if (checkAccess_VM($vehiclemanager_configuration['buyrequest']['registrationlevel'], 'RECURSE', userGID_VM($my->id), $acl))
          {
              $params->def('show_buystatus', 1);
              $params->def('show_buyrequest', 1);
          }
      }
      if (($GLOBALS['edocs_show']))
      {
          $params->def('show_edocstatus', 1);
          if (checkAccess_VM($GLOBALS['edocs_registrationlevel'], 'RECURSE', userGID_VM($my->id), $acl))
          {
              $params->def('show_edocsrequest', 1); //+18.01
              //+18.01
          }
      }

      if (($vehiclemanager_configuration['price']['show']))
      {
          $params->def('show_pricestatus', 1);
          if (checkAccess_VM($vehiclemanager_configuration['price']['registrationlevel'], 'RECURSE', userGID_VM($my->id), $acl))
          {
              $params->def('show_pricerequest', 1); //+18.01
          }
      }

      $params->def('pageclass_sfx', '');
      $params->def('item_description', 1);
      $params->def('rent_request', $vehiclemanager_configuration['rentrequest']['registrationlevel']);
      $params->def('buy_request', $vehiclemanager_configuration['buyrequest']['registrationlevel']);
      //$params->def('paypal_buy', $vehiclemanager_configuration['paypal_buy']['registrationlevel']);
      //$params->def('paypal_rent', $vehiclemanager_configuration['paypal_rent']['registrationlevel']);
      $params->def('show_edoc', $GLOBALS['edocs_show']);
      $params->def('back_button', $mainframe->getCfg('back_button'));

      // page header
      $currentcat = new stdClass();
      //$currentcat->header = $params->get( 'header' );
      //$currentcat->header = ((trim($currentcat->header))?$currentcat->header.":":""). $vehicle->vtitle;
      $currentcat->header = $category->title . " : " . $vehicle->vtitle;
      // page image
      $currentcat->img = "./components/com_vehiclemanager/images/vm_logo.png";

      header('Content-Type: text/html; charset=utf-8');

      $query = "SELECT f.* ";
      $query .= "FROM #__vehiclemanager_feature as f ";
      $query .= "LEFT JOIN #__vehiclemanager_feature_vehicles as fv ON f.id = fv.fk_featureid ";
      $query .= "WHERE f.published = 1 and fv.fk_vehicleid = $id ";
      $query .= "ORDER BY f.categories";
      $database->setQuery($query);
      $vehicle_feature = $database->loadObjectList();

      $query = "select main_img from #__vehiclemanager_photos WHERE fk_vehicleid='$vehicle->id' order by img_ordering,id";
      $database->setQuery($query);
      $vehicle_photos = $database->loadObjectList();
      // show the vehicle
////////////////

      $currencyArr = array();
      $currentCurrency;
      $currencys = explode(';', $vehiclemanager_configuration['currency']);
      foreach ($currencys as $oneCurency) {
          $oneCurrArr = explode('=', $oneCurency);
          if(!empty($oneCurrArr[0]) && !empty($oneCurrArr[1])){
             $currencyArr[$oneCurrArr[0]] = $oneCurrArr[1];
             if($vehicle->priceunit == $oneCurrArr[0]){
                 $currentCurrency = $oneCurrArr[1];
             }
          } 
      }
    
      foreach ($currencyArr as $key=>$value) {
        if (!isset($currentCurrency)) {
          $currencys_price[$key] = round($value);
        } else {
          $currencys_price[$key] = round($value / $currentCurrency * $vehicle->price, 2);
        }
      }
////////
      $params->def('view01', "{loadposition com_vehiclemanager_view_vehicle_01,xhtml}");
      $params->def('view02', "{loadposition com_vehiclemanager_view_vehicle_02,xhtml}");
      $params->def('view03', "{loadposition com_vehiclemanager_view_vehicle_03,xhtml}");
      $params->def('view04', "{loadposition com_vehiclemanager_view_vehicle_04,xhtml}");
      $params->def('view05', "{loadposition com_vehiclemanager_view_vehicle_05,xhtml}");
      $params->def('view06', "{loadposition com_vehiclemanager_view_vehicle_06,xhtml}");
      $params->def('view07', "{loadposition com_vehiclemanager_view_vehicle_07,xhtml}");
      //////////////start select video/tracks
      $query = "SELECT src,type,youtube FROM #__vehiclemanager_video_source AS v
                LEFT JOIN  #__vehiclemanager_vehicles AS veh ON v.fk_vehicle_id=veh.id
                WHERE v.fk_vehicle_id =" . $vehicle->id;
      $database->setQuery($query);
      $videos = $database->loadObjectList();
      $query = "SELECT src,kind,scrlang,label FROM #__vehiclemanager_track_source AS t
                LEFT JOIN  #__vehiclemanager_vehicles AS veh ON t.fk_vehicle_id = veh.id
                WHERE t.fk_vehicle_id = " . $vehicle->id;
      $database->setQuery($query);
      $tracks = $database->loadObjectList();
      /////////////////////end


          HTML_vehiclemanager::displayVehicle($vehicle, $tabclass, $params, $currentcat, $ratinglist, $vehicle_photos,$videos,$tracks, $id, $catid, $vehicle_feature, $currencys_price, $layout);

  }

//**************   begin gevi direct url   *************************

  static function new_direct_url($id)
  {
      global $database;

      $database->setQuery("SELECT URL FROM #__vehiclemanager_vehicles WHERE id=" . $id . ";");
      $direct_url = $database->loadResult();
      header("Location: " . $direct_url);
  }

//************   end gevi direct url   ******************************


  static function getMonth($month)
  {

      switch ($month) {
          case 1:
              $smonth = JText::_('JANUARY');
              break;
          case 2:
              $smonth = JText::_('FEBRUARY');
              break;
          case 3:
              $smonth = JText::_('MARCH');
              break;
          case 4:
              $smonth = JText::_('APRIL');
              break;
          case 5:
              $smonth = JText::_('MAY');
              break;
          case 6:
              $smonth = JText::_('JUNE');
              break;
          case 7:
              $smonth = JText::_('JULY');
              break;
          case 8:
              $smonth = JText::_('AUGUST');
              break;
          case 9:
              $smonth = JText::_('SEPTEMBER');
              break;
          case 10:
              $smonth = JText::_('OCTOBER');
              break;
          case 11:
              $smonth = JText::_('NOVEMBER');
              break;
          case 12:
              $smonth = JText::_('DECEMBER');
              break;
      }

      return $smonth;
  }

 static function getMonthCal($month, $year, $id) {
      
      global $database, $vehiclemanager_configuration;

      //$query = "SELECT rent_from, rent_until FROM #__vehiclemanager_rent WHERE fk_vehicleid='$id'";
      
      $query = "SELECT rent_from, rent_until, rent_return FROM #__vehiclemanager_rent WHERE fk_vehicleid='$id'";
      $database->setQuery($query);
      $calenDate = $database->loadObjectList();        
      
      $skip = date("w", mktime(0, 0, 0, $month, 1, $year)) - 1;

      if ($skip < 0)
      {
          $skip = 6;
      }

      $daysInMonth = date("t", mktime(0, 0, 0, $month, 1, $year));      
      //$rent = array();
     // $rentMonthArr = array();
      //$arrD = array();
      
/*******************************get only rent days*****************************/  

      
      $rentDataArr = array();
      foreach ($calenDate as &$value) {
          if(!($value->rent_return)){              
             array_push($rentDataArr, $value);
          }
      }
      $calenDate = $rentDataArr;
      
      
      
             
/******************************************************************************/        
/*        
      foreach ($calenDate as $key) {
          
          
          $rent_from = explode("-", $key->rent_from);
          $rent_until = explode("-", $key->rent_until);

          $currentYearFrom = (int)$rent_from[0]; 
          $currentMonthFrom = (int)$rent_from[1];
          $currentDayFrom = (int)$rent_from[2];  
          
          $currentYearUntil = (int)$rent_until[0]; 
          $currentMonthUntil = (int)$rent_until[1];
          $currentDayUntil = (int)$rent_until[2];            
                  
         
          if($currentYearFrom == $year){
                      
              if($currentMonthFrom == $month && $currentMonthUntil == $month){ 
                  for($d = 0; $d <= ($currentDayUntil - $currentDayFrom); $d++ ){
                      $arrD[] = $currentDayFrom + $d;
                  }     
              }else if($currentMonthFrom == $month){ 
                  for($d = 0; $d <= ($daysInMonth - $currentDayFrom); $d++){
                      $arrD[] = $currentDayFrom + $d;       
                  }  
              }else if($currentMonthUntil == $month){ 
                  for($d = 0; $d < $currentDayUntil; $d++){  
                      $arrD[] = $currentDayUntil - $d;  
                  }                   
              } 
          }     
              

          if (((int) $rent_from[0] == $year) && ((int) $rent_from[1] <= $month)){
              if ((int) $rent_from[1] == (int) $rent_until[1] and $rent_from[1]==$month){   
                  for ($i = (int) $rent_from[2]; $i <= (int) $rent_until[2]; $i++) {
                      $rent[] = $i;
                  } 
              } elseif ((int) $rent_from[1] == $month){
                  for ($i = (int) $rent_from[2]; $i <= $daysInMonth; $i++) {
                      $rent[] = $i;
                  }
              } elseif ((int) $rent_until[1] == $month and $rent_until[0]==$year){
                  for ($i = 1; $i <= (int) $rent_until[2]; $i++) {
                      $rent[] = $i;
                  }
              } elseif((int) $rent_from[1] == (int) $rent_until[1]){
                  
              } else {
                  for ($i = 1; $i <= $daysInMonth; $i++) {
                      $rent[] = $i;
                  }
              }
          } elseif (((int) $rent_from[0] < $year and (int) $rent_until[0] > $year ) ){
                  for ($i = 1; $i <= $daysInMonth; $i++) {
                      $rent[] = $i;
                  }
          } elseif (((int) $rent_until[0] == $year) && ((int) $rent_until[1] >= $month) and $rent_until[0]!=$rent_from[0]){
              if ((int) $rent_from[1] == (int) $rent_until[1]){
                  for ($i = (int) $rent_from[2]; $i <= (int) $rent_until[2]; $i++) {
                      $rent[] = $i;
                  }
              } else if ((int) $rent_from[1] == $month and $rent_from[0]==$year ){
                  for ($i = (int) $rent_from[2]; $i <= $daysInMonth; $i++) {
                      $rent[] = $i;
                  }
              } elseif ((int) $rent_until[1] == $month and $rent_until[0]==$year){
                  for ($i = 1; $i <= (int) $rent_until[2]; $i++) {
                      $rent[] = $i;
                  }
              } else {
                  for ($i = 1; $i <= $daysInMonth; $i++) {
                      $rent[] = $i;
                  }
              }
          }

     } 
*/       
      $calendar = '';
      $day = 1;
      $smonth = PHP_vehiclemanager::getMonth($month);

      $calendar = '<table class="tableC" style="border-collapse: separate;'.
        ' border-spacing: 2px;text-align:center"><tr class="year"><th colspan = "7">' .
        $smonth . ' ' . $year . '</th></tr><tr class="days"><th>' . JText::_('MON') .
        '</th><th>' . JText::_('TUE') . '</th><th>' . JText::_('WED') . '</th><th>' .
        JText::_('THU') . '</th><th>' . JText::_('FRI') . '</th><th>' . JText::_('SAT') .
         '</th><th>' . JText::_('SUN') . '</th></tr>';
      for ($i = 0; $i < 6; $i++) {
          $calendar .= '<tr>';
          for ($j = 0; $j < 7; $j++) {
              if (($skip > 0) or ($day > $daysInMonth)){
                  $calendar .= '<td> &nbsp; </td>';
                  $skip--;
              }else{ 
                  $isAvilable = getAvilableVM($calenDate,$month,$year,$vehiclemanager_configuration,$day);
                  $calendar .= '<td class="'.$isAvilable.'">' . $day . '</td>';
                 // if (in_array($month, $rentMonthArr) && in_array($day, $rent)){
                    //print_r('current month: '.$month.' current day: '.$day.' ('.$rent.') ');echo'<br />';                    
                              //$calendar .= '<td class="calendar_not_available">' . $day . '</td>';                  
                  $day++;
              }
          }
          $calendar .= '</tr>';
      }
      $calendar .= '</table>';
     
      return $calendar;
  }

  static function getCalendarPrice($month, $year, $id)
  {
      global $database;
      $query = "SELECT * FROM `#__vehiclemanager_rent_sal` " .
        " WHERE (`fk_vehiclesid`='$id') and (`yearW`='$year') and (`monthW`='$month')";
      $database->setQuery($query);
      $calenWeeks = $database->loadObjectList();
      if (!empty($calenWeeks))
      {
          $calenWeek = $calenWeeks[0];
          $calendar = "";
          $calendar = '<table style="text-align:left">';
          $calendar .= '<tr><td><b>' . _VEHICLE_MANAGER_LABEL_CALENDAR_WEEK . '<b></td></tr>';
          $calendar .= '<tr><td>' . str_replace("\n", "<br>\n", $calenWeek->week) . '</td></tr>';
          $calendar .= '<tr><td><b>' . _VEHICLE_MANAGER_LABEL_CALENDAR_WEEKEND . '<b></td></tr>';
          $calendar .= '<tr><td>' . str_replace("\n", "<br>\n", $calenWeek->weekend) . '</td></tr>';
          $calendar .= '<tr><td><b>' . _VEHICLE_MANAGER_LABEL_CALENDAR_MIDWEEK . '</b></td></tr>';
          $calendar .= '<tr><td><span>' . str_replace("\n", "<br>\n", $calenWeek->midweek) . '<span></td></tr>';
          $calendar .= '</table>';
          return $calendar;
      }
  }

  static function getCalendar($month, $year, $id)
  {
      $month = (int) $month;
      $year = (int) $year;

      if ($month == 1)
      {
          $month1 = 12;
          $year1 = $year - 1;
      } else
      {
          $month1 = $month - 1;
          $year1 = $year;
      }

      if ($month == 12)
      {
          $month2 = 1;
          $month3 = 2;
          $year2 = $year3 = $year + 1;
      } else
      {
          $month2 = $month + 1;
          $month3 = $month + 2;
          $year2 =$year3 = $year;
      }

      $calendar = new stdClass();
      
      $calendar->tab1 = PHP_vehiclemanager::getMonthCal($month1, $year1, $id);
      
      $calendar->tab2 = PHP_vehiclemanager::getMonthCal($month, $year, $id);

      $calendar->tab3 = PHP_vehiclemanager::getMonthCal($month2, $year2, $id);
      $calendar->tab4 = PHP_vehiclemanager::getMonthCal($month3, $year3, $id);

      $calendar->tab21 = PHP_vehiclemanager::getCalendarPrice($month1, $year1, $id);

      $calendar->tab22 = PHP_vehiclemanager::getCalendarPrice($month, $year, $id);

      $calendar->tab23 = PHP_vehiclemanager::getCalendarPrice($month2, $year2, $id);
      $calendar->tab24 = PHP_vehiclemanager::getCalendarPrice($month3, $year3, $id);

      return $calendar;
  }

  static function showSearchVehicles($options, $catid, $option, $layout)
  {

      global $mainframe, $database, $my;
      global $mosConfig_shownoauth, $mosConfig_live_site, $mosConfig_absolute_path;
      global $cur_template, $Itemid;
      

      PHP_vehiclemanager::addTitleAndMetaTags();

      $currentcat = new stdClass();
      // Parameters
      if (version_compare(JVERSION, '3.0', 'ge'))
      {
          $menu = new JTableMenu($database);
          $menu->load($Itemid);
          $params = new JRegistry;
          $params->loadString($menu->params);
      } else
      {
          $menu = new mosMenu($database);
          $menu->load($Itemid);
          $params = new mosParameters($menu->params);
      }
      
      

      ///$params->def( 'header', $menu->name );
      // for 1.6 
//    $app = JFactory::getApplication();
//    $menu1 = $app->getMenu();
      $menu_name = set_header_name_vm($menu, $Itemid);
//    $menu_name = $menu1->getItem($Itemid)->title ;
      $params->def('header', $menu_name);
      // --
      $params->def('pageclass_sfx', '');
      
      $params->def('show_search', '1');
      $params->def('back_button', $mainframe->getCfg('back_button'));

      $pathway = sefRelToAbs('index.php?option=' . $option .
              '&amp;task=show_search&amp;Itemid=' . $Itemid);
      $pathway_name = _VEHICLE_MANAGER_LABEL_SEARCH;

      $path_way = $mainframe->getPathway();
      $path_way->addItem($pathway_name, $pathway);

      $currentcat->descrip = _VEHICLE_MANAGER_SEARCH_DESC1;
      $currentcat->align = 'right';

      // page image
      $currentcat->img = "./components/com_vehiclemanager/images/vm_logo.png";


      $currentcat->header = $params->get('header');
      //$currentcat->header = ((trim($currentcat->header)) ? $currentcat->header . ":" : "") . _VEHICLE_MANAGER_LABEL_SEARCH;
      $currentcat->header =_VEHICLE_MANAGER_LABEL_SEARCH;


      // used to show table rows in alternating colours
      $tabclass = array('sectiontableentry1', 'sectiontableentry2');

      $categories[] = mosHTML::makeOption(_VEHICLE_MANAGER_LABEL_ALL, _VEHICLE_MANAGER_LABEL_ALL);
      $database->setQuery("SELECT id AS value, name AS text FROM #__vehiclemanager_main_categories" . "\nWHERE section='$option' ORDER BY ordering");
      $categories2 = array_merge($categories, $database->loadObjectList());

      if (count($categories2) < 1)
      {
          mosRedirect("index.php?option=categories&amp;section=$option&amp;err_msg=You must first create category for that section.&amp;Itemid=$Itemid");
      }

      //**********************
      $condition[] = mosHtml::makeOption(_VEHICLE_MANAGER_LABEL_ALL, _VEHICLE_MANAGER_LABEL_ALL);
      $condition1 = explode(',', _VEHICLE_MANAGER_OPTION_VEHICLE_CONDITION);
      $i = 1;
      foreach ($condition1 as $condition2) {
          $condition[] = mosHtml::makeOption($i, $condition2);
          $i++;
      }
      $condition_status_list = mosHTML::selectList($condition, 'vcondition', 'class="inputbox" size="1" style="width: 140px"', 'value', 'text', _VEHICLE_MANAGER_LABEL_ALL);
      $params->def('condition_status_list', $condition_status_list);

      //Select list for vehicle type
      $vehicletype[] = mosHtml::makeOption(_VEHICLE_MANAGER_LABEL_ALL, _VEHICLE_MANAGER_LABEL_ALL);
      $vehicletype1 = explode(',', _VEHICLE_MANAGER_OPTION_VEHICLE_TYPE);
      $i = 1;
      foreach ($vehicletype1 as $vehicletype2) {
          $vehicletype[] = mosHtml::makeOption($i, $vehicletype2);
          $i++;
      }
      $vehicle_type_list = mosHTML::selectList($vehicletype, 'vtype', 'class="inputbox" size="1" style="width: 140px"', 'value', 'text', _VEHICLE_MANAGER_LABEL_ALL);
      $params->def('vehicle_type_list', $vehicle_type_list);

      //Select list for vehicle transmission
      $transmission[] = mosHtml::makeOption(_VEHICLE_MANAGER_LABEL_ALL, _VEHICLE_MANAGER_LABEL_ALL);
      $transmission1 = explode(',', _VEHICLE_MANAGER_OPTION_TRANSMISSION);
      $i = 1;
      foreach ($transmission1 as $transmission2) {
          $transmission[] = mosHtml::makeOption($i, $transmission2);
          $i++;
      }
      $transmission_type_list = mosHTML::selectList($transmission, 'transmission', 'class="inputbox" size="1" style="width: 140px"', 'value', 'text', _VEHICLE_MANAGER_LABEL_ALL);
      $params->def('transmission_type_list', $transmission_type_list);
//**********************
//
//  $clist = mosHTML :: selectList($categories, 'catid', 'class="inputbox" size="1"', 'value', 'text', 0);

      $clist = vmLittleThings::com_veh_categoryTreeList(0, '', true, $categories);
      $params->def('yearto', date('Y'));
      $params->def('yearfrom', 1900);
//**********makers
      $makers[] = mosHtml::makeOption(_VEHICLE_MANAGER_LABEL_ALL, _VEHICLE_MANAGER_LABEL_ALL);
      $temp = mosVehicleManagerOthers::getMakersArray();
      $cars = $temp[0];
      foreach ($cars as $car) {
          if (trim($car) != '')
          {
              $makers[] = mosHtml::makeOption(trim($car), trim($car));
          }
      }
      $maker = mosHTML :: selectList($makers, 'maker', 'class="inputbox" size="1" style="width: 140px" onchange=vm_changedMaker(this)', 'value', 'text', _VEHICLE_MANAGER_LABEL_ALL);
      $params->def('maker', $maker);


      //*********maker end
      //******model
      $models[] = mosHtml::makeOption(_VEHICLE_MANAGER_LABEL_ALL, _VEHICLE_MANAGER_LABEL_ALL);
      $model = mosHTML :: selectList($models, 'model', 'class="inputbox" size="1" style="width: 140px"', 'value', 'text', _VEHICLE_MANAGER_LABEL_ALL);
      $params->def('models', $model);
      //******model end
      //fuel type
      $fueltype[] = mosHtml::makeOption(_VEHICLE_MANAGER_LABEL_ALL, _VEHICLE_MANAGER_LABEL_ALL);
      $fueltype1 = explode(',', _VEHICLE_MANAGER_OPTION_FUEL_TYPE);
      $i = 1;
      foreach ($fueltype1 as $fueltype2) {
          $fueltype[] = mosHtml::makeOption($i, $fueltype2);
          $i++;
      }
      $fuel_type_list = mosHTML :: selectList($fueltype, 'fuel_type', 'class="inputbox" size="1" style="width: 140px"', 'value', 'text', _VEHICLE_MANAGER_LABEL_ALL);
      $params->def('fuel_type_list', $fuel_type_list);

      //listing type
      $listing_type[] = mosHtml::makeOption(_VEHICLE_MANAGER_LABEL_ALL, _VEHICLE_MANAGER_LABEL_ALL);
      $listing_type[] = mosHtml::makeOption(1, _VEHICLE_MANAGER_OPTION_FOR_RENT);
      $listing_type[] = mosHtml::makeOption(2, _VEHICLE_MANAGER_OPTION_FOR_SALE);
      $listing_type_list = mosHTML :: selectList($listing_type, 'listing_type', 'class="inputbox" size="1" style="width: 140px"', 'value', 'text', _VEHICLE_MANAGER_LABEL_ALL);
      $params->def('listing_type_list', $listing_type_list);

      //price type
      $price_type[] = mosHtml::makeOption(_VEHICLE_MANAGER_LABEL_ALL, _VEHICLE_MANAGER_LABEL_ALL);
      $price_type1 = explode(',', _VEHICLE_MANAGER_OPTION_PRICE_TYPE);
      $i = 1;
      foreach ($price_type1 as $price_type2) {
          $price_type[] = mosHtml::makeOption($i, $price_type2);
          $i++;
      }
      $price_type_list = mosHTML :: selectList($price_type, 'price_type', 'class="inputbox" size="1" style="width: 140px"', 'value', 'text', _VEHICLE_MANAGER_LABEL_ALL);
      $params->def('price_type_list', $price_type_list);

      //listing status
      $listing_status[] = mosHtml::makeOption(_VEHICLE_MANAGER_LABEL_ALL, _VEHICLE_MANAGER_LABEL_ALL);
      $listing_status1 = explode(',', _VEHICLE_MANAGER_OPTION_LISTING_STATUS);
      $i = 1;
      foreach ($listing_status1 as $listing_status2) {
          $listing_status[] = mosHtml::makeOption($i, $listing_status2);
          $i++;
      }
      $listing_status_list = mosHTML::selectList($listing_status, 'listing_status', 'class="inputbox" size="1" style="width: 140px"', 'value', 'text', _VEHICLE_MANAGER_LABEL_ALL);
      $params->def('listing_status_list', $listing_status_list);

      //drive type
      $drive_type[] = mosHtml::makeOption(_VEHICLE_MANAGER_LABEL_ALL, _VEHICLE_MANAGER_LABEL_ALL);
      $drive_type1 = explode(',', _VEHICLE_MANAGER_OPTION_DRIVE_TYPE);
      $i = 1;
      foreach ($drive_type1 as $drive_type2) {
          $drive_type[] = mosHtml::makeOption($i, $drive_type2);
          $i++;
      }
      $drive_type_list = mosHTML :: selectList($drive_type, 'drive_type', 'class="inputbox" size="1" style="width: 140px"', 'value', 'text', _VEHICLE_MANAGER_LABEL_ALL);
      $params->def('drive_type_list', $drive_type_list);

      //number of cylinders
      $cylinder[] = mosHtml::makeOption(_VEHICLE_MANAGER_LABEL_ALL, _VEHICLE_MANAGER_LABEL_ALL);
      $cylinder1 = explode(',', _VEHICLE_MANAGER_OPTION_NUMBER_OF_CYLINDERS);
      $i = 1;
      foreach ($cylinder1 as $cylinder2) {
          $cylinder[] = mosHtml::makeOption($i, $cylinder2);
          $i++;
      }
      $cylinder_list = mosHTML::selectList($cylinder, 'cylinder', 'class="inputbox" size="1" style="width: 140px"', 'value', 'text', _VEHICLE_MANAGER_LABEL_ALL);
      $params->def('cylinder_list', $cylinder_list);

      //number of speeds
      $num_speed[] = mosHtml::makeOption(_VEHICLE_MANAGER_LABEL_ALL, _VEHICLE_MANAGER_LABEL_ALL);
      $num_speed1 = explode(',', _VEHICLE_MANAGER_OPTION_NUMBER_OF_SPEEDS);
      $i = 1;
      foreach ($num_speed1 as $num_speed2) {
          $num_speed[] = mosHtml::makeOption($i, $num_speed2);
          $i++;
      }
      $num_speed_list = mosHTML::selectList($num_speed, 'num_speed', 'class="inputbox" size="1" style="width: 140px"', 'value', 'text', _VEHICLE_MANAGER_LABEL_ALL);
      $params->def('num_speed_list', $num_speed_list);

      //number of doors
      $doors[] = mosHtml::makeOption(_VEHICLE_MANAGER_LABEL_ALL, _VEHICLE_MANAGER_LABEL_ALL);
      $doors1 = explode(',', _VEHICLE_MANAGER_OPTION_NUMBER_OF_DOORS);
      $i = 1;
      foreach ($doors1 as $doors2) {
          $doors[] = mosHtml::makeOption($i, $doors2);
          $i++;
      }
      $doors_list = mosHTML::selectList($doors, 'doors', 'class="inputbox" size="1" style="width: 140px"', 'value', 'text', _VEHICLE_MANAGER_LABEL_ALL);
      $params->def('doors_list', $doors_list);

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
      $extra_list[$i] = mosHTML::selectList($extraOption, 'extra'.$i, 'class="inputbox" size="1" style="width: 140px"', 'value', 'text', '');
      $params->def('extrafield'.$i, $extra_list[$i]);
      }
      // end for extra6,extra7,extra8,extra9,extra10
      
      //price 
      $db = JFactory::getDBO();
      $query = "SELECT price  FROM   #__vehiclemanager_vehicles ";
      $database->setQuery($query);
      if (version_compare(JVERSION, "3.0.0", "lt"))
      {
          $prices = $database->loadResultArray();
      } else
      {
          $prices = $database->loadColumn();
      }
      rsort($prices, SORT_NUMERIC);
      $max_price = $prices[0];


      $price[] = mosHTML::makeOption(_VEHICLE_MANAGER_LABEL_FROM, _VEHICLE_MANAGER_LABEL_FROM);
      $price_to[] = mosHTML::makeOption(_VEHICLE_MANAGER_LABEL_TO, _VEHICLE_MANAGER_LABEL_TO);

//print_r($max_pric.":111111111:".$stepFinalPrice."<br />");
      $stepPrice = $max_price / 50;
      $stepPrice = (string) $stepPrice;
      $stepCount = strlen($stepPrice);
      if ($stepCount > 2) {
          $stepFinalPrice = $stepPrice[0] . $stepPrice[1];
          for ($i = 2; $i < $stepCount; $i++) {
              $stepFinalPrice .= '0';
          }
          $stepFinalPrice = (int) $stepFinalPrice;
      }
      else
          $stepFinalPrice = (int) $stepPrice;

      $params->def('pricefrom_one', 0);
      $params->def('priceto_one', $max_price);

      if($max_price == 0 || $stepFinalPrice == 0){
          $price[] = mosHTML::makeOption(0, 0);
          $price_to[] = mosHTML::makeOption(0, 0);
      }
      for ($i = 0; $i < $max_price+$stepFinalPrice; $i = $i + $stepFinalPrice) {
          $price[] = mosHTML::makeOption($i, $i);
          $price_to[] = mosHTML::makeOption($i, $i);
      }
      
      $pricelist = mosHTML::selectList($price, 'pricefrom', 'class="inputbox" size="1"', 'value', 'text');
      $params->def('pricefrom', $pricelist);
      $pricelistto = mosHTML::selectList($price_to, 'priceto', 'class="inputbox" size="1"', 'value', 'text');
      $params->def('priceto', $pricelistto);

      $params->def('showsearch01', "{loadposition com_vehiclemanager_show_search_01,xhtml}");
      $params->def('showsearch02', "{loadposition com_vehiclemanager_show_search_02,xhtml}");
      $params->def('showsearch03', "{loadposition com_vehiclemanager_show_search_03,xhtml}");
      $params->def('showsearch04', "{loadposition com_vehiclemanager_show_search_04,xhtml}");
      $params->def('showsearch05', "{loadposition com_vehiclemanager_show_search_05,xhtml}");
      HTML_vehiclemanager::showSearchVehicles($params, $currentcat, $clist, $option, $temp, $layout);
  }

  static function searchVehicles($options, $catid, $option, $ownername = '')
  {
      global $mainframe, $database, $my, $acl, $hide_js, $langContent,$task;
      global $mosConfig_shownoauth, $mosConfig_live_site, $mosConfig_absolute_path;
      global $cur_template, $Itemid, $vehiclemanager_configuration, $mosConfig_list_limit, $limit, $total, $limitstart;
      PHP_vehiclemanager::addTitleAndMetaTags();
      // Parameters
      if (version_compare(JVERSION, '3.0', 'ge'))
      {
          $menu = new JTableMenu($database);
          $menu->load($Itemid);
          $params = new JRegistry;
          $params->loadString($menu->params);
      } else
      {
          $menu = new mosMenu($database);
          $menu->load($Itemid);
          $params = new mosParameters($menu->params);
      }
      
      //get current user groups
      $s = vmLittleThings::getWhereUsergroupsCondition();
      $session = JFactory::getSession();
      if ($ownername == ''){
        $pathway = sefRelToAbs('index.php?option=' . $option .
                '&amp;task=show_search&amp;Itemid=' . $Itemid);
        $pathway_name = _VEHICLE_MANAGER_LABEL_SEARCH;
        $path_way = $mainframe->getPathway();
        $path_way->addItem($pathway_name, $pathway);
      }

      //sorting
      $item_session = JFactory::getSession();
      $sort_arr = $item_session->get('vm_vehiclesort', '');
      if (is_array($sort_arr)){
        $tmp1 = protectInjectionWithoutQuote('order_direction'); // mosGetParam($_POST, 'order_direction');
        if ($tmp1 != ''){
          $sort_arr['order_direction'] = $tmp1;
        }
        $tmp1 = protectInjectionWithoutQuote('order_field'); // mosGetParam($_POST, 'order_field');
        if ($tmp1 != ''){
          $sort_arr['order_field'] = $tmp1;
        }
        $item_session->set('vm_vehiclesort', $sort_arr);
      } else{
        $sort_arr = array();
        $sort_arr['order_field'] = 'maker';
        $sort_arr['order_direction'] = 'asc';
        $item_session->set('vm_vehiclesort', $sort_arr);
      }
      if ($sort_arr['order_field'] == "price")
        $sort_string = "CAST( " . $sort_arr['order_field'] . " AS SIGNED)" . " " . $sort_arr['order_direction'];
      else
        $sort_string = $sort_arr['order_field'] . " " . $sort_arr['order_direction'];  //end sortering

      $currentcat = new stdClass();
      $menu_name = set_header_name_vm($menu, $Itemid);

      
      $params->def('pageclass_sfx', '');
      $params->def('category_name', _VEHICLE_MANAGER_LABEL_SEARCH);
      $params->def('search_request', '1');
      $params->def('hits', 1);
      $params->def('show_rating', 1);

      $params->def('sort_arr_order_direction', $sort_arr['order_direction']);
      $params->def('sort_arr_order_field', $sort_arr['order_field']);

      $database->setQuery("SELECT id FROM #__menu WHERE link='index.php?option=com_vehiclemanager'");
      if ($database->loadResult() != $Itemid)
      {
          $params->def('wrongitemid', '1');
      };
      // price
      if (($vehiclemanager_configuration['price']['show']))
      {
          $params->def('show_pricestatus', 1);
          if (checkAccess_VM($vehiclemanager_configuration['price']['registrationlevel'], 'RECURSE', userGID_VM($my->id), $acl))
          {
              $params->def('show_pricerequest', 1); //+18.01
          }
      }
      
      if (($vehiclemanager_configuration['rentstatus']['show'])){
        if (checkAccess_VM($vehiclemanager_configuration['rentrequest']['registrationlevel'], 'RECURSE', userGID_VM($my->id), $acl)){
          $params->def('show_rentstatus', 1);
          $params->def('show_rentrequest', 1);
        }
      }

      if (($vehiclemanager_configuration['buystatus']['show']))
      {
          if (checkAccess_VM($vehiclemanager_configuration['buyrequest']['registrationlevel'], 'RECURSE', userGID_VM($my->id), $acl))
          {
              $params->def('show_buystatus', 1);
              $params->def('show_buyrequest', 1);
          }
      }

      if (($GLOBALS['Reviews_vehicle_show']))
      {
          $params->def('show_reviews_vehicle', 1);
          if (checkAccess_VM($GLOBALS['Reviews_vehicle_registrationlevel'], 'RECURSE', userGID_VM($my->id), $acl))
          {
              $params->def('show_reviews_registrationlevel', 1);
          }
      }
      //add for show in category picture
      if (($GLOBALS['cat_pic_show']))
          $params->def('show_cat_pic', 1);
      $params->def('back_button', $mainframe->getCfg('back_button'));
      $currentcat->descrip = _VEHICLE_MANAGER_SEARCH_DESC2;
      $currentcat->align = 'right';
      // page image
      $currentcat->img = "./components/com_vehiclemanager/images/vm_logo.png";
      $currentcat->header = $params->get('header');
      $currentcat->header = ((trim($currentcat->header)) ? $currentcat->header . ":" : "") . _VEHICLE_MANAGER_LABEL_SEARCH;

      // used to show table rows in alternating colours
      $tabclass = array('sectiontableentry1', 'sectiontableentry2');

      // view type
      $params->def('view_type', $vehiclemanager_configuration['view_type']);
      $params->def('minifotohigh', $vehiclemanager_configuration['foto']['high']);
      $params->def('minifotowidth', $vehiclemanager_configuration['foto']['width']);

      if (array_key_exists("searchtext", $_REQUEST))
      {
          $search = protectInjectionWithoutQuote('searchtext', ''); // mosGetParam($_REQUEST, 'searchtext', '');
          $search = addslashes($search);
          $session->set("poisk", $search);
      } else if ($ownername != "")
      {
          $session->set("poisk", $ownername);
      }

      $poisk_search = $session->get("poisk", "");
      //search copy beginning

      $where = array();
      $Rent = " ";
      $Address = " ";
      $Title = " ";
      $Condition = " ";
      $Vehicle_type = " ";
      $Description = " ";
      $Listing_type = " ";
      $Mileage = " ";
      $Engine_type = " ";
      $Transmission = " ";
      $Fuel_type = " ";
      $Interior_color = " ";
      $Exterior_extras = " ";
      $Exterior_colors = " ";
      $Dashboard_option = " ";
      $Interior_extras = " ";
      $Safety_options = " ";
      $Warranty_options = " ";
      $Extra1 = " ";
      $Extra2 = " ";
      $Extra3 = " ";
      $Extra4 = " ";
      $Extra5 = " ";
      $Wheeltype = " ";
      $Interior_colors = " ";
      $Model = " ";
      $RentSQL_JOIN_ = " ";
      $RentSQL_JOIN_2 = " ";
      $RentSQL = " ";
      $RentSQL_rent_until = " ";
      $vehicleid = " ";
      $Country = " ";
      $Region = " ";
      $City = " ";
      $District = " ";
      $Zipcode = " ";
      $Mileage = " ";
      $Contacts = " ";
      $City_fuel_mpg = " ";
      $Highway_fuel_mpg = " ";
      $Wheelbase = " ";
      $Rear_axe_type = " ";
      $Brakes_type = " ";

      if (isset($_REQUEST['exactly']) && $_REQUEST['exactly'] == "on")
      {
          $exactly = $poisk_search;
      } else
      {
          $exactly = "%$poisk_search%";
      }
      if (isset($_REQUEST['yearfrom']) && (intval($_REQUEST['yearfrom']) > 1900) && (intval($_REQUEST['yearto']) > 1900) && isset($_REQUEST['yearto']))
      {
          $year = " (b.year BETWEEN " . intval(protectInjectionWithoutQuote('yearfrom')) . " and " . intval(protectInjectionWithoutQuote('yearto')). ") ";
      } elseif (isset($_REQUEST['yearfrom']) && (intval($_REQUEST['yearfrom']) > 1900))
      {
          $year = " b.year >= " . intval(protectInjectionWithoutQuote('yearfrom')) . " ";
      } elseif (isset($_REQUEST['yearto']) && (intval($_REQUEST['yearto']) > 1900))
      {
          $year = " b.year <= " . intval(protectInjectionWithoutQuote('yearto')) . " ";
      }
      $is_add_or = false;
      $add_or_value = "  ";

      if ($poisk_search != '')
      {
          if (isset($_REQUEST['Address']) && $_REQUEST['Address'] == "on")
          {
              $Address = " ";
              if ($is_add_or)
                  $Address = " or ";

              $is_add_or = true;
              $Address .=" LOWER(b.vlocation) LIKE '$exactly' ";
          }
          if (isset($_REQUEST['Title']) && $_REQUEST['Title'] == "on")
          {
              $Title = " ";
              if ($is_add_or)
                  $Title = " or ";
              $is_add_or = true;
              $Title .=" LOWER(b.vtitle) LIKE '$exactly' ";
          }
          if (isset($_REQUEST['Description']) && $_REQUEST['Description'] == "on")
          {
              $Description = " ";
              if ($is_add_or)
                  $Description = " or ";
              $is_add_or = true;
              $Description .= "LOWER(b.description) LIKE '$exactly' ";
          }
          if (isset($_REQUEST['Engine_type']) && $_REQUEST['Engine_type'] == "on")
          {
              $Engine_type = " ";
              if ($is_add_or)
                  $Engine_type = " or ";
              $is_add_or = true;
              $Engine_type .= "LOWER(b.engine) LIKE '$exactly' ";
          }
          if (isset($_REQUEST['Exterior_colors']) && $_REQUEST['Exterior_colors'] == "on")
          {
              $Exterior_colors = " ";
              if ($is_add_or)
                  $Exterior_colors = " or ";
              $is_add_or = true;
              $Exterior_colors .= "LOWER(b.exterior_color) LIKE '$exactly' ";
          }
          if (isset($_REQUEST['Exterior_extras']) && $_REQUEST['Exterior_extras'] == "on")
          {
              $Exterior_extras = " ";
              if ($is_add_or)
                  $Exterior_extras = " or ";
              $is_add_or = true;
              $Exterior_extras .= "LOWER(b.exterior_amenities) LIKE '$exactly' ";
          }
          if (isset($_REQUEST['Dashboard_options']) && $_REQUEST['Dashboard_options'] == "on")
          {
              $Dashboard_option = " ";
              if ($is_add_or)
                  $Dashboard_option = " or ";
              $is_add_or = true;
              $Dashboard_option .= "LOWER(b.dashboard_options) LIKE '$exactly' ";
          }
          if (isset($_REQUEST['Interior_extras']) && $_REQUEST['Interior_extras'] == "on")
          {
              $Interior_extras = " ";
              if ($is_add_or)
                  $Interior_extras = " or ";
              $is_add_or = true;
              $Interior_extras .= "LOWER(b.interior_amenities) LIKE '$exactly' ";
          }
          if (isset($_REQUEST['Interior_colors']) && $_REQUEST['Interior_colors'] == "on")
          {
              $Interior_color = " ";
              if ($is_add_or)
                  $Interior_color = " or ";
              $is_add_or = true;
              $Interior_color .= "LOWER(b.interior_color) LIKE '$exactly' ";
          }
          if (isset($_REQUEST['Wheeltype']) && $_REQUEST['Wheeltype'] == "on")
          {
              $Wheeltype = " ";
              if ($is_add_or)
                  $Wheeltype = " or ";
              $is_add_or = true;
              $Wheeltype .= "LOWER(b.wheeltype) LIKE '$exactly' ";
          }
          if (isset($_REQUEST['Warranty_options']) && $_REQUEST['Warranty_options'] == "on")
          {
              $Warranty_options = " ";
              if ($is_add_or)
                  $Warranty_options = " or ";
              $is_add_or = true;
              $Warranty_options .= " LOWER(b.w_basic) LIKE '$exactly' ";
              $Warranty_options .= " or LOWER(b.w_drivetrain) LIKE '$exactly' ";
              $Warranty_options .= " or LOWER(b.w_corrosion) LIKE '$exactly' ";
              $Warranty_options .= " or LOWER(b.w_roadside_ass) LIKE '$exactly' ";
          }
          if (isset($_REQUEST['Safety_options']) && $_REQUEST['Safety_options'] == "on")
          {
              $Safety_options = " ";
              if ($is_add_or)
                  $Safety_options = " or ";
              $is_add_or = true;
              $Safety_options .= "LOWER(b.safety_options) LIKE '$exactly' ";
          }
          if (isset($_REQUEST['extra1']) && $_REQUEST['extra1'] == "on")
          {
              $Extra1 = " ";
              if ($is_add_or)
                  $Extra1 = " or ";
              $is_add_or = true;
              $Extra1 .= "LOWER(b.extra1) LIKE '$exactly' ";
          }
          if (isset($_REQUEST['extra2']) && $_REQUEST['extra2'] == "on")
          {
              $Extra2 = " ";
              if ($is_add_or)
                  $Extra2 = " or ";
              $is_add_or = true;
              $Extra2 .= "LOWER(b.extra2) LIKE '$exactly' ";
          }
          if (isset($_REQUEST['extra3']) && $_REQUEST['extra3'] == "on")
          {
              $Extra3 = " ";
              if ($is_add_or)
                  $Extra3 = " or ";
              $is_add_or = true;
              $Extra3 .= "LOWER(b.extra3) LIKE '$exactly' ";
          }
          if (isset($_REQUEST['extra4']) && $_REQUEST['extra4'] == "on")
          {
              $Extra4 = " ";
              if ($is_add_or)
                  $Extra4 = " or ";
              $is_add_or = true;
              $Extra4 .= "LOWER(b.extra4) LIKE '$exactly' ";
          }
          if (isset($_REQUEST['extra5']) && $_REQUEST['extra5'] == "on")
          {
              $Extra5 = " ";
              if ($is_add_or)
                  $Extra5 = " or ";
              $is_add_or = true;
              $Extra5 .= "LOWER(b.extra5) LIKE '$exactly' ";
          }
          if (isset($_REQUEST['Vehicleid']) && $_REQUEST['Vehicleid'] == "on")
          {
              $vehicleid = " ";
              if ($is_add_or)
                  $vehicleid = " or ";
              $is_add_or = true;
              $vehicleid .= "LOWER(b.vehicleid) LIKE '$exactly' ";
          }
          if (isset($_REQUEST['Country']) && $_REQUEST['Country'] == "on")
          {
              $Country = " ";
              if ($is_add_or)
                  $Country = " or ";
              $is_add_or = true;
              $Country .= "LOWER(b.country) LIKE '$exactly' ";
          }
          if (isset($_REQUEST['Region']) && $_REQUEST['Region'] == "on")
          {
              $Region = " ";
              if ($is_add_or)
                  $Region = " or ";
              $is_add_or = true;
              $Region .= "LOWER(b.region) LIKE '$exactly' ";
          }
          if (isset($_REQUEST['City']) && $_REQUEST['City'] == "on")
          {
              $City = " ";
              if ($is_add_or)
                  $City = " or ";
              $is_add_or = true;
              $City .= "LOWER(b.city) LIKE '$exactly' ";
          }
          if (isset($_REQUEST['District']) && $_REQUEST['District'] == "on")
          {
              $District = " ";
              if ($is_add_or)
                  $District = " or ";
              $is_add_or = true;
              $District .= "LOWER(b.district) LIKE '$exactly' ";
          }
          if (isset($_REQUEST['Zipcode']) && $_REQUEST['Zipcode'] == "on")
          {
              $Zipcode = " ";
              if ($is_add_or)
                  $Zipcode = " or ";
              $is_add_or = true;
              $Zipcode .= "LOWER(b.zipcode) LIKE '$exactly' ";
          }
          
          
          if (isset($_REQUEST['Mileage']) && $_REQUEST['Mileage'] == "on")
          {
              $Mileage = " ";
              if ($is_add_or)
                  $Mileage = " or ";
              $is_add_or = true;
              $Mileage .= "LOWER(b.mileage) LIKE '$exactly' ";
          }
          
          if (isset($_REQUEST['Contacts']) && $_REQUEST['Contacts'] == "on")
          {
              $Contacts = " ";
              if ($is_add_or)
                  $Contacts = " or ";
              $is_add_or = true;
              $Contacts .= "LOWER(b.contacts) LIKE '$exactly' ";
          }
          if (isset($_REQUEST['City_fuel_mpg']) && $_REQUEST['City_fuel_mpg'] == "on")
          {
              $City_fuel_mpg = " ";
              if ($is_add_or)
                  $City_fuel_mpg = " or ";
              $is_add_or = true;
              $City_fuel_mpg .= "LOWER(b.city_fuel_mpg) LIKE '$exactly' ";
          }
          if (isset($_REQUEST['Highway_fuel_mpg']) && $_REQUEST['Highway_fuel_mpg'] == "on")
          {
              $Highway_fuel_mpg = " ";
              if ($is_add_or)
                  $Highway_fuel_mpg = " or ";
              $is_add_or = true;
              $Highway_fuel_mpg .= "LOWER(b.highway_fuel_mpg) LIKE '$exactly' ";
          }
          if (isset($_REQUEST['Wheelbase']) && $_REQUEST['Wheelbase'] == "on")
          {
              $Wheelbase = " ";
              if ($is_add_or)
                  $Wheelbase = " or ";
              $is_add_or = true;
              $Wheelbase .= "LOWER(b.wheelbase) LIKE '$exactly' ";
          }
          if (isset($_REQUEST['Rear_axe_type']) && $_REQUEST['Rear_axe_type'] == "on")
          {
              $Rear_axe_type = " ";
              if ($is_add_or)
                  $Rear_axe_type = " or ";
              $is_add_or = true;
              $Rear_axe_type .= "LOWER(b.rear_axe_type) LIKE '$exactly' ";
          }
          if (isset($_REQUEST['Brakes_type']) && $_REQUEST['Brakes_type'] == "on")
          {
              $Brakes_type = " ";
              if ($is_add_or)
                  $Brakes_type = " or ";
              $is_add_or = true;
              $Brakes_type .= "LOWER(b.brakes_type) LIKE '$exactly' ";
          }
      }

      $vtype = protectInjectionWithoutQuote('vtype', ''); // mosGetParam($_REQUEST, 'vtype', '');
      $maker = protectInjectionWithoutQuote('maker', ''); // mosGetParam($_REQUEST, 'maker', '');
      $model = strtolower(protectInjectionWithoutQuote('model', ''));
      if ($model == "")
          $model = strtolower(protectInjectionWithoutQuote('vm_model', ''));
      $transmission = protectInjectionWithoutQuote('transmission', ''); // mosGetParam($_REQUEST, 'transmission', '');
      $vcondition = protectInjectionWithoutQuote('vcondition', ''); // mosGetParam($_REQUEST, 'vcondition', '');
      $fuel_type = protectInjectionWithoutQuote('fuel_type', ''); // mosGetParam($_REQUEST, 'fuel_type', '');
      $listing_type = protectInjectionWithoutQuote('listing_type', ''); // mosGetParam($_REQUEST, 'listing_type', '');
      $price_type = protectInjectionWithoutQuote('price_type', ''); // mosGetParam($_REQUEST, 'price_type', '');
      $listing_status = protectInjectionWithoutQuote('listing_status', ''); // mosGetParam($_REQUEST, 'listing_status', '');
      $drive_type = protectInjectionWithoutQuote('drive_type', ''); // mosGetParam($_REQUEST, 'drive_type', '');
      $cylinder = protectInjectionWithoutQuote('cylinder', ''); // mosGetParam($_REQUEST, 'cylinder', '');
      $num_speed = protectInjectionWithoutQuote('num_speed', ''); // mosGetParam($_REQUEST, 'num_speed', '');
      $doors = protectInjectionWithoutQuote('doors', ''); // mosGetParam($_REQUEST, 'doors', '');
      $extra6 = protectInjectionWithoutQuote('extra6', ''); // mosGetParam($_REQUEST, 'extra6', '');
      $extra7= protectInjectionWithoutQuote('extra7', ''); // mosGetParam($_REQUEST, 'extra7', '');
      $extra8 = protectInjectionWithoutQuote('extra8', ''); // mosGetParam($_REQUEST, 'extra8', '');
      $extra9 = protectInjectionWithoutQuote('extra9', ''); // mosGetParam($_REQUEST, 'extra9', '');
      $extra10 = protectInjectionWithoutQuote('extra10', ''); // mosGetParam($_REQUEST, 'extra10', '');

      if ($vtype != _VEHICLE_MANAGER_LABEL_ALL && $vtype != '')
      {
          $where[] = " LOWER(b.vtype)='$vtype'";
      }
      $Maker = " ";
      if ($maker != _VEHICLE_MANAGER_LABEL_ALL && $maker != '')
      {
          $where[] = " LOWER(b.maker)='$maker'";
      }
      if ($transmission != _VEHICLE_MANAGER_LABEL_ALL && $transmission != '')
      {
          $where[] = " LOWER(b.transmission) LIKE '%$transmission%'";
      }
      if ($vcondition != _VEHICLE_MANAGER_LABEL_ALL && $vcondition != '')
      {
          $where[] = " LOWER(b.vcondition) LIKE '%$vcondition%'";
      }
      if ($fuel_type != _VEHICLE_MANAGER_LABEL_ALL && $fuel_type != '')
      {
          $where[] = " LOWER(b.fuel_type) LIKE '%$fuel_type%'";
      }
      if ($listing_type != _VEHICLE_MANAGER_LABEL_ALL && $listing_type != '')
      {
          $where[] = " LOWER(b.listing_type) LIKE '$listing_type'";
      }
      if ($model != strtolower(_VEHICLE_MANAGER_LABEL_ALL) && $model !== "")
      {
          $where[] = " LOWER(b.vmodel) LIKE '$model'";
      }
      if ($price_type != _VEHICLE_MANAGER_LABEL_ALL && $price_type != '')
      {
          $where[] = " LOWER(b.price_type) LIKE '$price_type'";
      }
      if ($listing_status != _VEHICLE_MANAGER_LABEL_ALL && $listing_status != '')
      {
          $where[] = " LOWER(b.listing_status) LIKE '$listing_status'";
      }
      if ($drive_type != _VEHICLE_MANAGER_LABEL_ALL && $drive_type != '')
      {
          $where[] = " LOWER(b.drive_type) LIKE '$drive_type'";
      }
      if ($cylinder != _VEHICLE_MANAGER_LABEL_ALL && $cylinder != '')
      {
          $where[] = " LOWER(b.cylinder) LIKE '$cylinder'";
      }
      if ($num_speed != _VEHICLE_MANAGER_LABEL_ALL && $num_speed != '')
      {
          $where[] = " LOWER(b.num_speed) LIKE '$num_speed'";
      }
      if ($doors != _VEHICLE_MANAGER_LABEL_ALL && $doors != '')
      {
          $where[] = " LOWER(b.doors) LIKE '$doors'";
      }
      if ($extra6 != _VEHICLE_MANAGER_LABEL_ALL && $extra6 != '')
      {
          $where[] = " LOWER(b.extra6)='$extra6'";
      }
      if ($extra7 != _VEHICLE_MANAGER_LABEL_ALL && $extra7 != '')
      {
          $where[] = " LOWER(b.extra7)='$extra7'";
      }
      if ($extra8 != _VEHICLE_MANAGER_LABEL_ALL && $extra8 != '')
      {
          $where[] = " LOWER(b.extra8)='$extra8'";
      }
      if ($extra9 != _VEHICLE_MANAGER_LABEL_ALL && $extra9 != '')
      {
          $where[] = " LOWER(b.extra9)='$extra9'";
      }
      if ($extra10 != _VEHICLE_MANAGER_LABEL_ALL && $extra10 != '')
      {
          $where[] = " LOWER(b.extra10)='$extra10'";
      }

      $pricefrom = intval(protectInjectionWithoutQuote('pricefrom', ''));
      $priceto = intval(protectInjectionWithoutQuote('priceto', ''));
      if ($pricefrom > 0)
          $where[] = " CAST( b.price AS SIGNED) >= $pricefrom ";
      if ($priceto > 0)
          $where[] = " CAST( b.price AS SIGNED) <= $priceto ";


      if (isset($_REQUEST['Ownername']) && $_REQUEST['Ownername'] == "on")
          $ownername = "$exactly";

      if ($ownername != '' && $ownername != '%%')
      {
          $query = "SELECT u.id
          \n FROM #__users AS u
          \n WHERE LOWER(u.id) LIKE '$ownername' OR LOWER(u.name) LIKE '$ownername';";
          $database->setQuery($query);
          if (version_compare(JVERSION, '3.0', 'lt'))
          {
              $owneremails = $database->loadResultArray();
          } else
          {
              $owneremails = $database->loadColumn();
          }
          $anonim = false;
          if($ownername == 'Anonymous')$anonim = true;
          $ownername = "";
          if (count($owneremails))
          {
          
              foreach ($owneremails as $owneremail) {
                  if (isset($_REQUEST['Ownername']) && $_REQUEST['Ownername'] == "on")
                  {
                      //search from frontend
                      if ($is_add_or)
                          $ownername .= " or ";
                      $is_add_or = true;
                      $ownername .= "b.owner_id='$owneremail'";
                  }
                  else
                  {
                      //show owner vehicles
                      $where[] = "b.owner_id='$owneremail'";
                  }
              }
          } else if($anonim){
            $where[] = "b.owner_id<1";
          }else if (!$is_add_or)
          {

              $doc = JFactory::getDocument();
              $doc->addStyleSheet($mosConfig_live_site . '/components/com_vehiclemanager/includes/vehiclemanager.css');
              echo "<h1>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . _VEHICLE_MANAGER_LABEL_SEARCH_NOTHING_FOUND . "</h1>";
              ?>
              <br />

              <div class="basictable_23">
              <?php mosHTML::BackButton($params, $hide_js); ?>
              </div>

              <?php
              return;
          }
      }

      $search_date_from = protectInjectionWithoutQuote('search_date_from', '');
      $search_date_from = addslashes($search_date_from);
      $search_date_until = protectInjectionWithoutQuote('search_date_until', '');
      $search_date_until = addslashes($search_date_until);

      if (isset($_REQUEST['search_date_from']) && ( trim($_REQUEST['search_date_from']) ) && trim($_REQUEST['search_date_until']) == "")
      {
          $RentSQL = "((fk_rentid = 0 OR b.id NOT IN (select dd.fk_vehicleid from #__vehiclemanager_rent AS dd
    WHERE dd.rent_until >= '" . $search_date_from . "' and dd.rent_from <= '" . $search_date_from . "' and dd.fk_vehicleid=b.id ) ) " .
                  " AND listing_type = '" . 1 . "' )  ";
          if ($is_add_or)
              $RentSQL .= " AND ";
          $RentSQL_JOIN_1 = "\nLEFT JOIN #__vehiclemanager_rent AS d ";
          $RentSQL_JOIN_2 = "\nON d.fk_vehicleid=b.id ";
      }
      if (isset($_REQUEST['search_date_until']) && (trim($_REQUEST['search_date_until']) ) && trim($_REQUEST['search_date_from']) == "")
      {
          $RentSQL = "((fk_rentid = 0 OR b.id NOT IN (select dd.fk_vehicleid from #__vehiclemanager_rent AS dd
    WHERE dd.rent_from <= '" . $search_date_until . "' and dd.rent_until >= '" . $search_date_until . "' and dd.fk_vehicleid=b.id ) ) " .
                  " AND listing_type = '" . 1 . "' )  ";
          if ($is_add_or)
              $RentSQL .= " AND ";

          $RentSQL_JOIN_1 = "\nLEFT JOIN #__vehiclemanager_rent AS d ";
          $RentSQL_JOIN_2 = "\nON d.fk_vehicleid=b.id ";
      }

      if (isset($_REQUEST['search_date_until']) && (trim($_REQUEST['search_date_until']) )
              && isset($_REQUEST['search_date_from']) && ( trim($_REQUEST['search_date_from']) ))
      {
          $RentSQL = "((fk_rentid = 0 OR b.id NOT IN (select dd.fk_vehicleid from #__vehiclemanager_rent AS dd 
    WHERE ( dd.rent_until >= '" . $search_date_from . "' and dd.rent_from <= '" . $search_date_from . "' )   or " .
                  " ( dd.rent_from < '" . $search_date_until . "' and dd.rent_until > '" . $search_date_until . "' ) or " .
                  " ( dd.rent_from > '" . $search_date_from . "' and dd.rent_until < '" . $search_date_until . "' ) ) ) " .
                  " AND listing_type = '" . 1 . "' )  ";
          if ($is_add_or)
              $RentSQL .= " AND ";

          $RentSQL_JOIN_1 = "\nLEFT JOIN #__vehiclemanager_rent AS d ";
          $RentSQL_JOIN_2 = "\nON d.fk_vehicleid=b.id ";
      }

      $RentSQL = $RentSQL . (($is_add_or) ? ( "( ( " . $Address . " " . $Title . " " . $Description . " " .
                      $Engine_type . " " . $Exterior_colors . " " . $Exterior_extras . " " . $Dashboard_option . " " .
                      $Interior_extras . " " . $Interior_color . " " . $Wheeltype . " " . $Warranty_options . " " .
                      $Safety_options . " " . $Extra1 . " " . $Extra2 . " " . $Extra3 . " " . $Extra4 . " " . $Extra5 . " " . $Maker . " " . $Model . " " . $vehicleid . " " . $Country . " " . $Region . " " .
                      $City . " " . $District . " " . $Zipcode . " " . $Mileage . " " . $Contacts . " " . $City_fuel_mpg . " " . $Highway_fuel_mpg . " " .
                      $Wheelbase . " " . $Rear_axe_type . " " . $Brakes_type . " " . $ownername . "  ))") : (" "));
      //print_r();exit;
      if (trim($RentSQL) != "")
          array_push($where, $RentSQL);

      if (isset($price))
          array_push($where, $price);
      if (isset($year))
          array_push($where, $year);
      if ($catid)
          array_push($where, "c.id=$catid");

      //select category, to which user has access
      $where[] = " ($s) ";
      $where[] = " c.published = '1' ";
      //select published and approved vehicles
      $where[] = " b.published = '1' ";
      $where[] = " b.approved = '1' ";

             
      if (isset($langContent))
      {
          $lang = $langContent;
          $query = "SELECT lang_code FROM #__languages WHERE sef = '$lang'";
          $database->setQuery($query);
          $lang = $database->loadResult();
          $lang = " and (b.language like 'all' or b.language like '' or b.language like '*' or b.language is null or b.language like '$lang') 
                   AND (c.language like 'all' or c.language like '' or c.language like '*' or c.language is null or c.language like '$lang') ";
      } else
      {
          $lang = "";
      }

      if (!isset($RentSQL_JOIN_1))
          $RentSQL_JOIN_1 = '';

      $query = "SELECT COUNT(DISTINCT b.id)
          FROM #__vehiclemanager_vehicles AS b
          LEFT JOIN #__vehiclemanager_categories AS vc ON b.id=vc.iditem
          LEFT JOIN #__vehiclemanager_main_categories AS c ON vc.idcat = c.id " .
              $RentSQL_JOIN_1 . $RentSQL_JOIN_2 .
              ((count($where) ? "\n WHERE " . implode(' AND ', $where) : "")) . $lang ;
      $database->setQuery($query);
      $total = $database->loadResult();
      $pageNav = new JPagination($total, $limitstart, $limit);

      $query = "SELECT distinct  vc.idcat as idcat, b . * ,  c.title AS category_titel, c.ordering AS category_ordering,
          c.id as catid
          FROM #__vehiclemanager_vehicles AS b
          LEFT JOIN #__vehiclemanager_categories AS vc ON b.id=vc.iditem
          LEFT JOIN #__vehiclemanager_main_categories AS c ON vc.idcat = c.id " .
              $RentSQL_JOIN_1 . $RentSQL_JOIN_2 .
              ((count($where) ? "\n WHERE " . implode(' AND ', $where) : "")) . $lang .
              " GROUP BY b.id ORDER BY $sort_string
            \nLIMIT $pageNav->limitstart,$pageNav->limit;";
      
      
      
      $database->setQuery($query);
     // print_r($database);
      $vehicles = $database->loadObjectList();    

      $params->def('singleuser01', "{loadposition com_vehiclemanager_single_user_vehicle_01,xhtml}");
      $params->def('singleuser02', "{loadposition com_vehiclemanager_single_user_vehicle_02,xhtml}");
      $params->def('singleuser03', "{loadposition com_vehiclemanager_single_user_vehicle_03,xhtml}");
      $params->def('singleuser04', "{loadposition com_vehiclemanager_single_user_vehicle_04,xhtml}");
      $params->def('singleuser05', "{loadposition com_vehiclemanager_single_user_vehicle_05,xhtml}");
      
      $layout = $vehiclemanager_configuration['view_type'];
      if(empty($layout))$layout = 'default';
      
      if (count($vehicles))
      {
if($option != 'com_simplemembership'){
          if ( (isset($_REQUEST['userId']) && $my->id == $_REQUEST['userId'] ) || $task == 'my_vehicles' || $task == 'show_my_cars'   ) PHP_vehiclemanager::showTabs();
}  
          HTML_vehiclemanager::displayVehicles($vehicles, $currentcat, $params, $tabclass, $catid, null, false, $pageNav, $layout);
      } else
      {
if($option != 'com_simplemembership'){
          if ( (isset($_REQUEST['userId']) && $my->id == $_REQUEST['userId'] ) || $task == 'my_vehicles' || $task == 'show_my_cars'   ) PHP_vehiclemanager::showTabs();
}
          $doc = JFactory::getDocument();
          $doc->addStyleSheet($mosConfig_live_site . '/components/com_vehiclemanager/includes/vehiclemanager.css');
          echo "<h1>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . _VEHICLE_MANAGER_LABEL_SEARCH_NOTHING_FOUND . "</h1>";
          ?>
          <br />
          <div class="basictable_24">
          <?php mosHTML::BackButton($params, $hide_js); ?>
          </div>
          <?php
      }
  }


  static function guid()
  
  {
      if (function_exists('com_create_guid'))
      {
      
	return trim(com_create_guid(), '{}');
} else
      {
          mt_srand((double) microtime() * 10000); //optional for php 4.2.0 and up.
          $charid = strtoupper(md5(uniqid(rand(), true)));
          $hyphen = chr(45); // "-"
          $uuid = //chr(123)// "{"
                  substr($charid, 0, 8) . $hyphen
                  . substr($charid, 8, 4) . $hyphen
                  . substr($charid, 12, 4) . $hyphen
                  . substr($charid, 16, 4) . $hyphen
                  . substr($charid, 20, 12);
          //.chr(125);// "}"
          return $uuid;
      }
  }


  static function ajax_rent_calcualete($vid,$rent_from,$rent_until){ 
    global $vehiclemanager_configuration;
    $database = JFactory::getDBO();    
    $resulArr = calculatePriceVM($vid,$rent_from,$rent_until,$vehiclemanager_configuration,$database);
    echo $resulArr[0].' '.$resulArr[1];
    exit; 
 }
  
 
  static function secretImage()
 
 
  {
      $session = JFactory::getSession();
      $pas = $session->get('captcha_keystring', 'default');
      $new_img = new PWImageVehicle();
      $new_img->set_show_string($pas);
      $new_img->get_show_image(2.2, array(mt_rand(0, 50), mt_rand(0, 50), mt_rand(0, 50)), array(mt_rand(200, 255),
          mt_rand(200, 255), mt_rand(200, 255)));
      exit();
   
  
  }

  /*
   * Must not be used in 1.6!
   */

  static function com_veh_categoryArray()
  {
      global $database, $my, $acl;

      // get a list of the menu items
      $query = "SELECT c.*, c.parent_id AS parent, c.params AS access"
              . "\n FROM #__vehiclemanager_main_categories c"
              . "\n WHERE section='com_vehiclemanager'"
              . "\n AND published <> -2"
              . "\n ORDER BY ordering";
      $database->setQuery($query);
      $items = $database->loadObjectList();
      foreach ($items as $r => $cat_item) {
          if (!checkAccess_VM($cat_item->access, 'RECURSE', userGID_VM($my->id), $acl))
          {//if have not access then remove book from search
              unset($items[$r]);
          }
      }
      $items = array_values($items);
      // establish the hierarchy of the menu
      $children = array();
      // first pass - collect children
      foreach ($items as $v) {
          $pt = $v->parent;
          $list = @$children[$pt] ? $children[$pt] : array();
          array_push($list, $v);
          $children[$pt] = $list;
      }
      // second pass - get an indent list of the items
      $array = mosTreeRecurse(0, '', array(), $children);

      return $array;
  }

  static function showTabs()
  {  
      global $mosConfig_live_site, $vehiclemanager_configuration, $database, $Itemid, $my, $option;
      $acl = JFactory::getACL();
      $doc = JFactory::getDocument();
      $doc->addStyleSheet($mosConfig_live_site . '/components/com_vehiclemanager/includes/vehiclemanager.css');

      if (version_compare(JVERSION, '3.0', 'ge'))
      {
          $menu = new JTableMenu($database);
          $menu->load($Itemid);
          $params = new JRegistry;
          $params->loadString($menu->params);
      } else
      {
          $menu = new mosMenu($database);
          $menu->load($Itemid);
          $params = new mosParameters($menu->params);
      }

      if ($option == "com_comprofiler") {
        return;
      }

      $userid = $my->id;
      $query = "SELECT u.id, u.name AS username FROM #__users AS u WHERE u.id = " . $userid;
      $database->setQuery($query);
      $ownerslist = $database->loadObjectList();
      foreach ($ownerslist as $owner) {
          $username = $owner->username;
      }

      $query = "SELECT v.owner_id as owner_id FROM #__vehiclemanager_vehicles AS v" .
              " INNER JOIN #__vehiclemanager_rent_request AS r ON v.id=r.fk_vehicleid " .
              " WHERE v.owner_id = '" . $my->id . "' AND r.status=0";
      $database->setQuery($query);
      $ownervehicle = $database->loadObjectList();
      foreach ($ownervehicle as $vowner) {
          $vehicleowner = $vowner->owner_id;
          break;
      }

      $query = "SELECT v.owneremail as owneremail FROM #__vehiclemanager_vehicles AS v" .
              " INNER JOIN  #__vehiclemanager_buying_request AS br ON v.id=br.fk_vehicleid" .
              " WHERE v.owneremail = '" . $my->email . "'";
      $database->setQuery($query);
      $ownerbuyvehicle = $database->loadObjectList();
      foreach ($ownerbuyvehicle as $buyowner) {
          $buyvehicleowner = $buyowner->owneremail;
          break;
      }

      $query = "SELECT * FROM #__vehiclemanager_rent AS r WHERE r.fk_userid = " . $my->id;
      $database->setQuery($query);
      $current_user_rent_history_array = $database->loadObjectList();
      $check_for_show_rent_history = 0;
      if (isset($current_user_rent_history_array)){
        foreach ($current_user_rent_history_array as $temp) {
          if ($temp->fk_userid == $my->id)
            $check_for_show_rent_history = 1;
        }
      }

      if (($vehiclemanager_configuration['cb_myvehicle']['show'])){
        $params->def('show_cb', 1);
        $i = checkAccess_VM($vehiclemanager_configuration['cb_myvehicle']['registrationlevel'], 'NORECURSE', userGID_VM($my->id), $acl);
        if ($i)
          $params->def('show_cb_registrationlevel', 1);
      }

      if (($vehiclemanager_configuration['cb_edit']['show']))
      {
          $params->def('show_edit', 1);
          $i = checkAccess_VM($vehiclemanager_configuration['cb_edit']['registrationlevel'], 'NORECURSE', userGID_VM($my->id), $acl);
          if ($i)
              $params->def('show_edit_registrationlevel', 1);
      }

      if (isset($vehicleowner) && $my->id == $vehicleowner){
        if (($vehiclemanager_configuration['cb_rent']['show'])){
          $params->def('show_rent', 1);
          $i = checkAccess_VM($vehiclemanager_configuration['cb_rent']['registrationlevel'], 'NORECURSE', userGID_VM($my->id), $acl);
          if ($i)
            $params->def('show_rent_registrationlevel', 1);
        }
      }

      if (isset($vehicleowner) && $my->id == $vehicleowner){
        if (($vehiclemanager_configuration['cb_buy']['show'])){
          $params->def('show_buy', 1);
          $i = checkAccess_VM($vehiclemanager_configuration['cb_buy']['registrationlevel'], 'NORECURSE', userGID_VM($my->id), $acl);
          if ($i)
            $params->def('show_buy_registrationlevel', 1);
        }
      }

      if ($check_for_show_rent_history != 0)
      {
          if (($vehiclemanager_configuration['cb_history']['show']))
          {
              $params->def('show_history', 1);
              $i = checkAccess_VM($vehiclemanager_configuration['cb_history']['registrationlevel'], 'NORECURSE', userGID_VM($my->id), $acl);
              if ($i)
                  $params->def('show_history_registrationlevel', 1);
          }
      }

      HTML_vehiclemanager::showTabs($params, $userid, $username, $comprofiler, $option);
  }



  static function ShowAllVechicle($layout = "default", $printItem)
  {
      global $mainframe, $database, $acl, $my, $langContent;
      global $mosConfig_shownoauth, $mosConfig_live_site, $mosConfig_absolute_path;
      global $cur_template, $Itemid, $vehiclemanager_configuration, $mosConfig_list_limit, $limit, $total, $limitstart;

      if (isset($langContent))
      { 
          $lang = $langContent;
          $query = "SELECT lang_code FROM #__languages WHERE sef = '$lang'";
          $database->setQuery($query);
          $lang = $database->loadResult();
          $lang = " and (c.language like 'all' or c.language like '' or c.language like '*' or c.language is null or c.language like '$lang')
	     AND  (v.language like 'all' or v.language like '' or v.language like '*' or v.language is null or v.language like '$lang')";
      } else
      {
          $lang = "";
      }

      //sorting
      $item_session =  JFactory::getSession();
      $sort_arr = $item_session->get('vm_vehiclesort', '');
      if (is_array($sort_arr))
      {
          $tmp1 = protectInjectionWithoutQuote('order_direction'); // mosGetParam($_POST, 'order_direction');
          if ($tmp1 != '')
              $sort_arr['order_direction'] = $tmp1;
          $tmp1 = protectInjectionWithoutQuote('order_field'); // mosGetParam($_POST, 'order_field');
          if ($tmp1 != '')
              $sort_arr['order_field'] = $tmp1;
          $item_session->set('vm_vehiclesort', $sort_arr);
      } else
      {
          $sort_arr = array();
          $sort_arr['order_field'] = 'vtitle';
          $sort_arr['order_direction'] = 'asc';
          $item_session->set('vm_vehiclesort', $sort_arr);
      }
      if ($sort_arr['order_field'] == "price")
          $sort_string = "CAST( " . $sort_arr['order_field'] . " AS SIGNED)" . " " . $sort_arr['order_direction'];
      else
          $sort_string = $sort_arr['order_field'] . " " . $sort_arr['order_direction'];

      //getting groups of user
      $s = vmLittleThings::getWhereUsergroupsCondition();

      $query = "SELECT COUNT(DISTINCT v.id)
          \nFROM #__vehiclemanager_vehicles AS v"
              . "\nLEFT JOIN #__vehiclemanager_categories AS vc ON vc.iditem=v.id"
              . "\nLEFT JOIN #__vehiclemanager_main_categories AS c ON c.id=vc.idcat"
              . "\nWHERE v.published='1' AND v.approved='1' AND c.published='1' $lang
            AND ($s)";

      $database->setQuery($query);
      $total = $database->loadResult();

      $pageNav = new JPagination($total, $limitstart, $limit);

      // getting all vehicles for this category
      $query = "SELECT v.*,vc.idcat AS catid,vc.idcat AS idcat, c.title as category_titel
     \nFROM #__vehiclemanager_vehicles AS v "
              . "\nLEFT JOIN #__vehiclemanager_categories AS vc ON vc.iditem=v.id "
              . "\nLEFT JOIN #__vehiclemanager_main_categories AS c ON c.id=vc.idcat "
              . "\nWHERE v.published='1' AND v.approved='1' "
              . "\nAND c.published='1' $lang AND ($s) "
              . "\nGROUP BY v.id "
              . "\nORDER BY " . $sort_string
              . "\nLIMIT $pageNav->limitstart,$pageNav->limit;";
      $database->setQuery($query);

      $vehicles = $database->loadObjectList();

      $query = "SELECT v.*,c.id, c.parent_id, c.title, c.published, c.image,COUNT(vc.iditem) as vehicles, '1' as display" .
              " \n FROM  #__vehiclemanager_main_categories as c
            \n LEFT JOIN #__vehiclemanager_categories AS vc ON vc.idcat=c.id
            \n LEFT JOIN #__vehiclemanager_vehicles AS v ON v.id=vc.iditem
            \n WHERE c.section='com_vehiclemanager' 
            AND c.published=1 AND v.approved='1' AND ({$s}) $lang
            \n GROUP BY c.id
            \n ORDER BY c.parent_id DESC, c.ordering ";

      $database->setQuery($query);
      $cat_all = $database->loadObjectList();

      foreach ($cat_all as $k1 => $cat_item1) {
          if (is_exist_curr_and_subcategory_vehicles($cat_all[$k1]->id))
          {
              foreach ($cat_all as $cat_item2) {
                  if ($cat_item1->id == $cat_item2->parent_id)
                  {
                      $cat_all[$k1]->vehicles += $cat_item2->vehicles;
                  }
              }
          } else
              $cat_all[$k1]->display = 0;
      }

      if (version_compare(JVERSION, '3.0', 'ge'))
      {
          $menu = new JTableMenu($database);
          $menu->load($Itemid);
          $params = new JRegistry;
          $params->loadString($menu->params);
      } else
      {
          $menu = new mosMenu($database);
          $menu->load($Itemid);
          $params = new mosParameters($menu->params);
      }

      $menu_name = set_header_name_vm($menu, $Itemid);
      
      // price
      if (($vehiclemanager_configuration['price']['show']))
      {
          $params->def('show_pricestatus', 1);
          if (checkAccess_VM($vehiclemanager_configuration['price']['registrationlevel'], 'RECURSE', userGID_VM($my->id), $acl))
          {
              $params->def('show_pricerequest', 1); //+18.01
          }
      }

      $params->def('header', $menu_name);
      $params->def('pageclass_sfx', '');
//     $params->def('category_name', $category->title);
      $params->def('show_search', '1');
      if (($vehiclemanager_configuration['rentstatus']['show']))
      {
          if (checkAccess_VM($vehiclemanager_configuration['rentrequest']['registrationlevel'], 'RECURSE', userGID_VM($my->id), $acl))
          {
              $params->def('show_rentstatus', 1);
              $params->def('show_rentrequest', 1);
          }
      }

      if (($GLOBALS['Reviews_vehicle_show']))
      {
          $params->def('show_reviews_vehicle', 1);
          if (checkAccess_VM($GLOBALS['Reviews_vehicle_registrationlevel'], 'RECURSE', userGID_VM($my->id), $acl))
          {
              $params->def('show_reviews_registrationlevel', 1);
          }
      }

      $params->def('sort_arr_order_direction', $sort_arr['order_direction']);
      $params->def('sort_arr_order_field', $sort_arr['order_field']);

      //add for show in category picture
      if (($GLOBALS['cat_pic_show']))
          $params->def('show_cat_pic', 1);

      $params->def('show_rating', 1);
      $params->def('hits', 1);
      $params->def('back_button', $mainframe->getCfg('back_button'));

      // used to show table rows in alternating colours
      $tabclass = array('sectiontableentry1', 'sectiontableentry2');

      //view type
      $params->def('view_type', $vehiclemanager_configuration['view_type']);
      $params->def('minifotohigh', $vehiclemanager_configuration['foto']['high']);
      $params->def('minifotowidth', $vehiclemanager_configuration['foto']['width']);

      $params->def('singlecategory01', "{loadposition com_vehiclemanager_all_vehicle_01,xhtml}");
      $params->def('singlecategory02', "{loadposition com_vehiclemanager_all_vehicle_02,xhtml}");
      $params->def('singlecategory03', "{loadposition com_vehiclemanager_all_vehicle_03,xhtml}");
      $params->def('singlecategory04', "{loadposition com_vehiclemanager_all_vehicle_04,xhtml}");
      $params->def('singlecategory05', "{loadposition com_vehiclemanager_all_vehicle_05,xhtml}");
      $params->def('singlecategory06', "{loadposition com_vehiclemanager_all_vehicle_06,xhtml}");
      $params->def('singlecategory07', "{loadposition com_vehiclemanager_all_vehicle_07,xhtml}");
      $params->def('singlecategory08', "{loadposition com_vehiclemanager_all_vehicle_08,xhtml}");
      $params->def('singlecategory09', "{loadposition com_vehiclemanager_all_vehicle_09,xhtml}");
      $params->def('singlecategory10', "{loadposition com_vehiclemanager_all_vehicle_10,xhtml}");
      $params->def('singlecategory11', "{loadposition com_vehiclemanager_all_vehicle_11,xhtml}");

      HTML_vehiclemanager::displayAllVehicles($vehicles, $params, $tabclass, $pageNav, $layout);
  }

}
