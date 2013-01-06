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
	include_once "../../../include/cp_header.php";
	include_once XOOPS_ROOT_PATH . "/class/template.php";
	include_once XOOPS_ROOT_PATH . "/class/pagenav.php";
	include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";

	xoops_cp_header();
	global $xoopsDB, $xoopsConfig;

	$tpl = new XoopsTpl();

	global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig;

	$gw = $_POST['gateway'];

	$form = new XoopsThemeForm(ucfirst($gw) . " " .
			'Gateway Configuration','form','update_gw_config.php');
	$form->addElement(
		new XoopsFormHidden('gateway',$gw));

	$sql = "select name, title, value, orderbit  from " . 
			$xoopsDB->prefix("subscription_gateway_config") . " where gateway = '$gw'";
	$sql .= " order by orderbit asc";

	$result = $xoopsDB->query($sql);
	while (list($conf_name, $conf_title, $conf_value, $orderbit) = 
			$xoopsDB->fetchRow($result)) {

		$form->addElement(
			new XoopsFormText(
					$conf_title,
					$gw . ":" . $conf_name . ":" . $orderbit,
					50, 150, 
					$conf_value));

		$form->addElement(
				new XoopsFormHidden($conf_name, $conf_title));

	}

	$val = '';
	if ($gw == $xoopsModuleConfig['gateway']) {
		$val = "yes";
		
	}
	$defaultbox = new XoopsFormCheckBox('Active Gateway?', 'active', $val);
	$defaultbox->addOption('yes','Yes');
	if ($val == "yes") {
		$defaultbox->setExtra(" disabled ");
	}
	$form->addElement($defaultbox);

	$deletebox = new XoopsFormCheckBox('Remove this Gateway?', 'delete');
	$deletebox->addOption('yes','Yes');
	$form->addElement($deletebox);

	$submit = new XoopsFormButton('', 'submit', '  Save  ', 'submit');
	$form->addElement($submit);
	$tpl->assign('form', $form->render());

	$tpl->display(XOOPS_ROOT_PATH .
		"/modules/" . $xoopsModule->getVar('dirname') .
		"/templates/subscription_admin_edit_gw.html");

	xoops_cp_footer();

?>

