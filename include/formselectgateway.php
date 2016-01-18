<?php

/**
 * lists of values
 */
include_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') .
		"/include/lists.php";
/**
 * base class
 */
include_once XOOPS_ROOT_PATH."/class/xoopsform/formselect.php";

class XoopsFormSelectGateway extends XoopsFormSelect
{
	function XoopsFormSelectGateway($caption, $name, $value=null,
			$size=1) {
		$this->XoopsFormSelect($caption, $name, $value, $size);

		$this->addOptionArray(SubscriptionLists::getGatewayList());
	
	}
}
?>
