<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

?>

 <div class="vehiclemanager_<?php if ($class_suffix != '') echo $class_suffix; ?>" >
    <ul class="similaire">
<?php 

foreach ($list as $item){

?>
        <li style="<?php echo $listestyle;?>">
            <div class="miniature" style="<?php echo $miniature?>">
            <?php
              $imageURL = $item->image_link; // name with type
            switch ($image_source_type) {
                case "0": $img_height = $vehiclemanager_configuration['fotomain']['high'];
                    $img_width = $vehiclemanager_configuration['fotomain']['width'];
                    break;
                case "1": $img_height = $vehiclemanager_configuration['foto']['high'];
                    $img_width = $vehiclemanager_configuration['foto']['width'];
                    break;
                case "2": $img_height = $vehiclemanager_configuration['fotogal']['high'];
                    $img_width = $vehiclemanager_configuration['fotogal']['width'];
                    break;
                default:$img_height = $vehiclemanager_configuration['fotoupload']['high'];
                    $img_width = $vehiclemanager_configuration['fotoupload']['width'];
                    break;
              }
            $imageURL1 = vm_picture_thumbnail($imageURL, $img_height, $img_width);
            $imageURL = "./components/com_vehiclemanager/photos/" . $imageURL1;
        
  ?>

                    <?php
                    $idvehi = $item->id;
                    $database = JFactory::getDBO();
                    $query = "SELECT v.priceunit FROM `#__vehiclemanager_vehicles` AS v WHERE id=(" . $idvehi . ")";
                    $database->setQuery($query);
                    $priceunit = $database->loadResult();
                    ?>
                    <a href="index.php?option=com_vehiclemanager&task=view_vehicle&id=<?php echo $item->id; ?>&catid=<?php echo $item->cid; ?>&Itemid=<?php echo $ItemId_tmp; ?>" title="<?php echo $item->vtitle; ?>">
                        <img  src="<?php echo $imageURL ?>" alt="<?php echo $item->link; ?>" />
                    </a>
                </div>
                <div>
                <a href="index.php?option=com_vehiclemanager&task=view_vehicle&id=<?php echo $item->id; ?>&catid=<?php echo $item->cid; ?>&Itemid=<?php echo $ItemId_tmp; ?>" title="<?php echo $item->vtitle; ?>">
                    <h4 style="<?php echo $h4;?>"><?php echo $item->vtitle; ?></h4>
                </a>

              <?php if ($item->price != ''){ 
              if ($vehiclemanager_configuration['price_unit_show'] == '1')
              if ($vehiclemanager_configuration['sale_separator']){?>
                <div class="prix" style="<?php echo $prix; ?>"><?php   echo formatMoney($item->price, false, $vehiclemanager_configuration['price_format']), ' ', $priceunit;?></div>
        <?php }
              else {?>
                      <div class="prix" style="<?php echo $prix; ?>"><?php    echo $item->price, ' ', $priceunit;?></div>
             <?php }
                   else {
                    if ($vehiclemanager_configuration['sale_separator']){ ?>
                      <div class="prix" style="<?php echo $prix; ?>"><?php  echo $priceunit, ' ', formatMoney($item->price, false, $vehiclemanager_configuration['price_format']);?></div>
             <?php  }
                    else {?>
                      <div class="prix" style="<?php echo $prix; ?>"><?php echo $priceunit, ' ', $item->price;?></div>
          <?php     }
                  }
          } ?>
                    <div class="vm_type_catlist">
                        <span class="featured_list_inline"><i class="fa fa-car"></i> <?php echo $item->maker. ' ' .$item->vmodel; ?></span>
                    <?php
                    if ($item->year != '') {
                        echo '<span class="featured_list_inline"><i class="fa fa-calendar"></i> ' . _VEHICLE_MANAGER_LABEL_ISSUE_YEAR .
                         ': ' . $item->year . '</span>';
                    }
                    
                    if ($item->fuel_type != '') {
                        $fuel_type[0] = _VEHICLE_MANAGER_OPTION_SELECT;
                        $fuel_type1 = explode(',', _VEHICLE_MANAGER_OPTION_FUEL_TYPE);
                        $g = 1;
                        foreach ($fuel_type1 as $fuel_type2) {
                            $fuel_type[$g] = $fuel_type2;
                            $g++;
                        }
                        echo '<span class="featured_list_inline"><i class="fa fa-fire"></i> ' . _VEHICLE_MANAGER_LABEL_FUEL_TYPE . ': ' . $fuel_type[$item->fuel_type] . '</span>';
                    }

                    if ($item->mileage != '') {
                       echo '<span class="featured_list_inline"><i class="fa fa-tachometer"></i> '  . _VEHICLE_MANAGER_LABEL_MILEAGE . ': ' . $item->mileage . '</span>';
                    }
                    ?>
                    </div>
                </div>
            </li>
<?php 
};
 ?>
        
    </ul>
    
    
</div>