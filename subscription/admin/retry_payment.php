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

include "../include/paymentgatewayfactory.php";
include "../include/paymentdata.php";
include "../include/paymentresponse.php";
include "../include/functions.php";

global $xoopsUser, $xoopsDB, $xoopsConfig, $xoopsLogger, $xoopsModuleConfig;

$txid = $_GET['txid'];

$delayedCapture = $xoopsModuleConfig['delayed_capture'];

//get sub types
$gw = PaymentGatewayFactory::getPaymentGateway();
$gwconfig = getGatewayConfig($xoopsModuleConfig['gateway']);
$gw->setConfig($gwconfig);
$gw->setLogger($xoopsLogger);
$gw->setDelayedCapture($delayedCapture);

if (!$gw->isDirect()) {
	redirect_header("tx_detail.php?txid=" . $txid, 2,
			"You must use a direct payment gateway for this function to work.");
}
$sql = "select uid from " . $xoopsDB->prefix("subscription_transaction") . 
	" where id = $txid";
$res = $xoopsDB->query($sql);
list($uid) = $xoopsDB->fetchRow($res);
if (empty($uid)) {
	redirect_header("tx_detail.php?txid=" . $txid, 2, "Could not find detail.");
}
$paymentData = &getLastPaymentData($uid);
$paymentData->invoiceNumber = getNextInvoiceNumber();
$paymentResponse = $gw->submitPayment($paymentData);
$id = recordPaymentTransaction($paymentData->uid, $paymentData->subid,
		$paymentData, $paymentResponse);
if ($paymentResponse->responseCode == 0) {
	if (strtoupper($delayedCapture) != 'Y') {
		addUserSubscription($xoopsUser->getVar('uid'), $subid);
		sendSubscriptionEmail($xoopsUser->getVar('uid'), $subid);
		redirect_header("tx_detail.php?txid=$id",2,
			"This transaction was successful.");
	} else {
		redirect_header("transactions.php",2,
			"You must now approve this transaction");
	}
}
else {
		redirect_header("tx_detail.php?txid=$id",2,
			"This transaction failed with the message: " . 
				$paymentResponse->responseMessage);
}
?>
