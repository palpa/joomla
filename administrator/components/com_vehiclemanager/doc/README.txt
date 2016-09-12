====================================================================================================
VehicleManager PRO, version 3.2, for Joomla 1.5.x, Joomla 1.6.x, Joomla 1.7.x, Joomla 2.5.x and PHP5
====================================================================================================


CONTENTS
========

01. Introduction
02. System Requirements
03. License
04. Installation
05. Upgrade Instructions
06. UPGRADE INSTRUCTIONS FROM PREVIOUS 1.5.x Pro VERSIONS TO Pro WITHOUT LOST DATA 
07. Getting started
08. Support
09. Addons
10. Languages
11. Other components/plugins
12. Customisation
13. Keeping up-to-date



01. INTRODUCTION
----------------

The VehicleManager Pro component for Joomla allows you to manage and maintain 
a vehicles with ease on a Joomla-based website. 

Because VehicleManager incorporates Lend-Return management and user contributed 
Vehicle Review options, it is excellent for Vehicle shop management. 
It can be used to set up and manage shops, vehicle rentals, vehicle ehtusiast 
sites, brand sites, etc. 

The Pro version of VehicleManager has a lot of added functionality compared to 
the Free version. For full details on the different options visit 
http://www.joomlawebserver.com.

Version    : 3.2 Pro
Maintainers: Andrey Kvasnevskiy
Homepage   : http://www.ordasoft.com



02. SYSTEM REQUIREMENTS
-----------------------

VehicleManager Pro is a Joomla component. It needs a functioning Joomla 1.5.x 
installation. VehicleManager Pro needs PHP 5 with CURL, XSL and GD extensions 
to function properly.

PLEASE CHECK BEFORE INSTALLING VehicleManage:
In order for csv export to work, you need to compile PHP5 with support for the 
XSL extension!
In order for CAPTCHA images (Guest User anti-spam for reviews and suggestions) 
to work, you need to compile PHP5 with the GD extension!

CHECK YOUR PHP INSTALLATION FOR PROPER EXTENSIONS:
First please make sure PHP5 has the above extensions enabled!
- If you run your own web server, please recompile PHP with support for XSL, 
  CURL and GD.
- If your website is with a hosting provider, check with them for the inclusion 
  of these PHP extensions.
VehicleManager needs these PHP extensions to install and function correctly!

INSTALLER CHECK FOR PHP EXTENSIONS:
The VehicleManager installer will check for the availability of these PHP extensions 
and issue a warning if they are missing. If you get such a warning, just uninstall 
VehicleManager, fix the PHP extensions first, and then reinstall VehicleManager.

PLEASE NOTE:
Even though we can do checks for the proper PHP extensions inside the VehicleManager 
installer package, we have no way to roll back the installation once started, not 
even when a check fails. So installation will continue, even if a PHP extension is 
not present. This is unfortunately a limitation of the Joomla installer!



03. LICENSE
-----------

VehicleManager Pro is released as a commercial component.
Check the included LICENSE.txt file for license details.
There will also a Free version available (with limited functionality), released 
under the GNU/GPL.



04. INSTALLATION
----------------

VehicleManager Pro is installed easily with the standard Joomla component installer. 
For additional information on how to set up and configure VehicleManager Pro to 
suit your needs, please consult the [VehicleManager Manuals] section on the website.



05. UPGRADE INSTRUCTIONS FROM PREVIOUS 2.1 FREE VERSIONS TO Pro WITHOUT LOST DATA 
----------------------------------------------------------

Full save so files and folders:

{yours site}/administrator/components/com_vehiclemanager
{yours site}/components/com_vehiclemanager

Please do full dataBase backup.

Then go to [VehicleManager] [Settings Backend] and set [Update] to “YES”

The uninstall basic component and install Pro version.

Please recove folders
{yours site}/components/com_vehiclemanager/edocs
{yours site}/components/com_vehiclemanager/photos

Also you will need upgrade all plugins and modules

And all will work.

At first please check Upgrade process at test site



06. UPGRADE INSTRUCTIONS FROM PREVIOUS 1.5.x Pro VERSIONS TO Pro WITHOUT LOST DATA 
----------------------------------------------------------

Full save so files and folders:

{yours site}/administrator/components/com_vehiclemanager
{yours site}/components/com_vehiclemanager

Please do full vehicles xml import.

After that please remove
{yours site}/administrator/components/com_vehiclemanager
{yours site}/components/com_vehiclemanager

Please do full VehicleManager xml import version.

Please recover folders:
{yours site}/components/com_vehiclemanager/edocs
{yours site}/components/com_vehiclemanager/photos

Also you will need upgrade all plugins and modules
And all will work.

At first please check Upgrade process at test site



07. GETTING STARTED
-------------------

The [VehicleManager Pro 2.2.x Manuals] section on the website has many articles 
with instructions on VehicleManage setup and use.



08. SUPPORT
-----------

The OrdaSoft site has a [Support Forums] section for support to the 
VehicleManager component, modules and plugins. There is a special forum section 
dedicated to registered VehicleManager Pro users.



09. ADDONS (MODULES, PLUGINS)
-----------------------------

Modules and plugins (mambots) are constantly being developed for use with 
VehicleManager. You can download them from the download sections on the website.



10. LANGUAGES
-------------

VehicleManager Pro comes with English and Russian language files included.
The VehicleManager interface will automatically pick up the frontend or backend 
language set in your Joomla configuration. Frontend language switches with 
JoomFish will also result in the automatic language change in VehicleManager.

You can add non-included languages to VehicleManager by creating your own 
translations. 
Copy english.php from the directory /components/com_vehiclemanager/language/, 
rename it to your language and then create the translation. Next add the 
language selectors to the code and upload the translation to your VehicleManage.
You can find full instructions on creating translations and adding languages 
on the website.
Remember that Joomla 1.5.x needs files saved as UTF-8, so do NOT use Notepad or
Wordpad (they are not UTF-8 capable)!



11. OTHER COMPONENTS/PLUGINS
----------------------------

Add-ons for interaction with other components are also available developed 
(SEF, Sitemap). Community Builder and full JoomFish integration will be 
developed later.



12. CUSTOMISATION
-----------------

If you need a new specific feature added to VehicleManager Pro for your own 
installation, you can order a custom development. 
Just contact sales@ordasoft.com describing the details of your requirements. 
We will then investigate your request and send you a price quote for this 
development. When you pay for a customisation for VehicleManager, you will receive 
the next version of VehicleManager Pro with your feature included.



14. KEEPING UP-TO-DATE
----------------------

Please check http://www.ordasoft.com for news, details and contact 
information regarding VehicleManage. In time there will also be a VehicleManager 
Newsletter to which you can subscribe (news, developments, etc.).