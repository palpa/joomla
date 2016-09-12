<?php 
// no direct access
defined('_JEXEC') or die('Restricted access');
?>

<div class="vehiclemanager_<?php if($moduleclass_sfx!='') echo $moduleclass_sfx; ?>">
  <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false" ></script>
  <script type="text/javascript">
    window.addEvent('domready', function() {
      var marker = new Array();
      var myOptions = {
        zoom:  1,
        scrollwheel: false,
        center: new google.maps.LatLng(<?php if ($rows[0]->vlatitude) echo $rows[0]->vlatitude; else echo 0; ?>,
        <?php if ($rows[0]->vlongitude) echo $rows[0]->vlongitude; else echo 0; ?>),
        <?php if ($params->get('menu_map') == 0) echo "mapTypeControl: false,"; else echo "mapTypeControl: true,";?>
        <?php if ($params->get('control_map') == 0) echo "zoomControl: false, panControl: false, streetViewControl: false,";
             else echo "zoomControl: true, panControl: true, streetViewControl: true,";?>
        mapTypeId: google.maps.MapTypeId.ROADMAP
      };
      var imgCatalogPath = "<?php 
              echo $mosConfig_live_site; ?>/components/com_vehiclemanager/";
      var map_mod_vehicle = new google.maps.Map(document.getElementById("map_canvas<?php echo $pr; ?>"), myOptions);
      var bounds = new google.maps.LatLngBounds ();
      <?php
      $newArr = explode(",", _VEHICLE_MANAGER_LOCATION_MARKER);
      $j=0;
      for ($i=0;$i < count($rows);$i++){
        if ($rows[$i]->vlatitude && $rows[$i]->vlongitude) {
          $numPick = '';
          if(isset($newArr[$rows[$i]->vtype])){
              $numPick = $newArr[$rows[$i]->vtype];
          } ?>
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
              map: map_mod_vehicle,
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
            infowindow.open(map_mod_vehicle,marker[<?php echo $j; ?>]);
          });
          var myLatlng = new google.maps.LatLng(<?php echo $rows[$i]->vlatitude; ?>,<?php
           echo $rows[$i]->vlongitude; ?>);
          var myZoom = <?php echo $rows[$i]->map_zoom; ?>;
          <?php
          $j++;
        }
      } ?>
      if (<?php echo $j; ?>>1) map_mod_vehicle.fitBounds(bounds);
      else if (<?php echo $j; ?>==1) {map_mod_vehicle.setCenter(myLatlng);map_mod_vehicle.setZoom(myZoom)}
      else {map_mod_vehicle.setCenter(new google.maps.LatLng(0,0));map_mod_vehicle.setZoom(0);}
    });
  </script>
  <div id="map_canvas<?php echo $pr; ?>" class="vm_map_canvas" style="width: <?php echo $params->get('map_width');?>px; 
                                                height: <?php echo $params->get('map_height'); ?>px; float: rigth;" >
  </div>
</div>
