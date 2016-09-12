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
$mosConfig_absolute_path = $GLOBALS['mosConfig_absolute_path'] = JPATH_SITE;
if (version_compare(JVERSION, '3.0', 'lt'))
{
    require_once ($mosConfig_absolute_path . '/libraries/joomla/database/table.php' );
}

/**
 * vehiclemanager Main Categories table class
 */
class mainVehiclemanagerCategories extends JTable
{

    /** @var int Primary key */
    var $id = null;

    /** @var int */
    var $parent_id = null;
    /** @var int */

    /** @var int */
    //var $sid=null;
    /** @var string */
    var $asset_id = null;

    /** @var datetime */
    var $title = null;

    /** @var int */
    var $name = null;

    /** @var int */
    var $alias = null;

    /** @var int */
    var $image = null;

    /** @var boolean */
    var $section = null;

    /** @var time */
    var $image_position = null;

    /** @var int */
    var $description = null;

    /** @var varchar(200) */
    var $published = null;

    /** @var varchar(200) */
    var $checked_out = null;

    /** @var varchar(250) */
    var $checked_out_time = null;

    /** @var int */
    var $editor = null;

    /** @var varchar(200) */
    var $ordering = null;

    /** @var varchar(200) */
    var $access = null;

    /** @var varchar(300) */
    var $count = null;

    /** @var int */
    var $params = null;

    /** @var text */
    var $params2 = null;

    /** @var text */
    var $language = null;

    /** @var char(7) */
    function __construct(&$db)
    {
        $table_prefix = $db->getPrefix();
        parent::__construct($table_prefix . "vehiclemanager_main_categories", 'id', $db);
        $this->access = (int) JFactory::getConfig()->get('access');
    }

    function updateOrder($where = '')
    {
        return $this->reorder($where);
    }

    /**
     * Legacy Method, use {@link JTable::publish()} instead
     * @deprecated As of 1.0.3
     */
    function publish_array($cid = null, $publish = 1, $user_id = 0)
    {
        $this->publish($cid, $publish, $user_id);
    }

}

