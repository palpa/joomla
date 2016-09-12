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
require_once ($mosConfig_absolute_path . "/components/com_vehiclemanager/vehiclemanager.class.rent.php");
require_once ($mosConfig_absolute_path . "/components/com_vehiclemanager/vehiclemanager.class.review.php");
if (version_compare(JVERSION, '3.0', 'lt'))
{
    require_once ( JPATH_SITE . DS . 'libraries' . DS . 'joomla' . DS . 'database' . DS . 'table.php' );
}

/**
 * Vehicle database table class
 */
class mosVehicleManager extends JTable
{

    //keys
    /** @var int Primary key */
    var $id = null;
     //keys
    /** @var int for clon vm  */
    var $id_true = null;

    /** @var int */
    var $vehicleid = null;

    /** @var int */
    var $asset_id = null;

    /** @var int */
    var $catid = null;

    /** @var int */
    var $sid = null;

    /** @var int */
    var $fk_rentid = null;
    //Required fields
    /** @var varchar(250) */
    var $description = null;   /* vehicle description. It may be various amenities of the vehicles
      /** @var varchar(250) */
    var $link = null;

    /** @var varchar(45) */
    var $listing_type = null; //(for rent or for sale)	
    /** @var varchar(14) */
    var $price = null;
    /** @var varchar(14) */

    /** @var varchar(200) */
    var $vtitle = null;       //beautiful vehicle and views

    /** @var varchar(100) */
    var $maker = null;     /* vehicle model
      /** @var varchar(100) */
    var $vmodel = null;     /* vehicle model
      /** @var varchar(20) */
    var $vtype = null; //sedan, convertible, coupe, crossover, hatchback, pickup, suv, truck, wagon
    //Address fields
    /** @var varchar(100) */
    var $vlocation = null;    //address of vehicle
    /** @var varchar(20) */
    var $vlatitude = null;    //latitude of vehicle locatoin
    /** @var varchar(20) */
    var $vlongitude = null;    //longitude
    /** @var tinyint(4) */
    var $map_zoom = 1;    //google map zoom
    //Recommended fields
    /** @var varchar(4) */
    var $year = null;       //year of   issue
    /** @var varchar(4) */
    var $vcondition = null;    //new or used
    /** @var varchar(20) */
    var $mileage = null;

    /** @var varchar(200) */
    var $image_link = null;

    /** @var varchar(45) */
    var $listing_status = null;

    /** @var varchar(45) */
    var $contacts = null;

    /** @var varchar(200) */
    var $price_type = null;    //negotiable or starting
    /** @var varchar(10) */
    var $transmission = null; //manual or automatic
    /** @var varchar(5) */
    var $num_speed = null;    //4Spd, 5Spd, 6Spd, 7Spd, 8Spd 
    /** @var varchar(45) */
    var $interior_color = null; //black, blue, gold, green, maroon, orange, purple, red, silver, gray, brown, white, yellow or many other colors
    /** @var varchar(45) */
    var $exterior_color = null; //many colors
    /** @var varchar(2) */
    var $doors = null; //2, 3, 4, 5
    //Optional fields
    /** @var varchar(40) */
    var $engine = null; //2.7L 100 PH 
    /** @var varchar(20) */
    var $fuel_type = null;  //petrol, gas, gasoline, diesel, H, Gas-electric hybrid 
    /** @var varchar(20) */
    var $drive_type = null;  //Fwd, Rwd, Awd, 4wd, 2wd
    /** @var varchar(4) */
    var $cylinder = null;  //4, 5, 6, 8, 10, 12, 16
    /** @var varchar(20) */
    var $wheelbase = null;  //range between forward and back wheel
    /** @var varchar(4) */
    var $seating = null;  //the number of seating places
    /** @var varchar(5) */
    var $city_fuel_mpg = null;  //fuel consumtion in city
    /** @var varchar(5) */
    var $highway_fuel_mpg = null; //	fuel consumtion in highway
    /** @var varchar(30) */
    var $wheeltype = null; //    wheeltype
    /** @var varchar(30) */
    var $rear_axe_type = null; //    rear axe type
    /** @var varchar(30) */
    var $brakes_type = null; //    brakes axe type
    /** @var varchar(250) */
    var $exterior_amenities = null; //    exterior_amenities
    /** @var varchar(250) */
    var $dashboard_options = null; //    dashboard_options
    /** @var varchar(250) */
    var $interior_amenities = null; //    interior_amenities
    /** @var varchar(250) */
    var $safety_options = null; //    safety_options
    /** @var varchar(30) */
    var $w_basic = null; //    basic warranty
    /** @var varchar(30) */
    var $w_drivetrain = null; //    drivetrain warranty
    /** @var varchar(30) */
    var $w_corrosion = null; //    corrosion warranty
    /** @var varchar(30) */
    var $w_roadside_ass = null; //    roadside assistance warranty

    /** @var varchar(100) */
    var $featured_clicks = null;

    /** @var varchar(100) */
    var $featured_shows = null;

    /** @var boolean */
    var $checked_out = null;

    /** @var time */
    var $checked_out_time = null;

    /** @var datetime */
    var $date = null;

    /** @var int */
    var $published = null;

    /** @var int */
    var $hits = null;

    /** @var varchar(200) */
    var $edok_link = null;

    /* /** @var int */
    var $ordering = null;
    /* /** @var varchar */
    var $owneremail = null;
    var $country = null;
    var $region = null;
    var $city = null;
    var $district = null;
    var $zipcode = null;
    var $priceunit = null;
    var $approved = null;

    /** @var varchar(250) */
    var $extra1 = null;

    /** @var varchar(250) */
    var $extra2 = null;

    /** @var varchar(250) */
    var $extra3 = null;

    /** @var varchar(250) */
    var $extra4 = null;

    /** @var varchar(250) */
    var $extra5 = null;

    /** @var varchar(250) */
    var $extra6 = null;

    /** @var varchar(250) */
    var $extra7 = null;

    /** @var varchar(250) */
    var $extra8 = null;

    /** @var varchar(250) */
    var $extra9 = null;

    /** @var varchar(250) */
    var $extra10 = null;

    /** @var char(7) */
    var $language = null;
    var $owner_id = 0;

    function __construct(&$db)
    {
        parent::__construct('#__vehiclemanager_vehicles', 'id', $db);
    }

    // overloaded check function
    function check()
    {
        global $vehiclemanager_configuration;
        // check for existing vehicleid
        $this->price = floatval(preg_replace('/[\s,]/', '', $this->price));
        if (trim($this->vehicleid) == "")
        {
            $this->setError(_VEHICLE_MANAGER_ADMIN_INFOTEXT_JS_EDIT_VEHICLEID_CHECK);
            return false;
        }

        $this->_db->setQuery("SELECT id FROM #__vehiclemanager_vehicles WHERE vehicleid='$this->vehicleid'");
        $xid = intval($this->_db->loadResult());
        if ($xid && $xid != intval($this->id))
        {
            $this->setError(_VEHICLE_MANAGER_ADMIN_INFOTEXT_JS_EDIT_VEHICLEID);
            return false;
        }
        return true;
    }

    function setCatIds()
    {
        $this->_db->setQuery("SELECT idcat FROM #__vehiclemanager_categories WHERE iditem='$this->id'");
        if (version_compare(JVERSION, '3.0', 'lt'))
        {
            $this->catid = $this->_db->loadResultArray();
        } else
        {
            $this->catid = $this->_db->loadColumn();
        }
    }

    function saveCatIds($categs)
    {   
        if (is_array($categs))
        {
            foreach ($categs as $categ)
                $temp[] = '(' . $this->id . ',' . $categ . ')';
            $queryvalue = implode(', ', $temp);
        } else
            $queryvalue = "('" . $this->id . "','" . $categs . "')";
        $this->catid = $categs;
        $this->_db->setQuery("DELETE FROM #__vehiclemanager_categories WHERE iditem='" . $this->id . "';");
        $this->_db->query();
        $this->_db->setQuery("INSERT INTO #__vehiclemanager_categories (iditem,idcat) VALUES $queryvalue");
        $this->_db->query();
        echo $this->_db->getErrorMsg();
    }

    //check access to vehicle
    function getAccess_VM()
    {
        $this->setCatIds();
        $categoriesid = implode(',', $this->catid);
        if (!$categoriesid)
            return;
        $this->_db->setQuery("SELECT params FROM #__vehiclemanager_main_categories WHERE id IN ($categoriesid)");
        if (version_compare(JVERSION, '3.0', 'lt'))
        {
            $accesses = $this->_db->loadResultArray();
        } else
        {
            $accesses = $this->_db->loadColumn();
        }
        foreach ($accesses as $key => $access)
            if ($access == '')
                $accesses[$key] = '-2';
        return implode(',', $accesses);
    }

    function getUnusedVehicleId()
    {
        $this->_db->setQuery("SELECT vehicleid FROM $this->_tbl");
        if (version_compare(JVERSION, '3.0', 'lt'))
        {
            $vehicles_ids = $this->_db->loadResultArray();
        } else
        {
            $vehicles_ids = $this->_db->loadColumn();
        }
        for ($i = 1; in_array($i, $vehicles_ids); $i++) {
            
        }
        return $i;
    }

    function setOwnerName()
    {
        $this->_db->setQuery("SELECT name FROM #__users WHERE email='$this->owneremail'");
        $this->ownername = $this->_db->loadResult();
    }

    function getOwnerUsername()
    {
        $this->_db->setQuery("SELECT name FROM #__users WHERE id='$this->owner_id'");
        return $this->_db->loadResult();
    }

    /**
     * @param string - Target search string
     * not used at the moment
     */
    function search($text, $state = '', $sectionPrefix = '')
    {
        $text = trim($text);
        return array();
    }

    function updateOrder($where = '')
    { // for 1.6
        return $this->reorder($where);
    }

    function getReviews()
    {
        $this->_db->setQuery("SELECT id FROM #__vehiclemanager_review WHERE fk_vehicleid='$this->id' and published=1 ORDER BY id DESC");
        if (version_compare(JVERSION, '3.0', 'lt'))
        {
            $tmp = $this->_db->loadResultArray();
        } else
        {
            $tmp = $this->_db->loadColumn();
        }
        $retVal = array();
        for ($i = 0, $j = count($tmp); $i < $j; $i++) {
            $help = new mosVehicleManager_review($this->_db);
            $help->load(intval($tmp[$i]));
            $retVal[$i] = $help;
        }
        return $retVal;
    }

    function getRent()
    {
        $rent = null;
        if ($this->fk_rentid != null && $this->fk_rentid != 0)
        {
            $rent = new mosVehicleManager_rent($this->_db);
            // load the row from the db table
            $rent->load(intval($this->fk_rentid));
        }
        return $rent;
    }

    function getAllRents($exclusion = "")
    {
        $this->_db->setQuery(
          "SELECT id FROM #__vehiclemanager_rent WHERE fk_vehicleid='$this->id' " .
          $exclusion . " ORDER BY id");
        if (version_compare(JVERSION, '3.0', 'lt'))
        {
            $tmp = $this->_db->loadResultArray();
        } else
        {
            $tmp = $this->_db->loadColumn();
        }
        $retVal = array();
        for ($i = 0, $j = count($tmp); $i < $j; $i++) {
            $help = new mosVehicleManager_rent($this->_db);
            $help->load(intval($tmp[$i]));
            $retVal[$i] = $help;
        }
        return $retVal;
    }

    function getAllRentRequests($exclusion = "")
    {

        $this->_db->setQuery(
          "SELECT id FROM #__vehiclemanager_rent_request WHERE fk_vehicleid='$this->id'"
           . $exclusion . " ORDER BY id");
        if (version_compare(JVERSION, '3.0', 'lt'))
        {
            $tmp = $this->_db->loadResultArray();
        } else
        {
            $tmp = $this->_db->loadColumn();
        }
        $retVal = array();
        for ($i = 0, $j = count($tmp); $i < $j; $i++) {

            $help = new mosVehicleManager_rent_request($this->_db);
            $help->load(intval($tmp[$i]));
            $retVal[$i] = $help;
        }
        return $retVal;
    }

    function getAllBuyingRequests($exclusion = "")
    {

        $this->_db->setQuery(
          "SELECT id FROM #__vehiclemanager_buying_request WHERE fk_vehicleid='$this->id'"
           . $exclusion . " ORDER BY id");
        if (version_compare(JVERSION, '3.0', 'lt'))
        {
            $tmp = $this->_db->loadResultArray();
        } else
        {
            $tmp = $this->_db->loadColumn();
        }
        $retVal = array();
        for ($i = 0, $j = count($tmp); $i < $j; $i++) {
            $help = new mosVehicleManager_buying_request($this->_db);
            $help->load(intval($tmp[$i]));
            $retVal[$i] = $help;
        }
        return $retVal;
    }

    function getAllImages($exclusion = "")
    {
        $this->_db->setQuery(
          "SELECT thumbnail_img, main_img FROM #__vehiclemanager_photos WHERE fk_vehicleid='$this->id'"
           . $exclusion . " ORDER BY id");
        $retVal = $this->_db->loadObjectList();
        return $retVal;
    }

    function getAllVideos($exclusion = ""){
        $this->_db->setQuery(
          "SELECT sequence_number, src, type, media, youtube FROM #__vehiclemanager_video_source WHERE fk_vehicle_id='$this->id'"
           . $exclusion . " ORDER BY id");
        $retVal = $this->_db->loadObjectList();
        return $retVal;
    }

    function getAllTracks($exclusion = ""){
        $this->_db->setQuery(
          "SELECT sequence_number, src, kind, scrlang, label FROM #__vehiclemanager_track_source WHERE fk_vehicle_id='$this->id'"
           . $exclusion . " ORDER BY id");
        $retVal = $this->_db->loadObjectList();
        return $retVal;
    }

    function getAllVehicleFeatures($exclusion = "") {
        $this->_db->setQuery(
          "SELECT * FROM #__vehiclemanager_feature_vehicles WHERE fk_vehicleid='$this->id' "
         . $exclusion . " ORDER BY id");
        $retVal = $this->_db->loadObjectList();
        return $retVal;
    }
    
    function getAllRentSal($exclusion = "") {
        $this->_db->setQuery(
          "SELECT * FROM #__vehiclemanager_rent_sal WHERE fk_vehiclesid='$this->id' "
           . $exclusion . " ORDER BY id");
        $retVal = $this->_db->loadObjectList();
        return $retVal;
    }
    function toXML3($xmlDoc, $all)
    {
        //create and append name element 
        $retVal = $xmlDoc->createElement("vehicle");

        $id = $xmlDoc->createElement("id");
        $id->appendChild($xmlDoc->createTextNode($this->id));
        $retVal->appendChild($id);

        $vehicleid = $xmlDoc->createElement("vehicleid");
        $vehicleid->appendChild($xmlDoc->createTextNode($this->vehicleid));
        $retVal->appendChild($vehicleid);

        $catid = $xmlDoc->createElement("catid");
        $catid->appendChild($xmlDoc->createTextNode($this->catid));
        $retVal->appendChild($catid);

        $description = $xmlDoc->createElement("description");
        $description->appendChild($xmlDoc->createCDATASection($this->description));
        $retVal->appendChild($description);

        $link = $xmlDoc->createElement("link");
        $link->appendChild($xmlDoc->createTextNode($this->link));
        $retVal->appendChild($link);

        $vmodel = $xmlDoc->createElement("vmodel");
        $vmodel->appendChild($xmlDoc->createTextNode($this->vmodel));
        $retVal->appendChild($vmodel);

        $vtype = $xmlDoc->createElement("vtype");
        $vtype->appendChild($xmlDoc->createTextNode($this->vtype));
        $retVal->appendChild($vtype);

        $listing_type = $xmlDoc->createElement("listing_type");
        $listing_type->appendChild($xmlDoc->createCDATASection($this->listing_type));
        $retVal->appendChild($listing_type);

        $price = $xmlDoc->createElement("price");
        $price->appendChild($xmlDoc->createTextNode($this->price));
        $retVal->appendChild($price);

        $priceunit = $xmlDoc->createElement("priceunit");
        $priceunit->appendChild($xmlDoc->createTextNode($this->priceunit));
        $retVal->appendChild($priceunit);

        $price_type = $xmlDoc->createElement("price_type");
        $price_type->appendChild($xmlDoc->createTextNode($this->price_type));
        $retVal->appendChild($price_type);

        $vtitle = $xmlDoc->createElement("vtitle");
        $vtitle->appendChild($xmlDoc->createCDATASection($this->vtitle));
        $retVal->appendChild($vtitle);

        $vlocation = $xmlDoc->createElement("vlocation");
        $vlocation->appendChild($xmlDoc->createCDATASection($this->vlocation));
        $retVal->appendChild($vlocation);

        $vlatitude = $xmlDoc->createElement("vlatitude");
        $vlatitude->appendChild($xmlDoc->createTextNode($this->vlatitude));
        $retVal->appendChild($vlatitude);

        $vlongitude = $xmlDoc->createElement("vlongitude");
        $vlongitude->appendChild($xmlDoc->createTextNode($this->vlongitude));
        $retVal->appendChild($vlongitude);

        $contacts = $xmlDoc->createElement("contacts");
        $contacts->appendChild($xmlDoc->createTextNode($this->contacts));
        $retVal->appendChild($contacts);

        $map_zoom = $xmlDoc->createElement("map_zoom");
        $map_zoom->appendChild($xmlDoc->createTextNode($this->map_zoom));
        $retVal->appendChild($map_zoom);

        //recommended fields
        $year = $xmlDoc->createElement("year");
        $year->appendChild($xmlDoc->createTextNode($this->year));
        $retVal->appendChild($year);

        $vcondition = $xmlDoc->createElement("vcondition");
        $vcondition->appendChild($xmlDoc->createTextNode($this->vcondition));
        $retVal->appendChild($vcondition);

        $mileage = $xmlDoc->createElement("mileage");
        $mileage->appendChild($xmlDoc->createTextNode($this->mileage));
        $retVal->appendChild($mileage);

        $listing_status = $xmlDoc->createElement("listing_status");
        $listing_status->appendChild($xmlDoc->createTextNode($this->listing_status));
        $retVal->appendChild($listing_status);

        //Technical options
        $engine = $xmlDoc->createElement("engine");
        $engine->appendChild($xmlDoc->createCDATASection($this->engine));
        $retVal->appendChild($engine);

        $transmission = $xmlDoc->createElement("transmission");
        $transmission->appendChild($xmlDoc->createTextNode($this->transmission));
        $retVal->appendChild($transmission);

        $drive_type = $xmlDoc->createElement("drive_type");
        $drive_type->appendChild($xmlDoc->createTextNode($this->drive_type));
        $retVal->appendChild($drive_type);

        $cylinder = $xmlDoc->createElement("cylinder");
        $cylinder->appendChild($xmlDoc->createTextNode($this->cylinder));
        $retVal->appendChild($cylinder);

        $num_speed = $xmlDoc->createElement("num_speed");
        $num_speed->appendChild($xmlDoc->createTextNode($this->num_speed));
        $retVal->appendChild($num_speed);

        $fuel_type = $xmlDoc->createElement("fuel_type");
        $fuel_type->appendChild($xmlDoc->createTextNode($this->fuel_type));
        $retVal->appendChild($fuel_type);

        $city_fuel_mpg = $xmlDoc->createElement("city_fuel_mpg");
        $city_fuel_mpg->appendChild($xmlDoc->createTextNode($this->city_fuel_mpg));
        $retVal->appendChild($city_fuel_mpg);

        $highway_fuel_mpg = $xmlDoc->createElement("highway_fuel_mpg");
        $highway_fuel_mpg->appendChild($xmlDoc->createTextNode($this->highway_fuel_mpg));
        $retVal->appendChild($highway_fuel_mpg);

        $wheelbase = $xmlDoc->createElement("wheelbase");
        $wheelbase->appendChild($xmlDoc->createTextNode($this->wheelbase));
        $retVal->appendChild($wheelbase);

        $wheeltype = $xmlDoc->createElement("wheeltype");
        $wheeltype->appendChild($xmlDoc->createCDATASection($this->wheeltype));
        $retVal->appendChild($wheeltype);

        $rear_axe_type = $xmlDoc->createElement("rear_axe_type");
        $rear_axe_type->appendChild($xmlDoc->createTextNode($this->rear_axe_type));
        $retVal->appendChild($rear_axe_type);

        $brakes_type = $xmlDoc->createElement("brakes_type");
        $brakes_type->appendChild($xmlDoc->createTextNode($this->brakes_type));
        $retVal->appendChild($brakes_type);

        //Exterior options        
        $exterior_color = $xmlDoc->createElement("exterior_color");
        $exterior_color->appendChild($xmlDoc->createTextNode($this->exterior_color));
        $retVal->appendChild($exterior_color);

        $doors = $xmlDoc->createElement("doors");
        $doors->appendChild($xmlDoc->createTextNode($this->doors));
        $retVal->appendChild($doors);

        $exterior_amenities = $xmlDoc->createElement("exterior_amenities");
        $exterior_amenities->appendChild($xmlDoc->createCDATASection($this->exterior_amenities));
        $retVal->appendChild($exterior_amenities);

        //Interior options        
        $interior_color = $xmlDoc->createElement("interior_color");
        $interior_color->appendChild($xmlDoc->createTextNode($this->interior_color));
        $retVal->appendChild($interior_color);

        $seating = $xmlDoc->createElement("seating");
        $seating->appendChild($xmlDoc->createTextNode($this->seating));
        $retVal->appendChild($seating);

        $dashboard_options = $xmlDoc->createElement("dashboard_options");
        $dashboard_options->appendChild($xmlDoc->createCDATASection($this->dashboard_options));
        $retVal->appendChild($dashboard_options);

        $interior_amenities = $xmlDoc->createElement("interior_amenities");
        $interior_amenities->appendChild($xmlDoc->createCDATASection($this->interior_amenities));
        $retVal->appendChild($interior_amenities);

        //Other options
        $safety_options = $xmlDoc->createElement("safety_options");
        $safety_options->appendChild($xmlDoc->createCDATASection($this->safety_options));
        $retVal->appendChild($safety_options);

        $w_basic = $xmlDoc->createElement("w_basic");
        $w_basic->appendChild($xmlDoc->createTextNode($this->w_basic));
        $retVal->appendChild($w_basic);

        $w_drivetrain = $xmlDoc->createElement("w_drivetrain");
        $w_drivetrain->appendChild($xmlDoc->createTextNode($this->w_drivetrain));
        $retVal->appendChild($w_drivetrain);

        $w_corrosion = $xmlDoc->createElement("w_corrosion");
        $w_corrosion->appendChild($xmlDoc->createTextNode($this->w_corrosion));
        $retVal->appendChild($w_corrosion);

        $w_roadside_ass = $xmlDoc->createElement("w_roadside_ass");
        $w_roadside_ass->appendChild($xmlDoc->createTextNode($this->w_roadside_ass));
        $retVal->appendChild($w_roadside_ass);

        $image_link = $xmlDoc->createElement("image_link");
        $image_link->appendChild($xmlDoc->createTextNode($this->image_link));
        $retVal->appendChild($image_link);

        $featured_shows = $xmlDoc->createElement("featured_shows");
        $featured_shows->appendChild($xmlDoc->createCDATASection($this->featured_shows));
        $retVal->appendChild($featured_shows);

        $featured_clicks = $xmlDoc->createElement("featured_clicks");
        $featured_clicks->appendChild($xmlDoc->createCDATASection($this->featured_clicks));
        $retVal->appendChild($featured_clicks);

        $hits = $xmlDoc->createElement("hits");
        $hits->appendChild($xmlDoc->createTextNode($this->hits));
        $retVal->appendChild($hits);

        $date = $xmlDoc->createElement("date");
        $date->appendChild($xmlDoc->createTextNode($this->date));
        $retVal->appendChild($date);

        $published = $xmlDoc->createElement("published");
        $published->appendChild($xmlDoc->createTextNode($this->published));
        $retVal->appendChild($published);

        $extra1 = $xmlDoc->createElement("extra1");
        $extra1->appendChild($xmlDoc->createTextNode($this->extra1));
        $retVal->appendChild($extra1);

        $extra2 = $xmlDoc->createElement("extra2");
        $extra2->appendChild($xmlDoc->createTextNode($this->extra2));
        $retVal->appendChild($extra1);

        $extra3 = $xmlDoc->createElement("extra3");
        $extra3->appendChild($xmlDoc->createTextNode($this->extra3));
        $retVal->appendChild($extra3);

        $extra4 = $xmlDoc->createElement("extra4");
        $extra4->appendChild($xmlDoc->createTextNode($this->extra4));
        $retVal->appendChild($extra4);

        $extra5 = $xmlDoc->createElement("extra5");
        $extra5->appendChild($xmlDoc->createTextNode($this->extra5));
        $retVal->appendChild($extra5);

        $extra6 = $xmlDoc->createElement("extra6");
        $extra6->appendChild($xmlDoc->createTextNode($this->extra6));
        $retVal->appendChild($extra6);

        $extra7 = $xmlDoc->createElement("extra7");
        $extra7->appendChild($xmlDoc->createTextNode($this->extra7));
        $retVal->appendChild($extra7);

        $extra8 = $xmlDoc->createElement("extra8");
        $extra8->appendChild($xmlDoc->createTextNode($this->extra8));
        $retVal->appendChild($extra8);

        $extra9 = $xmlDoc->createElement("extra9");
        $extra9->appendChild($xmlDoc->createTextNode($this->extra9));
        $retVal->appendChild($extra9);

        $extra10 = $xmlDoc->createElement("extra10");
        $extra10->appendChild($xmlDoc->createTextNode($this->extra10));
        $retVal->appendChild($extra10);

        $language = $xmlDoc->createElement("language");
        $language->appendChild($xmlDoc->createTextNode($this->language));
        $retVal->appendChild($language);
        
        $owner_id = $xmlDoc->createElement("owner_id");
        $owner_id->appendChild($xmlDoc->createTextNode($this->owner_id));
        $retVal->appendChild($owner_id);
       
        $associate_vehicle = $xmlDoc->createElement("associate_vehicle");
        $associate_vehicle->appendChild($xmlDoc->createTextNode($this->associate_vehicle)); 
        $retVal->appendChild($associate_vehicle);
        
        if ($all)
        {
            //$rents_data = $this->getRent();
            $exclusion = "";
            $rents = $xmlDoc->createElement("rents");
            $rents_data = $this->getAllRents($exclusion);
            ;
            foreach ($rents_data as $rent_data)
                $rents->appendChild($rent_data->toXML($xmlDoc));
            $retVal->appendChild($rents);
            $rentrequests = $xmlDoc->createElement("rentrequests");
            $rentrequests_data = $this->getAllRentRequests($exclusion);
            foreach ($rentrequests_data as $rentrequest_data)
                $rentrequests->appendChild($rentrequest_data->toXML($xmlDoc));
            $retVal->appendChild($rentrequests);
            $buyingrequests = $xmlDoc->createElement("buyingrequests");
            $buyingrequests_data = $this->getAllBuyingRequests($exclusion);
            foreach ($buyingrequests_data as $buyingrequest_data)
                $buyingrequests->appendChild($buyingrequest_data->toXML($xmlDoc));
            $retVal->appendChild($buyingrequests);
            $reviews = $xmlDoc->createElement("reviews");
            $reviews_data = $this->getReviews();
            foreach ($reviews_data as $review_data)
                $reviews->appendChild($review_data->toXML($xmlDoc));
            $retVal->appendChild($reviews);

            $images = $xmlDoc->createElement("images");
            $images_data = $this->getAllImages();
            foreach ($images_data as $image_data) {
                $image = $xmlDoc->createElement("image");
                $image_thumbnail_img = & $xmlDoc->createElement("thumbnail_img");
                $image_thumbnail_img->appendChild($xmlDoc->createTextNode($image_data->thumbnail_img));
                $image->appendChild($image_thumbnail_img);
                $image_main_img = $xmlDoc->createElement("main_img");
                $image_main_img->appendChild($xmlDoc->createTextNode($image_data->main_img));
                $image->appendChild($image_main_img);
                $images->appendChild($image);
            }
            $retVal->appendChild($images);
        }
        return $retVal;
    }

    function toXML2($all)
    {
        $this->setCatIds();
        if (count($this->catid) < 1)
        {
            echo _VEHICLE_MANAGER_ERROR_CATEGORIES;
            exit;
        }
        foreach ($this->catid as $catid)
            $catids[] = "<catid>" . $catid . "</catid>\n";
        $catids = implode('', $catids);

        $retVal = "<vehicle>\n";
        $retVal .= "<id>" . $this->id . "</id>\n";
        $retVal .= "<vehicleid>" . $this->vehicleid . "</vehicleid>\n";
        $retVal .= "<catids>\n" . $catids . "</catids>\n";
        $retVal .= "<description><![CDATA[" . $this->description . "]]></description>\n";
        $retVal .= "<link><![CDATA[" . $this->link . "]]></link>\n";
        $retVal .= "<vmodel><![CDATA[" . $this->vmodel . "]]></vmodel>\n";
        $retVal .= "<vtype><![CDATA[" . $this->vtype . "]]></vtype>\n";
        $retVal .= "<listing_type><![CDATA[" . $this->listing_type . "]]></listing_type>\n";
        $retVal .= "<price>" . $this->price . "</price>\n";
        $retVal .= "<priceunit><![CDATA[" . $this->priceunit . "]]></priceunit>\n";
        $retVal .= "<price_type>" . $this->price_type . "</price_type>\n";
        $retVal .= "<vtitle><![CDATA[" . $this->vtitle . "]]></vtitle>\n";
        $retVal .= "<vlocation><![CDATA[" . $this->vlocation . "]]></vlocation>\n";
        $retVal .= "<vlatitude>" . $this->vlatitude . "</vlatitude>\n";
        $retVal .= "<vlongitude>" . $this->vlongitude . "</vlongitude>\n";
        $retVal .= "<contacts>" . $this->contacts . "</contacts>\n"; //<contacts>
        $retVal .= "<map_zoom>" . $this->map_zoom . "</map_zoom>\n";
        $retVal .= "<year>" . $this->year . "</year>\n";
        $retVal .= "<vcondition><![CDATA[" . $this->vcondition . "]]></vcondition>\n";
        $retVal .= "<mileage>" . $this->mileage . "</mileage>\n";
        $retVal .= "<listing_status><![CDATA[" . $this->listing_status . "]]></listing_status>\n";
        $retVal .= "<engine><![CDATA[" . $this->engine . "]]></engine>\n";
        $retVal .= "<transmission><![CDATA[" . $this->transmission . "]]></transmission>\n";
        $retVal .= "<drive_type><![CDATA[" . $this->drive_type . "]]></drive_type>\n";
        $retVal .= "<cylinder><![CDATA[" . $this->cylinder . "]]></cylinder>\n";
        $retVal .= "<num_speed><![CDATA[" . $this->num_speed . "]]></num_speed>\n";
        $retVal .= "<fuel_type><![CDATA[" . $this->fuel_type . "]]></fuel_type>\n";
        $retVal .= "<city_fuel_mpg><![CDATA[" . $this->city_fuel_mpg . "]]></city_fuel_mpg>\n";
        $retVal .= "<highway_fuel_mpg><![CDATA[" . $this->highway_fuel_mpg . "]]></highway_fuel_mpg>\n";
        $retVal .= "<wheelbase><![CDATA[" . $this->wheelbase . "]]></wheelbase>\n";
        $retVal .= "<wheeltype><![CDATA[" . $this->wheeltype . "]]></wheeltype>\n";
        $retVal .= "<rear_axe_type><![CDATA[" . $this->rear_axe_type . "]]></rear_axe_type>\n";
        $retVal .= "<brakes_type><![CDATA[" . $this->brakes_type . "]]></brakes_type>\n";
        $retVal .= "<exterior_color><![CDATA[" . $this->exterior_color . "]]></exterior_color>\n";
        $retVal .= "<doors>" . $this->doors . "</doors>\n";
        $retVal .= "<exterior_amenities><![CDATA[" . $this->exterior_amenities . "]]></exterior_amenities>\n";
        $retVal .= "<interior_color><![CDATA[" . $this->interior_color . "]]></interior_color>\n";
        $retVal .= "<seating>" . $this->seating . "</seating>\n";
        $retVal .= "<dashboard_options><![CDATA[" . $this->dashboard_options . "]]></dashboard_options>\n";
        $retVal .= "<interior_amenities><![CDATA[" . $this->interior_amenities . "]]></interior_amenities>\n";
        $retVal .= "<safety_options><![CDATA[" . $this->safety_options . "]]></safety_options>\n";
        $retVal .= "<w_basic><![CDATA[" . $this->w_basic . "]]></w_basic>\n";
        $retVal .= "<w_drivetrain><![CDATA[" . $this->w_drivetrain . "]]></w_drivetrain>\n";
        $retVal .= "<w_corrosion><![CDATA[" . $this->w_corrosion . "]]></w_corrosion>\n";
        $retVal .= "<w_roadside_ass><![CDATA[" . $this->w_roadside_ass . "]]></w_roadside_ass>\n";
        $retVal .= "<image_link><![CDATA[" . $this->image_link . "]]></image_link>\n";
        $retVal .= "<featured_clicks><![CDATA[" . $this->featured_clicks . "]]></featured_clicks>\n";
        $retVal .= "<featured_shows><![CDATA[" . $this->featured_shows . "]]></featured_shows>\n";
        $retVal .= "<hits>" . $this->hits . "</hits>\n";
        $retVal .= "<edoc>" . $this->edok_link . "</edoc>\n";
        $retVal .= "<date>" . $this->date . "</date>\n";
        $retVal .= "<published>" . $this->published . "</published>\n";
        $retVal .= "<maker><![CDATA[" . $this->maker . "]]></maker>\n";
        $retVal .= "<owneremail><![CDATA[" . $this->owneremail . "]]></owneremail>\n";
        $retVal .= "<country><![CDATA[" . $this->country . "]]></country>\n";
        $retVal .= "<region><![CDATA[" . $this->region . "]]></region>\n";
        $retVal .= "<city><![CDATA[" . $this->city . "]]></city>\n";
        $retVal .= "<district><![CDATA[" . $this->district . "]]></district>\n";
        $retVal .= "<zipcode><![CDATA[" . $this->zipcode . "]]></zipcode>\n";
        $retVal .= "<extra1><![CDATA[" . $this->extra1 . "]]></extra1>\n";
        $retVal .= "<extra2><![CDATA[" . $this->extra2 . "]]></extra2>\n";
        $retVal .= "<extra3><![CDATA[" . $this->extra3 . "]]></extra3>\n";
        $retVal .= "<extra4><![CDATA[" . $this->extra4 . "]]></extra4>\n";
        $retVal .= "<extra5><![CDATA[" . $this->extra5 . "]]></extra5>\n";
        $retVal .= "<extra6><![CDATA[" . $this->extra6 . "]]></extra6>\n";
        $retVal .= "<extra7><![CDATA[" . $this->extra7 . "]]></extra7>\n";
        $retVal .= "<extra8><![CDATA[" . $this->extra8 . "]]></extra8>\n";
        $retVal .= "<extra9><![CDATA[" . $this->extra9 . "]]></extra9>\n";
        $retVal .= "<extra10><![CDATA[" . $this->extra10 . "]]></extra10>\n";
        $retVal .= "<language><![CDATA[" . $this->language . "]]></language>\n";
        $retVal .= "<owner_id><![CDATA[" . $this->owner_id . "]]></owner_id>\n";
        $retVal.= "<associate_vehicle><![CDATA[" . $this->associate_vehicle . "]]></associate_vehicle>\n";
             
               
        if ($all)
        {
            $exclusion = "";
            $retVal .= "<rents>\n";
            $rents = $this->getAllRents($exclusion);
            foreach ($rents as $rent)
                $retVal .= $rent->toXML2();
            $retVal .= "</rents>\n";

            $retVal .= "<rentrequests>\n";
            $rentrequests = $this->getAllRentRequests($exclusion);
            foreach ($rentrequests as $rentrequest)
                $retVal .= $rentrequest->toXML2();
            $retVal .= "</rentrequests>\n";

            $retVal .= "<buyingrequests>\n";
            $buyingrequests = $this->getAllBuyingRequests($exclusion);
            foreach ($buyingrequests as $buyingrequest)
                $retVal .= $buyingrequest->toXML2();
            $retVal .= "</buyingrequests>\n";

            $retVal .= "<reviews>\n";
            $reviews = $this->getReviews($exclusion);
            foreach ($reviews as $review)
                $retVal .= $review->toXML2();
            $retVal .= "</reviews>\n";

            $retVal .= "<images>\n";
            $images_data = $this->getAllImages();
            foreach ($images_data as $image_data) {
                $retVal .= "<image>\n";
                $retVal .= "<thumbnail_img><![CDATA[" . $image_data->thumbnail_img . "]]></thumbnail_img>\n";
                $retVal .= "<main_img><![CDATA[" . $image_data->main_img . "]]></main_img>\n";
                $retVal .= "</image>\n";
            }
            $retVal .= "</images>\n";

            $retVal .= "<videos>\n";
            $videos_data = $this->getAllVideos();
            foreach ($videos_data as $video_data) {
                $retVal .= "<video>\n";
                $retVal .= "<sequence_number><![CDATA[" . $video_data->sequence_number . "]]></sequence_number>\n";
                $retVal .= "<src><![CDATA[" . $video_data->src . "]]></src>\n";
                $retVal .= "<type><![CDATA[" . $video_data->type . "]]></type>\n";
                $retVal .= "<media><![CDATA[" . $video_data->media . "]]></media>\n";
                $retVal .= "<youtube><![CDATA[" . $video_data->youtube . "]]></youtube>\n";
                $retVal .= "</video>\n";
            }
            $retVal .= "</videos>\n";

            $retVal .= "<tracks>\n";
            $tracks_data = $this->getAllTracks();
            foreach ($tracks_data as $track_data) {
                $retVal .= "<track>\n";
                $retVal .= "<sequence_number><![CDATA[" . $track_data->sequence_number . "]]></sequence_number>\n";
                $retVal .= "<src><![CDATA[" . $track_data->src . "]]></src>\n";
                $retVal .= "<kind><![CDATA[" . $track_data->kind . "]]></kind>\n";
                $retVal .= "<scrlang><![CDATA[" . $track_data->scrlang . "]]></scrlang>\n";
                $retVal .= "<label><![CDATA[" . $track_data->label . "]]></label>\n";
                $retVal .= "</track>\n";
            }
            $retVal .= "</tracks>\n";

            $retVal.= "<feature_vehicles>\n";
            $features_data = $this->getAllVehicleFeatures();
            foreach ($features_data as $feature_data) {
                $retVal.= "<feature_vehicle>\n";
                $retVal.= "<fk_featureid><![CDATA[" . $feature_data->fk_featureid . "]]></fk_featureid>\n";
                $retVal.= "</feature_vehicle>\n";
            }
            $retVal.= "</feature_vehicles>\n";
            $retVal.= "<rent_sals>\n";
            $rentsals_data = $this->getAllRentSal();
            foreach ($rentsals_data as $rentsal_data) {
                $retVal.= "<rent_sal>\n";
                $retVal.= "<monthW><![CDATA[" . $rentsal_data->monthW . "]]></monthW>\n";
                $retVal.= "<yearW><![CDATA[" . $rentsal_data->yearW . "]]></yearW>\n";
                $retVal.= "<week><![CDATA[" . $rentsal_data->week . "]]></week>\n";
                $retVal.= "<weekend><![CDATA[" . $rentsal_data->weekend . "]]></weekend>\n";
                $retVal.= "<midweek><![CDATA[" . $rentsal_data->midweek . "]]></midweek>\n";
                $retVal.= "<price_from><![CDATA[" . $rentsal_data->price_from . "]]></price_from>\n";
                $retVal.= "<price_to><![CDATA[" . $rentsal_data->price_to . "]]></price_to>\n";
                $retVal.= "<special_price><![CDATA[" . $rentsal_data->special_price . "]]></special_price>\n";
                $retVal.= "<comment_price><![CDATA[" . $rentsal_data->comment_price . "]]></comment_price>\n";
                $retVal.= "<priceunit><![CDATA[" . $rentsal_data->priceunit . "]]></priceunit>\n";
                $retVal.= "</rent_sal>\n";
            }
            $retVal.= "</rent_sals>\n";
                      
        }
        $retVal .= "</vehicle>\n";
        
        //print_r($retVal); exit; 
        
        return $retVal;
    }

}

?>
