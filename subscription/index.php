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
$xoopsOption['template_main'] = 'subscription_index.html';

if (!is_object($xoopsUser)) {
	redirect_header(XOOPS_URL, 0, _NOPERM);
}

//get sub types

$sql = "select subid, expiration_date, cancel from " .
		$xoopsDB->prefix("subscription_user") . " su where cancel = 'N' and uid = " .
		$xoopsUser->getVar("uid");
$result = $xoopsDB->query($sql);
$existing_subs = array();
while (list($subid,$exp,$cancel) = $xoopsDB->fetchRow($result)) {
	$existing_subs[$subid] = 
		array('subid'=>$subid,
					'expirationdate'=>$exp,
					'cancel'=>$cancel);
}

$sql = "select subtypeid, type from " . $xoopsDB->prefix("subscription_type");

$result = $xoopsDB->query($sql);
$types = array();
$i = 0;
while (list($subtypeid, $subtypename) = $xoopsDB->fetchRow($result)) {
	$sql = "select xs.subid, xs.name, xs.subintervalid, i.name, xs.price from " .
			$xoopsDB->prefix("subscription") . " xs, " . 
			$xoopsDB->prefix("subscription_interval") . " i where " .
			"i.subintervalid = xs.subintervalid and " .
			"xs.subtypeid = $subtypeid order by " . 
			"xs.orderbit asc ";
	$xoopsLogger->addExtra("sql",$sql);
	$subresult = $xoopsDB->query($sql);
	$z = 0;
	$subs = array();

	while (list($subid, $name, $intervalid, $intervalname, $price) =
			$xoopsDB->fetchRow($subresult)) {
	
		$subs[$z]['subid'] = $subid;
		$subs[$z]['name'] = $name;
		$subs[$z]['intervalid'] = $intervalid;
		$subs[$z]['intervalname'] = $intervalname;
		$subs[$z]['price'] = $price;
		if (array_key_exists($subid, $existing_subs)) {
			$sub = $existing_subs[$subid];
			$subs[$z]['current'] = ($sub['cancel'] == 'N');
			$subs[$z]['expirationdate'] = $sub['expirationdate'];
		}
		else {
			$subs[$z]['current'] = false;
		}
		$z++;
	}
	$gw = &PaymentGatewayFactory::getPaymentGateway();	

	$scheme = "http://";
	if ($xoopsModuleConfig['ssl_enabled'] == 'Y') {
		$scheme = "https://";
	}
	$uri = parse_url(XOOPS_URL);

	if (!empty($uri['port']) && $uri['port'] != 80) {
		$prt = ":" . $uri['port'];
	}
	else {
		$prt = "";
	}

	$url = $scheme . $uri['host'] . $prt . $uri['path'] . "/modules/" . 
			$xoopsModule->getVar('dirname') . "/";

	if ($gw->isDirect()) {
		$xoopsTpl->assign('formurl', $url . "directpayment.php");
	}
	else {
		$xoopsTpl->assign("formurl", $url . "indirectpayment.php");
	}

	$xoopsTpl->append('types', array('name'=>$subtypename, 'id'=>$subtypeid,
		'subs'=>$subs, 'subcount'=>count($subs)));
}
	$symbol = getCurrencySymbol($xoopsModuleConfig['currency']);

	$xoopsTpl->assign('currencysymbol', $symbol);

include "../../footer.php";

?>
