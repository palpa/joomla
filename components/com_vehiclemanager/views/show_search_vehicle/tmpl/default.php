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
global $hide_js, $Itemid, $mainframe, $mosConfig_live_site, $doc, $vehiclemanager_configuration;
$doc->addStyleSheet($mosConfig_live_site . '/components/com_vehiclemanager/includes/vehiclemanager.css');
positions_vm($params->get('showsearch01')); ?>
<script type="text/javascript" src="<?php echo $mosConfig_live_site ?>/components/com_vehiclemanager/lightbox/js/jQuerVEH-1.9.0.js"></script>
<script type="text/javascript" src="<?php echo $mosConfig_live_site ?>/components/com_vehiclemanager/includes/jquery-ui.js"></script>
<div class="span6 componentheading<?php echo $params->get('pageclass_sfx'); ?>">
  <h3><?php echo $currentcat->header; ?></h3>
</div>
<?php positions_vm($params->get('showsearch02')); ?>
<div class="basictable_39 basictable">
  <div class="row_01">
    <?php
    if ($currentcat->img != null && $currentcat->align == 'left') {?>
      <span class="col_01">
        <img src="<?php echo $currentcat->img; ?>" alt="img" align="<?php echo $currentcat->align; ?>" />
      </span>
      <?php
    }?>
    <span class="col_02"></span>
    <?php
    if ($currentcat->img != null && $currentcat->align == 'right') {?>
      <span class="col_03">
        <img src="<?php echo $currentcat->img; ?>" align="<?php echo $currentcat->align; ?>"  alt = "?"/>
      </span>
      <?php
    }
    ?>
  </div>
</div>
<br />

<script type="text/javascript">
  function vm_showDate(){
      if(document.userForm1.search_date_box.checked ){
        var x=document.getElementById("search_date_from");
        document.userForm1.search_date_from.type="text";
        var x=document.getElementById("search_date_until");
        document.userForm1.search_date_until.type="text";
      }else{
        var x=document.getElementById("search_date_from");
        document.userForm1.search_date_from.type="hidden";
        var x=document.getElementById("search_date_until");
        document.userForm1.search_date_until.type="hidden";
      }
  }
</script>

<?php positions_vm($params->get('showsearch03'));
$path = "index.php?option=" . $option . "&amp;task=search_vehicle&amp;Itemid=" . $Itemid; ?>

<form action="<?php echo sefRelToAbs($path);?>" method="get" name="userForm1" id="userForm1">
  <input type="hidden" name="Vehicleid" value="on"/>
  <input type="hidden" name="Address" value="on"/>
  <input type="hidden" name="City" value="on"/>
  <input type="hidden" name="Ownername" value="on"/>
  <input type="hidden" name="Engine_type" value="on"/>
  <input type="hidden" name="Wheelbase" value="on"/>
  <input type="hidden" name="Brakes_type" value="on"/>
  <input type="hidden" name="Interior_colors" value="on"/>
  <input type="hidden" name="Safety_options" value="on"/>
  <input type="hidden" name="Description" value="on"/>
  <input type="hidden" name="Country" value="on"/>
  <input type="hidden" name="District" value="on"/>
  <input type="hidden" name="Mileage" value="on"/>
  <input type="hidden" name="City_fuel_mpg" value="on"/>
  <input type="hidden" name="Wheeltype" value="on"/>
  <input type="hidden" name="Exterior_colors" value="on"/>
  <input type="hidden" name="Dashboard_options" value="on"/>
  <input type="hidden" name="Warranty_options" value="on"/>
  <input type="hidden" name="Title" value="on"/>
  <input type="hidden" name="Region" value="on"/>
  <input type="hidden" name="Zipcode" value="on"/>
  <input type="hidden" name="Contacts" value="on"/>
  <input type="hidden" name="Highway_fuel_mpg" value="on"/>
  <input type="hidden" name="Rear_axe_type" value="on"/>
  <input type="hidden" name="Exterior_extras" value="on"/>
  <input type="hidden" name="Interior_extras" value="on"/>

  <input type="hidden" name="option" value="<?php echo $option; ?>" />
  <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
  <input type="hidden" name="task" value="search_vehicle" />
  <div class="search_filter">
    <div class="row_01">
      <div class="fix_width_3">
        <span class="col_01"><?php echo _VEHICLE_MANAGER_LABEL_SEARCH_KEYWORD; ?></span>
        <input class="inputbox" type="text" name="searchtext" size="20" maxlength="20"/>
        <br />
        <input type="submit" name="submit" 
                value="<?php echo _VEHICLE_MANAGER_LABEL_SEARCH_BUTTON; ?>" class="button search_f" />
      </div>
            <div class="fix_width_3">
        <span class="col_04"><?php echo _VEHICLE_MANAGER_LABEL_AVAILABLE_FOR_RENT; ?></span>
        <div class="col_05_06">
          <span class="col_05"><?php echo _VEHICLE_MANAGER_LABEL_AVAILABLE_FOR_RENT_FROM; ?></span>
          <input type="text" id="search_date_from" name="search_date_from">
        </div>
        <div class="col_07_08">
          <span class="col_07"><?php echo _VEHICLE_MANAGER_LABEL_AVAILABLE_FOR_RENT_UNTIL; ?></span>
          <input type="text" id="search_date_until" name="search_date_until">
        </div>
      </div>
            <script type="text/javascript"> 
        jQuerVEH(document).ready(function() {
          jQuerVEH( "#search_date_from, #search_date_until" ).datepicker({
            minDate: "+0",
            dateFormat: "<?php echo transforDateFromPhpToJquery();?>"
          });
        });
        jQuerVEH(function() {
          jQuerVEH("#slider").slider({ 
            min: <?php echo $params->get('pricefrom_one'); ?>,
            max: <?php echo $params->get('priceto_one'); ?>,
            values: [<?php echo $params->get('pricefrom_one'); ?>,<?php echo $params->get('priceto_one'); ?>],
            range: true,
            stop: function(event, ui) {
              jQuerVEH("input#pricefrom").val(jQuerVEH("#slider").slider("values",0));
              jQuerVEH("input#priceto").val(jQuerVEH("#slider").slider("values",1));
            },
            slide: function(event, ui){
              jQuerVEH("input#pricefrom").val(jQuerVEH("#slider").slider("values",0));
              jQuerVEH("input#priceto").val(jQuerVEH("#slider").slider("values",1));
            }
          });

          jQuerVEH("input#pricefrom").change(function(){
            var value1=jQuerVEH("input#pricefrom").val();
            var value2=jQuerVEH("input#priceto").val();
            if(parseInt(value1) > parseInt(value2)){
              value1 = value2;
              jQuerVEH("input#pricefrom").val(value1);
            }
            jQuerVEH("#slider").slider("values",0,value1);
          });
              
          jQuerVEH("input#priceto").change(function(){
            var value1=jQuerVEH("input#pricefrom").val();
            var value2=jQuerVEH("input#priceto").val();
            if (value2 > <?php echo $params->get('priceto_one'); ?>) { value2 = <?php echo $params->get('priceto_one'); ?>; jQuerVEH("input#priceto").val(<?php echo $params->get('priceto_one'); ?>)}
            if(parseInt(value1) > parseInt(value2)){
              value2 = value1;
              jQuerVEH("input#priceto").val(value2);
            }
            jQuerVEH("#slider").slider("values",1,value2);
          });
        });
      </script>
      <div class="fix_width_3">
        <span class="col_01"><?php echo _VEHICLE_MANAGER_LABEL_PRICE;?></span>
        <div class="inp_label_from">
          <span class="col_02"><?php echo _VEHICLE_MANAGER_LABEL_PRICE_FROM;?></span>
          <input type="text" name="pricefrom" id="pricefrom" value="<?php echo $params->get('pricefrom_one'); ?>"/>
        </div>
        <div class="inp_label_from">
          <span class="col_03"><?php echo _VEHICLE_MANAGER_LABEL_PRICE_TO;?></span>
          <input type="text" name="priceto" id="priceto" value="<?php echo $params->get('priceto_one'); ?>"/>
        </div>
        <div class="slider_price">
          <div id="slider" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"></div>
        </div>
      </div>
      <div style="display:inline-block;">
          <div class="fix_width_4">
            <span class="col_01"><?php echo _VEHICLE_MANAGER_LABEL_CATEGORY; ?></span>
            <?php echo $clist; ?>
          </div>

          <div class="fix_width_4">
            <span class="col_01"><?php echo _VEHICLE_MANAGER_LABEL_LISTING_STATUS; ?></span>
            <?php echo $params->get('listing_status_list'); ?>
          </div>

          <div class="fix_width_4">
            <span class="col_01"><?php echo _VEHICLE_MANAGER_LABEL_LISTING_TYPE; ?></span>
            <?php echo $params->get('listing_type_list'); ?>
          </div>
        </div>
    </div>
  </div> <!--  search_filter  -->
  <br />
  <div class="basictable_59">
    <?php 
    mosHTML::BackButton($params, $hide_js); ?>
  </div>
</form>
<?php positions_vm($params->get('showsearch04'));
