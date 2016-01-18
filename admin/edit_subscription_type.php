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
	include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
	include_once XOOPS_ROOT_PATH . "/class/pagenav.php";
	include_once XOOPS_ROOT_PATH . "/class/xoopslists.php";

	include_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') .
		"/include/lists.php";
	include_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') .
		"/include/formselectsubscriptiontype.php";
	include_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') .
		"/include/formselectsubscriptioninterval.php";

	xoops_cp_header();
	global $xoopsDB, $xoopsConfig;

//die("access denied - under development");
	
	global $xoopsDB, $xoopsConfig, $xoopsModule;

	$subtypeid = $_POST['subtypeid'];
	include(XOOPS_ROOT_PATH.'/header.php');
	$tpl = new XoopsTpl();
	
	$sql = "select s.subtypeid, s.type, s.groupid, s.psid from " . 
			$xoopsDB->prefix("subscription_type") . "  s where subtypeid = $subtypeid";

	$result = $xoopsDB->query($sql);
	list($subtypeid, $type, $groupid, $psid) =
		$xoopsDB->fetchRow($result);

	$editForm = new XoopsThemeForm(
		'Edit Subscription Type', "subscription", "update_subscription_type.php");
	$subid= new XoopsFormHidden('subtypeid', $subtypeid);
	$editForm->addElement($subid);
	
	$subnamebox = new XoopsFormText("Type","type",20,50,$type);
	$editForm->addElement($subnamebox);

	$subtypeselect = new XoopsFormSelectSubscriptionType("Parent Subscription Type", 
		"psid", $psid, 1, null);
	$editForm->addElement($subtypeselect);

	$group_select = new XoopsFormSelectGroup('Group Permission', 'groupid', false,
			$groupid, 5, false);
	$editForm->addElement($group_select);

	$deletebox = new XoopsFormCheckBox('Delete?', 'delete');
	$deletebox->addOption('yes','Yes');

	$editForm->addElement($deletebox);

	$submit = new XoopsFormButton('', 'submit', '  Save  ', 'submit');
	$editForm->addElement($submit);
	$xoopsTpl->assign('editinstructions', 
		'Edit the following fields and click \'Save\' to commit your changes.');

	$xoopsTpl->assign('form', $editForm->render());

	$xoopsTpl->display(XOOPS_ROOT_PATH . 
			"/modules/subscription/templates/subscription_admin_edit_subscription_type.html");

	xoops_cp_footer();

?>
