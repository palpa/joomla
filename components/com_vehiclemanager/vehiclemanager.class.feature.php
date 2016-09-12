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
class mosVehicleManager_feature extends JTable
{

    /** @var int - Primary key */
    var $id = null;

    /** @var varchar(250) */
    var $name = null;

    /** @var varchar(250) */
    var $categories = null;

    /** @var int */
    var $published = null;

    /** @var varchar(250) */
    var $image_link = null;

    /**
     * @param database A database connector object
     */
    function mosVehicleManager_feature($db)
    {
        parent::__construct("#__vehiclemanager_feature", 'id', $db);
    }

    function updateOrder($where = '')
    { // for 1.6
        return $this->reorder($where);
    }

    /*
      function saveFeatureIds($categs){
      if(is_array($categs)){
      foreach($categs as $categ) $temp[]='('.$this->id.','.$categ.')';
      $queryvalue=implode(', ',$temp);
      } else $queryvalue="('".$this->id."','".$categs."')";
      $this->catid=$categs;
      $this->_db->setQuery("DELETE FROM #__vehiclemanager_feature WHERE id='".$this->id."';");
      $this->_db->query();
      $this->_db->setQuery("INSERT INTO #__vehiclemanager_feature (iditem,idcat) VALUES $queryvalue");
      $this->_db->query();
      echo $this->_db->getErrorMsg();
      }
     */
    /*
      // overloaded check function
      function check(){
      // check if vehicle is already lent out
      $this->_db->setQuery( "SELECT id FROM #__vehiclemanager_rent WHERE fk_vehicleid='$this->fk_vehicleid' AND rent_return = null");
      $xid = intval( $this->_db->loadResult() );
      if ($xid){
      $this->_error = _VEHICLE_MANAGER_VEHICLE_RENT_OUT;
      return false;
      }
      return true;
      }
     */

    /**
     * @return array – name: the string of the user the vehicle is lent to - e-mail: the e-mail address of the user
     */
    /*
      function getRentTo($userid){
      if($userid != null && $userid != 0){
      $this->_db->setQuery( "SELECT name, email from #__users where id=$userid");
      $help = $this->_db->loadRow();
      $this->user_name = $help[0];
      $this->user_email = $help[1];
      } else{
      $this->user_name = _VEHICLE_MANAGER_LABEL_ANONYMOUS;
      $this->user_email = null;
      }
      } */


    function toXML3(& $xmlDoc, $elementname = "feature")
    {
        //create and append name element 
        $retVal = & $xmlDoc->createElement("feature");

        $name = & $xmlDoc->createElement("name");
        $name->appendChild($xmlDoc->createTextNode($this->name));
        $retVal->appendChild($name);

        $categories = & $xmlDoc->createElement("categories");
        $categories->appendChild($xmlDoc->createTextNode($this->categories));
        $retVal->appendChild($categories);

        $published = & $xmlDoc->createElement("published");
        $published->appendChild($xmlDoc->createTextNode($this->published));
        $retVal->appendChild($published);

        $image_link = & $xmlDoc->createElement("image_link");
        $image_link->appendChild($xmlDoc->createTextNode($this->image_link));
        $retVal->appendChild($image_link);

        $language = & $xmlDoc->createElement("language");
        $language->appendChild($xmlDoc->createTextNode($this->language));
        $retVal->appendChild($language);

        return $retVal;
    }

    function toXML2()
    {
        $retVal = "<feature>\n";
        $retVal .= "<feature_name><![CDATA[" . $this->name . "]]></feature_name>\n";
        $retVal .= "<feature_categories><![CDATA[" . $this->categories . "]]></feature_categories>\n";
        $retVal .= "<feature_published>" . $this->published . "</feature_published>\n";
        $retVal .= "<feature_image_link><![CDATA[" . $this->image_link . "]]></feature_image_link>\n";
        $retVal .= "<feature_language><![CDATA[" . $this->language . "]]></feature_language>\n";
        $retVal .= "</feature>\n";
        return $retVal;
    }

}
