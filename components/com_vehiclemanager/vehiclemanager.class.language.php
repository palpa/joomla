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
class mosVehicleManager_language extends JTable
{

    /** @var int - Primary key */
    var $id = null;

    /** @var int */
    var $fk_constid = null;

    /** @var int */
    var $fk_languagesid = null;

    /** @var varchar(150) */
    var $value_const = null;

    /** @var varchar(150) */
    var $sys_type = null;

    /**
     * @param database A database connector object
     */
    function mosVehicleManager_language($db)
    {
        parent::__construct("#__vehiclemanager_const_languages", 'id', $db);
    }

    function updateOrder($where = '')
    { // for 1.6
        return $this->reorder($where);
    }

}
