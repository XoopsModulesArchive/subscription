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
	include "../../../include/cp_header.php";

	xoops_cp_header();
	global $xoopsDB, $xoopsConfig, $xoopsModule, $_POST;

	$gw = $_POST['gateway'];

	$sql = "delete from " . $xoopsDB->prefix("subscription_gateway_config") . 
			" where gateway = '$gw'";

	$xoopsDB->query($sql);

	if (isset($_POST['delete'])) {
		$sql = "select conf_id from " . $xoopsDB->prefix("config") . 
			" where conf_name = 'gateway' and conf_modid = " .
			$xoopsModule->getVar("mid");
		$result = $xoopsDB->query($sql);
		list($confid) = $xoopsDB->fetchRow($result);

		$sql = "delete from " . $xoopsDB->prefix("configoption") . 
				" where confop_name = '" . $gw . "' and conf_id = $confid";
		$xoopsDB->query($sql);
	}
	else {
		$query = "insert into " . $xoopsDB->prefix("subscription_gateway_config") . 
			" (gateway, name, title, value, orderbit) " .
				" values('%s', '%s', '%s', '%s', %d)";

		foreach ($_POST as $k=>$v) {
			if (eregi("$gw".":", $k)) {
				$names = explode(":", $k);
				$name = $names[1];
				$title = $_POST[$name]; // this is the title;
				$order = $names[2];
				$sql = sprintf($query, 
					$gw,
					$name,
					$title,
					$v,
					$order);
				$xoopsDB->query($sql);
			}
		}

		// update the module config with the default gw
		if (isset($_POST['active'])) {
			$query = "update " . $xoopsDB->prefix("config") . 
				" set conf_value = '" . $gw . "' where conf_modid = " . 
				$xoopsModule->getVar("mid") . " and conf_name = 'gateway'";
			$xoopsDB->query($query);
		}

	}

	redirect_header("gateways.php", 5, "The configuration was saved" .
			" successfully.");

?>

