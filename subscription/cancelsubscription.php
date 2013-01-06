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
include "header.php";
require("include/paymentgatewayfactory.php");
include_once("include/paymentdata.php");
include_once("include/paymentgateway.php");

global $xoopsUser, $xoopsDB, $xoopsConfig, $xoopsModuleConfig;

$gatewayConfig = getGatewayConfig($xoopsModuleConfig['gateway']);

//get sub types

if (!is_object($xoopsUser)) {
	redirect_header("index.php", 0, _NOPERM);
}

$sql = "select su.subid, s.name, su.cancel from " .
		$xoopsDB->prefix('subscription_user') . " su, " .
		$xoopsDB->prefix('users') . " u, "  .
		$xoopsDB->prefix('subscription') . " s where su.uid = " .
		$xoopsUser->getVar('uid') . " and u.uid = su.uid and " .
		" s.subid = su.subid and su.cancel = 'N'";

$result = $xoopsDB->query($sql);

$subs = array();

$i = 0;

while (list($subid, $subname, $cancel) = $xoopsDB->fetchRow($result)) {
	$subs[$i]['subid'] = $subid;
	$subs[$i]['subname'] = $subname;
	$subs[$i]['cancel'] = $cancel;
	$i++;
}
if ($i == 0) {
	redirect_header("index.php",3, "You are not a subscriber.");
}



if (!empty($_POST['email'])) {
	if (empty($_POST['subid'])) {
		redirect_header("cancelsubscription.php", 3, "You must select a " .
			"subscription to cancel.");
	}

	if (strtolower($xoopsUser->getVar('email')) == 
			strtolower($_POST['email'])) {
		cancelSubscription($xoopsUser->getVar('uid'), $_POST['subid']);

    $rdir = $gw->cancelUrl;
		if (isset($rdir)) {
			include("gateways/" . $xoopsModuleConfig['gateway'] . "/" .  $rdir);
		}
		else {
			redirect_header(XOOPS_URL . "/index.php", 3, "Your
				subscription has " .  "been canceled.");
		}
	} else {
		redirect_header("cancelsubscription.php", 3, 
				"You did not enter the correct email address.");
	}

}
include "../../header.php";
$xoopsOption['template_main'] = 'subscription_cancel.html';
$xoopsTpl->assign('subs', $subs);
include "../../footer.php";
?>

