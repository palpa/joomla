<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<div class = "vehiclemanager_mod_search" >
<!--======================================================================================-->  
       <div class="vm_search_inline">
          <div class="VM_search_vehicles">
              <div><?php echo _VEHICLE_MANAGER_SEARCH_DESC1; ?></div>
              <input class="inputbox input-medium" type="text" name="searchtext" size="20" maxlength="20"/>
          </div>

                <?php if($showCategory_options==0){?>
                    <div class="VM_search_category">
                            <?php echo '<span>' . _VEHICLE_HEADER_CATEGORY. '</span>' .$clist ?>
                    </div>
                <?php } elseif($showCategory_options==1){ ?>
                    <input type="hidden" name="Category_options" value="on">
              <?php } ?> 
        </div>

        <?php if($params->get('rent') == 0){ ?>
            <script>
              jQuerVEH(function() {
                  jQuerVEH( "#vm_search_date_from_mod, #vm_search_date_until_mod" ).datepicker(
                  {
                  minDate: "+0",
                  dateFormat: "<?php echo filterDateVmSearchMod(); ?>",
                  });
              });
            </script>
          <div class="vm_search_inline">
            <div class="VM_search_rent">
            <div><?php echo _VEHICLE_MANAGER_LABEL_BUTTON_RENT; ?>:</div>
              <div class="box_from" >
                  <span class="col_01"><?php echo _VEHICLE_MANAGER_LABEL_AVAILABLE_FOR_RENT_FROM; ?></span>
                  <input type="text" id="vm_search_date_from_mod" name="search_date_from1">
              </div>
              <div class="box_from" >
                  <span class="col_01"><?php echo _VEHICLE_MANAGER_LABEL_AVAILABLE_FOR_RENT_UNTIL; ?></span>
                  <input type="text" id="vm_search_date_until_mod" name="search_date_until1">
              </div>
            </div>
        </div>
            <?php } ?>

        <?php if($params->get('year') == 0){ ?>
            <script type="text/javascript"> 
              jQuerVEH(function() {
                jQuerVEH("#vm_slider_mod_year").slider({
                  min: 1900,
                  max: <?php echo date('Y'); ?>,
                  values: [1900,<?php echo date('Y'); ?>],
                  range: true,
                  stop: function(event, ui) {
                    jQuerVEH(".vehiclemanager_mod_search input#yearfrom").val(jQuerVEH("#vm_slider_mod_year").slider("values",0));
                    jQuerVEH(".vehiclemanager_mod_search input#yearto").val(jQuerVEH("#vm_slider_mod_year").slider("values",1));
                  },
                  slide: function(event, ui){
                    jQuerVEH(".vehiclemanager_mod_search input#yearfrom").val(jQuerVEH("#vm_slider_mod_year").slider("values",0));
                    jQuerVEH(".vehiclemanager_mod_search input#yearto").val(jQuerVEH("#vm_slider_mod_year").slider("values",1));
                  }
                });

                jQuerVEH(".vehiclemanager_mod_search input#yearfrom").change(function(){
                  var value1=jQuerVEH(".vehiclemanager_mod_search input#yearfrom").val();
                  var value2=jQuerVEH(".vehiclemanager_mod_search input#yearto").val();
                  if(parseInt(value1) > parseInt(value2)){
                    value1 = value2;
                    jQuerVEH(".vehiclemanager_mod_search input#yearfrom").val(value1);
                  }
                  jQuerVEH("#vm_slider_mod_year").slider("values",0,value1);
                });
                      
                jQuerVEH(".vehiclemanager_mod_search input#yearto").change(function(){
                  var value1=jQuerVEH(".vehiclemanager_mod_search input#yearfrom").val();
                  var value2=jQuerVEH(".vehiclemanager_mod_search input#yearto").val();
                  if(parseInt(value1) > parseInt(value2)){
                    value2 = value1;
                    jQuerVEH(".vehiclemanager_mod_search input#yearto").val(value2);
                  }
                  jQuerVEH("#vm_slider_mod_year").slider("values",1,value2);
                });
              });
            </script>
        <div class="vm_search_inline">
            <div class="VM_search_year">
             <span class="price_label"><?php echo _VEHICLE_MANAGER_LABEL_YEAR; ?></span>
                <div id="vm_slider_mod_year" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"></div>
                <div class="yearfrom2">
                    <span><?php echo _VEHICLE_MANAGER_LABEL_FROM; ?></span>
                    <input type="text" id="yearfrom" name="yearfrom"  value="1900"/>
                </div>
                <div class="yearto2">
                    <span><?php echo _VEHICLE_MANAGER_LABEL_TO; ?></span>
                    <input type="text" id="yearto" name="yearto" value="<?php echo date('Y'); ?>"/>
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>
        <?php } if($params->get('price') == 0){ ?>
            <script type="text/javascript"> 
              jQuerVEH(function() {
                jQuerVEH("#vm_slider_mod_price").slider({
                  min: 0,
                  max: <?php echo $max_price; ?>,
                  values: [0,<?php echo $max_price; ?>],
                  range: true,
                  stop: function(event, ui) {
                    jQuerVEH(".vehiclemanager_mod_search input#pricefrom").val(jQuerVEH("#vm_slider_mod_price").slider("values",0));
                    jQuerVEH(".vehiclemanager_mod_search input#priceto").val(jQuerVEH("#vm_slider_mod_price").slider("values",1));
                  },
                  slide: function(event, ui){
                    jQuerVEH(".vehiclemanager_mod_search input#pricefrom").val(jQuerVEH("#vm_slider_mod_price").slider("values",0));
                    jQuerVEH(".vehiclemanager_mod_search input#priceto").val(jQuerVEH("#vm_slider_mod_price").slider("values",1));
                  }
                });

                jQuerVEH(".vehiclemanager_mod_search input#pricefrom").change(function(){
                  var value1=jQuerVEH(".vehiclemanager_mod_search input#pricefrom").val();
                  var value2=jQuerVEH(".vehiclemanager_mod_search input#priceto").val();
                  if(parseInt(value1) > parseInt(value2)){
                    value1 = value2;
                    jQuerVEH(".vehiclemanager_mod_search input#pricefrom").val(value1);
                  }
                  jQuerVEH("#vm_slider_mod_price").slider("values",0,value1);
                });
                    
                jQuerVEH(".vehiclemanager_mod_search input#priceto").change(function(){
                  var value1=jQuerVEH(".vehiclemanager_mod_search input#pricefrom").val();
                  var value2=jQuerVEH(".vehiclemanager_mod_search input#priceto").val();
                  if(parseInt(value1) > parseInt(value2)){
                    value2 = value1;
                    jQuerVEH(".vehiclemanager_mod_search input#priceto").val(value2);
                  }
                  jQuerVEH("#vm_slider_mod_price").slider("values",1,value2);
                });
              });
            </script>
        <div class="vm_search_inline">
            <div class="VM_search_price">
              <span class="vm_price_label"><?php echo _VEHICLE_MANAGER_LABEL_PRICE; ?></span>
              <div id="vm_slider_mod_price" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"></div>
              <div class="vm_pricefrom_2">
                <span><?php echo _VEHICLE_MANAGER_LABEL_FROM; ?></span>
                <input type="text" id="pricefrom" name="pricefrom"  value="0"/>
              </div>
              <div class="vm_priceto_2">
                <span><?php echo _VEHICLE_MANAGER_LABEL_TO; ?></span>
                <input type="text" id="priceto" name="priceto" value="<?php echo $max_price; ?>"/>
              </div>
              <div style="clear: both;"></div>
            </div>
        </div>
<?php } ?>


    <div class="VM_search_select">

        <?php  if($params->get('maker') == 0){ ?>
            <div  class="VM_search_maker">
                    <?php echo '<span>' . _VEHICLE_MANAGER_LABEL_MAKER .'</span>' . ':&nbsp;'. $maker;?>&nbsp;
            </div> 
        <?php } ?>
        <?php if($params->get('model') == 0){ ?>
         <script type="text/javascript">
            <?php
                $makers2 = $temp[0];
                $models2 = $temp[1];
                echo 'var modelscars2 = [];';
                for ($c = 0; $c < count($makers2); $c++):
                    $makers2[$c] = '\'' . $makers2[$c] . '\'';
                    foreach ($models2[$c] as $temp => $model_item) $models2[$c][$temp] = '\'' . $model_item . '\'';
                    echo 'var temp2=new Array(' . implode(',', $models2[$c]) . ",'other');\n";
                    echo 'modelscars2[' . $c . "]=temp2;\n";
                endfor;
                echo 'var makers2=new Array(' . implode(',', $makers2) . ');';
            ?>

            function changedMaker2(maker){
                var id = in_array2(maker.value,makers2);
                var model = document.getElementsByName('vm_model')[0]
                if(model.tagName.toLowerCase()=='input'){
                    var select =  document.createElement('select');
                    select.name='vm_model';
                    select.setAttribute('onchange','changedModel2(this)');
                    model.parentNode.appendChild(select);
                    model.parentNode.removeChild(model);
                }
                if(maker.value=='other'){
                    setTextfield2();
                    return;
                }
                clearSelectModel2();
                createOptionModel2('<?php echo _VEHICLE_MANAGER_LABEL_ALL ?>', '<?php echo _VEHICLE_MANAGER_LABEL_ALL ?>');
                for(var c=0;c<modelscars2[id].length;c++) createOptionModel2(modelscars2[id][c],modelscars2[id][c]);
            }

            function clearSelectModel2(){
                var objSelect=document.getElementsByName('vm_model')[0];
                while(objSelect.options.length > 0) objSelect.remove(0);
                return objSelect;
            }

            function in_array2(what, where){
                for(var i=0; i<where.length; i++) if(what == where[i]) return i;
                return false;
            }

            function setTextfield2(){
                var select=document.getElementsByName('vm_model')[0];
                var textfield = document.createElement('input');
                select.parentNode.appendChild(textfield);
                select.parentNode.removeChild(select);
                textfield.name='vm_model';
                textfield.size='35';
            }

            function createOptionModel2(newValue,newText){
                var objSelect = document.getElementsByName('vm_model')[0];
                var objOption = document.createElement("option");
                objOption.text = newText
                objOption.value = newValue
                if(document.all && !window.opera) objSelect.add(objOption); else objSelect.add(objOption, null);
            }

            function changedModel2(select){
                if(select.value=='other') setTextfield2();
            }
        </script>
<!-- ======================================================================================= -->
            <div class="VM_search_model">
                    <?php echo _VEHICLE_MANAGER_LABEL_MODEL . ':&nbsp;'. $model;?>&nbsp;
            </div>
<!-- ======================================================================================= -->

        <?php } if($params->get('vehicle_type_list') == 0){ ?>
            <div class="VM_search_vehicle">
                    <?php echo _VEHICLE_MANAGER_LABEL_VEHICLE_TYPE . ':&nbsp;'. $vehicle_type_list; ?>&nbsp;  
            </div>
        <?php } ?>

        <?php if($params->get('drive_type_list') == 0){ ?>
            <div class="VM_search_drive">
                    <?php echo _VEHICLE_MANAGER_LABEL_DRIVE_TYPE . ':&nbsp;'. $drive_type_list; ?>&nbsp;  
            </div>
        <?php } ?>
            
        <?php if($params->get('listing_type_list') == 0){ ?>
            <div class="VM_search_listing">
                    <?php echo _VEHICLE_MANAGER_LABEL_LISTING_TYPE . ':&nbsp;'. $listing_type_list; ?>&nbsp;  
            </div>
        <?php } ?>
        
        <?php if($params->get('condition_status_list') == 0){ ?>
            <div class="VM_search_condstatus">
                    <?php echo _VEHICLE_MANAGER_LABEL_CONDITION_STATUS . ':&nbsp;'. $condition_status_list; ?>&nbsp;  
            </div>
        <?php } ?>

        <?php if($params->get('transmission_type_list') == 0){ ?>
            <div class="VM_search_transmission">
                    <?php echo _VEHICLE_MANAGER_LABEL_TRANSMISSION_TYPE . ':&nbsp;'. $transmission_type_list; ?>&nbsp;  
            </div>
        <?php } ?>

        <?php if($params->get('fuel_type_list') == 0){ ?>
            <div class="VM_search_fuel">
                    <?php echo _VEHICLE_MANAGER_LABEL_FUEL_TYPE . ':&nbsp;'. $fuel_type_list; ?>&nbsp;  
            </div>
        <?php } ?>
            
        <?php if($params->get('cylinder_list') == 0){ ?>
            <div class="VM_search_cylinder"> 
                    <?php echo _VEHICLE_MANAGER_LABEL_NUMBER_CYLINDERS . ':&nbsp;'. $cylinder_list; ?>&nbsp;  
            </div>
        <?php } ?>
            
        <?php if($params->get('num_speed_list') == 0){ ?>
            <div class="VM_search_speed">
                    <?php echo _VEHICLE_MANAGER_LABEL_NUMBER_SPEEDS . ':&nbsp;'. $num_speed_list; ?>&nbsp;  
            </div>
        <?php } ?>
            
        <?php if($params->get('listing_status_list') == 0){ ?>
            <div class="VM_search_liststatus">
                    <?php echo _VEHICLE_MANAGER_LABEL_LISTING_STATUS . ':&nbsp;'. $listing_status_list; ?>&nbsp;  
            </div>
        <?php } ?> 

        <?php if($params->get('doors_list') == 0){ ?>
            <div class="VM_search_doors">
                    <?php echo _VEHICLE_MANAGER_LABEL_NUMBER_DOORS . ':&nbsp;'. $doors_list; ?>&nbsp;  
            </div>
        <?php } ?>
 
        <?php if($params->get('price_type_list') == 0){ ?>
            <div class="VM_search_pricetype">
                    <?php echo _VEHICLE_MANAGER_LABEL_PRICE_TYPE . ':&nbsp;'. $price_type_list; ?>&nbsp;  
            </div>
        <?php } ?>
            
        <?php for($i=6;$i<=10;$i++){ 
                if ($params->get('dropdownfield'.$i) == 0){
                ?><div class="VM_search_extra">
                        <?php echo _VEHICLE_MANAGER_LABEL_EXTRA.$i . ':&nbsp;'. $params->get('extrafield'.$i); ?>&nbsp;  
                </div>
        <?php 
                }
        
            } 
            ?> 
    </div>

<div class="VM_search_checkbox">            
      <?php if($VID==0){?>
            <div class="VM_search_VID">
                    <?php echo _VEHICLE_MANAGER_LABEL_VEHICLEID ?>:&nbsp;<input type="checkbox" name="VID" checked="checked">
            </div>
        <?php } elseif($showDescription==1){ ?>
            <input type="hidden" name="VID" value="on">
        <?php } ?>
            
       <?php if($showDescription==0){?>
            <div class="VM_search_comment">
                    <?php echo _VEHICLE_MANAGER_LABEL_COMMENT ?>:&nbsp;<input type="checkbox" name="Description" checked="checked">
            </div>
        <?php } elseif($showDescription==1){ ?>
            <input type="hidden" name="Description" value="on">
        <?php } ?>

        <?php if($showTitle==0){ ?>
            <div class="VM_search_title">
                    <?php echo _VEHICLE_MANAGER_LABEL_TITLE ?>:&nbsp;<input type="checkbox" name="Title" checked="checked">
            </div>
        <?php } elseif($showTitle==1){ ?>
            <input type="hidden" name="Title" value="on">
        <?php } ?>

        <?php if($showCountry==0){ ?>
            <div class="VM_search_country">
                    <?php echo _VEHICLE_MANAGER_LABEL_COUNTRY; ?>:&nbsp;<input type="checkbox" name="Country" checked="checked">
            </div>
        <?php } elseif($showCountry==1){?>
            <input type="hidden" name="Country" value="on">
        <?php } ?>

        <?php if($showRegion==0){ ?>
            <div class="VM_search_region">
                    <?php echo _VEHICLE_MANAGER_LABEL_REGION; ?>:&nbsp;<input type="checkbox" name="Region" checked="checked">
            </div>
        <?php } elseif($showRegion==1){ ?>
            <input type="hidden" name="Region" value="on">
        <?php }?>

        <?php if($showCity==0){?>
            <div class="VM_search_city">
                    <?php echo _VEHICLE_MANAGER_LABEL_CITY; ?>:&nbsp;<input type="checkbox" name="City" checked="checked">
            </div>
        <?php } elseif($showCity==1){ ?>
            <input type="hidden" name="City" value="on">
        <?php }?>

        <?php if($showDistrict==0){?>
            <div class="VM_search_district">
                    <?php echo _VEHICLE_MANAGER_LABEL_DISTRICT; ?>:&nbsp;<input type="checkbox" name="District" checked="checked">
            </div>
        <?php } elseif($showDistrict==1){ ?>
            <input type="hidden" name="District" value="on">
        <?php }?>

        <?php if($showAddress==0){ ?>
            <div class="VM_search_address">
                    <?php echo _VEHICLE_MANAGER_LABEL_ADDRESS ?>:&nbsp;<input type="checkbox" name="Address" checked="checked">
            </div>
        <?php } elseif($showAddress==1){ ?>
            <input type="hidden" name="Address" value="on">
        <?php }?>

        <?php if($showMileage==0){ ?>
            <div class="VM_search_mileage">
                    <?php echo _VEHICLE_MANAGER_LABEL_MILEAGE ?>:&nbsp;<input type="checkbox" name="Mileage" checked="checked">
            </div>
        <?php } elseif($showMileage==1){ ?>
            <input type="hidden" name="Mileage" value="on">
        <?php }?>

        <?php if($showEngine_type==0){?>
            <div class="VM_search_engine">
                    <?php echo _VEHICLE_MANAGER_LABEL_ENGINE_TYPE ?>:&nbsp;<input type="checkbox" name="Engine_type" checked="checked">
            </div>
        <?php } elseif($showEngine_type==1){ ?>
            <input type="hidden" name="Engine_type" value="on">
        <?php }?>

        <?php if($showWheeltype==0){?>
            <div class="VM_search_wheeltype">
                    <?php echo _VEHICLE_MANAGER_LABEL_WHEELTYPE ?>:&nbsp;<input type="checkbox" name="Wheeltype" checked="checked">
            </div>
        <?php } elseif($showWheeltype==1){ ?>
            <input type="hidden" name="Wheeltype" value="on">
        <?php }?>

        <?php if($showExterior_colors==0){ ?>
            <div class="VM_search_excolors">
                    <?php echo _VEHICLE_MANAGER_LABEL_EXTERIOR_COLORS ?>:&nbsp;<input type="checkbox" name="Exterior_colors" checked="checked">
            </div>
        <?php } elseif($showExterior_colors==1){ ?>
            <input type="hidden" name="Exterior_colors" value="on">
        <?php }?>

        <?php if($showExterior_extras==0){ ?>
            <div class="VM_search_extras">
                    <?php echo _VEHICLE_MANAGER_LABEL_EXTERIOR_EXTRAS ?>:&nbsp;<input type="checkbox" name="Exterior_extras" checked="checked">
            </div>
        <?php } elseif($showExterior_extras==1){ ?>
            <input type="hidden" name="Exterior_extras" value="on">
        <?php }?>

        <?php if($showInterior_colors==0){ ?>
            <div class="VM_search_intcolors">
                    <?php echo _VEHICLE_MANAGER_LABEL_INTERIOR_COLORS ?>:&nbsp;<input type="checkbox" name="Interior_colors" checked="checked">
            </div>
        <?php } elseif($showInterior_colors==1){ ?>
            <input type="hidden" name="Interior_colors" value="on">
        <?php }?>

        <?php if($showDashboard_options==0){ ?>
            <div class="VM_search_dashboard">
                    <?php echo _VEHICLE_MANAGER_LABEL_DASHBOARD_OPTIONS ?>:&nbsp;<input type="checkbox" name="Dashboard_options" checked="checked">
            </div>
        <?php } elseif($showDashboard_options==1){ ?>
            <input type="hidden" name="Dashboard_options" value="on">
        <?php }?>

        <?php if($showInterior_extras==0){ ?>
            <div class="VM_search_intextras">
                    <?php echo _VEHICLE_MANAGER_LABEL_INTERIOR_EXTRAS ?>:&nbsp;<input type="checkbox" name="Interior_extras" checked="checked">
            </div>
        <?php } elseif($showInterior_extras==1){ ?>
            <input type="hidden" name="Interior_extras" value="on">
        <?php }?>

        <?php if($showSafety_options==0){?>
            <div class="VM_search_safety">
                    <?php echo _VEHICLE_MANAGER_LABEL_SAFETY_OPTIONS ?>:&nbsp;<input type="checkbox" name="Safety_options" checked="checked">
            </div>
        <?php } elseif($showSafety_options==1){ ?>
            <input type="hidden" name="Safety_options" value="on">
        <?php }?>
		
        <?php if($showWarranty_options==0){?>
            <div class="VM_search_warranty">
                    <?php echo _VEHICLE_MANAGER_LABEL_WARRANTY_OPTIONS ?>:&nbsp;<input type="checkbox" name="Warranty_options" checked="checked">
            </div>
        <?php } elseif($showWarranty_options==1){ ?>
            <input type="hidden" name="Warranty_options" value="on">
        <?php }?>

        <?php if($showOwner==0){?>
            <div class="VM_search_ownername">
                    <?php echo _VEHICLE_MANAGER_LABEL_OWNER ?>:&nbsp;<input type="checkbox" name="Ownername" checked="checked">
            </div>
        <?php } elseif($showOwner==1){ ?>
            <input type="hidden" name="Ownername" value="on">
        <?php }?>
            
      <?php if($showzipcode==0){?>
            <div class="VM_search_zipcode">
                    <?php echo _VEHICLE_MANAGER_LABEL_ZIPCODE ?>:&nbsp;<input type="checkbox" name="Zipcode" checked="checked">
            </div>
        <?php } elseif($showzipcode==1){ ?>
            <input type="hidden" name="Zipcode" value="on">
        <?php } ?>            

      <?php if($ContactInformation==0){?>
            <div class="VM_search_contacts">
                    <?php echo _VEHICLE_MANAGER_LABEL_CONTACTS ?>:&nbsp;<input type="checkbox" name="ContactInformation" checked="checked">
            </div>
        <?php } elseif($ContactInformation==1){ ?>
            <input type="hidden" name="ContactInformation" value="on">
        <?php } ?> 

      <?php if($CityMPGKPL==0){?>
            <div class="VM_search_cityMPG">
                    <?php echo _VEHICLE_MANAGER_LABEL_CITY_FUEL_MPG ?>:&nbsp;<input type="checkbox" name="CityMPGKPL" checked="checked">
            </div>
        <?php } elseif($CityMPGKPL==1){ ?>
            <input type="hidden" name="CityMPGKPL" value="on">
        <?php } ?> 
            
      <?php if($HighwayMPGKPL==0){?>
            <div class="VM_search_highway">
                    <?php echo _VEHICLE_MANAGER_LABEL_HIGHWAY_FUEL_MPG ?>:&nbsp;<input type="checkbox" name="HighwayMPGKPL" checked="checked">
            </div>
        <?php } elseif($HighwayMPGKPL==1){ ?>
            <input type="hidden" name="HighwayMPGKPL" value="on">
        <?php } ?> 

      <?php if($Wheelbase==0){?>
            <div class="VM_search_wheelbase">
                    <?php echo _VEHICLE_MANAGER_LABEL_WHEELBASE ?>:&nbsp;<input type="checkbox" name="Wheelbase" checked="checked">
            </div>
        <?php } elseif($Rear_axel_type==1){ ?>
            <input type="hidden" name="Wheelbase" value="on">
        <?php } ?> 
            
      <?php if($Rear_axel_type==0){?>
            <div class="VM_search_rearaxel">
                    <?php echo _VEHICLE_MANAGER_LABEL_REARAXE_TYPE ?>:&nbsp;<input type="checkbox" name="Rear_axel_type" checked="checked">
            </div>
        <?php } elseif($Rear_axel_type==1){ ?>
            <input type="hidden" name="Rear_axel_type" value="on">
        <?php } ?> 

      <?php if($Brakes_type==0){?>
            <div class="VM_search_brakes">
                    <?php echo _VEHICLE_MANAGER_LABEL_BRAKES_TYPE ?>:&nbsp;<input type="checkbox" name="Brakes_type" checked="checked">
            </div>
        <?php } elseif($Brakes_type==1){ ?>
            <input type="hidden" name="Brakes_type" value="on">
        <?php } ?> 
       
        <?php for($i=1;$i<=5;$i++){ 
                if ($params->get('extra'.$i) == 0){
                ?><div class="VM_search_labextra">
                        <?php echo _VEHICLE_MANAGER_LABEL_EXTRA.'_'.$i ?>:&nbsp;<input type="checkbox" name="extra<?php echo $i;?>" checked="checked">
                </div>
        <?php 
                }
        
            } 
            ?>      
</div>
</div>
<div class="VM_search_batton">
    <input type="submit" value="<?php echo _VEHICLE_MANAGER_LABEL_SEARCH_BUTTON; ?>" class="button"  ></input>
    <input type="hidden" name="option" value="com_vehiclemanager" />
    <input type="hidden" name="task" value="search_vehicle" />
    <input type="hidden" name="Itemid" value="<?php echo $ItemId ?>" />
</div>