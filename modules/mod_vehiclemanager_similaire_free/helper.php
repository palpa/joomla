<?php

/**
 * version 3.0 Free
 * @package    andrey Kvasnevskiy - ordasoft.com
 * @copyright  Copyright (C) 2013 andrey Kvasnevskiy - ordasoft.com. All rights reserved
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

if (!function_exists('sefreltoabs')) {

    function sefRelToAbs($value) {
        // Replace all &amp; with & as the router doesn't understand &amp;
        $url = str_replace('&amp;', '&', $value);
        if (substr(strtolower($url), 0, 9) != "index.php")
            return $url;
        $uri = JURI::getInstance();
        $prefix = $uri->toString(array('scheme', 'host', 'port'));
        return $prefix . JRoute::_($url);
    }

}

class modSimilaireHelper {

    static function getList($params, $nbrAffiche,$langContent) {
        global $mainframe;

        $db = JFactory::getDBO();
        $user = JFactory::getUser();

        $option = JRequest::getCmd('option');
        if($option != "com_vehiclemanager") return;
        $func = JRequest::getCmd('func');
        $id = JRequest::getInt('id');
        $itemid = JRequest::getString('Itemid');

        $aid = $user->get('aid', 0);


        $conf = JFactory::getConfig();

        $related = modSimilaireHelper::getSimilaire($id, $nbrAffiche, $params,$langContent);

        return $related;
    }

    static function getSimilaire($id, $nbrAffiche, $params,$langContent) {
        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $date = JFactory::getDate();
        //return true;
        $related = array();

        $nullDate = $db->getNullDate();
        if (version_compare(JVERSION, "3.0.0", "lt"))
            $now = $date->toMySQL();
        else
            $now = $date->toSql();
        if (isset($langContent))
        {
            $lang = $langContent;
            $query = "SELECT lang_code FROM #__languages WHERE sef = '$lang'";
            $db->setQuery($query);
            $lang = $db->loadResult();
            $lang = " and (a.language like 'all' or a.language like '' or a.language like '*' or a.language is null or a.language like '$lang')
                     AND (c.language like 'all' or c.language like '' or c.language like '*' or c.language is null or c.language like '$lang') ";
        } else
        {
            $lang = "";
        }



        // select other items based on the metakey field 'like' the keys found
        $query = 'SELECT c.id AS cid, a.id, a.link, a.fuel_type, a.year, a.vtitle, a.maker, a.vmodel, a.mileage, a.image_link, a.price, a.catid' .
                ' FROM #__vehiclemanager_vehicles AS a, #__vehiclemanager_main_categories AS c , #__vehiclemanager_categories AS vc ' .
                ' WHERE a.id <> ' . (int) $id .$lang. " and c.section='com_vehiclemanager' " .
                " AND c.published='1' " .
                " AND vc.iditem=a.id " .
                " AND vc.idcat = c.id " .
                " AND a.published='1' " .
                " AND a.approved='1' ";

        if ($params->get('optMarque') != 0) {
            $query .= ' and a.maker =(select maker from #__vehiclemanager_vehicles t where t.id = ' . (int) $id . ')';
        }

        if ($params->get('optCategorie') != 0) {
            $query .= ' and vc.idcat in (select idcat from #__vehiclemanager_categories t2 where t2.iditem = ' . (int) $id . ')';
        }

        if ($params->get('optCarburant') != 0) {
            $query .= ' and a.fuel_type =(select fuel_type from #__vehiclemanager_vehicles t3 where t3.id = ' . (int) $id . ')';
        }
        $query .= " GROUP BY a.id ORDER BY a.date desc LIMIT {$nbrAffiche}";


        $db->setQuery($query);
        $temp = $db->loadObjectList();
        return $temp;
    }

}

?>
