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
	global $xoopsDB, $xoopsConfig, $xoopsModule;

	if (isset($_POST)) {
		foreach ($_POST as $k => $v) {
			${$k} = $v;
		}
	}

	$sql = "select uid from " . $xoopsDB->prefix('users') . " where uname = '" . 
			$uname . "'";
	$result = $xoopsDB->query($sql);
	list($uid) = $xoopsDB->fetchRow($result);
	if (empty($uid)) {
		redirect_header("transactions.php", 5, "Could not find user '" .
			$uname . "'.");
	}
	
	if (empty($expirationdate) || empty($amount)) {
		redirect_header("transactions.php", 5, 
			"You must include both the amount and the expiration date.");
	}

	$exp = strtotime($expirationdate);
	$expDate = date("Y-m-d h:i:s", $exp);

	$sql = "insert into " . $xoopsDB->prefix("subscription_transaction") . " (uid, subid," .
		"nameoncard, address, city, state, zipcode, referencenumber, " .
		"responsecode, response, amount, transactiondate) values(" .
		"$uid, $subid, '$name', '$address', '$city', '$state', '$zipcode', " .
		"'$checknumber', '0', '$info', '$amount', now())";


	$xoopsDB->query($sql);

	$sql = "select st.groupid from " . 
		$xoopsDB->prefix("subscription_type") . 
		" st, " . $xoopsDB->prefix("subscription") . " s " .
		" where s.subtypeid = st.subtypeid and s.subid = $subid";
	$result = $xoopsDB->query($sql);
	list($gid) = $xoopsDB->fetchRow($result);

	if (!empty($gid)) {
		$member_handler = &xoops_getHandler('member');
		$member_handler->addUserToGroup($gid, $uid);
	}

	$sql = "insert into " . $xoopsDB->prefix("subscription_user") . 
		" (subid, uid, expiration_date, intervaltype, intervalamount, " .
		"amount, cancel) values ($subid, $uid, '$expDate', 'x', 0, '$amount','N')";

	$xoopsDB->query($sql);

	redirect_header('transactions.php', 1, 'The subscription has been created.');

	xoops_cp_footer();

?>


