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
require_once ( JPATH_ROOT . "/components/com_vehiclemanager/functions.php" );


//*** Get language files
global $mosConfig_absolute_path, $mosConfig_lang, $database;

$mosConfig_absolute_path = $GLOBALS['mosConfig_absolute_path'];
require_once($mosConfig_absolute_path . "/administrator/components/com_vehiclemanager/menubar_ext.php");

class menucat
{

    static function NEW_CATEGORY(){   
        mosMenuBarVehicle_ext::startTable();
        mosMenuBarVehicle_ext::save();
        mosMenuBarVehicle_ext::apply('apply', 'apply');
        mosMenuBarVehicle_ext::cancel();
        mosMenuBarVehicle_ext::spacer();
        mosMenuBarVehicle_ext::endTable();
    }

    static function EDIT_CATEGORY(){   
        mosMenuBarVehicle_ext::startTable();
        mosMenuBarVehicle_ext::save();
        mosMenuBarVehicle_ext::apply('apply', 'apply');
        mosMenuBarVehicle_ext::cancel();
        mosMenuBarVehicle_ext::spacer();
        mosMenuBarVehicle_ext::endTable();
    }

    static function SHOW_CATEGORIES()
    {
        mosMenuBarVehicle_ext::startTable();
        mosMenuBarVehicle_ext::addNew();
        mosMenuBarVehicle_ext::editList();
        mosMenuBarVehicle_ext::publishList();
        mosMenuBarVehicle_ext::unpublishList();
        mosMenuBarVehicle_ext::deleteList();
        mosMenuBarVehicle_ext::spacer();
        mosMenuBarVehicle_ext::endTable();
    }

    static function DEFAULT_CATEGORIES()
    {
        mosMenuBarVehicle_ext::startTable();
         mosMenuBarVehicle_ext::addNew('new', 'Add');
        mosMenuBarVehicle_ext::editList();
        mosMenuBarVehicle_ext::publishList();
        mosMenuBarVehicle_ext::unpublishList();
        mosMenuBarVehicle_ext::deleteList();
        mosMenuBarVehicle_ext::endTable();
    }

}

class menufeaturedmanager
{

    static function NEW_FEATUREDMANAGER()
    {
        mosMenuBarVehicle_ext::startTable();
        mosMenuBarVehicle_ext::save();
        mosMenuBarVehicle_ext::cancel();
        mosMenuBarVehicle_ext::spacer();
        mosMenuBarVehicle_ext::endTable();
    }

    static function EDIT_FEATUREDMANAGER()
    {
        mosMenuBarVehicle_ext::startTable();
        mosMenuBarVehicle_ext::save();
        mosMenuBarVehicle_ext::cancel();
        mosMenuBarVehicle_ext::spacer();
        mosMenuBarVehicle_ext::endTable();
    }

    static function SHOW_FEATUREDMANAGER()
    {
        mosMenuBarVehicle_ext::startTable();
        mosMenuBarVehicle_ext::publishList();
        mosMenuBarVehicle_ext::unpublishList();
        mosMenuBarVehicle_ext::addNew();
        mosMenuBarVehicle_ext::editList();
        mosMenuBarVehicle_ext::deleteList();
        mosMenuBarVehicle_ext::spacer();
        mosMenuBarVehicle_ext::endTable();
    }

    static function MENU_FEATUREDMANAGER()
    {
        mosMenuBarVehicle_ext::startTable();
        mosMenuBarVehicle_ext::publishList();
        mosMenuBarVehicle_ext::unpublishList();
        mosMenuBarVehicle_ext::addNew('add', 'Add');
        mosMenuBarVehicle_ext::editList();
        mosMenuBarVehicle_ext::save('addFeature','Save category');
        mosMenuBarVehicle_ext::deleteList();
        mosMenuBarVehicle_ext::endTable();
    }

}

class menulanguagemanager
{

    static function EDIT_LANGUAGEMANAGER()
    {
        mosMenuBarVehicle_ext::startTable();
        mosMenuBarVehicle_ext::save();
        mosMenuBarVehicle_ext::cancel();
        mosMenuBarVehicle_ext::spacer();
        mosMenuBarVehicle_ext::endTable();
    }

    static function MENU_LANGUAGEMANAGER()
    {
        mosMenuBarVehicle_ext::startTable();
        mosMenuBarVehicle_ext::editList();
        mosMenuBarVehicle_ext::spacer();
        mosMenuBarVehicle_ext::endTable();
    }

}

class menuvehiclemanager
{

    static function MENU_NEW()
    {
        mosMenuBarVehicle_ext::startTable();
        mosMenuBarVehicle_ext::save();        
        mosMenuBarVehicle_ext::cancel();
        mosMenuBarVehicle_ext::spacer();
        mosMenuBarVehicle_ext::endTable();
    }
    static function MENU_CLON()
    {
        mosMenuBarVehicle_ext::startTable();
        mosMenuBarVehicle_ext::save();
        mosMenuBarVehicle_ext::apply('apply', 'apply');
        mosMenuBarVehicle_ext::cancel();
        mosMenuBarVehicle_ext::spacer();
        mosMenuBarVehicle_ext::endTable();
    }

    static function MENU_EDIT()
    {
        mosMenuBarVehicle_ext::startTable();
        mosMenuBarVehicle_ext::save();
        mosMenuBarVehicle_ext::apply('apply', 'apply');
        //*******************  begin add for review edit  **********************
        mosMenuBarVehicle_ext::editList('edit_review', _VEHICLE_MANAGER_TOOLBAR_ADMIN_EDIT_REVIEW);
        mosMenuBarVehicle_ext::deleteList('', 'delete_review', _VEHICLE_MANAGER_TOOLBAR_ADMIN_DELETE_REVIEW);
        //*******************  end add for review edit  ************************

        mosMenuBarVehicle_ext::cancel();
        //mosMenuBar::help();
        mosMenuBarVehicle_ext::spacer();
        mosMenuBarVehicle_ext::endTable();
    }

    static function MENU_DELETE_REVIEW()
    {
        mosMenuBarVehicle_ext::startTable();
        mosMenuBarVehicle_ext::save();
        mosMenuBarVehicle_ext::spacer();
        mosMenuBarVehicle_ext::apply('apply', 'apply');
        mosMenuBarVehicle_ext::spacer();

        //*******************  begin add for review edit  **********************
        mosMenuBarVehicle_ext::editList('edit_review', _VEHICLE_MANAGER_TOOLBAR_ADMIN_EDIT_REVIEW);
        mosMenuBarVehicle_ext::deleteList('', 'delete_review', _VEHICLE_MANAGER_TOOLBAR_ADMIN_DELETE_REVIEW);
        //*******************  end add for review edit  ************************

        mosMenuBarVehicle_ext::spacer();
        mosMenuBarVehicle_ext::cancel();
        //mosMenuBar::help();
        mosMenuBarVehicle_ext::spacer();
        mosMenuBarVehicle_ext::endTable();
    }

    static function MENU_EDIT_REVIEW()
    {
        mosMenuBarVehicle_ext::startTable();
        mosMenuBarVehicle_ext::save('update_review');
        mosMenuBarVehicle_ext::cancel('cancel_review_edit');
        mosMenuBarVehicle_ext::spacer();
        mosMenuBarVehicle_ext::endTable();
    }

    static function MENU_CANCEL()
    {
        mosMenuBarVehicle_ext::startTable();
        mosMenuBarVehicle_ext::back();  //old valid  mosMenuBar::cancel();
        //mosMenuBar::help();
        mosMenuBarVehicle_ext::spacer();
        mosMenuBarVehicle_ext::endTable();
    }

    static function MENU_CONFIG()
    {
        mosMenuBarVehicle_ext::startTable();
        mosMenuBarVehicle_ext::save('config_save');
        //mosMenuBarVehicle_ext::cancel();
        //mosMenuBar::help();
        mosMenuBarVehicle_ext::spacer();
        mosMenuBarVehicle_ext::endTable();
    }

//**************   begin for manage reviews   *********************
    static function MENU_MANAGE_REVIEW()
    {
        mosMenuBarVehicle_ext::startTable();
        mosMenuBarVehicle_ext::editList('edit_manage_review', _VEHICLE_MANAGER_TOOLBAR_ADMIN_EDIT_REVIEW);
        mosMenuBarVehicle_ext::spacer();
        mosMenuBarVehicle_ext::deleteList('', 'delete_manage_review', _VEHICLE_MANAGER_TOOLBAR_ADMIN_DELETE_REVIEW);
        mosMenuBarVehicle_ext::endTable();
    }

    static function MENU_MANAGE_REVIEW_DELETE()
    {
        mosMenuBarVehicle_ext::startTable();
        mosMenuBarVehicle_ext::editList('edit_manage_review', _VEHICLE_MANAGER_TOOLBAR_ADMIN_EDIT_REVIEW);
        mosMenuBarVehicle_ext::spacer();
        mosMenuBarVehicle_ext::deleteList('', 'delete_manage_review', _VEHICLE_MANAGER_TOOLBAR_ADMIN_DELETE_REVIEW);
        mosMenuBarVehicle_ext::endTable();
    }

    static function MENU_MANAGE_REVIEW_EDIT()
    {
        mosMenuBarVehicle_ext::startTable();
        mosMenuBarVehicle_ext::save('update_edit_manage_review');
        mosMenuBarVehicle_ext::spacer();
        mosMenuBarVehicle_ext::cancel('cancel_edit_manage_review');
        mosMenuBarVehicle_ext::endTable();
    }

    static function MENU_MANAGE_REVIEW_EDIT_EDIT()
    {
        mosMenuBarVehicle_ext::startTable();
        mosMenuBarVehicle_ext::editList('edit_manage_review', _VEHICLE_MANAGER_TOOLBAR_ADMIN_EDIT_REVIEW);
        mosMenuBarVehicle_ext::spacer();
        mosMenuBarVehicle_ext::deleteList('', 'delete_manage_review', _VEHICLE_MANAGER_TOOLBAR_ADMIN_DELETE_REVIEW);
        mosMenuBarVehicle_ext::endTable();
    }

//**************   end for manage reviews   ***********************


    static function MENU_DEFAULT()
    {
        mosMenuBarVehicle_ext::startTable();
        mosMenuBarVehicle_ext::addNew();
        mosMenuBarVehicle_ext::save2copy('clon_vm', _VEHICLE_MANAGER_LABEL_CLON_CAR);
        mosMenuBarVehicle_ext::publishList();
        mosMenuBarVehicle_ext::unpublishList();

        mosMenuBarVehicle_ext::spacer();
        mosMenuBarVehicle_ext::NewCustom('rent', 'adminForm', "../administrator/components/com_vehiclemanager/images/dm_lend.png", "../administrator/components/com_vehiclemanager/images/dm_lend_32.png", _VEHICLE_MANAGER_TOOLBAR_RENT_VEHICLES, _VEHICLE_MANAGER_TOOLBAR_ADMIN_RENT, true, 'adminForm');

        mosMenuBarVehicle_ext::NewCustom('rent_return', 'adminForm', "../administrator/components/com_vehiclemanager/images/dm_lend_return.png", "../administrator/components/com_vehiclemanager/images/dm_lend_return_32.png", _VEHICLE_MANAGER_TOOLBAR_RETURN_VEHICLE, _VEHICLE_MANAGER_TOOLBAR_ADMIN_RETURN, true, 'adminForm');
        mosMenuBarVehicle_ext::editList('edit_rent', _VEHICLE_MANAGER_TOOLBAR_ADMIN_EDIT_RENT);
        mosMenuBarVehicle_ext::editList('rent_history', _VEHICLE_MANAGER_TOOLBAR_ADMIN_RENT_HISTORY);
        //mosMenuBarVehicle_ext::clon_vm('', _VEHICLE_MANAGER_LABEL_CLON_CAR);
        //mosMenuBarVehicle_ext::editList();
        mosMenuBarVehicle_ext::spacer();
        mosMenuBarVehicle_ext::deleteList();
        mosMenuBarVehicle_ext::spacer();
        mosMenuBarVehicle_ext::endTable();
    }

    static function MENU_RENT_HISTORY()
    {
        mosMenuBarVehicle_ext::startTable();
        mosMenuBarVehicle_ext::spacer();
        mosMenuBarVehicle_ext::back();
        mosMenuBarVehicle_ext::spacer();
        mosMenuBarVehicle_ext::endTable();
    }



    static function MENU_SAVE_BACKEND()
    {
        mosMenuBarVehicle_ext::startTable();
        mosMenuBarVehicle_ext::spacer();
        mosMenuBarVehicle_ext::save();
        mosMenuBarVehicle_ext::spacer();
        mosMenuBarVehicle_ext::apply('apply', 'apply');
        mosMenuBarVehicle_ext::spacer();
        mosMenuBarVehicle_ext::back();
        mosMenuBarVehicle_ext::spacer();
        mosMenuBarVehicle_ext::endTable();
    }

    static function MENU_RENT()
    {
        mosMenuBarVehicle_ext::startTable();
        mosMenuBarVehicle_ext::NewCustom('rent', 'adminForm', "../administrator/components/com_vehiclemanager/images/dm_lend.png", "../administrator/components/com_vehiclemanager/images/dm_lend_32.png", _VEHICLE_MANAGER_TOOLBAR_RENT_VEHICLES, _VEHICLE_MANAGER_TOOLBAR_ADMIN_RENT, true, 'adminForm');
        mosMenuBarVehicle_ext::cancel();
        mosMenuBarVehicle_ext::spacer();
        mosMenuBarVehicle_ext::endTable();
    }

    static function MENU_EDIT_RENT()
    {
        mosMenuBarVehicle_ext::startTable();

        mosMenuBarVehicle_ext::NewCustom('edit_rent', 'adminForm', "../administrator/components/com_vehiclemanager/images/dm_lend.png", "../administrator/components/com_vehiclemanager/images/dm_lend_32.png", _VEHICLE_MANAGER_TOOLBAR_RENT_VEHICLES, _VEHICLE_MANAGER_TOOLBAR_ADMIN_RENT, true, 'adminForm');

        mosMenuBarVehicle_ext::cancel();
        mosMenuBarVehicle_ext::spacer();
        mosMenuBarVehicle_ext::endTable();
    }

    static function MENU_RENTREQUESTS()
    {
        global $mosConfig_absolute_path;
        mosMenuBarVehicle_ext::startTable();
        mosMenuBarVehicle_ext::back();
        mosMenuBarVehicle_ext::NewCustom('accept_rent_requests', 'adminForm', '../administrator/components/com_vehiclemanager/images/dm_accept.png', '../administrator/components/com_vehiclemanager/images/dm_accept_32.png', _VEHICLE_MANAGER_TOOLBAR_ACCEPT_REQUEST, _VEHICLE_MANAGER_TOOLBAR_ADMIN_ACCEPT, true, 'adminForm');

        mosMenuBarVehicle_ext::NewCustom('decline_rent_requests', 'adminForm', '../administrator/components/com_vehiclemanager/images/dm_decline.png', '../administrator/components/com_vehiclemanager/images/dm_decline_32.png', _VEHICLE_MANAGER_TOOLBAR_DECLINE_REQUEST, _VEHICLE_MANAGER_TOOLBAR_ADMIN_DECLINE, true, 'adminForm');

        //mosMenuBarVehicle_ext::cancel();
        mosMenuBarVehicle_ext::spacer();
        mosMenuBarVehicle_ext::endTable();
    }

    static function MENU_BUYINGREQUESTS()
    {
        global $mosConfig_absolute_path;
        mosMenuBarVehicle_ext::startTable();

        mosMenuBarVehicle_ext::NewCustom('accept_buying_requests', 'adminForm', '../administrator/components/com_vehiclemanager/images/dm_accept.png', '../administrator/components/com_vehiclemanager/images/dm_accept_32.png', _VEHICLE_MANAGER_TOOLBAR_ACCEPT_REQUEST, _VEHICLE_MANAGER_TOOLBAR_ADMIN_ACCEPT, true, 'adminForm');

        mosMenuBarVehicle_ext::NewCustom('decline_buying_requests', 'adminForm', '../administrator/components/com_vehiclemanager/images/dm_decline.png', '../administrator/components/com_vehiclemanager/images/dm_decline_32.png', _VEHICLE_MANAGER_TOOLBAR_DECLINE_REQUEST, _VEHICLE_MANAGER_TOOLBAR_ADMIN_DECLINE, true, 'adminForm');


        //mosMenuBarVehicle_ext::cancel();
        mosMenuBarVehicle_ext::spacer();
        mosMenuBarVehicle_ext::endTable();
    }

    static function MENU_RENT_RETURN()
    {
        mosMenuBarVehicle_ext::startTable();
        mosMenuBarVehicle_ext::NewCustom('rent_return', 'adminForm', "../administrator/components/com_vehiclemanager/images/dm_lend_return.png", "../administrator/components/com_vehiclemanager/images/dm_lend_return_32.png", _VEHICLE_MANAGER_TOOLBAR_RETURN_VEHICLE, _VEHICLE_MANAGER_TOOLBAR_ADMIN_RETURN, true, 'adminForm');
        mosMenuBarVehicle_ext::cancel();
        //mosMenuBarVehicle_ext::spacer();		
        mosMenuBarVehicle_ext::endTable();
    }

    function MENU_REFETCH_INFOS()
    {
        mosMenuBarVehicle_ext::startTable();
        mosMenuBarVehicle_ext::NewCustom('refetchInfos', 'adminForm', '../administrator/components/com_vehiclemanager/images/dm_refetchInfos.png', '../administrator/components/com_vehiclemanager/images/dm_refetchInfos_32.png', _VEHICLE_MANAGER_TOOLBAR_REFETCH_INFORMATION, _VEHICLE_MANAGER_TOOLBAR_ADMIN_REFRESH, true, 'adminForm');
        mosMenuBarVehicle_ext::cancel();

        mosMenuBarVehicle_ext::spacer();
        mosMenuBarVehicle_ext::endTable();
    }

    static function MENU_IMPORT_EXPORT()
    {
        mosMenuBarVehicle_ext::startTable();
        mosMenuBarVehicle_ext::NewCustom_I('import', 'adminForm', '../administrator/components/com_vehiclemanager/images/dm_import.png', '../administrator/components/com_vehiclemanager/images/dm_import_32.png', _VEHICLE_MANAGER_TOOLBAR_IMPORT, _VEHICLE_MANAGER_TOOLBAR_ADMIN_IMPORT, true, 'adminForm');

        mosMenuBarVehicle_ext::NewCustom_E('export', 'adminForm', '../administrator/components/com_vehiclemanager/images/dm_export.png', '../administrator/components/com_vehiclemanager/images/dm_export_32.png', _VEHICLE_MANAGER_TOOLBAR_EXPORT, _VEHICLE_MANAGER_TOOLBAR_ADMIN_EXPORT, true, 'adminForm');


        //mosMenuBarVehicle_ext::back();  
        mosMenuBarVehicle_ext::spacer();
        mosMenuBarVehicle_ext::endTable();
    }

    static function MENU_ABOUT()
    {
        mosMenuBarVehicle_ext::startTable();
        mosMenuBarVehicle_ext::back();
        mosMenuBarVehicle_ext::endTable();
    }
    static function MENU_ORDERS()
    {
        mosMenuBarVehicle_ext::deleteList('', 'deleteOrder','Delete Order');
        //mosMenuBarVehicle_ext::startTable();
        //mosMenuBarVehicle_ext::cancel();
        // mosMenuBarVehicle_ext::back();
        //mosMenuBarVehicle_ext::endTable();
    }

    static function MENU_ORDERS_DETAILS()
    {
        mosMenuBarVehicle_ext::startTable();
        mosMenuBarVehicle_ext::back();
        mosMenuBarVehicle_ext::endTable();
    }
    

    static function MENU_USER_RENT_HISTORY()
    {
        mosMenuBarVehicle_ext::startTable();
        mosMenuBarVehicle_ext::spacer();
        mosMenuBarVehicle_ext::back();
        mosMenuBarVehicle_ext::spacer();
        mosMenuBarVehicle_ext::endTable();
    }
}

