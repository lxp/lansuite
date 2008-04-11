<?php
/*************************************************************************
*
*	Lansuite - Webbased LAN-Party Management System
*	-------------------------------------------------------------------
*	Lansuite Version:	2.0
*	File Version:		2.0
*	Filename: 		delete_cat.php
*	Module: 		FAQ
*	Main editor: 		Micheal@one-network.org
*	Last change: 		29.03.2003 18:56
*	Description: 		Removes Faq Data
*	Remarks:
*
**************************************************************************/

switch($_GET["step"]) {
	
	default:


	$get_cat = $db->query("SELECT catid, name FROM {$config["tables"]["faq_cat"]}");

	$count_cat = $db->num_rows($get_cat);

	if($count_cat == 0) { $func->information($lang['faq']['no_itenm'],"index.php?mod=home"); }

	else {

		$dsp->NewContent($lang['faq']['del_caption'],$lang['faq']['del_subcaption']);
		if($_SESSION['menu_status']['faq'][$_GET['faqcatid']] == "open") {
			$_SESSION['menu_status']['faq'][$_GET['faqcatid']] = "closed";
		}else {
			$_SESSION['menu_status']['faq'][$_GET['faqcatid']] = "open";
		}

		while($row=$db->fetch_array($get_cat)) {

			$templ["faq"]["overview"]["row"]["cat"]["titel"]	= $row["name"];
			$templ["faq"]["overview"]["row"]["cat"]["link"]	= "index.php?mod=faq&action=show&faqcatid={$row['catid']}";
			$templ['faq']['overview']['row']['question']['change']['change']['link']	= $dsp->FetchButton("index.php?mod=faq&object=cat&action=delete_cat&catid={$row['catid']}&step=2","delete");

			$faq_content .= $dsp->FetchModTpl("faq","faq_overview_row_cat");

			if($_SESSION['menu_status']['faq'][$row['catid']] == "open") {

				$get_item = $db->query("SELECT caption,itemid FROM {$config["tables"]["faq_item"]}
													WHERE catid = '{$row['catid']}'");
				while($row=$db->fetch_array($get_item)) {

					$templ["faq"]["overview"]["row"]["question"]["title"]	= $func->text2html($row["caption"]);
					$templ["faq"]["overview"]["row"]["question"]["id"]	= $row["itemid"];
					$templ['faq']['overview']['row']['question']['change']['change']['link']	= $dsp->FetchButton("index.php?mod=faq&object=item&action=delete_item&itemid={$row['itemid']}&step=2","delete");
					$faq_content .= $dsp->FetchModTpl("faq","faq_overview_row_question");

				}//while
			}//if
		}//while

		$dsp->AddSingleRow($faq_content, "class='menu'");
		$dsp->AddContent();

	} // close else
	
	break;

	case 2: 
	
		$get_catname = $db->query_first("SELECT name FROM {$config["tables"]["faq_cat"]} WHERE catid = '{$_GET["catid"]}'");
		
		if($get_catname["name"] != "") {
			
			$func->question(str_replace("%CATNAME%",$get_catname['name'],$lang['faq']['del_cat_quest']),"index.php?mod=faq&object=cat&action=delete_cat&catid={$_GET['catid']}&step=3","index.php?mod=faq&object=cat&action=delete_cat");
		}
		
			else {
				$func->error($lang['faq']['cat_not_exists'],"");	
			}	
	
	break;
	
	case 3:
		$get_catname = $db->query_first("SELECT name FROM {$config["tables"]["faq_cat"]} WHERE catid = '{$_GET["catid"]}'");
		
		if($get_catname["name"] != "") {
			
			$del_cat = $db->query("DELETE FROM {$config["tables"]["faq_cat"]} WHERE catid = '{$_GET["catid"]}'");
			$del_item = $db->query("DELETE FROM {$config["tables"]["faq_item"]} WHERE catid = '{$_GET["catid"]}'");
			
			if($del_cat == true && $del_item == true) {
				
				$func->confirmation($lang['faq']['del_cat_ok'],"index.php?mod=faq&object=cat&action=delete_cat");
			}
			
				else {
				
					$func->error("DB_ERROR","");
				}
		
		} //if
		
			else {	
				
				$func->error($lang['faq']['cat_not_exists'],"");
			}	
	
	break;

} // close switch step
?>
