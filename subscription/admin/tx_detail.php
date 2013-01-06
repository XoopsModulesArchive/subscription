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
	include_once XOOPS_ROOT_PATH . "/class/xoopslists.php";
	include_once XOOPS_ROOT_PATH . "/class/pagenav.php";
	xoops_cp_header();

//die("access denied - under development");
	
	global $xoopsDB, $xoopsConfig, $xoopsModule;
	
	$tpl = new XoopsTpl();
	
	if (isset($_GET['txid'])) {
		$txid = $_GET['txid'];
	}
	
	$sql = "select id, subid, uid,cardnumber, expirationmonth, " . 
		" expirationyear, cvv, issuerphone, " . 
		" nameoncard, address, city, state, zipcode, " .
		" referencenumber, authcode, responsecode, response, amount, " .
		" transactiondate, transactiontype " .
		" from " . $xoopsDB->prefix("subscription_transaction") . " st " .
		" where id = $txid";

	$result = $xoopsDB->query($sql);
	list($id, $subid, $uid, $number, $month, $year, $cvv, $issuerphone, 
			$name, $address, $city, $state, $zipcode, $ref, $authcode, 
			$responsecode, $response, $amount, $txdate,$txtype) = $xoopsDB->fetchRow($result);
		if ($txtype == 'A') {
			$txtype_desc = 'Authorization';
		} else if ($txtype == 'S') {
			$txtype_desc = 'Sale';
		}
		else if ($txtype == 'V') {
			$txtype_desc = 'Void';
		}
		else {
			$txtype_desc = 'Capture';
		}
		$tpl->assign('tx', array(
			'txid'=>$id,
			'number'=>$number,
			'code'=>$cvv,
			'month'=>$month,
			'year'=>$year,
			'name'=>$name,
			'address'=>$address,
			'city'=>$city,
			'state'=>$state,
			'zipcode'=>$zipcode,
			'ref'=>$ref,
			'authcode'=>$authcode,
			'txdate'=>$txdate,
			'response'=>$response,
			'responsecode'=>$responsecode,
			'amount'=>$amount,
			'txtype'=>$txtype_desc));
	$tpl->display(XOOPS_ROOT_PATH . 
		"/modules/" . $xoopsModule->getVar('dirname') . 
		"/templates/subscription_admin_tx_detail.html");

	xoops_cp_footer();

?>
