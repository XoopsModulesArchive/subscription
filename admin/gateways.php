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
		"/include/formselectgateway.php";

	xoops_cp_header();

    $aboutAdmin = new ModuleAdmin();
    echo $aboutAdmin->addNavigation('gateways.php');
	global $xoopsDB, $xoopsConfig;

	
	global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig;;
	
	$tpl = new XoopsTpl();

	$gwForm = new XoopsThemeForm(
		'Installed Gateways','gateway',"edit_gw_config.php");

	$gwselect = new XoopsFormSelectGateway('Gateways', 'gateway',
			$xoopsModuleConfig['gateway'],1);

	$gwForm->addElement($gwselect);

	$modifybutton = new XoopsFormButton('', 'submit', '  Modify ', 'submit');

	$gwForm->addElement($modifybutton);
	$tpl->assign('gwform', $gwForm->render());

	$tpl->assign('editinstructions', 
		"To configure a payment gateway, select it from the list and click " . 
				"'Modify'");

	$createForm = new XoopsThemeForm(
		'Add Gateway', "gw", "add_gw.php");

	$gwname = new XoopsFormText("Gateway Name", "gateway",20,50,'');

	$createForm->addElement($gwname);

	$createbutton = new XoopsFormButton('','submit', ' Create ', 'submit');
	$createForm->addElement($createbutton);
	
	$tpl->assign('form', $createForm->render());

	$tpl->display(XOOPS_ROOT_PATH . 
			"/modules/subscription/templates/subscription_admin_gateways.html");

	xoops_cp_footer();

?>
