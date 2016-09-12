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
//require_once($mosConfig_absolute_path . "/libraries/joomla/plugin/helper.php");
jimport( 'joomla.plugin.helper' );
global $mosConfig_live_site;
///require_once($mosConfig_absolute_path . "/includes/HTML_toolbar.php");
require_once($mosConfig_absolute_path . "/administrator/includes/toolbar.php");
if (version_compare(JVERSION, "3.0.0", "lt"))
    require_once($mosConfig_absolute_path . "/libraries/joomla/html/toolbar.php");
    

require_once($mosConfig_absolute_path . "/components/com_vehiclemanager/vehiclemanager.php");
require_once($mosConfig_absolute_path . "/components/com_vehiclemanager/functions.php");
require_once($mosConfig_absolute_path . "/administrator/components/com_vehiclemanager/menubar_ext.php");

$doc = JFactory::getDocument();
$doc->addStyleSheet( $mosConfig_live_site . 
      '/components/com_vehiclemanager/includes/jquery-ui.css');
$GLOBALS['doc'] = $doc;
$g_item_count = 0;
$doc->addStyleSheet('//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
class HTML_vehiclemanager
{

    static function showRentRequest(& $vehicles, & $currentcat, & $params, & $tabclass, & $catid,
     & $sub_categories, $is_exist_sub_categories)
    {
        
        ///require_once( $GLOBALS['mosConfig_absolute_path'] . '/includes/pageNavigation.php' );
        ///$pageNav = new mosPageNav(0, 0, 0);
        $pageNav = new JPagination(0, 0, 0); // for J 1.6

        HTML_vehiclemanager::displayVehicles($vehicles, $currentcat, $params, $tabclass, $catid, $sub_categories, $is_exist_sub_categories, $pageNav);
        // add the formular for send to :-)
    }

    static function displayVehicles(&$rows, $currentcat, &$params, $tabclass, $catid, $categories,
     $is_exist_sub_categories, &$pageNav, $layout = "list")
    {   
        global $mosConfig_absolute_path;
        $type = 'alone_category';
        if(empty($layout))
            $layout = 'default';
        require getLayoutPath::getLayoutPathCom('com_vehiclemanager',$type, $layout);
        
    }

    static function displayAllVehicles(&$rows, &$params, $tabclass, &$pageNav, $layout = "list")
    {
        global $mosConfig_absolute_path;
        $type = 'all_vehicle';
        if(empty($layout))
            $layout = 'default';
        require getLayoutPath::getLayoutPathCom('com_vehiclemanager',$type, $layout);
        
    }

    /**
     * Displays the vehicle
     * rent Status
     */
    static function displayVehicle(& $vehicle, & $tabclass, & $params, & $currentcat,
       & $rating, & $vehicle_photos, &$videos, &$tracks, & $id, & $catid, & $vehicle_feature,
        & $currencys_price, & $layout = 'default')
    {
        if(!$catid) $catid = JRequest::getVar('catid');
        global $mosConfig_absolute_path;
        $type = 'view_vehicle';
        if(empty($layout))
            $layout = 'default';
        require getLayoutPath::getLayoutPathCom('com_vehiclemanager',$type, $layout);
    }

// END function displayVehicle

   
                /**
                 * Display links to categories
                 */
                static function showCategories(&$params, &$categories, &$catid, &$tabclass, &$currentcat, $layout)
                {
                    global $mosConfig_absolute_path;
                    $type = 'all_categories';
                    if(empty($layout))
                        $layout = 'default';
                    require getLayoutPath::getLayoutPathCom('com_vehiclemanager',$type, $layout);

                }


    static function listCategories(&$params, $cat_all, $catid, $tabclass, $currentcat)
    {
        global $Itemid, $mosConfig_live_site;
        ?>
        <?php positions_vm($params->get('allcategories04')); ?>
        <div class="basictable_12 basictable">
            <div class="row_01">
                <span class=" col_01 sectiontableheader<?php echo $params->get('pageclass_sfx'); ?>"><?php
                 echo _VEHICLE_MANAGER_LABEL_CATEGORY; ?></span>
                <span class="col_003 sectiontableheader<?php echo $params->get('pageclass_sfx'); ?>"><?php
                 echo _VEHICLE_MANAGER_LABEL_VEHICLES; ?> </span>

            </div>
            <div class="row_02">
                <span class="col_01">
        <?php positions_vm($params->get('allcategories05')); ?>

        <?php
        HTML_vehiclemanager::showInsertSubCategory($catid, $cat_all, $params, $tabclass, $Itemid, 0);
        ?>
                </span>
            </div>
        </div>
        <?php positions_vm($params->get('allcategories06')); ?>

                <?php
            }

            /*
             * function for show subcategory
             */

            static function showInsertSubCategory($id, $cat_all, $params, $tabclass, $Itemid, $deep)
            {
                global $g_item_count, $vehiclemanager_configuration, $mosConfig_live_site;
                $deep++;
                for ($i = 0; $i < count($cat_all); $i++) {
                    if (($id == $cat_all[$i]->parent_id) && ($cat_all[$i]->display == 1))
                    {
                        $g_item_count++;

                        $link = 'index.php?option=com_vehiclemanager&amp;task=alone_category&amp;catid=' .
                         $cat_all[$i]->id . '&amp;Itemid=' . $Itemid;
                        ?>
                <div class="basictable_13 basictable">
                    <div class="row_01">
                        <span class="col_01">
                <?php
                if ($deep != 1)
                {
                    $jj = $deep;
                    while ($jj--) {
                        echo "&nbsp;&nbsp;";
                    }
                    echo "&nbsp;|_";
                }
                ?>
                        </span>
                        <span class="col_01">
                <?php if (($params->get('show_cat_pic')) && ($cat_all[$i]->image != ""))
                { ?>
                                <img src="./images/stories/<?php echo $cat_all[$i]->image; ?>"
                                 alt="picture for subcategory" height="48" width="48" />&nbsp;
                    <?php } else
                {
                    ?>
                                <a <?php echo "href=" . sefRelToAbs($link); ?> class="category<?php
                                 echo $params->get('pageclass_sfx'); ?>" style="text-decoration: none"><img
                                  src="./components/com_vehiclemanager/images/folder.png"
                                   alt="picture for subcategory" height="48" width="48" /></a>&nbsp;
                <?php } ?>
                        </span>
                        <span class="col_02">
                <?php
                $count_veh = $cat_all[$i]->vehicles * 1;
                //if ($count_veh != 0)
                //{
                    $disable_link = "";
                    if ($cat_all[$i]->published != 1)
                        $disable_link = "href='#' onClick = 'return false'";
                    else
                        $disable_link = "href='" . sefRelToAbs($link) . "'";
                    ?>            
                                <a <?php echo $disable_link; ?> class="category<?php echo $params->get('pageclass_sfx'); ?>">
                    <?php
                //} else
                //{
                //    echo "";
                //}
                ?>                  
                <?php echo $cat_all[$i]->title; ?>
                            </a>
                        </span>
                                            <?php if ($params->get('rss_show')): ?>
                            <span class="col_04">
                                <a href="<?php echo $mosConfig_live_site; 
                                ?>/index.php?option=com_vehiclemanager&task=show_rss_categories&catid=<?php
                                 echo $cat_all[$i]->id; ?>&Itemid=<?php echo $Itemid; ?>">
                                    <img src="./components/com_vehiclemanager/images/rss2.png"
                                     alt="Category RSS" align="right" title="Category RSS"/>
                                </a>
                            </span>
                    <?php endif; ?>
                        <span class="col_03"><?php if ($cat_all[$i]->vehicles == '')
                         echo "0";else echo $cat_all[$i]->vehicles; ?></span>

                    </div>
                </div>
                    <?php
                    if ($GLOBALS['subcategory_show'])
                        HTML_vehiclemanager::showInsertSubCategory($cat_all[$i]->id, $cat_all,
                         $params, $tabclass, $Itemid, $deep);
                }//end if ($id == $cat_all[$i]->parent_id)
            }//end for(...) 
        }

            /*
             * function for show subcategory
             */

            static function showInsertSubCategoryBigImg($id, $cat_all, $params, $tabclass, $Itemid, $deep)
            {
                global $g_item_count, $vehiclemanager_configuration, $mosConfig_live_site;
                $mosConfig_absolute_path = $GLOBALS['mosConfig_absolute_path'] = JPATH_SITE;
                $deep++;
                for ($i = 0; $i < count($cat_all); $i++) {
                    if (($id == $cat_all[$i]->parent_id) && ($cat_all[$i]->display == 1))
                    {
                        $g_item_count++;

                        $link = 'index.php?option=com_vehiclemanager&amp;task=alone_category&amp;catid=' .
                         $cat_all[$i]->id . '&amp;Itemid=' . $Itemid;
                        ?>
                    <div class="row_img <?php echo $tabclass[($g_item_count % 2)]; ?>">
                        <div class="col_01">
                <?php if (($params->get('show_cat_pic')) && ($cat_all[$i]->image != ""))
                { ?>
                             <a href="<?php echo sefRelToAbs($link);?>" class="category<?php
                              echo $params->get('pageclass_sfx'); ?>" style="text-decoration: none; " >
                                <?php
                                if(!file_exists($mosConfig_absolute_path . '/components/com_vehiclemanager/photos/' .
                                 $cat_all[$i]->image ) )
                                    copy ( $mosConfig_absolute_path."/images/stories/" . $cat_all[$i]->image, 
                                        $mosConfig_absolute_path . '/components/com_vehiclemanager/photos/'  .
                                         $cat_all[$i]->image);
                                $file_name = vm_picture_thumbnail( $cat_all[$i]->image,
                                $vehiclemanager_configuration['fotogallery']['high'],
                                $vehiclemanager_configuration['fotogallery']['width']);
                                $file=$mosConfig_live_site . '/components/com_vehiclemanager/photos/'. $file_name;
                                echo '<img alt="picture for subcategory" title="'.$cat_all[$i]->title.'" src="' .$file. '">';
                                ?>
                             </a>&nbsp;

                    <?php } else
                {
                    ?>
                             <a href="<?php echo sefRelToAbs($link);?>" class="category<?php
                              echo $params->get('pageclass_sfx'); ?>" style="text-decoration: none; " >
                                <?php
                                if(!file_exists($mosConfig_absolute_path . '/components/com_vehiclemanager/photos/folder.png'  ) )
                                    copy ( $mosConfig_absolute_path."/components/com_vehiclemanager/images/folder.png" , 
                                        $mosConfig_absolute_path . '/components/com_vehiclemanager/photos/folder.png');
                                $file_name = vm_picture_thumbnail( 'folder.png',
                                $vehiclemanager_configuration['fotogallery']['high'],
                                $vehiclemanager_configuration['fotogallery']['width']);
                                $file=$mosConfig_live_site . '/components/com_vehiclemanager/photos/'.
                                 $file_name;
                                echo '<img alt="picture for subcategory" title="'.$cat_all[$i]->title.'" src="' .$file. '">';
                                ?>
                             </a>&nbsp;
                <?php } ?>
                        </div>
                      </div>
                    <?php
                }//end if ($id == $cat_all[$i]->parent_id)
            }//end for(...) 
        }

//end function showInsertSubCategory($id, $cat_all)

    static function showSearchVehicles($params, $currentcat, $clist, $option, &$arraymakersmodels, $layout) {
        global $mosConfig_absolute_path;
        // $layout = $params->get('showsearchvehiclelayout', 'default'); // need when not realize layout select from admin
        $type = 'show_search_vehicle';
        if(empty($layout))
            $layout = 'default';
        require getLayoutPath::getLayoutPathCom('com_vehiclemanager',$type, $layout);
        
    }


      static function showRentRequestThanks($params, $catid, $currentcat,$vehicle=NULL,$time_difference =NULL)
    {
        global $Itemid, $doc, $mosConfig_live_site, $hide_js, $option, $vehiclemanager_configuration;
        $doc->addStyleSheet($mosConfig_live_site . '/components/com_vehiclemanager/includes/vehiclemanager.css');
        ?>
        <div class="componentheading<?php echo $params->get('pageclass_sfx'); ?>">
            <?php echo $currentcat->header; ?>
        </div>

        <div class="table_save_add_vehicle basictable">
        <?php if ($currentcat->img != null)
        { ?>
           <div class="col_01"><img src="<?php echo $currentcat->img; ?>" alt="?" /></div>
            <?php
        }
        ?>
         <div class="col_02"><?php echo $currentcat->descrip; ?></div>
         
        <?php
        if($vehicle){
            if($time_difference){
                $amount = $time_difference[0]; // price
                $currency_code = $time_difference[1] ; // priceunit  
            }
            else{
                $amount= $vehicle->price;
                $currency_code = $vehicle->priceunit;
            }
        
        $amountLine='';
        $amountLine .= '<input type="hidden" name="amount" value="'.$amount.'" />'."\n"; 
        }

        ?>
		


        <?php

        
        if ($option != "com_vehiclemanager")
        {
            $path = $mosConfig_live_site . "/index.php?option=" . $option .
             "&amp;task=my_vehicles&amp;is_show_data=1&amp;Itemid=" . $_REQUEST['Itemid'] . "#tabs-2";
            $path_other = $mosConfig_live_site . "/index.php?option=" . $option .
             "&amp;task=view_user_vehicles&amp;is_show_data=1&amp;Itemid=" . $_REQUEST['Itemid'];
        } else
        {
            $path = $mosConfig_live_site .
             "/index.php?option=com_vehiclemanager&amp;task=my_vehicles&amp;Itemid=" . $_REQUEST['Itemid'];
            $path_other = $mosConfig_live_site .
             "/index.php?option=com_vehiclemanager&amp;task=showCategory&amp;catid=" . $catid .
              "&amp;Itemid=" . $_REQUEST['Itemid'];
        }
        ?>

      
            <div class="basictable_15 basictable">
                <span>
                            <input class="button" type="submit" ONCLICK="window.location.href='<?php
                            $user = JFactory::getUser(); 
                            if(!$user->guest) {
                                if ($catid == 0) {
                                    echo $path;
                                } else if (isset($_REQUEST['where']) && $_REQUEST['where'] == 2) {
                                    echo sefRelToAbs($path_other);
                                } else {
                                    echo sefRelToAbs($path);
                                } 
                            } else {
                                echo sefRelToAbs($mosConfig_live_site . "/index.php?option=" . $option .
                                 "&amp;Itemid=" . $_REQUEST['Itemid']);
                            }?>'" 
                            value="OK">

                    </span>
                </div>
            </div>

            <div class="basictable_16 basictable">
                    <?php mosHTML::BackButton($params, $hide_js); ?>
            </div>

        <?php

    }
    
//********************************************************************************************************   
//********************************************************************************************************    

    static function showTabs(&$params, &$userid, &$username, &$comprofiler, &$option){ 
      global $Itemid; ?>
      <div class='button_margin'>
        <?php
        if ($params->get('show_cb')){
          if ($params->get('show_cb_registrationlevel')){ ?>
            <span class='vehicle_button'>
                <a href="<?php echo JRoute::_('index.php?option=' . $option .
                                       '&task=my_vehicles&tab=getmyvehiclesTab&name=' . $username .
                                        '&Itemid=' . $Itemid . '&is_show_data=1'); ?>">
                                        <?php echo JText::_(_VEHICLE_MANAGER_LABEL_TITLE_MY_VEHICLES); ?>
                </a>
            </span>
            <?php
          }
        }
        if ($params->get('show_edit')){
          if ($params->get('show_edit_registrationlevel')){ ?>
            <span class='vehicle_button'>
              <a href="<?php echo JRoute::_('index.php?option=' . $option .
                                           '&task=edit_my_cars&Itemid=' . $Itemid . $comprofiler); ?>">
                                           <?php echo JText::_(_VEHICLE_MANAGER_LABEL_BUTTON_EDIT_VEHICLES); ?>
              </a>
            </span>
            <?php
          }
        }
        if ($params->get('show_rent')){
          if ($params->get('show_rent_registrationlevel')){ ?>
            <span class='vehicle_button'>
              <a href="<?php echo JRoute::_('index.php?option=' . $option . '&task=rent_requests_vehicle&Itemid=' .
               $Itemid . $comprofiler); ?>"><?php echo JText::_(_VEHICLE_MANAGER_LABEL_TITLE_RENT_REQUEST); ?></a>
            </span>
            <?php
          }
        }
        if ($params->get('show_buy')){
          if ($params->get('show_buy_registrationlevel')){ ?>
            <span class='vehicle_button'>
              <a href="<?php echo JRoute::_('index.php?option=' . $option .
                                   '&task=buying_requests_vehicle&Itemid=' . $Itemid . $comprofiler); ?>">
                                   <?php echo JText::_(_VEHICLE_MANAGER_LABEL_BUTTON_BUY_VEHICLE); ?>
              </a>
            </span>
            <?php
          }
        }
        if ($params->get('show_history')){
          if ($params->get('show_history_registrationlevel')){ ?>
            <span class='vehicle_button'>
                <a href="<?php echo JRoute::_('index.php?option=' . $option .
                                               '&task=rent_history_vehicle&name=' . $username .
                                                '&user=' . $userid . '&Itemid=' . $Itemid .
                                                 $comprofiler); ?>">
                                                 <?php echo JText::_(_VEHICLE_MANAGER_TOOLBAR_ADMIN_RENT_HISTORY); ?>
                </a>
            </span>
            <?php
          }
        }
        ?>
      </div>
      <?php
    }


    static function showRequestRentVehicles($option, &$rent_requests, $v_associated, $title_assoc, &$pageNav)
    {
        global $my, $mosConfig_live_site, $mainframe, $doc, $Itemid;
        $session = JFactory::getSession();
        $arr = $session->get("array", "default");
        $doc->addScript($mosConfig_live_site . '/components/com_vehiclemanager/includes/functions.js');
        $doc->addStyleSheet($mosConfig_live_site . '/components/com_vehiclemanager/includes/custom.css');
        ?>
        <form action="index.php" method="get" name="adminForm" id="adminForm">
            <div class="rent_requests_vehicle">
             <div class="row_01">
                <span class="col_01"><input type="checkbox" name="toggle" value="" onClick="vm_checkAll(this);" /></span>
                <span class="col_02">check All</span>
           </div>
        <?php
        for ($i = 0, $n = count($rent_requests); $i < $n; $i++) {
            $row = & $rent_requests[$i];
            
            $assoc_title = ''; 
              for ($t = 0, $z = count($title_assoc); $t < $z; $t++) {
                  if($title_assoc[$t]->vtitle != $row->vtitle) 
                     $assoc_title .= " ".$title_assoc[$t]->vtitle; 
              }
            ?>

	  <span class="user_name"><?php echo $row->user_name; ?></span>
		  <span class="arrow_up_comment"></span>
              <div class="rent_vehicle_head row_0<?php echo $i % 2; ?>">

	      <div class="row_vm_rent">

		  <div class="row_vtitle">
		   <?php //echo _VEHICLE_MANAGER_LABEL_TITLE; ?>
                   <?php echo $row->vtitle . " ( " . $assoc_title ." ) "; ?>
		  </div>

		  <div class="row_01">
		    <?php echo mosHTML::idBox($i, $row->id, 0, 'vid'); ?>
		    <span class="col_01">id</span>
                    <span class="col_02"><?php echo $row->id; ?></span>
		  </div>

		  <div class="row_02">
		    <span class="col_01"><?php echo _VEHICLE_MANAGER_LABEL_VEHICLEID; ?></span>
		    <span class="col_02">
			  <?php
			    $data = JFactory::getDBO();
			    $query = "SELECT vehicleid FROM #__vehiclemanager_vehicles where id = " . $row->fk_vehicleid . " ";
			    $data->setQuery($query);
			    $vehicleid = $data->loadObjectList();
		       echo $vehicleid[0]->vehicleid;
			  ?>
		    </span>
		  </div>

	     </div>

		<?php //echo _VEHICLE_MANAGER_LABEL_RENT_USER; ?>

		  <div class="row_comment">
		    <?php //echo _VEHICLE_MANAGER_LABEL_RENT_ADRES; ?>
		    <span class="quotes_before"></span>
			  <?php echo $row->user_mailing; ?>
		    <span class="quotes_after"></span>
		  </div>

		 <div class="mailto_from_until">
		  <div class="row_mailto">
		  <img src="<?php echo $mosConfig_live_site; ?>/components/com_vehiclemanager/images/mail_request.png" alt="email" />
		   <?php //echo _VEHICLE_MANAGER_LABEL_RENT_EMAIL; ?>
                   <a href=mailto:"<?php echo $row->user_email; ?>"><?php echo $row->user_email; ?></a>
		  </div>
		  <div class="row_from">
		    <span class="col_01"><?php echo _VEHICLE_MANAGER_LABEL_RENT_FROM; ?></span>
                    <span class="col_02"><?php echo $row->rent_from; ?></span>
		  </div>
		  <div class="row_until">
		    <span class="col_01"><?php echo _VEHICLE_MANAGER_LABEL_RENT_UNTIL; ?></span>
                    <span class="col_02"><?php echo $row->rent_until; ?></span>
		  </div>
		 </div>

               </div>
            <?php
        }
        ?>
      </div>

           <div class="page_navigation row_<?php echo $i % 2; ?>">
              <div class="row_02">
		<?php
		  $paginations = $arr;
	      if ($paginations && ($pageNav->total > $pageNav->limit))
		 {
		echo $pageNav->getPagesLinks();
		    }
		  ?>
             </div>
          </div>

            <input type="hidden" name="option" value="<?php echo $option; ?>" />
        <?php
        if ($option != "com_vehiclemanager")
        {
            ?>
                <input type="hidden" name="tab" value="getmyvehiclesTab" />
                <input type="hidden" name="is_show_data" value="1" />
            <?php
        }
        ?>
            <input type="hidden" id="adminFormTaskInput" name="task" value="" />
            <input type="hidden" name="boxchecked" value="0" />
            <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
            <input type="button" name="acceptButton" value="accept request" onclick="vm_buttonClickRentRequest(this)"/>
            <input type="button" name="declineButton" value="decline request" onclick="vm_buttonClickRentRequest(this)"/>
        </form>
        <?php
    }

    static function showRequestBuyingVehicles($option, $buy_requests, $pageNav)
    {
        global $my, $mosConfig_live_site, $mainframe, $doc, $Itemid;
        $session = JFactory::getSession();
        $arr = $session->get("array", "default");
        $doc->addScript($mosConfig_live_site . '/components/com_vehiclemanager/includes/functions.js');
        $doc->addStyleSheet($mosConfig_live_site . '/components/com_vehiclemanager/includes/custom.css');
        ?>
        <form action="index.php" method="get" name="adminForm" id="adminForm">

            <div class="buy_requests_form">
                <div class="row_01">
                    <span class="col_01"><input type="checkbox" name="toggle" value="" onClick="vm_checkAll(this);" /></span>
		    <span class="check_all_requests">check All</span>
               </div>
        <?php
        for ($i = 0, $n = count($buy_requests); $i < $n; $i++) {
            $row = $buy_requests[$i];
            ?>

        <span class="user_name"><?php echo $row->customer_name; ?></span>
	    <span class="arrow_up_comment"></span>
          <div class="box_request_vm row_0<?php echo $i % 2; ?>">

      <div class="row_vid">
	<div class="col_vtitle">
            <?php //echo _VEHICLE_MANAGER_LABEL_TITLE; ?>
            <?php echo $row->vtitle; ?>
	</div>
	<div class="row_01">
            <?php
            if ($row->fk_rentid != 0)
            {
            ?>
              &nbsp;
                <?php
            } else
            {
             ?>
		<?php echo mosHTML::idBox($i, $row->id, ($row->fk_rentid != 0), 'vid'); ?>
             <?php
            }
            ?>
	  <span class="col_02">id</span>
	  <span class="col_03"><?php echo $row->id; ?></span>
	</div>

	<div class="row_02">
	    <span class="col_01"><?php echo _VEHICLE_MANAGER_LABEL_VEHICLEID; ?></span>
            <span class="col_02"><?php echo $row->fk_vehicleid; ?></span>
	</div>
    </div>

		  <?php //echo _VEHICLE_MANAGER_LABEL_RENT_USER; ?>

		<div class="row_comment">
                  <?php //echo _VEHICLE_MANAGER_LABEL_COMMENT; ?>
                  <span class="quotes_before"></span>
		      <?php echo $row->customer_comment; ?>
                  <span class="quotes_after"></span>
		</div>

		    <div class="mailto_phone">
		<div class="row_mailto">
		  <img src="<?php echo $mosConfig_live_site; ?>/components/com_vehiclemanager/images/mail_request.png" alt="email" />
                  <?php //echo _VEHICLE_MANAGER_LABEL_RENT_EMAIL; ?>
		  <a href=mailto:"<?php echo $row->customer_email; ?>"><?php echo $row->customer_email; ?></a>
		</div>
		<div class="row_phone">
		  <img src="<?php echo $mosConfig_live_site; ?>/components/com_vehiclemanager/images/phone_request.png" alt="phone" />
                  <?php //echo _VEHICLE_MANAGER_LABEL_BUYING_ADRES; ?>
                  <span class="col_phone"><?php echo $row->customer_phone; ?></span>
		</div>
		    </div>

            </div>
          <?php
         }
        ?>

    </div>

        <div class="page_navigation">
           <div class="row_02">
	    <?php
		$paginations = $arr;
	    if ($paginations && ($pageNav->total > $pageNav->limit))
	    {
		echo $pageNav->getPagesLinks();
	    }
	    ?>
	  </div>
      </div>

            <input type="hidden" name="option" value="<?php echo $option; ?>" />
        <?php
        if ($option != "com_vehiclemanager")
        {
            ?>
                <input type="hidden" name="tab" value="getmyvehiclesTab" />
                <input type="hidden" name="is_show_data" value="1" />
            <?php
        }
        ?>
            <input type="hidden" id="adminFormTaskInput" name="task" value="" />
            <input type="hidden" name="boxchecked" value="0" />
            <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
            <input type="button" name="acceptButton" value="accept request" onclick="vm_buttonClickBuyRequest(this)"/>
            <input type="button" name="declineButton" value="decline request" onclick="vm_buttonClickBuyRequest(this)"/>
        </form>
        <?php
    }
}
//END CLASS VEHICLE MANAGER HTML
?>
