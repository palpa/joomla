<?php
defined('_JEXEC') or die('Restricted access');
/**
 * @package  VehicleManager
 * @copyright 2012 Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); Rob de Cleen(rob@decleen.com)
 * Homepage: http://www.ordasoft.com
 * @version: 3.5 Free
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * */
if (version_compare(JVERSION, "1.6.0", "lt"))
{

    class JElementVehicle extends JElement
    {

        var $_name = 'vehicle';

        function fetchElement($name, $value, &$node, $control_name)
        {
            $db = JFactory::getDBO();
            $query = "SELECT id AS vehicle, vtitle AS title
                      FROM `#__vehiclemanager_vehicles`";
            $db->setQuery($query);
            $vehicle = $db->loadObjectList();
            return JHTML::_('select.genericlist', $vehicle, '' . $control_name . '[' . $name . ']', 'class="inputbox"', 'vehicle', 'title', $value, $control_name . $name);
        }

    }

} else if (version_compare(JVERSION, "1.6.0", "ge"))
{

    class JFormFieldVehicle extends JFormField
    {

        protected $type = 'vehicle';

        protected function getInput()
        {
            $db = JFactory::getDBO();
            // Initialize variables.
            $html = array();
            $attr = '';
            // Initialize some field attributes.
            $attr .= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
            // To avoid user's confusion, readonly="true" should imply disabled="true".
            if ((string) $this->element['readonly'] == 'true' || (string) $this->element['disabled'] == 'true')
            {
                $attr .= ' disabled="disabled"';
            }
            $attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
            $attr .= $this->multiple ? ' multiple="multiple"' : '';
            // Initialize JavaScript field attributes.
            $attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

            $query = 'SELECT id AS v_id, vtitle AS title
                      FROM #__vehiclemanager_vehicles'; // for 1.6
            $db->setQuery($query);
            $vehicles = $db->loadObjectList();


            $options = array();
            foreach ($vehicles as $item)
                $options[] = JHtml::_('select.option', $item->v_id, $item->title);

            // Create a read-only list (no name) with a hidden input to store the value.
            if ((string) $this->element['readonly'] == 'true')
            {
                $html[] = JHtml::_('select.genericlist', $options, '', trim($attr), 'value', 'text', $this->value, $this->id);
                $html[] = '<input type="hidden" name="' . $this->name . '" value="' . $this->value . '"/>';
            }
            // Create a regular list.
            else
            {
                $html[] = JHtml::_('select.genericlist', $options, $this->name, trim($attr), 'value', 'text', $this->value, $this->id);
            }

            return implode($html);
        }

    }

} else
{
    echo "Sanity test. Error version check!";
    exit;
}
