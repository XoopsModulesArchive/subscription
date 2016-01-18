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
	require('header.php');
	include "../../class/xoopsformloader.php";

	global $_POST, $xoopsUser, $xoopsModule;

	if (!is_object($xoopsUser)) {
		redirect_header("index.php", 0, _NOPERM);
		exit();
	}

	if (!isset($_POST)) {
		redirect_header("index.php", 3, 
				"There is a problem with the previous form.");
		exit();
	}

	if (isset($_POST)) {
		foreach ($_POST as $k => $v) {
			${$k} = $v;
		}
	}

	if (!isset($subid)) {
		redirect_header("index.php", 0, "You must select a subscription.");
	}

	$uname = $xoopsUser->getVar('uname','E'); 
	$xoopsOption['template_main'] = 'payment_index.html';
	
	include(XOOPS_ROOT_PATH.'/header.php');

	$xoopsTpl->assign('subid', $subid);
	$xoopsTpl->assign('subtypeid',$subtypeid);
	$xoopsTpl->assign('uid', $xoopsUser->getVar('uid','E'));
	$xoopsTpl->assign('uname', $xoopsUser->getVar('uname','E'));

	$select = new XoopsFormSelectCountry('',
			'country', SUB_HOME_COUNTRY);
	$xoopsTpl->assign('countryselect', $select->render());


	$sql = "SELECT subid, xs.name, format(price,2) price, 
			xsi.intervaltype, xsi.intervalamount FROM " .  
			$xoopsDB->prefix("subscription") . " xs
			inner join " . $xoopsDB->prefix("subscription_interval") .
			" xsi on xs.subintervalid = 
			xsi.subintervalid where subid = ".$subid;

	$result = $xoopsDB->query($sql);
	if ($result) {
		list($subid, $subname, $price, $intervaltype, $intervalamount) = 
			$xoopsDB->fetchRow($result);
		$expDate = ($intervaltype == 'p') ? "Never" : date("m/d/Y h:i:s",
			getExpirationDate(time(), $intervaltype, $intervalamount));
		$xoopsTpl->assign('subid',$subid);
		$xoopsTpl->assign('subname',$subname);
		$xoopsTpl->assign('price',$price);
		$xoopsTpl->assign('currencysymbol',
				getCurrencySymbol($xoopsModuleConfig['currency']));
		$xoopsTpl->assign('intervaltype',$intervaltype);
		$xoopsTpl->assign('intervalamount',$intervalamount);
		$xoopsTpl->assign('expirationdate', $expDate);
		
	}
	else {
		die("an error occured");
	}

	$xoopsTpl->assign("formurl",
		XOOPS_URL . "/modules/" . $xoopsModule->getVar("dirname") .
		"/submitpayment.php");

	$xoopsTpl->assign("referer",
		XOOPS_URL . "/modules/" . $xoopsModule->getVar("dirname") .
		"/directpayment.php");
	$xoopsTpl->assign("success",
		XOOPS_URL . "/modules/" . $xoopsModule->getVar("dirname") .
		"/paymentsuccess.php");

	$xoopsTpl->assign("secure_base", XOOPS_URL);

	include(XOOPS_ROOT_PATH.'/footer.php');

?>


