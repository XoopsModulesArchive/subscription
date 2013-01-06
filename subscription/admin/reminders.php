<?php
//  ------------------------------------------------------------------------ //
//                		Subscription Module for XOOPS													 //
//               Copyright (c) 2005 Third Eye Software, Inc.						 		 //
//                 <http://products.thirdeyesoftware.com/>									 //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
    include_once dirname(__FILE__) . '/admin_header.php';
	include_once "../../../include/cp_header.php";

//	define("SUB_DIR_NAME", "subscription");
	include_once "../include/functions.php";
	include_once XOOPS_ROOT_PATH . "/class/template.php";
	include_once XOOPS_ROOT_PATH . "/class/pagenav.php";
	include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";

	xoops_cp_header();

    $aboutAdmin = new ModuleAdmin();
    echo $aboutAdmin->addNavigation('reminders.php');
	global $xoopsDB, $xoopsConfig;

	$tpl = new XoopsTpl();

	global $xoopsDB, $xoopsConfig, $xoopsModule;

	if (isset($_POST['expdate'])) {
		if (!isset($_POST['confirm'])) {
			$sql = "select count(*) from " .
				$xoopsDB->prefix("subscription") . " s, " .
				$xoopsDB->prefix("subscription_user") . " su, " .
				$xoopsDB->prefix("users") . " u where " .
				"su.uid = u.uid and su.subid = s.subid and su.cancel = 'N' and " .
				"su.expiration_date <= '" . $_POST['expdate'] . "'";
			$res = $xoopsDB->query($sql);
			list($ct) = $xoopsDB->fetchRow($res);

			xoops_confirm(array('confirm'=>true, 'expdate'=>$_POST['expdate']), 'reminders.php', 
				'Are you sure you want to send reminders to these ' . $ct . ' users?');
		}
		else {
			
			$sql = "select u.uid, uname, s.name, su.expiration_date from " .
				$xoopsDB->prefix("subscription") . " s, " .
				$xoopsDB->prefix("subscription_user") . " su, " .
				$xoopsDB->prefix("users") . " u where " .
				"su.uid = u.uid and su.subid = s.subid and su.cancel = 'N' and " .
				"su.expiration_date <= '" . $_POST['expdate'] . "'";
			$res = $xoopsDB->query($sql);
			$i = 0;
			while (list($uid, $uname, $subname, $exp) = $xoopsDB->fetchRow($res)) {
				sendReminderEmail($uid, $uname, $subname, $exp);
				$i++;
			}
			redirect_header("index.php", 3, "$i reminder(s) were sent for " .	
				"subscriptions that expire before $expdate.");
		}
	}
	else {
		$form = new XoopsThemeForm("Send Expiration Reminders",
			'form','reminders.php');
		$form->addElement(
			new XoopsFormText('Expiration Date (YYYY-mm-dd hh:mi:ss)',
					'expdate', 50, 100, 
					''));
		$submit = new XoopsFormButton('', 'submit', '  Send  ', 'submit');
		$form->addElement($submit);
		$tpl->assign('form', $form->render());
		$tpl->display(XOOPS_ROOT_PATH .
			"/modules/" . $xoopsModule->getVar('dirname') .
			"/templates/subscription_admin_reminders.html");
	}

	xoops_cp_footer();

?>

