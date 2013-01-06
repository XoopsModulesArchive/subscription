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
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}
if (!defined("SUB_DIR_NAME")) {
    die("SUB_DIR_NAME not defined");
}
include_once XOOPS_ROOT_PATH . "/modules/" . 
		SUB_DIR_NAME . "/include/paymentgateway.php";

include_once XOOPS_ROOT_PATH . "/modules/" . 
		SUB_DIR_NAME . "/include/paymentdata.php";
include_once XOOPS_ROOT_PATH . "/modules/" . 
		SUB_DIR_NAME . "/include/paymentresponse.php";

/**
 * base class
 */

class DefaultPaymentGateway extends PaymentGateway {

	function isDirect() {
		return true;
	}

	function submitPayment(&$details) {
		$this->logger->addExtra("DEFAULT", $details->toString());


		$response = $this->_processCard($details);
	
		return $response;
		
	}

	function _processCard($details) {
		if (TRUE) {
			return new PaymentResponse(0, "000-RES","SUCCESS",
					"Default Payment Gateway Info");
		}
		else {
			return new PaymentResponse(10, "010-RES","DECLINED",
					"Default Payment Gateway Info");
		}
	}

	function test() {
		$this->logger->addExtra("DEFAULT", "test");
	}
}


