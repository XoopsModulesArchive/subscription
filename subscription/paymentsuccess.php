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
	include_once("functions.php");

	global $xoopsUser, $xoopsDB, $xoopsConfig, $xoopsOption;

	if (!is_object($xoopsUser)) {
		redirect_header("index.php", 0, _NOPERM);
		exit();
	}
	$uname = $xoopsUser->getVar('uname','E'); 
	$xoopsOption['template_main'] = 'payment_success.html';

	include(XOOPS_ROOT_PATH.'/header.php');
	
	$xoopsTpl->assign('tid', $_GET['tid']);

	include(XOOPS_ROOT_PATH.'/footer.php');

?>


