<?php

/**
 *
 * @package VehicleManager
 * @copyright 2013 Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru);Rob de Cleen(rob@decleen.com)
 * Homepage: http://www.ordasoft.com
 * @version: 3.7 Pro
 *
 * */
defined('_JEXEC') or die;
$mosConfig_absolute_path = $GLOBALS['mosConfig_absolute_path'] = JPATH_SITE;
require_once($mosConfig_absolute_path . "/components/com_vehiclemanager/compat.joomla1.5.php");

function VehicleManagerBuildRoute(&$query)
{ 
 // print_r("VehicleManagerBuildRoute start") ;
 // print_r($query) ;

    $segments = array();
    $db = JFactory::getDBO();

    $JSite = new JSite();
    if (isset($query['Itemid'])  && $query['Itemid'] !== 0  )
    {   
        $menu = $JSite->getMenu();
        $component = isset($menu->getItem($query['Itemid'])->component) ? $menu->getItem($query['Itemid'])->component : '';
        if (!isset($query['view']) && ($component == 'com_vehiclemanager') && !isset($query['task']))
        {
            
            if (version_compare(JVERSION, "1.6.0", "lt"))
            { 
                $query['view'] = $menu->getItem($query['Itemid'])->query['task'];
            } else if (version_compare(JVERSION, "1.6.0", "ge"))
            {   

                $query['view'] = $menu->getItem($query['Itemid'])->query['view'];
            }
        }
        if( isset($query['view']) && $query['view'] == "alone_category" ) {
          if (version_compare(JVERSION, '3.0', 'ge'))
          {
              $menu = new JTableMenu($db);
              $menu->load($query['Itemid']);
              $params = new JRegistry;
              $params->loadString($menu->params);
          } else
          {
              $menu = new mosMenu($db);
              $menu->load($query['Itemid']);
              $params = new mosParameters($menu->params);
          }    
          $single_category = $params->get('single_category', '');
          if($single_category != "" ) $query['catid'] = $single_category;
        } 
    }
    if (isset($query['option']) && $query['option'] == 'com_vehiclemanager')
    { //check component
         if (isset($query['view']) && ($query['view'] == 'all_categories' 
        || $query['view'] == 'my_vehicles'
        || $query['view'] == 'owner_vehicles'
        || $query['view'] == 'show_search_vehicle'
        || $query['view'] == 'view_vehicle'
        || $query['view'] == 'owners_list')) {

        }else{
        $segments[0] = (isset($query['Itemid'])) && ($query['Itemid'] != 0) ? $query['Itemid'] : '0';
            if ((isset($query['view'])) && (!isset($query['task'])))
            {
                $query['task'] = $query['view'];
            }
        }

        if (isset($query['task']))
        {
            switch ($query['task']) {
                case 'all_vehicle':
                    $segments[1] = 'all_vehicle';
                break;
                case 'my_vehicles':
                    $segments[1] = 'my_vehicles';
                break;
                case 'new_url':
                    $segments[1] = 'buy_now';
                    break;
                case 'new_url_for_vm':
                    $segments[1] = 'buy_now_for_vm';
                    break;
                case 'view_user_vehicles':
                case 'owner_vehicles':
                    $segments[1] = $query['task'];
                    if (isset($query['name']))
                    {                       
                        //$segments[] = JFilterOutput::stringURLSafe($query['name']);
                        $segments[] = $query['name'];
                        unset($query['name']);                                        
                    }
                    break;
                case 'rent_requests_vehicle':
                    $segments[1] = $query['task'];
                    break;
                case 'buying_requests_vehicle':
                    $segments[1] = $query['task'];
                    break;
                case 'rent_history_vehicle':
                    $segments[1] = $query['task'];
                    break;
                default:
                    $segments[1] = $query['task'];
                    break;
            }
        } else if ( !isset($query['task']) && !isset($query['view']) ){

            $segments[1] = "all_vehicle";
        }

        unset($query['task']);
        unset($query['view']);

        if (isset($query['catid']))
        {
            if ($query['catid'] > 0)
            {
                $sql_query = "SELECT vmc.id, vmc.name, vc.idcat, vmc.parent_id " .
                        " FROM #__vehiclemanager_main_categories AS vmc" .
                        " LEFT JOIN #__vehiclemanager_categories AS vc ON vc.idcat=vmc.id " .
                        " LEFT JOIN #__vehiclemanager_vehicles AS v ON vc.iditem = v.id " .
                        " WHERE vmc.section = 'com_vehiclemanager' AND vmc.id = " . intval($query['catid']);
                $db->setQuery($sql_query);
                $row = null;
                $row = $db->loadObject();
                if (isset($row))
                {
                    $cattitle = array();
                    $segments[] = $query['catid'];
                    $segments[] = JFilterOutput::stringURLSafe($row->name);
                    unset($query['catid']);
                }
            } else
            {
                $temp = $query['catid']; //is catid set??
                unset($query['catid']);
            }
        }

        if (!empty($query['lang']))
        {
            unset($query['lang']);
        }
        // if (isset($query['Itemid']))
        // {
        //     unset($query['Itemid']);
        // }
        // if (!empty($query['Itemid']))
        // {
        //     $query['Itemid'] = "";
        //     unset($query['Itemid']);
        // }

        if (isset($query['name']))
        {
            $segments[] = JFilterOutput::stringURLSafe($query['name']);
            unset($query['name']);
        }

        if (isset($query['user']))
        {
            $segments[] = $query['user'];
            unset($query['user']);
        }



        if (isset($query['id']))
        {
            $sql_query = "SELECT vc.idcat AS catid, v.vtitle"
                    . "\n FROM #__vehiclemanager_vehicles AS v"
                    . "\n LEFT JOIN #__vehiclemanager_categories AS vc ON vc.iditem=v.id"
                    . "\n LEFT JOIN #__vehiclemanager_main_categories AS vmc ON vmc.id=vc.idcat"
                    . "\n WHERE v.id = " . intval($query['id']);
            $db->setQuery($sql_query);
            $row = null;
            $row = $db->loadObject();
            if (isset($row))
            {
                $temp_title = JFilterOutput::stringURLSafe($row->vtitle);
                if (isset($temp))
                {
                    $sql_query = "SELECT name FROM #__vehiclemanager_main_categories WHERE id = " . $row->catid;
                    $db->setQuery($sql_query);
                    $row = $db->loadObject();
                    if (isset($row))
                    {
                        $segments[] = $row->name;
                    }
                }
                $segments[] = $query['id']; //for back up in parseroute
                $segments[] = $temp_title;
                unset($query['id']);
            }
        }

        // print_r(':11111111:');
        // print_r($segments);

        // pagenator
        if (isset($query['start']))
        {
            $segments[] = $query['start'];
            if (isset($query['limitstart']))
            {
                $segments[] = $query['limitstart'];
                unset($query['limitstart']);
            } 
            // else
            // {
            //     $segments[] = $query['start'];
            // }
            unset($query['start']);
        }
        elseif(isset($query['limitstart']))
        {
            $segments[] = $query['limitstart'];
            unset($query['limitstart']);
        }
        // print_r(':22222222:');
        // print_r($segments);
        // print_r(':2222222:');
        // print_r($segments['limitstart']);

        
        if (isset($query['viewtype']))
        {
            $segments[] = 'viewtype' . ":" . $query['viewtype'];
            unset($query['viewtype']);
        }

        if (isset($query['searchtext']))
        {
            $segments[] = $query['searchtext'];
            unset($query['searchtext']);
        }

        if (isset($query['searchtype']))
        {
            $segments[] = $query['searchtype'];
            unset($query['searchtype']);
        }
    }
    //unset($query);
//  print_r("VehicleManagerBuildRoute end") ;
//  print_r($segments) ;
    

    return $segments;
}

/**
 * Parse the segments of a URL.
 * */
//print_r($segments); exit;
function VehicleManagerParseRoute($segments)
{
  // print_r("VehicleManagerParseRoute start") ;
  // print_r($segments) ;
  
    $db = JFactory::getDBO();
    $vars = array();
    
    $count = count($segments);
    $vars['option'] = 'com_vehiclemanager';

    $JSite = new JSite();
    $menu = $JSite->getMenu();
    if( !is_numeric($segments[0] ) ){  
        array_unshift($segments, "0" );
    }

    $menu->setActive($segments[0]);
    $vars['Itemid'] = $segments[0];

    if ((@$segments[1] == "alone_category" || @$segments[1] == "showCategory") && isset($segments[2]))
    {
        $vars['task'] = "alone_category";
        $vars['catid'] = @$segments[2];
        /*
          $sql_query = "SELECT id FROM #__vehiclemanager_main_categories WHERE name='".$segments[2]."'";
          $db->setQuery($sql_query);
          $row = null;
          $row = $db->loadObject();
          $vars['catid'] = $row->id;
         * */

        if (isset($segments[4]))
        {
            $viewtype = explode(':', $segments[3]);
            if ($viewtype[0] == "viewtype")
            {
                $vars['viewtype'] = (int) $viewtype[1];
            }
        }

        if (isset($segments[3]) && !isset($vars['viewtype']))
        {
            $vars['start'] = $segments[3];
        } else
        {
            unset($vars['start']);
        }

        if (isset($segments[4]) && !isset($vars['viewtype']))
        {
            $vars['limitstart'] = $segments[4];
        } else
        {
            unset($vars['limistart']);
        }

        if (isset($segments[5]))
        {
            $viewtype = explode(':', $segments[5]);
            if ($viewtype[0] == "viewtype")
            {
                $vars['viewtype'] = (int) $viewtype[1];
            }
        }
    } elseif (@$segments[1] == "all_categories"){
        
        $vars['task'] = 'all_categories';
        $vars['lang'] = 'en';
        
    } 
    elseif (@$segments[1] == "all_vehicle") {
        $vars['task'] = @$segments[1];
          if(isset($segments[2]))   $vars['limitstart'] = $segments[2];
         }
    elseif (@$segments[1] == "my_vehicles") {
        $vars['task'] = @$segments[1];
          if(isset($segments[2]))   $vars['limitstart'] = $segments[2];
         }
    elseif (@$segments[1] == "view_vehicle" && isset($segments[4]))
    {
        $vars['task'] = "view_vehicle";
        $vars['id'] = (int) $segments[4];
      /*  $vars['name'] = $segments[3];    Comment 13.05.13

        $sql_query = "SELECT id FROM #__vehiclemanager_main_categories WHERE name='" . $segments[3] . "'";
        $db->setQuery($sql_query);
        $row = null;
        $row = $db->loadObject(); 
        $vars['catid'] = $row->id;*/
        $vars['catid'] =  (int) $segments[2];
    } elseif (@$segments[1] == "Search")
    {
        $vars['task'] = "search";
        if (isset($segments[4]))
        {
            $vars['searchtext'] = $segments[4];
        }
         
        if (isset($segments[5]))
        {
            $vars['searchtype'] = $segments[5];
        }
        if (isset($segments[2]))
        {
            $vars['start'] = $segments[2];
        } else
        {
            unset($vars['start']);
        }
        if (isset($segments[3]))
        {
            $vars['limitstart'] = $segments[3];
        } else
        {
            unset($vars['limistart']);
        }
    } elseif (@$segments[1] == "show_search_vehicle")
    {
        $vars['task'] = "show_search_vehicle";
        if (isset($segments[4]))
        {
            $vars['searchtext'] = $segments[4];
        }
        if (isset($segments[5]))
        {
            $vars['searchtype'] = $segments[5];
        }
        if (isset($segments[2]))
        {
            $vars['start'] = $segments[2];
        } else
        {
            unset($vars['start']);
        }
        if (isset($segments[3]))
        {
            $vars['limitstart'] = $segments[3];
        } else
        {
            unset($vars['limistart']);
        }
    } elseif (@$segments[1] == "search" || @$segments[1] == "search_vehicle")
    {
        $vars['task'] = @$segments[1];
        if (isset($segments[4]))
        {
            $vars['searchtext'] = $segments[4];
        }
        if (isset($segments[5]))
        {
            $vars['searchtype'] = $segments[5];
        }
        if (isset($segments[2]))
        {
            $vars['start'] = $segments[2];
        } else
        {
            unset($vars['start']);
        }
        if (isset($segments[3]))
        {
            $vars['limitstart'] = $segments[3];
        } else
        {
            unset($vars['limistart']);
        }
    } elseif (@$segments[1] == "show_rss_categories")
    {
        $vars['task'] = "show_rss_categories";
    } elseif (@$segments[1] == "buy_now")
    {
        $vars['task'] = "new_url";
        if (isset($segments[2]))
        {
            $vars['id'] = $segments[2];
        }
    } elseif (@$segments[1] == "buy_now_for_vm")
    {
        $vars['task'] = "new_url_for_vm";
        if (isset($segments[2]))
        {
            $vars['id'] = $segments[2];
        }
    } elseif (@$segments[1] == "view_user_vehicles")
    {
        $vars['task'] = "view_user_vehicles";
        $vars['limit'] = isset($segments[3]) ? $segments[3] : null;
        $vars['limitstart'] = isset($segments[4]) ? $segments[4] : null;
        if (isset($segments[2]))
        {
            $vars['name'] = $segments[2];
        }
    } elseif (@$segments[1] == "owner_vehicles")
    {
        $vars['task'] = "owner_vehicles";
        if (isset($segments[2]))
        {
            $vars['name'] = $segments[2];
        }
    } 
    elseif(@$segments[1] == "rent_requests_vehicle"){
        $vars['task'] = "rent_requests_vehicle";
        if (isset($segments[2])) {
            $vars['start'] = $segments[2];
        } else {
            unset($vars['start']);
        }
        if (isset($segments[3])) {
            $vars['limitstart'] = $segments[3];
        } else {
            unset($vars['limistart']);
        }
    }elseif(@$segments[1] == "buying_requests_vehicle"){
        $vars['task'] = "buying_requests_vehicle";
        if (isset($segments[2])) {
            $vars['start'] = $segments[2];
        } else {
            unset($vars['start']);
        }
        if (isset($segments[3])) {
            $vars['limitstart'] = $segments[3];
        } else {
            unset($vars['limistart']);
        }
    }elseif(@$segments[1] == "rent_history_vehicle"){
        $vars['task'] = "rent_history_vehicle";
        if (isset($segments[2])) {
            $vars['start'] = $segments[2];
        } else {
            unset($vars['start']);
        }
        if (isset($segments[3])) {
            $vars['limitstart'] = $segments[3];
        } else {
            unset($vars['limistart']);
        }
    }
    ///////////
    elseif (@$segments[1] == "show_my_cars")
    {
        $vars['task'] = "show_my_cars";
        $vars['limit'] = isset($segments[3]) ? $segments[3] : null;
        $vars['limitstart'] = isset($segments[4]) ? $segments[4] : null;
        if (isset($segments[2]))
        {
            $vars['name'] = str_replace(":"," ", $segments[2]);
        }
    }
    ////////////////////    
    
    elseif (@$segments[1] == "lend_history")
    {
        $vars['task'] = "lend_history";
        $vars['name'] = $segments[2];
        $vars['user'] = $segments[3];
    } elseif (@$segments[1] == "edit_my_cars"  )
    {
        $vars['task'] = "edit_my_cars";
        if (isset($segments[2]))
        {
            $vars['start'] = $segments[2];
        } else
        {
            unset($vars['start']);
        }
        if (isset($segments[3]))
        {
            $vars['limitstart'] = $segments[3];
        } else
        {
            unset($vars['limistart']);
        }
    } elseif (@$segments[1] == "lend_requests")
    {
        $vars['task'] = "lend_requests";
        if (isset($segments[2]))
        {
            $vars['start'] = $segments[2];
        } else
        {
            unset($vars['start']);
        }
        if (isset($segments[3]))
        {
            $vars['limitstart'] = $segments[3];
        } else
        {
            unset($vars['limistart']);
        }
    } elseif (@$segments[1] == "mdownload")
    {
        $vars['task'] = "mdownload";
        if (isset($segments[2]))
        {
            $vars['id'] = $segments[2];
        }
    } elseif (@$segments[1] == "edit_vehicle")
    {
            $vars['task'] = "edit_vehicle";
            if (isset($segments[2]))
            $vars['id'] = $segments[2];
            $vars['Itemid'] = $segments[0];
        
    } else
    {
        $vars['task'] = @$segments[1];
    }
    //print_r(":1111111111111:".$limitstart);
    
  // print_r("VehicleManagerParseRoute end") ;
  // print_r($vars) ;
    
   return $vars;
}
