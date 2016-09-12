<?php
/**
 * @package  VehicleManager
 * @copyright 2012 Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); Rob de Cleen(rob@decleen.com)
 * Homepage: http://www.ordasoft.com
 * @version: 3.5 Free
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * */
defined('_JEXEC') or die('Restricted access');
require_once ( JPATH_SITE.'/components/com_vehiclemanager/functions.php' );
if (version_compare(JVERSION, "1.6.0", "ge")){
  class JFormFieldShowSearchVehiclelayout extends JFormField{
    protected function getInput(){
      $all_categories_layout = getLayoutsVeh('com_vehiclemanager','show_search_vehicle');
      $layouts = Array();
      $layouts[] = JHtml::_('select.option', '', 'Use Global');
      foreach($all_categories_layout as $value){
          $layouts[] = JHtml::_('select.option', "$value", "$value"); 
      }
      return JHtml::_('select.genericlist', $layouts, $this->name, 'class="inputbox"', 'value', 'text', $this->value, $this->name);
    }
  }
} else
{
  echo "Sanity test. Error version check!";
  exit;
}
