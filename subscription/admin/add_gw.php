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

	global $xoopsDB, $xoopsConfig, $xoopsModule;

	$tpl = new XoopsTpl();

	$gw = strtolower($_POST['gateway']);

	$config = array();
	$configPath = XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . 
			"/gateways/" . $gw;
	$configFile = $configPath . "/config.php";
	if (!file_exists($configFile)) {
		redirect_header("index.php", 5, "It does not look like " . $gw . 
				" is installed.<BR><BR>$configPath");
	}
	else {
		include_once $configFile;
	}
	$sql = "select conf_id from " . $xoopsDB->prefix("config") . 
		" where conf_name = 'gateway' and conf_modid = " .
		$xoopsModule->getVar("mid");
	$result = $xoopsDB->query($sql);
	list($confid) = $xoopsDB->fetchRow($result);

	// check to see if it already exists
	$sql = "select confop_id from " . $xoopsDB->prefix("configoption") . 
			" where confop_value = '" . $gw . "' and conf_id = $confid";
	$result = $xoopsDB->query($sql);
	list($opid) = $xoopsDB->fetchRow($result);
	if (!empty($opid)) {
		redirect_header("gateways.php", 5, "This gateway is already installed.");
	}

	$query = "insert into " . $xoopsDB->prefix("configoption") . 
		" (confop_name, confop_value, conf_id) values('%s', '%s', $confid)";
	$sql = sprintf($query, $gw, $gw);

	$xoopsDB->query($sql);


	$query = "insert into " . $xoopsDB->prefix("subscription_gateway_config") . 
		" (gateway, name, value, title, orderbit) " .
			" values('%s', '%s', '%s', '%s', %d)";

	for ($i = 0; $i < count($config); $i++) {
			$sql = sprintf($query, 
					$gw,
					$config[$i]['name'],
					$config[$i]['value'],
					$config[$i]['title'],
					$i);
			$xoopsDB->query($sql);
	}

	redirect_header("gateways.php", 5, "Gateway added successfully.");

?>

