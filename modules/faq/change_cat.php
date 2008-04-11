<?php
/*************************************************************************
*
*	Lansuite - Webbased LAN-Party Management System
*	-------------------------------------------------------------------
*	Lansuite Version:	2.0
*	File Version:		2.0
*	Filename: 		change_cat.php
*	Module: 		FAQ
*	Main editor: 		Micheal@one-network.org
*	Last change: 		01.04.2003 13:16
*	Description: 		Changes FAQ Cats
*	Remarks:
*
**************************************************************************/

switch($_GET["step"]) {
	
	case 3:
	//  ERRORS
	$get_cat_names = $db->query("SELECT name FROM {$config["tables"]["faq_cat"]}");
	
		while($row=$db->fetch_array($get_cat_names)) {
			
			$name = $row["name"];
		
				if($name == $_POST["cat_caption"]) {
				
					$faq_error['cat_caption'] = $lang['faq']['cat_exists'];
					$_GET["step"] = 2;
				}
		}
			
			
		if($_POST["cat_caption"] == "") {
			
			$faq_error['cat_caption']	= $lang['faq']['no_cat_name'];
			
			eval($error);
			
			$_GET["step"] = 2;
			
		}
			
				
	break;
		
} // close switch


switch($_GET["step"]) {

	
	case 2:
		
	$get_data = $db->query_first("SELECT name FROM {$config["tables"]["faq_cat"]} WHERE catid = '{$_GET["catid"]}'");
	$_POST["cat_caption"] = $get_data["name"];
		
	$_SESSION["change_blocker_faq_cat"] = "";
		
		if($_POST["cat_caption"] != "") {
			
			$dsp->NewContent($lang['faq']['change_cat_caption']);
			$dsp->SetForm("index.php?mod=faq&object=cat&action=change_cat&catid={$_GET['catid']}&step=3");
			$dsp->AddTextFieldRow("cat_caption",$lang['faq']['change_cat_caption'],$_POST['cat_caption'],$faq_error['cat_caption']);
			$dsp->AddFormSubmitRow("edit");
			$dsp->AddContent();
				
		}
		
			else {
		
				$func->error($lang['faq']['cat_not_exists'],"");
			}
			
	break;
		
	
	case 3:
		
		if($_SESSION["change_blocker_faq_cat"] != 1) {
		
			$get_data = $db->query_first("SELECT name FROM {$config["tables"]["faq_cat"]} WHERE catid = '{$_GET["catid"]}'");
			$catcaption = $get_data["name"];
		
				if($catcaption != "") {
				
					$change_it = $db->query("UPDATE {$config["tables"]["faq_cat"]} SET name = '{$_POST[cat_caption]}' WHERE catid = '{$_GET["catid"]}'");
				
						if($change_it == true) {
				
							$_SESSION["change_blocker_faq_cat"] = 1;
															
							$func->confirmation($lang['faq']['change_cat_ok'],"index.php?mod=faq&action=show");
				
						} 
				
							else { 
					
								$func->error("DB_ERROR","");
				
							}
				
				} 		
				
					else {
		
						$func->error($lang['faq']['cat_not_exists'],"");
					}			
		
		} // close if blocker
		
			else {
			
				$func->error("NO_REFRESH","");
			}
				
		
	break;
		
	
} // close switch step
	
?>
	
