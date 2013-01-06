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

global $xoopsLogger, $xoopsDB, $xoopsUser, $xoopsModuleConfig, $_POST;

if (!is_object($xoopsUser)) {
	redirect_header(XOOPS_URL, 0, _NOPERM);
}

if (isset($_POST)) {
	foreach ($_POST as $k => $v) {
		${$k} = $v;
	}
}
if ($xoopsUser->getVar('uid') != $uid) {
	redirect_header("index.php", 5, _NOPERM);
}

if(!isset($agree)) {
	redirect_header("index.php", 5, "You must agree to the terms.");
}

$gatewayConfig = getGatewayConfig($xoopsModuleConfig['gateway']);
$delayedCapture = $xoopsModuleConfig['delayed_capture'];
if (strtoupper($delayedCapture) == 'Y') {
	$txtype = 'A';
}
else {
	$txtype = 'S';
}
// create paymentdata instance
$paymentData = new PaymentData(
		$cardnumber,
		$name,
		$address1,
		$address2,
		$city,
		$state,
		$zipcode,
		$country,
		$expirationmonth,
		$expirationyear,
		$cvv,
		$issuerphone,
		$amount,
		getNextInvoiceNumber(),
		$txtype);

$gw = &PaymentGatewayFactory::getPaymentGateway();
$gw->setLogger($xoopsLogger);
$gw->setConfig($gatewayConfig);
$gw->setDelayedCapture($delayedCapture);

$paymentResponse = 
		$gw->submitPayment($paymentData);

$id = recordPaymentTransaction($uid, $subid, $paymentData, $paymentResponse);

if ($paymentResponse->responseCode == 0) {
		if (strtoupper($delayedCapture) != 'Y') {
			addUserSubscription($xoopsUser->getVar('uid'), $subid);
			sendSubscriptionEmail($xoopsUser->getVar('uid'), $subid);
			redirect_header("paymentsuccess.php?tid=$id", 2, 
					"Your payment has been accepted...");
		}
		else {
			//delayed capture...
			redirect_header("paymentsuccess.php?tid=$id", 2, 
					"Your payment has been accepted but is pending approval.  You will " .
					" receive an email when your payment has been approved.");
		}
}
else {
		redirect_header("paymenterror.php?RESPMSG=" . 
			$paymentResponse->responseMessage, 1, "Your payment was rejected.");
}

?>

