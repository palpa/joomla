<?php
if (!defined('_VALID_MOS') && !defined('_JEXEC')) die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');
/**
 * @package  VehicleManager
 * @copyright 2012 Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); Rob de Cleen(rob@decleen.com)
 * Homepage: http://www.ordasoft.com
 * @version: 3.5 Free
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * */
require('./components/com_vehiclemanager/admin.vehiclemanager.class.conf.php');

// load language 
global $mosConfig_absolute_path, $mosConfig_lang,$mosConfig_live_site;

?>
<div class="adminform_01">
    <table class="adminform">
	<tr>
		<td><h3><?php echo _VEHICLE_MANAGER_DOC_GENERAL_INFO;?></h3></td>
	</tr>
	<tr>
		<td><strong> <?php echo _VEHICLE_MANAGER_DOC_VERSION;?></strong></td>
		<td><strong>v <?php echo $vehiclemanager_configuration['release']['version'];?></strong></td>
	</tr>
	<tr>
		<td><strong><?php echo _VEHICLE_MANAGER_DOC_RELEASE_DATE;?></strong></td>
		<td><strong><?php echo $vehiclemanager_configuration['release']['date'];?></strong></td>
	</tr>
	<tr>
		<td><strong><?php echo _VEHICLE_MANAGER_DOC_PROJECTLINK;?></strong></td>
		<td><strong><a href="http://www.ordasoft.com" target="blank">www.ordasoft.com</a></strong></td>
	</tr>
	<tr>
		<td><strong><?php echo _VEHICLE_MANAGER_DOC_PROJECT_HOST;?></strong></td>
		<td><strong>Andrey Kvasnevskiy (<a href="mailto:akbet@ordasoft.com" >akbet@ordasoft.com</a>)</strong></td>
	</tr>
  <tr>
    <td valign="top"><strong><?php echo _VEHICLE_MANAGER_DOC_LICENSE;?></strong></td>
    <td>
      <strong>
        <a href="<?php echo $mosConfig_live_site."/administrator/components/com_vehiclemanager/doc/LICENSE.txt"; ?>" target="blank">License</a>
      </strong>
      <br />
       <?php echo _VEHICLE_MANAGER_DOC_WARRANTY;?>
    </td>
  </tr>
  <tr>
    <td valign="top"><strong>README:</strong></td>
    <td>
      <strong>
        <a href="<?php echo $mosConfig_live_site."/administrator/components/com_vehiclemanager/doc/README.txt"; ?>" target="blank">README</a>
      </strong>
    </td>
  </tr>
	<tr >
		<td valign="top"><strong><?php echo _VEHICLE_MANAGER_DOC_DEVELOP;?></strong></td>
		<td>
			<ul>     
        <li><b>v 3.7 FREE</b> OrdaSoft: Andrey Kvasnevskiy(<a href="mailto:akbet@mail.ru" >akbet@mail.ru</a>),
                              Roman Akoev(<a href="mailto:akoevroman@gmail.com" >akoevroman@gmail.com</a>)</li>
        <li><b>v 3.5 FREE</b> OrdaSoft: Andrey Kvasnevskiy(<a href="mailto:akbet@mail.ru" >akbet@mail.ru</a>),
                              Roman Akoev(<a href="mailto:akoevroman@gmail.com" >akoevroman@gmail.com</a>)</li>
        <li><b>v 3.3 FREE</b> OrdaSoft: Andrey Kvasnevskiy(<a href="mailto:akbet@mail.ru" >akbet@mail.ru</a>)</li>
        <li><b>v 3.0 FREE</b> OrdaSoft: Andrey Kvasnevskiy(<a href="mailto:akbet@mail.ru" >akbet@mail.ru</a>)</li>
				<li><b>v 2.4 FREE</b> OrdaSoft: Andrey Kvasnevskiy(<a href="mailto:akbet@mail.ru" >akbet@mail.ru</a>)</li>
				<li><b>v 2.2 FREE</b> OrdaSoft: Andrey Kvasnevskiy(<a href="mailto:akbet@mail.ru" >akbet@mail.ru</a>)</li>
				<li><b>v 2.1 FREE</b> OrdaSoft: Andrey Kvasnevskiy(<a href="mailto:akbet@mail.ru" >akbet@mail.ru</a>)</li>
				<li><b>v 2.0 FREE</b> OrdaSoft: Andrey Kvasnevskiy(<a href="mailto:akbet@mail.ru" >akbet@mail.ru</a>)</li>
				<li><b>v 1.0.1 FREE</b> OrdaSoft: Sergey Drughinin (<a href="mailto:Sergey.dru@gmail.com" >Sergey.dru@gmail.com</a>), OrdaSoft: Andrey Kvasnevskiy(<a href="mailto:akbet@mail.ru" >akbet@mail.ru</a>), Roman Voloschuk(<a href="mailto:voloschuk_roman@ukr.net" >voloschuk_roman@ukr.net</a>)</li>
			</ul>
		</td>
	</tr>
    </table>
</div>
