<?php

/**
 * @copyright 2013 OrdaSoft
 * @author 2013 Andrey Kvasnevsky
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @package Search
* @description Search plugin for VehicleManager Component
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

if (!defined("DS")) define("DS", DIRECTORY_SEPARATOR);

if (version_compare(JVERSION, "1.6.0", "lt"))
{

 function plgSearchVehicleAreas()
{ 
	static $areas = array(
		'vehicle' => 'Vehicle'
	);
	return $areas;
}

if( !function_exists( 'getUserGroups')) { 
  function getUserGroups(){
    $my=&JFactory::getUser();
    $acl =&JFactory::getACL();
    $usergroups = $acl->get_group_parents($my->gid,'ARO','RECURSE');
    if($usergroups) $usergroups=','.implode(',',$usergroups); else $usergroups='';
    return '-2,'.$my->gid.$usergroups;
  }
}
							 
function onContentSearch_vehicle( $text ,$phrase='', $ordering='', $areas=null)
{ 
	if (is_array($areas)) {
		if (!array_intersect( $areas, array_keys(plgSearchVehicleAreas() ) )) 	return array();
		}
     if(!function_exists('sefRelToAbs')){
            function sefRelToAbs( $value ){
                //Need check!!!
                //Replace all &amp; with & as the router doesn't understand &amp;
                $url = str_replace('&amp;', '&', $value);
                if(substr(strtolower($url),0,9) != "index.php") return $url;
                $uri = JURI::getInstance();
                $prefix = $uri->toString(array('scheme', 'host', 'port'));
                return $prefix.JRoute::_($url);
            }
	}
   

        $database = &JFactory::getDBO();
        $database->setQuery("SELECT id FROM #__menu WHERE link LIKE'%option=com_vehiclemanager%' AND params LIKE '%back_button%' ");
        $ItemId_tmp_from_db = $database->loadResult();
        $ItemId=$ItemId_tmp_from_db;   


   $order = '';
   switch($ordering)
   {
	case 'newest':
	$order = 'ORDER BY v.id  DESC';
	break;
	case 'oldest':
	$order = 'ORDER BY v.id';
	break;
	case 'popular':
	$order = 'ORDER BY v.hits';
	break;
	case 'alpha':
	$order = 'ORDER BY v.vtitle';
	break;
	case 'category':
	$order = 'ORDER BY category';
	break;
	}
	
   $text =  preg_replace ('/\s\s+/','%',trim( $text ));
   if ($text == '') { return array(); }

   switch($phrase)
   { 
         case 'exact':
         $text = preg_replace ('/\s\s+/',' ',trim( $text ));
       $where = "  (v.vtitle LIKE  '%$text%'"
      ." OR (v.vlocation LIKE  '%$text%')"
      ." OR (v.listing_type  LIKE '%$text%')"
      ." OR (v.vmodel LIKE  '%$text%')"
      ." OR (v.maker LIKE  '%$text%')"
      ." OR (v.vtype LIKE  '%$text%')"
      ." OR (v.interior_color  LIKE '%$text%')"
      ." OR (v.exterior_color  LIKE '%$text%')"
      ." OR (v.engine  LIKE '%$text%')"
      ." OR (v.fuel_type  LIKE '%$text%')"
      ." OR (v.drive_type  LIKE '%$text%')"
      ." OR (v.cylinder  LIKE '%$text%')"
      ." OR (v.wheeltype  LIKE '%$text%')"
      ." OR (v.rear_axe_type  LIKE '%$text%')"
      ." OR (v.exterior_amenities  LIKE '%$text%')"
      ." OR (v.dashboard_options  LIKE '%$text%')"
      ." OR (v.interior_amenities  LIKE '%$text%')"
      ." OR (v.safety_options  LIKE '%$text%')"
      ." OR (v.interior_amenities  LIKE '%$text%')"
      ." OR (v.description  LIKE '%$text%')) "; print_r("   exact   ");
          break;
		case 'all':
		case 'any':
		default:
		$text =  preg_replace ('/\s\s+/',' ',trim( $text ));
		$words = explode( ' ', $text );
		$wheres = array();
		foreach ($words as $word) {
		$word = $database->Quote( '%'.$database->escape( $word, true ).'%', false );
		$wheres2 = array();
		$wheres2[] = " v.vlocation LIKE $word ";
                $wheres2[] = " v.listing_type LIKE $word ";
                $wheres2[] = " v.vmodel LIKE $word";
                $wheres2[] = " v.maker LIKE $word";
                $wheres2[] = " v.vtype LIKE $word";
                $wheres2[] = " v.interior_color LIKE $word";
                $wheres2[] = " v.exterior_color LIKE $word";
                $wheres2[] = " v.engine LIKE $word";
                $wheres2[] = " v.fuel_type LIKE $word";
                $wheres2[] = " v.drive_type LIKE $word";
                $wheres2[] = " v.cylinder LIKE $word";
                $wheres2[] = " v.wheeltype LIKE $word";
                $wheres2[] = " v.rear_axe_type LIKE $word";
                $wheres2[] = " v.exterior_amenities LIKE $word";
                $wheres2[] = " v.dashboard_options LIKE $word";
                $wheres2[] = " v.interior_amenities LIKE $word";
                $wheres2[] = " v.safety_options LIKE $word";
                $wheres2[] = " v.interior_amenities LIKE $word";
                $wheres2[] = " v.description LIKE $word";
				
		$wheres[] 	= implode( ' OR ', $wheres2 );
			}
			$where = '(' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres ) . ')';
			break;
   }
   $usergroups=getUserGroups();
   $query =  "SELECT v.vtitle AS title,"
      ." v.date AS created,"
      ." v.description  AS text ,"
      ." CONCAT( 'index.php?option=com_vehiclemanager"
      ."&task=view_vehicle&id=', v.id,'&catid=', c.id,'&Itemid=', $ItemId) AS href,"
	    ." '2' AS browsernav,"            
	    ." 'VehicleManager' AS section,"
	    ." c.title AS category"
	    ." FROM #__vehiclemanager_vehicles AS v "
      ." LEFT JOIN #__vehiclemanager_categories AS vc ON v.id=vc.iditem "
	    ." LEFT JOIN #__vehiclemanager_main_categories AS c ON c.id=vc.idcat "
      ." WHERE v.approved='1'AND v.published = '1' AND (c.params IN ($usergroups) OR c.params='')"
      ." AND  $where "
      ." GROUP BY v.id"
      ." $order";
	  
   $database->setQuery( $query );
   return $database->loadObjectList();
     }
   
 }
if (version_compare(JVERSION, "1.6.0", "lt"))
{ 
 
$mainframe->registerEvent( 'onSearch','onContentSearch_vehicle' );                                                          
$mainframe->registerEvent( 'onSearchAreas', 'plgSearchVehicleAreas' ); 
     }
 
else
{
//===========================================JooMla 2.5.3===========================================


jimport('joomla.plugin.plugin');
jimport('joomla.html.parameter');

require_once ( JPATH_SITE .DS.'components'.DS.'com_vehiclemanager'.DS.'functions.php' );
 
 
if(version_compare(JVERSION,"3.0.0","lt")){
class plgSearchVehiclemanagerSearch extends JPlugin {
   var $ItemId;
public function __construct(& $subject, $config)
	{
 parent::__construct($subject, $config);
        $this->loadLanguage();
        $params = new JParameter($config['params']);
        $this->ItemId=$params->get('ItemId',0);

	}
  
  function onContentSearchAreas() { 
		static $areas = array(
			'vehicleman' => 'Vehicle Manager'
			);
			return $areas;
	}
  
  public function onSearchAreas () { // We get here when input box [Vehicle Manager] was enabled
  
      static $areas = array(
        'vehicleman' => 'VehicleManager'
      );
      return $areas;
  }

 
					 
public function onContentSearch( $text ,$phrase='', $ordering='', $areas=null)
{ 
	if (is_array( $areas )) {
		if (!array_intersect( $areas, array_keys( $this->onSearchAreas() ) )) {
			return array();
		}
	} // ??
  
if( !function_exists( 'sefreltoabs')) {
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

  
   $database = &JFactory::getDBO();

   $database->setQuery("SELECT id  FROM #__menu WHERE   link LIKE'%option=com_vehiclemanager%' AND params LIKE '%back_button%'  ");
   $ItemId = $database->loadResult();    
     
   $order = '';
   switch($ordering)
   {
	case 'newest':
	$order = 'ORDER BY v.id  DESC';
	break;
	case 'oldest':
	$order = 'ORDER BY v.id';
	break;
	case 'popular':
	$order = 'ORDER BY v.hits';
	break;
	case 'alpha':
	$order = 'ORDER BY v.vtitle';
	break;
	case 'category':
	$order = 'ORDER BY category';
	break;
	}
	
   $text =  preg_replace ('/\s\s+/','%',trim( $text ));
   if ($text == '') { return array(); }

   switch($phrase)
   {
      case 'exact':
      $text = preg_replace ('/\s\s+/',' ',trim( $text ));
      $where = 
      "  (v.vtitle LIKE  '%$text%'"
      ." OR (v.vlocation LIKE  '%$text%')"
      ." OR (v.listing_type  LIKE '%$text%')"
      ." OR (v.vmodel LIKE  '%$text%')"
      ." OR (v.maker LIKE  '%$text%')"
      ." OR (v.vtype LIKE  '%$text%')"
      ." OR (v.interior_color  LIKE '%$text%')"
      ." OR (v.exterior_color  LIKE '%$text%')"
      ." OR (v.engine  LIKE '%$text%')"
      ." OR (v.fuel_type  LIKE '%$text%')"
      ." OR (v.drive_type  LIKE '%$text%')"
      ." OR (v.cylinder  LIKE '%$text%')"
      ." OR (v.wheeltype  LIKE '%$text%')"
      ." OR (v.rear_axe_type  LIKE '%$text%')"
      ." OR (v.exterior_amenities  LIKE '%$text%')"
      ." OR (v.dashboard_options  LIKE '%$text%')"
      ." OR (v.interior_amenities  LIKE '%$text%')"
      ." OR (v.safety_options  LIKE '%$text%')"
      ." OR (v.interior_amenities  LIKE '%$text%')"
      ." OR (v.description  LIKE '%$text%')) ";
			break;
		case 'all':
		case 'any':
		default:
          $text =  preg_replace ('/\s\s+/',' ',trim( $text ));

          $words = explode( ' ', $text );
         
            $wheres = array();
            foreach ($words as $word) {
              $word = $database->Quote( '%'.$database->escape( $word, true ).'%', false );
            $wheres2 	= array();
            $wheres2[] 	= " v.vtitle LIKE $word ";  
            $wheres2[] 	= " v.vlocation LIKE $word ";
            $wheres2[] 	= " v.listing_type LIKE $word ";
            $wheres2[] 	= " v.vmodel LIKE $word";
            $wheres2[] 	= " v.maker LIKE $word";
            $wheres2[] 	= " v.vtype LIKE $word";
            $wheres2[] 	= " v.interior_color LIKE $word";
            $wheres2[] 	= " v.exterior_color LIKE $word";
            $wheres2[] 	= " v.engine LIKE $word";
            $wheres2[] 	= " v.fuel_type LIKE $word";
            $wheres2[] 	= " v.drive_type LIKE $word";
            $wheres2[] 	= " v.cylinder LIKE $word";
            $wheres2[] 	= " v.wheeltype LIKE $word";
            $wheres2[] 	= " v.rear_axe_type LIKE $word";
            $wheres2[] 	= " v.exterior_amenities LIKE $word";
            $wheres2[] 	= " v.dashboard_options LIKE $word";
            $wheres2[] 	= " v.interior_amenities LIKE $word";
            $wheres2[] 	= " v.safety_options LIKE $word";
            $wheres2[] 	= " v.price LIKE $word";
            $wheres2[] 	= " v.priceunit LIKE $word";
            $wheres2[] 	= " v.description LIKE $word";
            $wheres2[] 	= " v.contacts LIKE $word";
            $wheres2[] 	= " v.year LIKE $word";
             $wheres2[] 	= " v.vcondition LIKE $word";
              $wheres2[] 	= " v.mileage LIKE $word";
               $wheres2[] 	= " v.transmission LIKE $word";
                $wheres2[] 	= " v.fuel_type LIKE $word";
                 $wheres2[] 	= " v.wheelbase LIKE $word";
                  $wheres2[] 	= " v.brakes_type LIKE $word";
                   $wheres2[] 	= " v.owneremail LIKE $word";
                    $wheres2[] 	= " v.city LIKE $word";
                     $wheres2[] 	= " v.country LIKE $word"; 
                    
              
              $wheres[] = implode( ' OR ', $wheres2 );
            }
            $where = '(' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres ) . ')';
            break;
   }
   $s = vmLittleThings::getWhereUsergroupsCondition ();

   $query =  "SELECT v.vtitle AS title,"
      ." v.date AS created,"
      ." v.description  AS text ,"
      ." CONCAT( 'index.php?option=com_vehiclemanager"
      ."&task=view_vehicle&id=', v.id,'&catid=', c.id,'&Itemid=', $ItemId) AS href,"
	    ." '2' AS browsernav,"            
	    ." 'VehicleManager' AS section,"
	    ." c.title AS category"
	    ." FROM #__vehiclemanager_vehicles AS v "
            ." LEFT JOIN #__vehiclemanager_categories AS vc ON v.id=vc.iditem "
            ." LEFT JOIN #__vehiclemanager_main_categories AS c ON c.id=vc.idcat "
            ." WHERE v.approved='1' AND v.published = '1' AND ($s)"
            ." AND  $where "
            ." GROUP BY v.id"
            ." $order";
	  
    
   $database->setQuery( $query );
   return $database->loadObjectList();
    }
  }
}
else {
    class plgSearchVehiclemanagerSearch_j3 extends JPlugin {
        var $ItemId;
        public function __construct(& $subject, $config)
        {
            parent::__construct($subject, $config);
            $this->loadLanguage();

            if (version_compare(JVERSION,"3.0.0","lt")) {
                $params = new JParameter($config['params']);
            } else {
                $params = new JRegistry;
                $params->loadString($config['params']);
               // $params = new JParameter($config['params']);
            }
            $this->ItemId=$params->get('ItemId',0);

        }

        function onContentSearchAreas() {
            static $areas = array(
                'vehicleman' => 'Vehicle Manager'
            );
            return $areas;
        }

        public function onSearchAreas () { // We get here when input box [Vehicle Manager] was enabled

            static $areas = array(
                'vehicleman' => 'VehicleManager'
            );
            return $areas;
        }
        
        public function onContentSearch( $text ,$phrase='', $ordering='', $areas=null)
        {

            if (is_array( $areas )) {
                if (!array_intersect( $areas, array_keys( $this->onSearchAreas() ) )) {
                    return array();
                }
            } // ??

            if( !function_exists( 'sefreltoabs')) {
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
            $limit = $this->params->get('search_limit',10);

            $database = JFactory::getDBO();

            $database->setQuery("SELECT id  FROM #__menu WHERE   link LIKE'%option=com_vehiclemanager%' AND params LIKE '%back_button%'  ");
            $ItemId = $database->loadResult();

            $order = '';
            switch($ordering)
            {
                case 'newest':
                    $order = 'ORDER BY v.id  DESC';
                    break;
                case 'oldest':
                    $order = 'ORDER BY v.id';
                    break;
                case 'popular':
                    $order = 'ORDER BY v.hits';
                    break;
                case 'alpha':
                    $order = 'ORDER BY v.vtitle';
                    break;
                case 'category':
                    $order = 'ORDER BY category';
                    break;
            }
            $text =  preg_replace ('/\s\s+/','%',trim( $text ));
            if ($text == '') { return array(); }

            switch($phrase)
            {
                case 'exact':
                    $text = preg_replace ('/\s\s+/',' ',trim( $text ));
                    $where =
                        "  (v.vtitle LIKE  '%$text%'"
                            ." OR (v.vlocation LIKE  '%$text%')"
                            ." OR (v.listing_type  LIKE '%$text%')"
                            ." OR (v.vmodel LIKE  '%$text%')"
                            ." OR (v.maker LIKE  '%$text%')"
                            ." OR (v.vtype LIKE  '%$text%')"
                            ." OR (v.interior_color  LIKE '%$text%')"
                            ." OR (v.exterior_color  LIKE '%$text%')"
                            ." OR (v.engine  LIKE '%$text%')"
                            ." OR (v.fuel_type  LIKE '%$text%')"
                            ." OR (v.drive_type  LIKE '%$text%')"
                            ." OR (v.cylinder  LIKE '%$text%')"
                            ." OR (v.wheeltype  LIKE '%$text%')"
                            ." OR (v.rear_axe_type  LIKE '%$text%')"
                            ." OR (v.exterior_amenities  LIKE '%$text%')"
                            ." OR (v.dashboard_options  LIKE '%$text%')"
                            ." OR (v.interior_amenities  LIKE '%$text%')"
                            ." OR (v.safety_options  LIKE '%$text%')"
                            ." OR (v.interior_amenities  LIKE '%$text%')"
                            ." OR (v.description  LIKE '%$text%')) ";
                break;
                case 'all':
                case 'any':
                default:
                    $text =  preg_replace ('/\s\s+/',' ',trim( $text ));
                   $words = explode(' ', $text );
                    $wheres = array();
                    foreach ($words as $word) {
                        $word = $database->Quote('%'.$database->escape($word, true).'%', false);
                        $wheres2 	= array();
                        $wheres2[] 	= " v.vtitle LIKE $word ";
                        $wheres2[] 	= " v.vlocation LIKE $word ";
                        $wheres2[] 	= " v.listing_type LIKE $word ";
                        $wheres2[] 	= " v.vmodel LIKE $word";
                        $wheres2[] 	= " v.maker LIKE $word";
                        $wheres2[] 	= " v.vtype LIKE $word";
                        $wheres2[] 	= " v.interior_color LIKE $word";
                        $wheres2[] 	= " v.exterior_color LIKE $word";
                        $wheres2[] 	= " v.engine LIKE $word";
                        $wheres2[] 	= " v.fuel_type LIKE $word";
                        $wheres2[] 	= " v.drive_type LIKE $word";
                        $wheres2[] 	= " v.cylinder LIKE $word";
                        $wheres2[] 	= " v.wheeltype LIKE $word";
                        $wheres2[] 	= " v.rear_axe_type LIKE $word";
                        $wheres2[] 	= " v.exterior_amenities LIKE $word";
                        $wheres2[] 	= " v.dashboard_options LIKE $word";
                        $wheres2[] 	= " v.interior_amenities LIKE $word";
                        $wheres2[] 	= " v.safety_options LIKE $word";
                        $wheres2[] 	= " v.price LIKE $word";
                        $wheres2[] 	= " v.priceunit LIKE $word";
                        $wheres2[] 	= " v.description LIKE $word";
                        $wheres2[] 	= " v.contacts LIKE $word";
                        $wheres2[] 	= " v.year LIKE $word";
                        $wheres2[] 	= " v.vcondition LIKE $word";
                        $wheres2[] 	= " v.mileage LIKE $word";
                        $wheres2[] 	= " v.transmission LIKE $word";
                        $wheres2[] 	= " v.fuel_type LIKE $word";
                        $wheres2[] 	= " v.wheelbase LIKE $word";
                        $wheres2[] 	= " v.brakes_type LIKE $word";
                        $wheres2[] 	= " v.owneremail LIKE $word";
                        $wheres2[] 	= " v.city LIKE $word";
                        $wheres2[] 	= " v.country LIKE $word";
                        $wheres[] 	= implode( ' OR ', $wheres2 );
                    }
                    $where = '(' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres ) . ')';
                    break;
            }

            $s = vmLittleThings::getWhereUsergroupsCondition ();
            if (isset($ItemId) && $ItemId != "")
            $ItemId = ", ".$ItemId; else $ItemId="";
            $query =  "SELECT v.vtitle AS title,"
                ." v.date AS created,"
                ." v.description  AS text ,"
                ." CONCAT( 'index.php?option=com_vehiclemanager"
                ."&task=view_vehicle&id=', v.id,'&catid=', c.id,'&Itemid=' $ItemId) AS href,"
                ." '2' AS browsernav,"
                ." 'VehicleManager' AS section,"
                ." c.title AS category"
                ." FROM #__vehiclemanager_vehicles AS v "
                ." LEFT JOIN #__vehiclemanager_categories AS vc ON v.id=vc.iditem "
                ." LEFT JOIN #__vehiclemanager_main_categories AS c ON c.id=vc.idcat "
                ." WHERE v.approved='1' AND v.published = '1' AND ($s)"
                ." AND  $where "
                ." GROUP BY v.id"
                ." $order"
                ." LIMIT 0,$limit";
                
            $database->setQuery( $query );
            return $database->loadObjectList();
        }
    }
}
}
?>