<?php
$blockid = $_GET['blockid'];
$seating_ip = $_POST['seating_ip'];

switch($_GET['step']) {
	case 3:
		$seating_ip_exists = array();
		$seating_ip = array();

		if ($_POST['cell']) foreach($_POST['cell'] as $cur_cell => $value) if ($value) {
			$col = floor($cur_cell / 100);
			$row = $cur_cell % 100;

			// Check IP format
			if (!$func->checkIP($value)) {
		    $func->error($lang['seating']['err_ip_format'], '');
				$_GET['step'] = 2;
				break;
			}

			// Check for allready assigned IPs
			/*
			$current_ip = $db->query_first("SELECT 1 AS found FROM {$config["tables"]["seat_seats"]} WHERE ip = '$value'");
			if ($current_ip['found']) {
				$func->error($lang['seating']['err_double_ip'], '');
				$_GET['step'] = 2;
				break;
			}*/
		}
	break;
}

switch($_GET['step']) {
	default:
    $current_url = 'index.php?mod=seating&action=ip';
    $target_url = 'index.php?mod=seating&action=ip&step=2&blockid=';
    $target_icon = 'generate';
    include_once('modules/seating/search_basic_blockselect.inc.php');
	break;

	case 2:
		$dsp->NewContent($lang['seating']['seat_info'], $lang['seating']['seat_info_sub']);

		$dsp->SetForm("index.php?mod=seating&action=ip&step=3&blockid={$_GET['blockid']}");
		$dsp->AddSingleRow($seat2->DrawPlan($_GET['blockid'], 3));
		$dsp->AddFormSubmitRow('next');

		$dsp->AddBackButton('index.php?mod=seating&action=ip', 'seating/show');
		$dsp->AddContent();
	break;

	case 3:
		if ($_POST['cell']) foreach($_POST['cell'] as $cur_cell => $value) {
			$col = floor($cur_cell / 100);
			$row = $cur_cell % 100;

			$db->query_first("UPDATE {$config["tables"]["seat_seats"]} SET ip='$value'
				WHERE blockid = '{$_GET['blockid']}' AND row = '$row' AND col = '$col'");
		}
		$func->confirmation($lang['seating']['cf_add_ips'], 'index.php?mod=seating&action=ip');
	break;
}
?>