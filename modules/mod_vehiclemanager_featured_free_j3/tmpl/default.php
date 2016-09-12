<?php 
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<div class="vehiclemanager<?php if (isset($class_suffix)) echo $class_suffix; ?>">
  <div class="moduletable<?php echo "-".$class_suffix; ?> basictable"> 
    <?php
    $rank_count = 0;
    $span = 0;
    if ($status!=0){
      $span++;
    }
    if ($show_hits!=0){
      $span++;
    }

    if ($displaytype==1){ // Display Horizontal?>
      <div class="featured_vehicles">
      <?php
    }
        foreach ($vehicles as $row){
          if($row->vlocation){
            if($row->country)
              $row->vlocation .= ','.$row->country;
            if($row->city)
              $row->vlocation .= ','.$row->city;
          }else{
            if($row->country)
              $row->vlocation .= $row->country;
            if($row->city)
              $row->vlocation .= ','.$row->city;
          }
          $comment = strip_tags($row->description);
          $prevwords = count(explode(" ",$comment));
          if(trim($g_words == "" )) $words = $prevwords;
          else $words = intval($g_words);
          $text = implode(" ", array_slice(explode(" ",$comment), 0, $words));
          if (count(explode(" ",$text))<$prevwords){
              $text .= "";
          }
          $query = "UPDATE #__vehiclemanager_vehicles SET featured_shows = featured_shows-1 ".
            " WHERE featured_shows > 0 and id = ".$row->id . " ;";
          $database->setQuery($query);
          $database->query();
          $img = "";
          $rank_count = $rank_count + 1; //start ranking
          $img_path = '';
          $link1 ="index.php?option=com_vehiclemanager&task=view_vehicle&id=".$row->id."&catid=".$row->catid."&Itemid=".$Itemid;
                  $imageURL = $row->image_link;
          if ($imageURL!=''){
            $imageURL = searchPicture_vehiclemanager ($image_source_type,$imageURL);
            $imageURL = JURI::root().$imageURL;
          } else 
              $imageURL = JURI::root()."components/com_vehiclemanager/images/no-img_eng_big.gif";
          $img='<a href="'.$link1.'" target="_self"> <img src="'.$imageURL.'" alt="'.$row->vtitle.'" 
                   border="0" style="height: '.$image_height.'px; width: '.$image_width.'px" /></a>';

          if ($displaytype == 1){
            // Display Horizontal ?>
            <div class="featured_vehicles_block" style="width:<?php echo $image_width.'px'?>; vertical-align:top;">
              <div style="position:relative">
                <?php
                if ($show_image == 1){
                    echo $img;

                    if ($status == 1) {
                        if ($row->listing_type){ 
                          if ($row->listing_type != 1){
                              echo '<div class="vm_col_sale">'._VEHICLE_MANAGER_OPTION_FOR_SALE.'</div>';
                          } else{
                              echo '<div class="vm_col_rent">'._VEHICLE_MANAGER_OPTION_FOR_RENT.'</div>';
                          }
                        } 
                    }
                }  ?>

              </div>
              <div class="feature_textvehicle">
                <h4 class="featured_block_title">
                  <?php
                  if ($row->published!=1){
                      $msg = " [ <tt style='color:red;font-size:10px;'>unpublished</tt> ] ";
                      echo "<a target='' href='' style='cursor:default;' onClick='return false;'>".$row->vtitle."</a>";
                  } else{
                      $msg = ''; 
                      echo "<a href='".JRoute::_($link1, false)."' target='_self'>".$row->vtitle."</a>";
                  } ?>
                </h4>
                <?php
                if ($location == 1 && !empty($row->vlocation)){
                    echo "<div class='featured_vehicles_location'><i ".
                      "class='fa fa-map-marker'></i> {$row->vlocation}&nbsp;</div>";
                }
                if ($categories == 1){
                  $link2 = 'index.php?option=com_vehiclemanager&amp;task=showCategory&amp;catid='
                     . $row->catid[0] . '&amp;Itemid=' . $_REQUEST['Itemid'] .
                      '&amp;view=alone_category&amp;module=' . $module->module; ?>

                  <div class="featured_vehicles_category featured_vehicles_inline">
                    <?php 
                    $cattitles=Array();
                    $query = "SELECT c.id AS catid
                            \n FROM #__vehiclemanager_main_categories AS c
                            \n LEFT JOIN #__vehiclemanager_categories AS hc ON hc.idcat=c.id
                            \n LEFT JOIN #__vehiclemanager_vehicles AS v ON v.id=hc.iditem 
                            \n WHERE v.id =" . $row->id;
                    $database->setQuery($query);
                    $cattitles= $database->loadColumn();
                    $row->cattitle = array();
                    foreach($cattitles as $key => $value) {
                      $query = "SELECT title FROM #__vehiclemanager_main_categories WHERE id =" . $value;
                      $database->setQuery($query);
                      $row->cattitle[$key] = $database->loadResult();
                      $link2 = 'index.php?option=com_vehiclemanager&amp;task=showCategory&amp;catid='
                       . $cattitles[$key] . '&amp;Itemid=' . $_REQUEST['Itemid'] .
                        '&amp;view=alone_category&amp;module=' . $module->module; ?>

                      <i class='fa fa-tag'></i>
                      <a href="<?php echo sefRelToAbs($link2); ?>" 
                                      class="category<?php echo $params->get('pageclass_sfx'); ?>">
                        <?php echo $row->cattitle[$key];?>
                      </a>
                      <?php
                    } ?>
                  </div>
                  <?php   
                }
                if ($features == 1){
                  if (trim($row->vmodel)) {
                    echo "<div class='featured_vehicles_rooms featured_vehicles_inline'>"
                      ."<i class='fa fa-car'></i> " . "{$row->maker}&nbsp;{$row->vmodel}</div>";
                  }
                  if (trim($row->year)) {
                    echo "<div class='featured_vehicles_year featured_vehicles_inline'>".
                      "<i class='fa fa-calendar'></i> " . _VEHICLE_MANAGER_LABEL_ISSUE_YEAR .
                       ': ' ."{$row->year}&nbsp;</div>";
                  }
                    (trim($row->mileage))?(trim($row->mileage)):("0");
                    echo "<div class='featured_vehicles_mileage featured_vehicles_inline'>".
                      "<i class='fa fa-tachometer'></i> " . _VEHICLE_MANAGER_LABEL_MILEAGE .
                       ': ' ."{$row->mileage}&nbsp;</div>";
                } 
                  if ($show_hits == 1){
                    echo "<div class='featured_vehicles_hits featured_vehicles_inline'>".
                    "<i class='fa fa-eye'></i> " . _VEHICLE_MANAGER_LABEL_HITS .
                     ': ' ."{$row->hits}</div>";
                  }
                  ?>
              </div>
              <?php 
              if ($price == 1 || $view_listing == 1) { ?>
                <div class="vm_viewlist">
                  <a href='<?php echo JRoute::_($link1, false); ?>' target='_self' style='display: block'>
                    <?php               
                    if ($price == 1){
                      echo "<div class='featured_vehicles_price '>" ;
                      if ($vehiclemanager_configuration['price_unit_show'] == '1') {
                        if ($vehiclemanager_configuration['sale_separator']) {
                          echo formatMoney($row->price, true, $vehiclemanager_configuration['price_format'])
                           . "&nbsp;" . $row->priceunit ;
                        } else {
                          echo  $row->price . "&nbsp;" . $row->priceunit;    
                        }
                      } else {
                        if ($vehiclemanager_configuration['sale_separator']) {
                          echo $row->priceunit . "&nbsp;" .
                                formatMoney($row->price, true, $vehiclemanager_configuration['price_format']);
                        } else {
                          echo $row->priceunit . "&nbsp;" . $row->price ;
                        }
                      }                  
                      echo "</div>" ;
                    }                        
                    if ($view_listing == 1){
                      echo "<div class='featured_vehicles_viewlisting'>"
                      . _VEHICLE_MANAGER_LABEL_VIEW_LISTING . "</div>";
                    } ?>
                  </a>
                  <div style="clear: both;"></div>
                </div>
                <?php 
              } ?>
            </div>
            <?php
          }else{
            //Display Vertical
            ?>
            <div class="featured_vehicles_line">
              <div style="position:relative; display:inline-block; float:left; margin-right:15px;">
                <?php
                if ($show_image == 1){ 
                  echo $img;

                    if ($status == 1) {
                        if ($row->listing_type){ 
                          if ($row->listing_type != 1){
                              echo '<div class="vm_col_sale">'._VEHICLE_MANAGER_OPTION_FOR_SALE.'</div>';
                          } else{
                              echo '<div class="vm_col_rent">'._VEHICLE_MANAGER_OPTION_FOR_RENT.'</div>';
                          }
                        } 
                    }
                }
                  ?>

              </div>
              <h4 class="featured_list_title">
                <?php
                if ($row->published!=1){
                  $msg = " [ <tt style='color:red;font-size:10px;'>unpublished</tt> ] ";
                  echo "<a target='' href='' style='cursor:default;' ".
                    " onClick='return false;'>".$row->vtitle."</a>";
                } else{
                  $msg = ''; 
                  echo "<a href='".JRoute::_($link1, false)."' target='_self'>".$row->vtitle."</a>";
                } ?>
              </h4>
              <?php
              if ($price == 1){
                if($row->price){
                    echo "<div class='featured_list_price'>" ;
                    if ($vehiclemanager_configuration['price_unit_show'] == '1') {
                      if ($vehiclemanager_configuration['sale_separator']) {
                        echo formatMoney($row->price, true, $vehiclemanager_configuration['price_format'])
                         . "&nbsp;" . $row->priceunit ;
                      } else {
                        echo  $row->price . "&nbsp;" . $row->priceunit;    
                      }
                    } else {
                      if ($vehiclemanager_configuration['sale_separator']) {
                        echo $row->priceunit . "&nbsp;" .
                         formatMoney($row->price, true, $vehiclemanager_configuration['price_format']);
                      } else {
                        echo $row->priceunit . "&nbsp;" . $row->price ;
                      }
                    }                  
                    echo "</div>" ;
                }
              }?>

<br>
              <?php
              if ($location == 1 && !empty ($row->vlocation)){
                echo "<div class='featured_list_location'>".
                  "<i class='fa fa-map-marker'></i> {$row->vlocation}&nbsp;</div>";
              }
              if ($description == 1 && !empty($text)){
                echo "<div class='featured_list_description'>{$text}...</div>";
              }

              if ($features == 1 || $categories == 1 || $show_hits == 1 ){ ?>
                <div class="vm_type_catlist">
                  <?php
                  if ($categories == 1){
                    $link2 = 'index.php?option=com_vehiclemanager&amp;task=showCategory&amp;catid='
                     . $row->catid[0] . '&amp;Itemid=' . $_REQUEST['Itemid'] .
                      '&amp;view=alone_category&amp;module=' . $module->module; ?>

                    <div class="featured_list_category featured_list_inline">
                      <?php 
                      $cattitles=Array();
                      $query = "SELECT c.id AS catid
                              \n FROM #__vehiclemanager_main_categories AS c
                              \n LEFT JOIN #__vehiclemanager_categories AS hc ON hc.idcat=c.id
                              \n LEFT JOIN #__vehiclemanager_vehicles AS v ON v.id=hc.iditem 
                              \n WHERE v.id =" . $row->id;
                      $database->setQuery($query);
                      $cattitles= $database->loadColumn();
                      $row->cattitle = array();
                      foreach($cattitles as $key => $value) {
                        $query = "SELECT title FROM #__vehiclemanager_main_categories WHERE id =" . $value;
                        $database->setQuery($query);
                        $row->cattitle[$key] = $database->loadResult();
                        $link2 = 'index.php?option=com_vehiclemanager&amp;task=showCategory&amp;catid='
                         . $cattitles[$key] . '&amp;Itemid=' . $_REQUEST['Itemid'] .
                          '&amp;view=alone_category&amp;module=' . $module->module; ?>

                        <i class='fa fa-tag'></i>
                        <a href="<?php echo sefRelToAbs($link2); ?>" 
                              class="category<?php echo $params->get('pageclass_sfx'); ?>">
                          <?php echo $row->cattitle[$key]; ?>
                        </a>
                        <?php
                      }
                      ?>
                    </div>
                    <?php 
                  }
                  if ($features == 1){
                    if (trim($row->maker) && ($row->vmodel)) {
                      echo "<div class='featured_vehicles_model featured_list_inline'>"
                        ."<i class='fa fa-car'></i> " . "{$row->maker}&nbsp;{$row->vmodel}</div>";
                    }
                    if (trim($row->year)) {
                      echo "<div class='featured_vehicles_year featured_list_inline'>".
                        "<i class='fa fa-calendar'></i> " . _VEHICLE_MANAGER_LABEL_ISSUE_YEAR .
                         ': ' ."{$row->year}&nbsp;</div>";
                    }
                    (trim($row->mileage))?(trim($row->mileage)):("0");
                      echo "<div class='featured_vehicles_mileage featured_list_inline'>".
                        "<i class='fa fa-tachometer'></i> " . _VEHICLE_MANAGER_LABEL_MILEAGE .
                         ': ' ."{$row->mileage}&nbsp;</div>";
                  }
                  if ($show_hits == 1){
                          echo "<div class='featured_vehicles_hits featured_list_inline'>".
                          "<i class='fa fa-eye'></i> " . _VEHICLE_MANAGER_LABEL_HITS .
                           ': ' ."{$row->hits}</div>";
                  }
                  ?>
                </div>
                <?php 
              }
              if ($view_listing == 1){
                echo "<div class='featured_list_viewlisting'><a href='".
                  JRoute::_($link1, false)."' target='_self'>"
                  . _VEHICLE_MANAGER_LABEL_VIEW_LISTING . "</a></div>";
              } ?>
              <div style="clear: both;"></div>                 
            </div>
            <?php
          }
        }
    if ($displaytype==1){ // Display Horizontal
      ?>
      </div>
      <?php
    } ?>
  </div>
</div>
