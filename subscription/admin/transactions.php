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
    include_once dirname(__FILE__) . '/admin_header.php';
    include_once "../../../include/cp_header.php";
	include_once XOOPS_ROOT_PATH . "/class/template.php";
	include_once XOOPS_ROOT_PATH . "/class/xoopslists.php";
	include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
	include_once XOOPS_ROOT_PATH . "/class/pagenav.php";
	include_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') .
		"/include/lists.php";
	include_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') .
		"/include/formselectsubscription.php";

	xoops_cp_header();

    $aboutAdmin = new ModuleAdmin();
    echo $aboutAdmin->addNavigation('transactions.php');
	global $xoopsDB, $xoopsConfig;

//die("access denied - under development");
	
	$tpl = new XoopsTpl();
	
	if (isset($_POST['uid'])) {
		$uid = $_POST['uid'];
	}
	if (isset($_GET['uid'])) {
		$uid = $_GET['uid']; 
	}
	if (isset($_GET['uname'])) {
		$uname= $_GET['uname']; 
	}
	if (isset($_GET['start'])) {
		$startpos = $_GET['start'];
	}

	$sql = "select st.id, u.uid, u.uname, s.name, st.amount, st.responsecode, " .
		"st.transactiontype, st.transactiondate from " .
		$xoopsDB->prefix("users") . " u, " .
		$xoopsDB->prefix("subscription_transaction") . " st, " .
		$xoopsDB->prefix("subscription") . " s where " .
		"s.subid = st.subid and st.uid = u.uid ";
	$extra = '';
	if (isset($uname)) {
		$sql .= " and u.uname= '$uname'";
		$extra = " uname=$uname ";
	}
	if (isset($uid)) {
		$sql .= " and u.uid = $uid";
		$extra = " uid=$uid";
	}
	$sql .= " order by st.transactiondate desc";

	$ctsql = "select count(*) from " . 
			$xoopsDB->prefix("subscription_transaction");

	if (!isset($startpos)) {
		$startpos = 0;
	}
	$result = $xoopsDB->query($ctsql);
	list($txcount) = $xoopsDB->fetchRow($result);

	$result = $xoopsDB->query($sql,10, $startpos);
	while (list($txid, $uid,$uname, $subname, $amt, $rescode, $ttype, $date) =
			$xoopsDB->fetchRow($result)) {
		if ($rescode == '0') {
			$res = "Success (0)";
		}
		else {
			$res = "Failure ($rescode)";
		}
		switch ($ttype) {
			case "D":
				$txtype_desc = "Capture";
			  break;
			case "V":
				$txtype_desc = "Void";
				break;
			case "S":
				$txtype_desc = "Sale";
				break;
			case "A":
				$txtype_desc = "Authorization";
				break;
		}
		$tpl->append('transactions', array(
			'uid'=>$uid,
			'uname'=>$uname,
			'subname'=>$subname,
			'date'=>$date,
			'responsecode'=> $res,
			'txid'=>$txid,
			'amount'=>$amt,
			'txtype'=>$ttype,
			'rescode'=>$rescode,
			'txtype_desc'=>$txtype_desc));
	}
	$nav = new XoopsPageNav($txcount, 10, $startpos, 'start', $extra);

	$form = new XoopsThemeForm('Add Manual Transaction',
		"subform","addmanualtransaction.php");

	$name = new XoopsFormText("User Name","uname",10,20,"");
	$form->addElement($name, true);
	
	$realname = new XoopsFormText("Real Name", "name",20,50,"");
	$form->addElement($realname);
	
	$address = new XoopsFormText("Address","address",30,50,"");
	$form->addElement($address);

	$city = new XoopsFormText("City","city",20,50,"");
	$form->addElement($city);
	
	$state = new XoopsFormText("State","state",2,3,"");
	$form->addElement($state);
	
	$zipcode = new XoopsFormText("Zipcode", "zipcode",5,9,"");
	$form->addElement($zipcode);

	$checknumber = new XoopsFormText("Check Number", "checknumber", 5,6,"");
	$form->addElement($checknumber);

	$subscription = new XoopsFormSelectSubscription("Subscription","subid",1);
	$form->addElement($subscription);
	$amt = new XoopsFormText("Amount Paid", 'amount', 10,15,"");
	$form->addElement($amt, true);

	$exp = new XoopsFormText("Expires (YYYY-mm-dd hh:mm:ss)", 
		'expirationdate', 20, 25,"");
	$form->addElement($exp,true);

	$info = new XoopsFormText("Additional Info", 'info', 50,200,"");
	$form->addElement($info);

	$submit = new XoopsFormButton("", "submit"," Add ","submit");
	$form->addElement($submit);
	$tpl->assign('addform',$form->render());

	$tpl->assign("nav", $nav->renderNav());
	if (!empty($extra)) {
		$tpl->assign("showalllink", "true");
	}
	else {
		$tpl->assign("showalllink", "false");
	}
	$tpl->display(XOOPS_ROOT_PATH . "/modules/" .
		$xoopsModule->getVar('dirname') . "/templates/subscription_admin_tx.html");

	xoops_cp_footer();

?>
