  <?php
/**
 * @package  VehicleManager
 * @copyright 2012 Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); Rob de Cleen(rob@decleen.com)
 * Homepage: http://www.ordasoft.com
 * @version: 3.5 Free
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * */
  if (!defined('_VALID_MOS') && !defined('_JEXEC'))
    die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');
  include_once(JPATH_ROOT . "/components/com_vehiclemanager/compat.joomla1.5.php");

  if (!defined('DS'))
    define('DS', DIRECTORY_SEPARATOR);
  defined('_VM_IS_BACKEND') or define('_VM_IS_BACKEND', '1');

        
  $mainframe = $GLOBALS['mainframe'] = JFactory::getApplication(); // for 1.6
  $my = $GLOBALS['my'];
  $mosConfig_absolute_path = $GLOBALS['mosConfig_absolute_path'];

  if (get_magic_quotes_gpc()){
    function stripslashes_gpc(&$value){
        $value = stripslashes($value);
    }
    array_walk_recursive($_GET, 'stripslashes_gpc');
    array_walk_recursive($_POST, 'stripslashes_gpc');
    array_walk_recursive($_COOKIE, 'stripslashes_gpc');
    array_walk_recursive($_REQUEST, 'stripslashes_gpc');
  }

  jimport('joomla.html.pagination');
  jimport('joomla.application.pathway');
  jimport('joomla.filesystem.folder');

  $database = JFactory::getDBO();
  $GLOBALS['task'] = $task = protectInjectionWithoutQuote('task', ''); // mosGetParam($_REQUEST, 'task', '');
  require_once ($mosConfig_absolute_path . "/administrator/components/com_vehiclemanager/toolbar.vehiclemanager.php");

  $css = $mosConfig_live_site . '/components/com_vehiclemanager/admin_vehiclemanager.css';

  require_once ($mosConfig_absolute_path . "/components/com_vehiclemanager/vehiclemanager.class.php");
  require_once ($mosConfig_absolute_path . "/components/com_vehiclemanager/vehiclemanager.class.feature.php");
  require_once ($mosConfig_absolute_path . "/components/com_vehiclemanager/vehiclemanager.class.language.php");
  require_once ($mosConfig_absolute_path . "/administrator/components/com_vehiclemanager/vehiclemanager.html.php");
  require_once ($mosConfig_absolute_path . "/components/com_vehiclemanager/vehiclemanager.class.rent.php");
  require_once ($mosConfig_absolute_path . "/components/com_vehiclemanager/vehiclemanager.class.rent_request.php");
  require_once ($mosConfig_absolute_path . "/components/com_vehiclemanager/vehiclemanager.class.buying_request.php");
  require_once ($mosConfig_absolute_path . "/administrator/components/com_vehiclemanager/admin.vehiclemanager.class.impexp.php");
  require_once ($mosConfig_absolute_path . "/administrator/components/com_vehiclemanager/admin.vehiclemanager.class.conf.php");
  require_once($mosConfig_absolute_path . "/components/com_vehiclemanager/functions.php");

// vmLittleThings::remove_langs();
// vmLittleThings::loadConstVechicle();

  $GLOBALS['option'] = $option = mosGetParam($_REQUEST, 'option', 'com_vehiclemanager');
  $GLOBALS['vehiclemanager_configuration'] = $vehiclemanager_configuration;
  $GLOBALS['database'] = $database;
  $GLOBALS['my'] = $my;
  $GLOBALS['mosCondefine($item->constfig_absolute_path'] = $mosConfig_absolute_path;
   
  $vid = mosGetParam($_REQUEST, 'vid', array(0));
  $section = mosGetParam($_REQUEST, 'section', 'courses');

  if(isset ($_REQUEST["vid"]) AND isset ($_REQUEST["rent_from"]) AND isset($_REQUEST["rent_until"])
        AND isset($_REQUEST["special_price"])){
    $vid_ajax_rent = protectInjectionWithoutQuote("vid"); // $_REQUEST["vid"];
    $rent_from = protectInjectionWithoutQuote("rent_from"); // $_REQUEST["rent_from"];
    $rent_until = protectInjectionWithoutQuote("rent_until"); // $_REQUEST["rent_until"];
    $special_price = protectInjectionWithoutQuote("special_price"); // $_REQUEST["special_price"];
    $currency_spacial_price = protectInjectionWithoutQuote("currency_spacial_price"); // $_REQUEST["currency_spacial_price"];
    if(isset($_REQUEST["comment_price"]))
        $comment_price = protectInjectionWithoutQuote("comment_price"); // $_REQUEST["comment_price"];
    else
        $comment_price = '';
  }

  $vehiclemanager_configuration['debug'] = '0';

  if ($vehiclemanager_configuration['debug'] == '1')
  {
    echo "[debug mode] Task: " . $task . "<br /><pre>";
    print_r($_REQUEST);
    echo "</pre><hr /><br />";
  }
  if (isset($section) && $section == 'categories')
  {
    switch ($task) {
        case "edit" :
            editCategory($option, $vid[0]);
            break;

        case "add":
            editCategory($option, 0);
            break;

        case "cancel":
            cancelCategory();
            break;

        case "save":
        case "apply":
            saveCategory();
            break;

        case "remove":
            removeCategories($option, $vid);
            break;

        case "publish":
            publishCategories("com_vehiclemanager", $id, $vid, 1);
            break;

        case "unpublish":
            publishCategories("com_vehiclemanager", $id, $vid, 0);
            break;

        case "orderup":
            orderCategory($vid[0], -1);
            break;

        case "orderdown":
            orderCategory($vid[0], 1);
            break;

        case "accesspublic":
            accessCategory($vid[0], 0);
            break;

        case "accessregistered":
            accessCategory($vid[0], 1);
            break;

        case "accessspecial":
            accessCategory($vid[0], 2);
            break;

        case "show":
        default:
            showCategories();
    }
  } elseif ($section == 'featured_manager')
  {
    switch ($task) {
        case "edit" :
            editFeaturedManager($option, $vid[0]);
            break;

        case "add":
            editFeaturedManager($option, 0);
            break;

        case "cancel":
            cancelFeaturedManager();
            break;

        case "save":
            saveFeaturedManager();
            break;

        case "remove":
            removeFeaturedManager($option, $vid);
            break;

        case "publish":
            publishFeaturedManager("com_vehiclemanager", $id, $vid, 1);
            break;

        case "unpublish":
            publishFeaturedManager("com_vehiclemanager", $id, $vid, 0);
            break;
        case "addFeature":
            save_featured_category($option);
            showFeaturedManager($option);
            break;
        default:
            showFeaturedManager($option);
            break;
    }
  } elseif ($section == 'language_manager')
  {
    switch ($task) {
        case "edit" :
            editLanguageManager($option, $vid[0]);
            break;

        case "cancel":
            cancelLanguageManager();
            break;

        case "save":
            saveLanguageManager();
            break;

        default:
            showLanguageManager($option);
            break;
    }
  } else
  {
    switch ($task) {
        case "categories":
            echo "now work $section=='categories , this part not work";
            exit;
            mosRedirect("index.php?option=categories&section=com_vehiclemanager");
            break;

        case "add": // for 1.6
                    
            editVehicle($option, 0);
            break;
        
        case "clon_vm":   
            clonVehicle($vid,$option);
            break;
        case "ajax_rent_price":      
          $get_tmp = JRequest::get('get');
          if(isset ($get_tmp["bid"]) AND 
            isset ($get_tmp["rent_from"]) AND 
            isset($get_tmp["rent_until"]) AND 
            isset($get_tmp["special_price"])){

                $vid_ajax_rent = $get_tmp["vid"];
                $rent_from = $get_tmp["rent_from"];
                $rent_until = $get_tmp["rent_until"];
                $special_price = $get_tmp["special_price"];
                $currency_spacial_price = $get_tmp["currency_spacial_price"];
                if(isset($get_tmp["comment_price"]))
                    $comment_price = $get_tmp["comment_price"];
                else
                    $comment_price = '';
            }
             
            rentPrice($vid_ajax_rent,$rent_from,$rent_until,$special_price,
              $comment_price,$currency_spacial_price);
            break;
        
        case "edit":
            editVehicle($option, array_pop($vid));
            break;

        case "apply":
        case "save":            
            saveVehicle($option, $task);
            break;

        case "remove":
            removeVehicles($vid, $option);
            break;

        case "publish":
            publishVehicles($vid, 1, $option);
            break;

        case "unpublish":
            publishVehicles($vid, 0, $option);
            break;

        case "approve":
            approveVehicles($vid, 1, $option);
            break;

        case "unapprove":
            approveVehicles($vid, 0, $option);
            break;

        case "cancel":
            cancelVehicle($option);

            break;

  //***************   begin for manage reviews   ***********************
        case "manage_review":
            manage_review_s($option, "");
            break;

        case "publish_manage_review":
            publish_manage_review($vid[0], 1, $option);
            break;

        case "unpublish_manage_review":
            publish_manage_review($vid[0], 0, $option);
            break;

        case "delete_manage_review":
            delete_manage_review($option, $vid);
            manage_review_s($option, "");
            break;

        case "edit_manage_review":
            edit_manage_review($option, $vid);
            break;

        case "update_edit_manage_review":
            $title = protectInjectionWithoutQuote('title'); // mosGetParam($_POST, 'title');
            $comment = protectInjectionWithoutQuote('comment'); // mosGetParam($_POST, 'comment');
            $rating = protectInjectionWithoutQuote('rating'); // mosGetParam($_POST, 'rating');
            $vehicle_id = protectInjectionWithoutQuote('vehicle_id'); // mosGetParam($_POST, 'vehicle_id');
            $review_id = protectInjectionWithoutQuote('review_id'); // mosGetParam($_POST, 'review_id');
            update_review($title, $comment, $rating, $review_id);
            manage_review_s($option, "");
            break;

        case "cancel_edit_manage_review":
            manage_review_s($option, "");
            break;

        case "sorting_manage_review_numer":
            manage_review_s($option, "review_id");
            break;

        case "sorting_manage_review_title_vehicle":
            manage_review_s($option, "vehicle_title");
            break;

        case "sorting_manage_review_title_catigory":
            manage_review_s($option, "title_catigory");
            break;

        case "sorting_manage_review_title_review":
            manage_review_s($option, "title_review");
            break;

        case "sorting_manage_review_user_name":
            manage_review_s($option, "user_name");
            break;

        case "sorting_manage_review_date":
            manage_review_s($option, "date");
            break;

        case "sorting_manage_review_rating":
            manage_review_s($option, "rating");
            break;

        case "sorting_manage_review_published":
            manage_review_s($option, "published");
            break;
  //***************   end for manage reviews   *************************
        case "config":
            configure($option);
            break;

        case "config_save":
            configure_save_frontend($option);
            configure_save_backend($option);
            configure($option);
            break;

        case "rent":
            if (mosGetParam($_POST, 'save') == 1)
                saveRent($option, $vid); else
                rent($option, $vid);
            break;

        case "rent_history":
                rent_history($option, $vid);
            break;

        case "users_rent_history":
            users_rent_history($option, $vid);
            break;

        case "rent_requests":
            rent_requests($option, $vid);
            break;

        case "buying_requests":
            buying_requests($option);
            break;

        case "accept_rent_requests":
            accept_rent_requests($option, $vid);
            break;

        case "decline_rent_requests":
            decline_rent_requests($option, $vid);
            break;

        case "accept_buying_requests":
            accept_buying_requests($option, $vid);
            break;

        case "decline_buying_requests":
            decline_buying_requests($option, $vid);
            break;

        case "about" :
            HTML_vehiclemanager :: about();
            break;

        case "show_info":
            showInfo($option, $bid);
            break;

        case "rent_return":
            if (mosGetParam($_POST, 'save') == 1)
                saveRent_return($option, $vid);
            else
                rent_return($option, $vid);
            break;

        case "edit_rent":
            if (mosGetParam($_POST, 'save') == 1)
            {
                if (count($vid) > 1)
                {
                    echo "<script> alert('You must select only one item for edit'); window.history.go(-1); </script>\n";
                    exit;
                }
                saveRent($option, $vid, "edit_rent");
            } else
                edit_rent($option, $vid);
            break;

        case "delete_review":
            $ids = explode(',', $vid[0]);
            delete_review($option, $ids[1]);
            editVehicle($option, $ids[0]);
            break;

        case "edit_review":
            $ids = explode(',', $vid[0]);
            edit_review($option, $ids[1], $ids[0]);
            break;

        case "update_review":
            $title = mosGetParam($_POST, 'title');
            $comment = mosGetParam($_POST, 'comment');
            $rating = mosGetParam($_POST, 'rating');
            $vehicle_id = mosGetParam($_POST, 'vehicle_id');
            $review_id = mosGetParam($_POST, 'review_id');
            update_review($title, $comment, $rating, $review_id);
            editVehicle($option, $vehicle_id);
            break;

        case "cancel_review_edit":
            $vehicle_id = mosGetParam($_POST, 'vehicle_id');
            editVehicle($option, $vehicle_id);
            break;

       case "checkFile":
            checkFile();
        break;
        default:
            showVehicles($option);
            break;
    }
  }

  /**
  * HTML Class
  * Utility class for all HTML drawing classes
  * @desc class General HTML creation class. We use it for back/front ends.
  */
  class HTML
  {

    static function categoryParentList($id, $action, $options = array())
    {
        global $database;
        $list = vmLittleThings::categoryArray();
        $cat = new mainVehiclemanagerCategories($database); // for 1.6
        $cat->load($id);

        $this_treename = '';
        $childs_ids = Array();
        foreach ($list as $item) {
            if ($item->id == $cat->id || array_key_exists($item->parent_id, $childs_ids))
                $childs_ids[$item->id] = $item->id;
        }

        foreach ($list as $item) {
            if ($this_treename)
            {
                if ($item->id != $cat->id && strpos($item->treename, $this_treename) === false
                        && array_key_exists($item->id, $childs_ids) === false)
                {
                    $options[] = mosHTML::makeOption($item->id, $item->treename);
                }
            } else
            {
                if ($item->id != $cat->id)
                {
                    $options[] = mosHTML::makeOption($item->id, $item->treename);
                } else
                {
                    $this_treename = "$item->treename/";
                }
            }
        }

        $parent = null;
        $parent = mosHTML::selectList($options, 'parent_id', 'class="inputbox" size="1"', 'value', 'text', $cat->parent_id);

        return $parent;
    }

    static function imageList($name, &$active, $javascript = null, $directory = null)
    {
        global $mosConfig_absolute_path;
        if (!$javascript)
        {
            if (!is_dir(JPATH_ROOT . '/images/stories/'))
                mkdir(JPATH_ROOT . '/images/stories/', 0755);
            $javascript = "onchange=\"javascript:if (document.adminForm." . $name .
                    ".options[selectedIndex].value!='')    {document.imagelib.src='../images/stories/' + document.adminForm."
                    . $name . ".options[selectedIndex].value} else {document.imagelib.src='../images/blank.png'}\"";
        }
        if (!$directory)
        {
            $directory = '/images/stories';
        }

        $imageFiles = mosReadDirectory($mosConfig_absolute_path . $directory);
        $images = array(mosHTML::makeOption('', _VEHICLE_A_SELECT_IMAGE));
        foreach ($imageFiles as $file) {
            if (preg_match("/bmp|gif|jpg|jpeg|png/i", $file))
            {
                $images[] = mosHTML::makeOption($file);
            }
        }

        $images = mosHTML::selectList($images, $name, 'id="' . $name . '" class="inputbox" size="1" '
                        . $javascript, 'value', 'text', $active);
        return $images;
    }

  }

  function showCategories(){
    global $database, $my, $option, $menutype, $mainframe, $mosConfig_list_limit, $acl;
    $section = "com_vehiclemanager";
    $groups = get_group_children_vm();
    $sectionid = $mainframe->getUserStateFromRequest("sectionid{$section}{$section}", 'sectionid', 0);
    $limit = $mainframe->getUserStateFromRequest("viewlistlimit", 'limit', $mosConfig_list_limit);
    $limitstart = $mainframe->getUserStateFromRequest("view{$section}limitstart", 'limitstart', 0);
    $levellimit = $mainframe->getUserStateFromRequest("view{$option}limit$menutype", 'levellimit', 10);
    $language_owner = $mainframe->getUserStateFromRequest("language{$option}", 'language', '-1'); 
    $where = '';
    if ($language_owner != '0' and $language_owner != '*'and $language_owner != '-1' ){
      $where = " AND c.language='$language_owner'"; 
    }
    $query = "SELECT  c.*,'0' as cc, c.checked_out as checked_out_contact_category, 
              c.parent_id as parent, u.name AS editor, c.params, COUNT(vc.id) AS cc"
            . "\n FROM #__vehiclemanager_main_categories AS c"
            . "\n LEFT JOIN #__vehiclemanager_categories AS vc ON vc.idcat=c.id"
            . "\n LEFT JOIN #__users AS u ON u.id = c.checked_out"
            . "\n WHERE c.section='$section'"
            . "\n AND c.published !=-2"
            . $where
            . "\n GROUP BY c.id"
            . "\n ORDER BY parent DESC, ordering";
    $database->setQuery($query);
    $rows = $database->loadObjectList();
    if (version_compare(JVERSION, "3.0.0", "lt")){
      $curdate = strtotime(JFactory::getDate()->toMySQL());
    } else{
      $curdate = strtotime(JFactory::getDate()->toSQL());
    }
    foreach ($rows as $row) {
      $check = strtotime($row->checked_out_time);
      $remain = 7200 - ($curdate - $check);
      if (($remain <= 0) && ($row->checked_out != 0)){
        $item = new mainVehiclemanagerCategories($database);
        $item->checkin($row->id);
      }
    }
    foreach ($rows as $k => $v) {
      $rows[$k]->ncourses = 0;
      foreach ($rows as $k1 => $v1)
        if ($v->id == $v1->parent)
          $rows[$k]->cc +=$v1->cc;
      $aa = $v->cc;
      $rows[$k]->nvehicle = ($aa == 0) ?
           "-" : 
          "<a href=\"?option=com_vehiclemanager&section=vehicle&catid=" . $v->id . "\">" . ($aa) . "</a>";
      $curgroup = "";
      $ss = explode(',', $v->params);
      foreach ($ss as $s) {
        if ($s == '')
          $s = '-2';
        $curgroup[] = $groups[$s];
      }
      $rows[$k]->groups = implode(', ', $curgroup);
    }

    if ($database->getErrorNum()){
      echo $database->stderr();
      return false;
    }
    // establish the hierarchy of the categories
    $children = array();
    // first pass - collect children
    foreach ($rows as $v) {
      $pt = $v->parent;
      $list = @$children[$pt] ? $children[$pt] : array();
      array_push($list, $v);
      $children[$pt] = $list;
    }
    // second pass - get an indent list of the items
    $list = vmLittleThings::vehicleManagerTreeRecurse(0, '', array(), $children, max(0, $levellimit - 1));
    $total = count($list);
    $pageNav = new JPagination($total, $limitstart, $limit); // for J 1.6
    $levellist = mosHTML::integerSelectList(1, 20, 1, 'levellimit',
                           'size="1" onchange="document.adminForm.submit();"', $levellimit);
    // slice out elements based on limits
    $list = array_slice($list, $pageNav->limitstart, $pageNav->limit);
    $count = count($list);
    // number of Active Items
    // get list of sections for dropdown filter
    $javascript = 'onchange="document.adminForm.submit();"';
    if (version_compare(JVERSION, "3.0.0", "lt"))
      $lists['sectionid'] = mosAdminMenus::SelectSection('sectionid', $sectionid, $javascript);
    $query = "DELETE  FROM #__vehiclemanager_categories 
              WHERE iditem NOT IN (select id from #__vehiclemanager_vehicles  ) ";
    $database->setQuery($query);
    $cat1 = $database->query();
    $language = array();
    $selectlanguage = "SELECT `language` FROM `#__vehiclemanager_vehicles` WHERE language <> '*' GROUP BY language ";
    $database->setQuery($selectlanguage);
    $languages = $database->loadObjectList();
    $language_list[]= mosHTML :: makeOption('0', _VEHICLE_MANAGER_LABEL_SELECT_LANGUAGE);    
    foreach ($languages as $language) {
      $language_list[] = mosHTML::makeOption($language->language, $language->language);  
    }
    $language = mosHTML :: selectList($language_list, 'language', 
                      'class="inputbox input-medium" size="1" onchange="document.adminForm.submit();"',
                       'value', 'text', $language_owner);

    HTML_Categories::show($list, $my->id, $pageNav, $lists,$language, 'other');
  }

  function editCategory($section = '', $uid = 0)
  {
    global $database, $my, $acl;
    global $mosConfig_absolute_path, $mosConfig_live_site;

    $type = mosGetParam($_REQUEST, 'type', '');
    $redirect = mosGetParam($_POST, 'section', '');

    $row = new mainVehiclemanagerCategories($database); // for 1.6
    // load the row from the db table
    $row->load($uid);
    // fail if checked out not by 'me'
    if ($row->checked_out && $row->checked_out <> $my->id)
    {
        mosRedirect('index.php?option=com_vehiclemanager&task=categories', 'The category ' . $row->title . ' is currently being edited by another administrator');
    }

    if ($uid)
    {
        // existing record
        $row->checkout($my->id);
        // code for Link Menu
    } else
    {
        // new record
        $row->section = $section;
        $row->published = 1;
    }
    
    /*****************************************************************************************************************************/
    $associateArray = array();
    if($row->id){
        $query = "SELECT lang_code FROM `#__languages` WHERE 1";
        $database->setQuery($query);
        $allLanguages =  $database->loadColumn(); 
     
        $query = "SELECT id,language,title FROM `#__vehiclemanager_main_categories` WHERE 1";
        $database->setQuery($query);
        $allInCategories =  $database->loadObjectlist(); 

        $query = "select associate_category from #__vehiclemanager_main_categories where id =".$row->id;
        $database->setQuery($query);
        $categoryAssociateCategory =  $database->loadResult(); 

        if(!empty($categoryAssociateCategory)){
            $categoryAssociateCategory = unserialize($categoryAssociateCategory);
        }else{
            $categoryAssociateCategory = array();
        }

        foreach ($allLanguages as &$oneLang) {
            $associate_category = array();
            $associate_category[] = mosHtml::makeOption(0, 'select'); 
            $i = 0;
       
            foreach($allInCategories as &$oneCat){
                if($oneLang == $oneCat->language && $oneCat->id != $row->id){
                    $associate_category[] = mosHtml::makeOption(($oneCat->id), $oneCat->title);
                } 
            } 
       
            if($row->language != $oneLang){
                $associate_category_list = mosHTML :: selectList($associate_category, 'language_associate_category', 'class="inputbox" size="1"', 'value', 'text', ""); 
            }else{
                $associate_category_list = null;
            }
       
            $associateArray[$oneLang]['list'] = $associate_category_list;
       
            if(isset($categoryAssociateCategory[$oneLang])){
                $associateArray[$oneLang]['assocId'] = $categoryAssociateCategory[$oneLang];
            }else{
                $associateArray[$oneLang]['assocId'] = 0;
            }
        }
    }
    /*****************************************************************************************************************************/

    // make order list
    $order = array();

    $database->setQuery("SELECT COUNT(*) FROM #__vehiclemanager_main_categories WHERE section='$row->section'");
    $max = intval($database->loadResult()) + 1;

    for ($i = 1; $i < $max; $i++)
        $order[] = mosHTML::makeOption($i);
    // build the html select list for ordering
    $query = "SELECT ordering AS value, title AS text"
            . "\n FROM #__vehiclemanager_main_categories"
            . "\n WHERE section = '$row->section'"
            . "\n ORDER BY ordering";
    $lists = array();

    $lists['ordering'] = version_compare(JVERSION, '3.0', 'ge') ? NUll : mosAdminMenus::SpecificOrdering($row, $uid, $query);
    // build the select list for the image positions
    $active = ($row->image_position ? $row->image_position : 'left');
    $lists['image_position'] = version_compare(JVERSION, '3.0', 'ge') ? NUll : mosAdminMenus::Positions('image_position', $active, null, 0, 0);
    // Imagelist
    $lists['image'] = HTML::imageList('image', $row->image);
    // build the html select list for the group access
    $lists['access'] = version_compare(JVERSION, '3.0', 'ge') ? NUll : mosAdminMenus::Access($row);
    // build the html radio buttons for published
    $lists['published'] = mosHTML::yesnoRadioList('published', 'class="inputbox"', $row->published);
    // build the html select list for paraent item
    $options = array();
    $options[] = mosHTML::makeOption('0', _VEHICLE_A_SELECT_TOP);

    $lists['parent'] = HTML::categoryParentList($row->id, "", $options);

    //***********access category
    $gtree = get_group_children_tree_vm();

    $f = "";
    if (trim($row->params) == '')
        $row->params = '-2';
    $s = explode(',', $row->params);
    for ($i = 0; $i < count($s); $i++)
        $f[] = mosHTML::makeOption($s[$i]);
    $lists['category']['registrationlevel'] = mosHTML::selectList($gtree, 'category_registrationlevel[]', 'size="" multiple="multiple"', 'value', 'text', $f);
    //********end access category

    $query = "SELECT lang_code, title FROM #__languages";
    $database->setQuery($query);
    $languages = $database->loadObjectList();

    $languages_row[] = mosHTML::makeOption('*', 'All');
    foreach ($languages as $language) {
        $languages_row[] = mosHTML::makeOption($language->lang_code, $language->title);
    }

    $lists['languages'] = mosHTML :: selectList($languages_row, 'language', 'class="inputbox" size="1"', 'value', 'text', $row->language);
    HTML_Categories::edit($row, $section, $lists, $redirect, $associateArray);
  }

  function saveCategory(){
    global $database;
    $row = new mainVehiclemanagerCategories($database); // for 1.6
    $post = JRequest::get('post', JREQUEST_ALLOWHTML);
  /***************************************************************************************************/
    $currentId = $post['id'];
    if($currentId){
        $i = 1;
        $assocArray = array();
        $assocCategoryId = array();
        while(isset($post['associate_category'.$i])){
            $langAssoc = $post['associate_category_lang'.$i];
            $valAssoc = $post['associate_category'.$i];
            $assocArray[$langAssoc] = $valAssoc;
            if($valAssoc){
              $assocCategoryId[] = $valAssoc;  
            }
            $i++;
        }
        $currentLang = $post['language'];
        $assocArray[$currentLang] = $currentId;
        $assocStr = serialize($assocArray);
            $query = "SELECT `associate_category` 
                        FROM #__vehiclemanager_main_categories 
                        WHERE `id` = ".$currentId."";
            $database->setQuery($query);
            $oldAssociate = $database->loadResult(); 
            $oldAssociateArray = unserialize($oldAssociate);
            if($oldAssociateArray){
                foreach ($oldAssociateArray as $key => $value) {
                    if($value && !isset($assocCategoryId[$value])){
                        $assocCategoryId[] = $value;                    
                    }
                }    
            }
            if(!isset($assocCategoryId[$currentId])){
                $assocCategoryId[] = $currentId;                    
            }
            $idToChange = implode(',' , $assocCategoryId);   
          if(count($idToChange) && !empty($idToChange)){  
            $query = "UPDATE #__vehiclemanager_main_categories 
                        SET `associate_category`='".$assocStr."' 
                        WHERE `id` in (".$idToChange.")";
            $database->setQuery($query);
            $database->query();       
        }       
    }
  /***************************************************************************************************/
    $params2 = new stdClass();
    $params2->alone_category = $post['alone_category2'];
    $params2->view_vehicle = $post['view_vehicle'];
    $post['params2'] = serialize($params2);
    if (!$row->bind($post)){
      echo "<script> alert('" . $row->getError() . "'); window.history.go(-1); </script>\n";
      exit();
    }
    $row->section = 'com_vehiclemanager';
    $row->parent_id = $_REQUEST['parent_id'];
  //****set access level
    $row->params = implode(',', mosGetParam($_POST, 'category_registrationlevel', ''));
  //****end set access level
    if (!$row->check()){
      echo "<script> alert('" . $row->getError() . "'); window.history.go(-1); </script>\n";
      exit();
    }
    if (!$row->store()){
      echo "<script> alert('" . $row->getError() . "'); window.history.go(-1); </script>\n";
      exit();
    }
    $row->checkin();
    $row->updateOrder("section='com_vehiclemanager' AND parent_id='$row->parent_id'");
    if($_REQUEST['task'] == 'apply'){
      mosRedirect('index.php?option=com_vehiclemanager&section=categories&task=edit&vid[]='.$row->id);
    }
    mosRedirect('index.php?option=com_vehiclemanager&section=categories');
  }

  //this function check - is exist vehicles in this folder and folders under this category 
  function is_exist_curr_and_subcategory_vehicles($catid)
  {
    global $database, $my;

    $query = "SELECT *, COUNT(a.id) AS numlinks FROM #__vehiclemanager_main_categories AS cc"
            . "\n LEFT JOIN #__vehiclemanager_vehicles AS a ON a.catid = cc.id"
            . "\n WHERE a.published='1' AND a.approved='1' AND section='com_vehiclemanager' AND cc.id='$catid' AND cc.published='1' AND cc.access <= '$my->gid'"
            . "\n GROUP BY cc.id"
            . "\n ORDER BY cc.ordering";
    $database->setQuery($query);
    $categories = $database->loadObjectList();

    if (count($categories) != 0)
        return true;

    $query = "SELECT id "
            . "FROM #__vehiclemanager_main_categories AS cc "
            . " WHERE section='com_vehiclemanager' AND parent_id='$catid' AND published='1' AND access<='$my->gid'";
    $database->setQuery($query);
    $categories = $database->loadObjectList();

    if (count($categories) == 0)
        return false;

    foreach ($categories as $k) {
        if (is_exist_curr_and_subcategory_vehicles($k->id))
            return true;
    }
    return false;
  }

  //end function


  function removeCategoriesFromDB($cid)
  {
    global $database, $my;

    $query = "SELECT id  "
            . "FROM #__vehiclemanager_main_categories AS cc "
            . " WHERE section='com_vehiclemanager' AND parent_id = '$cid' AND published='1' AND access<='$my->gid'";
    $database->setQuery($query);
    $categories = $database->loadObjectList();

    if (count($categories) != 0)
    {
        //delete child
        foreach ($categories as $k) {
            removeCategoriesFromDB($k->id);
        }
    }

    $sql = "DELETE FROM #__vehiclemanager_main_categories WHERE id = $cid ";
    $database->setQuery($sql);
    $database->query();
  }

  /**
  * Deletes one or more categories from the categories table
  *
  * @param string $ The name of the category section
  * @param array $ An array of unique category id numbers
  */
  function removeCategories($section, $cid)
  {
    global $database;

    if (count($cid) < 1)
    {
        echo "<script> alert('Select a category to delete'); window.history.go(-1);</script>\n";
        exit;
    }

    foreach ($cid as $catid) {
        if (is_exist_curr_and_subcategory_vehicles($catid))
        {
            echo "<script> alert('Some category or subcategory from yours select contain vehicles. \\n Please remove vehicles first!'); window.history.go(-1); </script>\n";
            exit;
        }
    }

    foreach ($cid as $catid)
        removeCategoriesFromDB($catid);

    $msg = (count($err) > 1 ? "Categories " : _VEHICLE_CATEGORIES_NAME . " ") . _VEHICLE_DELETED;
    mosRedirect('index.php?option=com_vehiclemanager&section=categories&mosmsg=' . $msg);
  }

  /**
  * Publishes or Unpublishes one or more categories
  * 
  * @param string $ The name of the category section
  * @param integer $ A unique category id (passed from an edit form)
  * @param array $ An array of unique category id numbers
  * @param integer $ 0 if unpublishing, 1 if publishing
  * @param string $ The name of the current user
  */
  function publishCategories($section, $categoryid = null, $cid = null, $publish = 1)
  {
    global $database, $my;

    if (!is_array($cid))
        $cid = array();
    if ($categoryid)
        $cid[] = $categoryid;

    if (count($cid) < 1)
    {
        $action = $publish ? _PUBLISH : _DML_UNPUBLISH;
        echo "<script> alert('" . _DML_SELECTCATTO . " $action'); window.history.go(-1);</script>\n";
        exit;
    }

    $cids = implode(',', $cid);

    $query = "UPDATE #__vehiclemanager_main_categories SET published='$publish'"
            . "\nWHERE id IN ($cids) AND (checked_out=0 OR (checked_out='$my->id'))";
    $database->setQuery($query);
    if (!$database->query())
    {
        echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
        exit();
    }

    if (count($cid) == 1)
    {
        $row = new mainVehiclemanagerCategories($database); // for 1.6
        $row->checkin($cid[0]);
    }
    mosRedirect('index.php?option=com_vehiclemanager&section=categories');
  }

  /**
  * Cancels an edit operation
  *
  * @param string $ The name of the category section
  * @param integer $ A unique category id
  */
  function cancelCategory()
  {
    global $database;
    $row = new mainVehiclemanagerCategories($database); // for 1.6
    $row->bind($_POST);
    $row->checkin();
    mosRedirect('index.php?option=com_vehiclemanager&section=categories');
  }

  /**
  * Moves the order of a record
  *
  * @param integer $ The increment to reorder by
  */
  function orderCategory($uid, $inc)
  {
    global $database;
    $row = new mainVehiclemanagerCategories($database); //for 1.6
    $row->load($uid);
    if ($row->ordering == 1 && $inc == -1)
        mosRedirect('index.php?option=com_vehiclemanager&section=categories');
    $new_order = $row->ordering + $inc;

    //change ordering - for other element
    $query = "UPDATE #__vehiclemanager_main_categories SET ordering='" . ($row->ordering) . "'"
            . "\nWHERE parent_id = $row->parent_id and ordering=$new_order";
    $database->setQuery($query);
    $database->query();

    //change ordering - for this element
    $query = "UPDATE #__vehiclemanager_main_categories SET ordering='" . $new_order . "'"
            . "\nWHERE id = $uid";
    $database->setQuery($query);
    $database->query();

    mosRedirect('index.php?option=com_vehiclemanager&section=categories');
  }

  /**
  * changes the access level of a record
  *
  * @param integer $ The increment to reorder by
  */
  function accessCategory($uid, $access)
  {
    global $database;
    $row = new mainVehiclemanagerCategories($database); // for 1.6
    $row->load($uid);
    $row->access = $access;
    if (!$row->check())
        return $row->getError();
    if (!$row->store())
        return $row->getError();
    mosRedirect('index.php?option=com_vehiclemanager&section=categories');
  }

  function update_review($title, $comment, $rating, $review_id)
  {
    global $database;

    $review = new mosVehicleManager_review($database);
    $review->load($review_id);

    if (!$review->bind($_POST))
    {
        echo "<script> alert('" . $review->getError() . "'); window.history.go(-1); </script>\n";
        exit;
    }

    if (!$review->check())
    {
        echo "<script> alert('" . $review->getError() . "'); window.history.go(-1); </script>\n";
        exit;
    }

    if (!$review->store())
    {
        echo "<script> alert('" . $review->getError() . "'); window.history.go(-1); </script>\n";
        exit;
    }
  }

  function edit_review($option, $review_id, $vehicle_id)
  {
    global $database;
    $database->setQuery("SELECT * FROM #__vehiclemanager_review WHERE id=" . $review_id . " ");
    $review = $database->loadObjectList();
    echo $database->getErrorMsg();
    HTML_vehiclemanager :: edit_review($option, $vehicle_id, $review);
  }

  /*
  * Function for delete coment
  * (comment for every vehicle)
  * in database.
  */

  function delete_review($option, $id)
  {
    global $database;
    $database->setQuery("DELETE FROM #__vehiclemanager_review WHERE #__vehiclemanager_review.id=" . $id . ";");
    $database->query();
    echo $database->getErrorMsg();
  }

  //*************************************************************************************************************
  //*********************************   begin for manage reviews   **********************************************
  //*************************************************************************************************************
  function delete_manage_review($option, $id)
  {
    global $database;
    for ($i = 0; $i < count($id); $i++) {
        $database->setQuery("DELETE FROM #__vehiclemanager_review WHERE #__vehiclemanager_review.id=" . $id[$i] . ";");
        $database->query();
        echo $database->getErrorMsg();
    }
  }

  function edit_manage_review($option, $review_id)
  {
    global $database;
    if (count($review_id) > 1)
    {
        echo "<script> alert('Please select one review for edit!!!'); window.history.go(-1); </script>\n";
    } else
    {
        $database->setQuery("SELECT * FROM #__vehiclemanager_review WHERE id=" . $review_id[0] . " ");
        $review = $database->loadObjectList();
        echo $database->getErrorMsg();
        HTML_vehiclemanager :: edit_manage_review($option, $review);
    }
  }

  //*************************************************************************************************************
  //*********************************   end for manage reviews   ************************************************
  //*************************************************************************************************************


  function showInfo($option, $vid)
  {
    if (is_array($vid) && count($vid) > 0)
        $vid = $vid[0];
    echo "Test: " . $vid;
  }

  function rent_requests($option, $vid)
  {
    global $database, $mainframe, $mosConfig_list_limit;

    $limit = $mainframe->getUserStateFromRequest("viewlistlimit", 'limit', $mosConfig_list_limit);
    $limitstart = $mainframe->getUserStateFromRequest("view{$option}limitstart", 'limitstart', 0);

    $database->setQuery("SELECT count(*) FROM #__vehiclemanager_vehicles AS a" .
            "\nLEFT JOIN #__vehiclemanager_rent_request AS l" .
            "\nON l.fk_vehicleid = a.id" .
            "\nWHERE l.status = 0");
    $total = $database->loadResult();
    echo $database->getErrorMsg();


    $pageNav = new JPagination($total, $limitstart, $limit); // for J 1.6

    $database->setQuery("SELECT * FROM #__vehiclemanager_vehicles AS a" .
            "\nLEFT JOIN #__vehiclemanager_rent_request AS l" .
            "\nON l.fk_vehicleid = a.id" .
            "\nWHERE l.status = 0" .
            "\nORDER BY l.id DESC" .
            "\nLIMIT $pageNav->limitstart,$pageNav->limit;");
    $rent_requests = $database->loadObjectList();
    echo $database->getErrorMsg();
    $total = $database->loadResult();

    $query = "SELECT fk_vehicleid FROM #__vehiclemanager_rent_request";
    $database->setQuery($query);
    $v_associated = $database->loadResult();
    $title_assoc = array();
    if($v_associated){
        $assoc_veh = getAssociateVehicle($v_associated);
        if($assoc_veh){
          $database->setQuery("SELECT a.vtitle FROM #__vehiclemanager_vehicles AS a" .
            "\nLEFT JOIN #__vehiclemanager_rent_request AS l ON l.fk_vehicleid = a.id" .
            "\nWHERE a.id in ($assoc_veh)");
          $title_assoc = $database->loadObjectList();
        }
    }
    
    HTML_vehiclemanager :: showRequestRentVehicles($option, $rent_requests, $v_associated, $title_assoc, $pageNav);
  }

  function buying_requests($option)
  {   
    global $database, $mainframe, $mosConfig_list_limit;
    $limit = $mainframe->getUserStateFromRequest("viewlistlimit", 'limit', $mosConfig_list_limit);
    $limitstart = $mainframe->getUserStateFromRequest("view{$option}limitstart", 'limitstart', 0);

    $database->setQuery("SELECT count(*) FROM #__vehiclemanager_vehicles AS a" .
            "\nLEFT JOIN #__vehiclemanager_buying_request AS s" .
            "\nON s.fk_vehicleid = a.id" .
            "\nWHERE s.status = 0");
    $total = $database->loadResult();
    echo $database->getErrorMsg();

    $pageNav = new JPagination($total, $limitstart, $limit); // for J 1.6

    $database->setQuery("SELECT * FROM #__vehiclemanager_vehicles AS a" .
            "\nLEFT JOIN #__vehiclemanager_buying_request AS s" .
            "\nON s.fk_vehicleid = a.id" .
            "\nWHERE s.status = 0" .
            "\nORDER BY s.id DESC" .
            "\nLIMIT $pageNav->limitstart,$pageNav->limit;");
    $buy_requests = $database->loadObjectList();
    echo $database->getErrorMsg();

    HTML_vehiclemanager ::showRequestBuyingVehicles($option, $buy_requests, $pageNav);
  }


function checkFile() {
    $path = $_GET["path"];
    $filename = basename($_GET["file"]);
    $file = $path . $filename;
    if (file_exists($file)) {
        echo "The file with such name already is!";
    } else {
        echo "";
    }
}

  /**
  * Compiles a list of records
  * @param database - A database connector object
  * select categories
  */
  function showVehicles($option)
  {
    global $database, $mainframe, $mosConfig_list_limit;
    
    $limit = $mainframe->getUserStateFromRequest("viewlistlimit", 'limit', $mosConfig_list_limit);
    $limitstart = $mainframe->getUserStateFromRequest("view{$option}limitstart", 'limitstart', 0);
    $catid = $mainframe->getUserStateFromRequest("catid{$option}", 'catid', '-1'); //old 0
    $language_owner = $mainframe->getUserStateFromRequest("language{$option}", 'language', '-1'); 
    $rent = $mainframe->getUserStateFromRequest("rent{$option}", 'rent', '-1'); //add nik
    $pub = $mainframe->getUserStateFromRequest("pub{$option}", 'pub', '-1'); //add nik
    $owner = $mainframe->getUserStateFromRequest("owner{$option}", 'owner', '-1'); //add nik
     
    $search = $mainframe->getUserStateFromRequest("search{$option}", 'search', '');
    $where = array();

    if ($rent == "rent")
    {
        array_push($where, "a.fk_rentid <> 0");
    } else if ($rent == "not_rent")
    {
        array_push($where, "a.fk_rentid = 0");
    }
    if ($pub == "pub")
    {
        array_push($where, "a.published = 1");
    } else if ($pub == "not_pub")
    {
        array_push($where, "a.published = 0");
    }
    if ($owner != -1)
        array_push($where, "a.owneremail = '$owner'");
    if ($catid > 0)
    {
        array_push($where, "vc.idcat='$catid'");
    }
    if ($language_owner != '0' and $language_owner != '*'and $language_owner != '-1' )
    {
        array_push($where, "a.language='$language_owner'");        
    }
    if ($search)
    {

        array_push($where, "(LOWER(a.vtitle) LIKE '%$search%' OR " .
         " LOWER(a.vmodel) LIKE '%$search%' OR LOWER(a.vehicleid) LIKE '%$search%')");
    }

    $database->setQuery("SELECT count(*) FROM #__vehiclemanager_vehicles AS a" .
            "\nLEFT JOIN #__vehiclemanager_categories AS vc ON a.id=vc.iditem" .
            "\nLEFT JOIN #__vehiclemanager_rent AS l" .
            "\nON a.fk_rentid = l.id" .
            (count($where) ? "\nWHERE " . implode(' AND ', $where) : ""));

    $total = $database->loadResult();

    echo $database->getErrorMsg();

    $pageNav = new JPagination($total, $limitstart, $limit); // for J 1.6

    $selectstring = "SELECT a.*, GROUP_CONCAT(DISTINCT cc.title SEPARATOR ', ') AS category, 
            l.id as rentid, l.rent_from as rent_from, l.rent_return as rent_return,
            l.rent_until as rent_until, u.id AS uid, u.name AS editor, ue.name AS editor1, u.username AS owner_name" .
            "\nFROM #__vehiclemanager_vehicles AS a" .
            "\nLEFT JOIN #__vehiclemanager_categories AS vc ON vc.iditem = a.id" .
            "\nLEFT JOIN #__vehiclemanager_main_categories AS cc ON cc.id = vc.idcat" .
            "\nLEFT JOIN #__vehiclemanager_rent AS l ON l.fk_vehicleid = a.id  and l.rent_return is null " .
            "\nLEFT JOIN #__users AS u ON u.id = a.owner_id" .
            "\nLEFT JOIN #__users AS ue ON ue.id = a.checked_out" .
            (count($where) ? "\nWHERE " . implode(' AND ', $where) : "") .
            "\nGROUP BY a.id" .
            "\nORDER BY a.vtitle " .
            "\nLIMIT $pageNav->limitstart,$pageNav->limit;";
    $database->setQuery($selectstring);
    $rows = $database->loadObjectList();
    if ($database->getErrorNum())
    {
        echo $database->stderr();
        return false;
    }

    if (version_compare(JVERSION, "3.0.0", "lt"))
    {
        $curdate = strtotime(JFactory::getDate()->toMySQL());
    } else
    {
        $curdate = strtotime(JFactory::getDate()->toSQL());
    }
    foreach ($rows as $row) {
        $check = strtotime($row->checked_out_time);
        $remain = 7200 - ($curdate - $check);
        if (($remain <= 0) && ($row->checked_out != 0))
        {
            $item = new mosVehicleManager($database);
            $item->checkin($row->id);
        }
    }

    // get list of categories
    /*
     * select list treeSelectList
     */

    $categories[] = mosHTML :: makeOption('0', _VEHICLE_MANAGER_LABEL_SELECT_CATEGORIES);
    $categories[] = mosHTML :: makeOption('-1', _VEHICLE_MANAGER_LABEL_SELECT_ALL_CATEGORIES);
  //*************   begin add for sub category in select in manager vehicles   *************
    $options = $categories;
    $id = 0; //$categories_array;
    $list = vmLittleThings::categoryArray();

    $cat = new mainVehiclemanagerCategories($database); // for 1.6
    $cat->load($id);

    $this_treename = '';
    foreach ($list as $item) {
        if ($this_treename)
        {
            if ($item->id != $cat->id && strpos($item->treename, $this_treename) === false)
            {
                $options[] = mosHTML::makeOption($item->id, $item->treename);
            }
        } else
        {
            if ($item->id != $cat->id)
            {
                $options[] = mosHTML::makeOption($item->id, $item->treename);
            } else
            {
                $this_treename = "$item->treename/";
            }
        }
    }
   
    $clist = mosHTML::selectList($options, 'catid', 'class="inputbox input-medium" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $catid); //new nik edit
  //*****  end add for sub category in select in manager vehicles   **********
    
    $rentmenu[] = mosHTML :: makeOption('0', _VEHICLE_MANAGER_LABEL_SELECT_TO_RENT);
    $rentmenu[] = mosHTML :: makeOption('-1', _VEHICLE_MANAGER_LABEL_SELECT_ALL_RENT);
    $rentmenu[] = mosHTML :: makeOption('not_rent', _VEHICLE_MANAGER_LABEL_SELECT_NOT_RENT);
    $rentmenu[] = mosHTML :: makeOption('rent', _VEHICLE_MANAGER_LABEL_SELECT_RENT);

    $rentlist = mosHTML :: selectList($rentmenu, 'rent', 'class="inputbox input-medium" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $rent);

    $pubmenu[] = mosHTML :: makeOption('0', _VEHICLE_MANAGER_LABEL_SELECT_TO_PUBLIC);
    $pubmenu[] = mosHTML :: makeOption('-1', _VEHICLE_MANAGER_LABEL_SELECT_ALL_PUBLIC);
    $pubmenu[] = mosHTML :: makeOption('not_pub', _VEHICLE_MANAGER_LABEL_SELECT_NOT_PUBLIC);
    $pubmenu[] = mosHTML :: makeOption('pub', _VEHICLE_MANAGER_LABEL_SELECT_PUBLIC);

    $publist = mosHTML :: selectList($pubmenu, 'pub', 'class="inputbox input-medium" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $pub);

    $ownermenu[] = mosHTML :: makeOption('-1', _VEHICLE_MANAGER_LABEL_SELECT_ALL_USERS);
    $selectstring = "SELECT id,name,email FROM  #__users GROUP BY name ORDER BY id ";

    $database->setQuery($selectstring);
    $owner_list = $database->loadObjectList();

    if ($database->getErrorNum())
    {
        echo $database->stderr();
        return false;
    }
    $i = 2;
    foreach ($owner_list as $item) {
        $ownermenu[$i] = mosHTML::makeOption($item->email, $item->name);
        $i++;
    }

    $ownerlist = mosHTML :: selectList($ownermenu, 'owner', 'class="inputbox input-medium" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $owner);
    
    $language = array();
    $selectlanguage = "SELECT `language` FROM `#__vehiclemanager_vehicles` WHERE language <> '*' GROUP BY language ";

    $database->setQuery($selectlanguage);
    $languages = $database->loadObjectList();
    $language_list[]= mosHTML :: makeOption('0', _VEHICLE_MANAGER_LABEL_SELECT_LANGUAGE);
    
    foreach ($languages as $language) {
        $language_list[] = mosHTML::makeOption($language->language, $language->language);
        
    }
    $language = mosHTML :: selectList($language_list, 'language', 'class="inputbox input-medium" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $language_owner);
    HTML_vehiclemanager :: showVehicles($option, $rows, $clist,$language, $ownerlist, $rentlist, $publist, $search, $pageNav);
  }

  /**
  * Compiles information to add or edit vehicles
  * @param integer bid The unique id of the record to edit (0 if new)
  * @param array option the current options
  */


  function editVehicle($option, $vid){ 
    global $database, $my, $mosConfig_live_site, $vehiclemanager_configuration, $mosConfig_absolute_path;   
    if (isset($_GET['mess'])){ // for 1.6
      // Vehicle is get from table throw the ID
      echo "<tt style='font-size:12px !important;'>" . $_GET['mess'] . "</tt>";
      if (isset($_GET['vid'])){ 
        $q = "SELECT id
              FROM `#__vehiclemanager_vehicles`
              WHERE  `vehicleid`= " . $_GET['vid'] . ";";
        $database->setQuery($q);
        $vid = $database->loadObjectList();
        if (isset($vid[0]->id))
          $vid = $vid[0]->id; // $vid - is exactly id, not an vehicleid from Vehicles table!             
      } else
        echo "<script>window.history.go(-1);</script>";
    }
    $vehicle = new mosVehicleManager($database);
    // load the row from the db table 
    $vehicle->load(intval($vid));
    $numeric_vehicleids = Array();
    if (empty($vehicle->vehicleid) 
          && $vehiclemanager_configuration['vehicleid']['auto-increment']['boolean'] == 1){
      $database->setQuery("select vehicleid from #__vehiclemanager_vehicles order by vehicleid");
      $vehicleids = $database->loadObjectList();
      foreach ($vehicleids as $vehicleid) {
        if (is_numeric($vehicleid->vehicleid)){
          $numeric_vehicleids[] = intval($vehicleid->vehicleid);
        }
      }
      if (count($numeric_vehicleids) > 0) {
        sort($numeric_vehicleids);
        $vehicle->vehicleid = $numeric_vehicleids[count($numeric_vehicleids) - 1] + 1;
      }
      else
        $vehicle->vehicleid = 1;
    }
  /**************************************************************************************************/
    $associateArray = array();
    if($vid){
    $associateArray = edit_vehicle_associate($vehicle);
    }
  /**************************************************************************************************/
    // get list of categories
    $categories = array();
    $query = "SELECT  id ,name, parent_id as parent"
            . "\n FROM #__vehiclemanager_main_categories"
            . "\n WHERE section='com_vehiclemanager'"
            . "\n ORDER BY parent_id, ordering";
    $database->setQuery($query);
    $rows = $database->loadObjectList();
    // establish the hierarchy of the categories
    $children = array();
    // first pass - collect children
    foreach ($rows as $v) {
      $pt = $v->parent;
      $list = @$children[$pt] ? $children[$pt] : array();
      array_push($list, $v);
      $children[$pt] = $list;
    }
    // second pass - get an indent list of the items
    $list = vmLittleThings::vehicleManagerTreeRecurse(0, '', array(), $children);
    foreach ($list as $i => $item) {
      $item->text = $item->treename;
      $item->value = $item->id;
      $list[$i] = $item;
    }
    $categories = array_merge($categories, $list);
    $vehicle->setCatIds();
    $clist = mosHTML :: selectList($categories, 'catid[]', 'class="inputbox" multiple',
                                                            'value', 'text', $vehicle->catid);
    //get Rating
    $retVal2 = mosVehicleManagerOthers :: getRatingArray();
    $rating = null;
    for ($i = 0, $n = count($retVal2); $i < $n; $i++) {
        $help = $retVal2[$i];
        $rating[] = mosHTML :: makeOption($help[0], $help[1]);
    }
    //delete vehicle?
    $help = str_replace($mosConfig_live_site, "", $vehicle->edok_link);
    $delete_edoc_yesno[] = mosHTML :: makeOption($help, _VEHICLE_MANAGER_YES);
    $delete_edoc_yesno[] = mosHTML :: makeOption('0', _VEHICLE_MANAGER_NO);
    $delete_edoc = mosHTML :: RadioList($delete_edoc_yesno, 'delete_edoc', 'class="inputbox"', '0',
                                                                                     'value', 'text');
    // fail if checked out not by 'me'
    if ($vehicle->checked_out && $vehicle->checked_out <> $my->id){
      mosRedirect("index.php?option=$option", _VEHICLE_MANAGER_IS_EDITED);
    }
    if ($vid){
      $vehicle->checkout($my->id);
    }else{
      // initialise new record
      $vehicle->published = 0;
      $vehicle->approved = 0;
    }
  //*****************************   begin for reviews **************************//
    $database->setQuery("select a.* from #__vehiclemanager_review a" .
            " WHERE a.fk_vehicleid=" . $vid . " ORDER BY date ;");
    $reviews = $database->loadObjectList();
  //**********************   end for reviews   *****************************//
    //Select list for vehicle type
    $vehicletype[] = mosHtml::makeOption(0, _VEHICLE_MANAGER_OPTION_SELECT);
    $vehicletype1 = explode(',', _VEHICLE_MANAGER_OPTION_VEHICLE_TYPE);
    $i = 1;
    foreach ($vehicletype1 as $vehicletype2) {
      $vehicletype[] = mosHtml::makeOption($i, $vehicletype2);
      $i++;
    }
    $vehicle_type_list = mosHTML :: selectList($vehicletype, 'vtype', 'class="inputbox" size="1"',
                                                             'value', 'text', $vehicle->vtype);
    //Select list for listing type
    $listing_type[] = mosHtml::makeOption(0, _VEHICLE_MANAGER_OPTION_SELECT);
    $listing_type[] = mosHtml::makeOption(1, _VEHICLE_MANAGER_OPTION_FOR_RENT);
    $listing_type[] = mosHtml::makeOption(2, _VEHICLE_MANAGER_OPTION_FOR_SALE);
    $listing_type_list = mosHTML :: selectList($listing_type, 'listing_type', 'class="inputbox" size="1"',
                                                              'value', 'text', $vehicle->listing_type);
    //Select list for price type
    $test[] = mosHtml::makeOption(0, _VEHICLE_MANAGER_OPTION_SELECT);
    $test1 = explode(',', _VEHICLE_MANAGER_OPTION_PRICE_TYPE);
    $i = 1;
    foreach ($test1 as $test2) {
      $test[] = mosHtml::makeOption($i, $test2);
      $i++;
    }
    $test_list = mosHTML :: selectList($test, 'price_type', 'class="inputbox" size="1"',
                                              'value', 'text', $vehicle->price_type);
    //Select list for vehicle condition
    $condition[] = mosHtml::makeOption(0, _VEHICLE_MANAGER_OPTION_SELECT);
    $condition1 = explode(',', _VEHICLE_MANAGER_OPTION_VEHICLE_CONDITION);
    $i = 1;
    foreach ($condition1 as $condition2) {
      $condition[] = mosHtml::makeOption($i, $condition2);
      $i++;
    }
    $condition_status_list = mosHTML :: selectList($condition, 'vcondition', 'class="inputbox" size="1"',
                                                               'value', 'text', $vehicle->vcondition);
    //Select list for listing status
    $listing_status[] = mosHtml::makeOption(0, _VEHICLE_MANAGER_OPTION_SELECT);
    $listing_status1 = explode(',', _VEHICLE_MANAGER_OPTION_LISTING_STATUS);
    $i = 1;
    foreach ($listing_status1 as $listing_status2) {
      $listing_status[] = mosHtml::makeOption($i, $listing_status2);
      $i++;
    }
    $listing_status_list = mosHTML :: selectList($listing_status, 'listing_status', 'class="inputbox" size="1"',
                                                                  'value', 'text', $vehicle->listing_status);
    //Select list for vehicle transmission
    $transmission[] = mosHtml::makeOption(0, _VEHICLE_MANAGER_OPTION_SELECT);
    $transmission1 = explode(',', _VEHICLE_MANAGER_OPTION_TRANSMISSION);
    $i = 1;
    foreach ($transmission1 as $transmission2) {
      $transmission[] = mosHtml::makeOption($i, $transmission2);
      $i++;
    }
    $transmission_type_list = mosHTML :: selectList($transmission, 'transmission', 'class="inputbox" size="1"',
                                                                   'value', 'text', $vehicle->transmission);
    //Select list for vehicle drive type
    $drivetype[] = mosHtml::makeOption(0, _VEHICLE_MANAGER_OPTION_SELECT);
    $drivetype1 = explode(',', _VEHICLE_MANAGER_OPTION_DRIVE_TYPE);
    $i = 1;
    foreach ($drivetype1 as $drivetype2) {
      $drivetype[] = mosHtml::makeOption($i, $drivetype2);
      $i++;
    }
    $drive_type_list = mosHTML :: selectList($drivetype, 'drive_type', 'class="inputbox" size="1"',
                                                                        'value', 'text', $vehicle->drive_type);
    //Select list for number of cylinder
    $numcylinder[] = mosHtml::makeOption(0, _VEHICLE_MANAGER_OPTION_SELECT);
    $numcylinder1 = explode(',', _VEHICLE_MANAGER_OPTION_NUMBER_OF_CYLINDERS);
    $i = 1;
    foreach ($numcylinder1 as $numcylinder2) {
      $numcylinder[] = mosHtml::makeOption($i, $numcylinder2);
      $i++;
    }
    $num_cylinder_list = mosHTML :: selectList($numcylinder, 'cylinder', 'class="inputbox" size="1"',
                                                                         'value', 'text', $vehicle->cylinder);
    //Select list for vehicle fuel type
    $fueltype[] = mosHtml::makeOption(0, _VEHICLE_MANAGER_OPTION_SELECT);
    $fueltype1 = explode(',', _VEHICLE_MANAGER_OPTION_FUEL_TYPE);
    $i = 1;
    foreach ($fueltype1 as $fueltype2) {
      $fueltype[] = mosHtml::makeOption($i, $fueltype2);
      $i++;
    }
    $fuel_type_list = mosHTML :: selectList($fueltype, 'fuel_type', 'class="inputbox" size="1"', 
                                                                    'value', 'text', $vehicle->fuel_type);
    //Select list for number of speed
    $numspeed[] = mosHtml::makeOption(0, _VEHICLE_MANAGER_OPTION_SELECT);
    $numspeed1 = explode(',', _VEHICLE_MANAGER_OPTION_NUMBER_OF_SPEEDS);
    $i = 1;
    foreach ($numspeed1 as $numspeed2) {
      $numspeed[] = mosHtml::makeOption($i, $numspeed2);
      $i++;
    }
    $num_speed_list = mosHTML :: selectList($numspeed, 'num_speed', 'class="inputbox" size="1"',
                                                                    'value', 'text', $vehicle->num_speed);
    //Select list for number of doors
    $numdoors[] = mosHtml::makeOption(0, _VEHICLE_MANAGER_OPTION_SELECT);
    $numdoors1 = explode(',', _VEHICLE_MANAGER_OPTION_NUMBER_OF_DOORS);
    $i = 1;
    foreach ($numdoors1 as $numdoors2) {
      $numdoors[] = mosHtml::makeOption($i, $numdoors2);
      $i++;
    }
    $num_doors_list = mosHTML :: selectList($numdoors, 'doors', 'class="inputbox" size="1"',
                                                                   'value', 'text', $vehicle->doors);
    $query = "SELECT main_img 
              FROM #__vehiclemanager_photos 
              WHERE fk_vehicleid='$vehicle->id' 
              ORDER BY img_ordering,id";
    $database->setQuery($query);
    $vehicle_temp_photos = $database->loadObjectList();
    foreach ($vehicle_temp_photos as $vehicle_temp_photo) {
      $vehicle_photos[] = array($vehicle_temp_photo->main_img, 
                vm_picture_thumbnail($vehicle_temp_photo->main_img,
                                 $vehiclemanager_configuration['foto']['high'], 
                                 $vehiclemanager_configuration['foto']['width']));
    }
    $query = "SELECT image_link 
              FROM #__vehiclemanager_vehicles 
              WHERE id='$vehicle->id'";
    $database->setQuery($query);
    $vehicle_photo = $database->loadResult();
    if ($vehicle_photo != '')
      $vehicle_photo = array($vehicle_photo, 
                vm_picture_thumbnail($vehicle_photo,
                                  $vehiclemanager_configuration['foto']['high'],
                                  $vehiclemanager_configuration['foto']['width']));  
    // Setting the resize parameters
    $makers = array();
    $opt[] = mosHtml::makeOption('', _VEHICLE_MANAGER_OPTION_SELECT);
    $opt[] = mosHtml::makeOption('other', _VEHICLE_MANAGER_LABEL_SELECT_OTHER);
    $temp = mosVehicleManagerOthers::getMakersArray();
    $makers = array_merge($makers, $temp[0]);
    foreach ($makers as $maker) {
      $opt[] = mosHtml::makeOption(trim($maker), trim($maker));
    }
    $nummaker = array_search($vehicle->maker, $temp[0]);
    foreach ($temp[1][$nummaker] as $model) {
      $opt1[] = mosHtml::makeOption(trim($model), trim($model));
    }
    $opt1[] = mosHtml::makeOption('other', _VEHICLE_MANAGER_LABEL_SELECT_OTHER);
    $currentmodel = $vehicle->vmodel;
    $maker = mosHTML :: selectList($opt, 'maker', 'class="inputbox" size="1" onchange=changedMaker(this)',
                                                                           'value', 'text', $vehicle->maker);
    if (in_array($currentmodel, $temp[1][$nummaker])){
      $modellist = mosHTML :: selectList($opt1, 'vmodel',
                 'class="inputbox" size="1" id="vmodel" type="text" onchange=changedModel(this) ', 'value', 'text', $vehicle->vmodel);
    }else{
      $modellist = '<input name="vmodel" id="vmodel" type="text" value="' . $vehicle->vmodel . '"/>';
    }
    $youtubeId = "";
///////////START check video/audio files\\\\\\\\\\\\\\\\\\\\\\
    $tracks = array();
    $videos = array();
    if (!empty($vehicle->id)) { 
      $database->setQuery("SELECT * FROM #__vehiclemanager_video_source WHERE fk_vehicle_id=" . $vehicle->id);
      $videos = $database->loadObjectList();
    }
    $youtube = new stdClass();
    for ($i = 0;$i < count($videos);$i++) {
      if (!empty($videos[$i]->youtube)) {
        $youtube->code = $videos[$i]->youtube;
        $youtube->id = $videos[$i]->id;
        break;
      }
    }
    if (!empty($vehicle->id)) { //check video file
      $database->setQuery("SELECT * FROM #__vehiclemanager_track_source WHERE fk_vehicle_id=" . $vehicle->id);
      $tracks = $database->loadObjectList();
    }
////////////////////////////////END check video/audio files \\\\\\\\\\\\\\\\\\
    if (trim($vehicle->id) != ""){
      $query = "SELECT id, price_from, price_to, special_price, comment_price, priceunit 
                FROM #__vehiclemanager_rent_sal 
                WHERE fk_vehiclesid='$vehicle->id' 
                ORDER BY id DESC";
      $database->setQuery($query);
      $vehicle_rent_sal = $database->loadObjectList();
    }
    $query = "SELECT * ";
    $query .= "FROM #__vehiclemanager_feature as f ";
    $query .= "WHERE f.published = 1 ";
    $query .= "ORDER BY f.categories";
    $database->setQuery($query);
    $vehicle_feature = $database->loadObjectList();
    for ($i = 0; $i < count($vehicle_feature); $i++) {
      $feature = "";
      if (!empty($vehicle->id)){
        $query = "SELECT id ";
        $query .= "FROM #__vehiclemanager_feature_vehicles ";
        $query .= "WHERE fk_featureid =" . $vehicle_feature[$i]->id . " AND fk_vehicleid =" . $vehicle->id;
        $database->setQuery($query);
        $feature = $database->loadResult();
        if ($feature){
          $vehicle_feature[$i]->check = 1;
        }
        else
          $vehicle_feature[$i]->check = 0;
      }else{
        $vehicle_feature[$i]->check = 0;
      }
    }
    $currencys = explode(';', $vehiclemanager_configuration['currency']);
    foreach ($currencys as $row) {
      if ($row != ''){
        $row = explode("=", $row);
        $currency_temp[] = mosHTML::makeOption($row[0], $row[0]);
      }
    }
    $owner_email = '';
    if ($vehicle->owner_id > 0) {
      $database->setQuery("SELECT email FROM #__users WHERE `id`= '" .$vehicle->owner_id."'");
      $www= $database->loadResult();
      if (strlen( $vehicle->owneremail) > 0 && $www !=  $vehicle->owneremail)
        $owner_email = $vehicle->owneremail;
      else 
        $owner_email = $www;
    } 
    if($vid){
    }else{
        $vehicle->owner_id = $my->id;
    }
    $currency = mosHTML :: selectList($currency_temp, 'priceunit', 'class="inputbox" size="1"', 'value', 'text', $vehicle->priceunit);
    $currency_spacial_price = mosHTML :: selectList($currency_temp, 'currency_spacial_price[]',
                                         'class="inputbox" size="1"', 'value', 'text', $vehicle->priceunit);
    $query = "SELECT lang_code, title 
              FROM #__languages";
    $database->setQuery($query);
    $languages = $database->loadObjectList();
    $languages_row[] = mosHTML::makeOption('*', 'All');
    foreach ($languages as $language) {
      $languages_row[] = mosHTML::makeOption($language->lang_code, $language->title);
    }
    $languages = mosHTML :: selectList($languages_row, 'language',
                                       'class="inputbox" size="1"', 'value', 'text', $vehicle->language);
    for($i=6;$i<=10;$i++){
    $extraOption = '';    
    $extraOption[] = mosHtml::makeOption(0, _VEHICLE_MANAGER_OPTION_SELECT);    
  $name = "_VEHICLE_MANAGER_EXTRA" . $i . "_SELECTLIST";
  $extra = explode(',', constant($name));	
  $j = 1;
  foreach($extra as $extr){
    $extraOption[] = mosHTML::makeOption($j, $extr);	 
    $j++;    
  }
  switch ($i) {
    case 6:
      $extraSelect = $vehicle->extra6;
      break;
    case 7:
      $extraSelect = $vehicle->extra7;
      break;
    case 8:
      $extraSelect = $vehicle->extra8;
      break;
    case 9:
      $extraSelect = $vehicle->extra9;
      break;
    case 10:
      $extraSelect = $vehicle->extra10;
      break;
  }
  $extra_list[] = mosHTML :: selectList($extraOption, 'extra' . $i,
                                       'class="inputbox" size="1"', 'value', 'text', $extraSelect);
  }
  HTML_vehiclemanager:: editVehicle($option, $vehicle, $clist, $ratinglist, $delete_edoc,$videos,$youtube, $tracks, $reviews,
          $test_list, $vehicle_type_list, $listing_status_list, $condition_status_list,
           $transmission_type_list, 
          $listing_type_list, $drive_type_list, $fuel_type_list, $num_speed_list, $num_cylinder_list, 
          $num_doors_list, $vehicle_photo, $vehicle_temp_photos, $vehicle_photos, $maker, $temp, $currentmodel, $modellist, 
          $vehicle_rent_sal, $vehicle_feature, $currency, $languages, $extra_list,$owner_email,
          $currency_spacial_price, $associateArray);
  }






  function getMonth($month)
  {

    switch ($month) {
        case 1:
            $smonth = JText::_('JANUARY');
            break;
        case 2:
            $smonth = JText::_('FEBRUARY');
            break;
        case 3:
            $smonth = JText::_('MARCH');
            break;
        case 4:
            $smonth = JText::_('APRIL');
            break;
        case 5:
            $smonth = JText::_('MAY');
            break;
        case 6:
            $smonth = JText::_('JUNE');
            break;
        case 7:
            $smonth = JText::_('JULY');
            break;
        case 8:
            $smonth = JText::_('AUGUST');
            break;
        case 9:
            $smonth = JText::_('SEPTEMBER');
            break;
        case 10:
            $smonth = JText::_('OCTOBER');
            break;
        case 11:
            $smonth = JText::_('NOVEMBER');
            break;
        case 12:
            $smonth = JText::_('DECEMBER');
            break;
    }

    return $smonth;
  }

  function guid()
  {
    if (function_exists('com_create_guid'))
    {
  return trim(com_create_guid(), '{}');

    } else
    {
        mt_srand((double) microtime() * 10000); //optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45); 
        $uuid = substr($charid, 0, 8) . $hyphen
                . substr($charid, 8, 4) . $hyphen
                . substr($charid, 12, 4) . $hyphen
                . substr($charid, 16, 4) . $hyphen
                . substr($charid, 20, 12);
        return $uuid;
    }
  }



  function  saveVehicle($option, $task){
    global $database, $my, $mosConfig_absolute_path, $mosConfig_live_site, $vehiclemanager_configuration, $config;
    //check how the other info should be provided
    $vehicle = new mosVehicleManager($database);
    $post = JRequest::get('post', JREQUEST_ALLOWHTML);
    if (!$vehicle->bind($post)){
      echo "<script> alert('" . $vehicle->getError() . "'); window.history.go(-1); </script>\n";
      exit();
    }
    // if there is no owner, get your id as owner_id
    if ($vehicle->owneremail == ''){ 
        $vehicle->owner_id = $my->id;               
    }else{
        $vehicle->owner_id = $post['owner_id'];
    }
  /*******Call function to Save changes for associated vehicles**********/
    save_vehicle_associate();
  /*********************************************/ 
    if ($_POST['edocument_Link'] != ''){
      $vehicle->edok_link = $_POST['edocument_Link'];
    }    
    //delete evehicle file if neccesary
    $delete_edoc = mosGetParam($_POST, 'delete_edoc', 0);
    if ($delete_edoc != '0'){
      $retVal = unlink($mosConfig_absolute_path . $delete_edoc);
      $vehicle->edok_link = "";
    }
    //storing e-vehicle
    $edfile = $_FILES['edoc_file'];
    //check if fileupload is correct
    if ($vehiclemanager_configuration['edocs']['allow']
        && intval($edfile['error']) > 0
        && intval($edfile['error']) < 4){
      echo "<script> alert('" . _VEHICLE_MANAGER_LABEL_EDOCUMENT_UPLOAD_ERROR .
      "'); window.history.go(-1); </script>\n";
      exit();
    } else if ($vehiclemanager_configuration['edocs']['allow']
          && intval($edfile['error']) != 4){
      $uploaddir = $mosConfig_absolute_path . $vehiclemanager_configuration['edocs']['location'];
      $file_new = $uploaddir . $_FILES['edoc_file']['name'];
      $ext = pathinfo($_FILES['edoc_file']['name'], PATHINFO_EXTENSION);
      $ext = strtolower($ext);
      $allowed_exts = explode(",", $vehiclemanager_configuration['allowed_exts']);
      if (!in_array($ext, $allowed_exts)){
        echo "<script> alert(' File ext. not allowed to upload! - " . $edfile['name'] . "'); window.history.go(-1); </script>\n";
        exit();
      }
      $file['type'] = $_FILES['edoc_file']['type'];
      $db = JFactory::getDbo();
      $db->setQuery("SELECT mime_type FROM #__vehiclemanager_mime_types WHERE `mime_ext` = " . $db->quote($ext). " and mime_type = " . $db->quote($file['type']) );
      $file_db_mime = $db->loadResult();
      if ($file_db_mime != $file['type']){
        echo "<script> alert(' File mime type not match file ext. - " . $edfile['name'] . "'); window.history.go(-1); </script>\n";
        exit();
      } 
      if (!copy($_FILES['edoc_file']['tmp_name'], $file_new)){
        echo "<script> alert('error: not copy'); window.history.go(-1); </script>\n";
        exit();
      } else {
        $vehicle->edok_link = $mosConfig_live_site . $vehiclemanager_configuration['edocs']['location'] . $edfile['name'];
      }
    }else{
      if ($_POST['edocument_Link'] != ''){
        $vehicle->edok_link = $_POST['edocument_Link'];
      }    
      //delete evehicle file if neccesary
      $delete_edoc = mosGetParam($_POST, 'delete_edoc', 0);
      if ($delete_edoc != '0'){
        $retVal = unlink($mosConfig_absolute_path . $delete_edoc);
        $vehicle->edok_link = "";
      }
    }
    if ($vehiclemanager_configuration['publish_on_add']['show']){
      $vehicle->published = 1;
    } else {
      $vehicle->published = 0;
    }
    if ($vehiclemanager_configuration['approve_on_add']['show']){
      $vehicle->approved = 1;
    } else {
      $vehicle->approved = 0;
    } 
    if (is_string($vehicle)){
      echo "<script> alert('" . $vehicle . "'); window.history.go(-1); </script>\n";
      exit();
    }
    $vehicle->date = date("Y-m-d H:i:s");
    if (!$vehicle->store()){
      echo "<script> alert('" . $vehicle->getError() . "'); window.history.go(-1); </script>\n";
      exit();
    }
    $vehicle->saveCatIds($vehicle->catid);
    $vehicle->checkin();
    $vehicle->updateOrder("catid='$vehicle->catid'");
    //save dynamic files in a folder 'photos'
    $uploaddir = $mosConfig_absolute_path . '/components/com_vehiclemanager/photos/';
    if($_REQUEST['idtrue']){
      $uploaddir = $mosConfig_absolute_path . '/components/com_vehiclemanager/photos/';    
      $code = guid();
      $vehicle_true_id=$_REQUEST['idtrue'];
      $query = "select main_img from #__vehiclemanager_photos WHERE fk_vehicleid='$vehicle_true_id' order by img_ordering,id";
      $database->setQuery($query);
      $vehicle_temp_photos = $database->loadObjectList();    
      $query = "select image_link from #__vehiclemanager_vehicles WHERE id='$vehicle_true_id' order by id";
      $database->setQuery($query);
      $vehicle_mail_photos = $database->loadObject();               
      function createNewName($name){ 
        $regExp = '/^[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{10}/';
        if(preg_match($regExp, $name)){  
            $substrName = createNewName(mb_substr($name, 36, strlen($name)));
        }else{ 
            $substrName = $name;
        }
        return $substrName;
      }
      $vehicle_mail_photos_clon = $code.createNewName($vehicle_mail_photos->image_link);            
      if (copy($uploaddir.$vehicle_mail_photos->image_link, $uploaddir.$vehicle_mail_photos_clon)){ 
        $database->setQuery("UPDATE #__vehiclemanager_vehicles SET image_link = '$vehicle_mail_photos_clon' WHERE id=" . $vehicle->id);
      }
      if (!$database->query()){                       
        echo "<script> alert('" . $database->getErrorMsg() . "');</script>\n";      
      }         
      foreach($vehicle_temp_photos as $val){
        $trueImg = $uploaddir.$val->main_img;
        $nameImgWithoutCode = createNewName($val->main_img);
        $file_name = $code.$nameImgWithoutCode;
        $clonImg = $uploaddir.$file_name;
        if (copy($trueImg, $clonImg)){                      
          $database->setQuery("INSERT INTO #__vehiclemanager_photos (fk_vehicleid, main_img) 
                              VALUES ( '$vehicle->id','$file_name')");
          if (!$database->query()){
            echo "<script> alert('" . $database->getErrorMsg() . "');</script>\n";
          }
        }
      }
      $file_new_url = str_replace($vehiclemanager_configuration['edocs']['location'], $vehiclemanager_configuration['edocs']['location'].$code, $_REQUEST['edocument_Link']);
      $file_name = explode($vehiclemanager_configuration['edocs']['location'], $_REQUEST['edocument_Link']);        
      $file_new = $mosConfig_absolute_path.$vehiclemanager_configuration['edocs']['location'].$code.$file_name[1];
      $file_true = $mosConfig_absolute_path.$vehiclemanager_configuration['edocs']['location'].$file_name[1];
      if (copy($file_true, $file_new)){
        $sql="UPDATE #__vehiclemanager_vehicles SET edok_link ='$file_new_url' WHERE id=" . $vehicle->id;
        $database->setQuery($sql);
          if (!$database->query()){
            echo "<script> alert('" . $database->getErrorMsg() . "');</script>\n";
          }            
      }
      //edok_link 
      $vehicle->edok_link=$file_new_url;        
      foreach ($vehicle_temp_photos as $vehicle_temp_photo) {
        $vehicle_temp_photo->main_img= $code.$vehicle_temp_photo->main_img;
        $vehicle_photos[] = array($vehicle_temp_photo->main_img, vm_picture_thumbnail($vehicle_temp_photo->main_img, $vehiclemanager_configuration['foto']['high'], $vehiclemanager_configuration['foto']['width']));
      }
      //end clon
  }
    /********************* if count photo group > count photo user not published******************/
      $count_foto_for_single_group = '';
      $user_group = userGID_VM($my->id);       
      $user_group_mas = explode(',', $user_group);
      $max_count_foto = 0;
      foreach ($user_group_mas as $value) {            
          $count_foto_for_single_group =
           $vehiclemanager_configuration['user_manager_vm'][$value]['count_foto'];
          if($count_foto_for_single_group>$max_count_foto){
              $max_count_foto = $count_foto_for_single_group;
          }            
      }
      $count_foto_for_single_group = $max_count_foto;
      $query = "select main_img from #__vehiclemanager_photos WHERE fk_vehicleid='$vehicle->id' order by img_ordering,id";
      $database->setQuery($query);
      $vehicle_temp_photos = $database->loadObjectList();
      if(count($vehicle_temp_photos) != 0)
      {
          $count_foto_for_single_group = $count_foto_for_single_group - count($vehicle_temp_photos);
      }
  /**************************************************************************************************/
  if (array_key_exists("new_photo_file", $_FILES)){
    for ($i = 0; $i < $count_foto_for_single_group; $i++) {
        if (!empty($_FILES['new_photo_file']['name'][$i])) {
          $code = guid();
          $uploadfile = $uploaddir . $code . "_" . $_FILES['new_photo_file']['name'][$i];
          $ext = pathinfo($_FILES['new_photo_file']['name'][$i], PATHINFO_EXTENSION);
          $ext = strtolower($ext);
          $allowed_exts = explode(",", $vehiclemanager_configuration['allowed_exts_img']);
          if (!in_array($ext, $allowed_exts)){
            echo "<script> alert(' File ext. not allowed to upload! - " . $_FILES['new_photo_file']['name'][$i] . "'); window.history.go(-1); </script>\n";
            exit();
          }
          $file['type'] = $_FILES['new_photo_file']['type'][$i];
          $db = JFactory::getDbo();
          $db->setQuery("SELECT mime_type FROM #__vehiclemanager_mime_types WHERE `mime_ext` = " . $db->quote($ext). " and mime_type = " . $db->quote($file['type']) );
          $file_db_mime = $db->loadResult();
          if ($file_db_mime != $file['type']){
            echo "<script> alert(' File mime type not match file ext. - " . $_FILES['new_photo_file']['name'][$i] . "'); window.history.go(-1); </script>\n";
            exit();
          }
          if (copy($_FILES['new_photo_file']['tmp_name'][$i], $uploadfile)){
            $file_name = $code . "_" . $_FILES['new_photo_file']['name'][$i];
            $database->setQuery("INSERT INTO #__vehiclemanager_photos (fk_vehicleid, main_img) VALUES ( '$vehicle->id','$file_name')");
            if (!$database->query()){
              echo "<script> alert('" . $database->getErrorMsg() . "');</script>\n";
            }
          }
        }
    }
  }  //end if
  //save main image
  $uploaddir = $mosConfig_absolute_path . '/components/com_vehiclemanager/photos/';
  
  if ($_FILES['image_link']['name'] != ''){
    $code = guid();
    $uploadfile = $uploaddir . $code . "_" . $_FILES['image_link']['name'];
    $file_name = $code . "_" . $_FILES['image_link']['name'];
    $ext = pathinfo($_FILES['image_link']['name'], PATHINFO_EXTENSION);
    $ext = strtolower($ext);
    $allowed_exts = explode(",", $vehiclemanager_configuration['allowed_exts_img']);
    
    if (!in_array($ext, $allowed_exts)){
        echo "<script> alert(' File ext. not allowed to upload! - " . $_FILES['image_link']['name'] . "'); window.history.go(-1); </script>\n";
        exit();
    }
    $file['type'] = $_FILES['image_link']['type'];
    $db = JFactory::getDbo();
    $db->setQuery("SELECT mime_type FROM #__vehiclemanager_mime_types WHERE `mime_ext` = " . $db->quote($ext). " and mime_type = " . $db->quote($file['type']) );
    $file_db_mime = $db->loadResult();
    if ($file_db_mime != $file['type']){
        echo "<script> alert(' File mime type not match file ext. - " . $_FILES['image_link']['name'] . "'); window.history.go(-1); </script>\n";
        exit();
    }

    if (copy($_FILES['image_link']['tmp_name'], $uploadfile)){
      $tmp_file = vm_picture_thumbnail($file_name, $vehiclemanager_configuration['fotoupload']['high'], $vehiclemanager_configuration['fotoupload']['width']);
      copy($uploaddir . $tmp_file, $uploaddir . $file_name);
      unlink($uploaddir . $tmp_file);
      $database->setQuery("UPDATE #__vehiclemanager_vehicles SET image_link='$file_name' WHERE id=" . $vehicle->id);
      if (!$database->query()){
        echo "<script> alert('" . $database->getErrorMsg() . "');</script>\n";
      }
    }
  }//end if    
  //ordering_photo
    if(JRequest::getVar('veh_img_ordering')){
        $ordering = JRequest::getVar('veh_img_ordering');
        $ordering = explode(',', $ordering);
        foreach ($ordering as $key => $value) {
            $query = "UPDATE #__vehiclemanager_photos SET img_ordering = $key WHERE main_img='".$value."'";
            $database->setQuery($query);
            $database->query();
        }
    }
    //end ordering
  /////////////save video/tracks functions\\\\\\\\\\\\\\\\\\\\\\
    storeVideo($vehicle);
    storeTrack($vehicle);
  /////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    
    //check the files marked for deletion
    if (array_key_exists("del_main_photo", $_POST)){
        $del_main_photo = $_POST['del_main_photo'];
        if ($del_main_photo != ''){
            $file_inf = pathinfo($del_main_photo);
            $file_type = '.' . $file_inf['extension'];
            $file_name = basename($del_main_photo, $file_type);

            $path = $mosConfig_absolute_path . '/components/com_vehiclemanager/photos/';
            $check_files = JFolder::files($path, '^' . $file_name . '.*$', false, true);
            foreach ($check_files as $check_file) {
                unlink($check_file);
            }
        }
        //Database changes
        $database->setQuery("UPDATE #__vehiclemanager_vehicles SET image_link='' WHERE id=" . $vehicle->id);
        if (!$database->query()){
            echo "<script> alert('" . $database->getErrorMsg() . "');</script>\n";
        }
    } //end if
    
    if (array_key_exists("del_photos", $_POST)){
        if (count($_POST['del_photos']) != 0){
            for ($i = 0; $i < count($_POST['del_photos']); $i++) {
                $del_photo = $_POST['del_photos'][$i];
                $database->setQuery("DELETE FROM #__vehiclemanager_photos WHERE main_img='$del_photo'");
                if ($database->query()){
                    $file_inf = pathinfo($del_photo);
                    $file_type = '.' . $file_inf['extension'];
                    $file_name = basename($del_photo, $file_type);

                    $path = $mosConfig_absolute_path . '/components/com_vehiclemanager/photos/';
                    $check_files = JFolder::files($path, '^' . $file_name . '.*$', false, true);
                    foreach ($check_files as $check_file) {
                        unlink($check_file);
                    }
                } else {
                    echo '<script>alert("Can\'t delete");window.history.go(-1);</script>';
                }
            }
        }
    }
    
    if (isset($_POST['del_rent_sal'])){
       
        for ($i = 0; $i < count($_POST['del_rent_sal']); $i++) {
            $del_rent_sal = $_POST['del_rent_sal'][$i];
            $database->setQuery("DELETE FROM #__vehiclemanager_rent_sal WHERE id ='$del_rent_sal'");
            $database->query();
        }
    }    

    if (isset($_POST['feature'])) {
        $feature = $_POST['feature'];
        $database->setQuery("DELETE FROM #__vehiclemanager_feature_vehicles 
                              WHERE fk_vehicleid = " . $vehicle->id);
        $database->query();
        for ($i = 0; $i < count($feature); $i++) {
            $database->setQuery("INSERT INTO #__vehiclemanager_feature_vehicles (fk_vehicleid, fk_featureid) 
                                VALUES (" . $vehicle->id . ", " . $feature[$i] . ")");
            $database->query();
        }
    } else {
        $database->setQuery("DELETE FROM #__vehiclemanager_feature_vehicles WHERE fk_vehicleid = " . $vehicle->id);
        $database->query();
    }
    deleteVideos($vehicle->id);
    deleteTracks($vehicle->id);

    switch ($task) {
        case 'apply':
            mosRedirect("index.php?option=" . $option . "&task=edit&vid[]=" . $vehicle->id);
            break;

        case 'save':
            mosRedirect("index.php?option=" . $option);
            break;
    }
    
  }




  /**
  * Deletes one or more records
  * @param array - An array of unique category id numbers
  * @param string - The current author option
  */
  function removeVehicles($vid, $option,$if_clon=NULL){

    global $database, $mosConfig_absolute_path;

    if (!is_array($vid) || count($vid) < 1){
        echo "<script> alert('Select an item to delete'); window.history.go(-1);</script>\n";
        exit;
    }
    
  /***********************************************************************************************/
    
    for($i = 0; $i < count($vid); $i++){
        
        $query = "select associate_vehicle from #__vehiclemanager_vehicles where id =".$vid[$i];
        $database->setQuery($query);
        $vehicleAssociateVehicle = $database->loadResult(); 
      
        $assocVehicleObj = unserialize($vehicleAssociateVehicle);
        $idWhereChange = array();   
        if(!empty($assocVehicleObj)){
            foreach ($assocVehicleObj as $key => $value) {
                if($value == $vid[$i]){
                    $assocVehicleObj[$key] = null;
                }else if($value){
                    $idWhereChange[] = $value;
                }
            }
        
            $stringIdWhereChange = implode(',', $idWhereChange); 
            $newAssocSerialize = serialize($assocVehicleObj);
            if(!empty($stringIdWhereChange)){
                $query = "update #__vehiclemanager_vehicles set associate_vehicle ='$newAssocSerialize' where id in($stringIdWhereChange)";
                $database->setQuery($query);
                $database->query();                   
            }     
        }    
    }
    
  /***********************************************************************************************/
    
    if (count($vid)){
        $vids = implode(',', $vid);
        $database->setQuery("SELECT image_link FROM  #__vehiclemanager_vehicles WHERE id IN (" . $vids . ")");

        $del_photo = $database->loadObjectList();
        for ($i = 0; $i < count($del_photo); $i++) {
            if ($del_photo[$i]->image_link != '' && !$if_clon){
                $path = $mosConfig_absolute_path . '/components/com_vehiclemanager/photos';
                $del_photo_mask_inf = pathinfo($del_photo[$i]->image_link);    // arr
                $del_photo_mask_type = '.' . $del_photo_mask_inf['extension']; // .jpg
                $del_photo_mask = basename($del_photo[$i]->image_link, $del_photo_mask_type); 
                $check_files = JFolder::files($path, '^' . $del_photo_mask . '.*$', false, true);

                if (!empty($check_files)) {
                    foreach ($check_files as $check_file) {
                        unlink($check_file);
                    }
                }
            }
        }

        $database->setQuery("DELETE FROM #__vehiclemanager_review WHERE fk_vehicleid IN ($vids)");
        if (!$database->query()){
            echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
        }

        $database->setQuery("SELECT main_img FROM #__vehiclemanager_photos WHERE fk_vehicleid IN ($vids)");
        $del_photos = $database->loadObjectList();

        for ($i = 0; $i < count($del_photos); $i++) {
            if ($del_photos[$i]->main_img != ''){
                $path = $mosConfig_absolute_path . '/components/com_vehiclemanager/photos';
                $del_photos_mask_inf = pathinfo($del_photos[$i]->main_img);
                $del_photos_mask_type = '.' . $del_photos_mask_inf['extension'];
                $del_photos_mask = basename($del_photos[$i]->main_img, $del_photos_mask_type);
                $check_files = JFolder::files($path, '^' . $del_photos_mask . '.*$', false, true);

                if (!empty($check_files)){
                    foreach ($check_files as $check_file) {
                        unlink($check_file);
                    }
                }
            }
        }

        $database->setQuery("DELETE FROM #__vehiclemanager_feature_vehicles WHERE fk_vehicleid IN ($vids)");
        if (!$database->query()){
            echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
        }

        $database->setQuery("DELETE FROM #__vehiclemanager_photos WHERE fk_vehicleid IN ($vids)");
        if (!$database->query()){
            echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
        }

        $database->setQuery("DELETE FROM #__vehiclemanager_categories WHERE iditem IN ($vids)");
        if (!$database->query()){
            echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
        }
        $database->setQuery("DELETE FROM #__vehiclemanager_vehicles WHERE id IN ($vids)");
        if (!$database->query()){
            echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
        }
        $database->setQuery("DELETE FROM #__vehiclemanager_rent_sal WHERE fk_vehiclesid IN ($vids)");
        if (!$database->query()){
            echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
        }
        $database->setQuery("DELETE FROM #__vehiclemanager_track_source WHERE fk_vehicle_id IN ($vids)");
        if (!$database->query()){
            echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
        }
        $database->setQuery("DELETE FROM #__vehiclemanager_video_source WHERE fk_vehicle_id IN ($vids)");
        if (!$database->query()){
            echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
        }
    }  
    mosRedirect("index.php?option=$option");
  }

  /**
  * Publishes or Unpublishes one or more records
  * @param array - An array of unique category id numbers
  * @param integer - 0 if unpublishing, 1 if publishing
  * @param string - The current author option
  */


  function rentPrice($vid,$rent_from,$rent_until,$special_price,$comment_price,$currency_spacial_price){
    rentPriceVM($vid,$rent_from,$rent_until,$special_price,$comment_price,$currency_spacial_price);
  }


  function clonVehicle($vid, $option){

    global $database, $my, $mosConfig_live_site, $vehiclemanager_configuration, $mosConfig_absolute_path;    
       
    if(count($vid)>1){
        echo "<script> alert('You can clon only one vehicle'); window.history.go(-1); </script>\n";
        exit();         
    }
    
    $vid = $vid[0];
    if (isset($_GET['mess'])){ 
        // // for 1.6
        // Vehicle is get from table throw the ID
        echo "<tt style='font-size:12px !important;'>" . $_GET['mess'] . "</tt>";
         
        if (isset($_GET['vid'])){ 
            
            // $_GET['vid'] - is an vehicleID from Vehicles table
            $q = "SELECT id
                    FROM `#__vehiclemanager_vehicles`
                    WHERE  `vehicleid`= " . $_GET['vid'] . ";";
            $database->setQuery($q);
            $vid = $database->loadObjectList();
            
            if (isset($vid[0]->id)){
                // $vid - is exactly id, not an vehicleid from Vehicles table!
                $vid = $vid[0]->id;    
            }
                         
        } else{
            echo "<script>window.history.go(-1);</script>";
        }          
    }
    
    $vehicle_true = new mosVehicleManager($database);
    // load the row from the db table 
    $vehicle_true->load(intval($vid));
    $vehicle_true->setCatIds();
    $vehicle = clone $vehicle_true;
     
    $vehicle->id = null;
    $vehicle->store(); 
    $vehicle->id_true = $vehicle_true->id;
    $vehicle->saveCatIds($vehicle->catid); 

    // get list of categories
    $categories = array();

    $query = "SELECT  id ,name, parent_id as parent"
            . "\n FROM #__vehiclemanager_main_categories"
            . "\n WHERE section='com_vehiclemanager'"
            . "\n ORDER BY parent_id, ordering";

    $database->setQuery($query);
    $rows = $database->loadObjectList();

    // establish the hierarchy of the categories
    $children = array();
    // first pass - collect children
    foreach ($rows as $v) {
        $pt = $v->parent;
        $list = @$children[$pt] ? $children[$pt] : array();
        array_push($list, $v);
        $children[$pt] = $list;
    }
    
    // second pass - get an indent list of the items
    $list = vmLittleThings::vehicleManagerTreeRecurse(0, '', array(), $children);

    foreach ($list as $i => $item) {
        $item->text = $item->treename;
        $item->value = $item->id;
        $list[$i] = $item;
    }

    $categories = array_merge($categories, $list);
    
    $clist = mosHTML :: selectList($categories, 'catid[]', 'class="inputbox" multiple', 'value', 'text', $vehicle->catid);

    //get Rating
    $retVal2 = mosVehicleManagerOthers :: getRatingArray();
    $rating = null;
    for ($i = 0, $n = count($retVal2); $i < $n; $i++) {
        $help = $retVal2[$i];
        $rating[] = mosHTML :: makeOption($help[0], $help[1]);
    }

    //delete vehicle?
    $help = str_replace($mosConfig_live_site, "", $vehicle->edok_link);
    $delete_edoc_yesno[] = mosHTML :: makeOption($help, _VEHICLE_MANAGER_YES);
    $delete_edoc_yesno[] = mosHTML :: makeOption('0', _VEHICLE_MANAGER_NO);
    $delete_edoc = mosHTML :: RadioList($delete_edoc_yesno, 'delete_edoc', 'class="inputbox"', '0', 'value', 'text');

    // fail if checked out not by 'me'
    if ($vehicle->checked_out && $vehicle->checked_out <> $my->id){
        mosRedirect("index.php?option=$option", _VEHICLE_MANAGER_IS_EDITED);
    }

    if ($vid){
        $vehicle->checkout($my->id);
    } else{
        // initialise new record
        $vehicle->published = 0;
        $vehicle->approved = 0;
    }

  //*****************************   begin for reviews **************************//
    $database->setQuery("select a.* from #__vehiclemanager_review a" .
            " WHERE a.fk_vehicleid=" . $vid . " ORDER BY date ;");

    $reviews = $database->loadObjectList();
  //**********************   end for reviews   *****************************//
    //Select list for vehicle type
    $vehicletype[] = mosHtml::makeOption(0, _VEHICLE_MANAGER_OPTION_SELECT);
    $vehicletype1 = explode(',', _VEHICLE_MANAGER_OPTION_VEHICLE_TYPE);
    $i = 1;
    foreach ($vehicletype1 as $vehicletype2) {
        $vehicletype[] = mosHtml::makeOption($i, $vehicletype2);
        $i++;
    }
    $vehicle_type_list = mosHTML :: selectList($vehicletype, 'vtype',
     'class="inputbox" size="1"', 'value', 'text', $vehicle->vtype);
    //Select list for listing type
    $listing_type[] = mosHtml::makeOption(0, _VEHICLE_MANAGER_OPTION_SELECT);
    $listing_type[] = mosHtml::makeOption(1, _VEHICLE_MANAGER_OPTION_FOR_RENT);
    $listing_type[] = mosHtml::makeOption(2, _VEHICLE_MANAGER_OPTION_FOR_SALE);
    $listing_type_list = mosHTML :: selectList($listing_type, 'listing_type',
     'class="inputbox" size="1"', 'value', 'text', $vehicle->listing_type);

    //Select list for price type
    $test[] = mosHtml::makeOption(0, _VEHICLE_MANAGER_OPTION_SELECT);
    $test1 = explode(',', _VEHICLE_MANAGER_OPTION_PRICE_TYPE);
    $i = 1;
    foreach ($test1 as $test2) {
        $test[] = mosHtml::makeOption($i, $test2);
        $i++;
    }
    $test_list = mosHTML :: selectList($test, 'price_type', 'class="inputbox"
     size="1"', 'value', 'text', $vehicle->price_type);

    //Select list for vehicle condition
    $condition[] = mosHtml::makeOption(0, _VEHICLE_MANAGER_OPTION_SELECT);
    $condition1 = explode(',', _VEHICLE_MANAGER_OPTION_VEHICLE_CONDITION);
    $i = 1;
    foreach ($condition1 as $condition2) {
        $condition[] = mosHtml::makeOption($i, $condition2);
        $i++;
    }
    $condition_status_list = mosHTML :: selectList($condition, 'vcondition',
     'class="inputbox" size="1"', 'value', 'text', $vehicle->vcondition);

    //Select list for listing status
    $listing_status[] = mosHtml::makeOption(0, _VEHICLE_MANAGER_OPTION_SELECT);
    $listing_status1 = explode(',', _VEHICLE_MANAGER_OPTION_LISTING_STATUS);
    $i = 1;
    foreach ($listing_status1 as $listing_status2) {
        $listing_status[] = mosHtml::makeOption($i, $listing_status2);
        $i++;
    }
    $listing_status_list = mosHTML :: selectList($listing_status, 'listing_status',
     'class="inputbox" size="1"', 'value', 'text', $vehicle->listing_status);

    //Select list for vehicle transmission
    $transmission[] = mosHtml::makeOption(0, _VEHICLE_MANAGER_OPTION_SELECT);
    $transmission1 = explode(',', _VEHICLE_MANAGER_OPTION_TRANSMISSION);
    $i = 1;
    foreach ($transmission1 as $transmission2) {
        $transmission[] = mosHtml::makeOption($i, $transmission2);
        $i++;
    }
    $transmission_type_list = mosHTML :: selectList($transmission, 'transmission',
     'class="inputbox" size="1"', 'value', 'text', $vehicle->transmission);

    //Select list for vehicle drive type
    $drivetype[] = mosHtml::makeOption(0, _VEHICLE_MANAGER_OPTION_SELECT);
    $drivetype1 = explode(',', _VEHICLE_MANAGER_OPTION_DRIVE_TYPE);
    $i = 1;
    foreach ($drivetype1 as $drivetype2) {
        $drivetype[] = mosHtml::makeOption($i, $drivetype2);
        $i++;
    }
    $drive_type_list = mosHTML :: selectList($drivetype, 'drive_type',
     'class="inputbox" size="1"', 'value', 'text', $vehicle->drive_type);

    //Select list for number of cylinder
    $numcylinder[] = mosHtml::makeOption(0, _VEHICLE_MANAGER_OPTION_SELECT);
    $numcylinder1 = explode(',', _VEHICLE_MANAGER_OPTION_NUMBER_OF_CYLINDERS);
    $i = 1;
    foreach ($numcylinder1 as $numcylinder2) {
        $numcylinder[] = mosHtml::makeOption($i, $numcylinder2);
        $i++;
    }
    $num_cylinder_list = mosHTML :: selectList($numcylinder, 'cylinder',
     'class="inputbox" size="1"', 'value', 'text', $vehicle->cylinder);

    //Select list for vehicle fuel type
    $fueltype[] = mosHtml::makeOption(0, _VEHICLE_MANAGER_OPTION_SELECT);
    $fueltype1 = explode(',', _VEHICLE_MANAGER_OPTION_FUEL_TYPE);
    $i = 1;
    foreach ($fueltype1 as $fueltype2) {
        $fueltype[] = mosHtml::makeOption($i, $fueltype2);
        $i++;
    }
    $fuel_type_list = mosHTML :: selectList($fueltype, 'fuel_type',
     'class="inputbox" size="1"', 'value', 'text', $vehicle->fuel_type);

    //Select list for number of speed
    $numspeed[] = mosHtml::makeOption(0, _VEHICLE_MANAGER_OPTION_SELECT);
    $numspeed1 = explode(',', _VEHICLE_MANAGER_OPTION_NUMBER_OF_SPEEDS);
    $i = 1;
    foreach ($numspeed1 as $numspeed2) {
        $numspeed[] = mosHtml::makeOption($i, $numspeed2);
        $i++;
    }
    $num_speed_list = mosHTML :: selectList($numspeed, 'num_speed',
     'class="inputbox" size="1"', 'value', 'text', $vehicle->num_speed);

    //Select list for number of doors
    $numdoors[] = mosHtml::makeOption(0, _VEHICLE_MANAGER_OPTION_SELECT);
    $numdoors1 = explode(',', _VEHICLE_MANAGER_OPTION_NUMBER_OF_DOORS);
    $i = 1;
    foreach ($numdoors1 as $numdoors2) {
        $numdoors[] = mosHtml::makeOption($i, $numdoors2);
        $i++;
    }
    $num_doors_list = mosHTML :: selectList($numdoors, 'doors',
     'class="inputbox" size="1"', 'value', 'text', $vehicle->doors);
    
    $query = "select image_link from #__vehiclemanager_vehicles WHERE id='$vehicle_true->id'";
    $database->setQuery($query); 
    $vehicle_photo = $database->loadResult();

    if ($vehicle_photo != ''){
        $vehicle_photo = array($vehicle_photo, vm_picture_thumbnail($vehicle_photo,
         $vehiclemanager_configuration['foto']['high'], $vehiclemanager_configuration['foto']['width']));
       
    }
    
    // Setting the resize parameters
  //***********************
    $makers = array();
    $opt[] = mosHtml::makeOption('', _VEHICLE_MANAGER_OPTION_SELECT);
    $opt[] = mosHtml::makeOption('other', _VEHICLE_MANAGER_LABEL_SELECT_OTHER);
    $temp = mosVehicleManagerOthers::getMakersArray();
    $makers = array_merge($makers, $temp[0]);

    foreach ($makers as $maker) {
        $opt[] = mosHtml::makeOption(trim($maker), trim($maker));
    }
    $nummaker = array_search($vehicle->maker, $temp[0]);

    foreach ($temp[1][$nummaker] as $model) {
        $opt1[] = mosHtml::makeOption(trim($model), trim($model));
    }
    $opt1[] = mosHtml::makeOption('other', _VEHICLE_MANAGER_LABEL_SELECT_OTHER);
    $currentmodel = $vehicle->vmodel;
    $maker = mosHTML :: selectList($opt, 'maker', 'class="inputbox" size="1"
     onchange=changedMaker(this)', 'value', 'text', $vehicle->maker);
    if (in_array($currentmodel, $temp[1][$nummaker])){
        $modellist = mosHTML :: selectList($opt1, 'vmodel',
         'class="inputbox" size="1" id="vmodel" onchange=changedModel(this) ', 'value', 'text', $vehicle->vmodel);
    } else{
        $modellist = '<input name="vmodel" id="vmodel"  value="' . $vehicle->vmodel . '"/>';
    }
    ///////////START check video/audio files\\\\\\\\\\\\\\\\\\\\\\
    $tracks = array();
    $videos = array();
    if (!empty($vehicle->id)) { 
      $database->setQuery("SELECT * FROM #__vehiclemanager_video_source WHERE fk_vehicle_id=" . $vid);
      $videos = $database->loadObjectList();
    }
    $youtube = new stdClass();
    for ($i = 0;$i < count($videos);$i++) {
      if (!empty($videos[$i]->youtube)) {
        $youtube->code = $videos[$i]->youtube;
        $youtube->id = $videos[$i]->id;
        break;
      }
    }
    if (!empty($vehicle->id)) { //check video file
      $database->setQuery("SELECT * FROM #__vehiclemanager_track_source WHERE fk_vehicle_id=" . $vid);
      $tracks = $database->loadObjectList();
    }
    if(count($videos)){
        for($i = 0; $i < count($videos); $i++){
            $query = "INSERT INTO
                        `#__vehiclemanager_video_source`
                      SET
                        `fk_vehicle_id` = ".$vehicle->id.",
                        `sequence_number` = '".$videos[$i]->sequence_number."',
                        `src` = '".$videos[$i]->src."',
                        `type` = '".$videos[$i]->type."',
                        `media` = '".$videos[$i]->media."',
                        `youtube` = '".$videos[$i]->youtube."'";  
            $database->setQuery($query);
            $database->query();
        }
    }
    if(count($tracks)){
        for($i = 0; $i < count($tracks); $i++){
            $query = "INSERT INTO
                        `#__vehiclemanager_track_source`
                      SET
                        `fk_vehicle_id` = ".$vehicle->id.",
                        `sequence_number` = '".$tracks[$i]->sequence_number."',
                        `src` = '".$tracks[$i]->src."',
                        `kind` = '".$tracks[$i]->kind."',
                        `scrlang` = '".$tracks[$i]->scrlang."',
                        `label` = '".$tracks[$i]->label."'";
            $database->setQuery($query);
            $database->query();
        }
    }
////////////////////////////////END check video/audio files \\\\\\\\\\\\\\\\\\
    if (trim($vehicle_true->id) != ""){
       
        $query = "select * from #__vehiclemanager_rent_sal where fk_vehiclesid = " . $vid;
        $database->setQuery($query);
        $vehicle_rent_sal = $database->loadObjectList();
              
        if(count($vehicle_rent_sal)){
            for($i = 0; $i < count($vehicle_rent_sal); $i++){
                $query = "INSERT INTO
                            `#__vehiclemanager_rent_sal`
                          SET
                            `fk_vehiclesid` = ".$vehicle->id.",
                            `monthW` = '".$vehicle_rent_sal[$i]->monthW."',
                            `yearW` = '".$vehicle_rent_sal[$i]->yearW."',
                            `week` = '".$vehicle_rent_sal[$i]->week."',
                            `weekend` = '".$vehicle_rent_sal[$i]->weekend."',
                            `midweek` = '".$vehicle_rent_sal[$i]->midweek."', 
                            `price_from` = '".$vehicle_rent_sal[$i]->price_from."',
                            `price_to` = '".$vehicle_rent_sal[$i]->price_to."',    
                            `special_price` = ".$vehicle_rent_sal[$i]->special_price.",
                            `comment_price` = '".$vehicle_rent_sal[$i]->comment_price."',
                            `priceunit` = '".$vehicle_rent_sal[$i]->priceunit."'";  
                $database->setQuery($query);
                $database->query();
            }
        }
    }

    $query = "SELECT * ";
    $query .= "FROM #__vehiclemanager_feature as f ";
    $query .= "WHERE f.published = 1 ";
    $query .= "ORDER BY f.categories";
    $database->setQuery($query);
    $vehicle_feature = $database->loadObjectList();

    for ($i = 0; $i < count($vehicle_feature); $i++) {
        $feature = "";
        if (!empty($vehicle_true->id)){
            $query = "SELECT id ";
            $query .= "FROM #__vehiclemanager_feature_vehicles ";
            $query .= "WHERE fk_featureid =" . $vehicle_feature[$i]->id . " AND fk_vehicleid =" . $vehicle_true->id;
            
            
            $database->setQuery($query);
            
            $feature = $database->loadResult();
            
            if ($feature){
                $vehicle_feature[$i]->check = 1;
            }else{
                $vehicle_feature[$i]->check = 0;           
            }
        } else{
            $vehicle_feature[$i]->check = 0;
        }
    }

    $currencys = explode(';', $vehiclemanager_configuration['currency']);
    foreach ($currencys as $row) {
        if ($row != ''){
            $row = explode("=", $row);
            $currency_temp[] = mosHTML::makeOption($row[0], $row[0]);
        }
    }
    
    $owner_email = '';
    
    if ($vehicle->owner_id > 0) {
        $database->setQuery("SELECT email FROM #__users WHERE `id`= '" .$vehicle->owner_id."'");
        $www= $database->loadResult();
        
        if (strlen( $vehicle->owneremail) > 0 && $www !=  $vehicle->owneremail){
            $owner_email = $vehicle->owneremail;
        }else{ 
            $owner_email = $www;
        }
    } 
    
    $currency = mosHTML :: selectList($currency_temp, 'priceunit',
     'class="inputbox" size="1"', 'value', 'text', $vehicle->priceunit);
    
    $currency_spacial_price = mosHTML :: selectList($currency_temp,
     'currency_spacial_price[]', 'class="inputbox" size="1"', 'value', 'text', $vehicle->priceunit);
    
    
    $query = "SELECT lang_code, title FROM #__languages";
    $database->setQuery($query);
    $languages = $database->loadObjectList();

    $languages_row[] = mosHTML::makeOption('*', 'All');
    foreach ($languages as $language) {
        $languages_row[] = mosHTML::makeOption($language->lang_code, $language->title);
    }
    $languages = mosHTML :: selectList($languages_row, 'language',
     'class="inputbox" size="1"', 'value', 'text', $vehicle->language);
    $associate_vehicle = null;
     for($i=6;$i<=10;$i++){
        $extraOption = '';    
        $extraOption[] = mosHtml::makeOption(0, _VEHICLE_MANAGER_OPTION_SELECT);    
  $name = "_VEHICLE_MANAGER_EXTRA" . $i . "_SELECTLIST";
  $extra = explode(',', constant($name));	
  $j = 1;
        
  foreach($extra as $extr){
      $extraOption[] = mosHTML::makeOption($j, $extr);	 
            $j++;    
  }

        switch ($i) {
            case 6:
                $extraSelect = $vehicle->extra6;
                break;
            case 7:
                $extraSelect = $vehicle->extra7;
                break;
            case 8:
                $extraSelect = $vehicle->extra8;
                break;
            case 9:
                $extraSelect = $vehicle->extra9;
                break;
            case 10:
                $extraSelect = $vehicle->extra10;
                break;
        }
        $extra_list[] = mosHTML :: selectList($extraOption, 'extra' . $i,
         'class="inputbox" size="1"', 'value', 'text', $extraSelect);
    }

    HTML_vehiclemanager:: editVehicle($option, $vehicle, $clist, $ratinglist, $delete_edoc,$videos,$youtube, $tracks, $reviews,
            $test_list, $vehicle_type_list, $listing_status_list, $condition_status_list, $transmission_type_list, 
            $listing_type_list, $drive_type_list, $fuel_type_list, $num_speed_list, $num_cylinder_list, 
            $num_doors_list, $vehicle_photo, $vehicle_temp_photos, $vehicle_photos, $maker, $temp, $currentmodel, $modellist, 
            $vehicle_rent_sal, $vehicle_feature, $currency, $languages, $extra_list,
            $owner_email, $currency_spacial_price, $associate_vehicle);
  }

  function publishVehicles($vid, $publish, $option){

    
    global $database, $my;

    $catid = mosGetParam($_POST, 'catid', array(0));

    if (!is_array($vid) || count($vid) < 1){
        $action = $publish ? 'publish' : 'unpublish';
        echo "<script> alert('Select an item to $action'); window.history.go(-1);</script>\n";
        exit;
    }

    $vids = implode(',', $vid);

    $database->setQuery("UPDATE #__vehiclemanager_vehicles SET published='$publish'" .
            "\nWHERE id IN ($vids) AND (checked_out=0 OR (checked_out='$my->id'))");
    if (!$database->query()){
        echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
        exit();
    }

    if (count($vid) == 1){
        $row = new mosVehicleManager($database);
        $row->checkin($vid[0]);
    }

    mosRedirect("index.php?option=$option");
  }

  /**
  * Approve or Unapprove one or more records
  * @param array - An array of unique category id numbers
  * @param integer - 0 if unapprove, 1 if approve
  * @param string - The current author option
  */
  function approveVehicles($vid, $approve, $option)
  {
    global $database, $my;

    $catid = mosGetParam($_POST, 'catid', array(0));

    if (!is_array($vid) || count($vid) < 1)
    {
        $action = $approve ? 'approve' : 'unapprove';
        echo "<script> alert('Select an item to $action'); window.history.go(-1);</script>\n";
        exit;
    }

    $vids = implode(',', $vid);

    $database->setQuery("UPDATE #__vehiclemanager_vehicles SET published=$approve, approved='$approve'" .
            "\nWHERE id IN ($vids) AND (checked_out=0 OR (checked_out='$my->id'))");
    if (!$database->query())
    {
        echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
        exit();
    }

    if (count($vid) == 1)
    {
        $row = new mosVehicleManager($database);
        $row->checkin($vid[0]);
    }

    mosRedirect("index.php?option=$option");
  }

  /**
  * Moves the order of a record
  * @param integer - The increment to reorder by
  */
  function orderVehicles($vid, $inc, $option)
  {
    global $database;
    $vehicle = new mosVehicleManager($database);
    $vehicle->load($vid);
    $vehicle->move($inc);
    mosRedirect("index.php?option=$option");
  }

  /**
  * Cancels an edit operation
  * @param string - The current author option
  */
  function cancelVehicle($option){
    global $database;
    $row = new mosVehicleManager($database);
    if($_REQUEST['idtrue']){        
        $vid[]=$_REQUEST['id']; 
        
        removeVehicles($vid,$option,TRUE);
    }       
    
    $row->bind($_POST);
    $row->checkin();
    mosRedirect("index.php?option=$option");
  }

  function configure_save_frontend($option)
  {
    global $my, $vehiclemanager_configuration;

    $str = '';
    $supArr = array();
    $supArr = mosGetParam($_POST, 'edocs_registrationlevel', 0);
    for ($i = 0; $i < count($supArr); $i++)
        $str.=$supArr[$i] . ',';
    $str = substr($str, 0, -1);
    $vehiclemanager_configuration['edocs']['registrationlevel'] = $str;

    $str = '';
    $supArr = mosGetParam($_POST, 'reviews_registrationlevel', 0);
    for ($i = 0; $i < count($supArr); $i++)
        $str.=$supArr[$i] . ',';
    $str = substr($str, 0, -1);
    $vehiclemanager_configuration['reviews']['registrationlevel'] = $str;

    $str = '';
    $supArr = mosGetParam($_POST, 'rentrequest_registrationlevel', 0);
    for ($i = 0; $i < count($supArr); $i++)
        $str.=$supArr[$i] . ',';
    $str = substr($str, 0, -1);
    $vehiclemanager_configuration['rentrequest']['registrationlevel'] = $str;

    $str = '';
    $supArr = mosGetParam($_POST, 'buyrequest_registrationlevel', 0);
    for ($i = 0; $i < count($supArr); $i++)
        $str.=$supArr[$i] . ',';
    $str = substr($str, 0, -1);
    $vehiclemanager_configuration['buyrequest']['registrationlevel'] = $str;
    $str = '';
    $supArr = mosGetParam($_POST, 'Location_vehicle_registrationlevel', 0);
    for ($i = 0; $i < count($supArr); $i++)
        $str.=$supArr[$i] . ',';
    $str = substr($str, 0, -1);
    $vehiclemanager_configuration ['Location_vehicle']['registrationlevel'] = $str;

    $str = '';
    $supArr = mosGetParam($_POST, 'contacts_registrationlevel', 0);
    for ($i = 0; $i < count($supArr); $i++)
        $str.=$supArr[$i] . ',';
    $str = substr($str, 0, -1);
    $vehiclemanager_configuration ['contacts']['registrationlevel'] = $str;

    $str = '';
    $supArr = mosGetParam($_POST, 'cb_registrationlevel', 0);
    for ($i = 0; $i < count($supArr); $i++)
        $str.=$supArr[$i] . ',';
    $str = substr($str, 0, -1);
    $vehiclemanager_configuration['cb']['registrationlevel'] = $str;

    $str = '';
    $supArr = mosGetParam($_POST, 'cb_myvehicle_registrationlevel', 0);
    for ($i = 0; $i < count($supArr); $i++)
        $str.=$supArr[$i] . ',';
    $str = substr($str, 0, -1);
    $vehiclemanager_configuration['cb_myvehicle']['registrationlevel'] = $str;

    $str = '';
    $supArr = mosGetParam($_POST, 'cb_edit_registrationlevel', 0);
    for ($i = 0; $i < count($supArr); $i++)
        $str.=$supArr[$i] . ',';
    $str = substr($str, 0, -1);
    $vehiclemanager_configuration['cb_edit']['registrationlevel'] = $str;

    $str = '';
    $supArr = mosGetParam($_POST, 'cb_rent_registrationlevel', 0);
    for ($i = 0; $i < count($supArr); $i++)
        $str.=$supArr[$i] . ',';
    $str = substr($str, 0, -1);
    $vehiclemanager_configuration['cb_rent']['registrationlevel'] = $str;

    $str = '';
    $supArr = mosGetParam($_POST, 'cb_buy_registrationlevel', 0);
    for ($i = 0; $i < count($supArr); $i++)
        $str.=$supArr[$i] . ',';
    $str = substr($str, 0, -1);
    $vehiclemanager_configuration['cb_buy']['registrationlevel'] = $str;

    $str = '';
    $supArr = mosGetParam($_POST, 'cb_history_registrationlevel', 0);
    for ($i = 0; $i < count($supArr); $i++)
        $str.=$supArr[$i] . ',';
    $str = substr($str, 0, -1);
    $vehiclemanager_configuration['cb_history']['registrationlevel'] = $str;

    $str = '';
    $supArr = mosGetParam($_POST, 'Reviews_vehicle_registrationlevel', 0);
    for ($i = 0; $i < count($supArr); $i++)
        $str.=$supArr[$i] . ',';
    $str = substr($str, 0, -1);
    $vehiclemanager_configuration ['Reviews_vehicle']['registrationlevel'] = $str;

    $str = '';
    $supArr = mosGetParam($_POST, 'price_registrationlevel', 0);
    for ($i = 0; $i < count($supArr); $i++)
        $str.=$supArr[$i] . ',';
    $str = substr($str, 0, -1);
    $vehiclemanager_configuration['price']['registrationlevel'] = $str;

    $vehiclemanager_configuration['Contacts']['show'] = mosGetParam($_POST, 'Contacts_show_vehicle', 0);

    $vehiclemanager_configuration['cb']['show'] = mosGetParam($_POST, 'cb_show', 0);
    $vehiclemanager_configuration['cb_myvehicle']['show'] = mosGetParam($_POST, 'cb_show_myvehicle', 0);
    $vehiclemanager_configuration['cb_edit']['show'] = mosGetParam($_POST, 'cb_show_edit', 0);
    $vehiclemanager_configuration['cb_rent']['show'] = mosGetParam($_POST, 'cb_show_rent', 0);
    $vehiclemanager_configuration['cb_buy']['show'] = mosGetParam($_POST, 'cb_show_buy', 0);
    $vehiclemanager_configuration['cb_history']['show'] = mosGetParam($_POST, 'cb_show_history', 0);

  //**************************** end add for Tabs  ************	
    $vehiclemanager_configuration['Location_vehicle']['show'] = mosGetParam($_POST, 'Location_show_vehicle', 0);
    $vehiclemanager_configuration['Reviews_vehicle']['show'] = mosGetParam($_POST, 'Reviews_show_vehicle', 0);

    $vehiclemanager_configuration['special_price']['show'] = mosGetParam($_POST, 'special_price', 0);
    
    $vehiclemanager_configuration['edocs']['show'] = mosGetParam($_POST, 'edocs_show', 0);
    $vehiclemanager_configuration['ebooks']['allow'] = mosGetParam($_POST, 'ebooks_allow', 0);
    $vehiclemanager_configuration['videos_tracks']['show'] = mosGetParam($_POST, 'videos_tracks_allow', 0);
    $vehiclemanager_configuration['price']['show'] = mosGetParam($_POST, 'price_show', 0);
    $vehiclemanager_configuration['thumb_param']['show'] = mosGetParam($_POST, 'thumb_param_show', 0);
    $vehiclemanager_configuration['foto']['high'] = mosGetParam($_POST, 'foto_high');
    $vehiclemanager_configuration['foto']['width'] = mosGetParam($_POST, 'foto_width');
    $vehiclemanager_configuration['fotomain']['high'] = mosGetParam($_POST, 'fotomain_high');
    $vehiclemanager_configuration['fotomain']['width'] = mosGetParam($_POST, 'fotomain_width');
    $vehiclemanager_configuration['fotogallery']['high'] = mosGetParam($_POST, 'fotogallery_high');
    $vehiclemanager_configuration['fotogallery']['width'] = mosGetParam($_POST, 'fotogallery_width');
    $vehiclemanager_configuration['fotogal']['high'] = mosGetParam($_POST, 'fotogal_high');
    $vehiclemanager_configuration['fotogal']['width'] = mosGetParam($_POST, 'fotogal_width');
    $vehiclemanager_configuration['fotoupload']['high'] = mosGetParam($_POST, 'fotoupload_high');
    $vehiclemanager_configuration['fotoupload']['width'] = mosGetParam($_POST, 'fotoupload_width');
    $vehiclemanager_configuration['page']['items'] = mosGetParam($_POST, 'page_items');
    $vehiclemanager_configuration['license']['show'] = mosGetParam($_POST, 'license_show');
    //add for show in category picture
    $vehiclemanager_configuration['cat_pic']['show'] = mosGetParam($_POST, 'cat_pic_show');
    //add for show subcategory 
    $vehiclemanager_configuration['subcategory']['show'] = mosGetParam($_POST, 'subcategory_show');
    //owner show
    $vehiclemanager_configuration['owner']['show'] = mosGetParam($_POST, 'owner_show');

    //***********begin Owners list
    $vehiclemanager_configuration['ownerslist']['show'] = mosGetParam($_POST, 'ownerslist_show');
    $str = '';
    $supArr = mosGetParam($_POST, 'ownerslist_registrationlevel', 0);
    for ($i = 0; $i < count($supArr); $i++)
        $str.=$supArr[$i] . ',';
    $str = substr($str, 0, -1);
    $vehiclemanager_configuration['ownerslist']['registrationlevel'] = $str;
    //***********end Owners list
    //calendar show
    $vehiclemanager_configuration['calendar']['show'] = mosGetParam($_POST, 'calendar_show');

    //***********begin Calendar list
    $vehiclemanager_configuration['calendarlist']['show'] = mosGetParam($_POST, 'calendarlist_show');
    $str = '';
    $supArr = mosGetParam($_POST, 'calendarlist_registrationlevel', 0);
    for ($i = 0; $i < count($supArr); $i++)
        $str.=$supArr[$i] . ',';
    $str = substr($str, 0, -1);
    $vehiclemanager_configuration['calendarlist']['registrationlevel'] = $str;
    //***********end Calendar list
    //show location map
    $vehiclemanager_configuration['location_map'] = mosGetParam($_POST, 'location_map', 0);

    $vehiclemanager_configuration['manager_feature_image'] = mosGetParam($_POST, 'manager_feature_image', 0);

    $vehiclemanager_configuration['manager_feature_category'] = mosGetParam($_POST, 'manager_feature_category', 0);

    $vehiclemanager_configuration['sale_separator'] = mosGetParam($_POST, 'sale_separator', 0);

    $vehiclemanager_configuration['extra1'] = mosGetParam($_POST, 'extra1', '');
    $vehiclemanager_configuration['extra2'] = mosGetParam($_POST, 'extra2', '');
    $vehiclemanager_configuration['extra3'] = mosGetParam($_POST, 'extra3', '');
    $vehiclemanager_configuration['extra4'] = mosGetParam($_POST, 'extra4', '');
    $vehiclemanager_configuration['extra5'] = mosGetParam($_POST, 'extra5', '');
    $vehiclemanager_configuration['extra6'] = mosGetParam($_POST, 'extra6', '');
    $vehiclemanager_configuration['extra7'] = mosGetParam($_POST, 'extra7', '');
    $vehiclemanager_configuration['extra8'] = mosGetParam($_POST, 'extra8', '');
    $vehiclemanager_configuration['extra9'] = mosGetParam($_POST, 'extra9', '');
    $vehiclemanager_configuration['extra10'] = mosGetParam($_POST, 'extra10', '');

    mosVehicleManagerOthers :: setParams();
  }

  function configure_save_backend($option){
    global $my, $vehiclemanager_configuration;
    
    $gtree = get_group_children_tree_vm();
    foreach($gtree as $g){
        $vehiclemanager_configuration['user_manager_vm'][$g->value]['count_car'] = intval(mosGetParam($_POST, 'count_car' . $g->value, "5"));
        $vehiclemanager_configuration['user_manager_vm'][$g->value]['count_foto'] = intval(mosGetParam($_POST, 'count_foto' . $g->value, "5"));
    }
    $vehiclemanager_configuration['edocs']['allow'] = mosGetParam($_POST, 'edocs_allow', 0);
    $vehiclemanager_configuration['edocs']['location'] = mosGetParam($_POST, 'edocs_location', "/components/com_vehiclemanager/edocs/");
    $vehiclemanager_configuration['price_format'] = $_POST['patern'];
    $vehiclemanager_configuration['date_format'] = mosGetParam($_POST, 'date_format');
    $vehiclemanager_configuration['datetime_format'] = mosGetParam($_POST, 'datetime_format');
    $vehiclemanager_configuration['price_unit_show'] = $_POST['price_unit_show'];
   //update
    $vehiclemanager_configuration['calendar']['placeholder'] = mosGetParam($_POST, 'calendar_placeholder', "");
  
   
    $vehiclemanager_configuration['currency'] = mosGetParam($_POST, 'currency', "");
    

    $vehiclemanager_configuration['allowed_exts'] = mosGetParam($_POST, 'allowed_exts', "");
    $vehiclemanager_configuration['allowed_exts_img'] = mosGetParam($_POST, 'allowed_exts_img', "");
    $vehiclemanager_configuration['allowed_exts_video'] = mosGetParam($_POST, 'allowed_exts_video', "");
    $vehiclemanager_configuration['allowed_exts_track'] = mosGetParam($_POST, 'allowed_exts_track', "");
    mosVehicleManagerOthers :: setParams();
  }

  function configure($option)
  {
    //configure_frontend
    global $my, $vehiclemanager_configuration, $database;
    global $mosConfig_absolute_path; //for 1.6
    $yesno[] = mosHTML :: makeOption('1', _VEHICLE_MANAGER_YES);
    $yesno[] = mosHTML :: makeOption('0', _VEHICLE_MANAGER_NO);
    
    $lists = array();
    
    $gtree = get_group_children_tree_vm();
    
    foreach($gtree as $g) {
        $t['value'] = $g->value;
        $t['role'] = str_replace('&nbsp;', '', $g->text);
        $t['count_car'] = '<input type="text" name="count_car' . $g->value . '" value="' . $vehiclemanager_configuration['user_manager_vm'][$g->value]['count_car'] . '" class="inputbox" size="3" maxlength="3" />';
        $t['count_foto'] = '<input type="text" name="count_foto' . $g->value . '" value="' . $vehiclemanager_configuration['user_manager_vm'][$g->value]['count_foto'] . '" class="inputbox" size="3" maxlength="3" />';
        $lists['user_manager_vm'][] = $t;
    }
    
    // _______________- community builder section -_______________
    $f = "";
    $s = explode(',', $vehiclemanager_configuration['cb_myvehicle']['registrationlevel']);
    for ($i = 0; $i < count($s); $i++)
        $f[] = mosHTML::makeOption($s[$i]);

    $lists['cb_myvehicle']['show'] = mosHTML :: RadioList($yesno, 'cb_show_myvehicle', 'class="inputbox"', $vehiclemanager_configuration['cb_myvehicle']['show'], 'value', 'text');

    $lists['cb_myvehicle']['registrationlevel'] = mosHTML::selectList($gtree, 'cb_myvehicle_registrationlevel[]', 'size="4" multiple="multiple"', 'value', 'text', $f);

    $f = "";
    $s = explode(',', $vehiclemanager_configuration['cb_edit']['registrationlevel']);
    for ($i = 0; $i < count($s); $i++)
        $f[] = mosHTML::makeOption($s[$i]);

    $lists['cb_edit']['show'] = mosHTML :: RadioList($yesno, 'cb_show_edit', 'class="inputbox"', $vehiclemanager_configuration['cb_edit']['show'], 'value', 'text');

    $lists['cb_edit']['registrationlevel'] = mosHTML::selectList($gtree, 'cb_edit_registrationlevel[]', 'size="4" multiple="multiple"', 'value', 'text', $f);

    $f = "";
    $s = explode(',', $vehiclemanager_configuration['cb_rent']['registrationlevel']);
    for ($i = 0; $i < count($s); $i++)
        $f[] = mosHTML::makeOption($s[$i]);

    $lists['cb_rent']['show'] = mosHTML :: RadioList($yesno, 'cb_show_rent', 'class="inputbox"', $vehiclemanager_configuration['cb_rent']['show'], 'value', 'text');

    $lists['cb_rent']['registrationlevel'] = mosHTML::selectList($gtree, 'cb_rent_registrationlevel[]', 'size="4" multiple="multiple"', 'value', 'text', $f);

    $f = "";
    $s = explode(',', $vehiclemanager_configuration['cb_buy']['registrationlevel']);
    for ($i = 0; $i < count($s); $i++)
        $f[] = mosHTML::makeOption($s[$i]);

    $lists['cb_buy']['show'] = mosHTML :: RadioList($yesno, 'cb_show_buy', 'class="inputbox"', $vehiclemanager_configuration['cb_buy']['show'], 'value', 'text');

    $lists['cb_buy']['registrationlevel'] = mosHTML::selectList($gtree, 'cb_buy_registrationlevel[]', 'size="4" multiple="multiple"', 'value', 'text', $f);

    $f = "";
    $s = explode(',', $vehiclemanager_configuration['cb_history']['registrationlevel']);
    for ($i = 0; $i < count($s); $i++)
        $f[] = mosHTML::makeOption($s[$i]);

    $lists['cb_history']['show'] = mosHTML :: RadioList($yesno, 'cb_show_history', 'class="inputbox"', $vehiclemanager_configuration['cb_history']['show'], 'value', 'text');

    $lists['cb_history']['registrationlevel'] = mosHTML::selectList($gtree, 'cb_history_registrationlevel[]', 'size="4" multiple="multiple"', 'value', 'text', $f);
    // _______________- end community builder section -_______________

    $f = "";
    $s = explode(',', $vehiclemanager_configuration['reviews']['registrationlevel']);
    for ($i = 0; $i < count($s); $i++)
        $f[] = mosHTML::makeOption($s[$i]);

    $lists['reviews']['show'] = mosHTML :: RadioList($yesno, 'reviews_show', 'class="inputbox"', $vehiclemanager_configuration['reviews']['show'], 'value', 'text');
    $lists['reviews']['registrationlevel'] = mosHTML::selectList($gtree, 'reviews_registrationlevel[]', 'size="4" multiple="multiple"', 'value', 'text', $f);

    $lists['owner']['show'] = mosHTML :: RadioList($yesno, 'owner_show', 'class="inputbox"', $vehiclemanager_configuration['owner']['show'], 'value', 'text');

    //********** Calendar list ************
    $f = "";
    $s = explode(',', $vehiclemanager_configuration['calendarlist']['registrationlevel']);
    for ($i = 0; $i < count($s); $i++)
        $f[] = mosHTML::makeOption($s[$i]);
    $lists['calendarlist']['show'] = mosHTML :: RadioList($yesno, 'calendarlist_show', 'class="inputbox"', $vehiclemanager_configuration['calendarlist']['show'], 'value', 'text');
    $lists['calendarlist']['registrationlevel'] = mosHTML::selectList($gtree, 'calendarlist_registrationlevel[]', 'size="4" multiple="multiple"', 'value', 'text', $f);
    //********** END Calendar list ************

    $f = "";
    $s = explode(',', $vehiclemanager_configuration['rentrequest']['registrationlevel']);
    for ($i = 0; $i < count($s); $i++)
        $f[] = mosHTML::makeOption($s[$i]);

    $lists['rentstatus']['show'] = mosHTML :: RadioList($yesno, 'rentstatus_show', 'class="inputbox"', $vehiclemanager_configuration['rentstatus']['show'], 'value', 'text');

    $lists['rentrequest']['registrationlevel'] = mosHTML::selectList($gtree, 'rentrequest_registrationlevel[]', 'size="4" multiple="multiple"', 'value', 'text', $f);

    $f = "";
    $s = explode(',', $vehiclemanager_configuration['buyrequest']['registrationlevel']);
    for ($i = 0; $i < count($s); $i++)
        $f[] = mosHTML::makeOption($s[$i]);

    $lists['buystatus']['show'] = mosHTML :: RadioList($yesno, 'buystatus_show', 'class="inputbox"', $vehiclemanager_configuration['buystatus']['show'], 'value', 'text');

    $lists['buyrequest']['registrationlevel'] = mosHTML::selectList($gtree, 'buyrequest_registrationlevel[]', 'size="4" multiple="multiple"', 'value', 'text', $f);
   
    $lists['special_price']['show'] = mosHTML :: RadioList($yesno, 'special_price', 'class="inputbox"', $vehiclemanager_configuration['special_price']['show'], 'value', 'text');
    $f = "";
    $s = explode(',', $vehiclemanager_configuration ['Location_vehicle']['registrationlevel']);
    for ($i = 0; $i < count($s); $i++)
        $f[] = mosHTML::makeOption($s[$i]);

    $lists['Location_vehicle']['show'] = mosHTML :: RadioList($yesno, 'Location_show_vehicle', 'class="inputbox"', $vehiclemanager_configuration['Location_vehicle']['show'], 'value', 'text');

    $lists['Location_vehicle']['registrationlevel'] = mosHTML::selectList($gtree, 'Location_vehicle_registrationlevel[]', 'size="4" multiple="multiple"', 'value', 'text', $f);

    $f = "";
    $s = explode(',', $vehiclemanager_configuration ['contacts']['registrationlevel']);
    for ($i = 0; $i < count($s); $i++)
        $f[] = mosHTML::makeOption($s[$i]);

    $lists['Contacts']['show'] = mosHTML :: RadioList($yesno, 'Contacts_show_vehicle', 'class="inputbox"', $vehiclemanager_configuration['Contacts']['show'], 'value', 'text');

    $lists['contacts']['registrationlevel'] = mosHTML::selectList($gtree, 'contacts_registrationlevel[]', 'size="4" multiple="multiple"', 'value', 'text', $f);

    $f = "";
    $s = explode(',', $vehiclemanager_configuration ['Reviews_vehicle']['registrationlevel']);
    for ($i = 0; $i < count($s); $i++)
        $f[] = mosHTML::makeOption($s[$i]);

    $lists['Reviews_vehicle']['show'] = mosHTML :: RadioList($yesno, 'Reviews_show_vehicle', 'class="inputbox"', $vehiclemanager_configuration['Reviews_vehicle']['show'], 'value', 'text');

    $lists['Reviews_vehicle']['registrationlevel'] = mosHTML::selectList($gtree, 'Reviews_vehicle_registrationlevel[]', 'size="4" multiple="multiple"', 'value', 'text', $f);

    $f = "";
    $s = explode(',', $vehiclemanager_configuration['edocs']['registrationlevel']);
    for ($i = 0; $i < count($s); $i++) {
        $f[] = mosHTML::makeOption($s[$i]);
    }

    $lists['edocs']['show'] = mosHTML :: RadioList($yesno, 'edocs_show', 'class="inputbox"', $vehiclemanager_configuration['edocs']['show'], 'value', 'text');
    $lists['videos_tracks']['show'] = mosHTML::RadioList($yesno, 'videos_tracks_allow', 'class="inputbox"', $vehiclemanager_configuration['videos_tracks']['show'], 'value', 'text');
    $lists['videos']['location'] = '<input disabled="disabled" type="text" name="videos_location" value="' . $vehiclemanager_configuration['videos']['location'] . '" class="inputbox" size="50" maxlength="50" title="" />';
    $lists['tracks']['location'] = '<input disabled="disabled" type="text" name="tracks_location" value="' . $vehiclemanager_configuration['tracks']['location'] . '" class="inputbox" size="50" maxlength="50" title="" />';
    $lists['edocs']['registrationlevel'] = mosHTML::selectList($gtree, 'edocs_registrationlevel[]', 'size="4" multiple="multiple"', 'value', 'text', $f);

    $f = "";
    $s = explode(',', $vehiclemanager_configuration['price']['registrationlevel']);
    for ($i = 0; $i < count($s); $i++)
        $f[] = mosHTML::makeOption($s[$i]);

    $lists['price']['show'] = mosHTML :: RadioList($yesno, 'price_show', 'class="inputbox"', $vehiclemanager_configuration['price']['show'], 'value', 'text');

    $lists['price']['registrationlevel'] = mosHTML::selectList($gtree, 'price_registrationlevel[]', 'size="4" multiple="multiple"', 'value', 'text', $f);

    //show location map
    $lists['location_map'] = mosHTML :: RadioList($yesno, 'location_map', 'class="inputbox"', $vehiclemanager_configuration['location_map'], 'value', 'text');
            
    $lists['thumb_param']['show'] = mosHTML :: RadioList($yesno, 'thumb_param_show', 'class="inputbox"',
     $vehiclemanager_configuration['thumb_param']['show'], 'value', 'text');
    //show image for feature manager
    $lists['manager_feature_image'] = mosHTML :: RadioList($yesno, 'manager_feature_image', 'class="inputbox"', $vehiclemanager_configuration['manager_feature_image'], 'value', 'text');
    //show category for feature manager
    $lists['manager_feature_category'] = mosHTML :: RadioList($yesno, 'manager_feature_category', 'class="inputbox"', $vehiclemanager_configuration['manager_feature_category'], 'value', 'text');

    //show sale_separator
    $lists['sale_separator'] = mosHTML :: RadioList($yesno, 'sale_separator', 'class="inputbox"', $vehiclemanager_configuration['sale_separator'], 'value', 'text');

    $lists['extra1'] = mosHTML :: RadioList($yesno, 'extra1', 'class="inputbox"', $vehiclemanager_configuration['extra1'], 'value', 'text');
    $lists['extra2'] = mosHTML :: RadioList($yesno, 'extra2', 'class="inputbox"', $vehiclemanager_configuration['extra2'], 'value', 'text');
    $lists['extra3'] = mosHTML :: RadioList($yesno, 'extra3', 'class="inputbox"', $vehiclemanager_configuration['extra3'], 'value', 'text');
    $lists['extra4'] = mosHTML :: RadioList($yesno, 'extra4', 'class="inputbox"', $vehiclemanager_configuration['extra4'], 'value', 'text');
    $lists['extra5'] = mosHTML :: RadioList($yesno, 'extra5', 'class="inputbox"', $vehiclemanager_configuration['extra5'], 'value', 'text');
    $lists['extra6'] = mosHTML :: RadioList($yesno, 'extra6', 'class="inputbox"', $vehiclemanager_configuration['extra6'], 'value', 'text');
    $lists['extra7'] = mosHTML :: RadioList($yesno, 'extra7', 'class="inputbox"', $vehiclemanager_configuration['extra7'], 'value', 'text');
    $lists['extra8'] = mosHTML :: RadioList($yesno, 'extra8', 'class="inputbox"', $vehiclemanager_configuration['extra8'], 'value', 'text');
    $lists['extra9'] = mosHTML :: RadioList($yesno, 'extra9', 'class="inputbox"', $vehiclemanager_configuration['extra9'], 'value', 'text');
    $lists['extra10'] = mosHTML :: RadioList($yesno, 'extra10', 'class="inputbox"', $vehiclemanager_configuration['extra10'], 'value', 'text');

    $lists['foto']['high'] = '<input type="text" name="foto_high"
    value="' . $vehiclemanager_configuration['foto']['high'] .
            '" class="inputbox" size="4" maxlength="4" title="" />';

    $lists['foto']['width'] = '<input type="text" name="foto_width"
    value="' . $vehiclemanager_configuration['foto']['width'] .
            '" class="inputbox" size="4" maxlength="4" title="" />';

    $lists['fotomain']['high'] = '<input type="text" name="fotomain_high"
    value="' . $vehiclemanager_configuration['fotomain']['high'] .
            '" class="inputbox" size="4" maxlength="4" title="" />';

    $lists['fotomain']['width'] = '<input type="text" name="fotomain_width"
    value="' . $vehiclemanager_configuration['fotomain']['width'] .
            '" class="inputbox" size="4" maxlength="4" title="" />';

    $lists['fotogallery']['high'] = '<input type="text" name="fotogallery_high"
    value="' . $vehiclemanager_configuration['fotogallery']['high'] .
            '" class="inputbox" size="4" maxlength="4" title="" />';

    $lists['fotogallery']['width'] = '<input type="text" name="fotogallery_width"
    value="' . $vehiclemanager_configuration['fotogallery']['width'] .
            '" class="inputbox" size="4" maxlength="4" title="" />';

    $lists['fotogal']['high'] = '<input type="text" name="fotogal_high"
    value="' . $vehiclemanager_configuration['fotogal']['high'] .
            '" class="inputbox" size="4" maxlength="4" title="" />';

    $lists['fotogal']['width'] = '<input type="text" name="fotogal_width"
    value="' . $vehiclemanager_configuration['fotogal']['width'] .
            '" class="inputbox" size="4" maxlength="4" title="" />';

    $lists['fotoupload']['high'] = '<input type="text" name="fotoupload_high"
    value="' . $vehiclemanager_configuration['fotoupload']['high'] .
            '" class="inputbox" size="4" maxlength="4" title="" />';

    $lists['fotoupload']['width'] = '<input type="text" name="fotoupload_width"
    value="' . $vehiclemanager_configuration['fotoupload']['width'] .
            '" class="inputbox" size="4" maxlength="4" title="" />';

    $lists['page']['items'] = '<input type="text" name="page_items"
    value="' . $vehiclemanager_configuration['page']['items'] .
            '" class="inputbox" size="3" maxlength="5" title="" />';

    //add for show in category picture
    $lists['cat_pic']['show'] = mosHTML :: RadioList($yesno, 'cat_pic_show', 'class="inputbox"', $vehiclemanager_configuration['cat_pic']['show'], 'value', 'text');

    //add for show subcategory
    $lists['subcategory']['show'] = mosHTML :: RadioList($yesno, 'subcategory_show', 'class="inputbox"', $vehiclemanager_configuration['subcategory']['show'], 'value', 'text');

  
  //********** Owners list ************
    $f = "";
    $s = explode(',', $vehiclemanager_configuration['ownerslist']['registrationlevel']);
    for ($i = 0; $i < count($s); $i++)
        $f[] = mosHTML::makeOption($s[$i]);
    $lists['ownerslist']['show'] = mosHTML :: RadioList($yesno, 'ownerslist_show', 'class="inputbox"', $vehiclemanager_configuration['ownerslist']['show'], 'value', 'text');
    $lists['ownerslist']['registrationlevel'] = mosHTML::selectList($gtree, 'ownerslist_registrationlevel[]', 'size="4" multiple="multiple"', 'value', 'text', $f);
  //********** END Owners list ************

    $lists['calendar']['show'] = mosHTML :: RadioList($yesno, 'calendar_show', 'class="inputbox"', $vehiclemanager_configuration['calendar']['show'], 'value', 'text');

  //configure_backend
   
    $lists['edocs']['allow'] = mosHTML :: RadioList($yesno, 'edocs_allow', 'class="inputbox"', $vehiclemanager_configuration['edocs']['allow'], 'value', 'text');

    $lists['edocs']['location'] = '<input type="text" name="edocs_location" readonly="readonly" value="' . $vehiclemanager_configuration['edocs']['location'] . '" class="inputbox" size="50" maxlength="50" title="" />';

    $lists['calendar']['placeholder'] = '<input type="text" name="calendar_placeholder" value="' . $vehiclemanager_configuration['calendar']['placeholder'] . '" class="inputbox" size="50" maxlength="50" title=""/>';

    $lists['featuredmanager']['placeholder'] = '<input type="text" name="featuredmanager_placeholder" value="' . $vehiclemanager_configuration['featuredmanager']['placeholder'] . '" class="inputbox" size="50" maxlength="500" title=""/>';

    $lists['currency'] = '<input type="text" name="currency" value="' . $vehiclemanager_configuration['currency'] . '" class="inputbox" size="50" maxlength="500" title=""/>';
    
    //PayPal
    
    $lists['allowed_exts'] = '<input type="text" name="allowed_exts" value="' . $vehiclemanager_configuration['allowed_exts'] . '" class="inputbox" size="50" maxlength="1500" title=""/>';
    $lists['allowed_exts_img'] = '<input type="text" name="allowed_exts_img" value="' . $vehiclemanager_configuration['allowed_exts_img'] . '" class="inputbox" size="50" maxlength="1500" title=""/>';
    $lists['allowed_exts_video'] = '<input type="text" name="allowed_exts_video" value="' . $vehiclemanager_configuration['allowed_exts_video'] . '" class="inputbox" size="50" maxlength="1500" title=""/>';
    $lists['allowed_exts_track'] = '<input type="text" name="allowed_exts_track" value="' . $vehiclemanager_configuration['allowed_exts_track'] . '" class="inputbox" size="50" maxlength="1500" title=""/>';
    $txt = '';
    $money_ditlimer = array();
    $money_ditlimer[] = JHtml::_('select.option', ".", "Point (12.134.123,12)");
    $money_ditlimer[] = JHtml::_('select.option', ",", "Comma (12,134,123.12)");
    $money_ditlimer[] = JHtml::_('select.option', "space", "Space (12 134 123,12)");
    $money_ditlimer[] = JHtml::_('select.option', "other", "Youre ditlimer: ");

    $price_unit_show = array();
    $price_unit_show[] = mosHTML :: makeOption('1', _VEHICLE_MANAGER_PRICE_UNIT_SHOW_AFTER);
    $price_unit_show[] = mosHTML :: makeOption('0', _VEHICLE_MANAGER_PRICE_UNIT_SHOW_BEFORE);

    $selecter = '';
    switch ($vehiclemanager_configuration['price_format']) {
        case '.':
            $selecter = '.';
            break;
        case ',':
            $selecter = ',';
            break;
        case '&nbsp;':
            $selecter = 'space';
            break;
        default:
            $selecter = 'other';
    }
    $lists['price_unit_show'] = mosHTML :: RadioList($price_unit_show, 'price_unit_show', 'class="inputbox"', $vehiclemanager_configuration['price_unit_show'], 'value', 'text');

    $lists['money_ditlimer'] = mosHTML::selectList($money_ditlimer, 'money_select', 'size="1"  onchange="set_pricetype(this)"', 'value', 'text', $selecter);
    $lists['patern'] = '<input id="patt" type="hidden" readonly="true" value="' . $vehiclemanager_configuration['price_format'] . '" name="patern" size="2"/>';

    $lists['date_format'] = '<input type="text" name="date_format" value="' . $vehiclemanager_configuration['date_format'] . '" class="inputbox"  title="" />';
    $lists['datetime_format'] = '<input type="text" name="datetime_format" value="' . $vehiclemanager_configuration['datetime_format'] . '" class="inputbox" title="" />';
    HTML_vehiclemanager :: showConfiguration($lists, $option, $txt);
  }

  function getSelect(){
    $db = JFactory::getDBO();
    $condtion = array(0 => '\'payment\'');
    $condtionatype = join(',',$condtion);
    
    if(JVERSION >= '1.6.0')
    {
      $query = "SELECT extension_id as id,name,element,enabled as published
            FROM #__extensions
            WHERE folder in ($condtionatype) AND enabled=1";
    }
    else
    {
      $query = "SELECT id,name,element,published
            FROM #__plugins
            WHERE folder in ($condtionatype) AND published=1";
    }
    $db->setQuery($query);
    $gatewayplugin = $db->loadobjectList();
    if(!getNamePluginForValSelect($gatewayplugin)){

    }else{
        return getNamePluginForValSelect($gatewayplugin);
    }

  }
       
  function getNamePluginForValSelect($gatewayplugin){
          
          $new_plugin_name = array();
          
          $retr = count($gatewayplugin);
          
           if($retr>0){
          
                for($i=0;$i<$retr;$i++){
                      $plugin_name_strtolower = mb_strtolower(trim($gatewayplugin[$i]->name)); 
                      $plugin_name_mass = explode(" ", $plugin_name_strtolower);
                      $lenght_mass = count($plugin_name_mass);                       
                      array_push($new_plugin_name,$plugin_name_mass[$lenght_mass-1]);
                }
                return $new_plugin_name;
          
          }
          else{
                return false;
          }
          
      }

  //****************   begin for manage reviews   *******************
  function manage_review_s($option, $sorting)
  {
    global $database, $mainframe, $mosConfig_list_limit;

    $limit = $mainframe->getUserStateFromRequest("viewlistlimit", 'limit', $mosConfig_list_limit);
    $limitstart = $mainframe->getUserStateFromRequest("view{$option}limitstart", 'limitstart', 0);

    $database->setQuery("SELECT count(*) FROM #__vehiclemanager_review;");
    $total = $database->loadResult();
    echo $database->getErrorMsg();

    $pageNav = new JPagination($total, $limitstart, $limit); // for J 1.6
  //********************   begin request for reviews manager   **********************
    //if for sorting
    if ($sorting != "")
    {
        $request_string = "SELECT b.id as fk_vehicleid, a.id as review_id, b.vtitle as vehicle_title, " .
                " GROUP_CONCAT(c.title SEPARATOR ', ') as title_catigory, a.title as title_review, a.comment," .
                " a.user_name, a.date, a.rating,  a.published as published " .
                " FROM #__vehiclemanager_review as a, #__vehiclemanager_vehicles as b, #__vehiclemanager_main_categories as c, " .
                " #__vehiclemanager_categories as vc " .
                " WHERE a.fk_vehicleid = b.id AND vc.iditem = b.id and c.id = vc.idcat " .
                " GROUP BY a.id " .
                " ORDER by " . $sorting .
                " LIMIT $pageNav->limitstart,$pageNav->limit;";
        $database->setQuery($request_string);
        $reviews = $database->loadObjectList();
    } else
    {
        $request_string = "SELECT b.id as fk_vehicleid, a.id as review_id, b.vtitle as vehicle_title, " .
                " GROUP_CONCAT(c.title SEPARATOR ', ') as title_catigory, a.title as title_review, " .
                " a.comment, a.user_name, a.date, a.rating,  a.published" .
                " FROM #__vehiclemanager_review as a, #__vehiclemanager_vehicles as b, #__vehiclemanager_main_categories as c, " .
                " #__vehiclemanager_categories as vc " .
                " WHERE a.fk_vehicleid = b.id AND vc.iditem = b.id and c.id = vc.idcat " .
                " GROUP BY a.id " .
                " ORDER by date " .
                " LIMIT $pageNav->limitstart,$pageNav->limit;";
        $database->setQuery($request_string);
        $reviews = $database->loadObjectList();
    }

  //**************   end request for reviews manager   ***************************
    HTML_vehiclemanager :: showManageReviews($option, $pageNav, $reviews);
  }

  //*********************   end for manage reviews   ****************************

  function publish_manage_review($vid, $publish, $option)
  {
    global $database;

    $database->setQuery("UPDATE #__vehiclemanager_review SET published = $publish WHERE id  = $vid ");
    if (!$database->query())
    {
        echo "<script> alert(\"" . $database->getErrorMsg() . "\"); window.history.go(-1); </script>\n";
        exit();
    }

    mosRedirect("index.php?option=$option&task=manage_review");
  }


  function showFeaturedManager($option)
  {
    global $database, $mainframe, $mosConfig_list_limit, $menutype;

    $section = "com_vehiclemanager";

    $query = "SELECT * FROM #__vehiclemanager_feature";
    $database->setQuery($query);
    $features = $database->loadObjectList();

    $sectionid = $mainframe->getUserStateFromRequest("sectionid{$section}{$section}", 'sectionid', 0);
    $limit = $mainframe->getUserStateFromRequest("viewlistlimit", 'limit', $mosConfig_list_limit);
    $limitstart = $mainframe->getUserStateFromRequest("view{$section}limitstart", 'limitstart', 0);
    $levellimit = $mainframe->getUserStateFromRequest("view{$option}limit$menutype", 'levellimit', 10);

    $total = count($features);

    $pageNav = new JPagination($total, $limitstart, $limit); // for J 1.6

    $features = array_slice($features, $pageNav->limitstart, $pageNav->limit);

    HTML_vehiclemanager :: showFeaturedManager($features, $pageNav);
  }

  function editFeaturedManager($section = '', $uid = 0)
  {
    global $database, $my, $acl, $vehiclemanager_configuration;
    global $mosConfig_absolute_path, $mosConfig_live_site;

    $row = new mosVehicleManager_feature($database); // for 1.6
    // load the row from the db table
    $row->load($uid);

    // build the html radio buttons for published
    $lists['published'] = mosHTML::yesnoRadioList('published', 'class="inputbox"', $row->published);

    
    //Select list for number of doors
    
    $select_value=mosHtml::makeOption($row->categories,$row->categories)->value; // select value
    $categories[] = mosHtml::makeOption("", _VEHICLE_MANAGER_OPTION_SELECT);
    if($vehiclemanager_configuration['featuredmanager']['placeholder']!='')
        $categ = explode(',', $vehiclemanager_configuration['featuredmanager']['placeholder']);
    else
        $categ = array();
    if (isset($row->categories)and !in_array($select_value,$categ))
        $categories[] = mosHtml::makeOption($row->categories, $row->categories);    
    for ($i = 0; $i < count($categ); $i++)
        $categories[] = mosHtml::makeOption($categ[$i], $categ[$i]);
    $lists['categories'] = mosHTML :: selectList($categories, 'categories', 'class="inputbox" size="1"', 'value', 'text', $row->categories);

    HTML_vehiclemanager::editFeaturedManager($row, $lists);
  }

  function saveFeaturedManager()
  {
    global $database, $mosConfig_absolute_path;

    $row = new mosVehicleManager_feature($database); // for 1.6
    $post = JRequest::get('post', JREQUEST_ALLOWHTML);

    $idd = $_POST['id'];

    if (array_key_exists("del_main_photo", $_POST) && $idd)
    {
        $del_main_photo = $_POST['del_main_photo'];
        if ($del_main_photo != '')
        {
            $database->setQuery("SELECT image_link FROM #__vehiclemanager_feature WHERE id=$idd");
            $image_link = $database->loadResult();
            $database->setQuery("UPDATE #__vehiclemanager_feature SET image_link='' WHERE id=$idd");
            if (!$database->query())
            {
                echo "<script> alert('" . $database->getErrorMsg() . "');</script>\n";
            }
            unlink($mosConfig_absolute_path . '/components/com_vehiclemanager/featured_ico/' . $image_link);
        }
    }
    //save main image
    if ($_FILES['image_link']['name'] != '')
    {
        $uploaddir = $mosConfig_absolute_path . '/components/com_vehiclemanager/featured_ico/';
        $code = guid();
        $uploadfile = $uploaddir . $code . "_" . $_FILES['image_link']['name'];
        $file_name = $code . "_" . $_FILES['image_link']['name'];
        if (copy($_FILES['image_link']['tmp_name'], $uploadfile))
        {
            if($idd){
                $database->setQuery("UPDATE #__vehiclemanager_feature SET image_link='$file_name' WHERE id=$idd");
                if (!$database->query())
                {
                    echo "<script> alert('" . $database->getErrorMsg() . "');</script>\n";
                }               
            }else{
                $row->image_link = $file_name;
            }

        }
    }
    if (!$row->bind($post))
    {
        echo "<script> alert('" . $row->getError() . "'); window.history.go(-1); </script>\n";
        exit();
    }

    if (!$row->check())
    {
        echo "<script> alert('" . $row->getError() . "'); window.history.go(-1); </script>\n";
        exit();
    }

    if (!$row->store())
    {
        echo "<script> alert('" . $row->getError() . "'); window.history.go(-1); </script>\n";
        exit();
    }

    mosRedirect('index.php?option=com_vehiclemanager&section=featured_manager');
  }

  function cancelFeaturedManager()
  {
    global $database;
    $row = new mosVehicleManager_feature($database); // for 1.6
    $row->bind($_POST);
    mosRedirect('index.php?option=com_vehiclemanager&section=featured_manager');
  }

  function removeFeaturedManager($section, $fids)
  {
    global $database;
    
    if (count($fids) < 1)
    {
        echo "<script> alert('Select a feature to delete'); window.history.go(-1);</script>\n";
        exit;
    }

    foreach ($fids as $fid){
        removeFeaturedManagerFromDB($fid);
    }
    
    mosRedirect('index.php?option=com_vehiclemanager&section=featured_manager');
    
  }

  function removeFeaturedManagerFromDB($fid)
  {
    global $database, $my, $mosConfig_absolute_path;

    $database->setQuery("SELECT image_link FROM #__vehiclemanager_feature WHERE id=$fid");
    $image_link = $database->loadResult();
    unlink($mosConfig_absolute_path . '/components/com_vehiclemanager/featured_ico/' . $image_link);

    $sql = "DELETE FROM #__vehiclemanager_feature WHERE id = $fid ";
    $database->setQuery($sql);
    $database->query();
  }

  function publishFeaturedManager($section, $featureid = null, $cid = null, $publish = 1)
  {
    global $database, $my;

    if (!is_array($cid))
        $cid = array();
    if ($featureid)
        $cid[] = $featureid;

    if (count($cid) < 1)
    {
        $action = $publish ? _PUBLISH : _DML_UNPUBLISH;
        echo "<script> alert('" . _DML_SELECTCATTO . " $action'); window.history.go(-1);</script>\n";
        exit;
    }

    $cids = implode(',', $cid);

    $query = "UPDATE #__vehiclemanager_feature SET published='$publish'"
            . "\nWHERE id IN ($cids)";
    $database->setQuery($query);
    if (!$database->query())
    {
        echo "<script> alert(\"" . $database->getErrorMsg() . "\"); window.history.go(-1); </script>\n";
        exit();
    }

    if (count($cid) == 1)
    {
        $row = new mosVehicleManager_feature($database); // for 1.6
        $row->checkin($cid[0]);
    }
    mosRedirect('index.php?option=com_vehiclemanager&section=featured_manager');
  }

  function showLanguageManager($option)
  {
    global $database, $mainframe, $mosConfig_list_limit, $menutype, $mosConfig_absolute_path;

    

    $section = "com_vehiclemanager";

    $search['const'] = mosGetParam($_POST, 'search_const', '');
    $search['const_value'] = mosGetParam($_POST, 'search_const_value', '');
    $search['languages'] = $mainframe->getUserStateFromRequest("search_languages{$option}", 'search_languages', '');
    $search['sys_type']  = $mainframe->getUserStateFromRequest("search_sys_type{$option}", 'search_sys_type', '');


    $where_query = array();
    if ($search['const'] != '')
        $where_query[] = "c.const LIKE '%" . $search['const'] . "%'";
    if ($search['const_value'] != '')
        $where_query[] = "cl.value_const LIKE '%" . $search['const_value'] . "%'";
    if ($search['languages'] != '')
        $where_query[] = "cl.fk_languagesid = " .$database->quote( $search['languages']) . " ";
    if ($search['sys_type'] != '')
        $where_query[] = "c.sys_type LIKE '%" . $search['sys_type'] . "%'";

    $where = "";
    $i = 0;
    if (count($where_query) > 0)
        $where = "WHERE ";
    foreach ($where_query as $item) {
        if ($i == 0)
            $where .= "( $item ) ";
        else
            $where .= "AND ( $item ) ";
        $i++;
    }
    $limit = $mainframe->getUserStateFromRequest("viewlistlimit", 'limit', $mosConfig_list_limit);
    $limitstart = $mainframe->getUserStateFromRequest("view{$section}limitstart", 'limitstart', 0);
    $levellimit = $mainframe->getUserStateFromRequest("view{$option}limit$menutype", 'levellimit', 10);

    $query = "SELECT count(cl.id) ";
    $query .= "FROM #__vehiclemanager_const_languages as cl ";
    $query .= "LEFT JOIN #__vehiclemanager_languages AS l ON cl.fk_languagesid=l.id ";
    $query .= "LEFT JOIN #__vehiclemanager_const AS c ON cl.fk_constid=c.id $where";
    
    $database->setQuery($query);
    $total = $database->loadResult();
    $pageNav = new JPagination($total, $limitstart, $limit); // for J 1.6

    $query = "SELECT cl.id, cl.value_const, c.sys_type, l.title, c.const ";
    $query .= " FROM #__vehiclemanager_const_languages as cl ";
    $query .= " LEFT JOIN #__vehiclemanager_languages AS l ON cl.fk_languagesid=l.id ";
    $query .= " LEFT JOIN #__vehiclemanager_const AS c ON cl.fk_constid=c.id $where";
    $query .= " LIMIT $pageNav->limitstart,$pageNav->limit";
    $database->setQuery($query);
    $const_languages = $database->loadObjectList();

    $sectionid = $mainframe->getUserStateFromRequest("sectionid{$section}{$section}", 'sectionid', 0);


  //print_r($total);exit;
    
  //print_r($pageNav);exit;
    //$const_languages = array_slice($const_languages, $pageNav->limitstart, $pageNav->limit);

    $query = "SELECT sys_type FROM #__vehiclemanager_const GROUP BY sys_type";
    $database->setQuery($query);
    $sys_types = $database->loadObjectList();

    $sys_type_row[] = mosHTML::makeOption('', '--Select sys type--');
    foreach ($sys_types as $sys_type) {
        $sys_type_row[] = mosHTML::makeOption($sys_type->sys_type, $sys_type->sys_type);
    }

    $search['sys_type'] = mosHTML :: selectList($sys_type_row, 'search_sys_type', 'class="inputbox input-medium" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $search['sys_type']);

    $query = "SELECT id, title FROM #__vehiclemanager_languages";
    $database->setQuery($query);
    $languages = $database->loadObjectList();

    $languages_row[] = mosHTML::makeOption('', '--Select language--');
    foreach ($languages as $language) {
        $languages_row[] = mosHTML::makeOption($language->id, $language->title);
    }

    $search['languages'] = mosHTML :: selectList($languages_row, 'search_languages', 'class="inputbox input-medium" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $search['languages']);
    if(JRequest::getVar('task','') == 'loadLang'){
        vmLittleThings::loadConstVechicle();
        vmLittleThings::language_check();  
    }
    
    
    HTML_vehiclemanager :: showLanguageManager($const_languages, $pageNav, $search);
  }

  function editLanguageManager($section = '', $uid = 0)
  {
    global $database, $my, $acl, $vehiclemanager_configuration;
    global $mosConfig_absolute_path, $mosConfig_live_site;

    $row = new mosVehicleManager_language($database); // for 1.6
    // load the row from the db table
    $row->load($uid);

    $query = "SELECT * FROM #__vehiclemanager_const WHERE id = " . $row->fk_constid;
    $database->setQuery($query);
    $const = $database->loadObject();

    $lists['const'] = $const->const;
    $lists['sys_type'] = $const->sys_type;

    $query = "SELECT title FROM #__vehiclemanager_languages WHERE id = " . $row->fk_languagesid;
    $database->setQuery($query);
    $language = $database->loadResult();

    $lists['languages'] = $language;

    HTML_vehiclemanager::editLanguageManager($row, $lists);
  }

  function saveLanguageManager()
  {
    global $database, $mosConfig_absolute_path;

    $row = new mosVehicleManager_language($database); // for 1.6
    $post = JRequest::get('post', JREQUEST_ALLOWHTML);

    if (!$row->bind($post))
    {
        echo "<script> alert(\"" . $row->getError() . "\"); window.history.go(-1); </script>\n";
        exit();
    }

    if (!$row->check())
    {
        echo "<script> alert(\"" . $row->getError() . "\"); window.history.go(-1); </script>\n";
        exit();
    }

    if (!$row->store())
    {
        echo "<script> alert(\"" . $row->getError() . "\"); window.history.go(-1); </script>\n";
        exit();
    }

    mosRedirect('index.php?option=com_vehiclemanager&section=language_manager');
  }

  function cancelLanguageManager()
  {
    global $database, $mosConfig_absolute_path;

    $row = new mosVehicleManager_feature($database); // for 1.6
    $row->bind($_POST);
    mosRedirect('index.php?option=com_vehiclemanager&section=language_manager');
  }



  function save_featured_category($option)
  {
    global $vehiclemanager_configuration;
    
    $vehiclemanager_configuration['featuredmanager']['placeholder'] = mosGetParam($_POST, 'featuredmanager_placeholder', "");

    mosVehicleManagerOthers :: setParams();
    
  }


  function rent_history($option, $vid){
    global $database, $my,$mainframe;
    if (!is_array($vid) || count($vid) !== 1){
      echo "<script> alert('Select one item to show'); window.history.go(-1);</script>\n";
      exit;
    }
    $vid_veh = implode(',', $vid);
    $select = "SELECT a.*, cc.name AS category, l.id as rentid, l.rent_from as rent_from, " .
            "l.rent_return as rent_return, l.rent_until as rent_until, " .
            "l.user_name as user_name, l.user_email as user_email " .
            "\nFROM #__vehiclemanager_vehicles AS a" .
            "\nLEFT JOIN #__vehiclemanager_main_categories AS cc ON cc.id = a.catid" .
            "\nLEFT JOIN #__vehiclemanager_rent AS l ON l.id = a.fk_rentid" .
            "\nWHERE a.id = $vid_veh";
  
    $database->setQuery($select);
    $vehicle = $database->loadObject();
    if($vehicle->listing_type=='2'){
        echo "<script> alert('This vehicle is not for rent'); window.history.go(-1);</script>\n";
        exit;
    }
    $vids = implode(',', $vid);
    $vids = getAssociateVehicle($vids);
    $vehicles_assoc[]= $vehicle;
    if($vids){
      $select = "SELECT a.*, cc.name AS category, l.id as rentid, l.rent_from as rent_from, " .
              "l.rent_return as rent_return, l.rent_until as rent_until, " .
              "l.user_name as user_name, l.user_email as user_email " .
              "\nFROM #__vehiclemanager_vehicles AS a" .
              "\nLEFT JOIN #__vehiclemanager_main_categories AS cc ON cc.id = a.catid" .
              "\nLEFT JOIN #__vehiclemanager_rent AS l ON l.id = a.fk_rentid" .
              "\nWHERE a.id in ($vids)";

      $database->setQuery($select);
      $vehicles_assoc = $database->loadObjectList();
      
      //for rent or not
      $count = count($vehicles_assoc);
      for ($i = 0; $i < $count; $i++) {
        if ($vehicles_assoc[$i]->listing_type != 1){
          ?>
          <script type = "text/JavaScript" language = "JavaScript">
              alert('This vehicle has associated vehicle not for rent');
              window.history.go(-1);
          </script>
          <?php
          exit;
        }
      }
    }
    // get list of categories
    HTML_vehiclemanager:: showRentHistory($option, $vehicle, $vehicles_assoc,  $usermenu, "rent");
}

  function users_rent_history($option, $vid){
    global $database, $my,$mainframe;
    $owner = $mainframe->getUserStateFromRequest("owner_h{$option}", 'owner_h', '-1'); //add nik    
    $vehicle = '';
    $vehicles_assoc = '';
    $allrent = '';
    if($owner !=-1){
        $select = "SELECT l.rent_from as rent_from, " .
                "a.vtitle, a.vehicleid,l.rent_return as rent_return, l.rent_until as rent_until, " .
                "l.user_name as user_name, l.user_email as user_email " .
                "\n FROM #__vehiclemanager_rent AS l" .
                "\n LEFT JOIN #__vehiclemanager_vehicles AS a ON l.fk_vehicleid = a.id" .
                "\n WHERE l.user_name = ".$database->Quote($owner).
                "\n OR l.fk_userid = ".$database->Quote($owner) ." ORDER BY l.rent_return";
        $database->setQuery($select);
        $allrent = $database->loadObjectList();
    }
    $userlist[] = mosHTML :: makeOption('-1', 'Select User');
    $database->setQuery("SELECT DISTINCT fk_userid AS value, user_name AS text from #__vehiclemanager_rent ORDER BY user_name");
    $userlist = array_merge($userlist, $database->loadObjectList());
    foreach ($userlist as $value) {
        if(!$value->value)
            $value->value = $value->text;
    }
    $usermenu = mosHTML :: selectList($userlist, 'owner_h', 'class="inputbox input-medium" size="1" 
        onchange="document.adminForm.submit();"', 'value', 'text', $owner);

    HTML_vehiclemanager:: showUsersRentHistory($option, $allrent,  $usermenu);
}
function accept_buying_requests($option, $vids){
    global $database;
    foreach ($vids as $vid) {
      $buying_request = new mosVehicleManager_buying_request($database);
      $buying_request->load($vid);
      $datas[] = array('name' => $buying_request->customer_name,
          'email' => $buying_request->customer_email,
          'id' => $buying_request->fk_vehicleid);
      $buying_request->delete($vid);
    }
    mosRedirect("index.php?option=$option&task=buying_requests");
  }

  function decline_buying_requests($option, $bids)
  {
    global $database;
    foreach ($bids as $vid) {
        $buying_request = new mosVehicleManager_buying_request($database);
        $buying_request->load($vid);
        $datas[] = array('name' => $buying_request->customer_name,
            'email' => $buying_request->customer_email,
            'id' => $buying_request->fk_vehicleid);
        $tmp = $buying_request->decline();
        if ($tmp != null)
        {
            echo "<script> alert('" . $tmp . "'); window.history.go(-1); </script>\n";
            exit;
        }
    mosRedirect("index.php?option=$option&task=buying_requests");
  }
}

   function decline_rent_requests($option, $vids)
  {
    global $database, $vehiclemanager_configuration;
    $datas = array();
    foreach ($vids as $vid) {
        $rent_request = new mosVehicleManager_rent_request($database);
        $rent_request->load($vid);
        $tmp = $rent_request->decline();
        if ($tmp != null)
        {
            echo "<script> alert('" . $tmp . "'); window.history.go(-1); </script>\n";
            exit;
        }
        foreach ($datas as $c => $data) {
            if ($rent_request->user_email == $data['email'])
            {
                $datas[$c]['ids'][] = $rent_request->fk_vehicleid;
                continue 2;
            }
        }
        $datas[] = array('email' => $rent_request->user_email, 'name' => $rent_request->user_name, 'id' => $rent_request->fk_vehicleid);
        
    }
    mosRedirect("index.php?option=$option&task=rent_requests");
  }

  function accept_rent_requests($option, $vids)
  {
    global $database, $vehiclemanager_configuration;
  //    print_r($vid);exit;

   // $id = mosGetParam($_POST, 'id'); 

    
    $datas = array();
    foreach ($vids as $vid) {
        $rent_request = new mosVehicleManager_rent_request($database);
        $rent_request->load($vid);
        $tmp = $rent_request->accept();
        if ($tmp != null)
        {
            echo "<script> alert('" . $tmp . "'); window.history.go(-1); </script>\n";
            exit;
        }

        foreach ($datas as $c => $data) {
            if ($rent_request->user_email == $data['email'])
            {
                $datas[$c]['ids'][] = $rent_request->fk_vehicleid;
                continue 2;
            }
        }
        $datas[] = array('email' => $rent_request->user_email, 'name' => $rent_request->user_name, 'id' => $rent_request->fk_vehicleid);
      
    }
    mosRedirect("index.php?option=$option&task=rent_requests");
  }
