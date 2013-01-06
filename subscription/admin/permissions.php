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

include '../../../include/cp_header.php';
include_once XOOPS_ROOT_PATH.'/class/xoopsform/grouppermform.php';
$module_id = $xoopsModule->getVar('mid');

$item_list = array('1'=>'Chat Host Detail');
$title = "Subscription Permissions";
$name = "Category Permissions";
$desc = "Category Permissions Description";
$form = new XoopsGroupPermForm($title, $module_id, $name,
		$desc);
foreach ($item_list as $item_id => $item_name) {
	$form->addItem($item_id, $item_name);
}
xoops_cp_header();
echo $form->render();
xoops_cp_footer();
?>

