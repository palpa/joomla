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
class mosVehicleManager_rent extends JTable
{

    /** @var int - Primary key */
    var $id = null;

    /** @var int - the Vehicle id this rent is assosiated with */
    var $fk_vehicleid = null;

    /** @var datetime - since when this vehicle is rent out */
    var $rent_from = null;

    /** @var datetime - when the vehicle should be returned */
    var $rent_until = null;

    /** @var datetime - when the vehicle realy was/is returned */
    var $rent_return = null;

    /** @var boolean */
    var $checked_out = null;

    /** @var time */
    var $checked_out_time = null;

    /** @var string - the user who lent this vehicle if it's no user of the database */
    var $user_name = null;

    /** @var string the e-mail adress user who lent this vehicle if it's no user of the database */
    var $user_email = null;

    /** @var string the post mail adress user who lent this vehicle if it's no user of the database, or some comment */
    var $user_mailing = null;

    /**
     * @param database A database connector object
     */
    function mosVehicleManager_rent(&$db)
    {
        parent::__construct("#__vehiclemanager_rent", 'id', $db);
    }

    // overloaded check function
    function check()
    {
        // check if vehicle is already lent out
        $this->_db->setQuery("SELECT id FROM #__vehiclemanager_rent WHERE fk_vehicleid='$this->fk_vehicleid' AND rent_return = null");
        $xid = intval($this->_db->loadResult());
        if ($xid)
        {
            $this->_error = _VEHICLE_MANAGER_VEHICLE_RENT_OUT;
            return false;
        }
        return true;
    }

    /**
     * @return array – name: the string of the user the vehicle is lent to - e-mail: the e-mail address of the user
     */
    function getRentTo($userid)
    {
        if ($userid != null && $userid != 0)
        {
            $this->_db->setQuery("SELECT name, email from #__users where id=$userid");
            $help = $this->_db->loadRow();
            $this->user_name = $help[0];
            $this->user_email = $help[1];
        } else
        {
            $this->user_name = _VEHICLE_MANAGER_LABEL_ANONYMOUS;
            $this->user_email = null;
        }
    }

    function toXML3($xmlDoc, $elementname = "rent")
    {
        //create and append name element 
        $retVal = $xmlDoc->createElement("rent");

        $rent_from = $xmlDoc->createElement("rent_from");
        $rent_from->appendChild($xmlDoc->createTextNode($this->rent_from));
        $retVal->appendChild($rent_from);

        $rent_until = $xmlDoc->createElement("rent_until");
        $rent_until->appendChild($xmlDoc->createTextNode($this->rent_until));
        $retVal->appendChild($rent_until);

        $rent_return = $xmlDoc->createElement("rent_return");
        $rent_return->appendChild($xmlDoc->createTextNode($this->rent_return));
        $retVal->appendChild($rent_return);

        $user_name = $xmlDoc->createElement("user_name");
        $user_name->appendChild($xmlDoc->createTextNode($this->user_name));
        $retVal->appendChild($user_name);

        $user_email = $xmlDoc->createElement("user_email");
        $user_email->appendChild($xmlDoc->createTextNode($this->user_email));
        $retVal->appendChild($user_email);

        $user_mailing = $xmlDoc->createElement("user_mailing");
        $user_mailing->appendChild($xmlDoc->createTextNode($this->user_mailing));
        $retVal->appendChild($user_mailing);

        return $retVal;
    }

    function toXML2()
    {
        $retVal = "<rent>\n";
        $retVal .= "<rent_from>" . $this->rent_from . "</rent_from>\n";
        $retVal .= "<rent_until>" . $this->rent_until . "</rent_until>\n";
        $retVal .= "<rent_return>" . $this->rent_return . "</rent_return>\n";
        $retVal .= "<user_name>" . $this->user_name . "</user_name>\n";
        $retVal .= "<user_email>" . $this->user_email . "</user_email>\n";
        $retVal .= "<user_mailing><![CDATA[" . $this->user_mailing . "]]></user_mailing>\n";
        $retVal .= "</rent>\n";
        return $retVal;
    }

}
