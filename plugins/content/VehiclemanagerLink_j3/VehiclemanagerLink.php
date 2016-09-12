<?php

/**
 * @version 2.2
 * @package vehiclemanager
 * @copyright 2011 Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); 
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

/** Ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

if (!defined("DS")) define("DS", DIRECTORY_SEPARATOR);
if (version_compare(JVERSION, "1.6.0", "lt"))
{
if( !function_exists( 'getWhereUsergroupsCondition')) {
  function getWhereUsergroupsCondition ( $table_alias = "c" ) {
    global $my;

    if ( !isset ($my) ) {
      if ( $my  = JFactory::getUser() ) {
        $gid = $my->gid;
      }
      else  $gid = 0;
    } else
        $gid = $my->gid;

    $usergroups_sh = array ( $gid, -2 );
    $s = '';
    for ($i=0; $i<count($usergroups_sh); $i++) {
      $g = $usergroups_sh[$i];
      $s .= " $table_alias.params LIKE '%,{$g}' or $table_alias.params = '{$g}' or $table_alias.params LIKE '{$g},%' or $table_alias.params LIKE '%,{$g},%' ";
      if ( ($i+1)<count($usergroups_sh) )
        $s .= ' or ';
    }
    return $s;
  }
}


function vehicle_link( &$row, &$params, $page=0 ) {
	//global $database;
	$database = JFactory::getDBO();
	// Load the Bot's params (all)
    if (version_compare(JVERSION,"2.5.0","lt")) {
        $query ="SELECT id FROM #__plugins WHERE element = 'VehiclemanagerLink' AND folder = 'content'";
    } else {
        $query ="SELECT extension_id FROM #__extensions WHERE element = 'VehiclemanagerLink_j3' AND folder = 'content'";
    }
	$database->setQuery( $query );
	$id = $database->loadResult();
	include_once(JPATH_SITE."/libraries/joomla/database/table/plugin.php");
	$mambot = new JTablePlugin( $database );
	$mambot->load( $id );
    if (version_compare(JVERSION,"2.5.0","lt")) {
        $botparams = new JParameter($mambot->params );   // Use "$botparms" to avoid overwritting "$params" (from Module)
    } else {
        $botparams = new JRegistry;
        $botparams->loadString( $mambot->params );   // Use "$botparms" to avoid overwritting "$params" (from Module)
    }
	$botparams = new JParameter( $mambot->params );   // Use "$botparms" to avoid overwritting "$params" (from Module)
	     $regex = '/{(vehiclemanager)\s*(.*?)}/i';
	//$regex = '/{(vehicle)\s*(.*?)}/i';
	if ( strpos($row->text,$regex) === 0 ) {
			// nothing to do...
		} else {
		  // Bot Published => replace {RealEstateRealEstatelibrary isbn=..} with Book
			$row->text = showVehicle($row->text, $botparams);
		} 
	
	return true;
}

function showVehicle($text, $botparams) {
	$database = JFactory::getDBO();
	 $regex = '/{(vehiclemanager)\s*(.*?)}/i';
	//$regex = '/{(vehicle)\s*(.*?)}/i';
	preg_match_all( $regex, $text, $matches );
	
	$query = "SELECT id  FROM #__menu WHERE menutype like '%menu%' AND link LIKE '%option=com_vehiclemanager%'";
	$database->setQuery($query);
	$ItemId = $database->loadResult();
	
   	foreach ($matches[2] as $Vehicle){
   		$text = preg_replace ($regex, linkToVehicle($Vehicle, $ItemId, $botparams), $text, 1);	
   	}
	return $text;
}


function linkToVehicle($Vehicleparam, $ItemId, $botparams){
	global $mosConfig_live_site; 

	$database = JFactory::getDBO();

	$userparams = new JParameter( str_replace('&nbsp;',"\n",str_replace('&',"\n",html_entity_decode($Vehicleparam))) );
	$id = $userparams->get('id', '');
       	if ($id == ""){ 
		return "<strong> Vehicle not found !</strong>";
	}

  //$usergroups=getUserGroups();
  $s = getWhereUsergroupsCondition("c");

$query = "SELECT v.*, c.id as catid FROM  #__vehiclemanager_vehicles AS v, #__vehiclemanager_categories AS vc ,
           #__vehiclemanager_main_categories AS c
    WHERE ($s) AND v.approved=1 AND v.published=1 AND vc.iditem=v.id AND c.id=vc.idcat AND v.id= '".$id."'";
	$database->setQuery($query);
	$Vehicle = null;
	$Vehicle=$database->loadObject();

	if ($Vehicle){
		$target = $userparams->get('target', $botparams->get('target', '_self'));
		$title_align = $userparams->get('title_align', $botparams->get('title_align', 'left'));
		$Vehicle_align = $userparams->get('Vehicle_align', $botparams->get('Vehicle_align', 'no'));
		$caption = $userparams->get('caption', $botparams->get('caption', ''));
		
		
/*		if($botparams->get('useDefaultStylesheets', '') == '1') {
			$stylesheetImageContainer = $botparams->get('stylesheetImageContainer', '');
			$stylesheetImageBorder = $botparams->get('stylesheetImageBorder', '');
			$stylesheetImageImageBorder = $botparams->get('stylesheetImageImageBorder', '');
			$stylesheetImageCaption = $botparams->get('stylesheetImageCaption', '');
		} else {
			$stylesheetImageContainer = "";
			$stylesheetImageBorder = "";
			$stylesheetImageImageBorder = "";
			$stylesheetImageCaption = "";
		}
*/
    $img =$Vehicle->image_link;
    //for local images
    if($img != '' && substr($img,0,4) != "http")
    { $img = "./components/com_vehiclemanager/photos/".$Vehicle->image_link;
      //  $img = JURI::base() . $book->imageURL;;
    }
        
    if($img  == ''){
           $img = "./components/com_vehiclemanager/images/no-img_eng.gif";
    }         

    if ($img != ""){
        $size = '';
        $imgsize = '';
        if ( function_exists( 'getimagesize' ) ) {
           $size     = @getimagesize( $img );

      		 if (is_array( $size )) {
                    
              $size[0]=150; // width
		          $size[1]=100; //height
              $imgsize = ' width="'. $size[0] .'" height="'. $size[1] .'" ';
           }
        }
        $image = '<img src="'.$img.'" ' .
                    'border="0" ' .
                    'alt="'.$Vehicle->vtitle.'" ' .
                    'name="image" ' .
                    $imgsize .
                    'align="'.$Vehicle_align.'" ' .
                    '/>';
     }else{
        $image = '';
     } 
        
        
		  $link = '<a href="'.JRoute::_('index.php?option=com_vehiclemanager' .
					'&amp;task=view_vehicle' .
					'&amp;id='.$Vehicle->id.
					'&amp;catid='.$Vehicle->catid.
					'&amp;Itemid='.$ItemId).'" ' .
          'target="'.$target.'">';
        
      $width = $size[0];
      
      $title = $Vehicle->vtitle;
      if(strlen($title)>80) $title = substr_replace($title, '<br>', 80, 0);
            
/*        if ($title_align == 'left' || $title_align == 'right'){
            $width = $size[0] + 200 ;
        } */
      $VehicleHTML = '';
      $VehicleHTML .= '<table width="" border="0"><tr>'
                .'<td width="" align='.$Vehicle_align.'>';

      $VehicleHTML .= '<table border="0"><tr>';
      if($title_align!='off') {
          if($title_align=='top') $title_align = 'center';
          $VehicleHTML .="<td align='$title_align'>";
          $VehicleHTML .= ''.$caption;
          $VehicleHTML .= '<br />'.$link.$title.'</a>';
          $VehicleHTML .= '</td>';
      }
                      

      $VehicleHTML .= '</tr>';
      $VehicleHTML .= '<tr>';
      $VehicleHTML .= '<td align='.$Vehicle_align.'>';

      $VehicleHTML .= ''.$link.$image.'</a>';
      $VehicleHTML .= '</td>';
      $VehicleHTML .= '</tr>';

                    
      if($title_align=='bottom') {
        $VehicleHTML .= '<tr>';
        $VehicleHTML .="<td align='$title_align'>";
        $VehicleHTML .= ''.$caption;
        $VehicleHTML .= '<br />'.$link.$title.'</a>';
        $VehicleHTML .= '</td>';
        $VehicleHTML .= '</tr>';
       }
       $VehicleHTML .= '</table>';
       $VehicleHTML .= '</td>';
       $VehicleHTML .= '</tr>';
       $VehicleHTML .= '</table>';

       return $VehicleHTML;  
	}else{
		return "<strong> Vehicle not found !</strong>";
	}
    }
}


if (version_compare(JVERSION, "1.6.0", "lt"))
{
    $mainframe->registerEvent( 'onPrepareContent', 'vehicle_link' );
}
else
{
//===========================================JooMla 2.5.3===========================================
jimport( 'joomla.html.parameter' );
jimport('joomla.plugin.plugin');
require_once ( JPATH_ROOT .DS.'components'.DS.'com_vehiclemanager'.DS.'functions.php' );

    if( !function_exists( 'sefRelToAbs')) {
        function sefRelToAbs( $value ) {
            //Need check!!!

            // Replace all &amp; with & as the router doesn't understand &amp;
            $url = str_replace('&amp;', '&', $value);
            if(substr(strtolower($url),0,9) != "index.php") return $url;
            $uri    = JURI::getInstance();
            $prefix = $uri->toString(array('scheme', 'host', 'port'));
            return $prefix.JRoute::_($url);
        }
    }

if (version_compare(JVERSION, "3.0.0","lt")) {
class plgContentVehiclemanagerLink extends JPlugin {


  public function onContentPrepare($context, &$article, &$params, $page = 0) {

    // checking
		if (!isset($article->text)||!preg_match("#{vehiclemanager id(.*?)}#s", $article->text) ) {
			return;
		}
    //    
		$document = JFactory::getDocument();
		$database = JFactory::getDBO();		
    $mainframe = JFactory::getApplication();
    

     
      // Load the Bot's params (all)
      if (version_compare(JVERSION,"2.5.0","lt")) {
          $query ="SELECT id FROM #__plugins WHERE element = 'VehiclemanagerLink' AND folder = 'content'";
      } else {
          $query ="SELECT extension_id FROM #__extensions WHERE element = 'VehiclemanagerLink_j3' AND folder = 'content'";
      }
      $database->setQuery( $query );
      $id = $database->loadResult();
      
            
      // checking again
      $regex = '/{(vehiclemanager)\s*(.*?)}/i';
      if ( strpos($article->text,$regex) === 0 ) {
          // nothing to do...
        } else {
          $article->text = showVehicle($article->text, $this->params);
        } 
	
	return true;
   

    }

    public function onContentAfterDisplay($context, &$article, &$params, $limitstart=0) {

      		$app = JFactory::getApplication();
      }
}
// --
}
else {
    //=========================================== Joomla 3.0 ===========================================
    
    class plgContentVehiclemanagerLink_j3 extends JPlugin {


        public function onContentPrepare($context, &$article, &$params, $page = 0) {


            // checking
            if (!isset($article->text)||!preg_match("#{vehiclemanager id(.*?)}#s", $article->text) ) {
                return;
            }
            //
            $document = JFactory::getDocument();
            $database = JFactory::getDBO();
            $mainframe = JFactory::getApplication();

            // Load the Bot's params (all)
            if (version_compare(JVERSION,"2.5.0","lt")) {
                $query ="SELECT id FROM #__plugins WHERE element = 'VehiclemanagerLink' AND folder = 'content'";
            }
            else {
                $query ="SELECT extension_id FROM #__extensions WHERE element = 'VehiclemanagerLink_j3' AND folder = 'content'";
            }
            $database->setQuery( $query );
            $id = $database->loadResult();


            // checking again
            $regex = '/{(vehiclemanager)\s*(.*?)}/i';
            if ( strpos($article->text,$regex) === 0 ) {
                // nothing to do...
            } else {
                $article->text = showVehicle($article->text, $this->params);
            }

            return true;

        }

        public function onContentAfterDisplay($context, &$article, &$params, $limitstart=0) {
            $app = JFactory::getApplication();
        }
    }
}

function showVehicle($text, $botparams) {
	$database = JFactory::getDBO();
	$regex = '/{(vehiclemanager)\s*(.*?)}/i';
	preg_match_all( $regex, $text, $matches );
	
  $database->setQuery("SELECT id  FROM #__menu WHERE link LIKE'%option=com_vehiclemanager%' AND params LIKE '%back_button%'  ");
  $ItemId = $database->loadResult();    
	
        foreach ($matches[2] as $Vehicle){
   		$text = preg_replace ($regex, linkToVehicle($Vehicle, $ItemId, $botparams), $text, 1);	
   	}
	return $text;
}

function linkToVehicle($Vehicleparam, $ItemId, $botparams){
	global $mosConfig_live_site; 

	$database = JFactory::getDbo();
        
        if (version_compare(JVERSION,"2.5.0","lt")) {
            $userparams = new JParameter( str_replace('&nbsp;',"\n",str_replace('&',"\n",html_entity_decode($Vehicleparam))) );
        }
        else {
            $userparams = new JRegistry;
            $userparams->loadString(str_replace('&nbsp;',"\n",str_replace('&',"\n",html_entity_decode($Vehicleparam))));
        }

	/*
        $id = $userparams->get('id', '');
        if(version_compare(JVERSION,"2.5.0","ge")) {
            $id = str_replace("<span style=\"text-align: -webkit-center;\">", "",$id);
            $id = str_replace("</span>", "",$id);
        }
         * 
         */
        $Vehicleparam = preg_replace("/ +/", "", $Vehicleparam);        
        $id = (int)str_replace("id=", "", $Vehicleparam);       
	if ($id == false){
		return "<strong> Vehicle not found !</strong>";
	}

$s = vmLittleThings::getWhereUsergroupsCondition ();

        $query = "SELECT v.*, c.id as catid FROM  #__vehiclemanager_vehicles AS v, #__vehiclemanager_categories AS vc ,
                    #__vehiclemanager_main_categories AS c
                    WHERE ($s) AND v.approved=1 AND v.published=1 AND vc.iditem=v.id AND c.id=vc.idcat AND v.id= '".$id."'";
        $database->setQuery($query);


        $Vehicle = $database->loadObject();
        
        if (count($Vehicle) > 0){
            $target = $userparams->get('target', $botparams->get('target', '_self'));
            $title_align = $userparams->get('title_align', $botparams->get('title_align', 'left'));
            $Vehicle_align = $userparams->get('Vehicle_align', $botparams->get('Vehicle_align', 'no'));
            $caption = $userparams->get('caption', $botparams->get('caption', ''));
		
            $img =$Vehicle->image_link;
            //for local images
            if($img != '' && substr($img,0,4) != "http")
            { $img = "./components/com_vehiclemanager/photos/".$Vehicle->image_link;
              //  $img = JURI::base() . $book->imageURL;;
            }

            if($img  == ''){
                   $img = "./components/com_vehiclemanager/images/no-img_eng.gif";
            }         

            if ($img != ""){
                $size = '';
            $imgsize = '';
            if ( function_exists( 'getimagesize' ) ) {
                $size = @getimagesize( $img );

      		 if (is_array( $size )) {
                    
                $size[0]=150; // width
		          $size[1]=100; //height
              $imgsize = ' width="'. $size[0] .'" height="'. $size[1] .'" ';
           }
        }
        $image = '<img src="'.$img.'" ' .
                    'border="0" ' .
                    'alt="'.$Vehicle->vtitle.'" ' .
                    'name="image" ' .
                    $imgsize .
                    'align="'.$Vehicle_align.'" ' .
                    '/>';
     }
     else{
        $image = '';
     } 
        
        
		  $link = '<a href="'.JRoute::_('index.php?option=com_vehiclemanager' .
					'&amp;task=view_vehicle' .
					'&amp;id='.$Vehicle->id.
					'&amp;catid='.$Vehicle->catid.
					'&amp;Itemid='.$ItemId).'" ' .
          'target="'.$target.'">';
        
      $width = $size[0];
      
      $title = $Vehicle->vtitle;
      if(strlen($title)>80) $title = substr_replace($title, '<br>', 80, 0);
            
/*        if ($title_align == 'left' || $title_align == 'right'){
            $width = $size[0] + 200 ;
        } */
      $VehicleHTML = '';
      $VehicleHTML .= '<table class="basictable" width="" border="0"><tr>'
                .'<td width="" align='.$Vehicle_align.'>';

      $VehicleHTML .= '<table border="0"><tr>';
      if($title_align!='off') {
          if($title_align=='top') $title_align = 'center';
          $VehicleHTML .="<td align='$title_align'>";
          $VehicleHTML .= ''.$caption;
          $VehicleHTML .= '<br />'.sefRelToAbs( $link ) .$title.'</a>';
          $VehicleHTML .= '</td>';
      }
                     
      $VehicleHTML .= '</tr>';
      $VehicleHTML .= '<tr>';
      $VehicleHTML .= '<td align='.$Vehicle_align.'>';

      $VehicleHTML .= ''.sefRelToAbs( $link ) .$image.'</a>';
      $VehicleHTML .= '</td>';
      $VehicleHTML .= '</tr>';
              
      if($title_align=='bottom') {
        $VehicleHTML .= '<tr>';
        $VehicleHTML .="<td align='$title_align'>";
        $VehicleHTML .= ''.$caption;
        $VehicleHTML .= '<br />'.sefRelToAbs( $link ) .$title.'</a>';
        $VehicleHTML .= '</td>';
        $VehicleHTML .= '</tr>';
       }
       $VehicleHTML .= '</table>';
       $VehicleHTML .= '</td>';
       $VehicleHTML .= '</tr>';
       $VehicleHTML .= '</table>';

       return $VehicleHTML;  
	}
    }
}
?>
