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
class mosVehicleManager_review extends JTable
{

    /** @var int Primary key */
    var $id = null;

    /** @var int - the vehicle id this lend is assosiated with */
    var $fk_vehicleid = null;

    /** @var the user of the user who reviewed; can also be null if not set */
    var $user_name = null;

    /** @var the user of the user who reviewed; can also be null if not set */
    var $user_email = null;

    /** @var datetime - date when adding this review */
    var $date = null;

    /** @var comment - the comment to this */
    var $comment = null;

    /** @var titel */
    var $title = null;

    /** @var rating */
    var $rating = 0;

    /** @var boolean */
    var $checked_out = null;

    /** @var time */
    var $checked_out_time = null;

    /** @var int */
    var $published = null;

    /**
     * @param database - A database connector object
     */
    function mosVehicleManager_review(&$db)
    {
        $table_prefix = $db->getPrefix();
        parent::__construct($table_prefix . "vehiclemanager_review", 'id', $db);
    }

    /**
     * @return array - name: the string of the user the vehicle is reviewed to - e-mail: the e-mail address of the user
     */
    function getReviewFrom($userid)
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

    function toXML3($xmlDoc)
    {

        //create and append name element 
        $retVal = $xmlDoc->createElement("review");

        $user_name = $xmlDoc->createElement("user_name");
        $user_name->appendChild($xmlDoc->createTextNode($this->user_name));
        $retVal->appendChild($user_name);

        $user_email = $xmlDoc->createElement("user_email");
        $user_email->appendChild($xmlDoc->createTextNode($this->user_email));
        $retVal->appendChild($user_email);

        $rating = $xmlDoc->createElement("rating");
        $rating->appendChild($xmlDoc->createTextNode($this->rating));
        $retVal->appendChild($rating);

        $date = $xmlDoc->createElement("date");
        $date->appendChild($xmlDoc->createTextNode($this->date));
        $retVal->appendChild($date);

        $title = $xmlDoc->createElement("title");
        $title->appendChild($xmlDoc->createCDATASection($this->title));
        $retVal->appendChild($title);

        $comment = $xmlDoc->createElement("comment");
        $comment->appendChild($xmlDoc->createCDATASection($this->comment));
        $retVal->appendChild($comment);

        $published = $xmlDoc->createElement("published");
        $published->appendChild($xmlDoc->createCDATASection($this->published));
        $retVal->appendChild($published);

        return $retVal;
    }

    function toXML2()
    {

        $retVal = "<review>\n";

        $retVal .= "<user_name>" . $this->user_name . "</user_name>\n";
        $retVal .= "<user_email>" . $this->user_email . "</user_email>\n";
        $retVal .= "<rating>" . $this->rating . "</rating>\n";
        $retVal .= "<date>" . $this->date . "</date>\n";
        $retVal .= "<title><![CDATA[" . $this->title . "]]></title>\n";
        $retVal .= "<comment><![CDATA[" . $this->comment . "]]></comment>\n";
        $retVal .= "<published><![CDATA[" . $this->published . "]]></published>\n";

        $retVal .= "</review>\n";

        return $retVal;
    }

}

