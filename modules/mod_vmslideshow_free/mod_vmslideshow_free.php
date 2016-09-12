<?php

/*
* @version 3.0 FREE
* @package VehicleManager - vehicle slideShow
* @copyright 2013 OrdaSoft
* @author 2013 Andrey Kvasnekskiy (akbet@ordasoft.com )
* @description VehicleManager - vehicle slideShow for Vehicle Manager Component
*/


// no direct access
defined('_JEXEC') or die('Restricted access');
$path = JPATH_BASE.'/components/com_vehiclemanager/functions.php';
if (!file_exists($path)){
  echo "To display the featured books You have to install VehicleManager first<br />"; exit;
} else{
  require_once($path);
}
// Include the syndicate functions only once
require_once (dirname(__FILE__).'/helper.php');
$app = JFactory::getApplication();

$database = JFactory::getDBO() ;
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
       
        if ($locale == $language->title || $locale == $language->lang_code || $locale == $language->sef)
        {
            $mosConfig_lang = $locale;
            $languagelocale = $language->lang_code;
            
            break;
        }
    }
}

if ($languagelocale == ''){
    $mosConfig_lang = $lang->getTag();
    $languagelocale = $lang->getTag();
}

if ($languagelocale == '')
    $languagelocale = "en-GB";

// Set content language
global $langContent;
if(isset($_REQUEST['lang'])) 
    {$langContent = $_REQUEST['lang'];
}else{
    $langContent = substr($languagelocale, 0, 2);
}

// taking the slides from the source
$slides = modVMSlideShowHelper::getImagesFromVMSlideShow($params, $langContent);
if($slides==null) {
    $app = JFactory::getApplication();
    $app->enqueueMessage(JText::_('MOD_VMSLIDESHOW_NO_CATEGORY_OR_ITEMS'),'notice');
    return;
}
if (version_compare(JVERSION,"3.0.0","lt")) {
    JHTML::_('behavior.mootools'); }
else {
    JHtml::_('behavior.framework', true);
}

if($params->get('link_image',1)==2) {JHTML::_('behavior.modal');JHTML::_('mootools-uncompressed.modal');}
$document = JFactory::getDocument();
$document->addScript(JURI::base(true) . '/modules/mod_vmslideshow_free/assets/slider.js');   

if(!is_numeric($width = $params->get('image_width'))) $width = 240;
if(!is_numeric($height = $params->get('image_height'))) $height = 180;
if(!is_numeric($max = $params->get('count_vehicle'))) $max = 20;
if(!is_numeric($count = $params->get('visible_images'))) $count = 3;
if(!is_numeric($spacing = $params->get('space_between_images'))) $spacing = 3;
$moduleclass_sfx = $params->get('space_between_images') ;
if($count>count($slides)) $count = count($slides);
if($count<1) $count = 1;
if($count>$max) $count = $max;
$mid = $module->id;
$slider_type = $params->get('slider_type',0);
switch($slider_type){
    case 2:
        $slide_size = $width;
        $count = 1;
        break;
    case 1:
        $slide_size = $height + $spacing;
        break;
    case 0:
    default:
        $slide_size = $width + $spacing;
        break;
}

$animationOptions = modVMSlideShowHelper::getAnimationOptions($params);
$showB = $params->get('show_buttons',1);
$showA = $params->get('show_arrows',1);
if(!is_numeric($preload = $params->get('preload'))) $preload = 800;
$moduleSettings = "{id: '$mid', slider_type: $slider_type, slide_size: $slide_size, visible_slides: $count, show_buttons: $showB, show_arrows: $showA, preload: $preload}";
$js = "window.addEvent('domready',function(){var Slider$mid = new VMSlideShow($moduleSettings,$animationOptions)});";
$js = "(function($){ ".$js." })(document.id);";
$document->addScriptDeclaration($js);
$css = JURI::base().'modules/mod_vmslideshow_free/assets/style.css';
$document->addStyleSheet($css);

$css = modVMSlideShowHelper::getStyleSheet($params,$mid);
$document->addStyleDeclaration($css);

$navigation = modVMSlideShowHelper::getNavigation($params,$mid);

require(JModuleHelper::getLayoutPath('mod_vmslideshow_free', $params->get('layout', 'default'))); 
?>
<div style="text-align: center;"><a href="http://ordasoft.com" style="font-size: 10px;">Powered by OrdaSoft!</a></div>