<?php

function getExpirationDate($tm, $type, $number) {
	if ($type == 'x') {
		return $tm;
	}

	$date_time_array = getdate($tm);

  $hours = $date_time_array['hours'];
	$minutes = $date_time_array['minutes'];
	$seconds = $date_time_array['seconds'];
	$month = $date_time_array['mon'];
	$day = $date_time_array['mday'];
	$year = $date_time_array['year'];

	switch ($type) {
		case 'y':   // add year
			$year += $number;
			break;
		case 'w':
			$day += ($number * 7);
			break;
		case 'd':
			$day += $number;
			break;
		case 'm':
			$month += $number;
			break;
	}
	$timestamp= mktime($hours,$minutes,$seconds,$month,$day,$year);
	return $timestamp;

}

function addUserSubscription($uid, $subid, $expDate = null) {

	$db = getDatabaseConnection();

	$sql = "select si.intervalamount, si.intervaltype, s.price, t.groupid from " . 
			XOOPS_DB_PREFIX . "_subscription_interval si, " .
			XOOPS_DB_PREFIX . "_subscription s, " .
			XOOPS_DB_PREFIX . "_subscription_type t where si.subintervalid = " . 
			" s.subintervalid  and s.subid = $subid" .
			" and t.subtypeid = s.subtypeid" ;

	$res =&  mysql_query($sql, $db);
	list($intamt, $inttype, $amount, $gid) = @mysql_fetch_row($res);
	if (!isset($expDate)) {
		$dt = getExpirationDate(time(), $inttype, $intamt);
		$expDate = date("Y-m-d h:i:s", $dt);
	}

	$sql = "insert into " . XOOPS_DB_PREFIX . "_subscription_user" . 
		" (subid, uid, expiration_date, intervaltype, intervalamount, amount) " .
		" values ($subid, $uid, '$expDate', '$inttype', $intamt, '$amount')";

	mysql_query($sql, $db);

	$linkid = addUserGroup($db, $gid, $uid);
	//mysql_close($db);

}

function addUserGroup($db, $gid, $uid) {

	$sql = "insert into " . XOOPS_DB_PREFIX . "_groups_users_link " .
		"(groupid, uid) values ($gid, $uid)";
	mysql_query($sql, $db);
	$linkid = mysql_insert_id($db);
	return $linkid;
}

/** 
 * record payment transaction
 * @param uid user making the payment
 * @param subid the subid for the subscription being paid for
 * @param data instance of paymentdata containing the payment details
 * @param response instance of paymentresponse containing the payment
 * 									gateway response.
 */
function recordPaymentTransaction($uid, $subid, $data, $response) {
	$db = getDatabaseConnection();
	$sql = "";

	$sql = sprintf(" insert into %s " .
				"(id, subid, uid, cardnumber, cvv, issuerphone, expirationmonth, " . 
				" expirationyear, " . 
				" nameoncard, address, city, state, zipcode, country, amount, " . 
				" authcode, response, " .
				" responsecode, referencenumber, transactiondate, transactiontype) " .
				" values(%u, %u, " .
				" %u, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', " .
				" '%s', '%s', '%s', '%s', '%s', '%s', now(), '%s')", 
				XOOPS_DB_PREFIX . "_subscription_transaction",
				$data->invoiceNumber,
				$subid,
				$uid, 
				$data->cardNumber,
				$data->cvv,
				$data->issuerPhone,
				$data->expirationMonth,
				$data->expirationYear,
				$data->nameOnCard,
				$data->address1,
				$data->city,
				$data->state,
				$data->zipcode,
				$data->countrycode,
				$data->amount,
				$response->authCode,
				$response->responseMessage,
				$response->responseCode,
				$response->referenceNumber,
				$data->txType);
	mysql_query($sql, $db) or die(@mysql_error());
	return $data->invoiceNumber;
}

function updatePaymentTransaction($txid, $paymentData, $response) {
	$db = getDatabaseConnection();
	$sql = "";
	$sql = sprintf("update %s set referencenumber = '%s', " .
			"responsecode = %u, response = '%s', transactiondate = now(), " .
			"transactiontype = '%s' where id = %u",
		XOOPS_DB_PREFIX . "_subscription_transaction",
		$response->referenceNumber,
		$response->responseCode,
		$response->responseMessage,
		$paymentData->txType,
		$txid);
	mysql_query($sql, $db);
}

function revokeUserSubscription($uid, $subid) {

	$db = getDatabaseConnection();
	$sql = "select groupid from " . XOOPS_DB_PREFIX . 
		"_subscription_type t, " . XOOPS_DB_PREFIX . 
		"_subscription s WHERE s.subtypeid = t.subtypeid and " .
		" s.subid = $subid";
	$res = mysql_query($sql, $db);
	if ($res) {
		list($gid) = mysql_fetch_row($res);
		if (!empty($gid)) {
			$sql = "delete from " . XOOPS_DB_PREFIX . "_groups_users_link " .
			" where uid = $uid and groupid = $gid";
			mysql_query($sql, $db);
		}
		$sql = "update " . XOOPS_DB_PREFIX . "_subscription_user set " . 
			"cancel = 'Y' where subid = $subid and uid = $uid";
		mysql_query($sql, $db);
	}
	//mysql_close($db);
}

function renewUserSubscription($uid, $subid, $expdate) {
	$db = getDatabaseConnection();
	$sql = "update " . XOOPS_DB_PREFIX . "_subscription_user " . 
		" set expiration_date = '$expdate'" . 
		", cancel = 'N' where uid = $uid and subid = $subid";
	mysql_query($sql, $db);
	//mysql_close($db);
}

function getPaymentDataById($txid) {
	$db = getDatabaseConnection();
	$sql = "select id, uid, subid, cardnumber, cvv, issuerphone, " . 
		"expirationmonth, expirationyear, nameoncard, address, city, state, " . 
		"zipcode,  country, amount, referencenumber,transactiontype  from " . 
		XOOPS_DB_PREFIX . "_subscription_transaction " . 
		" where id = $txid";

	$data = null;

	$res = mysql_query($sql, $db);
	if ($res) {
		list($id, $uid, $subid, $cardnumber, $cvv, $issuerphone, 
				$expmonth, $expyear, $name, $address, $city, $state, $zip, $ctry,
				$amount, $refnum, $txtype) = mysql_fetch_row($res);
		if (!empty($id)) {
			$data = new PaymentData(
				$cardnumber,
				$name,
				$address,
				'',
				$city,
				$state,
				$zip,
				$ctry,
				$expmonth,
				$expyear,
				$cvv,
				$issuerphone,
				$amount,
				$refnum,
				$txtype);
			$data->id = $id;
			$data->subid = $subid;
			$data->uid = $uid;

		}
	}
	return $data;
}

function getPaymentByReferenceNumber($uid, $pnref) {
	$db = getDatabaseConnection();
	$sql = "select id, uid, subid, cardnumber, cvv, issuerphone, " . 
		"expirationmonth, expirationyear, nameoncard, address, city, state, " . 
		"zipcode,  country, amount, referencenumber, transactiontype from " . 
		XOOPS_DB_PREFIX . "_subscription_transaction " . 
		" where referencenumber = '$pnref' and uid = $uid order by transactiondate desc";

	$data = null;

	$res = mysql_query($sql, $db);
	if ($res) {
		list($id, $uid, $subid, $cardnumber, $cvv, $issuerphone, 
				$expmonth, $expyear, $name, $address, $city, $state, $zip, $ctry,
				$amount, $refnum, $txtype) = mysql_fetch_row($res);
		if (!empty($id)) {
			$data = new PaymentData(
				$cardnumber,
				$name,
				$address,
				'',
				$city,
				$state,
				$zip,
				$ctry,
				$expmonth,
				$expyear,
				$cvv,
				$issuerphone,
				$amount,
				$refnum,
				$txtype);
		}
	}
	return $data;
}

function getLastPaymentData($uid) {
	$db = getDatabaseConnection();
	$sql = "select id, uid, subid, cardnumber, cvv, issuerphone, " . 
		"expirationmonth, expirationyear, nameoncard, address, city, state, " . 
		"zipcode,  country, amount, referencenumber,transactiontype from " . 
		XOOPS_DB_PREFIX . "_subscription_transaction " . 
		" where uid = $uid order by transactiondate desc";

	$data = null;

	$res = mysql_query($sql, $db);
	if ($res) {
		list($id, $uid, $subid, $cardnumber, $cvv, $issuerphone, 
				$expmonth, $expyear, $name, $address, $city, $state, $zip, $ctry,
				$amount, $refnum,$txtype) = mysql_fetch_row($res);
		if (!empty($id)) {
			if (empty($txtype)) $txtype = 'S';
			$data = new PaymentData(
				$cardnumber,
				$name,
				$address,
				'',
				$city,
				$state,
				$zip,
				$ctry,
				$expmonth,
				$expyear,
				$cvv,
				$issuerphone,
				$amount,
				$refnum,
				$txtype);
			$data->subid = $subid;
			$data->uid = $uid;
		}
	}
	return $data;
}

function getDatabaseConnection() {
	$db = @mysql_connect(XOOPS_DB_HOST, XOOPS_DB_USER, XOOPS_DB_PASS);
	mysql_select_db(XOOPS_DB_NAME);
	return $db;
}
function runRenewals() {
	global $xoopsModuleConfig;

	$db = @mysql_connect(XOOPS_DB_HOST, XOOPS_DB_USER, XOOPS_DB_PASS);
	mysql_select_db(XOOPS_DB_NAME);

	$now = date("Y-m-d H:i:s", time());

	$sql = "select uid, subid, intervaltype, intervalamount, amount, cancel " .
			"from " . XOOPS_DB_PREFIX . "_subscription_user where expiration_date " .
			" <= '$now' and intervaltype not in ('x', 'p')  and cancel = 'N'";
	$res = mysql_query($sql, $db);
	$gw = PaymentGatewayFactory::getPaymentGateway();
	$gatewayConfig = getGatewayConfig($xoopsModuleConfig['gateway']);
	$gw->setConfig($gatewayConfig);

	while (list($uid, $subid, $intervaltype, $intervalamount, $amt, $cancel) =
			mysql_fetch_row($res)) {
		$disable = false;
		switch ($intervaltype) {
			case 'p':
				// do nothing
				break;
			case 'x': //manual payment entry
				$disable = true;
				break;
			default:
				if ($gw->isDirect()) {
					$paymentData = & getLastPaymentData($uid);
					$paymentData->invoiceNumber = getNextInvoiceNumber();
					$paymentData->amount = $amt;
					$paymentData->txType = "S";
					$response = $gw->submitPayment($paymentData);
					$id = recordPaymentTransaction($uid, $subid, $paymentData, $response);
					if ($response->responseCode == 0) {
						$expDate = date("Y-m-d h:i:s", 
								getExpirationDate(time(), $intervaltype, $intervalamount));
						renewUserSubscription($uid, $subid, $expDate);
					}
					else {
						revokeUserSubscription($uid, $subid);
					}
				}
		}
	}
} //end runRenewals

function sendReminderEmail($uid, $uname, $subname, $expdate) {
	global $xoopsConfig;

	$mailer = &getMailer();
	$mailer->useMail();
	$mailer->setTemplateDir(XOOPS_ROOT_PATH . "/modules/" . SUB_DIR_NAME . 
		"/language/" . $xoopsConfig['language'] . "/mail");
	$mailer->setTemplate("subscription_reminder.tpl");
	$mailer->setToUsers(new XoopsUser($uid));
	$mailer->assign("SUBNAME", $subname);
	$mailer->assign("USERNAME", $uname);
	$mailer->assign("EXPDATE", $expdate);
	$mailer->assign("ADMINMAIL", $xoopsConfig['adminmail']);
	$mailer->assign("SITEURL", $xoopsConfig['xoops_url']);
	$mailer->assign("SITENAME", $xoopsConfig['sitename']);
	$mailer->setFromEmail($xoopsConfig['adminmail']);
	$mailer->setFromName($xoopsConfig['sitename']);
	$mailer->setSubject("Subscription Reminder");
	$mailer->send();
}
function sendCancelEmail($uid, $subid) {
	global $xoopsConfig;
	$db = getDatabaseConnection();

	$sql = "select uname, s.name from " .
		XOOPS_DB_PREFIX . "_subscription_transaction t, " . 
		XOOPS_DB_PREFIX . "_users u, " .
		XOOPS_DB_PREFIX . "_subscription s  where " .
		"s.subid = t.subid and u.uid = t.uid and " .
		"t.uid = $uid and t.subid = $subid";
	$res = mysql_query($sql, $db);
	list($uname, $subname) = @mysql_fetch_row($res);
	$mailer = &getMailer();
	$mailer->useMail();
	$mailer->setTemplateDir(XOOPS_ROOT_PATH . "/modules/" . SUB_DIR_NAME . 
		"/language/" . $xoopsConfig['language'] . "/mail");
	$mailer->setTemplate("subscription_admin_cancel.tpl");
	$mailer->setToEmails($xoopsConfig['adminmail']);
	$mailer->assign("SUBNAME", $subname);
	$mailer->assign("USERNAME", $uname);
	$mailer->assign("ADMINMAIL", $xoopsConfig['adminmail']);
	$mailer->assign("SITEURL", $xoopsConfig['xoops_url']);
	$mailer->assign("SITENAME", $xoopsConfig['sitename']);
	$mailer->setFromEmail($xoopsConfig['adminmail']);
	$mailer->setFromName($xoopsConfig['sitename']);
	$mailer->setSubject("Subscription Cancellation");
	$mailer->send();
}

function sendSubscriptionEmail($uid, $subid) {
	global $xoopsConfig;
	$user = new XoopsUser($uid);
	
	$db = getDatabaseConnection();
	$sql = "select s.name, si.intervalamount, si.intervaltype, s.price, " . 
			" t.groupid from " . 
			XOOPS_DB_PREFIX . "_subscription_interval si, " .
			XOOPS_DB_PREFIX . "_subscription s, " .
			XOOPS_DB_PREFIX . "_subscription_type t where si.subintervalid = " . 
			" s.subintervalid  and s.subid = $subid" .
			" and t.subtypeid = s.subtypeid" ;

	$res = mysql_query($sql, $db);
	list($subname, $intamt, $inttype, $amount, $gid) = @mysql_fetch_row($res);

	if ($inttype == 'p') {
		$expDate = "Permanent - Does Not Expire";
	}
	else {
		$dt = getExpirationDate(time(), $inttype, $intamt);
		$expDate = date("Y-m-d h:i:s", $dt);
	}
	
	$mailer = &getMailer();
	$mailer->useMail();
	$mailer->setTemplateDir(XOOPS_ROOT_PATH . "/modules/" . SUB_DIR_NAME . 
		"/language/" . $xoopsConfig['language'] . "/mail");
	$mailer->setTemplate("subscription_new.tpl");
	$mailer->setToUsers($user);
	$mailer->assign("SUBNAME", $subname);
	$mailer->assign("EXPDATE", $expDate);
	$mailer->assign("ADMINMAIL", $xoopsConfig['adminmail']);
	$mailer->assign("SITEURL", $xoopsConfig['xoops_url']);
	$mailer->assign("SITENAME", $xoopsConfig['sitename']);
	$mailer->setFromEmail($xoopsConfig['adminmail']);
	$mailer->setFromName($xoopsConfig['sitename']);
	$mailer->setSubject(SUB_EMAIL_SUBJECT);
	$mailer->send();

	$mailer = &getMailer();
	$mailer->useMail();
	$mailer->setTemplateDir(XOOPS_ROOT_PATH . "/modules/" . SUB_DIR_NAME . 
		"/language/" . $xoopsConfig['language'] . "/mail");
	$mailer->setTemplate("subscription_admin_new.tpl");
	$mailer->setToEmails($xoopsConfig['adminmail']);
	$mailer->setSubject(SUB_EMAIL_SUBJECT);
	$mailer->assign("SUBNAME", $subname);
	$mailer->assign("UNAME", $user->getVar('uname'));
	$mailer->send();
}

function sendVoidEmail($uid, $subid) {
	global $xoopsConfig;
	
	$db = getDatabaseConnection();
	$sql = "select s.name, si.intervalamount, si.intervaltype, s.price, " . 
			" t.groupid from " . 
			XOOPS_DB_PREFIX . "_subscription_interval si, " .
			XOOPS_DB_PREFIX . "_subscription s, " .
			XOOPS_DB_PREFIX . "_subscription_type t where si.subintervalid = " . 
			" s.subintervalid  and s.subid = $subid" .
			" and t.subtypeid = s.subtypeid" ;

	$res = mysql_query($sql, $db);
	list($subname, $intamt, $inttype, $amount, $gid) = @mysql_fetch_row($res);

	if ($inttype == 'p') {
		$expDate = "Permanent - Does Not Expire";
	}
	else {
		$dt = getExpirationDate(time(), $inttype, $intamt);
		$expDate = date("Y-m-d h:i:s", $dt);
	}
	
	$mailer = &getMailer();
	$mailer->useMail();
	$user = new XoopsUser($uid);

	$mailer->setTemplateDir(XOOPS_ROOT_PATH . "/modules/" . SUB_DIR_NAME . 
		"/language/" . $xoopsConfig['language'] . "/mail");
	$mailer->setTemplate("subscription_void.tpl");
	$mailer->setToUsers($user);
	$mailer->assign("SUBNAME", $subname);
	$mailer->assign("ADMINMAIL", $xoopsConfig['adminmail']);
	$mailer->assign("SITEURL", $xoopsConfig['xoops_url']);
	$mailer->assign("SITENAME", $xoopsConfig['sitename']);
	$mailer->setFromEmail($xoopsConfig['adminmail']);
	$mailer->setFromName($xoopsConfig['sitename']);
	$mailer->setSubject(SUB_EMAIL_VOID_SUBJECT);
	$mailer->send();

	/*$mailer = &getMailer();
	$mailer->useMail();
	$mailer->setTemplateDir(XOOPS_ROOT_PATH . "/modules/" . SUB_DIR_NAME . 
		"/language/" . $xoopsConfig['language'] . "/mail");
	$mailer->setToEmails($xoopsConfig['adminmail']);
	$mailer->setTemplate("subscription_admin_void.tpl");
	$mailer->setSubject(SUB_EMAIL_VOID_SUBJECT);
	$mailer->assign("SUBNAME", $subname);
	$mailer->assign("UNAME", $user->getVar('uname'));
	$mailer->send();
	*/
}

function getNextInvoiceNumber() {
	$db = getDatabaseConnection();
	$sql = "update " . XOOPS_DB_PREFIX . "_sequences set nextval = ";
	$sql .= "nextval + 1 where sequencename = 'subscription_transaction_seq'";

	mysql_query($sql, $db);

	$sql = "select nextval from " . XOOPS_DB_PREFIX . "_sequences " . 
		"where sequencename = " .
		"'subscription_transaction_seq'";
	$res = mysql_query($sql, $db);
	list($val) = @mysql_fetch_row($res);

	//mysql_close($db);	

	return $val;

}

/**
 * returns the gateway config as an array
 */
function getGatewayConfig($gateway) {
	$db = getDatabaseConnection();
	$sql = "select name, value from " . XOOPS_DB_PREFIX .
		"_subscription_gateway_config where gateway = '$gateway'";
	mysql_query($sql, $db);

	$res = mysql_query($sql, $db);
	$config = array();

	while (list($name, $value) = @mysql_fetch_row($res)) {
		$config[$name] = $value;
	}

	return $config;

}
function cancelSubscription($uid, $subid) {
  $db = getDatabaseConnection();
	$sql = "update " . XOOPS_DB_PREFIX . "_subscription_user" .
		" set cancel = 'Y' where uid = $uid " .
		" and cancel = 'N' and subid = $subid ";
	mysql_query($sql, $db);
}
function getCurrencySymbol($cur) {
	switch ($cur) {
		case "USD":
			return "$";
			break;
		case "GBP":
			return "&pound;";
			break;
		case "EUR":
			return "&euro;";
			break;
		case "JPY":
			return "&yen;";
			break;
		case "CAD":
			return "CAD";
			break;
		default:
			return "$";
	}
}

?>
