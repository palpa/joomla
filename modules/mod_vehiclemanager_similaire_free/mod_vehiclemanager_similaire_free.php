<?php

/**
 * @version 3.0
 * @package    andrey Kvasnevskiy - ordasoft.com 
 * @copyright  Copyright (C) 2013 andrey Kvasnevskiy - ordasoft.com. All rights reserved
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
    
$f_path = JPATH_BASE .'/components/com_vehiclemanager/functions.php';
if (!file_exists($f_path)){
    echo "To display this module You have to install VehicleManager first<br />"; exit;
} else require_once ($f_path);

vmLittleThings::language_load_VM();
$langContent = $GLOBALS['langContent'] ;

global $vehiclemanager_configuration;
// Include the syndicate functions only once
require_once (dirname(__FILE__) . '/helper.php');

$nbrAffiche = $params->get('nbProd');

$h4 = $params->get('similaire_h4');

#$id = $params->get('id');

$prix = $params->get('similaire_prix');
$miniature = $params->get('miniature');
$listestyle = $params->get('listestyle');
$ItemId_tmp_from_params = $params->get('ItemId');
$image_source_type = $params->get('image_source_type');
$class_suffix = $params->get('moduleclass_sfx', '');

$list = modSimilaireHelper::getList($params, $nbrAffiche,$langContent);

$database = JFactory::getDBO();

$database->setQuery("SELECT id FROM #__menu WHERE link LIKE'%option=com_vehiclemanager%' AND params LIKE '%back_button%'");
$ItemId_tmp_from_db = $database->loadResult();



if ($ItemId_tmp_from_params == "") {
    $ItemId_tmp = $ItemId_tmp_from_db;
} else {
    $ItemId_tmp = $ItemId_tmp_from_params;
}

if (count($list) <= 0) {
    return;
}

$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::base(true) . '/' . 'components' . '/' . 'com_vehiclemanager' . '/' . 'includes' . '/' . 'vehiclemanager.css');

require(JModuleHelper::getLayoutPath('mod_vehiclemanager_similaire_free', $params->get('layout', 'default'))); 
?>
<div style="text-align: center;"><a href="http://ordasoft.com" style="font-size: 10px;">Powered by OrdaSoft!</a></div>