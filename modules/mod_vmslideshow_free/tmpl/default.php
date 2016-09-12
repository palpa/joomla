<?php
/*
 * @version 3.0 FREE
 * @package VehicleManager - vehicle slideShow
 * @copyright 2013 OrdaSoft
 * @author 2013 Andrey Kvasnekskiy (akbet@ordasoft.com )
 * @description VehicleManager - vehicle slideShow for Vehicle Manager Component
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
global $vehiclemanager_configuration;
?>

<div id="vmslideshow-module<?php echo $moduleclass_sfx; ?>" class="vmslideshow-loader<?php echo $moduleclass_sfx; ?>">
    <div id="vmslideshow-loader<?php echo $mid; ?>" class="vmslideshow-loader">
        <div id="vmslideshow<?php echo $mid; ?>" class="vmslideshow">
            <div id="slider-container<?php echo $mid; ?>" class="slider-container">
                <ul id="slider<?php echo $mid; ?>">
                        <?php foreach ($slides as $slide) { ?>
                        <li>
                                <?php if (($slide->link && $params->get('link_image', 1) == 1) || $params->get('link_image', 1) == 2) { ?>
                                <a <?php echo ($params->get('link_image', 1) == 2 ? 'class="modal"' : ''); ?> href="<?php echo ($params->get('link_image', 1) == 2 ? $slide->image : $slide->link); ?>" target="<?php echo $slide->target; ?>">
                                <?php } ?>
                                <img src="<?php echo $slide->image; ?>" alt="<?php echo $slide->alt; ?>" />
                            <?php if (($slide->link && $params->get('link_image', 1) == 1) || $params->get('link_image', 1) == 2) { ?>
                                </a>
                            <?php } ?>

                            <?php
                            if ($params->get('show_title')
                                    || ($params->get('show_desc') && !empty($slide->description))
                                    || ($params->get('show_price') && !empty($slide->price))
                                    || ($params->get('show_address') && !empty($slide->address))
                            ) {
                                ?>

                                <!-- Slide description area: START -->

                                <div class="slide-desc">
                                    <div class="slide-desc-in">
                                        <div class="slide-desc-bg"></div>
                                        <div class="slide-desc-text">
                                                    <?php if ($params->get('show_title')) { ?>

                                                <div class="slide-text title">
                                                <?php if ($params->get('link_title') && $slide->link) { ?><a href="<?php echo $slide->link; ?>" target="<?php echo $slide->target; ?>"><?php } ?>
                                                <?php echo $slide->title; ?>
                                                <?php if ($params->get('link_title') && $slide->link) { ?></a><?php } ?>
                                                </div>
                                                <?php } ?>
                                                    <?php if ($params->get('show_price') && !empty($slide->price)) { ?>

                                                    <div class="slide-text price">
                                                    <?php if ($params->get('link_price') && $slide->link) { ?>
                                                        <a href="<?php echo $slide->link; ?>" target="<?php echo $slide->target; ?>">
                                                        <?php                
                                                        if ($vehiclemanager_configuration['price_unit_show'] == '1')
                                                            if ($vehiclemanager_configuration['sale_separator'])
                                                                echo formatMoney($slide->price, false, $vehiclemanager_configuration['price_format']), ' ', $slide->priceunit;
                                                             else echo $slide->price, ' ', $slide->priceunit;
                                                         else {
                                                              if ($vehiclemanager_configuration['sale_separator'])
                                                                echo $slide->priceunit, ' ', formatMoney($slide->price, false, $vehiclemanager_configuration['price_format']);
                                                              else echo $slide->priceunit, ' ', $slide->price;
                                                         }?>
                                                        </a>
                                                <?php } else { ?>
                                                    <?php   if ($vehiclemanager_configuration['price_unit_show'] == '1')
                                                            if ($vehiclemanager_configuration['sale_separator'])
                                                                echo formatMoney($slide->price, false, $vehiclemanager_configuration['price_format']), ' ', $slide->priceunit;
                                                             else echo $slide->price, ' ', $slide->priceunit;
                                                         else {
                                                              if ($vehiclemanager_configuration['sale_separator'])
                                                                echo $slide->priceunit, ' ', formatMoney($slide->price, false, $vehiclemanager_configuration['price_format']);
                                                              else echo $slide->priceunit, ' ', $slide->price;
                                                         } ?>
                                                <?php } ?>
                                                </div>

                                                <?php } ?>
                                                    <?php if ($params->get('show_address') && !empty($slide->address)) { ?>

                                                <div class="slide-text address">
                                                    <?php if ($params->get('link_address') && $slide->link) { ?>
                                                        <a href="<?php echo $slide->link; ?>" target="<?php echo $slide->target; ?>">
                                                        <?php echo strip_tags($slide->address); ?>
                                                        </a>
                                                <?php } else { ?>
                                                    <?php echo strip_tags($slide->address); ?>
                                                <?php } ?>
                                                </div>
                                                <?php } ?>
                                                    <?php if ($params->get('show_desc')) { ?>

                                                <div class="slide-text desc">
                                                    <?php if ($params->get('link_desc') && $slide->link) { ?>
                                                        <a href="<?php echo $slide->link; ?>" target="<?php echo $slide->target; ?>">
                                                        <?php echo strip_tags($slide->description); ?>
                                                        </a>
                                                <?php } else { ?>
                                                    <?php echo strip_tags($slide->description); ?>
						      <?php } ?>
                                                </div>
                                                    <?php } ?>

                                            <div style="clear: both"></div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Slide description area: END -->
                        <?php } ?>						

                        </li>
<?php } ?>
                </ul>
            </div>

            <div id="navigation<?php echo $mid; ?>" class="navigation-container">
                <img id="prev<?php echo $mid; ?>" class="prev-button" src="<?php echo $navigation->prev; ?>" alt="<?php echo JText::_('MOD_VMSLIDESHOW_PREVIOUS'); ?>" />
                <img id="next<?php echo $mid; ?>" class="next-button" src="<?php echo $navigation->next; ?>" alt="<?php echo JText::_('MOD_VMSLIDESHOW_NEXT'); ?>" />
                <img id="play<?php echo $mid; ?>" class="play-button" src="<?php echo $navigation->play; ?>" alt="<?php echo JText::_('MOD_VMSLIDESHOW_PLAY'); ?>" />
                <img id="pause<?php echo $mid; ?>" class="pause-button" src="<?php echo $navigation->pause; ?>" alt="<?php echo JText::_('MOD_VMSLIDESHOW_PAUSE'); ?>" />
            </div>
            <div id="cust-navigation<?php echo $mid; ?>" class="navigation-container-custom">
<?php $i = 0;
foreach ($slides as $slide) { ?>
                    <span class="load-button<?php if ($i == 0) echo ' load-button-active'; ?>"></span>
    <?php if (count($slides) == $i + $count) break; else $i++;
} ?>
            </div>
        </div>
    </div>

    <div style="clear: both"></div>
    <br>
</div>
