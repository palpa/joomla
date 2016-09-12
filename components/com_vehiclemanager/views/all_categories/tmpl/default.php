<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );

/**
*
* @package  VehicleManager
* @copyright 2012 Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); Rob de Cleen(rob@decleen.com);
* Homepage: http://www.ordasoft.com
* @version: 3.0 Free
*
**/


    global $hide_js, $Itemid, $acl, $mosConfig_live_site, $my,$mainframe;
    $doc = JFactory::getDocument();  
    $doc->addStyleSheet( $mosConfig_live_site.'/components/com_vehiclemanager/includes/vehiclemanager.css' );
    ?>
<!-- main stylesheet ends, CC with new stylesheet below... -->

<!--[if IE]>
<style type="text/css">
  .basictable {
    zoom: 1;     /* triggers hasLayout */
    }  /* Only IE can see inside the conditional comment
    and read this CSS rule. Don't ever use a normal HTML
    comment inside the CC or it will close prematurely. */
</style>
<![endif]-->

<?php positions_vm($params->get('allcategories01'));?>
    <div class="componentheading<?php echo $params->get( 'pageclass_sfx' ); ?>">
      <?php //echo $currentcat->header; ?>
    </div>
<?php positions_vm($params->get('allcategories02'));?>
    <div class="category_title basictable">
        <div class="row_01">
            <div class="col_02">
                <img src="<?php echo $mosConfig_live_site; ?>/components/com_vehiclemanager/images/vm_logo.png" alt="Vehicles"/>
            </div>
            <div class="vm_col_search">
            <div class="vm_cat_title">
                <?php echo $currentcat->descrip; ?>
            </div>
          <?php if ($params->get('show_search')){?>
        <div class="basictable_44 basictable">
            <?php
                $link = 'index.php?option=com_vehiclemanager&amp;task=show_search_vehicle&amp;Itemid='. $Itemid;
                //$link = 'index.php?option=com_vehiclemanager&amp;task=show_search_vehicle&amp;catid='. $catid. '&amp;Itemid='. $Itemid;
            ?> 
       <div class="search_button_vehicle">
            <a href="<?php echo JRoute::_($link, false); ?>" class="category<?php echo $params->get( 'pageclass_sfx' ); ?>">
                <i class="fa fa-search"></i>
                <?php echo _VEHICLE_MANAGER_LABEL_SEARCH; ?>
            </a>
      </div>
        </div>
<?php } ?>    
            </div>
        </div>
    </div>
<?php positions_vm($params->get('allcategories03'));?>
    <br/>
    <?php 
      HTML_vehiclemanager::listCategories($params, $categories, $catid, $tabclass , $currentcat);
    ?>
  <div class="basictable_59">
    <?php 
    mosHTML::BackButton($params, $hide_js); ?>
  </div>

    <br/>
    
<?php
    if ($params->get( 'show_input_add_vehicle')) HTML_vehiclemanager::showAddButton();
positions_vm($params->get('allcategories07'));
positions_vm($params->get('allcategories08'));
    if ($params->get( 'ownerslist_show')) HTML_vehiclemanager::showOwnersButton();
positions_vm($params->get('allcategories09'));
    if ($params->get( 'show_button_my_cars')) HTML_vehiclemanager::showButtonMyCars();
positions_vm($params->get('allcategories10'));
?>