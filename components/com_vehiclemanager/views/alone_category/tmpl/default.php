<?php 
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
 * @package  VehicleManager
 * @copyright 2012 Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); Rob de Cleen(rob@decleen.com)
 * Homepage: http://www.ordasoft.com
 * @version: 3.5 Free
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * */
$session = JFactory::getSession();
$arr = $session->get("array", "default");

global $hide_js, $Itemid, $mosConfig_live_site, $mosConfig_absolute_path,$database;
global $limit, $total, $limitstart, $task, $paginations, $mainframe, $vehiclemanager_configuration, $option;
$user=Jfactory::getUser();
if(isset($_REQUEST['userId'])){
    if($option != 'com_vehiclemanager' and $_REQUEST['userId'] == $user->id) PHP_vehiclemanager::showTabs();                    
}
else{
    if($option != 'com_vehiclemanager') PHP_vehiclemanager::showTabs();
}

$doc = JFactory::getDocument();  
$doc->addStyleSheet( $mosConfig_live_site.'/components/com_vehiclemanager/includes/vehiclemanager.css' );
$doc->addStyleSheet( $mosConfig_live_site.'/components/com_vehiclemanager/includes/custom.css' );
$doc->addScript("//maps.googleapis.com/maps/api/js?sensor=false");
if (version_compare(JVERSION, "1.6.0", "lt")) JHTML::_('behavior.mootools');
 //PHP_vehiclemanager::showTabs();
        ?>
<!--[if IE]>
<style type="text/css">
  .basictable {
    zoom: 1;     /* triggers hasLayout */
    }  /* Only IE can see inside the conditional comment
    and read this CSS rule. Don't ever use a normal HTML
    comment inside the CC or it will close prematurely. */
</style>
<![endif]-->

    <script type="text/javascript">
        function vm_rent_request_submitbutton() {
            var form = document.userForm;
            if (form.user_name.value == "") {
                alert( "<?php echo _VEHICLE_MANAGER_INFOTEXT_JS_RENT_REQ_NAME; ?>" );
            } else if (form.user_email.value == "" || !vm_isValidEmail(form.user_email.value)) {
                alert( "<?php echo _VEHICLE_MANAGER_INFOTEXT_JS_RENT_REQ_EMAIL; ?>" );
            } else if (form.user_mailing == "") {
                alert( "<?php echo _VEHICLE_MANAGER_INFOTEXT_JS_RENT_REQ_MAILING; ?>" );
            } else if (form.rent_until.value == "") {
                alert( "<?php echo _VEHICLE_MANAGER_INFOTEXT_JS_RENT_REQ_UNTIL; ?>" );
            } else {
                form.submit();
            }
        }

        function vm_isValidEmail(str) {
            return (str.indexOf("@") > 1);
        }

        function vm_allreordering(){
          if(document.orderForm_v.order_direction.value=='asc')
            document.orderForm_v.order_direction.value='desc';
          else document.orderForm_v.order_direction.value='asc';

          document.orderForm_v.submit();
        }

    </script>

<?php positions_vm($params->get('singleuser01'));?>
<?php positions_vm($params->get('singlecategory01')); ?>
    <!--<div class="componentheading<?php// echo $params->get('pageclass_sfx'); ?>">
    <?php /*if (!$params->get('wrongitemid')){
      echo $currentcat->header;
    }
    else{
     $parametrs=$mainframe->getParams();
     echo $parametrs->toObject()->page_title;
    }*/?>

    </div>-->

  <div class="pre_button">
      <?php
        if ($currentcat->img != null && $currentcat->align == 'left' && $params->get('show_cat_pic')) {
      ?>
      <span class="col_01">
        <img src="<?php echo $currentcat->img; ?>" align="<?php echo $currentcat->align; ?>" alt="?"/>
      </span>
      <?php
        }
        if(!$params->get('wrongitemid')){
      ?>

      <span class="col_02">
        <?php //echo $currentcat->descrip; ?>
      </span>

      <?php if ($currentcat->img != null && $currentcat->align == 'right') { ?>
      <span class="col_03">
        <img src="<?php echo $currentcat->img; ?>" align="<?php echo $currentcat->align; ?>" alt="?" />
      </span>
      <?php }
      } ?>         
  </div>






<?php 
positions_vm($params->get('singleuser02'));
positions_vm($params->get('singlecategory02'));

  
if (($task!='rent_request_vehicle')&&($vehiclemanager_configuration['location_map']==1)){ ?>
                <div id="vm_map_canvas" class="vm_map_canvas vm_map_canvas_03"></div>
<script type="text/javascript">
        window.onload =  function() {
            vm_initialize2();
        };
        function vm_initialize2(){
            var map;
            var marker = new Array();
            var myOptions = {
                scrollwheel: false,
                zoomControlOptions: {
                    style: google.maps.ZoomControlStyle.LARGE
                },
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            var imgCatalogPath = "<?php 
              echo $mosConfig_live_site; ?>/components/com_vehiclemanager/";
            var map = new google.maps.Map(document.getElementById("vm_map_canvas"), myOptions);
            var bounds = new google.maps.LatLngBounds ();
    <?php
     $newArr = explode(",", _VEHICLE_MANAGER_LOCATION_MARKER);
     $j=0;
     for ($i=0;$i < count($rows);$i++){
        if ($rows[$i]->vlatitude && $rows[$i]->vlongitude) {
                $numPick = '';
                if(isset($newArr[$rows[$i]->vtype])){
                    $numPick = $newArr[$rows[$i]->vtype];
                }
     ?>
      var srcForPic = "<?php echo $numPick; ?>";
      var image = '';
        if(srcForPic.length){
            var image = new google.maps.MarkerImage(imgCatalogPath + srcForPic,
                new google.maps.Size(32, 39),
                new google.maps.Point(0,0),
                new google.maps.Point(16, 39));
        }
            marker.push(new google.maps.Marker({
                icon: image,
                position: new google.maps.LatLng(<?php echo $rows[$i]->vlatitude; ?>,
                 <?php echo $rows[$i]->vlongitude; ?>),
                map: map,
                title: "<?php echo $database->Quote($rows[$i]->vtitle); ?>"
            }));
            bounds.extend(new google.maps.LatLng(<?php echo $rows[$i]->vlatitude; ?>,<?php
             echo $rows[$i]->vlongitude; ?>));
            var infowindow  = new google.maps.InfoWindow({});
            google.maps.event.addListener(marker[<?php echo $j; ?>], 'mouseover', function() {
            <?php
            if (strlen($rows[$i]->vtitle) > 45)
                $vtitle = substr($rows[$i]->vtitle, 0, 25) . '...';
            else {
                $vtitle = $rows[$i]->vtitle;
            }
            ?>     
            var title =  "<?php echo $vtitle ?>";
            <?php 
              //for local images
              $imageURL = ($rows[$i]->image_link);
              if ($imageURL == '') $imageURL = _VEHICLE_MANAGER_NO_PICTURE_BIG;
                  $file_name = vm_picture_thumbnail($imageURL,150,150);
                  $file = $mosConfig_live_site . '/components/com_vehiclemanager/photos/' . $file_name;
            ?>
            var imgUrl =  "<?php echo $file; ?>";
            var price =  "<?php echo $rows[$i]->price; ?>";
            var priceunit =  "<?php echo $rows[$i]->priceunit; ?>";
            var foto_width = 150;
            var foto_height = 150;
            var contentStr = '<div>'+
                                '<div>'+
                                    '<img width = "'+foto_width+'" height="'+foto_height+'" src = '+imgUrl+
                                        ' onclick=window.open("index.php?option=com_vehiclemanager'+
                                          '&task=view_vehicle&id=<?php echo $rows[$i]->id; ?>'+
                                          '&catid=<?php echo $rows[$i]->idcat ?>&Itemid=<?php echo $Itemid;?>")>'+
                                '</div>'+
                                '<div id="marker_link">'+
                                    '<a onclick=window.open("index.php?option=com_vehiclemanager'+
                                      '&task=view_vehicle&id=<?php echo $rows[$i]->id; ?>'+
                                      '&catid=<?php echo $rows[$i]->idcat ?>&Itemid=<?php echo $Itemid;?>")>' +
                                       title + '</a>'+
                                '</div>'+
                                '<div id="marker_price">'+
                                    '<a onclick=window.open("index.php?option=com_vehiclemanager'+
                                    '&task=view_vehicle&id=<?php echo $rows[$i]->id; ?>'+
                                    '&catid=<?php echo $rows[$i]->idcat ?>&Itemid=<?php echo $Itemid;?>") >' +
                                     price +' ' + priceunit + '</a>'+
                                '</div>'+
                            '</div>';
               infowindow.setContent(contentStr);
               infowindow.open(map,marker[<?php echo $j; ?>]);
            });
            var myLatlng = new google.maps.LatLng(<?php echo $rows[$i]->vlatitude; ?>,<?php
             echo $rows[$i]->vlongitude; ?>);
            var myZoom = <?php echo $rows[$i]->map_zoom; ?>;
            <?php
            $j++;
        }
    }
?>
            if (<?php echo $j; ?>>1) map.fitBounds(bounds);
            else if (<?php echo $j; ?>==1) {map.setCenter(myLatlng);map.setZoom(myZoom)}
            else {map.setCenter(new google.maps.LatLng(0,0));map.setZoom(0);}
        }
        </script>
        <?php
}
?>
 
<?php
 global $option;
if (count($rows) > 0) {  
  if(JRequest::getVar('option') == "com_vehiclemanager" || !$params->get('wrongitemid') || $params->get('show_search')){?>
    <div class="all_vehicle_search">
    <?php positions_vm($params->get('singlecategory03')); ?>
    <?php 
    if (!$params->get('wrongitemid')){ ?>
      <div class="vm_addHouse">
      <?php
      if ($params->get('show_input_add_vehicle')) HTML_vehiclemanager::showAddButton();
          positions_vm($params->get('singlecategory10')); ?>
      </div>
      <?php
    }
    if ($params->get('show_search')){?>
      <div class="componentheading<?php echo $params->get('pageclass_sfx'); ?>"></div>
      <div class="search_button_vehicle basictable_47 basictable">
        <?php
        $link = 'index.php?option=' . $option . '&amp;task=show_search_vehicle&amp;catid=' .
         $catid . '&amp;Itemid=' . $Itemid;
        ?> 
        <a href="<?php echo JRoute::_($link, false); ?>" 
          class="category<?php echo $params->get('pageclass_sfx'); ?>" align="right">
          <i class="fa fa-search"></i>
        <?php echo _VEHICLE_MANAGER_LABEL_SEARCH; ?>&nbsp;
          </a>
      </div>
    <?php 
    }
    $sort_arr['order_field'] = $params->get('sort_arr_order_field');
    $sort_arr['order_direction'] = $params->get('sort_arr_order_direction');
    positions_vm($params->get('singleuser03'));
    positions_vm($params->get('singlecategory04'));

    if(JRequest::getVar('option') == "com_vehiclemanager") {?>
          <div id="ShowOrderBy">
            <form method="POST" action="<?php echo sefRelToAbs($_SERVER["REQUEST_URI"]);?>" name="orderForm_v">
              <input type="hidden" id="order_direction" name="order_direction" value="<?php echo $sort_arr['order_direction']; ?>" >
              <a title="Click to sort by this column." onclick="javascript:vm_allreordering();return false;" href="#">
              <img alt="" src="./media/system/images/sort_<?php echo $sort_arr['order_direction']; ?>.png"></a>
              <?php echo _VEHICLE_MANAGER_LABEL_ORDER_BY; ?> <select size="1" class="inputbox"
               onchange="javascript:document.orderForm_v.order_direction.value='asc';
                document.orderForm_v.submit();" id="order_field" name="order_field">
              <option value="date" <?php if($sort_arr['order_field'] == "date")
               echo 'selected="selected"'; ?> >  <?php echo _VEHICLE_MANAGER_LABEL_DATE; ?> </option>
              <option value="price" <?php if($sort_arr['order_field'] == "price") 
                echo 'selected="selected"'; ?> > <?php echo _VEHICLE_MANAGER_LABEL_PRICE; ?></option>
              <option value="maker" <?php if($sort_arr['order_field'] == "maker")
               echo 'selected="selected"'; ?> >  <?php echo _VEHICLE_MANAGER_LABEL_MODEL; ?></option>
              <option value="vtitle" <?php if($sort_arr['order_field'] == "vtitle")
               echo 'selected="selected"'; ?> > <?php echo _VEHICLE_MANAGER_LABEL_TITLE; ?></option></select>       
            </form>
          </div>
      <?php 
    } ?>
  </div>
  <?php
}
 positions_vm($params->get('singleuser04'));?>
<?php positions_vm($params->get('singlecategory05')); ?>

  <div id="gallery">
    <?php $total = count($rows);
    foreach ($rows as $row) {
            
      if ($option != "com_vehiclemanager") {
        $link = 'index.php?option=' . $option .
         '&task=view_vehicle&tab=getmyvehiclesTab&is_show_data=1&id=' .
          $row->id . '&catid=' . $row->catid . '&Itemid=' . $Itemid . '#tabs-2';
      } else {
        $link= 'index.php?option=' . $option .
         '&amp;task=view_vehicle&amp;id=' . $row->id . '&amp;catid=' .
          $row->catid . '&amp;Itemid=' . $Itemid;
      }
      $imageURL = $row->image_link;
      ?>
      <div class="okno_V">
         <div class="okno_img" style = "position:relative;">
         <a href="<?php echo sefRelToAbs($link);?>" style="text-decoration: none" >
         <?php
            $file_name = vm_picture_thumbnail($imageURL,
              $vehiclemanager_configuration['fotogallery']['high'],
              $vehiclemanager_configuration['fotogallery']['width']);
            $file=$mosConfig_live_site . '/components/com_vehiclemanager/photos/'. $file_name;
            echo '<img alt="'.$row->vtitle.'" title="'.$row->vtitle.'" src="' .$file.
             '">';
?>
         </a>
        </div>

        <div class="textvehicle">

          <h4 class="titlevehicle">
             <a href="<?php echo sefRelToAbs($link); ?>" >
                <?php if(strlen($row->vtitle)>45) echo substr($row->vtitle,0,25),'...';
                else {
                    echo $row->vtitle;
                }?>
             </a>
          </h4>
           <?php if ($row->maker != '' || $row->vmodel !== 0 && trim($row->vmodel) !== "") {
                          ?>
                            <div class="vm_text_model">
                             <i class="fa fa-car"></i>
                            <?php if ($row->maker != '' && $row->maker != 'other' ) {
                          ?>
                                                          <span class="vm_maker"><?php echo $row->maker; ?></span>
                      <?php } if ($row->vmodel !== 0 && trim($row->vmodel) !== "") { ?>
                                                          <span class="vm_model"><?php echo $row->vmodel; ?></span>
                                                      <?php
                                                  } ?>
                          </div>
                              <?php
                            } 
                            if ($row->year != 0) {
                            ?> 
                            <div class="vm_text">
                                <i class="fa fa-calendar"></i>
                                <span><?php echo _VEHICLE_MANAGER_LABEL_ISSUE_YEAR; ?>:</span>
                                <span><?php echo $row->year; ?></span>
                            </div>
                          <?php } 
                           ?>
                                <div class="vm_text" >
                                    <i class="fa fa-tachometer"></i>
                                    <span><?php echo _VEHICLE_MANAGER_LABEL_MILEAGE; ?>:</span>
                                    <span><?php echo (trim($row->mileage))?(trim($row->mileage)):("0"); ?></span>
                                </div>
        </div>
        <div class="vm_viewlist">
                   <a href="<?php echo sefRelToAbs($link); ?>" style="display: block"> 
                            <?php
                                if ($params->get('show_pricerequest') && $row->price != '' && $row->priceunit != '') {
                          ?>   
                                  <div class="price">
                          <?php
                                  if ($vehiclemanager_configuration['price_unit_show'] == '1'){
                                    if ($vehiclemanager_configuration['sale_separator'])
                                       echo formatMoney($row->price, true,
                                        $vehiclemanager_configuration['price_format']), ' ', $row->priceunit;
                                    else echo $row->price, ' ', $row->priceunit;
                                  }else {
                                    if ($vehiclemanager_configuration['sale_separator'])
                                       echo $row->priceunit, ' ', formatMoney($row->price,
                                        true, $vehiclemanager_configuration['price_format']);
                                    else echo $row->priceunit, ' ', $row->price;
                                  }
                          ?>
                                </div>
                        <?php } ?>

                        <span><?php echo _VEHICLE_MANAGER_LABEL_VIEW_LISTING; ?></span></a>
                        <div style="clear: both;"></div>
                </div>
      </div>
  <?php
      }
  ?>    
  </div>

<form action="<?php echo sefRelToAbs("index.php");?>" name="userForm" method="post">
  <?php 
  if ($params->get('show_rentstatus') && $params->get('show_rentrequest') 
    && $params->get('rent_save')) {// && $available)
  ?>

    <div class="componentheading<?php echo $params->get('pageclass_sfx'); ?>">
    <?php echo _VEHICLE_MANAGER_LABEL_RENT_INFORMATIONS; ?>
        <input type="hidden" name="vehicleid" id="vehicleid" value="<?php echo $row->id ?>" maxlength="80" />
    </div>

    <div class="basictable_49 basictable">
      <div class="row_02">
        <span class="col_01">
          <?php echo _VEHICLE_MANAGER_LABEL_RENT_REQUEST_NAME; ?>:<br />
          <input class="inputbox" type="text" name="user_name" size="38" maxlength="80" />
        </span>
        <span class="col_02">
          <?php echo _VEHICLE_MANAGER_LABEL_RENT_REQUEST_EMAIL; ?>:<br />
          <input class="inputbox" type="text" name="user_email" size="30" maxlength="80" />
        </span>
      </div>
    </div>
    <div class="basictable_50 basictable">
      <div class="row_01">
        <span class="col_01">
          <?php echo _VEHICLE_MANAGER_LABEL_RENT_REQUEST_MAILING; ?>:<br />
          <?php //editorArea('editor1', '', 'user_mailing', '400', '200', '30', '5'); ?>
                                  <textarea name ="user_mailing"></textarea>
                          </span>
        <span class="col_02">
          <br />
          <p>
              <?php echo _VEHICLE_MANAGER_LABEL_RENT_REQUEST_FROM; ?>:<br />
              <?php echo JHtml::_('calendar',date("Y-m-d"), 'rent_from','rent_from','%Y-%m-%d' );  ?>    
          </p>
          <p>
              <?php echo _VEHICLE_MANAGER_LABEL_RENT_REQUEST_UNTIL; ?>:<br />
              <?php echo JHtml::_('calendar',date("Y-m-d"), 'rent_until','rent_until','%Y-%m-%d' ); ?>    
          </p>
        </span>
      </div>
    </div>
           

    <br/>
    <div class="basictable_51 page_navigation">
      <div class="row_03">
        <span clas="col_01">
          <?php
            if ($params->get('show_rentstatus') && $params->get('show_rentrequest')
             && !$params->get('rent_save')) {
          ?>
              <br />
          <!-- <input type="submit" name="submit" value="<?php echo _VEHICLE_MANAGER_LABEL_BUTTON_RENT_REQU; ?>" class="button" />
              <br />-->
          <?php
            } else if ($params->get('show_rentstatus') && $params->get('show_rentrequest')
               && $params->get('rent_save')) {// && $available)
          ?>
                <input type="button" class="button" 
                  value="<?php echo _VEHICLE_MANAGER_LABEL_BUTTON_RENT_REQU_SAVE; ?>"
                     onclick="vm_rent_request_submitbutton()" />
      <?php } else { ?>
          &nbsp;
          <?php
            }
          ?>
        </span>
      </div>

    </div>
    
    <input type="hidden" name="option" value="<?php echo $option;?>"/>
    <input type="hidden" name="task" value="save_rent_request_vehicle"/>

  <?php
    if($option != 'com_vehiclemanager'){
  ?>
        <input type="hidden" name="tab" value="getmyvehiclestab"/>
        <input type="hidden" name="is_show_data" value="1"/>
  <?php
    }
  ?>
    <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
    <input type="hidden" name="vehicleid" value="<?php echo $rows[0]->id;?>"/>
<?php 
  }
?>

</form>

    <div class="basictable_51 page_navigation">
      <div class="row_02">
        <?php
        $paginations = $arr;
          if ($paginations && ( $pageNav->total > $pageNav->limit )) {
            echo $pageNav->getPagesLinks( ); 
          }
        ?>
      </div>      
      
    </div>
    
<?php
}//if row > 0

   if ($is_exist_sub_categories) {
?>
    <?php positions_vm($params->get('singlecategory07')); ?>
    <div class="componentheading<?php echo $params->get('pageclass_sfx'); ?>">
    <?php echo _VEHICLE_MANAGER_LABEL_FETCHED_SUBCATEGORIES . " : " . $params->get('category_name'); ?></div>
    <?php positions_vm($params->get('singlecategory08')); ?>
<?php
     HTML_vehiclemanager::listCategories($params, $categories, $catid, $tabclass, $currentcat);
  }
?>
  <div class="basictable_59">
    <?php 
    mosHTML::BackButton($params, $hide_js); ?>
  </div>

<?php positions_vm($params->get('singlecategory09')); ?>

<?php 
positions_vm($params->get('singlecategory11'));
?>

<style type="text/css">
#list img.little{
    /*height: <?php echo $params->get('minifotohigh');?>px;
    width:<?php echo $params->get('minifotowidth');?>px;*/
}
.okno_V {
    width: <?php echo $vehiclemanager_configuration['fotogallery']['width'];?>px;
}
.okno_V .okno_img {
    width: <?php echo $vehiclemanager_configuration['fotogallery']['width'];?>px;
    height: <?php echo $vehiclemanager_configuration['fotogallery']['high'];?>px;
}
.okno_V img {
    max-height: <?php echo $vehiclemanager_configuration['fotogallery']['high'];?>px;
    width: <?php echo $vehiclemanager_configuration['fotogallery']['width'];?>px;
}
/*.okno_V .textvehicle {
    width:<?php echo $vehiclemanager_configuration['fotogallery']['width'];?>px;
}*/
</style>
