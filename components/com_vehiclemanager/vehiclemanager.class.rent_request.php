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
class mosVehicleManager_rent_request extends JTable
{

    /** @var int Primary key */
    var $id = null;

    /** @var int - the vehicle id this rent is assosiated with */
    var $fk_vehicleid = null;

    /** @var datetime - since when this vehicle is rent out */
    var $rent_from = null;

    /** @var datetime - when the vehicle should be returned */
    var $rent_until = null;

    /** @var datetime - when the vehicle was/is returned */
    var $rent_request = null;

    /** @var boolean */
    var $checked_out = null;

    /** @var time */
    var $checked_out_time = null;

    /** @var string - the user who rent this vehicle if it's no user of the database */
    var $user_name = null;

    /** @var string – the e-mail address of the user who rent this vehicle if it's no user of the database */
    var $user_email = null;

    /** @var string – the post mail address of the user who rent this vehicle if it's no user of the database, or some comment */
    var $user_mailing = null;

    /** @var int – staus */
    var $status = 0;
    var $fk_userid = null;

    /**
     * @param database - A database connector object
     */
    function mosVehicleManager_rent_request(&$db)
    {
        $table_prefix = $db->getPrefix();
        parent::__construct($table_prefix . "vehiclemanager_rent_request", 'id', $db);
    }

    // overloaded check function
    function check()
    {

        // check if vehicle is already rent out
        $this->_db->setQuery("SELECT fk_rentid FROM #__vehiclemanager_vehicles "
                . "\nWHERE id='$this->fk_vehicleid' "// AND fk_rentid = null"
        );
        $xid = intval($this->_db->loadResult());
        //if ($xid) {
        //	$this->_error = _VEHICLE_RENT_OUT;
        //	return false;
        //	}
        return true;
    }

    /**
     * @return array – name: the string of the user the vehicle is rent to - e-mail: the e-mail address of the user
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

    //status codes
    //0: just inserted
    //1: accepted
    //2: not accepted

    function accept()
    {
        global $database, $my, $vehiclemanager_configuration;
        if ($this->id == null)
            return "Method called on a non instant object";
        $this->checkout($my->id);
        if(getAssociateVehicle($this->fk_vehicleid)){
        $assoc_veh = getAssociateVehicle($this->fk_vehicleid);
        $assoc_veh = explode(',', $assoc_veh);
        }else{
            $assoc_veh = explode(',', $this->fk_vehicleid);
        }
        
        for($i = 0, $n = count($assoc_veh); $i < $n; $i++){
            $rent = new mosVehicleManager_rent($database);
            
        if(!empty($assoc_veh[$i])) {
            $query = "SELECT * FROM #__vehiclemanager_rent where fk_vehicleid = " . $assoc_veh[$i] . " AND rent_return is NULL ";
            $database->setQuery($query);
            $rentTerm = $database->loadObjectList();
            }
            
            $rent_from = substr($this->rent_from, 0, 10);
            $rent_until = substr($this->rent_until, 0, 10);

            foreach ($rentTerm as $oneTerm){

                $oneTerm->rent_from = substr($oneTerm->rent_from, 0, 10);
                $oneTerm->rent_until = substr($oneTerm->rent_until, 0, 10);
                $returnMessage = checkRentDayNightVM (($oneTerm->rent_from),($oneTerm->rent_until), $rent_from, $rent_until, $vehiclemanager_configuration);
                
                if($assoc_veh[$i] !== $oneTerm->id && strlen($returnMessage) > 0){                 
                    echo "<script> alert('$returnMessage'); window.history.go(-1); </script>\n";          
                    exit;
                }       
            }

            $rent->fk_vehicleid = $assoc_veh[$i];
            $rent->user_name = $this->user_name;
            $rent->user_email = $this->user_email;
            $rent->user_mailing = $this->user_mailing;
            $rent->rent_from = $this->rent_from;
            $rent->rent_until = $this->rent_until;
            $rent->fk_userid = $this->fk_userid;


            if (!$rent->check($rent))
            {
                return $rent->getError();
            }
            if (!$rent->store())
            {
                return $rent->getError();
            }

            $rent->checkin();
            $this->status = 1;

            if (!$this->store())
            {
                return $this->getError();
            }
            $this->checkin();
            $vehicle = new mosVehicleManager($database);
            $vehicle->load($rent->fk_vehicleid);
            $vehicle->fk_rentid = $rent->id;
            $vehicle->store();
            $vehicle->checkin();
        }    
        return null;
    }

    function decline()
    {
        if ($this->id == null)
        {
            return "Method called on a non instant object";
        }
        $this->status = 2;
        if (!$this->store())
        {
            return $this->getError();
        }
        return null;
    }

    function toXML3($xmlDoc)
    {

        //create and append name element 
        $retVal = $xmlDoc->createElement("rentrequest");

        $rent_from = $xmlDoc->createElement("rent_from");
        $rent_from->appendChild($xmlDoc->createTextNode($this->rent_from));
        $retVal->appendChild($rent_from);

        $rent_until = $xmlDoc->createElement("rent_until");
        $rent_until->appendChild($xmlDoc->createTextNode($this->rent_until));
        $retVal->appendChild($rent_until);

        $rent_request = $xmlDoc->createElement("rent_retquest");
        $rent_request->appendChild($xmlDoc->createTextNode($this->rent_request));
        $retVal->appendChild($rent_request);

        $user_name = $xmlDoc->createElement("user_name");
        $user_name->appendChild($xmlDoc->createTextNode($this->user_name));
        $retVal->appendChild($user_name);

        $user_email = $xmlDoc->createElement("user_email");
        $user_email->appendChild($xmlDoc->createTextNode($this->user_email));
        $retVal->appendChild($user_email);

        $user_mailing = $xmlDoc->createElement("user_mailing");
        $user_mailing->appendChild($xmlDoc->createTextNode($this->user_mailing));
        $retVal->appendChild($user_mailing);

        $status = $xmlDoc->createElement("status");
        $status->appendChild($xmlDoc->createTextNode($this->status));
        $retVal->appendChild($status);

        return $retVal;
    }

    function toXML2()
    {

        $retVal = "<rentrequest>\n";

        $retVal .= "<rent_from>" . $this->rent_from . "</rent_from>\n";
        $retVal .= "<rent_until>" . $this->rent_until . "</rent_until>\n";
        $retVal .= "<rent_request>" . $this->rent_request . "</rent_request>\n";
        $retVal .= "<user_name>" . $this->user_name . "</user_name>\n";
        $retVal .= "<user_email>" . $this->user_email . "</user_email>\n";
        $retVal .= "<user_mailing><![CDATA[" . $this->user_mailing . "]]></user_mailing>\n";
        $retVal .= "<status>" . $this->status . "</status>\n";

        $retVal .= "</rentrequest>\n";

        return $retVal;
    }

}
