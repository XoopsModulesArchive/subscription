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
	include_once XOOPS_ROOT_PATH . "/class/template.php";
	include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
	include_once XOOPS_ROOT_PATH . "/class/xoopslists.php";
	include_once XOOPS_ROOT_PATH . "/class/pagenav.php";
	include_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') .
		"/include/lists.php";
	include_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') .
		"/include/formselectsubscriptiontype.php";
	include_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') .
		"/include/formselectsubscriptioninterval.php";
	xoops_cp_header();

$aboutAdmin = new ModuleAdmin();
echo $aboutAdmin->addNavigation('subscriptionintervals.php');
	
	global $xoopsDB, $xoopsConfig, $xoopsModule;
	
	$tpl = new XoopsTpl();

	$existingSubForm = new XoopsThemeForm(
		'Existing Subscription Intervals','intervals',"edit_subscription_interval.php");

	$sub_select = new XoopsFormSelect('Subscription Intervals', 'subintervalid');
		
	$sql = "SELECT s.subintervalid, s.name, s.intervalamount, s.intervaltype " . 
			"from " . $xoopsDB->prefix("subscription_interval") . " s order by s.orderbit";

	$result = $xoopsDB->query($sql);

	while (list($subintervalid, $name) = $xoopsDB->fetchRow($result)) {
		$sub_select->addOption($subintervalid, $name);
	}

	$existingSubForm->addElement($sub_select);
	$modifybutton = new XoopsFormButton('', 'submit', '  Modify ', 'submit');

	$existingSubForm->addElement($modifybutton);
	$tpl->assign('existingsubform', $existingSubForm->render());
	$tpl->assign('editinstructions', 
		'To modify an existing subscription interval, choose subscription from the dropdown.');
	
	$createForm = new XoopsThemeForm(
		'Create New Subscription Interval', "sub", "create_subscription_interval.php");

	$subtypename = new XoopsFormText("Subscription Interval", "name",20,50,'');
	$createForm->addElement($subtypename);

	$intervaltypes = new XoopsFormSelect('Interval Types', 'intervaltype');
	$intervaltypes->addOption('d','Day');
	$intervaltypes->addOption('w','Week');
	$intervaltypes->addOption('m','Month');
	$intervaltypes->addOption('y','Year');
	$intervaltypes->addOption('p','Permanent');
	$createForm->addElement($intervaltypes);

	$intervalamount = new XoopsFormText("Interval Amount",'intervalamount',2,3,'0');
	$createForm->addElement($intervalamount);

	$order = new XoopsFormText("Sort Order",'orderbit',2,3,'0');
	$createForm->addElement($order);

	$createbutton = new XoopsFormButton('','submit', ' Create ', 'submit');
	$createForm->addElement($createbutton);
	
	$tpl->assign('form', $createForm->render());

	$tpl->display(XOOPS_ROOT_PATH . 
			"/modules/subscription/templates/subscription_admin_subscription_intervals.html");

	xoops_cp_footer();

?>
